# Medical AI Assistant API

A Laravel-based RESTful API for managing medical sessions and interacting with an AI-powered medical chatbot.  
The system allows users to create medical sessions, send symptoms, receive AI responses, and track conversation history securely.



# Features

- User authentication using Laravel Sanctum
- Medical sessions per user
- AI-powered medical chatbot
- Session-based conversation history
- Automatic session expiration
- RESTful API architecture
- Postman documented API



# Tech Stack

- Laravel 12
- PHP 8+
- PostgreSQL
- Laravel Sanctum
- Ollama / AI API
- Postman

# Ollama Model
gemma3:27b-cloud

# Installation

```bash
git clone https://github.com/tardib777/AI-Medical-Assistant.git
cd AI-Medical-Assistant
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
# After Installation

First, Get your own Ollama API Key from the link: https://www.ollama.com . 
Then, create an account or log in to your account if you have an Ollama account before.
After that, create new API key.
Finally, Add in .env file the key: OLLAMA_API_KEY = YOUR_OWN_KEY

# API Documentation

visit the link: https://documenter.getpostman.com/view/39149211/2sBXVhBq75