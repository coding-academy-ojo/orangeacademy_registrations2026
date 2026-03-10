/**
 * Orange Academy — Global Bilingual Language System
 * Supports Arabic (RTL) ↔ English (LTR)
 * Usage: add data-en="..." data-ar="..." to any element.
 *        Call OA_Lang.set('ar') or OA_Lang.set('en')
 */
(function () {
    const STORAGE_KEY = 'oa_lang';

    const OA_Lang = {
        current: 'en',

        init: function () {
            const saved = localStorage.getItem(STORAGE_KEY) || 'en';
            this.apply(saved, false);

            // Bind all toggle buttons
            document.querySelectorAll('[data-lang-btn]').forEach(btn => {
                btn.addEventListener('click', () => {
                    OA_Lang.set(btn.getAttribute('data-lang-btn'));
                });
            });
        },

        set: function (lang) {
            this.apply(lang, true);
        },

        apply: function (lang, save) {
            this.current = lang;
            const isAr = lang === 'ar';

            // document / html element direction
            document.documentElement.setAttribute('lang', lang);
            document.documentElement.setAttribute('dir', isAr ? 'rtl' : 'ltr');

            // body classes
            document.body.classList.toggle('lang-ar', isAr);
            document.body.classList.toggle('lang-en', !isAr);

            // 1. Explicit data-en / data-ar (has highest priority)
            document.querySelectorAll('[data-en]').forEach(el => {
                const val = el.getAttribute(isAr ? 'data-ar' : 'data-en');
                if (val !== null) el.textContent = val;
            });

            // Swap placeholder attributes
            document.querySelectorAll('[data-en-placeholder]').forEach(el => {
                const val = el.getAttribute(isAr ? 'data-ar-placeholder' : 'data-en-placeholder');
                if (val !== null) el.placeholder = val;
            });

            // 2. Global Dictionary Auto-Translation (only if OADict exists)
            if (window.OADict) {
                this.translateDOM(document.body, isAr);
            }

            // Update all toggle buttons visuals
            document.querySelectorAll('[data-lang-btn]').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-lang-btn') === lang);
            });

            if (save) localStorage.setItem(STORAGE_KEY, lang);
        },

        // Recursive function to translate text nodes and placeholders
        translateDOM: function (node, isAr) {
            // Ignore script, style, and elements with explicit data-en
            if (node.nodeType === Node.ELEMENT_NODE) {
                if (['SCRIPT', 'STYLE', 'NOSCRIPT', 'CODE', 'PRE'].includes(node.tagName)) return;
                if (node.hasAttribute('data-en') || node.hasAttribute('data-no-translate')) return;

                // Translate inputs/select placeholders or values
                if (node.tagName === 'INPUT' || node.tagName === 'TEXTAREA') {
                    if (node.placeholder) {
                        this.processTextProperty(node, 'placeholder', isAr);
                    }
                }

                // Traverse children
                for (let i = 0; i < node.childNodes.length; i++) {
                    this.translateDOM(node.childNodes[i], isAr);
                }
            } else if (node.nodeType === Node.TEXT_NODE) {
                this.processTextProperty(node, 'nodeValue', isAr);
            }
        },

        processTextProperty: function (node, prop, isAr) {
            // prop is usually 'nodeValue' or 'placeholder'
            let text = node[prop].trim();
            if (!text) return; // Skip empty whitespace nodes

            // Store original english text
            if (!node.oaOrigEn) {
                // If the initial text is found in dictionary values (it's already Arabic), don't store it as orig.
                node.oaOrigEn = text;
            }

            if (isAr) {
                // Try to find the english text in our dictionary
                let origText = node.oaOrigEn;
                // Try exact match
                if (window.OADict[origText]) {
                    node[prop] = node[prop].replace(origText, window.OADict[origText]);
                } else {
                    // Try case insensitive match or clean match (ignoring leading/trailing punctuation)
                    for (const [enKey, arVal] of Object.entries(window.OADict)) {
                        if (origText.includes(enKey)) {
                            node[prop] = node[prop].replace(enKey, arVal);
                        }
                    }
                }
            } else {
                // Restore english
                if (node.oaOrigEn) {
                    node[prop] = node.oaOrigEn;
                }
            }
        }
    };

    // Expose globally
    window.OA_Lang = OA_Lang;

    // Auto-init on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => OA_Lang.init());
    } else {
        OA_Lang.init();
    }
})();
