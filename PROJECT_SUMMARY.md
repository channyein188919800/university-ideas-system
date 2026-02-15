# University Ideas System - Project Summary

## Overview
A comprehensive Laravel web application for collecting ideas for improvement from staff in a large University. The system features role-based access control, idea submission with file uploads, commenting, voting, and comprehensive reporting.

## Project Structure

### Database (9 Migrations)
1. **Users** - Staff accounts with role-based access
2. **Departments** - University departments with QA coordinators
3. **Categories** - Idea categorization system
4. **Ideas** - Main idea submissions with metadata
5. **Idea_Category** - Many-to-many relationship
6. **Comments** - Comments on ideas
7. **Votes** - Thumbs up/down voting system
8. **Documents** - File attachments for ideas
9. **Settings** - System configuration (closure dates)

### Models (7 Models)
- **User** - Authentication, roles, relationships
- **Department** - Department management
- **Category** - Category management with usage tracking
- **Idea** - Idea submission with voting and commenting
- **Comment** - Comment system with anonymous support
- **Vote** - One-vote-per-user system
- **Document** - File upload handling
- **Setting** - Dynamic configuration system

### Controllers (12 Controllers)

#### Admin
- `DashboardController` - Admin dashboard with statistics
- `UserController` - User CRUD operations
- `DepartmentController` - Department management
- `SettingController` - System settings (closure dates)

#### QA Manager
- `DashboardController` - QA Manager overview
- `CategoryController` - Category management
- `ReportController` - Statistics and exception reports, CSV/ZIP downloads

#### QA Coordinator
- `DashboardController` - Department-specific dashboard

#### Staff
- `DashboardController` - Personal dashboard

#### General
- `HomeController` - Public homepage
- `IdeaController` - Idea submission, viewing, voting
- `CommentController` - Comment management
- `Auth/LoginController` - Authentication
- `Auth/TermsController` - Terms acceptance

### Middleware (4 Custom)
- `RoleMiddleware` - Role-based access control
- `CheckTerms` - Terms acceptance verification
- `CheckClosureDate` - Closure date enforcement

### Views (20+ Blade Templates)

#### Layouts
- `layouts/app.blade.php` - Main layout with university theme

#### Public
- `home.blade.php` - Homepage with statistics
- `ideas/index.blade.php` - Idea listing with filters
- `ideas/create.blade.php` - Idea submission form
- `ideas/show.blade.php` - Idea detail with comments

#### Auth
- `auth/login.blade.php` - Login page
- `auth/terms.blade.php` - Terms and conditions

#### Admin
- `admin/dashboard.blade.php`
- `admin/users/index.blade.php`
- `admin/users/create.blade.php`
- `admin/users/edit.blade.php`
- `admin/departments/index.blade.php`
- `admin/departments/create.blade.php`
- `admin/departments/edit.blade.php`
- `admin/settings/index.blade.php`

#### QA Manager
- `qa-manager/dashboard.blade.php`
- `qa-manager/categories/index.blade.php`
- `qa-manager/categories/create.blade.php`
- `qa-manager/categories/edit.blade.php`
- `qa-manager/reports/statistics.blade.php`
- `qa-manager/reports/exceptions.blade.php`

#### QA Coordinator
- `qa-coordinator/dashboard.blade.php`

#### Staff
- `staff/dashboard.blade.php`

### Notifications (2)
- `IdeaSubmittedNotification` - Email to QA Coordinator
- `CommentAddedNotification` - Email to idea author

### Seeders (4)
- `DatabaseSeeder` - Main seeder
- `DepartmentSeeder` - 8 sample departments
- `CategorySeeder` - 10 sample categories
- `UserSeeder` - Admin, QA Manager, QA Coordinators, Staff
- `SettingSeeder` - Default closure dates

## Key Features Implemented

### 1. Role-Based Access Control
- Admin: Full system access
- QA Manager: Categories, reports, data export
- QA Coordinator: Department oversight
- Staff: Submit ideas, comment, vote

### 2. Idea Submission
- Title and description
- Multiple category selection
- Optional file uploads (max 10MB)
- Anonymous submission option
- Terms acceptance required

### 3. Voting System
- Thumbs Up (+1) / Thumbs Down (-1)
- One vote per user per idea
- Vote changeable
- Popularity score calculation

### 4. Commenting System
- Comments on any idea
- Anonymous option
- Email notifications to idea authors

### 5. Closure Dates
- Idea submission closure date
- Final closure date for comments
- Automatic enforcement

### 6. Reporting & Statistics
- Department-wise idea counts
- Percentage breakdowns
- Contributor statistics
- Exception reports (ideas without comments, anonymous content)
- CSV data export
- ZIP document export

### 7. Email Notifications
- Automatic emails on idea submission
- Automatic emails on comment addition

### 8. Responsive Design
- Mobile-friendly interface
- Bootstrap 5 framework
- University theme colors (navy blue, gold accent)

## University Theme Colors

```css
--primary-color: #1e3a5f;    /* Navy Blue */
--secondary-color: #2c5282;  /* Lighter Navy */
--accent-color: #d69e2e;     /* Gold */
--success-color: #38a169;    /* Green */
--danger-color: #e53e3e;     /* Red */
--warning-color: #dd6b20;    /* Orange */
--info-color: #3182ce;       /* Blue */
```

## Installation Steps

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Storage Link**
   ```bash
   php artisan storage:link
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Start Server**
   ```bash
   php artisan serve
   ```

## Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@university.ac.uk | password |
| QA Manager | qamanager@university.ac.uk | password |
| QA Coordinator | qacoordinator1@university.ac.uk | password |
| Staff | staff@university.ac.uk | password |

## File Count
- PHP Files: 60+
- Blade Templates: 20+
- Configuration Files: 10+
- Total Files: 99+

## Security Features
- CSRF protection
- Password hashing
- Input validation
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade escaping)
- Role-based middleware

## Assumptions Made
1. All staff have valid university email addresses
2. Each department has one QA Coordinator
3. Staff belong to only one department
4. Anonymous submissions are traceable for investigation
5. Ideas are auto-approved (no moderation queue)
6. File uploads limited to 10MB per file
7. Supported formats: PDF, Word, Excel, PowerPoint, Images
