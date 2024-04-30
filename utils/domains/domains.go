package domains

import (
	"database/sql"
	"log"
	"os"
	"path/filepath"

	_ "github.com/mattn/go-sqlite3"
)

var db *sql.DB

type Domain struct {
	ID     int    `json:"id"`
	Domain string `json:"domain"`
	Status string `json:"status"`
}

func InitDB() {
	var err error
	baseDir, _ := os.Getwd()
	db, err = sql.Open("sqlite3", filepath.Join(baseDir, "database", "domains.db"))
	if err != nil {
		log.Fatal(err)
	}

	_, err = db.Exec(`CREATE TABLE IF NOT EXISTS domains (id INTEGER PRIMARY KEY, domain TEXT, status TEXT)`)
	if err != nil {
		log.Fatal(err)
	}

	log.Println("Successfully connected to the domains database!")
}

func AddDomain(domain string) {
	_, err := db.Exec(`INSERT INTO domains (domain, status) VALUES (?, ?)`, domain, "active")
	if err != nil {
		log.Fatal(err)
	}
}

func GetDomains(db *sql.DB) []string {
	rows, err := db.Query(`SELECT domain FROM domains`)
	if err != nil {
		log.Fatal(err)
	}
	defer rows.Close()

	var domains []string
	for rows.Next() {
		var domain string
		err := rows.Scan(&domain)
		if err != nil {
			log.Fatal(err)
		}
		domains = append(domains, domain)
	}

	return domains
}

func DeleteDomain(domain string) {
	_, err := db.Exec(`DELETE FROM domains WHERE domain = ?`, domain)
	if err != nil {
		log.Fatal(err)
	}
}

func DomainCount() int {
	baseDir, err := os.Getwd()
	if err != nil {
		log.Fatal(err)
	}

	db, err := sql.Open("sqlite3", filepath.Join(baseDir, "database", "domains.db"))
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	var count int
	err = db.QueryRow("SELECT COUNT(*) FROM domains").Scan(&count)
	if err != nil {
		log.Fatal(err)
	}

	return count
}
