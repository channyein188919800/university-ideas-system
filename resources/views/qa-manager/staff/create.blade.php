@extends('layouts.qa-manager')

@section('title', 'Add User - University Ideas System')

@section('content')
    
    <section class="p-0" style="overflow-x:hidden;">

        {{-- ===== INLINE STYLES ===== --}}
        <style>
            /* ── Page shell ──────────────────────────────────────────── */
            .uc-stage {
                min-height: 100vh;
                display: flex;
                align-items: flex-start;
                justify-content: center;
                padding: 2.5rem 1.5rem 3rem;
                background: #f8fafc; /* Light grey/white background */
                position: relative;
                overflow: hidden;
            }

            /* floating orbs */
            .uc-orb {
                position: fixed;
                border-radius: 50%;
                filter: blur(80px);
                pointer-events: none;
                z-index: 0;
                animation: uc-pulse 9s ease-in-out infinite alternate;
            }
            .uc-orb-1 { width:480px;height:480px;background:radial-gradient(circle,rgba(214,158,46,.4),transparent 70%);top:-140px;left:-100px;animation-delay:0s; }
            .uc-orb-2 { width:360px;height:360px;background:radial-gradient(circle,rgba(56,161,105,.3),transparent 70%);bottom:-80px;right:-60px;animation-delay:4s; }
            .uc-orb-3 { width:280px;height:280px;background:radial-gradient(circle,rgba(99,102,241,.25),transparent 70%);top:40%;left:55%;transform:translate(-50%,-50%);animation-delay:2s; }
            @keyframes uc-pulse { 0%{opacity:.3;transform:scale(1)} 100%{opacity:.5;transform:scale(1.12)} }

            /* floating particles */
            .uc-particle {
                position: fixed;
                width: 4px; height: 4px;
                background: rgba(214,158,46,.7);
                border-radius: 50%;
                pointer-events: none;
                z-index: 0;
                animation: uc-float 14s infinite ease-in-out;
            }
            .uc-particle:nth-child(1){left:8%;top:18%;animation-delay:0s;animation-duration:12s;}
            .uc-particle:nth-child(2){left:22%;top:74%;animation-delay:2s;animation-duration:15s;}
            .uc-particle:nth-child(3){left:65%;top:38%;animation-delay:4s;animation-duration:16s;}
            .uc-particle:nth-child(4){left:82%;top:62%;animation-delay:1s;animation-duration:13s;}
            .uc-particle:nth-child(5){left:44%;top:85%;animation-delay:3s;animation-duration:14s;}
            .uc-particle:nth-child(6){left:76%;top:14%;animation-delay:0.5s;animation-duration:11s;}
            @keyframes uc-float {
                0%,100%{transform:translateY(0)translateX(0);opacity:.7}
                25%{transform:translateY(-90px)translateX(40px);opacity:1}
                50%{transform:translateY(-160px)translateX(-25px);opacity:.4}
                75%{transform:translateY(-110px)translateX(60px);opacity:.9}
            }

            /* ── Card ────────────────────────────────────────────────── */
            .uc-card {
                position: relative;
                z-index: 2;
                width: 100%;
                max-width: 860px;
                border-radius: 1.75rem;
                background: linear-gradient(145deg, rgba(255,255,255,.97) 0%, rgba(241,245,249,.97) 100%);
                border: 1px solid rgba(255,255,255,.12);
                box-shadow: 0 28px 60px rgba(0,0,0,.45), 0 0 0 1px rgba(255,255,255,.06) inset;
                animation: uc-card-in .9s ease-out forwards;
                opacity: 0;
                transform: translateY(36px) scale(0.97);
                overflow: hidden;
            }
            @keyframes uc-card-in { to{ opacity:1; transform:translateY(0) scale(1); } }

            /* card top accent bar */
            .uc-card-bar {
                height: 5px;
                background: linear-gradient(90deg, #d69e2e 0%, #38a169 50%, #2c5282 100%);
            }

            /* card inner padding */
            .uc-card-body { padding: 2.2rem 2.6rem 2.6rem; }

            /* ── Card header ─────────────────────────────────────────── */
            .uc-header {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 2rem;
                animation: uc-slide-up .6s ease-out .2s both;
            }
            .uc-header-icon {
                width: 56px; height: 56px;
                border-radius: 1rem;
                background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
                display: flex; align-items: center; justify-content: center;
                color: #d69e2e;
                font-size: 1.5rem;
                flex-shrink: 0;
                box-shadow: 0 8px 24px rgba(15,23,42,.35);
            }
            .uc-header-text h2 {
                font-size: 1.7rem; font-weight: 800;
                color: #0f172a; margin: 0; letter-spacing: -.02em;
            }
            .uc-header-text p { margin: 0; color: #64748b; font-size:.9rem; }

            /* ── Divider ─────────────────────────────────────────────── */
            .uc-divider {
                height: 1px;
                background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
                margin: 1.4rem 0;
            }
            .uc-section-title {
                font-size: .72rem;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: .1em;
                margin: 0 0 1rem;
            }

            /* ── Field ───────────────────────────────────────────────── */
            .uc-field { margin-bottom: 1.2rem; }
            .uc-label {
                display: block;
                margin-bottom: .5rem;
                font-size: .82rem;
                font-weight: 700;
                color: #334155;
                text-transform: uppercase;
                letter-spacing: .05em;
            }
            .uc-label .req { color: #ef4444; margin-left: 2px; }

            .uc-shell { position: relative; }
            .uc-shell > .bi {
                position: absolute;
                left: 1rem; top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                font-size: 1.05rem;
                transition: color .3s;
                z-index: 2;
                pointer-events: none;
            }
            .uc-shell:focus-within > .bi { color: #d69e2e; }

            .uc-input, .uc-select {
                width: 100%;
                padding: .85rem 1rem .85rem 2.85rem;
                font-size: .97rem;
                border: 2px solid #e2e8f0;
                border-radius: 1rem;
                background: #fff;
                color: #0f172a;
                transition: all .3s ease;
                outline: none;
                font-family: inherit;
            }
            .uc-input::placeholder { color: #94a3b8; }
            .uc-input:focus, .uc-select:focus {
                border-color: #d69e2e;
                box-shadow: 0 0 0 4px rgba(214,158,46,.12);
                transform: translateY(-1px);
            }
            .uc-input.is-invalid, .uc-select.is-invalid {
                border-color: #ef4444;
                box-shadow: 0 0 0 3px rgba(239,68,68,.1);
            }

            /* input bottom highlight sweep */
            .uc-highlight {
                position: absolute;
                bottom: 0; left: 50%;
                width: 0; height: 2px;
                background: linear-gradient(90deg,transparent,#d69e2e,transparent);
                transition: all .4s;
                transform: translateX(-50%);
                border-radius: 0 0 1rem 1rem;
            }
            .uc-input:focus ~ .uc-highlight { width: 80%; }

            /* password toggle */
            .uc-eye {
                position: absolute;
                right: .9rem; top: 50%;
                transform: translateY(-50%);
                background: none; border: none;
                color: #94a3b8; cursor: pointer;
                padding: .4rem;
                transition: color .3s;
                z-index: 3;
            }
            .uc-eye:hover { color: #d69e2e; }

            /* ── Password strength bar ───────────────────────────────── */
            .uc-strength-bar {
                height: 4px;
                border-radius: 2px;
                background: #e2e8f0;
                margin-top: .5rem;
                overflow: hidden;
            }
            .uc-strength-fill {
                height: 100%;
                border-radius: 2px;
                transition: width .4s ease, background .4s ease;
                width: 0%;
            }
            .uc-strength-hints {
                display: flex;
                flex-wrap: wrap;
                gap: .35rem .75rem;
                margin-top: .55rem;
            }
            .uc-hint {
                display: inline-flex;
                align-items: center;
                gap: .28rem;
                font-size: .76rem;
                color: #94a3b8;
                transition: color .3s;
            }
            .uc-hint.ok { color: #38a169; }
            .uc-hint.ok .bi { color: #38a169; }
            .uc-hint .bi { font-size: .72rem; }

            /* ── Role badges ─────────────────────────────────────────── */
            .uc-role-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: .6rem;
            }
            .uc-role-btn {
                position: relative;
                cursor: pointer;
            }
            .uc-role-btn input[type="radio"] { display: none; }
            .uc-role-label {
                display: flex;
                align-items: center;
                gap: .6rem;
                padding: .7rem 1rem;
                border: 2px solid #e2e8f0;
                border-radius: .9rem;
                background: #fff;
                cursor: pointer;
                transition: all .25s;
                font-size: .88rem;
                font-weight: 600;
                color: #475569;
                user-select: none;
            }
            .uc-role-label .role-icon {
                width: 30px; height: 30px;
                border-radius: .5rem;
                display: flex; align-items: center; justify-content: center;
                font-size: .95rem;
                background: #f1f5f9;
                color: #64748b;
                transition: all .25s;
                flex-shrink: 0;
            }
            .uc-role-btn input:checked + .uc-role-label {
                border-color: #d69e2e;
                background: linear-gradient(135deg, rgba(214,158,46,.08), rgba(214,158,46,.03));
                color: #0f172a;
                box-shadow: 0 0 0 3px rgba(214,158,46,.12);
            }
            .uc-role-btn input:checked + .uc-role-label .role-icon {
                background: linear-gradient(135deg,#0f172a,#1e3a5f);
                color: #d69e2e;
            }
            .uc-role-label:hover { border-color: #d69e2e; }

            /* ── Upload box ──────────────────────────────────────────── */
            .uc-upload {
                border: 2px dashed #cbd5e1;
                border-radius: 1rem;
                padding: 1.2rem;
                text-align: center;
                position: relative;
                transition: all .25s;
                background: #f8fafc;
                cursor: pointer;
            }
            .uc-upload:hover, .uc-upload.drag-over {
                border-color: #d69e2e;
                background: rgba(214,158,46,.04);
            }
            .uc-upload input[type="file"] {
                position: absolute; inset: 0;
                opacity: 0; cursor: pointer;
            }
            .uc-upload-icon { font-size: 1.8rem; color: #94a3b8; margin-bottom: .3rem; }
            .uc-upload-text { font-size: .84rem; color: #64748b; }
            .uc-upload-sub  { font-size: .76rem; color: #94a3b8; margin-top:.2rem; }

            /* avatar preview */
            .uc-avatar-wrap { display: none; text-align: center; margin-top: .8rem; position: relative; }
            .uc-avatar-wrap.visible { display: block; }
            .uc-avatar-preview {
                width: 90px; height: 90px;
                border-radius: 50%; object-fit: cover;
                border: 3px solid #d69e2e;
                box-shadow: 0 8px 20px rgba(214,158,46,.25);
            }
            .uc-avatar-clear {
                position: absolute; top: -6px; right: calc(50% - 50px);
                width: 26px; height: 26px;
                border-radius: 50%;
                background: #ef4444; color: #fff;
                border: none; cursor: pointer;
                display: flex; align-items: center; justify-content: center;
                font-size: .75rem;
                box-shadow: 0 2px 8px rgba(239,68,68,.35);
            }

            /* ── Responsibilities strip ──────────────────────────────── */
            .uc-perms {
                display: none;
                flex-wrap: wrap;
                gap: .45rem;
                padding: .9rem 1rem;
                background: rgba(214,158,46,.06);
                border: 1px solid rgba(214,158,46,.2);
                border-radius: .9rem;
                margin-top: .75rem;
                animation: uc-slide-up .4s ease-out both;
            }
            .uc-perms.visible { display: flex; }
            .uc-perm-badge {
                display: inline-flex; align-items: center; gap: .3rem;
                padding: .25rem .7rem;
                background: rgba(15,23,42,.06);
                border: 1px solid rgba(15,23,42,.1);
                border-radius: 999px;
                font-size: .77rem; color: #334155; font-weight: 600;
            }
            .uc-perm-badge .bi { color: #d69e2e; font-size: .72rem; }

            /* ── Error message ───────────────────────────────────────── */
            .uc-error {
                margin-top: .4rem;
                font-size: .78rem; color: #ef4444;
                display: flex; align-items: center; gap: .3rem;
                animation: uc-shake .4s ease;
            }
            @keyframes uc-shake {
                0%,100%{transform:translateX(0)} 25%{transform:translateX(-4px)} 75%{transform:translateX(4px)}
            }

            /* ── Submit button ───────────────────────────────────────── */
            .uc-submit-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                margin-top: 1.8rem;
                padding-top: 1.4rem;
                border-top: 1px solid #f1f5f9;
                animation: uc-slide-up .6s ease-out .5s both;
            }
            .uc-btn-cancel {
                display: inline-flex; align-items: center; gap: .5rem;
                padding: .8rem 1.5rem;
                border: 2px solid #e2e8f0;
                border-radius: 1rem;
                background: #fff;
                color: #64748b;
                font-weight: 600; font-size: .95rem;
                text-decoration: none;
                transition: all .25s;
            }
            .uc-btn-cancel:hover { border-color: #94a3b8; color: #0f172a; transform: translateX(-2px); }
            .uc-btn-submit {
                display: inline-flex; align-items: center; gap: .55rem;
                padding: .85rem 2.2rem;
                border: none; border-radius: 1rem;
                background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
                background-size: 200% 200%;
                color: #fff;
                font-weight: 700; font-size: 1rem;
                cursor: pointer;
                position: relative; overflow: hidden;
                transition: all .35s;
                box-shadow: 0 8px 24px rgba(15,23,42,.3);
            }
            .uc-btn-submit::before {
                content: '';
                position: absolute; inset: 0;
                background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,.18) 50%, transparent 100%);
                transform: translateX(-110%);
                transition: transform .55s ease;
            }
            .uc-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(15,23,42,.4); background-position: 100% 0; }
            .uc-btn-submit:hover::before { transform: translateX(110%); }
            .uc-btn-submit:active { transform: scale(.97); }
            /* click ripple */
            .uc-btn-submit .ripple {
                position: absolute; border-radius: 50%;
                background: rgba(255,255,255,.3);
                transform: scale(0);
                animation: uc-ripple .6s linear;
                pointer-events: none;
            }
            @keyframes uc-ripple { to { transform: scale(4); opacity: 0; } }

            /* ── Back link ───────────────────────────────────────────── */
            .uc-back {
                display: inline-flex; align-items: center; gap: .4rem;
                color: rgba(255,255,255,.6);
                text-decoration: none; font-size: .88rem; font-weight: 600;
                margin-bottom: 1.4rem;
                border: 1px solid rgba(255,255,255,.14);
                background: rgba(255,255,255,.06);
                padding: .4rem 1rem; border-radius: 999px;
                transition: all .25s;
                position: relative; z-index: 2;
            }
            .uc-back:hover { color: #fff; border-color: rgba(255,255,255,.3); background: rgba(255,255,255,.12); transform: translateX(-3px); }

            /* animation helpers */
            @keyframes uc-slide-up {
                from { opacity:0; transform:translateY(14px); }
                to   { opacity:1; transform:translateY(0); }
            }

            /* responsive */
            @media (max-width: 600px) {
                .uc-card-body { padding: 1.5rem 1.2rem; }
                .uc-role-grid { grid-template-columns: 1fr; }
                .uc-submit-row { flex-direction: column-reverse; }
                .uc-btn-cancel, .uc-btn-submit { width:100%; justify-content:center; }
            }
        </style>

        {{-- Ambient background --}}
        <div class="uc-stage">
            <div class="uc-orb uc-orb-1"></div>
            <div class="uc-orb uc-orb-2"></div>
            <div class="uc-orb uc-orb-3"></div>
            <div class="uc-particle"></div>
            <div class="uc-particle"></div>
            <div class="uc-particle"></div>
            <div class="uc-particle"></div>
            <div class="uc-particle"></div>
            <div class="uc-particle"></div>

            <div style="position:relative;z-index:2;width:100%;max-width:860px;">
                {{-- Main card --}}
                <div class="uc-card">
                    <div class="uc-card-bar"></div>
                    <div class="uc-card-body">

                        {{-- Header --}}
                        <div class="uc-header">
                            <div class="uc-header-icon"><i class="bi bi-person-plus-fill"></i></div>
                            <div class="uc-header-text">
                                <h2>Create New User</h2>
                                <p>Add a university staff or admin account</p>
                            </div>
                        </div>

                        {{-- Session errors --}}
                        @if($errors->any())
                            <div style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.25);border-radius:.9rem;padding:.9rem 1rem;margin-bottom:1.2rem;color:#b91c1c;font-size:.88rem;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Please fix the errors below before submitting.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('qa-manager.staff.store') }}" id="ucForm">
                            @csrf

                            {{-- ── Basic Info ── --}}
                            <p class="uc-section-title">Basic Information</p>
                            <div class="row g-3">
                                {{-- Name --}}
                                <div class="col-md-6">
                                    <div class="uc-field">
                                        <label class="uc-label" for="name">Full Name <span class="req">*</span></label>
                                        <div class="uc-shell">
                                            <i class="bi bi-person"></i>
                                            <input type="text" id="name" name="name"
                                                value="{{ old('name') }}"
                                                class="uc-input @error('name') is-invalid @enderror"
                                                placeholder="Enter full name" required>
                                            <div class="uc-highlight"></div>
                                        </div>
                                        @error('name')
                                            <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <div class="uc-field">
                                        <label class="uc-label" for="email">Email Address <span class="req">*</span></label>
                                        <div class="uc-shell">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" id="email" name="email"
                                                value="{{ old('email') }}"
                                                class="uc-input @error('email') is-invalid @enderror"
                                                placeholder="name@university.edu" required>
                                            <div class="uc-highlight"></div>
                                        </div>
                                        @error('email')
                                            <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Password ── --}}
                            <div class="uc-divider"></div>
                            <p class="uc-section-title">Security</p>

                            <div class="uc-field">
                                <label class="uc-label" for="password">Password <span class="req">*</span></label>
                                <div class="uc-shell">
                                    <i class="bi bi-shield-lock"></i>
                                    <input type="password" id="password" name="password"
                                        class="uc-input @error('password') is-invalid @enderror"
                                        placeholder="Enter Password"
                                        required autocomplete="new-password">
                                    <div class="uc-highlight"></div>
                                    <button type="button" class="uc-eye" id="ucTogglePwd" title="Toggle password">
                                        <i class="bi bi-eye" id="ucEyeIcon"></i>
                                    </button>
                                </div>

                                {{-- Strength bar --}}
                                <div class="uc-strength-bar"><div class="uc-strength-fill" id="ucStrengthFill"></div></div>

                                {{-- Live hints --}}
                                <div class="uc-strength-hints">
                                    <span class="uc-hint" id="hint-len"><i class="bi bi-circle"></i> 8+ chars</span>
                                    <span class="uc-hint" id="hint-upper"><i class="bi bi-circle"></i> Uppercase</span>
                                    <span class="uc-hint" id="hint-lower"><i class="bi bi-circle"></i> Lowercase</span>
                                    <span class="uc-hint" id="hint-symbol"><i class="bi bi-circle"></i> Symbol</span>
                                    <span class="uc-hint" id="hint-number"><i class="bi bi-circle"></i> Number</span>
                                </div>

                                @error('password')
                                    <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ── Role ── --}}
                            <div class="uc-divider"></div>
                            <p class="uc-section-title">Role & Department</p>

                            <div class="uc-field">
                                <label class="uc-label">Role <span class="req">*</span></label>
                                <div class="uc-role-grid" id="ucRoleGrid">
                                    @php
                                        $roleIcons = [
                                            'admin'          => ['icon' => 'bi-shield-fill-check', 'label' => 'Admin'],
                                            'qa_manager'     => ['icon' => 'bi-bar-chart-fill',    'label' => 'QA Manager'],
                                            'qa_coordinator' => ['icon' => 'bi-people-fill',       'label' => 'QA Coordinator'],
                                            'staff'          => ['icon' => 'bi-person-fill',       'label' => 'Staff'],
                                        ];
                                    @endphp
                                    @foreach($roles as $role)
                                    <label class="uc-role-btn">
                                        <input type="radio" name="role" value="{{ $role }}"
                                            {{ old('role', 'staff') === $role ? 'checked' : '' }}>
                                        <span class="uc-role-label">
                                            <span class="role-icon"><i class="bi {{ $roleIcons[$role]['icon'] }}"></i></span>
                                            {{ $roleIcons[$role]['label'] }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('role')
                                    <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                @enderror

                                {{-- Role responsibilities --}}
                                <div class="uc-perms" id="ucPerms"></div>
                            </div>

                            {{-- Department --}}
                            <div class="uc-field">
                                <label class="uc-label" for="department_id">Department</label>
                                <div class="uc-shell">
                                    <i class="bi bi-building"></i>
                                    <select id="department_id" name="department_id"
                                        class="uc-select @error('department_id') is-invalid @enderror"
                                        style="padding-left:2.85rem;">
                                        <option value="">Select Department (optional)</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('department_id')
                                    <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="uc-field">
                                <label class="uc-label" for="status">Status <span class="req">*</span></label>
                                <div class="uc-shell">
                                    <i class="bi bi-toggle-on"></i>
                                    <select id="status" name="status"
                                        class="uc-select @error('status') is-invalid @enderror"
                                        style="padding-left:2.85rem;" required>
                                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="disabled" {{ old('status') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                </div>
                                @error('status')
                                    <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- ── Actions ── --}}
                            <div class="uc-submit-row">
                                <a href="{{ route('qa-manager.staff.index') }}" class="uc-btn-cancel">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                                <button type="submit" class="uc-btn-submit" id="ucSubmitBtn">
                                    <i class="bi bi-person-plus-fill"></i> Create User
                                </button>
                            </div>
                        </form>

                    </div>{{-- /card-body --}}
                </div>{{-- /card --}}
            </div>
        </div>{{-- /stage --}}
    </section>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Password strength & hints ───────────────────────── */
    var pwdInput   = document.getElementById('password');
    var fillBar    = document.getElementById('ucStrengthFill');
    var hintLen    = document.getElementById('hint-len');
    var hintUpper  = document.getElementById('hint-upper');
    var hintLower  = document.getElementById('hint-lower');
    var hintSymbol = document.getElementById('hint-symbol');
    var hintNumber = document.getElementById('hint-number');


    function setHint(el, ok) {
        var icon = el.querySelector('.bi');
        if (ok) {
            el.classList.add('ok');
            icon.className = 'bi bi-check-circle-fill';
        } else {
            el.classList.remove('ok');
            icon.className = 'bi bi-circle';
        }
    }

    pwdInput && pwdInput.addEventListener('input', function () {
        var v = pwdInput.value;
        var hasLen    = v.length >= 8;
        var hasUpper  = /[A-Z]/.test(v);
        var hasLower  = /[a-z]/.test(v);
        var hasSymbol = /[^A-Za-z0-9]/.test(v);
        var hasNumber = /[0-9]/.test(v);


        setHint(hintLen,    hasLen);
        setHint(hintUpper,  hasUpper);
        setHint(hintLower,  hasLower);
        setHint(hintSymbol, hasSymbol);
        setHint(hintNumber, hasNumber);

        var score = [hasLen, hasUpper, hasLower, hasSymbol, hasNumber].filter(Boolean).length;
        var pct   = (score / 5) * 100;
        var color = score <= 1 ? '#ef4444' : score === 2 ? '#f59e0b' : score === 3 ? '#3b82f6' : '#22c55e';
        fillBar.style.width     = pct + '%';
        fillBar.style.background = color;
    });

    /* ── Password toggle ─────────────────────────────────── */
    var toggleBtn = document.getElementById('ucTogglePwd');
    var eyeIcon   = document.getElementById('ucEyeIcon');
    toggleBtn && toggleBtn.addEventListener('click', function () {
        var shown = pwdInput.type === 'text';
        pwdInput.type    = shown ? 'password' : 'text';
        eyeIcon.className = shown ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    /* ── Role responsibilities ───────────────────────────── */
    var permsMap = {
        'admin':          [['bi-person-plus','Create User'],['bi-person-check','Edit Users'],['bi-file-text','Audit Logs'],['bi-gear','System Settings'],['bi-lightbulb','Submit Idea'],['bi-chat-left-text','Comment']],
        'qa_manager':     [['bi-file-text','Audit Logs'],['bi-lightbulb','Submit Idea'],['bi-chat-left-text','Comment'],['bi-trash','Remove Idea'],['bi-bar-chart','Reports']],
        'qa_coordinator': [['bi-lightbulb','Submit Idea'],['bi-chat-left-text','Comment'],['bi-people','Dept. Overview']],
        'staff':          [['bi-lightbulb','Submit Idea'],['bi-chat-left-text','Comment']]
    };
    var permsBox  = document.getElementById('ucPerms');
    var roleRadios = document.querySelectorAll('input[name="role"]');

    function updatePerms() {
        var selected = document.querySelector('input[name="role"]:checked');
        var role = selected ? selected.value : null;
        if (!role || !permsMap[role]) { permsBox.classList.remove('visible'); return; }
        permsBox.innerHTML = permsMap[role].map(function(p) {
            return '<span class="uc-perm-badge"><i class="bi ' + p[0] + '"></i>' + p[1] + '</span>';
        }).join('');
        permsBox.classList.add('visible');
    }
    roleRadios.forEach(function(r){ r.addEventListener('change', updatePerms); });
    updatePerms();

    /* ── Submit button ripple ────────────────────────────── */
    var submitBtn = document.getElementById('ucSubmitBtn');
    submitBtn && submitBtn.addEventListener('click', function (e) {
        var rect   = submitBtn.getBoundingClientRect();
        var ripple = document.createElement('span');
        var size   = Math.max(rect.width, rect.height);
        ripple.className = 'ripple';
        ripple.style.cssText = 'width:'+size+'px;height:'+size+'px;left:'+(e.clientX-rect.left-size/2)+'px;top:'+(e.clientY-rect.top-size/2)+'px;';
        submitBtn.appendChild(ripple);
        ripple.addEventListener('animationend', function(){ ripple.remove(); });
    });

});
</script>
@endpush
