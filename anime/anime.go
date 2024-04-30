package anime

import (
	"bytes"
	"database/sql"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"
	"os"
	"path/filepath"
	"time"

	"github.com/labstack/echo/v4"
	_ "github.com/mattn/go-sqlite3"
)

type Anime struct {
	ID            int    `db:"id"`
	OrderID       int    `db:"order_id"`
	Title         string `db:"title"`
	Episode       string `db:"episode"`
	Season        string `db:"season"`
	Year          int    `db:"year"`
	Studio        string `db:"studio"`
	WatchStatus   string `db:"watch_status"`
	DateCreated   string `db:"date_created"`
	DateCompleted string `db:"date_completed"`
	CoverImage    string `db:"cover_image"`
}

type Variables struct {
	Types  []string `json:"types"`
	UserID int      `json:"userid"`
}

type Query struct {
	Query     string    `json:"query"`
	Variables Variables `json:"variables"`
}

type Response struct {
	Data PageData `json:"data"`
}

type PageData struct {
	Page ActivitiesPage `json:"page"`
}

type ActivitiesPage struct {
	Activities []Activity `json:"activities"`
}

type Activity struct {
	Media     Media  `json:"media"`
	Status    string `json:"status"`
	Progress  string `json:"progress"` // Change this to string
	CreatedAt int64  `json:"createdAt"`
}

type Media struct {
	ID          int     `json:"id"`
	Title       Title   `json:"title"`
	Studios     Studios `json:"studios"`    // Add this line
	SeasonYear  int     `json:"seasonYear"` // Add this line
	Season      string  `json:"season"`
	Episode     string  `json:"episode"`
	CoverImage  Image   `json:"coverImage"`
	Description string  `json:"description"`
}

type Studios struct {
	Nodes []Studio `json:"nodes"`
}

type Studio struct {
	Name string `json:"name"`
}

type Title struct {
	Romaji string `json:"romaji"`
}

type Image struct {
	Medium string `json:"medium"`
}

var db *sql.DB

func InitAnimeDB() error {
	var err error
	baseDir, err := os.Getwd()
	if err != nil {
		fmt.Println("Error getting base directory:", err)
		return err
	}

	dbPath := filepath.Join(baseDir, "database", "anime.db")

	// Delete the existing database file if it exists
	if _, err := os.Stat(dbPath); err == nil {
		err = os.Remove(dbPath)
		if err != nil {
			fmt.Println("Error deleting existing database:", err)
			return err
		}
	}

	db, err = sql.Open("sqlite3", dbPath)
	if err != nil {
		fmt.Println("Error opening database:", err)
		return err
	}

	_, err = db.Exec(`CREATE TABLE IF NOT EXISTS anime (
		order_id INTEGER PRIMARY KEY AUTOINCREMENT,
		id INTEGER UNIQUE,
		title TEXT,
		episode TEXT,
		season INTEGER,
		year INTEGER,
		studio TEXT,
		watch_status TEXT,
		date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
		date_completed TEXT,
		cover_image TEXT
	)`)
	if err != nil {
		return fmt.Errorf("failed to create anime table: %w", err)
	}
	return nil
}

func FetchAnime(c echo.Context) error {
	rows, err := db.Query("SELECT * FROM anime ORDER BY order_id ASC")
	if err != nil {
		fmt.Println("Error fetching anime:", err)
		return echo.NewHTTPError(http.StatusInternalServerError, "internal server error")
	}
	defer rows.Close()

	animeList := []Anime{}
	for rows.Next() {
		var a Anime
		err := rows.Scan(&a.OrderID, &a.ID, &a.Title, &a.Episode, &a.Season, &a.Year, &a.Studio, &a.WatchStatus, &a.DateCreated, &a.DateCompleted, &a.CoverImage)
		if err != nil {
			fmt.Println("Error scanning anime:", err)
			return echo.NewHTTPError(http.StatusInternalServerError, "internal server error")
		}
		animeList = append(animeList, a)
	}
	return c.JSON(http.StatusOK, animeList)
}

// update anime every hour from anilist
func UpdateAnime() {
	_, err := db.Exec("DELETE FROM anime")
	if err != nil {
		fmt.Println("Error purging anime table:", err)
		return
	}
	url := "https://graphql.anilist.co"
	query := `query ($types: [ActivityType], $userid: Int) {
		Page {
			activities(type_in: $types, userId: $userid, sort: ID_DESC) {
				... on ListActivity {
					media {
						id
						title {
							romaji
						}
						coverImage {
							medium
						}
						season
						seasonYear
						studios {
							nodes {
								name
							}
						}
					}
					status
					progress
					createdAt
				}
			}
		}
	}`
	variables := Variables{
		Types:  []string{"ANIME_LIST", "MANGA_LIST"},
		UserID: 533005, // SET TO YOUR USER_ID
	}
	body := Query{
		Query:     query,
		Variables: variables,
	}
	bodyBytes, err := json.Marshal(body)
	if err != nil {
		fmt.Println("Error marshalling body:", err)
		return
	}
	resp, err := http.Post(url, "application/json", bytes.NewBuffer(bodyBytes))
	if err != nil {
		fmt.Println("Error making request:", err)
		return
	}
	defer resp.Body.Close()
	bodyRespBytes, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		fmt.Println("Error reading response:", err)
		return
	}
	var response Response
	err = json.Unmarshal(bodyRespBytes, &response)
	if err != nil {
		fmt.Println("Error unmarshalling response:", err)
		return
	}
	for _, activity := range response.Data.Page.Activities {
		studio := ""
		if len(activity.Media.Studios.Nodes) > 0 {
			studio = activity.Media.Studios.Nodes[0].Name
		}

		anime := Anime{
			ID:            activity.Media.ID,
			Title:         activity.Media.Title.Romaji,
			Episode:       activity.Progress,
			Season:        activity.Media.Season,
			Year:          activity.Media.SeasonYear, // Corrected Year field
			Studio:        studio,                    // Corrected Studio field
			WatchStatus:   activity.Status,
			DateCompleted: time.Unix(activity.CreatedAt, 0).Format("2006-01-02 15:04:05"),
			DateCreated:   time.Now().Format(time.RFC3339),
			CoverImage:    activity.Media.CoverImage.Medium,
		}
		err = UpsertAnime(db, anime)
		if err != nil {
			fmt.Println("Error upserting anime:", err)
			continue
		}
		fmt.Printf("Anime: %+v\n", anime)

	}
}
func UpsertAnime(db *sql.DB, anime Anime) error {
	query := `
		INSERT INTO anime(id, title, episode, season, year, studio, watch_status, date_created, date_completed, cover_image) 
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) 
		ON CONFLICT(id) DO UPDATE 
		SET title = ?, episode = ?, season = ?, year = ?, studio = ?, watch_status = ?, date_created = ?, date_completed = ?, cover_image = ?
	`
	_, err := db.Exec(query, anime.ID, anime.Title, anime.Episode, anime.Season, anime.Year, anime.Studio, anime.WatchStatus, anime.DateCreated, anime.DateCompleted, anime.CoverImage, anime.Title, anime.Episode, anime.Season, anime.Year, anime.Studio, anime.WatchStatus, anime.DateCreated, anime.DateCompleted, anime.CoverImage)
	return err
}
