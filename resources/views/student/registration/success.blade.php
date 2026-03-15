@extends('layouts.student')

@section('title', 'Registration Complete')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 text-center">
                <!-- Animated Success Icon -->
                <div class="mb-4 d-flex justify-content-center align-items-center">
                    <div class="success-checkmark">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="icon-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                </div>

                <h2 class="fw-bold mb-3" data-en="Registration Successful!" data-ar="تم التسجيل بنجاح!">Registration
                    Successful!</h2>
                <p class="lead text-muted mb-5"
                    data-en="Congratulations! You have successfully completed all steps of your registration."
                    data-ar="تهانينا! لقد أكملت جميع خطوات التسجيل بنجاح.">
                    Congratulations! You have successfully completed all steps of your registration.
                </p>

                <div class="card border-0 shadow-sm mb-5 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-4 d-flex align-items-center justify-content-center p-4" style="background-color: #000000;">
                            <img src="{{ asset('storage/logo-orange.jpg') }}" width="120"
                                alt="Orange Logo" class="img-fluid" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/7/72/Orange_logo.svg'; this.onerror=null;">
                        </div>
                        <div class="col-md-8 text-start p-4 bg-white">
                            <h5 class="fw-bold mb-3" data-en="About Orange Digital Center" data-ar="عن مركز أورنج الرقمي">
                                About Orange Digital Center</h5>
                            <p class="small text-muted mb-0"
                                data-en="Orange Jordan Academy is part of the Orange Digital Center (ODC) initiative, which aims to provide top-tier digital skills training to youth. Our programs are designed to empower the next generation of developers and tech leaders in Jordan, providing them with the tools and mentorship needed to succeed in the modern job market."
                                data-ar="أكاديمية أورنج الأردن هي جزء من مبادرة مركز أورنج الرقمي (ODC)، والتي تهدف إلى توفير تدريب على المهارات الرقمية عالية المستوى للشباب. تم تصميم برامجنا لتمكين الجيل القادم من المطورين وقادة التكنولوجيا في الأردن، وتزويدهم بالأدوات والإرشاد اللازمين للنجاح في سوق العمل الحديث.">
                                Orange Jordan Academy is part of the Orange Digital Center (ODC) initiative, which aims to
                                provide top-tier digital skills training to youth. Our programs are designed to empower the
                                next generation of developers and tech leaders in Jordan, providing them with the tools and
                                mentorship needed to succeed in the modern job market.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="alert bg-orange-light text-dark p-4 rounded-4 mb-5 border-0 shadow-sm">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <i class="bi bi-clock-history fs-3 text-orange"></i>
                        <div class="text-start">
                            <h6 class="fw-bold mb-1" data-en="Next Steps" data-ar="الخطوات التالية">Next Steps</h6>
                            <p class="mb-0 small"
                                data-en="Our team is now reviewing your application. Please be patient, we will connect with you as soon as possible via email or phone."
                                data-ar="يقوم فريقنا الآن بمراجعة طلبك. يرجى التحلي بالصبر، وسنتواصل معك في أقرب وقت ممكن عبر البريد الإلكتروني أو الهاتف.">
                                Our team is now reviewing your application. Please be patient, we will connect with you as
                                soon as possible via email or phone.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('student.dashboard') }}" class="btn btn-orange px-5 rounded-pill"
                        data-en="Go to Dashboard" data-ar="الذهاب للرئيسية">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-orange-light {
            background-color: rgba(255, 121, 0, 0.08);
        }

        /* Success Checkmark Animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            position: relative;
        }

        .success-checkmark .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4CAF50;
        }

        .success-checkmark .check-icon .icon-line {
            height: 5px;
            background-color: #4CAF50;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .success-checkmark .check-icon .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }

        .success-checkmark .check-icon .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }

        .success-checkmark .check-icon .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(76, 175, 80, 0.5);
        }

        @keyframes icon-line-tip {
            0% {
                width: 0;
                left: 14px;
                top: 46px;
            }

            100% {
                width: 25px;
                left: 14px;
                top: 46px;
            }
        }

        @keyframes icon-line-long {
            0% {
                width: 0;
                right: 8px;
                top: 38px;
            }

            100% {
                width: 47px;
                right: 8px;
                top: 38px;
            }
        }
    </style>
@endsection