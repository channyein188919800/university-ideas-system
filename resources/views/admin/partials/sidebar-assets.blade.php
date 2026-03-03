@push('styles')
<style>
    .admin-shell {
        display: flex;
        min-height: calc(100vh - 72px);
        background: radial-gradient(circle at 0% 0%, #e9f2ff 0%, #f4f7fc 35%, #f8fafc 100%);
        position: relative;
    }

    .admin-sidebar {
        width: 280px;
        background: linear-gradient(180deg, #0f1f3a 0%, #15294a 100%);
        color: #cdd8ee;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        box-shadow: 0 16px 40px rgba(12, 25, 52, 0.28);
        z-index: 1100;
        transition: transform 0.25s ease;
    }

    .admin-brand {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .admin-brand h5 {
        color: #f4f7ff;
        font-weight: 700;
    }

    .admin-brand small {
        color: #98aed6;
    }

    .admin-brand-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.13);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #8ab5ff;
    }

    .admin-nav-title {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #8fa6cf;
        margin-bottom: 0.55rem;
        padding: 0 0.7rem;
    }

    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.72rem 0.78rem;
        border-radius: 10px;
        color: #d5e1f5;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 0.3rem;
    }

    .admin-nav-link i {
        width: 18px;
        text-align: center;
    }

    .admin-nav-link:hover,
    .admin-nav-link.active {
        background: rgba(122, 164, 255, 0.18);
        color: #fff;
    }

    .admin-main {
        flex: 1;
        padding: 1.5rem;
        overflow-x: hidden;
    }

    .admin-menu-toggle {
        position: fixed;
        top: 84px;
        left: 14px;
        z-index: 1200;
        background: #0f1f3a;
        color: #fff;
        border: 0;
        border-radius: 10px;
        width: 42px;
        height: 42px;
        box-shadow: 0 10px 26px rgba(12, 25, 52, 0.3);
    }

    .admin-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(6, 12, 26, 0.45);
        z-index: 1090;
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            position: fixed;
            inset: 72px auto 0 0;
            transform: translateX(-105%);
            height: calc(100vh - 72px);
        }

        .admin-sidebar.open {
            transform: translateX(0);
        }

        .admin-main {
            width: 100%;
            padding-top: 2.2rem;
        }

        .admin-backdrop.open {
            display: block;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('adminMenuToggle');
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminBackdrop');

        if (!toggleButton || !sidebar || !backdrop) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
        });

        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('open');
            backdrop.classList.remove('open');
        });
    });
</script>
@endpush
