package chat

import (
	"crypto/rand"
	"database/sql"
	"fmt"
	"html"
	"log"
	"os"
	"path/filepath"
	"strings"
	"time"

	"github.com/pusher/pusher-http-go/v5"

	_ "github.com/mattn/go-sqlite3"

	"requiem.moe/utils/config"
)

var cfg *config.Config
var client pusher.Client

func init() {
	var err error
	cfg, err = config.NewConfig()
	if err != nil {
		log.Fatal(err)
	}
	client = pusher.Client{
		AppID:   cfg.PusherAppID,
		Key:     cfg.PusherKey,
		Secret:  cfg.PusherSecret,
		Cluster: cfg.PusherCluster,
		Secure:  true,
	}
}

func TestEnv() {
	// Initialize configuration explicitly before testing
	if err := initializeConfig(); err != nil {
		log.Fatal("Error initializing config:", err)
	}

	log.Println("Pusher App ID:", cfg.PusherAppID)
	log.Println("Pusher Key:", cfg.PusherKey)
	log.Println("Pusher Secret:", cfg.PusherSecret)
	log.Println("Pusher Cluster:", cfg.PusherCluster)
}

func initializeConfig() error {
	var err error
	cfg, err = config.NewConfig()
	if err != nil {
		return err
	}

	client = pusher.Client{
		AppID:   cfg.PusherAppID,
		Key:     cfg.PusherKey,
		Secret:  cfg.PusherSecret,
		Cluster: cfg.PusherCluster,
		Secure:  true,
	}
	return nil
}

type Chat struct {
	ID                 int64
	Username           string
	Message            string
	TimeStamp          int64
	FormattedTimeStamp string // New field for formatted timestamp
	IP_address         string
	True_User          string
	UUID               string
}

type UserChat struct {
	ID                 int64
	Username           string
	Message            string
	TimeStamp          int64
	FormattedTimeStamp string
	True_User          string
}

func InitDB() (*sql.DB, error) {
	baseDir, err := os.Getwd()
	if err != nil {
		return nil, fmt.Errorf("failed to get working directory: %v", err)
	}

	database, err := sql.Open("sqlite3", filepath.Join(baseDir, "database", "chat.db"))
	if err != nil {
		return nil, fmt.Errorf("failed to open db: %v", err)
	}

	sqlStmt := `
	create table if not exists messages (
		id integer not null primary key, 
		username text, 
		message text, 
		timestamp integer,
		ip_address text,
		true_user text,
		uuid text
	);
	`
	_, err = database.Exec(sqlStmt)
	if err != nil {
		return nil, fmt.Errorf("failed to create table: %v", err)
	}

	log.Println("successfully initialized chat DB!")
	return database, nil
}

var lastMessageTime = make(map[string]time.Time)

func InsertMessage(db *sql.DB, username, message, trueUser, ip string) error {
	// Rate limit: 1 message per second
	if lastMsgTime, ok := lastMessageTime[username]; ok {
		if time.Since(lastMsgTime) < time.Second {
			return fmt.Errorf("error 429: rate limit exceeded for user %s, please wait before sending another message", username)
		}
	}
	lastMessageTime[username] = time.Now()
	uuid := randomUUID()
	//username and message limits
	message, username = sanitizeMessage(message, username)
	if len(username) > 50 {
		return fmt.Errorf("error 400: username is too long, must be less than 50 characters")
	}
	if len(message) > 500 {
		return fmt.Errorf("error 400: message is too long, must be less than 500 characters")
	}

	if message == "" {
		return fmt.Errorf("error 400: message is empty, a message is required to send")
	}
	if username == "" {
		return fmt.Errorf("error 400: username is empty, a username is required")

	}

	_, err := db.Exec("INSERT INTO messages (username, message, timestamp, ip_address, true_user, uuid) VALUES (?, ?, ?, ?, ?, ?)", username, message, time.Now().Unix(), ip, trueUser, uuid)
	if err != nil {
		log.Printf("Failed to insert message for user %s: %v", username, err)
		return fmt.Errorf("error 500: failed to insert message into the database for user %s, error: %v", username, err)
	}

	client.Trigger("chat", "new_message", map[string]string{ // Trigger the new_message event on the chat channel
		"username":  username,
		"message":   message,
		"uuid":      uuid,
		"trueuser":  trueUser,
		"timestamp": time.Now().Format("2006-01-02 15:04:05"),
	})
	client.Trigger("adminchat", "new_message", map[string]string{ // Trigger the new_message event on the adminchat channel

		"username": username,

		"message":   message,
		"uuid":      uuid,
		"ip":        ip,
		"trueuser":  trueUser,
		"timestamp": time.Now().Format("2006-01-02 15:04:05"),
	})

	return nil
}

func GetMessages(db *sql.DB) ([]Chat, error) {
	rows, err := db.Query("SELECT * FROM messages ORDER BY id ASC")
	if err != nil {
		return nil, fmt.Errorf("failed to get messages: %v", err)
	}
	defer rows.Close()

	var messages []Chat
	for rows.Next() {
		var message Chat
		err := rows.Scan(&message.ID, &message.Username, &message.Message, &message.TimeStamp, &message.IP_address, &message.True_User, &message.UUID)
		if err != nil {
			return nil, fmt.Errorf("failed to scan message: %v", err)
		}
		timestamp := time.Unix(message.TimeStamp, 0)
		message.FormattedTimeStamp = timestamp.Format("2006-01-02 15:04:05")
		messages = append(messages, message) // Append each message to the slice
	}

	log.Printf("Retrieved %d messages from the database\n", len(messages))

	return messages, nil
}

func GetMessagesByUser(db *sql.DB, username string) ([]Chat, error) {
	rows, err := db.Query("SELECT * FROM messages WHERE username = ? ORDER BY date_created DESC", username)
	if err != nil {
		return nil, fmt.Errorf("failed to get messages: %v", err)
	}
	defer rows.Close()

	var messages []Chat
	for rows.Next() {
		var message Chat
		err := rows.Scan(&message.ID, &message.Username, &message.Message, &message.TimeStamp, &message.IP_address, &message.True_User, &message.UUID)
		if err != nil {
			return nil, fmt.Errorf("failed to scan message: %v", err)
		}
		messages = append(messages, message)
	}
	return messages, nil
}

func GetMessagesByIP(db *sql.DB, ip string) ([]Chat, error) {
	rows, err := db.Query("SELECT * FROM messages WHERE ip = ? ORDER BY date_created DESC", ip)
	if err != nil {
		return nil, fmt.Errorf("failed to get messages: %v", err)
	}
	defer rows.Close()

	var messages []Chat
	for rows.Next() {
		var message Chat
		err := rows.Scan(&message.ID, &message.Username, &message.Message, &message.TimeStamp, &message.IP_address, &message.True_User, &message.UUID)
		if err != nil {
			return nil, fmt.Errorf("failed to scan message: %v", err)
		}
		messages = append(messages, message)
	}
	return messages, nil
}

func GetMessagesByUserAndIP(db *sql.DB, username, ip string) ([]Chat, error) {
	rows, err := db.Query("SELECT * FROM messages WHERE username = ? AND ip = ? ORDER BY date_created DESC", username, ip)
	if err != nil {
		return nil, fmt.Errorf("failed to get messages: %v", err)
	}
	defer rows.Close()

	var messages []Chat
	for rows.Next() {
		var message Chat
		err := rows.Scan(&message.ID, &message.Username, &message.Message, &message.TimeStamp, &message.IP_address, &message.True_User, &message.UUID)
		if err != nil {
			return nil, fmt.Errorf("failed to scan message: %v", err)
		}
		messages = append(messages, message)
	}
	return messages, nil
}

func GetMessageByID(db *sql.DB, id int64) (UserChat, error) {
	var message UserChat
	err := db.QueryRow("SELECT id, username, message, timestamp, trueuser FROM messages WHERE id = ?", id).Scan(&message.ID, &message.Username, &message.Message, &message.TimeStamp, &message.True_User)
	if err != nil {
		return UserChat{}, fmt.Errorf("failed to get message: %v", err)
	}
	return message, nil
}

func AdminGetMessageByID(db *sql.DB, id int64) (Chat, error) {
	var message Chat
	err := db.QueryRow("SELECT * FROM messages WHERE id = ?", id).Scan(&message.ID, &message.Username, &message.Message, &message.TimeStamp, &message.IP_address, &message.True_User, &message.UUID)
	if err != nil {
		return Chat{}, fmt.Errorf("failed to get message: %v", err)
	}
	return message, nil
}

func AdminGetMessages(db *sql.DB) ([]Chat, error) {
	rows, err := db.Query("SELECT * FROM messages ORDER BY id ASC")
	if err != nil {
		return nil, fmt.Errorf("failed to get messages: %v", err)
	}
	defer rows.Close()

	var messages []Chat
	for rows.Next() {
		var message Chat
		err := rows.Scan(&message.ID, &message.Username, &message.Message, &message.TimeStamp, &message.IP_address, &message.True_User, &message.UUID)
		if err != nil {
			return nil, fmt.Errorf("failed to scan message: %v", err)
		}
		messages = append(messages, message)
	}
	return messages, nil
}

func DeleteMessage(db *sql.DB, uuid string) error {
	_, err := db.Exec("DELETE FROM messages WHERE uuid = ?", uuid)
	if err != nil {
		return fmt.Errorf("failed to delete message: %v", err)
	}
	return nil
}
func randomUUID() string {
	b := make([]byte, 16)
	_, err := rand.Read(b)
	if err != nil {
		return ""
	}
	b[6] = (b[6] & 0x0f) | 0x40
	b[8] = (b[8] & 0x3f) | 0x80
	return fmt.Sprintf("%x-%x-%x-%x-%x", b[0:4], b[4:6], b[6:8], b[8:10], b[10:])
}

func filterBadWords(message, username string) string {
	badWords := []string{"badword1", "badword2", "badword3"}
	for _, word := range badWords {
		message = strings.ReplaceAll(message, word, "****")
		username = strings.ReplaceAll(username, word, "****")
	}
	return message
}

func sanitizeMessage(message string, username string) (string, string) {
	message = strings.TrimSpace(message)
	message = filterBadWords(message, username)
	message = html.EscapeString(message)
	username = filterBadWords(username, message)
	username = html.EscapeString(username)

	return message, username
}

func GetTotalMessages() int {
	baseDir, err := os.Getwd()
	if err != nil {
		log.Fatal(err)
	}

	db, err := sql.Open("sqlite3", filepath.Join(baseDir, "database", "chat.db"))
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	var count int
	err = db.QueryRow("SELECT COUNT(*) FROM messages").Scan(&count)
	if err != nil {
		log.Fatal(err)
	}

	return count
}
