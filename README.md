# University Ideas System

A secure web-enabled role-based system for collecting ideas for improvement from staff in a large University.

## Features

### User Roles
- **Administrator**: System management, user and department management, closure date settings
- **QA Manager**: Oversee the process, manage categories, view statistics and reports, download data
- **QA Coordinator**: Department-level oversight, encourage staff participation
- **Staff**: Submit ideas, comment on ideas, vote on ideas

### Core Functionality
- User authentication with role-based access control
- Terms and Conditions acceptance before submission
- Idea submission with optional document uploads
- Category tagging for ideas
- Commenting system with anonymous option
- Voting system (Thumbs Up/Down) - one vote per user per idea
- Anonymous submissions (identity stored for investigation)
- Closure date management for ideas and comments
- Email notifications for idea submissions and comments
- Statistical reports and exception reports
- CSV and ZIP data export
- Responsive design for all devices

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL or MariaDB
- Web server (Apache/Nginx)

### Step 1: Clone and Install Dependencies
```bash
cd university-ideas-system
composer install
```

### Step 2: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=university_ideas
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 3: Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### Step 4: Storage Link
```bash
php artisan storage:link
```

### Step 5: Start the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@university.ac.uk | password |
| QA Manager | qamanager@university.ac.uk | password |
| QA Coordinator | qacoordinator1@university.ac.uk | password |9 > *Password9
| Staff | staff@university.ac.uk | password |

## Directory Structure

```
university-ideas-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Auth/
│   │   │   ├── QaCoordinator/
│   │   │   ├── QaManager/
│   │   │   ├── Staff/
│   │   │   ├── CommentController.php
│   │   │   ├── HomeController.php
│   │   │   └── IdeaController.php
│   │   └── Middleware/
│   ├── Models/
│   ├── Notifications/
│   └── Providers/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── ideas/
│       ├── layouts/
│       ├── qa-coordinator/
│       ├── qa-manager/
│       └── staff/
├── routes/
│   └── web.php
└── storage/
```

## Key Features Explained

### Closure Dates
- **Idea Closure Date**: Staff cannot submit new ideas after this date
- **Final Closure Date**: Staff cannot comment after this date
- Only the Admin can set these dates

### Categories
- QA Manager can add new categories at any time
- Categories can only be deleted if they haven't been used
- Staff must select at least one category when submitting an idea

### Anonymous Submissions
- Staff can submit ideas and comments anonymously
- The actual author identity is stored in the database for investigation purposes
- Displayed as "Anonymous" to other users

### Voting System
- Users can vote Thumbs Up (+1) or Thumbs Down (-1) on any idea
- Each user can only vote once per idea
- Users can change their vote by clicking the opposite button
- Popularity score = Thumbs Up - Thumbs Down

### Email Notifications
- QA Coordinator receives email when an idea is submitted in their department
- Idea author receives email when a comment is added to their idea

### Reports (QA Manager)
- **Statistics Report**: Ideas per department, percentage breakdown, contributor count
- **Exception Report**: Ideas without comments, anonymous ideas and comments
- **Data Export**: CSV download of all ideas, ZIP download of all documents

## Security Features

- Role-based access control
- CSRF protection
- Password hashing
- Input validation
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade escaping)

## Assumptions

1. All staff members have a valid university email address
2. The QA Manager is responsible for overall system oversight
3. Each department has one designated QA Coordinator
4. Staff can belong to only one department
5. Anonymous submissions are allowed but traceable for investigation
6. Ideas are automatically approved upon submission (no moderation queue)
7. File uploads are limited to 10MB per file
8. Supported file types: PDF, Word, Excel, PowerPoint, Images

## License

This project is developed for educational purposes.

## Support

For support or questions, please contact the Quality Assurance Office.
