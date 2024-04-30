package blog

import (
	"crypto/rand"
	"database/sql"
	"fmt"
	_ "image/gif"
	"io"
	"net/http"
	"os"
	"path/filepath"
	"time"

	"github.com/labstack/echo/v4"
	_ "github.com/mattn/go-sqlite3"

	"requiem.moe/utils/rss"
)

type BlogPost struct {
	ID          int64     `json:"id"`
	Image       string    `json:"image"`
	Post        string    `json:"post"`
	Title       string    `json:"title"`
	Tags        string    `json:"tags"`
	Hex         string    `json:"hex"`
	Author      string    `json:"author"`
	Quote       string    `json:"quote"`
	DateCreated time.Time `json:"date_created"`
}
type Comments struct {
	ID          int64     `json:"id"`
	PostID      int64     `json:"post_id"`
	Comment     string    `json:"comment"`
	Author      string    `json:"author"`
	DateCreated time.Time `json:"date_created"`
	IP          string    `json:"ip"`
}

var db *sql.DB

func initDB() error {
	var err error
	baseDir, err := os.Getwd()
	if err != nil {
		fmt.Println("Error getting base directory:", err)
		return err
	}
	fmt.Println("Base directory:", baseDir)

	dbPath := filepath.Join(baseDir, "database", "blog.db")
	db, err = sql.Open("sqlite3", dbPath)
	if err != nil {
		return err
	}

	// In the initDB function
	_, err = db.Exec(`CREATE TABLE IF NOT EXISTS blog_posts (
		id INTEGER PRIMARY KEY,
		image TEXT,
		post TEXT,
		title TEXT,
		tags TEXT,
		hex TEXT,
		author TEXT,
		quote TEXT,
		date_created DATETIME DEFAULT CURRENT_TIMESTAMP
	)`)
	if err != nil {
		fmt.Println("Error creating blog_posts table:", err)
		return err
	}

	_, err = db.Exec(`CREATE TABLE IF NOT EXISTS comments (
		id INTEGER PRIMARY KEY,
		post_id INTEGER,
		comment TEXT,
		author TEXT,
		date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
		ip TEXT
	)`)
	if err != nil {
		fmt.Println("Error creating comments table:", err)
		return err
	}

	return nil

}

func InitDB() {
	err := initDB()
	if err != nil {
		fmt.Println("Error initializing database:", err)
		return
	}
}

func CreatePost(c echo.Context) error {
	post := new(BlogPost)
	if err := c.Bind(post); err != nil {
		return echo.NewHTTPError(http.StatusBadRequest, "invalid request")
	}

	title := c.FormValue("title")
	author := c.FormValue("author")
	postContent := c.FormValue("post")
	tags := c.FormValue("tags")
	hex := c.FormValue("hex")
	quote := c.FormValue("quote")

	file, err := c.FormFile("image")

	var imagePath string

	if err != nil {
		imagePath = "null"
	} else {
		src, err := file.Open()
		if err != nil {
			return echo.NewHTTPError(http.StatusInternalServerError, "unable to open image")
		}
		defer src.Close()

		// Generate a random filename
		filename := make([]byte, 16)
		if _, err := rand.Read(filename); err != nil {
			return echo.NewHTTPError(http.StatusInternalServerError, "unable to generate filename")
		}
		filenameStr := fmt.Sprintf("%x", filename)

		// Determine file extension
		ext := filepath.Ext(file.Filename)
		imagePath = filepath.Join("blog", "images", filenameStr+ext)

		// Create and save the image
		dst, err := os.Create(imagePath)
		if err != nil {
			return echo.NewHTTPError(http.StatusInternalServerError, "unable to create image file")
		}
		defer dst.Close()

		if _, err := io.Copy(dst, src); err != nil {
			return echo.NewHTTPError(http.StatusInternalServerError, "unable to save image file")
		}
	}

	stmt, err := db.Prepare("INSERT INTO blog_posts (title, author, post, image, tags, quote) VALUES (?, ?, ?, ?, ?, ?)")
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}
	defer stmt.Close()

	_, err = stmt.Exec(title, author, postContent, imagePath, tags, quote)
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}

	// get the id of the post
	row := db.QueryRow("SELECT id FROM blog_posts WHERE title = ? AND author = ? AND post = ? AND image = ? AND tags = ? AND hex = ? AND quote = ?", title, author, postContent, imagePath, tags, hex, quote)
	id := 0
	err = row.Scan(&id)
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}

	// insert into rss
	item := rss.Item{
		Title:       title,
		Link:        "https://requiem.moe/blog?id=" + fmt.Sprint(id),
		Description: postContent,
		PubDate:     time.Now().Format(time.RFC1123Z),
	}
	rss.InsertRSS(item)

	return c.JSON(http.StatusCreated, post)
}

func EditPost(c echo.Context) error {
	id := c.Param("id")
	post := new(BlogPost)
	if err := c.Bind(post); err != nil {
		return echo.NewHTTPError(http.StatusBadRequest, "invalid request")
	}

	stmt, err := db.Prepare("UPDATE blog_posts SET post = ?, title = ?, tags = ?, hex = ?, author = ? WHERE id = ?")
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}
	defer stmt.Close()

	_, err = stmt.Exec(post.Post, post.Title, post.Tags, post.Hex, post.Author, id)
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}

	return c.JSON(http.StatusOK, post)
}

func GetPosts(c echo.Context) error {
	rows, err := db.Query("SELECT * FROM blog_posts")
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "Failed to query database: "+err.Error())
	}
	defer rows.Close()

	posts := make([]BlogPost, 0)
	for rows.Next() {
		post := BlogPost{}
		var timestamp int64
		err := rows.Scan(&post.ID, &post.Image, &post.Post, &post.Title, &post.Tags, &post.Hex, &post.Author, &post.Quote, &timestamp)
		if err != nil {
			return echo.NewHTTPError(http.StatusInternalServerError, "Failed to scan row: "+err.Error())
		}
		post.DateCreated = time.Unix(timestamp, 0)
		posts = append(posts, post)
	}

	return c.JSON(http.StatusOK, posts)
}

func GetPost(c echo.Context) error {
	id := c.Param("id")

	row := db.QueryRow("SELECT * FROM blog_posts WHERE id = ?", id)
	post := BlogPost{}
	var timestamp int64
	err := row.Scan(&post.ID, &post.Image, &post.Post, &post.Title, &post.Tags, &post.Hex, &post.Author, &post.Quote, &timestamp)
	if err != nil {
		return echo.NewHTTPError(http.StatusNotFound, "post not found"+err.Error())
	}
	post.DateCreated = time.Unix(timestamp, 0)

	return c.JSON(http.StatusOK, post)
}
func UpdatePost(c echo.Context) error {
	id := c.Param("id")
	post := new(BlogPost)
	if err := c.Bind(post); err != nil {
		return echo.NewHTTPError(http.StatusBadRequest, "invalid request")
	}

	stmt, err := db.Prepare("UPDATE blog_posts SET image = ?, post = ?, title = ?, tags = ?, author = ? WHERE id = ?")
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}
	defer stmt.Close()

	_, err = stmt.Exec(post.Image, post.Post, post.Title, post.Tags, post.Author, id)
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}

	return c.JSON(http.StatusOK, post)
}

func DeletePost(c echo.Context) error {
	id := c.Param("id")

	stmt, err := db.Prepare("DELETE FROM blog_posts WHERE id = ?")
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}
	defer stmt.Close()

	_, err = stmt.Exec(id)
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}

	return c.NoContent(http.StatusNoContent)
}

func GetTotalPosts(c echo.Context) error {
	var totalPosts int
	err := db.QueryRow("SELECT COUNT(*) FROM blog_posts").Scan(&totalPosts)
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "Failed to get total posts: "+err.Error())
	}

	return c.JSON(http.StatusOK, totalPosts)
}

func SortByTag(c echo.Context) error {
	tag := c.Param("tag")

	rows, err := db.Query("SELECT * FROM blog_posts WHERE tags LIKE ?", "%"+tag+"%")
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "Failed to query database: "+err.Error())
	}
	defer rows.Close()

	posts := make([]BlogPost, 0)
	for rows.Next() {
		post := BlogPost{}
		var timestamp int64
		err := rows.Scan(&post.ID, &post.Image, &post.Post, &post.Title, &post.Tags, &post.Hex, &post.Author, &post.Quote, &timestamp)
		if err != nil {
			return echo.NewHTTPError(http.StatusInternalServerError, "Failed to scan row: "+err.Error())
		}
		post.DateCreated = time.Unix(timestamp, 0)
		posts = append(posts, post)
	}

	return c.JSON(http.StatusOK, posts)
}
