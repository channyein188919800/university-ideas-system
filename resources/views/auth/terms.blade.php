@extends('layouts.app')

@section('title', 'Terms and Conditions - University Ideas System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header text-center">
                    <i class="fas fa-file-contract fa-3x mb-3"></i>
                    <h4 class="mb-0">Terms and Conditions</h4>
                    <p class="mb-0 opacity-75">Please read and accept to continue</p>
                </div>
                <div class="card-body p-4">
                    <div class="terms-content bg-light p-4 rounded mb-4" style="max-height: 400px; overflow-y: auto;">
                        <h5>University Ideas System - Terms and Conditions</h5>
                        
                        <p><strong>1. Introduction</strong></p>
                        <p>Welcome to the University Ideas System. By using this platform, you agree to comply with and be bound by the following terms and conditions. Please read them carefully before submitting any ideas or comments.</p>
                        
                        <p><strong>2. Purpose</strong></p>
                        <p>This system is designed to collect ideas for improvement from all university staff members. The goal is to foster innovation and continuous improvement across all departments.</p>
                        
                        <p><strong>3. User Responsibilities</strong></p>
                        <ul>
                            <li>You must provide accurate and truthful information when submitting ideas</li>
                            <li>All submissions should be constructive and professional</li>
                            <li>You must not submit any content that is unlawful, harmful, threatening, abusive, harassing, defamatory, vulgar, obscene, or otherwise objectionable</li>
                            <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                            <li>You agree to use the system in a manner consistent with university policies and values</li>
                        </ul>
                        
                        <p><strong>4. Idea Submission</strong></p>
                        <ul>
                            <li>You may submit multiple ideas</li>
                            <li>Ideas should be clear, specific, and actionable</li>
                            <li>You may optionally upload supporting documents</li>
                            <li>You may choose to submit ideas anonymously, but your identity will be stored in the database for administrative purposes</li>
                            <li>All ideas are subject to review and approval</li>
                        </ul>
                        
                        <p><strong>5. Voting and Comments</strong></p>
                        <ul>
                            <li>You may vote (thumbs up or thumbs down) on any idea once</li>
                            <li>You may comment on any idea</li>
                            <li>Comments should be constructive and respectful</li>
                            <li>You may comment anonymously if desired</li>
                        </ul>
                        
                        <p><strong>6. Closure Dates</strong></p>
                        <ul>
                            <li>Idea submission will close on the specified closure date</li>
                            <li>Comments will continue to be accepted until the final closure date</li>
                            <li>No new ideas or comments will be accepted after the final closure date</li>
                        </ul>
                        
                        <p><strong>7. Privacy and Data Protection</strong></p>
                        <ul>
                            <li>Your personal information will be handled in accordance with data protection regulations</li>
                            <li>While you may submit ideas and comments anonymously, your identity will be stored for administrative and investigative purposes</li>
                            <li>The university reserves the right to investigate any inappropriate content</li>
                        </ul>
                        
                        <p><strong>8. Intellectual Property</strong></p>
                        <ul>
                            <li>By submitting an idea, you grant the university the right to evaluate and potentially implement it</li>
                            <li>The university reserves the right to modify or adapt ideas as needed</li>
                        </ul>
                        
                        <p><strong>9. Moderation</strong></p>
                        <ul>
                            <li>The university reserves the right to remove any content that violates these terms</li>
                            <li>Inappropriate submissions may result in disciplinary action</li>
                        </ul>
                        
                        <p><strong>10. Changes to Terms</strong></p>
                        <p>The university reserves the right to modify these terms at any time. Continued use of the system constitutes acceptance of any changes.</p>
                        
                        <p><strong>11. Contact</strong></p>
                        <p>For questions or concerns about these terms, please contact the Quality Assurance Office.</p>
                    </div>
                    
                    <form method="POST" action="{{ route('terms.accept') }}">
                        @csrf
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                   id="terms_accepted" name="terms_accepted" value="1" required>
                            <label class="form-check-label" for="terms_accepted">
                                I have read and agree to the Terms and Conditions
                            </label>
                            @error('terms_accepted')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle"></i> Accept and Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
