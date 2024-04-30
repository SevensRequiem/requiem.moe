package bans

import (
	"database/sql"
	"io"
	"log"
	"net/http"
	"os"
	"strings"
	"time"

	"github.com/labstack/echo/v4"
	_ "github.com/mattn/go-sqlite3"
)

type Ban struct {
	ID          int64     `json:"id"`
	Username    string    `json:"username"`
	Reason      string    `json:"reason"`
	Admin       string    `json:"admin"`
	DateCreated time.Time `json:"date_created"`
	IP          string    `json:"ip"`
}

var db *sql.DB

func InitDB() {
	baseDir, err := os.Getwd()
	if err != nil {
		log.Fatalf("failed to get working directory: %v", err)
	}

	db, err = sql.Open("sqlite3", baseDir+"/database/bans.db")
	if err != nil {
		log.Fatalf("failed to open db: %v", err)
	}

	sqlStmt := `
    create table if not exists bans (
        id integer not null primary key, 
        username text, 
        reason text, 
        admin text,
        date_created datetime default current_timestamp,
        ip text
    );
    `
	_, err = db.Exec(sqlStmt)
	if err != nil {
		log.Fatalf("failed to create table: %v", err)
	}
	log.Println("successfully initialized bans DB!")
}

var banLines []string

func Init() error {
	baseDir, err := os.Getwd()
	if err != nil {
		log.Println("Failed to get working directory:", err)
		return err
	}

	dirPath := baseDir + "/bans/dictionary"
	dir, err := os.Open(dirPath)
	if err != nil {
		log.Println("Failed to open directory:", err)
		return err
	}
	defer dir.Close()

	// Read all the files in the directory
	files, err := dir.Readdir(-1)
	if err != nil {
		log.Println("Failed to read directory:", err)
		return err
	}

	// Check each file
	for _, file := range files {
		// Skip directories
		if file.IsDir() {
			continue
		}

		// Open the file
		f, err := os.Open(dirPath + "/" + file.Name())
		if err != nil {
			log.Println("Failed to open file:", err)
			continue
		}

		// Read the file
		content, err := io.ReadAll(f)
		f.Close()
		if err != nil {
			log.Println("Failed to read file:", err)
			continue
		}

		// Split the content into lines and add them to banLines
		lines := strings.Split(string(content), "\n")
		banLines = append(banLines, lines...)

		// Log the number of lines read
		log.Printf("Read %d lines from file: %s\n", len(lines), file.Name())
	}
	rows, err := db.Query("SELECT * FROM bans")
	if err != nil {
		return err
	}
	defer rows.Close()

	bans := make([]*Ban, 0)
	for rows.Next() {
		ban := new(Ban)
		err := rows.Scan(&ban.ID, &ban.Username, &ban.Reason, &ban.Admin, &ban.DateCreated, &ban.IP)
		if err != nil {
			return err
		}
		bans = append(bans, ban)
	}

	// load bans to memory for faster access
	for _, ban := range bans {
		bannedIPs[ban.IP] = true
	}

	return nil
}

func CreateBan(c echo.Context, ban *Ban) error {
	stmt, err := db.Prepare("INSERT INTO bans (username, reason, admin, ip) VALUES (?, ?, ?, ?)")
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}
	defer stmt.Close()

	_, err = stmt.Exec(ban.Username, ban.Reason, ban.Admin, ban.IP)
	if err != nil {
		return echo.NewHTTPError(http.StatusInternalServerError, "database error")
	}

	return nil
}

var bannedIPs = make(map[string]bool)

func HoneyPot(c echo.Context) error {
	// Get the request path and IP
	requestPath := c.Request().URL.Path
	ip := c.RealIP()

	// If the IP is in the bannedIPs map, return a 403
	if bannedIPs[ip] {
		return c.HTML(http.StatusForbidden, "Forbidden")
	}

	// Check if the request path is in banLines
	for _, line := range banLines {
		if line == requestPath {
			// Create a new Ban object
			ban := &Ban{
				Username: "HoneyPot",
				Reason:   requestPath,
				Admin:    "system",
				IP:       ip,
			}

			// Call CreateBan with the new Ban object
			if err := CreateBan(c, ban); err != nil {
				return err
			}

			// Add the IP to the bannedIPs map
			bannedIPs[ip] = true

			//return a 403
			return c.HTML(http.StatusForbidden, "Forbidden")
		}
	}

	return nil
}

func CheckBan(c echo.Context) error {
	ip := c.RealIP()
	if bannedIPs[ip] {
		return echo.ErrForbidden
	}

	return nil
}

func HoneyPotMiddleware(next echo.HandlerFunc) echo.HandlerFunc {
	return func(c echo.Context) error {
		// Get the path from the request URL
		fullURL := c.Request().URL.Path
		ip := c.RealIP()

		// If the IP is in the bannedIPs map, return a 403
		if bannedIPs[ip] {
			return c.HTML(http.StatusForbidden, "Forbidden")
		}

		// Check if the path of the full URL is in banLines
		for _, line := range banLines {
			// Add a / to the start of the line
			line = "/" + line

			// Skip if the URL is "/"
			if fullURL == "/" {
				continue
			}

			// Compare the paths
			if fullURL == line {
				// Create a new Ban object
				ban := &Ban{
					Username: "HoneyPot",
					Reason:   fullURL,
					Admin:    "system",
					IP:       ip,
				}

				// Call CreateBan with the new Ban object
				if err := CreateBan(c, ban); err != nil {
					return err
				}

				// Add the IP to the bannedIPs map
				bannedIPs[ip] = true

				//return a 403
				return c.HTML(http.StatusForbidden, "Forbidden")
			}
		}

		// Call the next middleware/handler in the chain
		return next(c)
	}
}
