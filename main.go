package main

import (
	"fmt"
	"html/template"
	"io"
	"log"
	"net/http"
	"os"
	"time"

	"encoding/gob"

	"github.com/gorilla/sessions"
	"github.com/jinzhu/gorm"
	_ "github.com/jinzhu/gorm/dialects/sqlite"
	"github.com/labstack/echo-contrib/session"
	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"

	"requiem.moe/anime"
	"requiem.moe/auth"
	"requiem.moe/bans"
	"requiem.moe/blog"
	"requiem.moe/chat"
	"requiem.moe/home"
	"requiem.moe/routes"
	"requiem.moe/utils/config"
	"requiem.moe/utils/hitcounter"
	"requiem.moe/utils/rss"
	"requiem.moe/utils/schedule"
	"requiem.moe/utils/stats"
	"requiem.moe/utils/visitors"
)

type (
	TemplateRenderer struct {
		templates *template.Template
	}
)

var db *gorm.DB
var renderer *TemplateRenderer

var store = sessions.NewCookieStore([]byte("StWjEd5c41a0DlHA0faP0mTSvdZXyPMaG4dhfafM7WQaPQBW28bgg3gpYlSkxdLU"))

func init() {
	renderer = &TemplateRenderer{
		templates: template.Must(template.ParseGlob("views/*.html")),
	}
	gob.Register(map[string]interface{}{})
}

func (t *TemplateRenderer) Load() error {
	return nil
}

func (t *TemplateRenderer) Render(w io.Writer, name string, data interface{}, c echo.Context) error {
	return t.templates.ExecuteTemplate(w, name, data)
}

func main() {
	cfg, err := config.NewConfig()
	db, err = gorm.Open("sqlite3", "database/database.db")
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	anime.InitAnimeDB()
	anime.UpdateAnime()
	fmt.Println("=====================================")
	blog.InitDB()
	fmt.Println("=====================================")
	hitcounter.InitDB()
	fmt.Println("=====================================")
	auth.InitDB()
	fmt.Println("=====================================")
	bans.InitDB()
	bans.Init()
	fmt.Println("=====================================")
	chat.InitDB()
	fmt.Println("=====================================")
	visitors.InitDB()
	fmt.Println("=====================================")
	rss.InitDB()
	fmt.Println("=====================================")
	stats.InitDB()
	stats.UpdateStats()

	e := echo.New()
	s := schedule.NewScheduler()

	s.ScheduleTask(schedule.Task{
		Action:   anime.UpdateAnime,
		Duration: 12 * time.Hour,
	})
	s.ScheduleTask(schedule.Task{
		Action: func() {
			err := stats.UpdateStats()
			if err != nil {
				log.Printf("Error updating stats: %v", err)
			}
		},
		Duration: 15 * time.Minute,
	})

	s.Run()

	e.Use(middleware.LoggerWithConfig(middleware.LoggerConfig{
		Format: "${id} ${time_rfc3339} ${remote_ip} > ${method} > ${uri} > ${status} ${latency_human}\n",
	}))

	e.Use(middleware.Recover())
	e.Use(middleware.CORSWithConfig(middleware.CORSConfig{
		AllowOrigins: []string{
			"http://localhost:8080",
			"https://localhost:8080",
			"https://requiem.moe",
			"https://www.requiem.moe",
			"https://requiem.moe:8080",
			"https://www.requiem.moe:8080",
		},
		AllowMethods: []string{http.MethodGet, http.MethodPut, http.MethodPost, http.MethodDelete},
	}))
	e.Use(middleware.Gzip())
	e.Use(middleware.Secure())
	e.Use(middleware.CSRF())

	// Open a file for writing logs
	accesslog, err := os.OpenFile("access.log", os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0666)
	if err != nil {
		e.Logger.Fatal(err)
	}
	e.Use(middleware.LoggerWithConfig(middleware.LoggerConfig{
		Format: "${id} ${time_rfc3339} ${remote_ip} > ${method} > ${uri} > ${status} ${latency_human}\n",
		Output: accesslog, // Set the Output to the log file
	}))

	e.Use(func(next echo.HandlerFunc) echo.HandlerFunc {
		return func(c echo.Context) error {
			if err := bans.HoneyPot(c); err != nil {
				return err
			}
			if err := bans.CheckBan(c); err != nil {
				return err
			}
			return next(c)
		}
	})
	//bans.go  honeypot middleware

	// session middleware
	e.Use(session.Middleware(store))
	//e.Use(middleware.RateLimiter(middleware.NewRateLimiterMemoryStore(30)))
	e.Renderer = renderer
	e.HTTPErrorHandler = home.ErrorHandler
	routes.Routes(e)
	e.Use(bans.HoneyPotMiddleware)

	chat.TestEnv()

	e.Start(":" + cfg.Port)
}
