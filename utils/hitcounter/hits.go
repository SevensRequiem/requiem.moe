package hitcounter

import (
	"database/sql"
	"log"
	"os"
	"sync"
	"time"

	_ "github.com/mattn/go-sqlite3"
)

type HitCounter struct {
	hits  int
	mutex sync.Mutex
	cache map[string]time.Time
}

var db *sql.DB

func InitDB() {
	var err error
	baseDir, _ := os.Getwd()
	db, err = sql.Open("sqlite3", baseDir+"/database/database.db")
	if err != nil {
		log.Fatal(err)
	}

	_, err = db.Exec(`CREATE TABLE IF NOT EXISTS hits (id INTEGER PRIMARY KEY, count INTEGER)`)
	if err != nil {
		log.Fatal(err)
	}

	// Ensure there is a row to increment
	_, err = db.Exec(`INSERT INTO hits (id, count) SELECT 1, 0 WHERE NOT EXISTS (SELECT 1 FROM hits WHERE id = 1)`)
	if err != nil {
		log.Fatal(err)
	}

	log.Println("Successfully connected to the hits database!")
}

func NewHitCounter() *HitCounter {
	log.Println("Creating a new HitCounter...")
	return &HitCounter{
		cache: make(map[string]time.Time),
	}
}

func (h *HitCounter) Hit(ip string) {
	h.mutex.Lock()
	defer h.mutex.Unlock()

	if hitTime, ok := h.cache[ip]; ok && time.Since(hitTime).Hours() < 2 {
		log.Printf("IP %s hit less than 2 hours ago, not counting this hit.\n", ip)
		return
	}

	// Increment the count in the first row
	_, err := db.Exec(`UPDATE hits SET count = count + 1 WHERE id = 1`)
	if err != nil {
		log.Fatal(err)
	}

	log.Printf("Counted a hit from IP %s.\n", ip)
	h.cache[ip] = time.Now()
	h.hits++
}

func (h *HitCounter) GetHits() int {
	h.mutex.Lock()
	defer h.mutex.Unlock()

	var count int
	err := db.QueryRow(`SELECT SUM(count) FROM hits`).Scan(&count)
	if err != nil {
		log.Fatal(err)
	}

	log.Printf("Retrieved hit count: %d.\n", count)
	return count
}
