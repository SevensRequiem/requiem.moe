package visitors

import (
	"bytes"
	"database/sql"
	"encoding/json"
	"fmt"
	"io"
	"io/ioutil"
	"os"

	"net/http"

	"github.com/labstack/echo/v4"
	_ "github.com/mattn/go-sqlite3"
)

type Visitor struct {
	Id             int
	Ip             string
	User_agent     string
	Request_type   string
	Request_header string // Change this to string
	Request_body   string
	Request        string
	Request_full   string
}

func InitDB() *sql.DB {
	db, err := sql.Open("sqlite3", "database/visitors.db")
	if err != nil {
		fmt.Println(err)
		os.Exit(1)
	}
	_, err = db.Exec("CREATE TABLE IF NOT EXISTS visitors (id INTEGER PRIMARY KEY, ip TEXT, user_agent TEXT, request_type TEXT, request_header TEXT, request_body TEXT, request TEXT, request_full TEXT)")
	if err != nil {
		fmt.Println(err)
	}

	return db
}

func VisitorMiddleware(next echo.HandlerFunc) echo.HandlerFunc {
	return func(c echo.Context) error {
		db := InitDB()
		defer db.Close()

		body, err := readBody(c.Request())
		if err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
		}

		// ...

		header, err := json.Marshal(c.Request().Header)
		if err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
		}

		v := Visitor{
			Ip:             c.RealIP(),
			User_agent:     c.Request().UserAgent(),
			Request_type:   c.Request().Method,
			Request_header: string(header), // This is now a string
			Request_body:   body,
			Request:        c.Request().RequestURI,
			Request_full:   c.Request().URL.String(),
		}

		stmt, err := db.Prepare("INSERT INTO visitors(ip, user_agent, request_type, request_header, request_body, request, request_full) values(?,?,?,?,?,?,?)")
		if err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
		}

		_, err = stmt.Exec(v.Ip, v.User_agent, v.Request_type, v.Request_header, v.Request_body, v.Request, v.Request_full)
		if err != nil {
			return c.JSON(http.StatusInternalServerError, map[string]string{"error": err.Error()})
		}

		// Call the next middleware in the chain
		return next(c)
	}
}

func readBody(req *http.Request) (string, error) {
	bodyBytes, err := ioutil.ReadAll(req.Body)
	if err != nil {
		return "", err
	}

	// Replace the body in the request with a new reader, so it can be read again later
	req.Body = io.NopCloser(bytes.NewBuffer(bodyBytes))

	return string(bodyBytes), nil
}
func GetVisitors(db *sql.DB) ([]Visitor, error) {
	rows, err := db.Query("SELECT * FROM visitors")
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	visitors := []Visitor{}
	for rows.Next() {
		v := Visitor{}
		err := rows.Scan(&v.Id, &v.Ip, &v.User_agent, &v.Request_type, &v.Request_header, &v.Request_body, &v.Request, &v.Request_full)
		if err != nil {
			return nil, err
		}
		visitors = append(visitors, v)
	}

	return visitors, nil
}
