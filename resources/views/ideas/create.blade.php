@extends('layouts.app')

@section('title', 'Submit Idea - University Ideas System')

@section('content')
<style>
    /* ── Color Variables ───────────────────────────────────────────────── */
    :root {
        --primary-navy: #0A4076;
        --primary-navy-light: #1a5090;
        --primary-navy-dark: #08305a;
        --secondary-light: #D2E9FF;
        --accent-gold: #EAB529;
        --text-dark: #0f172a;
        --text-gray: #475569;
        --text-light: #64748b;
        --border-light: #e2e8f0;
        --gradient-navy: linear-gradient(135deg, #0A4076 0%, #1a5090 50%, #08305a 100%);
        --gradient-navy-soft: linear-gradient(135deg, rgba(10, 64, 118, 0.1) 0%, rgba(26, 80, 144, 0.05) 100%);
    }

    /* ── Page Shell ───────────────────────────────────────────────────── */
    .idea-stage {
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 2rem 1.5rem 3rem;
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 50%, #e6f0fa 100%);
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
        background: radial-gradient(circle, rgba(10, 64, 118, 0.12) 0%, rgba(26, 80, 144, 0.05) 70%);
        top: -150px;
        right: -150px;
        animation: floatBlob1 20s ease-in-out infinite;
    }
    .idea-stage::after {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(10, 64, 118, 0.1) 0%, rgba(26, 80, 144, 0.03) 70%);
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
        background: rgba(10, 64, 118, 0.3);
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
        color: var(--text-gray);
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
        color: var(--primary-navy);
        border-color: var(--primary-navy);
        transform: translateX(-4px);
        box-shadow: 0 4px 20px rgba(10, 64, 118, 0.15);
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
        background: linear-gradient(135deg, rgba(10, 64, 118, 0.4), rgba(26, 80, 144, 0.2), rgba(210, 233, 255, 0.3));
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
        background: linear-gradient(90deg, #0A4076, #1a5090, #2a60a0, #0A4076);
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
        background: var(--gradient-navy);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        flex-shrink: 0;
        box-shadow: 0 10px 30px -5px rgba(10, 64, 118, 0.4);
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
        0%, 100% { box-shadow: 0 10px 30px -5px rgba(10, 64, 118, 0.4); }
        50% { box-shadow: 0 15px 40px -5px rgba(10, 64, 118, 0.5); }
    }
    @keyframes sparkleFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: 1; }
        50% { transform: translateY(-5px) scale(1.2); opacity: 0.8; }
    }

    .idea-header-content h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-navy);
        margin: 0 0 0.4rem;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }
    .idea-header-content p {
        margin: 0;
        color: var(--text-gray);
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
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .idea-section-title i {
        color: var(--primary-navy);
        font-size: 0.9rem;
    }

    .idea-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--border-light) 20%, var(--border-light) 80%, transparent);
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
        color: var(--text-dark);
    }
    .idea-label .required {
        color: #ef4444;
        margin-left: 2px;
    }
    .idea-label .optional {
        color: var(--text-light);
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
        color: var(--text-light);
        font-size: 1.1rem;
        transition: color 0.3s ease;
        pointer-events: none;
    }
    .idea-input-wrapper:focus-within > i {
        color: var(--primary-navy);
    }

    .idea-input,
    .idea-textarea,
    .idea-select {
        width: 100%;
        padding: 1rem 1.1rem 1rem 3rem;
        font-size: 1rem;
        font-family: inherit;
        border: 2px solid var(--border-light);
        border-radius: 14px;
        background: #fff;
        color: var(--text-dark);
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
        color: var(--text-light);
    }
    .idea-input:focus,
    .idea-textarea:focus,
    .idea-select:focus {
        border-color: var(--primary-navy);
        box-shadow: 0 0 0 4px rgba(10, 64, 118, 0.1);
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
        color: var(--text-gray);
        line-height: 1.5;
    }
    .idea-hint i {
        color: var(--text-light);
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
        border: 2px solid var(--border-light);
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
        border-color: var(--primary-navy);
        box-shadow: 0 0 0 4px rgba(10, 64, 118, 0.1);
    }
    .category-trigger.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .category-trigger-icon {
        color: var(--text-light);
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: color 0.3s ease;
    }
    .category-trigger.open .category-trigger-icon {
        color: var(--primary-navy);
    }

    .category-placeholder {
        color: var(--text-light);
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
        background: linear-gradient(135deg, #D2E9FF, #b5d8ff);
        border: 1px solid var(--primary-navy);
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary-navy);
        animation: chipIn 0.3s ease;
    }
    @keyframes chipIn {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    .category-chip-remove {
        cursor: pointer;
        color: var(--primary-navy);
        font-size: 0.75rem;
        transition: color 0.2s;
        line-height: 1;
    }
    .category-chip-remove:hover {
        color: #ef4444;
    }

    .category-caret {
        color: var(--text-light);
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }
    .category-trigger.open .category-caret {
        transform: rotate(180deg);
        color: var(--primary-navy);
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
        border: 2px solid var(--border-light);
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
        color: var(--text-light);
    }
    .category-search input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 0.95rem;
        font-family: inherit;
        color: var(--text-dark);
        background: transparent;
    }
    .category-search input::placeholder {
        color: var(--text-light);
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
        background: var(--border-light);
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
        background: linear-gradient(135deg, #D2E9FF, #b5d8ff);
    }
    .category-item input[type="checkbox"] {
        display: none;
    }

    .category-check {
        width: 22px;
        height: 22px;
        border: 2px solid var(--border-light);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .category-item.selected .category-check {
        background: var(--gradient-navy);
        border-color: var(--primary-navy);
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
        color: var(--text-gray);
        flex: 1;
    }
    .category-item.selected .category-item-label {
        color: var(--primary-navy);
        font-weight: 600;
    }

    .category-empty {
        padding: 2rem;
        text-align: center;
        color: var(--text-light);
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
        background: linear-gradient(135deg, #D2E9FF, #e6f0fa);
        border: 2px solid var(--primary-navy-light);
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .idea-toggle-wrapper:hover {
        border-color: var(--primary-navy);
        box-shadow: 0 4px 20px rgba(10, 64, 118, 0.15);
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
        background: var(--gradient-navy);
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
        color: var(--primary-navy);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .idea-toggle-title i {
        color: var(--primary-navy);
    }
    .idea-toggle-desc {
        font-size: 0.85rem;
        color: var(--text-gray);
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
        border-color: var(--primary-navy);
        background: linear-gradient(135deg, #D2E9FF, #e6f0fa);
    }
    .idea-upload input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .idea-upload-icon {
        font-size: 2.5rem;
        color: var(--text-light);
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }
    .idea-upload:hover .idea-upload-icon {
        color: var(--primary-navy);
        transform: translateY(-5px);
    }

    .idea-upload-text {
        font-size: 1rem;
        color: var(--text-gray);
    }
    .idea-upload-text strong {
        color: var(--primary-navy);
    }

    .idea-upload-hint {
        font-size: 0.85rem;
        color: var(--text-light);
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
        background: linear-gradient(135deg, #D2E9FF, #b5d8ff);
        border: 1px solid var(--primary-navy);
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--primary-navy);
        animation: chipIn 0.3s ease;
    }
    .idea-file-chip-name {
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .idea-file-remove {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border: none;
        border-radius: 50%;
        background: rgba(10, 64, 118, 0.12);
        color: var(--primary-navy);
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
    }
    .idea-file-remove:hover {
        background: var(--primary-navy);
        color: #fff;
        transform: scale(1.05);
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

    /* ── Terms & Conditions Checkbox ───────────────────────────────────── */
    .terms-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border-light);
        border-radius: 16px;
        margin: 2rem 0 1.5rem;
        transition: all 0.3s ease;
    }
    .terms-wrapper:hover {
        border-color: var(--primary-navy);
    }
    .terms-wrapper.error {
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.05);
        animation: shake 0.4s ease;
    }

    .terms-checkbox {
        position: relative;
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }
    .terms-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    .terms-checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 24px;
        width: 24px;
        background-color: #fff;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .terms-checkbox:hover input ~ .terms-checkmark {
        border-color: var(--primary-navy);
    }
    .terms-checkbox input:checked ~ .terms-checkmark {
        background: var(--gradient-navy);
        border-color: var(--primary-navy);
    }
    .terms-checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    .terms-checkbox input:checked ~ .terms-checkmark:after {
        display: block;
    }
    .terms-checkbox .terms-checkmark:after {
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .terms-content {
        flex: 1;
    }
    .terms-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-dark);
        margin-bottom: 0.2rem;
    }
    .terms-text {
        font-size: 0.85rem;
        color: var(--text-gray);
    }
    .terms-text a {
        color: var(--primary-navy);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .terms-text a:hover {
        color: var(--primary-navy-dark);
        text-decoration: underline;
    }

    .terms-error {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin: -1rem 0 1rem 2.5rem;
        font-size: 0.85rem;
        color: #ef4444;
    }

    /* ── Modal Styles for Terms & Conditions ───────────────────────────── */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: modalFadeIn 0.3s ease;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-container {
        max-width: 1000px;
        max-height: 90vh;
        width: 100%;
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5);
        animation: modalSlideIn 0.4s cubic-bezier(0.22, 0.68, 0, 1.2);
        position: relative;
        border-top: 8px solid var(--primary-navy);
    }
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        background: var(--primary-navy);
        color: white;
        padding: 30px 40px;
        position: relative;
    }
    .modal-header h2 {
        margin: 0 0 8px 0;
        font-size: 2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
    }
    .modal-header p {
        margin: 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }
    .modal-header .last-updated {
        position: absolute;
        bottom: 15px;
        right: 40px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .modal-content {
        padding: 40px;
        overflow-y: auto;
        max-height: calc(90vh - 200px);
        background: #fafafa;
    }

    /* Terms content styling */
    .modal-content h1 {
        color: var(--primary-navy);
        font-size: 2rem;
        margin: 30px 0 15px 0;
        border-bottom: 2px solid var(--secondary-light);
        padding-bottom: 8px;
    }
    .modal-content h1:first-of-type {
        margin-top: 0;
    }
    .modal-content h2 {
        color: var(--primary-navy);
        font-size: 1.5rem;
        margin: 25px 0 10px 0;
        border-bottom: 2px solid var(--secondary-light);
        padding-bottom: 8px;
    }
    .modal-content h3 {
        color: var(--primary-navy);
        font-size: 1.2rem;
        margin: 15px 0 8px 0;
    }
    .modal-content p {
        color: #444;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    .modal-content ul, .modal-content ol {
        margin-left: 25px;
        margin-bottom: 20px;
    }
    .modal-content li {
        color: #444;
        margin-bottom: 8px;
        line-height: 1.6;
    }
    .modal-content .highlight-box {
        background: #fdf8e9;
        border-left: 5px solid var(--accent-gold);
        padding: 20px;
        margin: 25px 0;
        font-style: italic;
        border-radius: 0 8px 8px 0;
    }
    .modal-content .contact-info {
        background: #f4f7fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        margin: 20px 0;
    }
    .modal-content a {
        color: var(--accent-gold);
        text-decoration: none;
    }
    .modal-content a:hover {
        text-decoration: underline;
    }

    .modal-footer {
        padding: 20px 40px;
        background: #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        border-top: 1px solid var(--border-light);
    }
    .modal-btn {
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.95rem;
    }
    .modal-btn-primary {
        background: var(--gradient-navy);
        color: white;
        box-shadow: 0 4px 15px -5px var(--primary-navy);
    }
    .modal-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px var(--primary-navy);
    }
    .modal-btn-secondary {
        background: white;
        color: var(--text-gray);
        border: 2px solid var(--border-light);
    }
    .modal-btn-secondary:hover {
        border-color: var(--primary-navy);
        color: var(--primary-navy);
    }

    /* ── Submit Section (without cancel button) ────────────────────────── */
    .idea-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 2rem;
        border-top: 1px solid #f1f5f9;
        animation: slideIn 0.6s ease-out 0.7s both;
    }

    .idea-btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 1rem 3rem;
        border: none;
        border-radius: 12px;
        background: var(--gradient-navy);
        color: white;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px -5px rgba(10, 64, 118, 0.5);
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
        box-shadow: 0 12px 35px -5px rgba(10, 64, 118, 0.6);
    }
    .idea-btn-submit:hover::before {
        left: 100%;
    }
    .idea-btn-submit:active {
        transform: translateY(-1px);
    }
    .idea-btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
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
        background: linear-gradient(135deg, rgba(10, 64, 118, 0.3), rgba(26, 80, 144, 0.1));
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
        color: var(--primary-navy);
    }
    .idea-tips-header i {
        color: var(--primary-navy);
        font-size: 1.25rem;
    }

    .idea-tip {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
        color: var(--text-gray);
        line-height: 1.6;
    }
    .idea-tip:last-child {
        border-bottom: none;
    }
    .idea-tip-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, #D2E9FF, #b5d8ff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-navy);
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
            justify-content: center;
        }
        .idea-btn-submit {
            width: 100%;
            justify-content: center;
        }
        .idea-tips {
            padding: 1.5rem;
        }
        .modal-header {
            padding: 20px;
        }
        .modal-header h2 {
            font-size: 1.5rem;
        }
        .modal-content {
            padding: 20px;
        }
        .modal-footer {
            padding: 15px 20px;
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

                    {{-- Terms & Conditions Checkbox (Added here) --}}
                    <div class="terms-wrapper" id="termsWrapper">
                        <label class="terms-checkbox">
                            <input type="checkbox" name="terms_accepted" id="termsCheckbox" value="1" {{ old('terms_accepted') ? 'checked' : '' }}>
                            <span class="terms-checkmark"></span>
                        </label>
                        <div class="terms-content">
                            <div class="terms-title">Terms & Conditions</div>
                            <div class="terms-text">
                                I have read and agree to the 
                                <a href="#" id="showTermsModal">Privacy Policy and Terms of Use</a>
                            </div>
                        </div>
                    </div>
                    <div class="terms-error" id="termsError" style="display: none;">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        You must agree to the terms and conditions to submit your idea.
                    </div>

                    {{-- Actions (Cancel button removed) --}}
                    <div class="idea-actions">
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

{{-- Terms & Conditions Modal --}}
<div class="modal-overlay" id="termsModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Privacy & Policy</h2>
            <p>University Idea Management System</p>
            <span class="last-updated">Last updated: February 21, 2026</span>
            <button class="modal-close" id="closeModalBtn">&times;</button>
        </div>
        <div class="modal-content">
            <p>This Privacy Policy describes Our policies and procedures on the collection, use and disclosure of Your information when You use the Service and tells You about Your privacy rights and how the law protects You.</p>
            
            <div class="highlight-box">
               We use Your Personal Data to provide and improve the Service. By using the Service, You agree to the collection and use of information in accordance with this Privacy Policy. This Privacy Policy has been created with the help of the Privacy Policy Generator.
            </div>

           
            <h2>Interpretation and Definitions</h2>
            <h3>Interpretation</h3>
            <p>The words whose initial letters are capitalized have meanings defined under the following conditions. The following definitions shall have the same meaning regardless of whether they appear in singular or in plural.</p>

            <h3>Definitions</h3>
             <p>For the purposes of this Privacy Policy:</p>
            <ul>
                <li><b>Account</b> means a unique account created for You to access our Service or parts of our Service.</li>
                <li><b>Company</b>means an entity that controls, is controlled by, or is under common control with a party, where "control" means ownership of 50% or more of the shares, equity interest or other securities entitled to vote for election of directors or other managing authority.               
                 <li><b>Company</b>(referred to as either "the Company", "We", "Us" or "Our" in this Privacy Policy) refers to University Ideas.</li>
                  <li><b>Cookies</b>are small files that are placed on Your computer, mobile device or any other device by a website, containing the details of Your browsing history on that website among its many uses.</li>
                <li><b>Country</b> refers to: Myanmar (Burma)</li>    
                 <li><b>Device </b>means any device that can access the Service such as a computer, a cell phone or a digital tablet.</li>
                  <li><b>Personal Data </b> (or "Personal Information") is any information that relates to an identified or identifiable individual.</li>
                  <p>We use "Personal Data" and "Personal Information" interchangeably unless a law uses a specific term.</p>
                <li><b>Service </b> refers to the Website.</li>              
                 <li><b>Service Provider</b>means any natural or legal person who processes the data on behalf of the Company. It refers to third-party companies or individuals employed by the Company to facilitate the Service, to provide the Service on behalf of the Company, to perform services related to the Service or to assist the Company in analyzing how the Service is used.</li>
                  <li><b>Usage Data</b> refers to data collected automatically, either generated by the use of the Service or from the Service infrastructure itself (for example, the duration of a page visit).</li>
                <li><b>Website </b>  refers to University Ideas, accessible from [University Ideas](University Ideas).</li>          
                 <li><b>You  </b>means the individual accessing or using the Service, or the company, or other legal entity on behalf of which such individual is accessing or using the Service, as applicable.</li>
            </ul>

            <h2>Collecting and Using Your Personal Data</h2>
            <h3>Types of Data Collected</h3>
            <p><strong>Personal Data</strong> 
           While using Our Service, We may ask You to provide Us with certain personally identifiable information that can be used to contact or identify You. Personally identifiable information may include, but is not limited to:</p>
           <ul>
                <li>Email address</li>
            </ul>
            
            <p><strong>Usage Data</strong> Usage Data is collected automatically when using the Service.</p>
            <p>Usage Data may include information such as Your Device's Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that You visit, the time and date of Your visit, the time spent on those pages, unique device identifiers and other diagnostic data.</p>
            <p>When You access the Service by or through a mobile device, We may collect certain information automatically, including, but not limited to, the type of mobile device You use, Your mobile device's unique ID, the IP address of Your mobile device, Your mobile operating system, the type of mobile Internet browser You use, unique device identifiers and other diagnostic data.</p>
            <p>We may also collect information that Your browser sends whenever You visit Our Service or when You access the Service by or through a mobile device.</p>

            <h3>Tracking Technologies and Cookies</h3>
            <p>We use Cookies and similar tracking technologies to track the activity on Our Service and store certain information. Tracking technologies We use include beacons, tags, and scripts to collect and track information and to improve and analyze Our Service. The technologies We use may include:</p>

            <ul>
                <li><b>Cookies or Browser Cookies. </b> A cookie is a small file placed on Your Device. You can instruct Your browser to refuse all Cookies or to indicate when a Cookie is being sent. However, if You do not accept Cookies, You may not be able to use some parts of our Service.</li>

                <li><b>Web Beacons.</b>Certain sections of our Service and our emails may contain small electronic files known as web beacons (also referred to as clear gifs, pixel tags, and single-pixel gifs) that permit the Company, for example, to count users who have visited those pages or opened an email and for other related website statistics (for example, recording the popularity of a certain section and verifying system and server integrity).</li>        

                <p>Cookies can be "Persistent" or "Session" Cookies. Persistent Cookies remain on Your personal computer or mobile device when You go offline, while Session Cookies are deleted as soon as You close Your web browser.</p>  

                <p>Cookies can be "Persistent" or "Session" Cookies. Persistent Cookies remain on Your personal computer or mobile device when You go offline, while Session Cookies are deleted as soon as You close Your web browser.</p>  

                <p>Where required by law, we use non-essential cookies (such as analytics, advertising, and remarketing cookies) only with Your consent. You can withdraw or change Your consent at any time using Our cookie preferences tool (if available) or through Your browser/device settings. Withdrawing consent does not affect the lawfulness of processing based on consent before its withdrawal.</p>  

                <p>We use both Session and Persistent Cookies for the purposes set out below:</p>

                 <li><b>Necessary / Essential Cookies</b></li>
                 <p>Type: Session Cookies</p>
                 <p>Administered by: Us</p>
                 <p>Purpose: These Cookies are essential to provide You with services available through the Website and to enable You to use some of its features. They help to authenticate users and prevent fraudulent use of user accounts. Without these Cookies, the services that You have asked for cannot be provided, and We only use these Cookies to provide You with those services.</p>
 
                <li><b>Cookies Policy / Notice Acceptance Cookies</b></li>
                <p>Type: Persistent Cookies</p>
                 <p>Administered by: Us</p>
                 <p>Purpose: These Cookies identify if users have accepted the use of cookies on the Website.</p>

                <li><b>Functionality Cookies</b></li>  
                <p>Type: Persistent Cookies</p>
                 <p>Administered by: Us</p>
                 <p>Purpose: These Cookies allow Us to remember choices You make when You use the Website, such as remembering your login details or language preference. The purpose of these Cookies is to provide You with a more personal experience and to avoid You having to re-enter your preferences every time You use the Website.</p>
                 <p>For more information about the cookies we use and your choices regarding cookies, please visit our Cookies Policy or the Cookies section of Our Privacy Policy.</p>

                
            </ul>

            <h2>Use of Your Personal Data</h2>
            <p>The Company may use Personal Data for the following purposes:</p>
            <ul>
                <li><strong>To provide and maintain our Service:</strong>  including to monitor the usage of our Service.</li>
                <li><strong>To manage Your Account:</strong> to manage Your registration as a user of the Service. The Personal Data You provide can give You access to different functionalities of the Service that are available to You as a registered user.</li>
                <li><strong>For the performance of a contract:</strong>  the development, compliance and undertaking of the purchase contract for the products, items or services You have purchased or of any other contract with Us through the Service.</li>
                <li><strong>To contact You: </strong>  To contact You by email, telephone calls, SMS, or other equivalent forms of electronic communication, such as a mobile application's push notifications regarding updates or informative communications related to the functionalities, products or contracted services, including the security updates, when necessary or reasonable for their implementation.</li>

                <li><strong>To provide You </strong>  with news, special offers, and general information about other goods, services and events which We offer that are similar to those that you have already purchased or inquired about unless You have opted not to receive such information.</li>
                <li><strong>To manage Your requests:</strong>  To attend and manage Your requests to Us.</li>
                <li><strong>For business transfers:</strong> We may use Your Personal Data to evaluate or conduct a merger, divestiture, restructuring, reorganization, dissolution, or other sale or transfer of some or all of Our assets, whether as a going concern or as part of bankruptcy, liquidation, or similar proceeding, in which Personal Data held by Us about our Service users is among the assets transferred.</li>
                <li><strong>For other purposes:</strong>  We may use Your information for other purposes, such as data analysis, identifying usage trends, determining the effectiveness of our promotional campaigns and to evaluate and improve our Service, products, services, marketing and your experience.</li>

                <p>We may share Your Personal Data in the following situations:</p>

                <li><strong>With Service Providers:</strong>   We may share Your Personal Data with Service Providers to monitor and analyze the use of our Service, to contact You.</li>
                <li><strong>For business transfers: </strong> We may share or transfer Your Personal Data in connection with, or during negotiations of, any merger, sale of Company assets, financing, or acquisition of all or a portion of Our business to another company.</li>
                <li><strong>With Affiliates:</strong> We may share Your Personal Data with Our affiliates, in which case we will require those affiliates to honor this Privacy Policy. Affiliates include Our parent company and any other subsidiaries, joint venture partners or other companies that We control or that are under common control with Us.</li>
                <li><strong>With business partners: </strong> We may share Your Personal Data with Our business partners to offer You certain products, services or promotions.</li>
                 <li><strong>With other users:</strong>If Our Service offers public areas, when You share Personal Data or otherwise interact in the public areas with other users, such information may be viewed by all users and may be publicly distributed outside.</li>
                <li><strong>With Your consent:</strong> We may disclose Your Personal Data for any other purpose with Your consent.</li>
            </ul>

            <h2>Retention of Your Personal Data</h2>
            <p>The Company will retain Your Personal Data only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use Your Personal Data to the extent necessary to comply with our legal obligations (for example, if We are required to retain Your data to comply with applicable laws), resolve disputes, and enforce our legal agreements and policies.</p>

           <p>Where possible, We apply shorter retention periods and/or reduce identifiability by deleting, aggregating, or anonymizing data. Unless otherwise stated, the retention periods below are maximum periods ("up to") and We may delete or anonymize data sooner when it is no longer needed for the relevant purpose. We apply different retention periods to different categories of Personal Data based on the purpose of processing and legal obligations:</p>

             <ul>
                <li><strong>Account Information</strong>User Accounts: retained for the duration of your account relationship plus up to 24 months after account closure to handle any post-termination issues or resolve disputes.</li>
                <li><strong>Customer Support Data</strong>Support tickets and correspondence: up to 24 months from the date of ticket closure to resolve follow-up inquiries, track service quality, and defend against potential legal claims
                Chat transcripts: up to 24 months for quality assurance and staff training purposes.</li>
                <li><strong>Usage Data</strong> Website analytics data (cookies, IP addresses, device identifiers): up to 24 months from the date of collection, which allows us to analyze trends while respecting privacy principles.Server logs (IP addresses, access times): up to 24 months for security monitoring and troubleshooting purposes.</li>
                <p>Usage Data is retained in accordance with the retention periods described above, and may be retained longer only where necessary for security, fraud prevention, or legal compliance.</p>
                <p>We may retain Personal Data beyond the periods stated above for different reasons:</p>

                <li><strong>Legal obligation: We are required by law to retain specific data (e.g., financial records for tax authorities).</strong></li>

                <li><strong>Legal claims: Data is necessary to establish, exercise, or defend legal claims. </strong></li>
                <li><strong>To manage Your requests:</strong>  To attend and manage Your requests to Us.</li>
                <li><strong>Your explicit request: You ask Us to retain specific information.</strong></li>
                <li><strong>Technical limitations: Data exists in backup systems that are scheduled for routine deletion.</strong></li>

                <p>You may request information about how long We will retain Your Personal Data by contacting Us.</p>
                <p>When retention periods expire, We securely delete or anonymize Personal Data according to the following procedures:</p>

                <li><strong>Deletion: Personal Data is removed from Our systems and no longer actively processed.</strong> </li>
                <li><strong>Backup retention: Residual copies may remain in encrypted backups for a limited period consistent with our backup retention schedule and are not restored except where necessary for security, disaster recovery, or legal compliance.</strong> </li>
                <li><strong>Anonymization: In some cases, We convert Personal Data into anonymous statistical data that cannot be linked back to You. This anonymized data may be retained indefinitely for research and analytics.</strong> </li>
               
            </ul>


            <h2>Transfer and Disclosure</h2>
            <p>Your information, including Personal Data, is processed at the Company's operating offices and in any other places where the parties involved in the processing are located. It means that this information may be transferred to — and maintained on — computers located outside of Your state, province, country or other governmental jurisdiction where the data protection laws may differ from those from Your jurisdiction.</p>

            <p>Where required by applicable law, We will ensure that international transfers of Your Personal Data are subject to appropriate safeguards and supplementary measures where appropriate. The Company will take all steps reasonably necessary to ensure that Your data is treated securely and in accordance with this Privacy Policy and no transfer of Your Personal Data will take place to an organization or a country unless there are adequate controls in place including the security of Your data and other personal information.</p>


            <h2>Delete Your Personal Data</h2>
            <p>You have the right to delete or request that We assist in deleting the Personal Data that We have collected about You.</p>

            <p>Our Service may give You the ability to delete certain information about You from within the Service.</p>  

            <p>You may update, amend, or delete Your information at any time by signing in to Your Account, if you have one, and visiting the account settings section that allows you to manage Your personal information. You may also contact Us to request access to, correct, or delete any Personal Data that You have provided to Us.</p>

            <p>Please note, however, that We may need to retain certain information when we have a legal obligation or lawful basis to do so.</p>

            <h2>Disclosure of Your Personal Data</h2>

            <h3>Business Transactions</h3>
            <p>If the Company is involved in a merger, acquisition or asset sale, Your Personal Data may be transferred. We will provide notice before Your Personal Data is transferred and becomes subject to a different Privacy Policy.</p>

            <h3>Law enforcement</h3>
            <p>Under certain circumstances, the Company may be required to disclose Your Personal Data if required to do so by law or in response to valid requests by public authorities (e.g. a court or a government agency).</p>

            <h3>Other legal requirements</h3>
            <p>The Company may disclose Your Personal Data in the good faith belief that such action is necessary to:</p>

            <ul>
                <li>Comply with a legal obligation</li>
                 <li>Protect and defend the rights or property of the Company</li>
                  <li>Prevent or investigate possible wrongdoing in connection with the Service</li>
                   <li>Protect the personal safety of Users of the Service or the public</li>
                   <li>Protect against legal liability</li>
            </ul>

            <h2>Security of Your Personal Data</h2>
            <p>The security of Your Personal Data is important to Us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While We strive to use commercially reasonable means to protect Your Personal Data, We cannot guarantee its absolute security.</p>

            <h1>Detailed Information on the Processing of Your Personal Data</h1>

            <p>The Service Providers We use may have access to Your Personal Data. These third-party vendors collect, store, use, process and transfer information about Your activity on Our Service in accordance with their Privacy Policies.</p>

            <h2>Usage, Performance and Miscellaneous</h2>
            <p>We may use third-party Service Providers to maintain and improve our Service.</p>

            <ul>
                <li>Google Places</li>
                <p>Google Places is a service that returns information about places using HTTP requests. It is operated by Google</p>

                <p>Google Places service may collect information from You and from Your Device for security purposes.</p>

                <p>The information gathered by Google Places is held in accordance with the Privacy Policy of Google: 
                    <a href="https://www.google.com/intl/en/policies/privacy/">https://www.google.com/intl/en/policies/privacy/</a></p>

                
            </ul>

            <h1>Children's Privacy</h1>
            <p>Our Service does not address anyone under the age of 16. We do not knowingly collect personally identifiable information from anyone under the age of 16. If You are a parent or guardian and You are aware that Your child has provided Us with Personal Data, please contact Us. If We become aware that We have collected Personal Data from anyone under the age of 16 without verification of parental consent, We take steps to remove that information from Our servers.</p>

            <p>If We need to rely on consent as a legal basis for processing Your information and Your country requires consent from a parent, We may require Your parent's consent before We collect and use that information.</p>

             <h1>Children's Privacy</h1>
            <p>Our Service does not address anyone under the age of 16. We do not knowingly collect personally identifiable information from anyone under the age of 16. If You are a parent or guardian and You are aware that Your child has provided Us with Personal Data, please contact Us. If We become aware that We have collected Personal Data from anyone under the age of 16 without verification of parental consent, We take steps to remove that information from Our servers.</p>

            <p>If We need to rely on consent as a legal basis for processing Your information and Your country requires consent from a parent, We may require Your parent's consent before We collect and use that information.</p>

            <h1>Links to Other Websites</h1>
            <p>Our Service may contain links to other websites that are not operated by Us. If You click on a third party link, You will be directed to that third party's site. We strongly advise You to review the Privacy Policy of every site You visit.</p>

            <p>We have no control over and assume no responsibility for the content, privacy policies or practices of any third party sites or services.</p>

             <h1>Changes to this Privacy Policy</h1>
            <p>We may update Our Privacy Policy from time to time. We will notify You of any changes by posting the new Privacy Policy on this page.</p>

            <p>We will let You know via email and/or a prominent notice on Our Service, prior to the change becoming effective and update the "Last updated" date at the top of this Privacy Policy.</p>

            <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>

            <h2>Contact Us</h2>
            <div class="contact-info">
                <p>If you have any questions about this Privacy Policy, You can contact us:</p>
                <ul>
                    <li><strong>By email:</strong> universityideas@gmail.com</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-secondary" id="closeModalBtn2">Close</button>
            <button class="modal-btn modal-btn-primary" id="agreeAndCloseBtn">I Agree</button>
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
    let selectedFiles = [];

    function syncFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }

    function renderSelectedFiles() {
        fileList.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const chip = document.createElement('span');
            chip.className = 'idea-file-chip';

            const fileIcon = document.createElement('i');
            fileIcon.className = 'bi bi-file-earmark-check';

            const fileName = document.createElement('span');
            fileName.className = 'idea-file-chip-name';
            fileName.textContent = file.name;
            fileName.title = file.name;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'idea-file-remove';
            removeButton.dataset.fileIndex = index;
            removeButton.setAttribute('aria-label', `Remove ${file.name}`);

            const removeIcon = document.createElement('i');
            removeIcon.className = 'bi bi-x-lg';

            removeButton.appendChild(removeIcon);
            chip.appendChild(fileIcon);
            chip.appendChild(fileName);
            chip.appendChild(removeButton);
            fileList.appendChild(chip);
        });
    }

    function addFiles(files) {
        selectedFiles = [...selectedFiles, ...Array.from(files)];
        syncFileInput();
        renderSelectedFiles();
        fileInput.value = '';
    }

    fileInput.addEventListener('change', () => {
        addFiles(fileInput.files);
    });

    fileList.addEventListener('click', (e) => {
        const removeButton = e.target.closest('.idea-file-remove');

        if (!removeButton) {
            return;
        }

        const fileIndex = Number(removeButton.dataset.fileIndex);
        selectedFiles.splice(fileIndex, 1);
        syncFileInput();
        renderSelectedFiles();
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
                addFiles(e.dataTransfer.files);
            }
        });
    });

    /* ── Terms & Conditions Modal ──────────────────────────────────────── */
    const modal = document.getElementById('termsModal');
    const showModalBtn = document.getElementById('showTermsModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const closeModalBtn2 = document.getElementById('closeModalBtn2');
    const agreeAndCloseBtn = document.getElementById('agreeAndCloseBtn');
    const termsCheckbox = document.getElementById('termsCheckbox');
    const termsWrapper = document.getElementById('termsWrapper');
    const termsError = document.getElementById('termsError');

    // Show modal
    showModalBtn.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    });

    // Close modal functions
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }

    closeModalBtn.addEventListener('click', closeModal);
    closeModalBtn2.addEventListener('click', closeModal);

    // Click outside to close
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Agree and close - check the checkbox
    agreeAndCloseBtn.addEventListener('click', () => {
        termsCheckbox.checked = true;
        termsWrapper.classList.remove('error');
        termsError.style.display = 'none';
        closeModal();
    });

    // Escape key to close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeModal();
        }
    });

    /* ── Form Submit with Terms Validation ─────────────────────────────── */
    const form = document.getElementById('ideaForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', (e) => {
        // Check if terms checkbox is checked
        if (!termsCheckbox.checked) {
            e.preventDefault();
            termsWrapper.classList.add('error');
            termsError.style.display = 'flex';
            
            // Scroll to terms section
            termsWrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            return false;
        }

        // If form is valid and terms accepted, show loading state
        if (form.checkValidity()) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Submitting...
            `;
        }
    });

    // Remove error when checkbox is checked
    termsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            termsWrapper.classList.remove('error');
            termsError.style.display = 'none';
        }
    });

    // Preserve old value if validation fails
    @if(old('terms_accepted'))
        termsCheckbox.checked = true;
    @endif

});
</script>
@endsection
