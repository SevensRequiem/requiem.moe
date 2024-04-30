package auth

import (
	"database/sql"
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"os"
	"path/filepath"

	"github.com/gorilla/sessions"
	"github.com/labstack/echo-contrib/session"
	"github.com/labstack/echo/v4"
	_ "github.com/mattn/go-sqlite3" // Import sqlite3 driver
	"golang.org/x/oauth2"

	"encoding/gob"

	"requiem.moe/utils/config"
)

var cfg *config.Config

var oauthConf *oauth2.Config

func init() {
	var err error
	cfg, err = config.NewConfig()
	if err != nil {
		log.Fatal(err)
	}
	gob.Register(User{})

	oauthConf = &oauth2.Config{
		ClientID:     cfg.DiscordClientID,
		ClientSecret: cfg.DiscordClientSecret,
		RedirectURL:  cfg.DiscordRedirectURI,
		Endpoint: oauth2.Endpoint{
			AuthURL:  "https://discord.com/api/oauth2/authorize",
			TokenURL: "https://discord.com/api/oauth2/token",
		},
		Scopes: []string{"identify"},
	}
}

type User struct {
	ID       string `json:"id"`
	Username string `json:"username"`
	Email    string `json:"email"`
	Groups   string `json:"groups"`
	Date     string `json:"date_created"`
	Exists   bool   `json:"exists"`
}

var db *sql.DB

func InitDB() (*sql.DB, error) {
	baseDir, err := os.Getwd()
	if err != nil {
		return nil, fmt.Errorf("failed to get working directory: %v", err)
	}

	database, err := sql.Open("sqlite3", filepath.Join(baseDir, "database", "users.db"))
	if err != nil {
		return nil, fmt.Errorf("failed to open db: %v", err)
	}

	sqlStmt := `
	create table if not exists users (
		id string, 
		username text, 
		email text, 
		groups text,
		date_created datetime default current_timestamp
	);
	`
	_, err = database.Exec(sqlStmt)
	if err != nil {
		return nil, fmt.Errorf("failed to create table: %v", err)
	}

	log.Println("successfully initialized users DB!")
	return database, nil
}

// CallbackHandler handles the callback from the OAuth2 server, put new users into the database following the user struct
func CallbackHandler(c echo.Context) error {
	code := c.QueryParam("code")
	if code == "" {
		return c.JSON(http.StatusBadRequest, "Missing authorization code")
	}
	db, err := InitDB()
	if err != nil {
		return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to initialize database: %s", err.Error()))
	}

	if db == nil {
		return c.JSON(http.StatusInternalServerError, "Database connection not initialized")
	}

	token, err := oauthConf.Exchange(oauth2.NoContext, code)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to exchange code: %s", err.Error()))
	}

	client := oauthConf.Client(oauth2.NoContext, token)
	resp, err := client.Get("https://discord.com/api/users/@me")
	if err != nil {
		return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to fetch user information: %s", err.Error()))
	}
	defer resp.Body.Close()

	var user User
	err = json.NewDecoder(resp.Body).Decode(&user)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to decode user information: %s", err.Error()))
	}
	var exists bool
	err = db.QueryRow("SELECT EXISTS(SELECT 1 FROM users WHERE id=?)", user.ID).Scan(&exists)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to check user existence: %s", err.Error()))
	}

	// Insert the user into the database
	if !exists {
		_, err = db.Exec("INSERT INTO users (id, username, email, groups) VALUES (?, ?, ?, ?)", user.ID, user.Username, user.Email, "user")
		if err != nil {
			return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to insert user into database: %s", err.Error()))
		}
	} else {
		// Fetch the user's groups and date_created from the database
		err = db.QueryRow("SELECT groups, date_created FROM users WHERE id=?", user.ID).Scan(&user.Groups, &user.Date)
		if err != nil {
			return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to fetch user groups and date_created: %s", err.Error()))
		}
	}

	user.Exists = exists

	// Store the user in the session
	sess, err := session.Get("session", c)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to get session: %s", err.Error()))
	}
	sess.Values["user"] = user
	err = sess.Save(c.Request(), c.Response())
	if err != nil {
		return c.JSON(http.StatusInternalServerError, fmt.Sprintf("Failed to save session: %s", err.Error()))
	}

	return c.Redirect(http.StatusTemporaryRedirect, "/")
}

func LoginHandler(c echo.Context) error {
	url := oauthConf.AuthCodeURL("state", oauth2.AccessTypeOffline)
	return c.Redirect(http.StatusTemporaryRedirect, url)
}

func LogoutHandler(c echo.Context) error {
	sess, _ := session.Get("session", c)
	sess.Options = &sessions.Options{MaxAge: -1}
	sess.Save(c.Request(), c.Response())
	return c.Redirect(http.StatusTemporaryRedirect, "/")
}

func AdminCheck(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		return c.JSON(http.StatusInternalServerError, err.Error())
	}

	user, ok := sess.Values["user"].(map[string]interface{})
	if !ok {
		return c.JSON(http.StatusInternalServerError, "User not found in session")
	}

	// Query the database for the user's groups
	var groups string
	err = db.QueryRow("SELECT groups FROM users WHERE id = ?", user["id"]).Scan(&groups)
	if err != nil {
		if err == sql.ErrNoRows {
			return c.JSON(http.StatusForbidden, "User not found in database")
		} else {
			return c.JSON(http.StatusInternalServerError, err.Error())
		}
	}

	// Check if the user is in the admin group
	if groups != "admin" {
		return c.HTML(http.StatusForbidden, "You are not an admin")
	}

	return c.JSON(http.StatusOK, "You are an admin")
}
func ProfileHandler(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		fmt.Println("Error getting session:", err)
		return c.JSON(http.StatusInternalServerError, err.Error())
	}

	// Create a new map with string keys
	stringKeyMap := make(map[string]interface{})
	for key, value := range sess.Values {
		stringKey, ok := key.(string)
		if !ok {
			fmt.Println("Non-string key found in session values")
			return c.JSON(http.StatusInternalServerError, "Non-string key found in session values")
		}
		stringKeyMap[stringKey] = value
	}

	// Try to serialize the new map into JSON
	jsonSessionValues, err := json.Marshal(stringKeyMap)
	if err != nil {
		fmt.Println("Error serializing session values:", err)
		return c.JSON(http.StatusInternalServerError, err.Error())
	}

	// Return all session values
	return c.JSONBlob(http.StatusOK, jsonSessionValues)
}

func GetTotalUsers() int {
	baseDir, err := os.Getwd()
	if err != nil {
		fmt.Println("Error getting base directory:", err)
	}
	dbPath := filepath.Join(baseDir, "database", "users.db")
	db, err = sql.Open("sqlite3", dbPath)
	if err != nil {
		fmt.Println("Error opening database:", err)
	}
	defer db.Close()

	var totalUsers int
	err = db.QueryRow("SELECT COUNT(*) FROM users").Scan(&totalUsers)
	if err != nil {
		fmt.Println("Error getting total users:", err)
	}
	return totalUsers
}
