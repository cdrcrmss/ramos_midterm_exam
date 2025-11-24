TwitterClone - A Full-Featured Social Media Platform

A modern, feature-rich social media platform built with Laravel and Tailwind CSS, providing a Twitter-like experience with comprehensive social networking functionality.

## 🚀 Live Demo

**[View Live Application](https://ramos-midterm-exam-main-x9ft04.laravel.cloud/)**

*Try registering a new account or exploring the features!*

Project Description and Purpose

TwitterClone is a complete social media application that replicates the core functionality of Twitter while adding additional features for enhanced user interaction. The project demonstrates full-stack web development capabilities using modern PHP frameworks and responsive design principles.

Purpose:
- Create a fully functional social media platform
- Demonstrate proficiency in Laravel framework development
- Implement real-time social interactions and user engagement features
- Showcase modern web UI/UX design patterns
- Build a scalable and maintainable codebase

Features Implemented

User Authentication and Profile Management
- User Registration and Login - Secure authentication system
- Profile Customization - Edit name, username, bio, and email
- Profile Pictures - Upload and manage profile images with fallback to initials
- User Profiles - Comprehensive profile pages with statistics

Tweet Management
- Create Tweets - Post messages up to 280 characters
- Edit Tweets - Modify posted tweets with edit indicators
- Delete Tweets - Remove unwanted posts
- Real-time Character Count - Live feedback while composing
- Tweet Timestamps - Relative time display (e.g., "2 hours ago")

Social Interactions
- Like System - Like/unlike tweets with visual feedback
- Retweet Functionality - Share others' tweets with attribution
- Follow/Unfollow Users - Build your social network
- Social Analytics - View who liked and retweeted your posts

Social Analytics and Discovery
- Likes Viewer - See all users who liked specific tweets
- Retweets Viewer - View everyone who retweeted content
- User Search - Find users by name, username, or bio
- Tweet Search - Search through all public tweets
- Follow Statistics - Track followers and following counts

User Experience
- Responsive Design - Mobile-first, works on all devices
- Modern UI - Clean, intuitive interface with Tailwind CSS
- Interactive Elements - Smooth transitions and hover effects
- Profile Integration - Seamless navigation between features
- File Upload - Drag-and-drop profile picture uploads

Advanced Features
- User Retweets Page - Dedicated section showing all user retweets
- Profile Statistics - Comprehensive stats (tweets, followers, following, retweets, likes)
- Search Functionality - Global search across users and content
- Navigation System - Intuitive menu system with user indicators
- Content Management - Edit/delete permissions for own content

Installation Instructions

Prerequisites
- PHP 8.2 or higher
- Composer 2.6 or higher
- Node.js 18.x or higher and npm
- SQLite 3.x (or MySQL 8.0+/PostgreSQL 13+)
- Git

Step 1: Clone and Setup Project
```bash
# Clone the repository
git clone <repository-url>
cd ramos_midterm_exam

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

Step 2: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create storage link for file uploads
php artisan storage:link
```

Step 3: Database Configuration
Edit your `.env` file with database settings:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Or for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=twitter_clone
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

Step 4: Build Assets
```bash
# Build frontend assets
npm run build

# Or for development with watching
npm run dev
```

Database Setup Steps

Step 1: Create Database File (for SQLite)
```bash
# Create SQLite database file
touch database/database.sqlite
```

Step 2: Run Migrations
```bash
# Run all database migrations
php artisan migrate

# Or migrate with fresh database
php artisan migrate:fresh
```

Step 3: Seed Database (Optional)
```bash
# Seed with sample data
php artisan db:seed
```

Database Tables Created:
- users - User accounts and profiles
- tweets - All tweet content and metadata  
- likes - Tweet like relationships
- follows - User following relationships
- retweets - Retweet relationships
- cache - Application caching
- jobs - Background job queue

Step 4: Start Development Server
```bash
# Start Laravel development server
php artisan serve

# Server will be available at: http://127.0.0.1:8000
```

Screenshots of the application

The following screenshots demonstrate the key features and user interface of the TwitterClone application:

1. Registration Page
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/7028830b-e091-4c32-88f9-af88744d8a67" />

2. Login Page
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/6c142ac7-0297-4303-bdd4-5c1f708548ab" />

3. Home Feed
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/f65cd362-bb85-4794-bc59-0952e36c496a" />

4. User Profile
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/c2c398b9-6efa-46ac-aca5-47400beee34e" />


### 5. Profile Edit
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/a89bad4e-369f-4a34-82ed-4c3f859f858e" />


### 6. Followers/Following
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/0e145c70-aaec-47b1-a1e8-d119cec087cf" />
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/66c2f7f8-6f2d-4dd1-ad7b-5b819a578a95" />

### 7. User Retweets
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/beba41bb-f5c6-4883-a9f1-1ca9ab0210c6" />

### 8. Tweet Edit
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/6ba9b9d2-2313-4970-b65d-dea45ef6c32d" />


### 9. Tweet Likes
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/cb39a5b4-4f7e-4c42-84de-9ab94cf421a5" />


### 10. Tweet Retweets
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/e7e13b94-0504-4a36-843a-ae83f6711ed7" />


### 11. Search Results
<img width="2560" height="1600" alt="image" src="https://github.com/user-attachments/assets/3728890d-92dd-4871-a71e-c4e6e9f3e9c3" />


Additional Configuration

File Upload Setup
Ensure the `storage/app/public` directory is writable:
```bash
# Set proper permissions (Unix/Linux)
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Create storage link if not exists
php artisan storage:link
```

Clear Caches (if needed)
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Technology Stack

- Backend: Laravel 12.0 (PHP 8.2+)
- Frontend: Blade Templates, Tailwind CSS 4.0
- Database: SQLite (configurable for MySQL/PostgreSQL)
- Build Tools: Vite, npm
- File Storage: Laravel Storage with public disk
- Authentication: Laravel's built-in authentication

Key Technical Achievements

- MVC Architecture - Clean separation of concerns
- Database Relationships - Complex many-to-many relationships
- File Upload System - Secure image handling with validation
- Responsive Design - Mobile-first CSS framework implementation
- Real-time Features - Dynamic content updates
- Search Functionality - Full-text search across multiple models
- Security - CSRF protection, input validation, and secure file uploads

Project Structure

```
ramos_midterm_exam/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Policies/            # Authorization policies
├── database/
│   ├── migrations/          # Database schema files
│   └── seeders/            # Database seeding files
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheet files
│   └── js/                 # JavaScript files
├── routes/
│   └── web.php             # Application routes
└── public/
    └── storage/            # Public file storage
```

Credits

Development
- Developer: Cedric Ramos (@cdrx)
- Framework: Laravel (Taylor Otwell and Laravel team)
- CSS Framework: Tailwind CSS
- Icons: Heroicons

AI Assistance - ChatGPT Code Generation
This project's entire codebase was generated with assistance from ChatGPT by OpenAI, including:

Code Generation:
- Complete Laravel controller implementations
- Database migration files and model relationships
- Blade template views with Tailwind CSS styling
- Route definitions and middleware setup
- JavaScript functionality and form validation
- Authentication and authorization logic

Technical Guidance:
- Laravel framework best practices
- Database design and optimization
- Frontend responsive design patterns
- Security implementation strategies
- Error handling and debugging
- Code organization and structure

Made by Cedric Ramos | Code generated with ChatGPT assistance | November 2025
