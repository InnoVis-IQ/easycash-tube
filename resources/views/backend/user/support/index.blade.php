@extends('layout.master')

@push('plugin-styles')
    {{-- {!! Html::style('/assets/plugins/plugin.css') !!} --}}
@endpush

@section('content')
<div class="container mt-4">
    <h2>Support & Help</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Quick Contact -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                    <h5>Phone Support</h5>
                    <p class="mb-0">+92 300 1234567</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fab fa-whatsapp fa-2x text-success mb-3"></i>
                    <h5>WhatsApp Support</h5>
                    <a href="https://wa.me/923001234567" target="_blank" class="btn btn-success btn-sm">
                        <i class="fab fa-whatsapp"></i> Chat Now
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-envelope fa-2x text-info mb-3"></i>
                    <h5>Email Support</h5>
                    <p class="mb-0">support@easycashtube.com</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQs Section -->
    <div class="card mb-4 shadow-sm faq-modern-card">
        <div class="card-header bg-white border-0 pb-0">
            <h4 class="fw-bold mb-0"><i class="fas fa-question-circle me-2"></i> Frequently Asked Questions</h4>
        </div>
        <div class="card-body">
            @if($faqs->count() > 0)
                <div class="accordion faq-modern-accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item faq-modern-item mb-2">
                            <h2 class="accordion-header" id="faq{{ $index }}">
                                <button class="accordion-button faq-modern-question d-flex align-items-center {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    <span class="me-2 text-primary"><i class="fas fa-chevron-down faq-chevron"></i></span>
                                    <span class="flex-grow-1">{{ $faq->question }}</span>
                                    @if($faq->category)
                                        <span class="badge bg-info text-dark ms-2">{{ ucfirst($faq->category) }}</span>
                                    @endif
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="faq{{ $index }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body faq-modern-answer">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center">No FAQs available at the moment.</p>
            @endif
        </div>
    </div>

    <!-- Contact Form -->
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-envelope"></i> Submit Support Request</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('support.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="subject">Subject *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="category">Category *</label>
                            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>Payment</option>
                                <option value="withdrawal" {{ old('category') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="priority">Priority *</label>
                            <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="message">Message *</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Please describe your issue in detail..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                    <a href="{{ route('support.history') }}" class="btn btn-secondary">
                        <i class="fas fa-history"></i> View History
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<style>
.faq-modern-card {
    background: #f8fafc;
    border-radius: 1rem;
    border: none;
}
.faq-modern-accordion .faq-modern-item {
    border: none;
    background: transparent;
}
.faq-modern-question {
    background: #fff;
    border-radius: 0.5rem;
    font-size: 1.08rem;
    font-weight: 500;
    color: #222;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    transition: background 0.2s, box-shadow 0.2s;
    padding: 1rem 1.25rem;
}
.faq-modern-question:hover, .faq-modern-question:not(.collapsed) {
    background: #e9f5ff;
    color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13,110,253,0.08);
}
.faq-modern-question .faq-chevron {
    transition: transform 0.3s;
}
.faq-modern-question:not(.collapsed) .faq-chevron {
    transform: rotate(180deg);
}
.faq-modern-answer {
    background: #fff;
    border-radius: 0.5rem;
    margin-top: 0.5rem;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    color: #444;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.badge.bg-info.text-dark {
    background: #d1ecf1 !important;
    color: #0c5460 !important;
    font-size: 0.85em;
    font-weight: 500;
}
</style>
@endpush 