@extends('layouts.app')

@section('title', 'Submit Idea - University Ideas System')

@section('content')
<style>
    /* ── Page Shell ───────────────────────────────────────────────────── */
    .idea-stage {
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 2rem 1.5rem 3rem;
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 50%, #fffbeb 100%);
        position: relative;
        overflow-x: hidden;
    }

    /* Animated Background Elements */
    .idea-stage::before,
    .idea-stage::after {
        content: "";
        position: fixed;
        border-radius: 50%;
        filter: blur(100px);
        pointer-events: none;
        z-index: 0;
    }
    .idea-stage::before {
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(251, 191, 36, 0.15) 0%, rgba(245, 158, 11, 0.05) 70%);
        top: -150px;
        right: -150px;
        animation: floatBlob1 20s ease-in-out infinite;
    }
    .idea-stage::after {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, rgba(37, 99, 235, 0.04) 70%);
        bottom: -100px;
        left: -100px;
        animation: floatBlob2 25s ease-in-out infinite;
    }

    @keyframes floatBlob1 {
        0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
        33% { transform: translate(40px, 30px) scale(1.1) rotate(5deg); }
        66% { transform: translate(-20px, 50px) scale(0.95) rotate(-3deg); }
    }
    @keyframes floatBlob2 {
        0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
        33% { transform: translate(-30px, -40px) scale(1.08) rotate(-5deg); }
        66% { transform: translate(50px, 20px) scale(0.92) rotate(3deg); }
    }

    /* Floating Particles */
    .particles {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }
    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(251, 191, 36, 0.4);
        border-radius: 50%;
        animation: particleFloat 15s linear infinite;
    }
    .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 12s; }
    .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 18s; }
    .particle:nth-child(3) { left: 30%; animation-delay: 4s; animation-duration: 14s; }
    .particle:nth-child(4) { left: 50%; animation-delay: 1s; animation-duration: 20s; }
    .particle:nth-child(5) { left: 70%; animation-delay: 3s; animation-duration: 16s; }
    .particle:nth-child(6) { left: 85%; animation-delay: 5s; animation-duration: 13s; }
    .particle:nth-child(7) { left: 95%; animation-delay: 2.5s; animation-duration: 17s; }

    @keyframes particleFloat {
        0% { transform: translateY(100vh) scale(0); opacity: 0; }
        10% { opacity: 1; transform: translateY(90vh) scale(1); }
        90% { opacity: 1; }
        100% { transform: translateY(-10vh) scale(0.5); opacity: 0; }
    }

    /* ── Inner Wrapper ────────────────────────────────────────────────── */
    .idea-wrap {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 880px;
    }

    /* ── Back Link ─────────────────────────────────────────────────────── */
    .idea-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding: 0.6rem 1.2rem;
        border-radius: 100px;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        animation: slideIn 0.5s ease-out 0.1s both;
    }
    .idea-back:hover {
        color: #0f172a;
        border-color: #fbbf24;
        transform: translateX(-4px);
        box-shadow: 0 4px 20px rgba(251, 191, 36, 0.15);
    }
    .idea-back i {
        transition: transform 0.3s ease;
    }
    .idea-back:hover i {
        transform: translateX(-3px);
    }

    /* ── Main Card ─────────────────────────────────────────────────────── */
    .idea-card {
        position: relative;
        z-index: 2;
        width: 100%;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 
            0 4px 6px -1px rgba(0, 0, 0, 0.05),
            0 20px 60px -10px rgba(0, 0, 0, 0.1);
        animation: cardEntrance 0.7s cubic-bezier(0.22, 0.68, 0, 1.2) 0.2s both;
        overflow: hidden;
    }

    /* Gradient Border Effect */
    .idea-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 24px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.3), rgba(245, 158, 11, 0.1), rgba(59, 130, 246, 0.2));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    @keyframes cardEntrance {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Top Gradient Bar */
    .idea-card-bar {
        height: 4px;
        background: linear-gradient(90deg, #fbbf24, #f59e0b, #d97706, #fbbf24);
        background-size: 300% 100%;
        animation: gradientSlide 3s linear infinite;
    }
    @keyframes gradientSlide {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    /* Card Body */
    .idea-card-body {
        padding: 2.5rem 3rem 3rem;
    }

    /* ── Header Section ────────────────────────────────────────────────── */
    .idea-header {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        margin-bottom: 2.5rem;
        animation: slideIn 0.6s ease-out 0.3s both;
    }

    .idea-header-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        flex-shrink: 0;
        box-shadow: 0 10px 30px -5px rgba(251, 191, 36, 0.4);
        position: relative;
        animation: iconPulse 3s ease-in-out infinite;
    }
    .idea-header-icon::after {
        content: "✨";
        position: absolute;
        top: -6px;
        right: -6px;
        font-size: 0.9rem;
        animation: sparkleFloat 2s ease-in-out infinite;
    }

    @keyframes iconPulse {
        0%, 100% { box-shadow: 0 10px 30px -5px rgba(251, 191, 36, 0.4); }
        50% { box-shadow: 0 15px 40px -5px rgba(251, 191, 36, 0.5); }
    }
    @keyframes sparkleFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: 1; }
        50% { transform: translateY(-5px) scale(1.2); opacity: 0.8; }
    }

    .idea-header-content h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.4rem;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }
    .idea-header-content p {
        margin: 0;
        color: #64748b;
        font-size: 1.05rem;
    }

    /* ── Section Styling ───────────────────────────────────────────────── */
    .idea-section {
        margin-bottom: 2rem;
        animation: slideIn 0.6s ease-out both;
    }
    .idea-section:nth-child(1) { animation-delay: 0.4s; }
    .idea-section:nth-child(2) { animation-delay: 0.5s; }
    .idea-section:nth-child(3) { animation-delay: 0.6s; }

    .idea-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .idea-section-title i {
        color: #fbbf24;
        font-size: 0.9rem;
    }

    .idea-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
        margin: 2rem 0;
    }

    /* ── Form Fields ───────────────────────────────────────────────────── */
    .idea-field {
        margin-bottom: 1.5rem;
    }

    .idea-label {
        display: block;
        margin-bottom: 0.6rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
    }
    .idea-label .required {
        color: #ef4444;
        margin-left: 2px;
    }
    .idea-label .optional {
        color: #94a3b8;
        font-weight: 400;
        font-size: 0.85rem;
    }

    .idea-input-wrapper {
        position: relative;
    }
    .idea-input-wrapper > i {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.1rem;
        transition: color 0.3s ease;
        pointer-events: none;
    }
    .idea-input-wrapper:focus-within > i {
        color: #fbbf24;
    }

    .idea-input,
    .idea-textarea,
    .idea-select {
        width: 100%;
        padding: 1rem 1.1rem 1rem 3rem;
        font-size: 1rem;
        font-family: inherit;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
        color: #0f172a;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
    }
    .idea-textarea {
        padding-left: 1.1rem;
        resize: vertical;
        min-height: 150px;
        line-height: 1.6;
    }
    .idea-input::placeholder,
    .idea-textarea::placeholder {
        color: #94a3b8;
    }
    .idea-input:focus,
    .idea-textarea:focus,
    .idea-select:focus {
        border-color: #fbbf24;
        box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
        transform: translateY(-1px);
    }
    .idea-input.is-invalid,
    .idea-textarea.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    /* Hint Text */
    .idea-hint {
        display: flex;
        align-items: flex-start;
        gap: 0.4rem;
        margin-top: 0.6rem;
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.5;
    }
    .idea-hint i {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Error Message */
    .idea-error {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #ef4444;
        animation: shake 0.4s ease;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* ── Custom Category Dropdown ──────────────────────────────────────── */
    .category-dropdown {
        position: relative;
        z-index: 10;
    }
    .category-dropdown.open {
        z-index: 20;
    }

    .category-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.1rem;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
        font-size: 1rem;
        font-family: inherit;
        cursor: pointer;
        text-align: left;
        transition: all 0.3s ease;
        outline: none;
    }
    .category-trigger:hover {
        border-color: #cbd5e1;
    }
    .category-trigger.open {
        border-color: #fbbf24;
        box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
    }
    .category-trigger.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .category-trigger-icon {
        color: #94a3b8;
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: color 0.3s ease;
    }
    .category-trigger.open .category-trigger-icon {
        color: #fbbf24;
    }

    .category-placeholder {
        color: #94a3b8;
        flex: 1;
    }

    .category-chips {
        flex: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .category-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.7rem;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 1px solid #fbbf24;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #92400e;
        animation: chipIn 0.3s ease;
    }
    @keyframes chipIn {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    .category-chip-remove {
        cursor: pointer;
        color: #b45309;
        font-size: 0.75rem;
        transition: color 0.2s;
        line-height: 1;
    }
    .category-chip-remove:hover {
        color: #ef4444;
    }

    .category-caret {
        color: #94a3b8;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }
    .category-trigger.open .category-caret {
        transform: rotate(180deg);
        color: #fbbf24;
    }

    /* Dropdown Panel */
    .category-panel {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: #fff;
        background-clip: padding-box;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        z-index: 20;
        animation: dropdownOpen 0.25s ease;
    }
    .category-panel.open {
        display: block;
        position: relative;
        top: 8px;
        left: auto;
        right: auto;
    }
    @keyframes dropdownOpen {
        from {
            opacity: 0;
            transform: translateY(-10px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .category-search {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .category-search i {
        color: #94a3b8;
    }
    .category-search input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 0.95rem;
        font-family: inherit;
        color: #0f172a;
        background: transparent;
    }
    .category-search input::placeholder {
        color: #94a3b8;
    }

    .category-list {
        max-height: 220px;
        overflow-y: auto;
        padding: 0.5rem;
    }
    .category-list::-webkit-scrollbar {
        width: 6px;
    }
    .category-list::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 3px;
    }

    .category-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 2px;
    }
    .category-item:hover {
        background: #f8fafc;
    }
    .category-item.selected {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
    }
    .category-item input[type="checkbox"] {
        display: none;
    }

    .category-check {
        width: 22px;
        height: 22px;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .category-item.selected .category-check {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        border-color: #fbbf24;
        color: white;
        animation: checkPop 0.3s ease;
    }
    @keyframes checkPop {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .category-item-label {
        font-size: 0.95rem;
        color: #334155;
        flex: 1;
    }
    .category-item.selected .category-item-label {
        color: #92400e;
        font-weight: 600;
    }

    .category-empty {
        padding: 2rem;
        text-align: center;
        color: #94a3b8;
    }
    .category-empty i {
        display: block;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    /* ── Anonymous Toggle ──────────────────────────────────────────────── */
    .idea-toggle-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #fef3c7, #fffbeb);
        border: 2px solid #fde68a;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .idea-toggle-wrapper:hover {
        border-color: #fbbf24;
        box-shadow: 0 4px 20px rgba(251, 191, 36, 0.15);
    }

    .idea-switch {
        position: relative;
        width: 52px;
        height: 28px;
        flex-shrink: 0;
    }
    .idea-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .idea-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #cbd5e1;
        border-radius: 100px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .idea-slider::before {
        content: "";
        position: absolute;
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .idea-switch input:checked + .idea-slider {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
    }
    .idea-switch input:checked + .idea-slider::before {
        transform: translateX(24px);
    }

    .idea-toggle-content {
        flex: 1;
    }
    .idea-toggle-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .idea-toggle-title i {
        color: #f59e0b;
    }
    .idea-toggle-desc {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    /* ── File Upload ───────────────────────────────────────────────────── */
    .idea-upload {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #fafafa;
        cursor: pointer;
    }
    .idea-upload:hover,
    .idea-upload.drag-over {
        border-color: #fbbf24;
        background: linear-gradient(135deg, #fef3c7, #fffbeb);
    }
    .idea-upload input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .idea-upload-icon {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }
    .idea-upload:hover .idea-upload-icon {
        color: #fbbf24;
        transform: translateY(-5px);
    }

    .idea-upload-text {
        font-size: 1rem;
        color: #64748b;
    }
    .idea-upload-text strong {
        color: #0f172a;
    }

    .idea-upload-hint {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 0.5rem;
    }

    .idea-file-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .idea-file-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.8rem;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 500;
        color: #1e40af;
        animation: chipIn 0.3s ease;
    }

    /* ── Alert ─────────────────────────────────────────────────────────── */
    .idea-alert {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 1rem 1.25rem;
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        color: #b91c1c;
        font-size: 0.95rem;
        animation: slideIn 0.4s ease;
    }

    /* ── Submit Section ────────────────────────────────────────────────── */
    .idea-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid #f1f5f9;
        animation: slideIn 0.6s ease-out 0.7s both;
    }

    .idea-btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.9rem 1.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .idea-btn-cancel:hover {
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateX(-3px);
    }

    .idea-btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px -5px rgba(251, 191, 36, 0.5);
    }
    .idea-btn-submit::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }
    .idea-btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px -5px rgba(251, 191, 36, 0.6);
    }
    .idea-btn-submit:hover::before {
        left: 100%;
    }
    .idea-btn-submit:active {
        transform: translateY(-1px);
    }

    /* ── Tips Panel ────────────────────────────────────────────────────── */
    .idea-tips {
        position: relative;
        margin-top: 2rem;
        padding: 2rem 2.5rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
        animation: cardEntrance 0.7s cubic-bezier(0.22, 0.68, 0, 1.2) 0.4s both;
    }
    .idea-tips::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 20px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.3), rgba(5, 150, 105, 0.1));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .idea-tips-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1.25rem;
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
    }
    .idea-tips-header i {
        color: #10b981;
        font-size: 1.25rem;
    }

    .idea-tip {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.6;
    }
    .idea-tip:last-child {
        border-bottom: none;
    }
    .idea-tip-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        font-size: 0.7rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ── Animations ────────────────────────────────────────────────────── */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ── Responsive ────────────────────────────────────────────────────── */
    @media (max-width: 640px) {
        .idea-card-body {
            padding: 1.75rem 1.5rem 2rem;
        }
        .idea-header {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        .idea-header-content h1 {
            font-size: 1.6rem;
        }
        .idea-actions {
            flex-direction: column-reverse;
        }
        .idea-btn-cancel,
        .idea-btn-submit {
            width: 100%;
            justify-content: center;
        }
        .idea-tips {
            padding: 1.5rem;
        }
    }
</style>

<div class="idea-stage">
    <!-- Floating Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="idea-wrap">
        {{-- Back Link --}}
        <a href="{{ route('ideas.index') }}" class="idea-back">
            <i class="bi bi-arrow-left"></i>
            Back to Ideas
        </a>

        {{-- Main Card --}}
        <div class="idea-card">
            <div class="idea-card-bar"></div>
            <div class="idea-card-body">
                {{-- Header --}}
                <div class="idea-header">
                    <div class="idea-header-icon">
                        <i class="bi bi-lightbulb-fill"></i>
                    </div>
                    <div class="idea-header-content">
                        <h1>Submit Your Idea</h1>
                        <p>Share innovative ideas to help improve our university</p>
                    </div>
                </div>

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="idea-alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>Please fix the errors below before submitting.</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('ideas.store') }}" enctype="multipart/form-data" id="ideaForm">
                    @csrf

                    {{-- Section: Idea Details --}}
                    <div class="idea-section">
                        <div class="idea-section-title">
                            <i class="bi bi-stars"></i>
                            Idea Details
                        </div>

                        {{-- Title Field --}}
                        <div class="idea-field">
                            <label class="idea-label" for="title">
                                Idea Title <span class="required">*</span>
                            </label>
                            <div class="idea-input-wrapper">
                                <i class="bi bi-fonts"></i>
                                <input type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    class="idea-input @error('title') is-invalid @enderror"
                                    placeholder="Enter a clear and concise title for your idea"
                                    required>
                            </div>
                            @error('title')
                                <div class="idea-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Description Field --}}
                        <div class="idea-field">
                            <label class="idea-label" for="description">
                                Description <span class="required">*</span>
                            </label>
                            <textarea id="description"
                                name="description"
                                class="idea-textarea @error('description') is-invalid @enderror"
                                rows="6"
                                placeholder="Describe your idea in detail. What problem does it solve? How will it benefit the university?"
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="idea-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="idea-hint">
                                <i class="bi bi-info-circle"></i>
                                <span>Be as specific as possible. Include relevant details that help others understand your idea.</span>
                            </div>
                        </div>
                    </div>

                    <div class="idea-divider"></div>

                    {{-- Section: Category & Visibility --}}
                    <div class="idea-section">
                        <div class="idea-section-title">
                            <i class="bi bi-tags"></i>
                            Category & Visibility
                        </div>

                        {{-- Categories Dropdown --}}
                        <div class="idea-field">
                            <label class="idea-label">
                                Categories <span class="required">*</span>
                            </label>

                            {{-- Hidden inputs for form submission --}}
                            <div id="categoryHidden"></div>

                            {{-- Custom Dropdown --}}
                            <div class="category-dropdown" id="categoryDropdown">
                                <button type="button"
                                    class="category-trigger @error('categories') is-invalid @enderror"
                                    id="categoryTrigger"
                                    aria-haspopup="listbox"
                                    aria-expanded="false">
                                    <i class="bi bi-tags category-trigger-icon"></i>
                                    <span class="category-placeholder" id="categoryPlaceholder">Select categories...</span>
                                    <span class="category-chips" id="categoryChips" style="display: none;"></span>
                                    <i class="bi bi-chevron-down category-caret"></i>
                                </button>

                                {{-- Dropdown Panel --}}
                                <div class="category-panel" id="categoryPanel" role="listbox" aria-multiselectable="true">
                                    <div class="category-search">
                                        <i class="bi bi-search"></i>
                                        <input type="text"
                                            id="categorySearch"
                                            placeholder="Search categories..."
                                            autocomplete="off">
                                    </div>
                                    <div class="category-list" id="categoryList">
                                        @foreach($categories as $category)
                                            <label class="category-item"
                                                data-value="{{ $category->id }}"
                                                data-label="{{ $category->name }}">
                                                <input type="checkbox"
                                                    value="{{ $category->id }}"
                                                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                                <span class="category-check">
                                                    <i class="bi bi-check2"></i>
                                                </span>
                                                <span class="category-item-label">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="category-empty" id="categoryEmpty" style="display: none;">
                                        <i class="bi bi-emoji-frown"></i>
                                        No categories found
                                    </div>
                                </div>
                            </div>

                            @error('categories')
                                <div class="idea-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="idea-hint">
                                <i class="bi bi-info-circle"></i>
                                <span>Click the dropdown to select one or more categories.</span>
                            </div>
                        </div>

                        {{-- Anonymous Toggle --}}
                        <div class="idea-field">
                            <label class="idea-toggle-wrapper" for="is_anonymous">
                                <div class="idea-switch">
                                    <input type="checkbox"
                                        id="is_anonymous"
                                        name="is_anonymous"
                                        value="1"
                                        {{ old('is_anonymous') ? 'checked' : '' }}>
                                    <span class="idea-slider"></span>
                                </div>
                                <div class="idea-toggle-content">
                                    <div class="idea-toggle-title">
                                        <i class="bi bi-incognito"></i>
                                        Submit Anonymously
                                    </div>
                                    <div class="idea-toggle-desc">
                                        Your identity is stored for admin purposes, but won't be shown publicly.
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="idea-divider"></div>

                    {{-- Section: Supporting Documents --}}
                    <div class="idea-section">
                        <div class="idea-section-title">
                            <i class="bi bi-paperclip"></i>
                            Supporting Documents <span class="optional">(optional)</span>
                        </div>

                        <div class="idea-field">
                            <div class="idea-upload" id="uploadArea">
                                <input type="file"
                                    id="documents"
                                    name="documents[]"
                                    multiple
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif"
                                    class="@error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror">
                                <div class="idea-upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <div class="idea-upload-text">
                                    <strong>Click to upload</strong> or drag & drop
                                </div>
                                <div class="idea-upload-hint">
                                    PDF, Word, Excel, PowerPoint, Images • Max 10 MB each
                                </div>
                            </div>
                            @error('documents')
                                <div class="idea-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            @error('documents.*')
                                <div class="idea-error">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="idea-file-list" id="fileList"></div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="idea-actions">
                        <a href="{{ route('ideas.index') }}" class="idea-btn-cancel">
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </a>
                        <button type="submit" class="idea-btn-submit" id="submitBtn">
                            <i class="bi bi-send-fill"></i>
                            Submit Idea
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tips Panel --}}
        <div class="idea-tips">
            <div class="idea-tips-header">
                <i class="bi bi-stars"></i>
                Tips for a Great Idea
            </div>
            <div class="idea-tip">
                <span class="idea-tip-icon"><i class="bi bi-check2"></i></span>
                <span>Be clear and specific about what you want to achieve.</span>
            </div>
            <div class="idea-tip">
                <span class="idea-tip-icon"><i class="bi bi-check2"></i></span>
                <span>Explain the problem your idea solves and who benefits.</span>
            </div>
            <div class="idea-tip">
                <span class="idea-tip-icon"><i class="bi bi-check2"></i></span>
                <span>Include any potential benefits, cost savings, or improvements.</span>
            </div>
            <div class="idea-tip">
                <span class="idea-tip-icon"><i class="bi bi-check2"></i></span>
                <span>Attach supporting documents to strengthen your submission.</span>
            </div>
            <div class="idea-tip">
                <span class="idea-tip-icon"><i class="bi bi-check2"></i></span>
                <span>Consider how your idea could realistically be implemented.</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    /* ── Category Dropdown ─────────────────────────────────────────────── */
    const dropdown = document.getElementById('categoryDropdown');
    const trigger = document.getElementById('categoryTrigger');
    const panel = document.getElementById('categoryPanel');
    const placeholder = document.getElementById('categoryPlaceholder');
    const chipsContainer = document.getElementById('categoryChips');
    const hiddenContainer = document.getElementById('categoryHidden');
    const searchInput = document.getElementById('categorySearch');
    const categoryList = document.getElementById('categoryList');
    const emptyMessage = document.getElementById('categoryEmpty');
    const items = categoryList.querySelectorAll('.category-item');

    function updateCategories() {
        const selected = [];
        items.forEach(item => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                selected.push({
                    value: item.dataset.value,
                    label: item.dataset.label
                });
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });

        // Update hidden inputs
        hiddenContainer.innerHTML = '';
        selected.forEach(s => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'categories[]';
            input.value = s.value;
            hiddenContainer.appendChild(input);
        });

        // Update chips display
        if (selected.length === 0) {
            placeholder.style.display = '';
            chipsContainer.style.display = 'none';
            chipsContainer.innerHTML = '';
        } else {
            placeholder.style.display = 'none';
            chipsContainer.style.display = 'flex';
            chipsContainer.innerHTML = selected.map(s =>
                `<span class="category-chip">
                    ${s.label}
                    <span class="category-chip-remove" data-value="${s.value}">&times;</span>
                </span>`
            ).join('');

            // Add remove handlers
            chipsContainer.querySelectorAll('.category-chip-remove').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const value = btn.dataset.value;
                    items.forEach(item => {
                        if (item.dataset.value === value) {
                            item.querySelector('input[type="checkbox"]').checked = false;
                        }
                    });
                    updateCategories();
                });
            });
        }
    }

    // Toggle dropdown
    trigger.addEventListener('click', () => {
        const isOpen = panel.classList.toggle('open');
        trigger.classList.toggle('open', isOpen);
        dropdown && dropdown.classList.toggle('open', isOpen);
        trigger.setAttribute('aria-expanded', isOpen);
        if (isOpen) {
            searchInput.value = '';
            filterCategories('');
            searchInput.focus();
        }
    });

    // Checkbox change
    items.forEach(item => {
        item.addEventListener('change', updateCategories);
    });

    // Search filter
    function filterCategories(query) {
        let count = 0;
        items.forEach(item => {
            const matches = item.dataset.label.toLowerCase().includes(query.toLowerCase());
            item.style.display = matches ? '' : 'none';
            if (matches) count++;
        });
        emptyMessage.style.display = count === 0 ? '' : 'none';
    }

    searchInput.addEventListener('input', () => filterCategories(searchInput.value));

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (panel.classList.contains('open') &&
            !trigger.contains(e.target) &&
            !panel.contains(e.target)) {
            panel.classList.remove('open');
            trigger.classList.remove('open');
            dropdown && dropdown.classList.remove('open');
            trigger.setAttribute('aria-expanded', 'false');
        }
    });

    // Initialize
    updateCategories();

    /* ── File Upload ───────────────────────────────────────────────────── */
    const fileInput = document.getElementById('documents');
    const fileList = document.getElementById('fileList');
    const uploadArea = document.getElementById('uploadArea');

    fileInput.addEventListener('change', () => {
        fileList.innerHTML = '';
        Array.from(fileInput.files).forEach(file => {
            const chip = document.createElement('span');
            chip.className = 'idea-file-chip';
            chip.innerHTML = `<i class="bi bi-file-earmark-check"></i> ${file.name}`;
            fileList.appendChild(chip);
        });
    });

    // Drag & Drop
    ['dragenter', 'dragover'].forEach(event => {
        uploadArea.addEventListener(event, (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });
    });

    ['dragleave', 'drop'].forEach(event => {
        uploadArea.addEventListener(event, (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            if (event === 'drop' && e.dataTransfer.files) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    });

    /* ── Form Submit ───────────────────────────────────────────────────── */
    const form = document.getElementById('ideaForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', () => {
        if (form.checkValidity()) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Submitting...
            `;
        }
    });

});
</script>
@endsection
