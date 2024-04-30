package home

import (
	"crypto/sha256"
	"fmt"
	"html/template"
	"io"
	"net/http"
	"strings"

	"github.com/labstack/echo-contrib/session"
	"github.com/labstack/echo/v4"
	"requiem.moe/auth"
	"requiem.moe/utils/maxmind"
	"requiem.moe/utils/visitors"
)

type TemplateRenderer struct {
	templates *template.Template
}

func (t *TemplateRenderer) Render(w io.Writer, name string, data interface{}, c echo.Context) error {
	return t.templates.ExecuteTemplate(w, name, data)
}

func NewTemplateRenderer(glob string) *TemplateRenderer {
	tmpl := template.Must(template.ParseGlob(glob))
	return &TemplateRenderer{
		templates: tmpl,
	}
}

func AboutHandler(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}

	data := map[string]interface{}{}
	user, ok := sess.Values["user"].(auth.User)
	if ok {
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			data["IsAdmin"] = true
			fmt.Println("User is an admin")
		}
	}
	tmpl, err := template.ParseFiles("views/base.html", "views/about.html")
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}
	csrfToken := c.Get("csrf") // Get the CSRF token from the context
	if csrfToken == nil {
		return c.String(http.StatusInternalServerError, "CSRF token not found")
	}

	// Add new values to the existing data map instead of redeclaring it
	data["csrf"] = csrfToken
	data["ip"] = c.RealIP()
	country := maxmind.GetCountry(c)
	fmt.Println("Country:", country)
	data["Country"] = country
	if !user.Exists {
		// Generate a unique ID from the session ID
		sdata := c.RealIP() + sess.ID
		uniqueID := fmt.Sprintf("%x", sha256.Sum256([]byte(sdata)))
		// trim the unique ID to 8 characters
		uniqueID = uniqueID[:8]
		data["randUser"] = uniqueID
	} else {
		data["User"] = user.Username
	}

	err = tmpl.ExecuteTemplate(c.Response().Writer, "base.html", data)
	if err != nil {
		fmt.Println("Error executing template:", err)
		return c.String(http.StatusInternalServerError, err.Error())
	}

	return nil
}

func ContactHandler(c echo.Context) error {
	return c.Render(http.StatusOK, "contact.html", nil)
}

func ServicesHandler(c echo.Context) error {
	return c.Render(http.StatusOK, "services.html", nil)
}

func PortfolioHandler(c echo.Context) error {

	return c.Render(http.StatusOK, "portfolio.html", nil)
}

func HomeHandler(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}

	data := map[string]interface{}{}
	user, ok := sess.Values["user"].(auth.User)
	if ok {
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			data["IsAdmin"] = true
			fmt.Println("User is an admin")
		}
	}
	tmpl, err := template.ParseFiles("views/base.html", "views/home.html")
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}
	csrfToken := c.Get("csrf") // Get the CSRF token from the context
	if csrfToken == nil {
		return c.String(http.StatusInternalServerError, "CSRF token not found")
	}
	country := maxmind.GetCountry(c)
	fmt.Println("Country:", country)
	data["Country"] = country
	// Add new values to the existing data map instead of redeclaring it
	data["csrf"] = csrfToken
	data["ip"] = c.RealIP()
	if !user.Exists {
		// Generate a unique ID from the session ID
		sdata := c.RealIP() + sess.ID
		uniqueID := fmt.Sprintf("%x", sha256.Sum256([]byte(sdata)))
		// trim the unique ID to 8 characters
		uniqueID = uniqueID[:8]
		data["randUser"] = uniqueID
	} else {
		data["User"] = user.Username
	}

	err = tmpl.ExecuteTemplate(c.Response().Writer, "base.html", data)
	if err != nil {
		fmt.Println("Error executing template:", err)
		return c.String(http.StatusInternalServerError, err.Error())
	}

	return nil
}

func BlogHandler(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}

	data := map[string]interface{}{}
	user, ok := sess.Values["user"].(auth.User)
	if ok {
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			data["IsAdmin"] = true
			fmt.Println("User is an admin")
		}
	}

	tmpl, err := template.ParseFiles("views/base.html", "views/blog.html")
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}
	csrfToken := c.Get("csrf") // Get the CSRF token from the context
	if csrfToken == nil {
		return c.String(http.StatusInternalServerError, "CSRF token not found")
	}
	country := maxmind.GetCountry(c)
	fmt.Println("Country:", country)
	data["Country"] = country
	data["csrf"] = csrfToken
	data["ip"] = c.RealIP()
	if !user.Exists {
		// Generate a unique ID from the session ID
		sdata := c.RealIP() + sess.ID
		uniqueID := fmt.Sprintf("%x", sha256.Sum256([]byte(sdata)))
		// trim the unique ID to 8 characters
		uniqueID = uniqueID[:8]
		data["randUser"] = uniqueID
	} else {
		data["User"] = user.Username
	}

	fmt.Println("Data:", data)
	return tmpl.ExecuteTemplate(c.Response().Writer, "base.html", data)
}

func AdminHandler(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}

	data := map[string]interface{}{}
	user, ok := sess.Values["user"].(auth.User)
	if ok {
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			data["IsAdmin"] = true
			data["User"] = user
			fmt.Println("User is an admin")
			tmpl, err := template.ParseFiles("views/base.html", "views/admin.html")
			if err != nil {
				return c.String(http.StatusInternalServerError, err.Error())
			}
			csrfToken := c.Get("csrf") // Get the CSRF token from the context
			if csrfToken == nil {
				return c.String(http.StatusInternalServerError, "CSRF token not found")
			}
			data["csrf"] = csrfToken

			fmt.Println("Data:", data)
			return tmpl.ExecuteTemplate(c.Response().Writer, "base.html", data)
		}
	}

	return c.Render(http.StatusNotFound, "404.html", nil)

}

func VisitorsHandler(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}

	data := map[string]interface{}{}
	user, ok := sess.Values["user"].(auth.User)
	if ok {
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			data["IsAdmin"] = true
			fmt.Println("User is an admin")
		}
	}

	tmpl, err := template.ParseFiles("views/base.html", "views/analytics.html")
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}
	csrfToken := c.Get("csrf") // Get the CSRF token from the context
	if csrfToken == nil {
		return c.String(http.StatusInternalServerError, "CSRF token not found")
	}
	country := maxmind.GetCountry(c)
	fmt.Println("Country:", country)
	data["Country"] = country
	data["csrf"] = csrfToken
	data["ip"] = c.RealIP()

	db := visitors.InitDB()
	// Call the GetVisitors function
	visitorsData, err := visitors.GetVisitors(db)
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}
	data["visitors"] = visitorsData

	if !user.Exists {
		// Generate a unique ID from the session ID
		sdata := c.RealIP() + sess.ID
		uniqueID := fmt.Sprintf("%x", sha256.Sum256([]byte(sdata)))
		// trim the unique ID to 8 characters
		uniqueID = uniqueID[:8]
		data["randUser"] = uniqueID
	} else {
		data["User"] = user.Username
	}

	fmt.Println("Data:", data)
	return tmpl.ExecuteTemplate(c.Response().Writer, "base.html", data)
}

func LoadHandler(c echo.Context) error {
	return c.Render(http.StatusOK, "load.html", nil)
}

func StoreHandler(c echo.Context) error {
	sess, err := session.Get("session", c)
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}

	data := map[string]interface{}{}
	user, ok := sess.Values["user"].(auth.User)
	if ok {
		isAdmin := strings.Contains(user.Groups, "admin")
		if isAdmin {
			data["IsAdmin"] = true
			fmt.Println("User is an admin")
		}
	}
	tmpl, err := template.ParseFiles("views/base.html", "views/store.html")
	if err != nil {
		return c.String(http.StatusInternalServerError, err.Error())
	}
	csrfToken := c.Get("csrf") // Get the CSRF token from the context
	if csrfToken == nil {
		return c.String(http.StatusInternalServerError, "CSRF token not found")
	}
	country := maxmind.GetCountry(c)
	fmt.Println("Country:", country)
	data["Country"] = country
	// Add new values to the existing data map instead of redeclaring it
	data["csrf"] = csrfToken
	data["ip"] = c.RealIP()
	if !user.Exists {
		// Generate a unique ID from the session ID
		sdata := c.RealIP() + sess.ID
		uniqueID := fmt.Sprintf("%x", sha256.Sum256([]byte(sdata)))
		// trim the unique ID to 8 characters
		uniqueID = uniqueID[:8]
		data["randUser"] = uniqueID
	} else {
		data["User"] = user.Username
	}

	err = tmpl.ExecuteTemplate(c.Response().Writer, "base.html", data)
	if err != nil {
		fmt.Println("Error executing template:", err)
		return c.String(http.StatusInternalServerError, err.Error())
	}

	return nil
}

func ErrorHandler(err error, c echo.Context) {
	code := http.StatusInternalServerError
	if he, ok := err.(*echo.HTTPError); ok {
		code = he.Code
	}
	data := map[string]interface{}{
		"code":  code,
		"error": err.Error(),
	}

	if err := c.Render(code, "error.html", data); err != nil {
		c.Logger().Error(err)
	}
}
