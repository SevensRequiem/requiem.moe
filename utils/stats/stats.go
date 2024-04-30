package stats

import (
	"database/sql"
	"fmt"
	"os"
	"path/filepath"

	"net/http"

	"github.com/labstack/echo/v4"
	_ "github.com/mattn/go-sqlite3"

	"requiem.moe/auth"
	"requiem.moe/chat"
	"requiem.moe/utils/domains"
	"requiem.moe/utils/hitcounter"
	"requiem.moe/utils/system"
)

var db *sql.DB

type Stat struct {
	Hits     int
	Messages int
	Users    int
	Hdd      int
	Domains  int
}

func InitDB() {
	var err error
	baseDir, _ := os.Getwd()
	db, err = sql.Open("sqlite3", filepath.Join(baseDir, "database", "stats.db"))
	if err != nil {
		fmt.Println(err)
	}

	_, err = db.Exec(`CREATE TABLE IF NOT EXISTS stats (id INTEGER PRIMARY KEY, hits INTEGER, messages INTEGER, users INTEGER, hdd INTEGER, domains INTEGER)`)
	if err != nil {
		fmt.Println(err)
	}

	// Ensure there is a row to increment
	_, err = db.Exec(`INSERT INTO stats (id, hits, messages, users, hdd, domains) SELECT 1, 0, 0, 0, 0, 0 WHERE NOT EXISTS (SELECT 1 FROM stats WHERE id = 1)`)
	if err != nil {
		fmt.Println(err)
	}

	fmt.Println("Successfully connected to the stats database!")
}

func GetStats(c echo.Context) error {
	var stat Stat
	err := db.QueryRow(`SELECT hits, messages, users, hdd, domains FROM stats WHERE id = 1`).Scan(&stat.Hits, &stat.Messages, &stat.Users, &stat.Hdd, &stat.Domains)
	if err != nil {
		fmt.Println(err)
	}

	return c.JSON(http.StatusOK, stat)
}

func UpdateStats() error {
	hc := hitcounter.NewHitCounter()
	hits := hc.GetHits()

	_, err := db.Exec(`UPDATE stats SET hits = ?, messages = ?, users = ?, hdd = ?, domains = ? WHERE id = 1`, hits, chat.GetTotalMessages(), auth.GetTotalUsers(), system.GetBaseDirSize(), domains.DomainCount())
	if err != nil {
		return err
	}

	return nil
}

// check status of domain from query, return 200 or 400 depending on if site is up or not
func CheckDomain(c echo.Context) error {
	domain := c.QueryParam("domain")
	if domain == "" {
		return c.JSON(http.StatusBadRequest, "No domain provided")
	}

	// Send a GET request to the domain
	resp, err := http.Get(domain)
	if err != nil {
		return c.JSON(http.StatusBadRequest, "Domain is down")
	}
	defer resp.Body.Close()

	// Return the exact status code received from the domain
	return c.JSON(resp.StatusCode, resp.Status)
}

// return a list of domains

func GetDomains(c echo.Context) error {
	baseDir, err := os.Getwd()
	if err != nil {
		return fmt.Errorf("failed to get working directory: %v", err)
	}

	db, err := sql.Open("sqlite3", filepath.Join(baseDir, "database", "domains.db"))
	if err != nil {
		return fmt.Errorf("failed to open database: %v", err)
	}
	defer db.Close()

	rows, err := db.Query("SELECT domain FROM domains")
	if err != nil {
		return fmt.Errorf("failed to query database: %v", err)
	}
	defer rows.Close()

	var domains []string
	for rows.Next() {
		var domain string
		err = rows.Scan(&domain)
		if err != nil {
			return fmt.Errorf("failed to scan row: %v", err)
		}
		domains = append(domains, domain)
	}

	return c.JSON(http.StatusOK, domains)
}

func GetDomainStatus(c echo.Context) error {
	domain := c.QueryParam("domain")
	if domain == "" {
		return c.JSON(http.StatusBadRequest, "No domain provided")
	}

	// Send a GET request to the domain
	resp, err := http.Get(domain)
	if err != nil {
		return c.JSON(http.StatusBadRequest, "Domain is down")
	}
	defer resp.Body.Close()

	// Return the exact status code received from the domain
	return c.JSON(resp.StatusCode, resp.Status)
}

func GetAllDomainStatus(c echo.Context) error {
	baseDir, err := os.Getwd()
	if err != nil {
		return fmt.Errorf("failed to get working directory: %v", err)
	}

	db, err := sql.Open("sqlite3", filepath.Join(baseDir, "database", "domains.db"))
	if err != nil {
		return fmt.Errorf("failed to open database: %v", err)
	}
	defer db.Close()

	rows, err := db.Query("SELECT domain, status FROM domains")
	if err != nil {
		return fmt.Errorf("failed to query database: %v", err)
	}
	defer rows.Close()

	var heartbeats []Heartbeat
	for rows.Next() {
		var heartbeat Heartbeat
		err = rows.Scan(&heartbeat.Domain, &heartbeat.Status)
		if err != nil {
			return fmt.Errorf("failed to scan row: %v", err)
		}
		heartbeats = append(heartbeats, heartbeat)
	}

	return c.JSON(http.StatusOK, heartbeats)
}

type Heartbeat struct {
	Domain string `json:"domain"`
	Status string `json:"status"`
}

func UpdateHeartbeat() error {
	baseDir, err := os.Getwd()
	if err != nil {
		return fmt.Errorf("failed to get working directory: %v", err)
	}

	db, err := sql.Open("sqlite3", filepath.Join(baseDir, "database", "domains.db"))
	if err != nil {
		return fmt.Errorf("failed to open database: %v", err)
	}
	defer db.Close()

	rows, err := db.Query("SELECT domain FROM domains")
	if err != nil {
		return fmt.Errorf("failed to query database: %v", err)
	}
	defer rows.Close()

	for rows.Next() {
		var domain string
		err = rows.Scan(&domain)
		if err != nil {
			return fmt.Errorf("failed to scan row: %v", err)
		}

		resp, err := http.Get("https://" + domain)
		var status string
		if err != nil {
			status = "Domain is down"
		} else {
			status = resp.Status
		}

		_, err = db.Exec("UPDATE domains SET status = ? WHERE domain = ?", status, domain)
		if err != nil {
			return fmt.Errorf("failed to update database: %v", err)
		}
	}

	return nil
}
