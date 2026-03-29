<div class="a11y-container" id="a11yContainer">
    <!-- Overlay for mobile/blur effect -->
    <div class="a11y-overlay" id="a11yOverlay"></div>

    <!-- Floating Toggle Button -->
    <button class="a11y-main-btn" id="a11yToggle" aria-label="Accessibility Options" aria-expanded="false" aria-controls="a11ySheet">
        <div class="btn-inner">
            <i class="bi bi-person-wheelchair"></i>
        </div>
        <div class="active-indicator" id="a11yIndicator"></div>
    </button>

    <!-- Modern Glassmorphism Sheet/Panel -->
    <div class="a11y-sheet" id="a11ySheet" role="dialog" aria-modal="true" aria-labelledby="a11yTitle">
        <div class="sheet-header">
            <div class="drag-handle d-md-none"></div>
            <div class="d-flex align-items-center justify-content-between w-100">
                <h5 id="a11yTitle" class="m-0 fw-800 text-gradient">
                    <span data-en="Accessibility" data-ar="إمكانية الوصول">Accessibility</span>
                </h5>
                <button class="btn-close-a11y" id="a11yClose" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <div class="sheet-body">
            <!-- Font Size Group -->
            <div class="a11y-group">
                <div class="group-label">
                    <i class="bi bi-fonts"></i>
                    <span data-en="Text Scaling" data-ar="تحجيم النص">Text Scaling</span>
                    <span class="value-badge" id="fontSizeValue">100%</span>
                </div>
                <div class="scaling-controls">
                    <button class="scale-btn" onclick="changeFontSize(-1)" aria-label="Decrease font size">
                        <i class="bi bi-dash"></i>
                    </button>
                    <div class="scale-track">
                        <div class="scale-fill" id="fontSizeFill"></div>
                    </div>
                    <button class="scale-btn" onclick="changeFontSize(1)" aria-label="Increase font size">
                        <i class="bi bi-plus"></i>
                    </button>
                    <button class="reset-link" onclick="resetFontSize()" data-en="Reset" data-ar="إعادة تعيين">Reset</button>
                </div>
            </div>

            <div class="a11y-divider"></div>

            <!-- Toggles Group -->
            <div class="toggles-grid">
                <div class="a11y-toggle-card" onclick="toggleOption('highContrast')">
                    <div class="card-icon">
                        <i class="bi bi-circle-half"></i>
                    </div>
                    <div class="card-info">
                        <span class="title" data-en="High Contrast" data-ar="تباين عالٍ">High Contrast</span>
                        <span class="desc" data-en="Better readability" data-ar="قراءة أفضل">Better readability</span>
                    </div>
                    <div class="custom-switch">
                        <input type="checkbox" id="highContrastToggle" hidden>
                        <div class="switch-slider"></div>
                    </div>
                </div>

                <div class="a11y-toggle-card" onclick="toggleOption('reduceMotion')">
                    <div class="card-icon">
                        <i class="bi bi-wind"></i>
                    </div>
                    <div class="card-info">
                        <span class="title" data-en="Reduce Motion" data-ar="تقليل الحركة">Reduce Motion</span>
                        <span class="desc" data-en="Calmer interface" data-ar="واجهة أهدأ">Calmer interface</span>
                    </div>
                    <div class="custom-switch">
                        <input type="checkbox" id="reduceMotionToggle" hidden>
                        <div class="switch-slider"></div>
                    </div>
                </div>
            </div>

            <div class="a11y-divider"></div>

            <!-- Color Blindness Group -->
            <div class="a11y-group">
                <div class="group-label">
                    <i class="bi bi-palette"></i>
                    <span data-en="Color Filters" data-ar="فلاتر الألوان">Color Filters</span>
                </div>
                <div class="color-filters-grid">
                    <button class="color-filter-btn" onclick="setColorFilter('none')" data-filter="none" title="Normal">
                        <span class="color-preview normal"></span>
                        <small data-en="Normal" data-ar="عادي">Normal</small>
                    </button>
                    <button class="color-filter-btn" onclick="setColorFilter('grayscale')" data-filter="grayscale" title="Grayscale">
                        <span class="color-preview grayscale"></span>
                        <small data-en="Grayscale" data-ar="رمادي">Grayscale</small>
                    </button>
                    <button class="color-filter-btn" onclick="setColorFilter('protanopia')" data-filter="protanopia" title="Protanopia">
                        <span class="color-preview protanopia"></span>
                        <small data-en="Protan" data-ar="أحمر">Protan</small>
                    </button>
                    <button class="color-filter-btn" onclick="setColorFilter('deuteranopia')" data-filter="deuteranopia" title="Deuteranopia">
                        <span class="color-preview deuteranopia"></span>
                        <small data-en="Deutan" data-ar="أخضر">Deutan</small>
                    </button>
                    <button class="color-filter-btn" onclick="setColorFilter('tritanopia')" data-filter="tritanopia" title="Tritanopia">
                        <span class="color-preview tritanopia"></span>
                        <small data-en="Tritan" data-ar="أزرق">Tritan</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SVG Filters for Color Blindness (Daltonization) -->
<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <filter id="protanopia">
            <feColorMatrix type="matrix" values="0.567, 0.433, 0, 0, 0, 0.558, 0.442, 0, 0, 0, 0, 0.242, 0.758, 0, 0, 0, 0, 0, 1, 0" />
        </filter>
        <filter id="deuteranopia">
            <feColorMatrix type="matrix" values="0.625, 0.375, 0, 0, 0, 0.7, 0.3, 0, 0, 0, 0, 0.3, 0.7, 0, 0, 0, 0, 0, 1, 0" />
        </filter>
        <filter id="tritanopia">
            <feColorMatrix type="matrix" values="0.95, 0.05, 0, 0, 0, 0, 0.433, 0.567, 0, 0, 0, 0, 0.475, 0.525, 0, 0, 0, 0, 1, 0" />
        </filter>
    </defs>
</svg>

<style>
    .color-filters-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }

    .color-filter-btn {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 12px;
        padding: 8px 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }

    .color-filter-btn:hover { background: #f8fafc; transform: translateY(-2px); }
    .color-filter-btn.active { border-color: var(--a11y-accent); background: rgba(255,121,0,0.05); }

    .color-preview {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .color-preview.normal { background: linear-gradient(to right, #ff0000, #00ff00, #0000ff); }
    .color-preview.grayscale { background: #888; }
    .color-preview.protanopia { background: #666600; }
    .color-preview.deuteranopia { background: #775500; }
    .color-preview.tritanopia { background: #ff00ff; }

    .color-filter-btn small { font-size: 0.65rem; font-weight: 600; color: #64748b; }
    .color-filter-btn.active small { color: var(--a11y-accent); }

    /* Application of filters */
    body.filter-grayscale { filter: grayscale(1); }
    body.filter-protanopia { filter: url('#protanopia'); }
    body.filter-deuteranopia { filter: url('#deuteranopia'); }
    body.filter-tritanopia { filter: url('#tritanopia'); }
    /* Premium Modern Tokens */
    :root {
        --a11y-glass: rgba(255, 255, 255, 0.7);
        --a11y-glass-border: rgba(255, 255, 255, 0.4);
        --a11y-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        --a11y-accent: #ff7900;
        --a11y-accent-gradient: linear-gradient(135deg, #ff7900 0%, #ff5000 100%);
    }

    [dir="rtl"] {
        --a11y-float-pos: auto;
        --a11y-float-side: 24px;
    }

    [dir="ltr"] {
        --a11y-float-pos: 24px;
        --a11y-float-side: auto;
    }

    /* Container & Overlay */
    .a11y-container {
        position: fixed;
        bottom: 24px;
        right: var(--a11y-float-pos);
        left: var(--a11y-float-side);
        z-index: 10000;
        pointer-events: none;
    }

    .a11y-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: -1;
        pointer-events: all;
    }

    .a11y-container.open .a11y-overlay {
        opacity: 1;
        visibility: visible;
    }

    /* Main Toggle Button */
    .a11y-main-btn {
        width: 60px;
        height: 60px;
        padding: 0;
        border: none;
        background: transparent;
        cursor: pointer;
        pointer-events: all;
        position: relative;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .a11y-main-btn:hover {
        transform: scale(1.1) rotate(5deg);
    }

    .a11y-main-btn:active {
        transform: scale(0.95);
    }

    .btn-inner {
        width: 100%;
        height: 100%;
        background: var(--a11y-accent-gradient);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.6rem;
        box-shadow: 0 10px 25px rgba(255, 121, 0, 0.35);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .active-indicator {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 14px;
        height: 14px;
        background: #10b981;
        border: 3px solid white;
        border-radius: 50%;
        display: none;
    }

    /* Modern Sheet */
    .a11y-sheet {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 340px;
        background: var(--a11y-glass);
        backdrop-filter: blur(20px);
        border: 1px solid var(--a11y-glass-border);
        border-radius: 28px;
        padding: 24px;
        box-shadow: var(--a11y-shadow);
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.95);
        transform-origin: bottom right;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: all;
    }

    [dir="rtl"] .a11y-sheet {
        right: auto;
        left: 0;
        transform-origin: bottom left;
    }

    .a11y-container.open .a11y-sheet {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    /* Sheet Components */
    .sheet-header h5 {
        font-weight: 850;
        letter-spacing: -0.02em;
    }

    .text-gradient {
        background: linear-gradient(90deg, #0f172a, #ff7900);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .btn-close-a11y {
        border: none;
        background: rgba(0, 0, 0, 0.05);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s;
    }

    .btn-close-a11y:hover { background: rgba(0, 0, 0, 0.1); }

    .a11y-group {
        margin-top: 24px;
    }

    .group-label {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .group-label i { color: var(--a11y-accent); }

    .value-badge {
        margin-left: auto;
        background: white;
        padding: 2px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        color: var(--a11y-accent);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .scaling-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .scale-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,0.08);
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .scale-btn:hover { background-color: #f8fafc; transform: translateY(-1px); }

    .scale-track {
        flex: 1;
        height: 6px;
        background: rgba(0,0,0,0.06);
        border-radius: 3px;
        overflow: hidden;
    }

    .scale-fill {
        height: 100%;
        background: var(--a11y-accent);
        width: 50%;
        transition: width 0.3s;
    }

    .reset-link {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        background: none;
        border: none;
        padding: 4px;
        text-decoration: underline;
    }

    .a11y-divider {
        height: 1px;
        background: rgba(0,0,0,0.04);
        margin: 20px 0;
    }

    /* Cards Grid */
    .toggles-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .a11y-toggle-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 18px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .a11y-toggle-card:hover {
        border-color: var(--a11y-accent-gradient);
        background: rgba(255, 121, 0, 0.02);
    }

    .a11y-toggle-card .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #475569;
        transition: all 0.3s;
    }

    .a11y-toggle-card.active .card-icon {
        background: var(--a11y-accent-gradient);
        color: white;
    }

    .card-info { flex: 1; display: flex; flex-direction: column; }
    .card-info .title { font-size: 0.9rem; font-weight: 700; color: #0f172a; }
    .card-info .desc { font-size: 0.75rem; color: #64748b; }

    /* Custom Switch */
    .custom-switch .switch-slider {
        width: 44px;
        height: 24px;
        background: #e2e8f0;
        border-radius: 12px;
        position: relative;
        transition: all 0.3s;
    }

    .custom-switch .switch-slider::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 18px;
        height: 18px;
        background: white;
        border-radius: 50%;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .a11y-toggle-card.active .switch-slider { background: #10b981; }
    .a11y-toggle-card.active .switch-slider::after { left: 23px; }

    /* RESPONSIVE DESIGN */
    @media (max-width: 576px) {
        .a11y-container {
            bottom: 20px;
            right: 20px;
            left: 20px;
            display: flex;
            justify-content: center;
        }

        .a11y-sheet {
            position: fixed;
            bottom: -100%;
            left: 0;
            right: 0;
            width: 100%;
            border-radius: 32px 32px 0 0;
            transform: none !important;
            transition: bottom 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            padding-bottom: 40px;
        }

        .a11y-container.open .a11y-sheet {
            bottom: 0;
        }

        .drag-handle {
            width: 40px;
            height: 5px;
            background: rgba(0,0,0,0.1);
            border-radius: 10px;
            margin: -10px auto 15px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('a11yToggle');
        const container = document.getElementById('a11yContainer');
        const overlay = document.getElementById('a11yOverlay');
        const closeBtn = document.getElementById('a11yClose');
        
        const togglePanel = () => {
            const isOpen = container.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen);
        };

        toggle.addEventListener('click', togglePanel);
        overlay.addEventListener('click', togglePanel);
        closeBtn.addEventListener('click', togglePanel);

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && container.classList.contains('open')) togglePanel();
        });

        checkActiveStatus();
    });

    let currentFontSize = 100;
    function changeFontSize(delta) {
        currentFontSize += delta * 10;
        if (currentFontSize < 70) currentFontSize = 70;
        if (currentFontSize > 150) currentFontSize = 150;
        applyFontSize();
    }

    function resetFontSize() {
        currentFontSize = 100;
        applyFontSize();
    }

    function applyFontSize() {
        document.documentElement.style.fontSize = currentFontSize + '%';
        document.getElementById('fontSizeValue').textContent = currentFontSize + '%';
        document.getElementById('fontSizeFill').style.width = ((currentFontSize - 70) / (150 - 70) * 100) + '%';
        localStorage.setItem('a11y-font-size', currentFontSize);
        checkActiveStatus();
    }

    function toggleOption(type) {
        const isHighContrast = type === 'highContrast';
        const className = isHighContrast ? 'high-contrast' : 'reduced-motion';
        const storageKey = `a11y-${isHighContrast ? 'high-contrast' : 'reduced-motion'}`;
        const card = document.querySelector(`.a11y-toggle-card[onclick*="${type}"]`);
        
        document.body.classList.toggle(className);
        const isActive = document.body.classList.contains(className);
        card.classList.toggle('active', isActive);
        localStorage.setItem(storageKey, isActive);
        checkActiveStatus();

        // Small Haptic-like effect
        card.style.transform = 'scale(0.98)';
        setTimeout(() => card.style.transform = '', 100);
    }

    function checkActiveStatus() {
        const indicator = document.getElementById('a11Indicator');
        const hasColorFilter = !!document.body.className.split(' ').find(c => c.startsWith('filter-'));
        const isActive = currentFontSize !== 100 || 
                         document.body.classList.contains('high-contrast') || 
                         document.body.classList.contains('reduced-motion') ||
                         hasColorFilter;
        
        if (indicator) indicator.style.display = isActive ? 'block' : 'none';
    }

    function setColorFilter(type) {
        // Remove existing filters
        const activeFilters = Array.from(document.body.classList).filter(c => c.startsWith('filter-'));
        activeFilters.forEach(c => document.body.classList.remove(c));

        // Remove active class from buttons
        document.querySelectorAll('.color-filter-btn').forEach(btn => btn.classList.remove('active'));

        if (type !== 'none') {
            document.body.classList.add(`filter-${type}`);
            document.querySelector(`.color-filter-btn[data-filter="${type}"]`).classList.add('active');
            localStorage.setItem('a11y-color-filter', type);
        } else {
            localStorage.removeItem('a11y-color-filter');
            document.querySelector(`.color-filter-btn[data-filter="none"]`).classList.add('active');
        }
        
        checkActiveStatus();
    }

    // Load preferences
    (function() {
        const savedFontSize = localStorage.getItem('a11y-font-size');
        if (savedFontSize) {
            currentFontSize = parseInt(savedFontSize);
            // We need to wait for DOM for fill and text
            window.addEventListener('load', applyFontSize);
        }

        const options = ['high-contrast', 'reduced-motion'];
        options.forEach(opt => {
            if (localStorage.getItem(`a11y-${opt}`) === 'true') {
                document.body.classList.add(opt);
                window.addEventListener('load', () => {
                    const type = opt === 'high-contrast' ? 'highContrast' : 'reduceMotion';
                    document.querySelector(`.a11y-toggle-card[onclick*="${type}"]`).classList.add('active');
                });
            }
        });

        // Load color filter
        const savedColorFilter = localStorage.getItem('a11y-color-filter');
        if (savedColorFilter) {
            document.body.classList.add(`filter-${savedColorFilter}`);
            window.addEventListener('load', () => {
                const btn = document.querySelector(`.color-filter-btn[data-filter="${savedColorFilter}"]`);
                if (btn) btn.classList.add('active');
            });
        } else {
            window.addEventListener('load', () => {
                const btn = document.querySelector(`.color-filter-btn[data-filter="none"]`);
                if (btn) btn.classList.add('active');
            });
        }
    })();
</script>
