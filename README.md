TwitterClone - A Full-Featured Social Media Platform

A modern, feature-rich social media platform built with Laravel and Tailwind CSS, providing a Twitter-like experience with comprehensive social networking functionality.

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

•	Screenshots of the application

The following screenshots demonstrate the key features and user interface of the TwitterClone application:

### 1. Registration Page
![Registration Page](screenshots/registration.png)
*User account creation form with validation*

### 2. Login Page
![Login Page](screenshots/login.png)
*Secure user authentication interface*

### 3. Home Feed
![Home Feed](screenshots/home-feed.png)
*Main timeline showing tweets with interaction buttons*

### 4. User Profile
![User Profile](screenshots/user-profile.png)
*Complete profile page with user statistics and tweets*

### 5. Profile Edit
![Profile Edit](screenshots/profile-edit.png)
*Profile customization interface with image upload*

### 6. Followers/Following
![Followers Page](screenshots/followers.png)
*Social network management pages*

### 7. User Retweets
![User Retweets](screenshots/retweets.png)
*Dedicated page showing user's retweeted content*

### 8. Tweet Edit
![Tweet Edit](screenshots/tweet-edit.png)
*Tweet modification interface with character counter*

### 9. Tweet Likes
![Tweet Likes](screenshots/tweet-likes.png)
*View of users who liked specific tweets*

### 10. Tweet Retweets
![Tweet Retweets](screenshots/tweet-retweets.png)
*Display of users who retweeted content*

### 11. Search Results
![Search Results](screenshots/search-results.png)
*User search functionality with results display*

### 12. Mobile Responsive
![Mobile View](screenshots/mobile-responsive.png)
*Mobile-optimized views across all pages*

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

Screenshots

Screenshots will be added here to showcase:
- Homepage - Main tweet feed and composer
- User Profiles - Profile pages with statistics
- Search Results - User and tweet search
- Mobile View - Responsive design demonstration
- Profile Editor - Profile customization interface

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
