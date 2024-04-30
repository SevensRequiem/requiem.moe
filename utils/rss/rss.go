package rss

import (
	"database/sql"
	"encoding/xml"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"os"

	_ "github.com/mattn/go-sqlite3"
)

type RSS struct {
	XMLName xml.Name `xml:"rss"`
	Version string   `xml:"version,attr"`
	Channel Channel  `xml:"channel"`
}

type Channel struct {
	Title       string `xml:"title"`
	Link        string `xml:"link"`
	Description string `xml:"description"`
	Items       []Item `xml:"item"`
}

type Item struct {
	Title       string `xml:"title"`
	Link        string `xml:"link"`
	Description string `xml:"description"`
	PubDate     string `xml:"pubDate"`
}

type Items struct {
	XMLName xml.Name `xml:"Items"`
	Items   []Item   `xml:"Item"`
}

var db *sql.DB

func InitDB() {
	var err error
	baseDir, _ := os.Getwd()
	db, err = sql.Open("sqlite3", baseDir+"/database/database.db")
	if err != nil {
		log.Fatal(err)
	}

	_, err = db.Exec(`CREATE TABLE IF NOT EXISTS rss (id INTEGER PRIMARY KEY, title TEXT, link TEXT, description TEXT, date TEXT)`)
	if err != nil {
		log.Fatal(err)
	}

	log.Println("Successfully connected to the rss database!")
}

func NewRSS() *RSS {
	log.Println("Creating a new RSS...")
	return &RSS{}
}

func (r *RSS) Fetch(url string) error {
	resp, err := http.Get(url)
	if err != nil {
		return err
	}
	defer resp.Body.Close()

	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return err
	}

	err = xml.Unmarshal(body, r)
	if err != nil {
		return err
	}

	return nil
}

func (r *RSS) Print() {
	fmt.Println("Title:", r.Channel.Title)
	fmt.Println("Link:", r.Channel.Link)
	fmt.Println("Description:", r.Channel.Description)
	for _, item := range r.Channel.Items {
		fmt.Println("Item Title:", item.Title)
		fmt.Println("Item Link:", item.Link)
		fmt.Println("Item Description:", item.Description)
		fmt.Println("Item PubDate:", item.PubDate)
	}
}

func ListRSS() Items {
	rows, err := db.Query("SELECT title, link, description, date FROM rss")
	if err != nil {
		log.Fatal(err)
	}
	defer rows.Close()

	var items []Item
	for rows.Next() {
		var title, link, description, date string
		err = rows.Scan(&title, &link, &description, &date)
		if err != nil {
			log.Fatal(err)
		}

		items = append(items, Item{title, link, description, date})
	}

	return Items{Items: items}
}

func InsertRSS(item Item) {
	_, err := db.Exec("INSERT INTO rss (title, link, description, date) VALUES (?, ?, ?, ?)", item.Title, item.Link, item.Description, item.PubDate)
	if err != nil {
		log.Fatal(err)
	}
}

func FetchLatestRSS() (Item, error) {
	rows, err := db.Query("SELECT title, link, description, date FROM rss")
	if err != nil {
		return Item{}, err
	}
	defer rows.Close()

	var item Item
	for rows.Next() {
		err = rows.Scan(&item.Title, &item.Link, &item.Description, &item.PubDate)
		if err != nil {
			return Item{}, err
		}
	}

	return item, nil
}
