@extends('layouts.base')

@section('title', 'Terms and Conditions')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --code-bg: #1a1a2e;
            --code-header: #16162a;
            --code-border: #2d2d44;
            --code-string: #98c379;
            --code-keyword: #c678dd;
            --code-comment: #5c6370;
            --code-function: #61afef;
            --code-number: #d19a66;
            --code-property: #e06c75;
            --orange-primary: #ff6b35;
            --orange-light: #ff9d42;
            --orange-gradient: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        }

        body {
            background: #0d0d14;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .page-container {
            padding: 80px 20px 40px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .code-window {
            background: var(--code-bg);
            border-radius: 16px;
            border: 1px solid var(--code-border);
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        }

        .code-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 20px;
            background: var(--code-header);
            border-bottom: 1px solid var(--code-border);
        }

        .code-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .code-dot.red { background: #ff5f57; }
        .code-dot.yellow { background: #febc2e; }
        .code-dot.green { background: #28c840; }

        .code-title {
            margin-left: auto;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
            font-family: 'Fira Code', monospace;
            font-weight: 500;
        }

        .code-filename {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: rgba(255, 121, 0, 0.08);
            border-bottom: 1px solid var(--code-border);
            color: var(--orange-light);
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
        }

        .code-body {
            padding: 24px;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            line-height: 1.8;
            max-height: 70vh;
            overflow-y: auto;
        }

        .code-line {
            display: flex;
            gap: 20px;
        }

        .line-number {
            color: rgba(255, 255, 255, 0.15);
            width: 30px;
            flex-shrink: 0;
            user-select: none;
        }

        .line-content {
            color: rgba(255, 255, 255, 0.85);
            flex: 1;
        }

        .line-content h4 {
            color: var(--orange-light);
            font-family: 'Fira Code', monospace;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .line-content p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 16px;
            line-height: 1.7;
        }

        .line-content ul, 
        .line-content ol {
            padding-left: 24px;
            margin-bottom: 16px;
        }

        .line-content li {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .keyword { color: var(--code-keyword); }
        .string { color: var(--code-string); }
        .comment { color: var(--code-comment); font-style: italic; }
        .function { color: var(--code-function); }
        .property { color: var(--code-property); }
        .number { color: var(--code-number); }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 121, 0, 0.1);
            border: 1px solid rgba(255, 121, 0, 0.2);
            color: var(--orange-light);
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            margin-bottom: 24px;
        }

        .back-btn:hover {
            background: var(--orange-gradient);
            color: white;
            transform: translateX(-4px);
        }

        .section-tag {
            display: inline-block;
            padding: 2px 10px;
            background: rgba(255, 121, 0, 0.15);
            border-radius: 6px;
            font-size: 0.7rem;
            color: var(--orange-light);
            font-family: 'Fira Code', monospace;
            margin-bottom: 8px;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--orange-gradient);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 14px rgba(255, 121, 0, 0.35);
            margin-top: 32px;
        }

        .back-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 121, 0, 0.45);
            color: white;
        }

        .code-body::-webkit-scrollbar {
            width: 6px;
        }

        .code-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .code-body::-webkit-scrollbar-thumb {
            background: rgba(255, 121, 0, 0.2);
            border-radius: 3px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .code-window {
            animation: fadeInUp 0.5s ease;
        }

        @media (max-width: 768px) {
            .code-body {
                padding: 16px;
                font-size: 0.8rem;
            }
            
            .line-number {
                width: 20px;
            }
            
            .code-line {
                gap: 10px;
            }
        }
    </style>
@endsection

@section('body')
    <div class="page-container">
        <a href="{{ url('/') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
            return Home
        </a>

        <div class="code-window">
            <div class="code-header">
                <span class="code-dot red"></span>
                <span class="code-dot yellow"></span>
                <span class="code-dot green"></span>
                <span class="code-title">terms_conditions.js</span>
            </div>
            
            <div class="code-filename">
                <i class="bi bi-file-earmark-code"></i>
                Terms & Conditions — Orange Academy
            </div>

            <div class="code-body">
                <div class="code-line">
                    <span class="line-number">1</span>
                    <div class="line-content">
                        <span class="comment">// Acceptance of Terms of Use</span>
                        <h4><span class="keyword">const</span> acceptanceOfTerms = <span class="string">"By using this Site you agree to be bound by these Terms"</span>;</h4>
                        <p>The following Terms apply to anyone (corporate and individuals) accessing and using Orange Coding Academy website for the subscription in Coding Academy registration application which include content, text, information, advertising, data, audio/visual materials and, software (the "Content") to subscribe.</p>
                    </div>
                </div>

                <div class="code-line">
                    <span class="line-number">2</span>
                    <div class="line-content">
                        <span class="comment">// Privacy Policy and Property Rights</span>
                        <h4><span class="keyword">class</span> <span class="function">PrivacyPolicy</span> {</h4>
                        <p>The provisions of the Privacy Policy apply to the Academy and all the courses it offers during the entire training period and until the end of the contract between them.</p>
                        
                        <ul>
                            <li><span class="property">Collect</span>, <span class="function">use</span> and <span class="function">maintain</span> the information that is collected from me in the manner deemed appropriate by the Coding Academy by Orange Jordan.</li>
                            <li><span class="keyword">const</span> trainingMaterials = <span class="string">"may not be used or sent abroad the Academy except with written permission"</span>;</li>
                            <li><span class="comment">// Student Projects</span></li>
                            <li>All students' small and large projects are educational outputs by the Coding Academy by Orange in Jordan. The Academy has the right to use these projects and/or present them to the concerned authorities without my prior permission.</li>
                            <li><span class="function">photoShoot</span>(<span class="string">"me"</span>) && <span class="function">film</span>(<span class="string">"my projects"</span>) → <span class="string">"use in media without prior permission"</span>;</li>
                        </ul>
                    </div>
                </div>

                <div class="code-line">
                    <span class="line-number">3</span>
                    <div class="line-content">
                        <span class="comment">// ACCESS</span>
                        <h4><span class="keyword">function</span> <span class="function">accessSite</span>() {</h4>
                        <ul>
                            <li><span class="keyword">if</span> (<span class="function">provideInformation</span>(false)) { <span class="comment">// untrue, inaccurate</span></li>
                            <li style="padding-left: 20px;"><span class="keyword">return</span> <span class="function">suspendAccount</span>();</li>
                            <li>}</li>
                            <li><span class="keyword">const</span> password = <span class="function">getPassword</span>();</li>
                            <li><span class="keyword">if</span> (<span class="function">unauthorizedAccess</span>(password)) {</li>
                            <li style="padding-left: 20px;"><span class="function">notifyUser</span>(<span class="string">"security breach"</span>);</li>
                            <li style="padding-left: 20px;"><span class="keyword">return</span> <span class="function">terminateAccount</span>();</li>
                            <li>}</li>
                        </ul>
                    </div>
                </div>

                <div class="code-line">
                    <span class="line-number">4</span>
                    <div class="line-content">
                        <span class="comment">// Creating Your Profile</span>
                        <h4><span class="keyword">const</span> registrationSteps = [</h4>
                        <ol>
                            <li><span class="string">"Log on to orangecodingacademy.com website"</span>,</li>
                            <li><span class="string">"Access Sign on Services from the homepage"</span>,</li>
                            <li><span class="string">"Fill in the registration form"</span></li>
                        ];<br>
                        <span class="function">registerProfile</span>(registrationSteps);
                    </div>
                </div>

                <div class="code-line">
                    <span class="line-number">5</span>
                    <div class="line-content">
                        <span class="comment">// PRIVACY POLICY</span>
                        <h4><span class="keyword">import</span> { privacyPolicy } <span class="keyword">from</span> <span class="string">'@orange/policy'</span>;</h4>
                        <p><span class="keyword">await</span> <span class="function">viewPrivacyPolicy</span>(); <span class="comment">// PLEASE VIEW OUR PRIVACY POLICY</span></p>
                        <p><span class="keyword">this</span>.useOfInformation = <span class="function">consentToCollection</span>(); <span class="comment">// You consent to the collection and use of this information</span></p>
                    </div>
                </div>

                <div class="code-line">
                    <span class="line-number">6</span>
                    <div class="line-content">
                        <span class="comment">// DISCLAIMER OF WARRANTIES AND LIMITATION OF LIABILITY</span>
                        <h4><span class="keyword">const</span> siteStatus = <span class="string">"as is"</span>; <span class="comment">// "as is" and "as available" basis</span></h4>
                        <ul>
                            <li><span class="keyword">try</span> {</li>
                            <li style="padding-left: 20px;"><span class="keyword">const</span> warranty = <span class="function">disclaimAllWarranties</span>();</li>
                            <li style="padding-left: 20px;"><span class="keyword">const</span> liability = <span class="function">limitLiability</span>(<span class="number">0</span>);</li>
                            <li>} <span class="keyword">catch</span> (damages) {</li>
                            <li style="padding-left: 20px;"><span class="function">notLiable</span>(<span class="string">"direct, indirect, incidental, punitive, consequential damages"</span>);</li>
                            <li>}</li>
                        </ul>
                        <span class="comment">// No warranty for viruses or security threats</span>
                        <span class="keyword">const</span> security = <span class="string">"reasonable steps taken, but no guarantee"</span>;
                    </div>
                </div>

                <div class="code-line">
                    <span class="line-number">7</span>
                    <div class="line-content">
                        <span class="comment">// End of Terms</span>
                        <span class="keyword">export default</span> <span class="function">agreeToTerms</span>();
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url('/') }}" class="back-home">
                <i class="bi bi-house-door"></i>
                Back to homepage
            </a>
        </div>
    </div>
@endsection
