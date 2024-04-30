package config

import (
	"log"

	"github.com/caarlos0/env/v11"
	"github.com/joho/godotenv"
)

func LoadEnvFile() {
	err := godotenv.Load(".env") // Specify the path to your .env file
	if err != nil {
		log.Fatalf("Error loading .env file: %v", err)
	}
}

type Config struct {
	Port string `env:"PORT" envDefault:"1556"`
	Host string `env:"HOST" envDefault:"localhost"`

	PusherAppID   string `env:"PUSHER_APP_ID"`
	PusherKey     string `env:"PUSHER_KEY"`
	PusherSecret  string `env:"PUSHER_SECRET"` // Added missing closing quote
	PusherCluster string `env:"PUSHER_CLUSTER"`

	DiscordClientID     string `env:"DISCORD_CLIENT_ID"`
	DiscordClientSecret string `env:"DISCORD_CLIENT_SECRET"`
	DiscordRedirectURI  string `env:"DISCORD_REDIRECT_URI"`

	RecaptchaSiteKey   string `env:"RECAPTCHA_SITE_KEY"`
	RecaptchaSecretKey string `env:"RECAPTCHA_SECRET_KEY"`

	DatabasePath string `env:"DATABASE_PATH" envDefault:"database/database.db"`

	Secret string `env:"SECRET"`
}

func NewConfig() (*Config, error) {
	// Load environment variables from file
	LoadEnvFile()

	cfg := Config{}
	if err := env.Parse(&cfg); err != nil {
		log.Printf("Error parsing config: %v\n", err)
		return nil, err
	}
	log.Printf("Config: %+v\n", cfg)
	return &cfg, nil
}
