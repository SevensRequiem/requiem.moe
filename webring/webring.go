package webring

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"
	"os"
	"path/filepath"
)

type Webring struct {
	Title string `json:"title"`
	Image string `json:"image"`
	Link  string `json:"link"`
}

func GetWebring() ([]Webring, error) {
	url := "https://raw.githubusercontent.com/SevensRequiem/requiem.moe/main/webring.json"
	resp, err := http.Get(url)
	if err != nil {
		return nil, err
	}
	defer resp.Body.Close()
	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return nil, err
	}
	var webring []Webring
	err = json.Unmarshal(body, &webring)
	if err != nil {
		return nil, err
	}
	return webring, nil
}

func GetWebringImage() error {
	webring, err := GetWebring()
	if err != nil {
		return err
	}
	for _, w := range webring {
		if w.Image != "" {
			url := fmt.Sprintf("https://raw.githubusercontent.com/SevensRequiem/requiem.moe/main/%s", w.Image)
			resp, err := http.Get(url)
			if err != nil {
				return err
			}
			defer resp.Body.Close()
			body, err := ioutil.ReadAll(resp.Body)
			if err != nil {
				return err
			}
			dir, _ := filepath.Split(w.Image)
			os.MkdirAll(dir, os.ModePerm)
			err = ioutil.WriteFile(w.Image, body, os.ModePerm)
			if err != nil {
				return err
			}
		}
	}
	return nil
}

func GetWebringImageList() ([]string, error) {
	webring, err := GetWebring()
	if err != nil {
		return nil, err
	}
	var images []string
	for _, w := range webring {
		if w.Image != "" {
			images = append(images, w.Image)
		}
	}
	return images, nil
}
