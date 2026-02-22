@extends('layouts.app')

@section('title', 'Submit Idea - University Ideas System')

@section('content')
<style>
    /* ── Hero Background ── */
    .create-hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 3rem 1rem 4rem;
        color: #fff;
        background-image:
            linear-gradient(135deg, rgba(15,23,42,0.82), rgba(15,23,42,0.55)),
            url('https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    /* ── Glass Form Card ── */
    .glass-form-card {
        max-width: 780px;
        width: 100%;
        margin: 0 auto;
        padding: 2.5rem 2.2rem;
        border-radius: 1.5rem;
        background: rgba(15,23,42,0.50);
        border: 1px solid rgba(255,255,255,0.10);
        backdrop-filter: blur(22px) saturate(140%);
        -webkit-backdrop-filter: blur(22px) saturate(140%);
        box-shadow:
            0 20px 50px rgba(0,0,0,0.45),
            inset 0 1px 0 rgba(255,255,255,0.12);
        position: relative;
        overflow: hidden;
    }

    .glass-form-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: -120%;
        width: 70%;
        height: 100%;
        background: linear-gradient(
            105deg,
            transparent,
            rgba(255,255,255,0.08),
            transparent
        );
        transform: skewX(-14deg);
        animation: card-sweep 6s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes card-sweep {
        0%, 30% { left: -120%; }
        50%, 100% { left: 150%; }
    }

    /* ── Header Area ── */
    .create-header {
        text-align: center;
        margin-bottom: 2.2rem;
    }

    .create-header-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background: linear-gradient(145deg, rgba(214,158,46,0.25), rgba(214,158,46,0.10));
        border: 1px solid rgba(214,158,46,0.35);
        font-size: 1.8rem;
        color: #d69e2e;
        box-shadow: 0 8px 24px rgba(214,158,46,0.18);
        animation: icon-pulse 3s ease-in-out infinite;
    }

    @keyframes icon-pulse {
        0%, 100% { box-shadow: 0 8px 24px rgba(214,158,46,0.18); }
        50% { box-shadow: 0 8px 36px rgba(214,158,46,0.35); }
    }

    .create-header h1 {
        font-family: "Merriweather", serif;
        font-size: 1.85rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
    }

    .create-header p {
        opacity: 0.75;
        font-size: 0.95rem;
        max-width: 480px;
        margin: 0 auto;
    }

    /* ── Back Link ── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: rgba(255,255,255,0.65);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        padding: 0.45rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.06);
        transition: all 0.25s ease;
        margin-bottom: 1.5rem;
    }

    .back-link:hover {
        color: #fff;
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.22);
        transform: translateX(-3px);
    }

    /* ── Form Labels ── */
    .glass-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #dbeafe;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.55rem;
        letter-spacing: 0.02em;
    }

    .glass-label .label-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.10);
    }

    .glass-label .required-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #f87171;
        flex-shrink: 0;
    }

    /* ── Glass Inputs ── */
    .glass-input,
    .glass-textarea,
    .glass-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(255,255,255,0.10);
        background: rgba(255,255,255,0.06);
        color: #f1f5f9;
        font-size: 0.92rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        backdrop-filter: blur(6px);
        outline: none;
    }

    .glass-input::placeholder,
    .glass-textarea::placeholder {
        color: rgba(255,255,255,0.35);
    }

    .glass-input:focus,
    .glass-textarea:focus,
    .glass-select:focus {
        border-color: rgba(59,130,246,0.65);
        background: rgba(255,255,255,0.10);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.15), 0 0 20px rgba(59,130,246,0.08);
    }

    .glass-select option {
        background: #1e293b;
        color: #f1f5f9;
    }

    .glass-textarea {
        resize: vertical;
        min-height: 140px;
    }

    /* ── Form Group ── */
    .form-group-glass {
        margin-bottom: 1.5rem;
    }

    .form-hint {
        display: flex;
        align-items: flex-start;
        gap: 0.4rem;
        margin-top: 0.45rem;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.40);
        line-height: 1.4;
    }

    .form-hint i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* ── File Upload ── */
    .file-upload-area {
        border: 2px dashed rgba(255,255,255,0.15);
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        background: rgba(255,255,255,0.03);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .file-upload-area:hover,
    .file-upload-area.drag-over {
        border-color: rgba(59,130,246,0.50);
        background: rgba(59,130,246,0.06);
    }

    .file-upload-area .upload-icon {
        font-size: 2rem;
        color: rgba(255,255,255,0.30);
        margin-bottom: 0.5rem;
        transition: transform 0.3s ease;
    }

    .file-upload-area:hover .upload-icon {
        transform: translateY(-3px);
        color: rgba(59,130,246,0.65);
    }

    .file-upload-area .upload-text {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.50);
    }

    .file-upload-area .upload-text strong {
        color: #93c5fd;
    }

    .file-upload-area input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .file-names-display {
        margin-top: 0.7rem;
        font-size: 0.8rem;
        color: #93c5fd;
    }

    /* ── Anonymous Toggle ── */
    .toggle-container {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        padding: 1rem 1.2rem;
        border-radius: 0.75rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .toggle-container:hover {
        background: rgba(255,255,255,0.07);
        border-color: rgba(255,255,255,0.15);
    }

    .toggle-switch {
        position: relative;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: rgba(255,255,255,0.15);
        border-radius: 999px;
        transition: all 0.35s ease;
    }

    .toggle-slider::before {
        content: "";
        position: absolute;
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background: #fff;
        border-radius: 50%;
        transition: all 0.35s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .toggle-switch input:checked + .toggle-slider {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
    }

    .toggle-switch input:checked + .toggle-slider::before {
        transform: translateX(22px);
    }

    .toggle-label {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .toggle-label-text {
        color: #e2e8f0;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .toggle-label-hint {
        color: rgba(255,255,255,0.40);
        font-size: 0.78rem;
        line-height: 1.3;
    }

    /* ── Buttons ── */
    .btn-glass-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.7rem 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.70);
        font-weight: 500;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btn-glass-cancel:hover {
        background: rgba(255,255,255,0.12);
        color: #fff;
        border-color: rgba(255,255,255,0.25);
        transform: translateY(-1px);
    }

    .btn-glass-cancel:active {
        transform: scale(0.96);
    }

    .btn-glass-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        border: none;
        background: linear-gradient(135deg, #d69e2e, #b7791f);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.02em;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(214,158,46,0.25);
    }

    .btn-glass-submit::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            105deg,
            transparent,
            rgba(255,255,255,0.30),
            transparent
        );
        transition: left 0.5s ease;
    }

    .btn-glass-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(214,158,46,0.40);
    }

    .btn-glass-submit:hover::before {
        left: 100%;
    }

    .btn-glass-submit:active {
        transform: scale(0.96);
        box-shadow: 0 4px 12px rgba(214,158,46,0.25);
    }

    /* ── Divider ── */
    .glass-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
        margin: 1.8rem 0;
    }

    /* ── Tips Card ── */
    .glass-tips-card {
        max-width: 780px;
        width: 100%;
        margin: 1.5rem auto 0;
        padding: 1.8rem 1.8rem;
        border-radius: 1.25rem;
        background: rgba(15,23,42,0.45);
        border: 1px solid rgba(255,255,255,0.08);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        box-shadow: 0 12px 36px rgba(0,0,0,0.30);
    }

    .tips-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1.1rem;
        color: #fbbf24;
        font-weight: 700;
        font-size: 1rem;
    }

    .tips-header i {
        font-size: 1.2rem;
    }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
        padding: 0.5rem 0;
        color: rgba(255,255,255,0.70);
        font-size: 0.88rem;
        line-height: 1.5;
    }

    .tip-item .tip-check {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(52,211,153,0.15);
        border: 1px solid rgba(52,211,153,0.30);
        color: #34d399;
        font-size: 0.7rem;
        margin-top: 1px;
    }

    /* ── Validation Error ── */
    .glass-invalid-feedback {
        color: #f87171;
        font-size: 0.8rem;
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .glass-input.is-invalid,
    .glass-textarea.is-invalid,
    .glass-select.is-invalid {
        border-color: rgba(248,113,113,0.55);
    }

    /* ── Ripple Effect ── */
    .ripple-container {
        position: relative;
        overflow: hidden;
    }

    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.30);
        transform: scale(0);
        animation: ripple-expand 0.6s ease-out forwards;
        pointer-events: none;
    }

    @keyframes ripple-expand {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* ── Fade-in-up Animations ── */
    .fade-in-up {
        opacity: 0;
        transform: translateY(22px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-delay-1 { transition-delay: 0.08s; }
    .fade-delay-2 { transition-delay: 0.16s; }
    .fade-delay-3 { transition-delay: 0.24s; }
    .fade-delay-4 { transition-delay: 0.32s; }
    .fade-delay-5 { transition-delay: 0.40s; }
    .fade-delay-6 { transition-delay: 0.48s; }
    .fade-delay-7 { transition-delay: 0.56s; }

    /* ── Responsive ── */
    @media (max-width: 767.98px) {
        .create-hero {
            padding: 2rem 0.75rem 3rem;
        }

        .glass-form-card,
        .glass-tips-card {
            padding: 1.6rem 1.2rem;
        }

        .create-header h1 {
            font-size: 1.45rem;
        }

        .btn-actions {
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-glass-submit,
        .btn-glass-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<section class="create-hero">
    <div style="max-width: 780px; width: 100%; margin: 0 auto;">

        {{-- Back Link --}}
        <a href="{{ route('ideas.index') }}" class="back-link fade-in-up ripple-container">
            <i class="bi bi-arrow-left-short" style="font-size: 1.2rem;"></i>
            Back to Ideas
        </a>

        {{-- Main Form Card --}}
        <div class="glass-form-card fade-in-up fade-delay-1">

            {{-- Header --}}
            <div class="create-header">
                <div class="create-header-icon">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                <h1>Submit Your Idea</h1>
                <p>Share your innovative ideas to help improve our university</p>
            </div>

            <form method="POST" action="{{ route('ideas.store') }}" enctype="multipart/form-data" id="ideaForm">
                @csrf

                {{-- Title --}}
                <div class="form-group-glass fade-in-up fade-delay-2">
                    <label class="glass-label" for="title">
                        <span class="label-icon"><i class="bi bi-type-h1"></i></span>
                        Idea Title
                        <span class="required-dot"></span>
                    </label>
                    <input type="text"
                           class="glass-input @error('title') is-invalid @enderror"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           placeholder="Enter a clear and concise title for your idea">
                    @error('title')
                        <div class="glass-invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-group-glass fade-in-up fade-delay-3">
                    <label class="glass-label" for="description">
                        <span class="label-icon"><i class="bi bi-text-paragraph"></i></span>
                        Description
                        <span class="required-dot"></span>
                    </label>
                    <textarea class="glass-textarea @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="5"
                              required
                              placeholder="Describe your idea in detail. What problem does it solve? How will it benefit the university?">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="glass-invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="form-hint">
                        <i class="bi bi-info-circle"></i>
                        <span>Be as specific as possible. Include any relevant details that help others understand your idea.</span>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="form-group-glass fade-in-up fade-delay-4">
                    <label class="glass-label" for="categories">
                        <span class="label-icon"><i class="bi bi-tags"></i></span>
                        Categories
                        <span class="required-dot"></span>
                    </label>
                    <select class="glass-select @error('categories') is-invalid @enderror"
                            id="categories"
                            name="categories[]"
                            multiple
                            required
                            size="4">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('categories')
                        <div class="glass-invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="form-hint">
                        <i class="bi bi-info-circle"></i>
                        <span>Hold Ctrl (or Cmd on Mac) to select multiple categories.</span>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="form-group-glass fade-in-up fade-delay-5">
                    <label class="glass-label">
                        <span class="label-icon"><i class="bi bi-paperclip"></i></span>
                        Supporting Documents
                        <span style="font-size: 0.75rem; color: rgba(255,255,255,0.35); font-weight: 400;">(Optional)</span>
                    </label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file"
                               class="@error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror"
                               id="documents"
                               name="documents[]"
                               multiple
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                        <div class="upload-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="upload-text">
                            <strong>Click to upload</strong> or drag and drop<br>
                            <span style="font-size: 0.78rem;">PDF, Word, Excel, PowerPoint, Images &bull; Max 10MB each</span>
                        </div>
                    </div>
                    @error('documents')
                        <div class="glass-invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    @error('documents.*')
                        <div class="glass-invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="file-names-display" id="fileNamesDisplay"></div>
                </div>

                <div class="glass-divider"></div>

                {{-- Anonymous Toggle --}}
                <div class="form-group-glass fade-in-up fade-delay-6">
                    <label class="toggle-container" for="is_anonymous">
                        <div class="toggle-switch">
                            <input type="checkbox"
                                   id="is_anonymous"
                                   name="is_anonymous"
                                   value="1"
                                   {{ old('is_anonymous') ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </div>
                        <div class="toggle-label">
                            <span class="toggle-label-text">
                                <i class="bi bi-incognito me-1"></i> Submit anonymously
                            </span>
                            <span class="toggle-label-hint">
                                Your identity is stored for administrative purposes, but your name won't be displayed publicly.
                            </span>
                        </div>
                    </label>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-between align-items-center btn-actions fade-in-up fade-delay-7">
                    <a href="{{ route('ideas.index') }}" class="btn-glass-cancel ripple-container">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                    <button type="submit" class="btn-glass-submit ripple-container" id="submitBtn">
                        <i class="bi bi-send-fill"></i> Submit Idea
                    </button>
                </div>
            </form>
        </div>

        {{-- Tips Card --}}
        <div class="glass-tips-card fade-in-up fade-delay-7">
            <div class="tips-header">
                <i class="bi bi-stars"></i>
                Tips for a Great Idea
            </div>
            <div class="tip-item">
                <span class="tip-check"><i class="bi bi-check2"></i></span>
                <span>Be clear and specific about what you want to achieve</span>
            </div>
            <div class="tip-item">
                <span class="tip-check"><i class="bi bi-check2"></i></span>
                <span>Explain the problem your idea solves</span>
            </div>
            <div class="tip-item">
                <span class="tip-check"><i class="bi bi-check2"></i></span>
                <span>Include any potential benefits or cost savings</span>
            </div>
            <div class="tip-item">
                <span class="tip-check"><i class="bi bi-check2"></i></span>
                <span>Attach supporting documents if they help explain your idea</span>
            </div>
            <div class="tip-item">
                <span class="tip-check"><i class="bi bi-check2"></i></span>
                <span>Consider how your idea could be implemented</span>
            </div>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Fade-in-up via IntersectionObserver ──
    var animated = document.querySelectorAll('.fade-in-up');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });
        animated.forEach(function (el) { observer.observe(el); });
    } else {
        animated.forEach(function (el) { el.classList.add('visible'); });
    }

    // ── Ripple Click Animation ──
    document.querySelectorAll('.ripple-container').forEach(function (el) {
        el.addEventListener('click', function (e) {
            var rect = el.getBoundingClientRect();
            var ripple = document.createElement('span');
            ripple.classList.add('ripple');
            var size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
            el.appendChild(ripple);
            ripple.addEventListener('animationend', function () {
                ripple.remove();
            });
        });
    });

    // ── File Upload Display ──
    var fileInput = document.getElementById('documents');
    var fileDisplay = document.getElementById('fileNamesDisplay');
    var uploadArea = document.getElementById('fileUploadArea');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            var files = fileInput.files;
            if (files.length > 0) {
                var names = Array.from(files).map(function (f) {
                    return '<i class="bi bi-file-earmark me-1"></i>' + f.name;
                }).join('<br>');
                fileDisplay.innerHTML = names;
            } else {
                fileDisplay.innerHTML = '';
            }
        });
    }

    // Drag-and-drop visual feedback
    if (uploadArea) {
        ['dragenter','dragover'].forEach(function (evt) {
            uploadArea.addEventListener(evt, function (e) {
                e.preventDefault();
                uploadArea.classList.add('drag-over');
            });
        });
        ['dragleave','drop'].forEach(function (evt) {
            uploadArea.addEventListener(evt, function (e) {
                e.preventDefault();
                uploadArea.classList.remove('drag-over');
            });
        });
    }

});
</script>
@endsection
