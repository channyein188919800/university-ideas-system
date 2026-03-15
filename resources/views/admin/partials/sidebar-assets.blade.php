@push('styles')
<style>
    .admin-shell {
        display: flex;
        min-height: 100vh;
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
        
        /* Scrollable sidebar */
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
    }

    /* Custom scrollbar */
    .admin-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .admin-sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .admin-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    .admin-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    /* Firefox scrollbar */
    .admin-sidebar {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
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

    .admin-profile {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        padding: 0.85rem 0.9rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(7, 16, 33, 0.35);
    }

    .admin-profile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(138, 181, 255, 0.25), rgba(255, 255, 255, 0.12));
        border: 2px solid rgba(255, 255, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f8fbff;
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        overflow: hidden;
    }

    .admin-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .admin-profile-meta {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        min-width: 0;
    }

    .admin-profile-name {
        color: #f3f7ff;
        font-weight: 700;
        font-size: 0.98rem;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .admin-profile-role {
        color: #a9c2f0;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    /* === WHITE SYSTEM ADMINISTRATION TITLE === */
    .admin-section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 0.8rem;
        margin: 0.2rem 0 0.5rem 0;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .admin-section-title i {
        font-size: 1.2rem;
        color: #ffffff;
        opacity: 0.9;
    }

    .admin-section-title span {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        color: #ffffff;
    }

    /* Hover effect for the title */
    .admin-section-title:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
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
        display: none; /* Hidden by default, shown in mobile */
    }

    .admin-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(6, 12, 26, 0.45);
        z-index: 1090;
    }

    nav.navbar,
    footer {
        display: none !important;
    }

    /* Mobile Responsive */
    @media (max-width: 991.98px) {
        .admin-menu-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            transform: translateX(-105%);
            height: 100vh;
            z-index: 1201;
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

    /* Small screen adjustments */
    @media (max-width: 575.98px) {
        .admin-sidebar {
            width: 260px;
        }
        
        .admin-section-title {
            padding: 0.8rem 0.6rem;
            font-size: 0.85rem;
        }
        
        .admin-section-title i {
            font-size: 1rem;
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
