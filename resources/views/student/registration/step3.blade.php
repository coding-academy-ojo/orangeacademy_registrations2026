@extends('layouts.student')
@section('title', 'Step 3 - Orange Coursat')

@section('content')
    @include('student.registration._progress', ['step' => 3])

    <div class="interactive-hero rounded-4 mb-5 position-relative overflow-hidden shadow-lg">
        <div class="hero-bg-animated">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
            <div class="mesh-overlay"></div>
        </div>

        <div class="hero-content position-relative z-3 p-4 p-md-5 text-white">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center bg-white bg-opacity-10 text-white px-3 py-2 rounded-pill mb-4 border border-white border-opacity-25"
                        style="backdrop-filter: blur(10px);">
                        <i class="bi bi-stars text-warning me-2"></i>
                        <span class="fw-bold tracking-wider small">ORANGE COURSAT</span>
                    </div>

                    <h2 class="display-5 fw-bolder mb-3 lh-sm text-white">
                        <span data-en="Master the Web." data-ar="احترف الويب.">Master the Web.</span><br>
                        <span class="text-gradient-orange typing-container">Build Your Future.</span>
                    </h2>

                    <p class="fs-5 fw-light mb-4 text-white-50"
                        data-en="Free e-learning platform in various digital fields. Enhance your skills at your convenience."
                        data-ar="منصة تعليم إلكتروني مجانية في مجالات رقمية متعددة. طور مهاراتك في الوقت الذي يناسبك.">
                        Free e-learning platform in various digital fields. Enhance your skills at your convenience.
                    </p>

                    <div class="glass-alert p-3 rounded-4 d-inline-block border border-warning border-opacity-50">
                        <div class="d-flex align-items-start">
                            <div class="icon-pulse-wrapper me-3">
                                <i class="bi bi-shield-exclamation fs-3 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-white" data-en="Mandatory Requirement" data-ar="مطلب إلزامي">
                                    Mandatory Requirement</h6>
                                <p class="small mb-0 text-white-50"
                                    data-en="To proceed with your application, you must complete the foundational web development courses and upload all 3 certificates (HTML, CSS, JS)."
                                    data-ar="لمتابعة طلبك، يجب عليك إكمال دورات تطوير الويب الأساسية ورفع الشهادات الثلاث (HTML، CSS، JS).">
                                    To proceed with your application, you must complete the foundational web development
                                    courses and upload all 3 certificates (HTML, CSS, JS).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block text-end">
                    <div
                        class="floating-code-window text-start p-4 mx-auto shadow-2xl rounded-4 border border-white border-opacity-10">
                        <div class="mac-dots mb-4 d-flex gap-2">
                            <div class="dot bg-danger rounded-circle" style="width:12px; height:12px;"></div>
                            <div class="dot bg-warning rounded-circle" style="width:12px; height:12px;"></div>
                            <div class="dot bg-success rounded-circle" style="width:12px; height:12px;"></div>
                        </div>
                        <div class="code-lines font-monospace small">
                            <div class="text-primary mb-2">class <span class="text-warning">Developer</span> {</div>
                            <div class="ms-4 text-info mb-1">constructor() {</div>
                            <div class="ms-5 text-white-50 mb-1">this.skills = [];</div>
                            <div class="ms-4 text-info mb-2">}</div>
                            <div class="ms-4 text-info mb-1">learn() {</div>
                            <div class="ms-5 text-white-50 mb-1">this.skills.push(</div>
                            <div class="ms-5 ps-4 text-success fw-bold">"HTML", "CSS", "JS"</div>
                            <div class="ms-5 text-white-50 mb-1">);</div>
                            <div class="ms-4 text-info mb-1">}</div>
                            <div class="text-primary">}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-body p-4 p-md-5">
            <h4 class="fw-bold mb-5 text-center position-relative pb-3">
                <span class="bg-white px-3 position-relative z-3" data-en="Your Learning Path"
                    data-ar="مسار التعلم الخاص بك">Your Learning Path</span>
                <div class="position-absolute w-100 border-bottom"
                    style="top: 50%; left: 0; z-index: 1; border-color: #eee !important;"></div>
            </h4>

            <form method="POST" action="{{ route('student.save.coursat') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-4 mb-5 path-container">
                    @php
                        $courses = [
                            [
                                'id' => 'html',
                                'title' => 'HTML5',
                                'label' => 'HTML Development',
                                'link' => 'https://coursat.orange.jo/course/view.php?id=7',
                                'icon' => 'bi-filetype-html',
                                'color' => '#E34F26',
                                'shadow' => 'rgba(227, 79, 38, 0.4)',
                                'desc' => 'Structure & Semantics'
                            ],
                            [
                                'id' => 'css',
                                'title' => 'CSS3',
                                'label' => 'CSS Development',
                                'link' => 'https://coursat.orange.jo/course/view.php?id=8',
                                'icon' => 'bi-filetype-css',
                                'color' => '#1572B6',
                                'shadow' => 'rgba(21, 114, 182, 0.4)',
                                'desc' => 'Styling & Layouts'
                            ],
                            [
                                'id' => 'javascript',
                                'title' => 'JavaScript',
                                'label' => 'JS Course',
                                'link' => 'https://coursat.orange.jo/course/view.php?id=5',
                                'icon' => 'bi-filetype-js',
                                'color' => '#F7DF1E',
                                'shadow' => 'rgba(247, 223, 30, 0.5)',
                                'desc' => 'Logic & Interactivity'
                            ]
                        ];
                    @endphp

                    @foreach($courses as $index => $course)
                        @php
                            $hasUploaded = isset($certificates) && $certificates->has($course['id']);
                            $certPath = $hasUploaded ? $certificates[$course['id']]->file_path : null;
                        @endphp
                        <div class="col-md-4 position-relative path-step">
                            @if($index < 2)
                                <div class="path-connector d-none d-md-block"></div>
                            @endif

                            <div class="premium-card h-100 {{ $hasUploaded ? 'uploaded' : '' }}"
                                style="--theme-color: {{ $course['color'] }}; --theme-shadow: {{ $course['shadow'] }};">

                                @if($hasUploaded)
                                    <div class="success-ribbon">
                                        <i class="bi bi-check-lg text-white"></i>
                                    </div>
                                @endif

                                <div class="card-content p-4 d-flex flex-column h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="icon-glass bg-light d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                            style="width: 65px; height: 65px;">
                                            <i class="bi {{ $course['icon'] }} fs-1" style="color: {{ $course['color'] }};"></i>
                                        </div>
                                        <div class="step-badge rounded-pill px-3 py-1 fw-bold small text-muted bg-light">
                                            Step {{ $index + 1 }}
                                        </div>
                                    </div>

                                    <h4 class="fw-bolder mb-1">{{ $course['title'] }}</h4>
                                    <p class="text-muted small fw-medium mb-4">{{ $course['label'] }} <span
                                            class="mx-1">•</span> {{ $course['desc'] }}</p>

                                    <a href="{{ $course['link'] }}" target="_blank"
                                        class="modern-btn mb-4 w-100 text-center text-decoration-none fw-bold shadow-sm"
                                        aria-label="Take {{ $course['title'] }} course on Orange Coursat">
                                        <i class="bi bi-arrow-up-right-circle me-2" aria-hidden="true"></i> Take Course
                                    </a>

                                    <div
                                        class="mt-auto pt-3 upload-zone rounded-3 p-3 text-center position-relative transition-all {{ $hasUploaded ? 'border-success bg-success bg-opacity-10' : 'border-dashed' }}">
                                        @if(!$hasUploaded)
                                            <i class="bi bi-cloud-arrow-up fs-3 text-muted mb-2 d-block"></i>
                                        @endif
                                        <label class="form-label small fw-bold text-dark mb-2 d-block cursor-pointer m-0">
                                            {{ $hasUploaded ? 'Update Certificate' : 'Upload Certificate' }} <span
                                                class="text-danger">*</span>
                                        </label>

                                        <div class="file-input-wrapper overflow-hidden position-relative mt-2">
                                            <input type="file" name="certificate_{{ $course['id'] }}"
                                                id="certificate_{{ $course['id'] }}"
                                                class="form-control form-control-sm certificate_{{ $course['id'] }} @error('certificate_' . $course['id']) is-invalid @enderror file-magic"
                                                accept=".pdf,.jpg,.jpeg,.png,.webp">
                                        </div>

                                        @error('certificate_' . $course['id'])
                                            <div class="invalid-feedback d-block mt-2 fw-semibold" style="font-size: 0.75rem;">
                                                {{ $message }}</div>
                                        @enderror

                                        @if($hasUploaded)
                                            <div class="mt-3">
                                                <a href="{{ asset('storage/' . $certPath) }}" target="_blank"
                                                    class="btn btn-sm btn-success rounded-pill fw-bold w-100 shadow-sm">
                                                    <i class="bi bi-eye-fill me-1"></i> View Uploaded
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                    <a href="{{ route('student.step', 2) }}"
                        class="btn btn-lg btn-light border shadow-sm px-4 rounded-3 fw-semibold text-secondary btn-hover-scale">
                        <i class="bi bi-arrow-left me-2"></i><span data-en="Back" data-ar="رجوع">Back</span>
                    </a>
                    <button type="submit"
                        class="btn btn-lg btn-orange shadow-lg px-5 rounded-3 fw-bold text-white btn-hover-scale position-relative overflow-hidden submit-btn-glow">
                        <span class="position-relative z-1 d-flex align-items-center">
                            <span data-en="Save & Continue" data-ar="حفظ ومتابعة">Save & Continue</span> <i
                                class="bi bi-arrow-right ms-2 fs-5"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @section('styles')
        <style>
            /* Colors */
            :root {
                --orange-gradient: linear-gradient(135deg, #FF7900 0%, #FF5000 100%);
            }

            .text-gradient-orange {
                background: var(--orange-gradient);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .tracking-wider {
                letter-spacing: 2px;
            }

            /* Interactive Hero section */
            .interactive-hero {
                background-color: #0b0f19;
                min-height: 400px;
                border: 1px solid rgba(255, 255, 255, 0.05);
            }

            .hero-bg-animated {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 1;
            }

            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.5;
                animation: float 20s infinite ease-in-out;
            }

            .orb-1 {
                top: -50px;
                left: -100px;
                width: 400px;
                height: 400px;
                background: #FF7900;
                animation-delay: 0s;
            }

            .orb-2 {
                bottom: -100px;
                right: 10%;
                width: 500px;
                height: 500px;
                background: #e83e8c;
                opacity: 0.3;
                animation-delay: -5s;
            }

            .orb-3 {
                top: 20%;
                left: 40%;
                width: 300px;
                height: 300px;
                background: #6f42c1;
                opacity: 0.2;
                animation-delay: -10s;
            }

            .mesh-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                background-size: 20px 20px;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                }

                50% {
                    transform: translateY(-20px) scale(1.05);
                }
            }

            /* Glassmorphism Alerts */
            .glass-alert {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .icon-pulse-wrapper {
                animation: pulse-warn 2s infinite;
            }

            @keyframes pulse-warn {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.1);
                    filter: drop-shadow(0 0 10px rgba(255, 193, 7, 0.5));
                }

                100% {
                    transform: scale(1);
                }
            }

            /* Floating Code Window */
            .floating-code-window {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(16px);
                transform: perspective(1000px) rotateY(-15deg) rotateX(5deg);
                box-shadow: -20px 20px 50px rgba(0, 0, 0, 0.5);
                transition: transform 0.5s ease;
                width: 80%;
                margin-left: auto;
            }

            .floating-code-window:hover {
                transform: perspective(1000px) rotateY(-5deg) rotateX(2deg) translateY(-10px);
            }

            /* Premium Cards */
            .premium-card {
                background: #ffffff;
                border-radius: 20px;
                border: 1px solid rgba(0, 0, 0, 0.08);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                position: relative;
                overflow: hidden;
            }

            .premium-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                background: var(--theme-color);
                transform: scaleX(0);
                transform-origin: left;
                transition: transform 0.4s ease;
            }

            .premium-card:hover {
                transform: translateY(-12px);
                box-shadow: 0 25px 50px var(--theme-shadow);
                border-color: rgba(0, 0, 0, 0.02);
            }

            .premium-card:hover::before {
                transform: scaleX(1);
            }

            .premium-card.uploaded {
                border-color: rgba(25, 135, 84, 0.3);
                background: linear-gradient(to bottom, #ffffff, #f4fff8);
            }

            .premium-card.uploaded::before {
                background: #198754;
                transform: scaleX(1);
            }

            /* Learning Path Connectors */
            .path-connector {
                position: absolute;
                top: 40px;
                right: -50%;
                width: 100%;
                height: 2px;
                background: dashed 2px #dee2e6;
                z-index: 0;
            }

            /* Success Ribbon */
            .success-ribbon {
                position: absolute;
                top: -10px;
                right: 20px;
                width: 40px;
                height: 50px;
                background: #198754;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 0 0 5px 5px;
                box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
            }

            .success-ribbon::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 0;
                border-left: 20px solid transparent;
                border-right: 20px solid transparent;
                border-top: 10px solid #198754;
            }

            /* Buttons & Upload Zone */
            .modern-btn {
                display: inline-block;
                padding: 10px 20px;
                border-radius: 12px;
                background: #f8f9fa;
                color: #212529;
                border: 1px solid #e9ecef;
                transition: all 0.3s ease;
            }

            .premium-card:hover .modern-btn {
                background: var(--theme-color);
                color: white;
                border-color: var(--theme-color);
            }

            .border-dashed {
                border: 2px dashed #dee2e6;
            }

            .upload-zone {
                transition: all 0.3s;
                background: #fafafa;
            }

            .upload-zone:hover {
                border-color: var(--theme-color);
                background: #ffffff;
            }

            .file-magic {
                font-size: 0.8rem;
                padding: 8px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 8px;
                cursor: pointer;
            }

            /* Global Animations */
            .btn-hover-scale {
                transition: transform 0.2s;
            }

            .btn-hover-scale:hover {
                transform: scale(1.03);
            }

            .submit-btn-glow::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 60%);
                opacity: 0;
                transform: scale(0.5);
                transition: opacity 0.3s, transform 0.5s;
                z-index: 0;
            }

            .submit-btn-glow:hover::before {
                opacity: 1;
                transform: scale(1);
            }
        </style>
    @endsection
@endsection