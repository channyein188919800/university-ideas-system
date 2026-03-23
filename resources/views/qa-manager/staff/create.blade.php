@extends('layouts.qa-manager')

@section('title', 'Add User - University Ideas System')

@section('content')
<section class="p-0" style="overflow-x:hidden;">

    <style>
        .uc-stage {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2.5rem 1.5rem 3rem;
            background: #f8fafc;
            position: relative;
            overflow: hidden;
        }

        .uc-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 860px;
            border-radius: 1.75rem;
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            animation: uc-card-in .6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
            overflow: hidden;
        }

        @keyframes uc-card-in {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .uc-card-bar {
            height: 5px;
            background: linear-gradient(90deg, #d69e2e 0%, #38a169 50%, #2c5282 100%);
        }

        .uc-card-body {
            padding: 2.2rem 2.6rem 2.6rem;
        }

        .uc-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            animation: uc-slide-up .6s ease-out .2s both;
        }

        .uc-header-icon {
            width: 56px;
            height: 56px;
            border-radius: 1rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d69e2e;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 8px 24px rgba(30, 58, 95, 0.2);
        }

        .uc-header-text h2 {
            font-size: 1.7rem;
            font-weight: 800;
            color: #1e3a5f;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .uc-header-text p {
            margin: 0;
            color: #64748b;
            font-size: 0.9rem;
        }

        .uc-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 1.4rem 0;
        }

        .uc-section-title {
            font-size: 0.72rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0 0 1rem;
        }

        .uc-field {
            margin-bottom: 1.2rem;
        }

        .uc-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .uc-label .req {
            color: #ef4444;
            margin-left: 2px;
        }

        .uc-shell {
            position: relative;
        }

        .uc-shell > .bi {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.05rem;
            transition: color .3s;
            z-index: 2;
            pointer-events: none;
        }

        .uc-shell:focus-within > .bi {
            color: #d69e2e;
        }

        .uc-input,
        .uc-select {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.85rem;
            font-size: 0.97rem;
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            color: #0f172a;
            transition: all .3s ease;
            outline: none;
            font-family: inherit;
        }

        .uc-input::placeholder {
            color: #94a3b8;
        }

        .uc-input:focus,
        .uc-select:focus {
            border-color: #d69e2e;
            box-shadow: 0 0 0 4px rgba(214,158,46,.12);
            transform: translateY(-1px);
        }

        .uc-input.is-invalid,
        .uc-select.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        }

        .uc-eye {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.4rem;
            transition: color .3s;
            z-index: 3;
        }

        .uc-eye:hover {
            color: #d69e2e;
        }

        .uc-strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            margin-top: 0.5rem;
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
            gap: 0.35rem 0.75rem;
            margin-top: 0.55rem;
        }

        .uc-hint {
            display: inline-flex;
            align-items: center;
            gap: 0.28rem;
            font-size: 0.76rem;
            color: #94a3b8;
            transition: color .3s;
        }

        .uc-hint.ok {
            color: #38a169;
        }

        .uc-hint.ok .bi {
            color: #38a169;
        }

        .uc-hint .bi {
            font-size: 0.72rem;
        }

        .uc-role-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.6rem;
        }

        .uc-role-btn {
            position: relative;
            cursor: pointer;
        }

        .uc-role-btn input[type="radio"] {
            display: none;
        }

        .uc-role-label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.9rem;
            background: #fff;
            cursor: pointer;
            transition: all .25s;
            font-size: 0.88rem;
            font-weight: 600;
            color: #475569;
            user-select: none;
        }

        .uc-role-label .role-icon {
            width: 30px;
            height: 30px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            background: #f1f5f9;
            color: #64748b;
            transition: all .25s;
            flex-shrink: 0;
        }

        .uc-role-btn input:checked + .uc-role-label {
            border-color: #d69e2e;
            background: rgba(214,158,46,.05);
            color: #0f172a;
            box-shadow: 0 0 0 3px rgba(214,158,46,.12);
        }

        .uc-role-btn input:checked + .uc-role-label .role-icon {
            background: linear-gradient(135deg,#1e3a5f,#2c5282);
            color: #d69e2e;
        }

        .uc-role-label:hover {
            border-color: #d69e2e;
        }

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

        .uc-upload:hover,
        .uc-upload.drag-over {
            border-color: #d69e2e;
            background: rgba(214,158,46,.04);
        }

        .uc-upload input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .uc-upload-icon {
            font-size: 1.8rem;
            color: #94a3b8;
            margin-bottom: 0.3rem;
        }

        .uc-upload-text {
            font-size: 0.84rem;
            color: #64748b;
        }

        .uc-upload-sub {
            font-size: 0.76rem;
            color: #94a3b8;
            margin-top: 0.2rem;
        }

        .uc-avatar-wrap {
            display: none;
            text-align: center;
            margin-top: 0.8rem;
            position: relative;
        }

        .uc-avatar-wrap.visible {
            display: block;
        }

        .uc-avatar-preview {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #d69e2e;
            box-shadow: 0 8px 20px rgba(214,158,46,.25);
        }

        .uc-avatar-clear {
            position: absolute;
            top: -6px;
            right: calc(50% - 50px);
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #ef4444;
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            box-shadow: 0 2px 8px rgba(239,68,68,.35);
        }

        .uc-perms {
            display: none;
            flex-wrap: wrap;
            gap: 0.45rem;
            padding: 0.9rem 1rem;
            background: rgba(214,158,46,.06);
            border: 1px solid rgba(214,158,46,.2);
            border-radius: 0.9rem;
            margin-top: 0.75rem;
            animation: uc-slide-up .4s ease-out both;
        }

        .uc-perms.visible {
            display: flex;
        }

        .uc-perm-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.7rem;
            background: rgba(30,58,95,.06);
            border: 1px solid rgba(30,58,95,.1);
            border-radius: 999px;
            font-size: 0.77rem;
            color: #334155;
            font-weight: 600;
        }

        .uc-perm-badge .bi {
            color: #d69e2e;
            font-size: 0.72rem;
        }

        .uc-error {
            margin-top: 0.4rem;
            font-size: 0.78rem;
            color: #ef4444;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            animation: uc-shake .4s ease;
        }

        @keyframes uc-shake {
            0%,100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        .uc-submit-row {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.8rem;
            padding-top: 1.4rem;
            border-top: 1px solid #f1f5f9;
            animation: uc-slide-up .6s ease-out .5s both;
        }

        .uc-btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.85rem 2.2rem;
            border: none;
            border-radius: 1rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all .35s;
            box-shadow: 0 8px 24px rgba(30,58,95,.3);
        }

        .uc-btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(30,58,95,.4);
        }

        .uc-btn-submit:active {
            transform: scale(.97);
        }

        @keyframes uc-slide-up {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 600px) {
            .uc-card-body {
                padding: 1.5rem 1.2rem;
            }

            .uc-role-grid {
                grid-template-columns: 1fr;
            }

            .uc-submit-row {
                justify-content: center;
            }

            .uc-btn-submit {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="uc-stage">
        <div style="position:relative;z-index:2;width:100%;max-width:860px;">
            <div class="uc-card">
                <div class="uc-card-bar"></div>
                <div class="uc-card-body">

                    <div class="uc-header">
                        <div class="uc-header-icon"><i class="bi bi-person-plus-fill"></i></div>
                        <div class="uc-header-text">
                            <h2>Create New User</h2>
                            <p>Add DMK University's user account.</p>
                        </div>
                    </div>

                    @if($errors->any())
                        <div style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.25);border-radius:.9rem;padding:.9rem 1rem;margin-bottom:1.2rem;color:#b91c1c;font-size:.88rem;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            Please fix the errors below before submitting.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('qa-manager.staff.store') }}" enctype="multipart/form-data" id="ucForm">
                        @csrf

                        <p class="uc-section-title">Basic Information</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="uc-field">
                                    <label class="uc-label" for="name">Full Name <span class="req">*</span></label>
                                    <div class="uc-shell">
                                        <i class="bi bi-person"></i>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name') }}"
                                            class="uc-input @error('name') is-invalid @enderror"
                                            placeholder="Enter full name"
                                            required
                                            oninvalid="this.setCustomValidity('Please fill out this field')"
                                            oninput="this.setCustomValidity('')">
                                    </div>
                                    @error('name')
                                        <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="uc-field">
                                    <label class="uc-label" for="email">Email Address <span class="req">*</span></label>
                                    <div class="uc-shell">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email') }}"
                                            class="uc-input @error('email') is-invalid @enderror"
                                            placeholder="name@university.edu"
                                            required
                                            oninvalid="this.setCustomValidity('Please fill out this field')"
                                            oninput="this.setCustomValidity('')">
                                    </div>
                                    @error('email')
                                        <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="uc-divider"></div>
                        <p class="uc-section-title">Security</p>

                        <div class="uc-field">
                            <label class="uc-label" for="password">Password <span class="req">*</span></label>
                            <div class="uc-shell">
                                <i class="bi bi-shield-lock"></i>
                                <input type="password" id="password" name="password"
                                    class="uc-input @error('password') is-invalid @enderror"
                                    placeholder="Enter password"
                                    required autocomplete="new-password"
                                    oninvalid="this.setCustomValidity('Please fill out this field')"
                                    oninput="this.setCustomValidity('')" required>
                                <button type="button" class="uc-eye" id="ucTogglePwd" title="Toggle password">
                                    <i class="bi bi-eye" id="ucEyeIcon"></i>
                                </button>
                            </div>

                            <div class="uc-strength-bar"><div class="uc-strength-fill" id="ucStrengthFill"></div></div>

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

                        <div class="uc-divider"></div>
                        <p class="uc-section-title">Role & Department</p>

                        <div class="uc-field">
                            <label class="uc-label">Role <span class="req">*</span></label>
                            <div class="uc-role-grid" id="ucRoleGrid">
                                @php
                                    $roleIcons = [
                                        'qa_coordinator' => ['icon' => 'bi-people-fill', 'label' => 'QA Coordinator'],
                                        'staff'          => ['icon' => 'bi-person-fill', 'label' => 'Staff'],
                                    ];
                                @endphp
                                @foreach($roles as $role)
                                <label class="uc-role-btn">
                                    <input type="radio" name="role" value="{{ $role }}"
                                        {{ old('role', 'staff') === $role ? 'checked' : '' }}
                                        required
                                        oninvalid="this.setCustomValidity('Please fill out this field')"
                                        oninput="this.setCustomValidity('')">
                                    <span class="uc-role-label">
                                        <span class="role-icon"><i class="bi {{ $roleIcons[$role]['icon'] ?? 'bi-person-fill' }}"></i></span>
                                        {{ $roleIcons[$role]['label'] ?? ucwords(str_replace('_', ' ', $role)) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @error('role')
                                <div class="uc-error"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                            @enderror

                            <div class="uc-perms" id="ucPerms"></div>
                        </div>

                        <div class="uc-field" id="deptFieldContainer" style="transition: opacity 0.3s ease;">
                            <label class="uc-label" for="department_id">Department</label>
                            <div class="uc-shell">
                                <i class="bi bi-building" id="deptIcon"></i>
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

                        <div class="uc-divider"></div>
                        <p class="uc-section-title">Profile Image (optional)</p>

                        <div class="uc-upload" id="ucUpload">
                            <input type="file" id="profile_image" name="profile_image"
                                accept="image/png,image/jpeg,image/jpg,image/webp">
                            <div class="uc-upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="uc-upload-text">Click or drag &amp; drop to upload</div>
                            <div class="uc-upload-sub">JPG, PNG, WEBP — max 2 MB</div>
                        </div>

                        <div class="uc-avatar-wrap" id="ucAvatarWrap">
                            <img id="ucAvatarPreview" class="uc-avatar-preview" alt="Preview">
                            <button type="button" class="uc-avatar-clear" id="ucAvatarClear" title="Remove image">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @error('profile_image')
                            <div class="uc-error mt-1"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                        @enderror

                        <div class="uc-submit-row">
                            <button type="submit" class="uc-btn-submit" id="ucSubmitBtn">
                                <i class="bi bi-person-plus-fill"></i> Create User
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var pwdInput = document.getElementById('password');
    var fillBar = document.getElementById('ucStrengthFill');
    var hintLen = document.getElementById('hint-len');
    var hintUpper = document.getElementById('hint-upper');
    var hintLower = document.getElementById('hint-lower');
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
        var hasLen = v.length >= 8;
        var hasUpper = /[A-Z]/.test(v);
        var hasLower = /[a-z]/.test(v);
        var hasSymbol = /[^A-Za-z0-9]/.test(v);
        var hasNumber = /[0-9]/.test(v);

        setHint(hintLen, hasLen);
        setHint(hintUpper, hasUpper);
        setHint(hintLower, hasLower);
        setHint(hintSymbol, hasSymbol);
        setHint(hintNumber, hasNumber);

        var score = [hasLen, hasUpper, hasLower, hasSymbol, hasNumber].filter(Boolean).length;
        var pct = (score / 5) * 100;
        var color = score <= 1 ? '#ef4444' : score === 2 ? '#f59e0b' : score === 3 ? '#3b82f6' : '#22c55e';
        fillBar.style.width = pct + '%';
        fillBar.style.background = color;
    });

    var toggleBtn = document.getElementById('ucTogglePwd');
    var eyeIcon = document.getElementById('ucEyeIcon');
    toggleBtn && toggleBtn.addEventListener('click', function () {
        var shown = pwdInput.type === 'text';
        pwdInput.type = shown ? 'password' : 'text';
        eyeIcon.className = shown ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    var permsMap = {
        'qa_coordinator': [['bi-lightbulb','Submit Idea'],['bi-chat-left-text','Comment'],['bi-people','Dept. Overview']],
        'staff':          [['bi-lightbulb','Submit Idea'],['bi-chat-left-text','Comment']]
    };
    var permsBox = document.getElementById('ucPerms');
    var roleRadios = document.querySelectorAll('input[name="role"]');

    function updatePerms() {
        var selected = document.querySelector('input[name="role"]:checked');
        var role = selected ? selected.value : null;
        if (!role || !permsMap[role]) {
            permsBox.classList.remove('visible');
            return;
        }
        permsBox.innerHTML = permsMap[role].map(function(p) {
            return '<span class="uc-perm-badge"><i class="bi ' + p[0] + '"></i>' + p[1] + '</span>';
        }).join('');
        permsBox.classList.add('visible');
    }
    roleRadios.forEach(function(r){ r.addEventListener('change', updatePerms); });
    updatePerms();

    var fileInput = document.getElementById('profile_image');
    var avatarWrap = document.getElementById('ucAvatarWrap');
    var avatarImg = document.getElementById('ucAvatarPreview');
    var clearBtn = document.getElementById('ucAvatarClear');
    var uploadBox = document.getElementById('ucUpload');

    fileInput && fileInput.addEventListener('change', function () {
        if (fileInput.files && fileInput.files[0]) {
            avatarImg.src = URL.createObjectURL(fileInput.files[0]);
            avatarWrap.classList.add('visible');
            uploadBox.style.display = 'none';
        }
    });

    clearBtn && clearBtn.addEventListener('click', function () {
        fileInput.value = '';
        avatarImg.src = '';
        avatarWrap.classList.remove('visible');
        uploadBox.style.display = '';
    });

    uploadBox && ['dragenter','dragover'].forEach(function(e){
        uploadBox.addEventListener(e, function(ev){ ev.preventDefault(); uploadBox.classList.add('drag-over'); });
    });
    uploadBox && ['dragleave','drop'].forEach(function(e){
        uploadBox.addEventListener(e, function(ev){ ev.preventDefault(); uploadBox.classList.remove('drag-over'); });
    });
});
</script>
@endpush
