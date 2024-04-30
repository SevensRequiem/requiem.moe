package system

import (
	"net/http"
	"os"
	"os/exec"
	"path/filepath"
	"strings"

	"github.com/labstack/echo/v4"
)

type SystemStats struct {
	Uptime      string `json:"Uptime"`
	Cpu         string `json:"Cpu"`
	Memory      string `json:"Memory"`
	HitsToday   string `json:"HitsToday"`
	HitsAllTime string `json:"HitsAllTime"`
}

func GetSysStats(c echo.Context) error {
	// get uptime
	uptimeOut, _ := exec.Command("uptime", "-p").Output()
	uptime := strings.TrimSpace(string(uptimeOut))

	// get cpu usage
	cpuOut, _ := exec.Command("sh", "-c", "top -bn1 | grep 'Cpu(s)' | sed 's/.*, *\\([0-9.]*\\)%* id.*/\\1/' | awk '{print 100 - $1\"%\"}'").Output()
	cpu := strings.TrimSpace(string(cpuOut))

	// get memory usage
	memOut, _ := exec.Command("sh", "-c", "free -m | awk 'NR==2{printf \"%.2f%%\", $3*100/$2 }'").Output()
	memory := strings.TrimSpace(string(memOut))

	// get hits today and all time (dummy values, replace with actual implementation)
	hitsToday := "100"
	hitsAllTime := "1000"

	stats := &SystemStats{
		Uptime:      uptime,
		Cpu:         cpu,
		Memory:      memory,
		HitsToday:   hitsToday,
		HitsAllTime: hitsAllTime,
	}

	return c.JSON(http.StatusOK, stats)
}

func GetUptime() string {
	uptimeOut, _ := exec.Command("uptime", "-p").Output()
	uptime := strings.TrimSpace(string(uptimeOut))
	return uptime
}

func GetBaseDirSize() int64 {
	var size int64
	baseDir, _ := os.Getwd()
	err := filepath.Walk(baseDir, func(path string, info os.FileInfo, err error) error {
		if err != nil {
			return err
		}
		size += info.Size()
		return nil
	})
	if err != nil {
		return 0
	}
	return size
}
