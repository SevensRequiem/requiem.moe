package routes

import (
	"log"
	"net/http"
	"strconv"
	"strings"

	_ "github.com/jinzhu/gorm/dialects/sqlite"
	"github.com/labstack/echo-contrib/session"
	"github.com/labstack/echo/v4"
	analytics "github.com/tom-draper/api-analytics/analytics/go/echo"
	"requiem.moe/anime"
	"requiem.moe/auth"
	"requiem.moe/blog"
	"requiem.moe/chat"
	"requiem.moe/home"
	"requiem.moe/utils/hitcounter"
	"requiem.moe/utils/rss"
	"requiem.moe/utils/stats"
	"requiem.moe/utils/system"
)

func Routes(e *echo.Echo) {
	e.Use(analytics.Analytics("1425cce4-efc4-4957-9f6c-42577d2ce71d"))
	hc := hitcounter.NewHitCounter()

	e.GET("/ads.txt", func(c echo.Context) error {
		return c.File("static/ads.txt")
	})
	e.GET("/robots.txt", func(c echo.Context) error {
		return c.File("static/robots.txt")
	})
	e.GET("/sitemap.xml", func(c echo.Context) error {
		return c.File("static/sitemap.xml")
	})

	e.GET("/home", func(c echo.Context) error {
		hc.Hit(c.RealIP())
		return home.HomeHandler(c)
	})
	e.GET("/", func(c echo.Context) error {
		hc.Hit(c.RealIP())
		return home.HomeHandler(c)
	})
	e.GET("/about", func(c echo.Context) error {
		hc.Hit(c.RealIP())
		return home.AboutHandler(c)
	})

	e.GET("/contact", home.ContactHandler)
	e.GET("/services", home.ServicesHandler)
	e.GET("/portfolio", home.PortfolioHandler)

	e.GET("/api/fetchanime", anime.FetchAnime)

	e.GET("/blog", home.BlogHandler)
	e.GET("/blog/:id", blog.GetPost)
	e.POST("/blog", blog.CreatePost)
	e.PUT("/blog/:id", blog.UpdatePost)
	e.DELETE("/blog/:id", blog.DeletePost)
	e.GET("/blog/:id/edit", blog.EditPost)
	e.Static("/blog/images", "blog/images")
	e.Static("/assets", "assets")

	e.GET("/store", home.StoreHandler)

	e.GET("/admin", home.AdminHandler)
	e.GET("/visitors", home.VisitorsHandler)
	// discord oauth
	e.GET("/login", auth.LoginHandler)
	e.GET("/auth/callback", auth.CallbackHandler)
	e.GET("/logout", auth.LogoutHandler)
	e.GET("/profile", auth.ProfileHandler)

	//apis
	e.GET("/api/heartbeat", func(c echo.Context) error {
		return c.String(http.StatusOK, "ok")
	})

	e.GET("/admin/sysstats", system.GetSysStats)

	e.GET("/rss", func(c echo.Context) error {
		items := rss.ListRSS()
		return c.XML(http.StatusOK, items)
	})
	e.GET("/rss/latest", func(c echo.Context) error {
		items, err := rss.FetchLatestRSS()
		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}
		return c.XML(http.StatusOK, items)
	})
	chatdb, err := chat.InitDB()
	if err != nil {
		log.Fatal(err)
	}

	e.GET("/get-message/:id", func(c echo.Context) error {
		id, err := strconv.ParseInt(c.Param("id"), 10, 64)
		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}

		sess, err := session.Get("session", c)
		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}

		user, _ := sess.Values["user"].(auth.User)

		var chatMessage interface{}
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			chatMessage, err = chat.AdminGetMessageByID(chatdb, id)
		} else {
			chatMessage, err = chat.GetMessageByID(chatdb, id)
		}

		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}

		return c.JSON(http.StatusOK, chatMessage)
	})
	e.GET("/get-messages", func(c echo.Context) error {
		sess, err := session.Get("session", c)
		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}

		user, _ := sess.Values["user"].(auth.User)

		var chatMessages interface{}
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			chatMessages, err = chat.AdminGetMessages(chatdb)
		} else {
			chatMessages, err = chat.GetMessages(chatdb)
		}

		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}

		return c.JSON(http.StatusOK, chatMessages)
	})

	e.DELETE("/delete-message/:uuid", func(c echo.Context) error {
		sesh, err := session.Get("session", c)
		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}

		user, _ := sesh.Values["user"].(auth.User)

		if !strings.Contains(user.Groups, "admin") {
			return c.String(http.StatusUnauthorized, "Unauthorized")
		}

		uuid := c.Param("uuid")
		err = chat.DeleteMessage(chatdb, uuid)
		if err != nil {
			return c.String(http.StatusInternalServerError, err.Error())
		}

		return c.String(http.StatusOK, "Message deleted")

	})

	e.POST("/send-message", func(c echo.Context) error {
		// Extract parameters from the request
		session, err := session.Get("session", c)
		if err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": "Failed to get session: " + err.Error()})
		}
		username := c.FormValue("username")
		message := c.FormValue("message")

		var True_User string
		if session.Values["user"] == nil {
			True_User = "guest"
		} else {
			True_User = session.Values["user"].(auth.User).Username
		}
		ip := c.RealIP()

		// Call InsertMessage
		err = chat.InsertMessage(chatdb, username, message, True_User, ip)
		if err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": "Failed to insert message: " + err.Error()})
		}

		return c.JSON(http.StatusOK, map[string]string{"status": "message sent"})
	})
	e.GET("/api/stats", func(c echo.Context) error {
		stats := stats.GetStats(c)
		return stats
	})

	e.GET("/api/domains/status", func(c echo.Context) error {
		return stats.GetAllDomainStatus(c)
	})

	e.GET("/api/check-domain", func(c echo.Context) error {
		return stats.CheckDomain(c)
	})
}
