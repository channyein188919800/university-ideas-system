<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'University Ideas System'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e3a5f;
            --secondary-color: #2c5282;
            --accent-color: #d69e2e;
            --success-color: #38a169;
            --danger-color: #e53e3e;
            --warning-color: #dd6b20;
            --info-color: #3182ce;
            --light-bg: #f7fafc;
            --dark-text: #1a202c;
            --muted-text: #718096;
            --border-color: #e2e8f0;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: white !important;
        }
        
        .navbar-brand i {
            color: var(--accent-color);
            margin-right: 0.5rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white !important;
        }
        
        .nav-link.active {
            background-color: var(--accent-color);
            color: white !important;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }
        
        .card-header i {
            margin-right: 0.5rem;
            color: var(--accent-color);
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border: none;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border: none;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border: none;
            color: white;
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border: none;
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #b7791f;
            color: white;
        }
        
        /* Stats Card */
        .stats-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-3px);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .stats-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .stats-icon.success {
            background: linear-gradient(135deg, var(--success-color), #48bb78);
            color: white;
        }
        
        .stats-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #ed8936);
            color: white;
        }
        
        .stats-icon.info {
            background: linear-gradient(135deg, var(--info-color), #4299e1);
            color: white;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stats-label {
            color: var(--muted-text);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        /* Idea Card */
        .idea-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }
        
        .idea-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .idea-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .idea-meta {
            color: var(--muted-text);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }
        
        .idea-meta i {
            margin-right: 0.25rem;
        }
        
        .idea-description {
            color: var(--dark-text);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .idea-stats {
            display: flex;
            gap: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
        
        .idea-stat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--muted-text);
            font-size: 0.875rem;
        }
        
        .idea-stat i {
            font-size: 1rem;
        }
        
        .vote-btn {
            background: none;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .vote-btn:hover {
            background-color: var(--light-bg);
        }
        
        .vote-btn.active-up {
            background-color: #c6f6d5;
            border-color: var(--success-color);
            color: var(--success-color);
        }
        
        .vote-btn.active-down {
            background-color: #fed7d7;
            border-color: var(--danger-color);
            color: var(--danger-color);
        }
        
        /* Badge Styles */
        .badge-category {
            background-color: #e6fffa;
            color: #234e52;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 0.5rem;
        }
        
        .badge-anonymous {
            background-color: #edf2f7;
            color: #4a5568;
        }
        
        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 0.5rem;
        }
        
        .alert-success {
            background-color: #c6f6d5;
            color: #22543d;
        }
        
        .alert-danger {
            background-color: #fed7d7;
            color: #742a2a;
        }
        
        .alert-warning {
            background-color: #feebc8;
            color: #7c2d12;
        }
        
        .alert-info {
            background-color: #bee3f8;
            color: #2a4365;
        }
        
        /* Form Styles */
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }
        
        footer a {
            color: var(--accent-color);
            text-decoration: none;
        }
        
        footer a:hover {
            text-decoration: underline;
        }
        
        /* Pagination */
        .pagination {
            justify-content: center;
        }
        
        .page-link {
            color: var(--primary-color);
            border: 1px solid var(--border-color);
        }
        
        .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Sidebar */
        .sidebar {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--dark-text);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }
        
        .sidebar-menu i {
            width: 24px;
            margin-right: 0.75rem;
            color: var(--muted-text);
        }
        
        .sidebar-menu a:hover i,
        .sidebar-menu a.active i {
            color: var(--primary-color);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }
        
        /* Table Styles */
        .table {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
        }
        
        .table tbody tr:hover {
            background-color: var(--light-bg);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 1.75rem;
            }
            
            .stats-number {
                font-size: 1.5rem;
            }
            
            .idea-stats {
                flex-wrap: wrap;
                gap: 0.75rem;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <i class="fas fa-lightbulb"></i>
                University Ideas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('ideas.index') ? 'active' : ''); ?>" href="<?php echo e(route('ideas.index')); ?>">
                            <i class="fas fa-ideas"></i> All Ideas
                        </a>
                    </li>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->canSubmitIdea()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(request()->routeIs('ideas.create') ? 'active' : ''); ?>" href="<?php echo e(route('ideas.create')); ?>">
                                    <i class="fas fa-plus-circle"></i> Submit Idea
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if(auth()->guard()->guest()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('login') ? 'active' : ''); ?>" href="<?php echo e(route('login')); ?>">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?php echo e(auth()->user()->name); ?>

                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if(auth()->user()->isAdmin()): ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>"><i class="fas fa-cog"></i> Admin Panel</a></li>
                                <?php elseif(auth()->user()->isQaManager()): ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('qa-manager.dashboard')); ?>"><i class="fas fa-chart-line"></i> QA Manager</a></li>
                                <?php elseif(auth()->user()->isQaCoordinator()): ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('qa-coordinator.dashboard')); ?>"><i class="fas fa-users-cog"></i> QA Coordinator</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('staff.dashboard')); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        <?php if(session('success')): ?>
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
            <div class="container mt-3">
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('warning')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="container mt-3">
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fas fa-info-circle"></i> <?php echo e(session('info')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-lightbulb"></i> University Ideas System</h5>
                    <p class="mb-0">Empowering staff to contribute ideas for university improvement.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?php echo e(date('Y')); ?> University. All rights reserved.</p>
                    <small>Quality Assurance Division</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/layouts/app.blade.php ENDPATH**/ ?>