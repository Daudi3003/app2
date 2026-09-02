@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')

<section class="page-head">
    <div class="container container--wide">
        <div class="page-head__inner">
            <x-breadcrumbs light :items="['Home' => route('home'), 'Contact' => null]" />
            <h1>Get in Touch</h1>
            <p>Questions about a course, your account or teaching with us? We usually reply within one working day.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container container--wide">
        <div class="contact-layout">

            <div class="card">
                <div class="card__head">
                    <div>
                        <h3>Send us a message</h3>
                        <p>Fill in the form and the right team will pick it up.</p>
                    </div>
                </div>

                <div class="card__body">
                    <form class="form" data-simulate-form="Thanks — your message is on its way 📬" data-simulate-reset>
                        <div class="form-grid">
                            <div class="field">
                                <label class="field__label" for="contactName">Full name <span class="req">*</span></label>
                                <input id="contactName" type="text" class="input" placeholder="Alex Mwangi" required>
                            </div>

                            <div class="field">
                                <label class="field__label" for="contactEmail">Email address <span class="req">*</span></label>
                                <input id="contactEmail" type="email" class="input" placeholder="you@example.com" required>
                            </div>

                            <div class="field">
                                <label class="field__label" for="contactPhone">Phone number</label>
                                <input id="contactPhone" type="tel" class="input" placeholder="0745 000 000">
                            </div>

                            <div class="field">
                                <label class="field__label" for="contactTopic">What is this about? <span class="req">*</span></label>
                                <select id="contactTopic" class="select" required>
                                    <option value="">Choose a topic…</option>
                                    <option>Course content or access</option>
                                    <option>Free course access</option>
                                    <option>Becoming an instructor</option>
                                    <option>Certificates</option>
                                    <option>Technical problem</option>
                                    <option>Something else</option>
                                </select>
                            </div>

                            <div class="field is-full">
                                <label class="field__label" for="contactMessage">Message <span class="req">*</span></label>
                                <textarea id="contactMessage" class="textarea" rows="6" maxlength="1000"
                                          placeholder="Tell us what you need help with…"
                                          data-count-target="contactCount" required></textarea>
                                <span class="field__hint" id="contactCount"></span>
                            </div>

                            <div class="field is-full">
                                <label class="check">
                                    <input type="checkbox" required>
                                    <span>I agree to the privacy policy and consent to being contacted about my enquiry.</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn--primary btn--lg">
                                <x-icon name="send" :size="17" /> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="stack" style="gap:var(--sp-6)">
                <div class="card card--pad">
                    <h3 class="mb-6">Contact details</h3>

                    <div class="contact-info">
                        <div class="contact-info__row">
                            <span class="contact-info__icon" aria-hidden="true">📧</span>
                            <div>
                                <div class="contact-info__label">Email</div>
                                <div class="contact-info__value">{{ config('learnhub.support_email') }}</div>
                                <small class="t-muted">Replies within one working day</small>
                            </div>
                        </div>

                        <div class="contact-info__row">
                            <span class="contact-info__icon" aria-hidden="true">📞</span>
                            <div>
                                <div class="contact-info__label">Phone</div>
                                <div class="contact-info__value">{{ config('learnhub.support_phone') }}</div>
                                <small class="t-muted">Mon–Fri, 08:00–18:00 EAT</small>
                            </div>
                        </div>

                        <div class="contact-info__row">
                            <span class="contact-info__icon" aria-hidden="true">📍</span>
                            <div>
                                <div class="contact-info__label">Office</div>
                                <div class="contact-info__value">{{ config('learnhub.address') }}</div>
                            </div>
                        </div>

                        <div class="contact-info__row">
                            <span class="contact-info__icon" aria-hidden="true">💬</span>
                            <div>
                                <div class="contact-info__label">Live chat</div>
                                <div class="contact-info__value">Available 24/7</div>
                                <button type="button" class="btn btn--ghost btn--sm" style="padding-left:0"
                                        data-toast="Live chat would open here" data-toast-type="info">
                                    Start a chat →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="map-placeholder">
                    <x-icon name="map-pin" :size="18" /> Dar es Salaam, Tanzania
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="section section--alt" id="faq">
    <div class="container">
        <x-section-head eyebrow="FAQ" title="Frequently asked questions"
                        text="The answers people look for most often." />

        <div class="accordion" data-accordion="single">
            @foreach ($faqs as $faq)
                <div class="accordion__item {{ $loop->first ? 'is-open' : '' }}">
                    <button type="button" class="accordion__trigger" data-accordion-trigger
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                        <span>{{ $faq->q }}</span>
                        <span class="accordion__chevron"><x-icon name="chevron-down" :size="18" /></span>
                    </button>
                    <div class="accordion__panel">
                        <div class="accordion__inner">
                            <p class="mb-0 t-muted">{{ $faq->a }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
