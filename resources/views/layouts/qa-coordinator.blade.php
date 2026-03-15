<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'QA Coordinator - UniIdeas')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #0f1f3a;
            --secondary-color: #1e3a5f;
            --accent-color: #d69e2e;
            --success-color: #48bb78;
            --danger-color: #f56565;
            --warning-color: #ecc94b;
            --info-color: #4299e1;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
        }

        /* Admin Shell Layout - Exactly copied from admin */
        .qa-shell {
            display: flex;
            min-height: 100vh;
            background: radial-gradient(circle at 0% 0%, #e9f2ff 0%, #f4f7fc 35%, #f8fafc 100%);
            position: relative;
        }

        /* Sidebar Styles - Exactly copied from admin */
        .qa-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f1f3a 0%, #15294a 100%);
            color: #cdd8ee;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 8px 0 30px rgba(16, 29, 59, 0.2);
            z-index: 50;
            transition: transform 0.25s ease;
        }

        .qa-sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .qa-sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .qa-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .qa-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .qa-brand h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 0;
        }

        .qa-brand small {
            color: #8ca3cf;
            font-size: 0.75rem;
        }

        .qa-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #d69e2e 0%, #fbbf24 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .qa-nav-title {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.72rem;
            color: #7287b8;
            margin: 0 0 0.45rem 0.6rem;
        }

        .qa-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 0.65rem;
            color: #cdd8ee;
            text-decoration: none;
            padding: 0.68rem 0.75rem;
            margin-bottom: 0.25rem;
            transition: all 0.25s ease;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .qa-nav-link i {
            font-size: 1.05rem;
            width: 20px;
        }

        .qa-nav-link:hover,
        .qa-nav-link.active {
            background: rgba(125, 160, 255, 0.18);
            color: #ffffff;
            transform: translateX(4px);
        }

        .qa-main {
            flex: 1;
            padding: 1.5rem;
            overflow-x: hidden;
        }

        /* Topbar - Exactly copied from admin */
        .qa-topbar {
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid #e2e8f4;
            border-radius: 1rem;
            padding: 1rem 1.2rem;
            margin-bottom: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            backdrop-filter: blur(8px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .qa-topbar h3 {
            font-weight: 700;
            color: #1c2a45;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }

        .qa-topbar p {
            color: #6b7891;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* Stat Cards - Exactly copied from admin */
        .qa-stat-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.2rem;
            border: 1px solid #e4ebf8;
            box-shadow: 0 10px 24px rgba(27, 52, 102, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            height: 100%;
        }

        .qa-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(27, 52, 102, 0.12);
        }

        .qa-stat-card h4 {
            font-size: 1.65rem;
            font-weight: 700;
            margin: 0.75rem 0 0.2rem;
            color: #22314f;
        }

        .qa-stat-card p {
            margin: 0;
            color: #70809f;
            font-size: 0.9rem;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.2rem;
        }

        .stat-icon.ideas { background: linear-gradient(135deg, #00a58b, #35d1b2); }
        .stat-icon.comments { background: linear-gradient(135deg, #e58f07, #f8bc5e); }
        .stat-icon.users { background: linear-gradient(135deg, #3577ff, #5ea8ff); }
        .stat-icon.participation { background: linear-gradient(135deg, #7946fd, #9f75ff); }

        /* Cards - Exactly copied from admin */
        .qa-card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #e4ebf8;
            box-shadow: 0 10px 24px rgba(27, 52, 102, 0.08);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .qa-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #edf2fb;
            background: linear-gradient(90deg, #f9fbff, #f3f7ff);
        }

        .qa-card-header h5 {
            margin: 0;
            font-size: 1rem;
            color: #253558;
            font-weight: 600;
        }

        .qa-card-body {
            padding: 1rem 1.1rem;
        }

        /* Feed Items - Exactly copied from admin */
        .feed-item {
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.15rem 0;
        }

        .feed-item.with-border {
            border-bottom: 1px solid #edf2fb;
            padding-bottom: 0.85rem;
            margin-bottom: 0.85rem;
        }

        .feed-main {
            min-width: 0;
        }

        .feed-title {
            color: #263a64;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0.35rem;
        }

        .feed-title:hover {
            color: #1f6dff;
        }

        .feed-meta {
            font-size: 0.85rem;
            color: #6a7b9a;
        }

        /* Menu Toggle - Exactly copied from admin */
        .qa-menu-toggle {
            position: fixed;
            top: 12px;
            left: 10px;
            z-index: 60;
            background: #0f1f3a;
            color: #fff;
            border: none;
            border-radius: 0.55rem;
            width: 40px;
            height: 40px;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .qa-backdrop {
            display: none;
        }

        /* Responsive - Exactly copied from admin */
        @media (max-width: 991.98px) {
            .qa-menu-toggle {
                display: flex;
            }

            .qa-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                transform: translateX(-102%);
                z-index: 1000;
            }

            .qa-sidebar.open {
                transform: translateX(0);
            }

            .qa-main {
                width: 100%;
                padding: 1rem;
                margin-top: 3rem;
            }

            .qa-backdrop.open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(11, 17, 31, 0.55);
                z-index: 999;
            }
        }

        /* Animations - Exactly copied from admin */
        .reveal {
            opacity: 0;
            transform: translateY(10px);
            animation: reveal 0.5s ease forwards;
            animation-delay: var(--delay, 0s);
        }

        @keyframes reveal {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Department badge */
        .dept-badge {
            background: rgba(214, 158, 46, 0.1);
            color: #d69e2e;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid rgba(214, 158, 46, 0.3);
        }

        /* Progress bar */
        .progress-sm {
            height: 0.5rem;
            border-radius: 1rem;
            background: #edf2f7;
        }

        .progress-bar-success {
            background: linear-gradient(90deg, #48bb78, #68d391);
        }

    </style>
</head>
<body>
    <div class="qa-shell">
        <!-- Sidebar -->
        @include('qa-coordinator.partials.sidebar')

        <!-- Mobile Menu Toggle -->
        <button class="qa-menu-toggle" type="button" id="menuToggle">
            <i class="bi bi-list"></i>
        </button>

        <!-- Backdrop -->
        <div class="qa-backdrop" id="backdrop"></div>

        <!-- Main Content -->
        <main class="qa-main">
            @yield('content')
        </main>
    </div>

    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="confirmActionMessage">
                    Are you sure you want to proceed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmActionButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const toggleBtn = document.getElementById('menuToggle');
            const sidebar = document.querySelector('.qa-sidebar');
            const backdrop = document.getElementById('backdrop');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    backdrop.classList.toggle('open');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    backdrop.classList.remove('open');
                });
            }

            // Tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(el => new bootstrap.Tooltip(el));

            const confirmModalEl = document.getElementById('confirmActionModal');
            const confirmMessageEl = document.getElementById('confirmActionMessage');
            const confirmButton = document.getElementById('confirmActionButton');
            let pendingAction = null;

            window.confirmAction = function (message, onConfirm) {
                if (!confirmModalEl) {
                    onConfirm();
                    return;
                }
                pendingAction = onConfirm;
                confirmMessageEl.textContent = message || 'Are you sure you want to proceed?';
                const modal = bootstrap.Modal.getOrCreateInstance(confirmModalEl);
                modal.show();
            };

            confirmButton?.addEventListener('click', function () {
                if (pendingAction) {
                    pendingAction();
                    pendingAction = null;
                }
                const modal = bootstrap.Modal.getInstance(confirmModalEl);
                modal?.hide();
            });

        });
    </script>

    @stack('scripts')
</body>
</html>
