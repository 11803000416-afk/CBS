/**
 * ═══════════════════════════════════════════════════════════════════════
 * 🎯 CBS OPTIMIZATION - JAVASCRIPT UTILITIES & PERFORMANCE
 * ═══════════════════════════════════════════════════════════════════════
 */

(function(window) {
    'use strict';

    // ─────────────────────────────────────────────────────────────────
    // SECTION: UTILITY FUNCTIONS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Debounce function to prevent excessive function calls
     * @param {Function} func - Function to debounce
     * @param {number} wait - Milliseconds to wait before executing
     * @returns {Function} Debounced function
     */
    window.debounce = function(func, wait = 300) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };

    /**
     * Throttle function to limit function call frequency
     * @param {Function} func - Function to throttle
     * @param {number} limit - Milliseconds between calls
     * @returns {Function} Throttled function
     */
    window.throttle = function(func, limit = 300) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    };

    /**
     * Event delegation for better performance
     * @param {string} selector - CSS selector for elements
     * @param {string} event - Event type (e.g., 'click')
     * @param {Function} handler - Event handler function
     */
    window.delegate = function(selector, event, handler) {
        document.addEventListener(event, function(e) {
            let target = e.target.closest(selector);
            if (target) handler.call(target, e);
        });
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: LAZY LOADING
    // ─────────────────────────────────────────────────────────────────

    /**
     * Setup Intersection Observer for lazy loading images
     * Optimizes performance by only loading images when visible
     */
    window.setupLazyLoading = function() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        // Use data-src for lazy loading
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('image-lazy');
                        }
                        observer.unobserve(img);
                    }
                });
            });

            // Observe all lazy images
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for older browsers
            document.querySelectorAll('img[data-src]').forEach(img => {
                img.src = img.dataset.src;
            });
        }
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: FORM OPTIMIZATION
    // ─────────────────────────────────────────────────────────────────

    /**
     * Real-time form validation with debouncing
     * Prevents excessive validation calls while typing
     */
    window.setupFormValidation = function() {
        const inputs = document.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('input', debounce(function() {
                validateField(this);
            }, 300));
        });
    };

    /**
     * Validate individual form field
     * @param {HTMLElement} field - Form field element
     */
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;

        // Check required
        if (field.hasAttribute('required') && !value) {
            isValid = false;
        }

        // Check email
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        }

        // Check min length
        if (field.hasAttribute('minlength')) {
            const minLength = parseInt(field.getAttribute('minlength'));
            isValid = value.length >= minLength;
        }

        // Update field state
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            if (field.nextElementSibling?.classList.contains('error-text')) {
                field.nextElementSibling.style.display = 'none';
            }
        } else {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            if (field.nextElementSibling?.classList.contains('error-text')) {
                field.nextElementSibling.style.display = 'block';
            }
        }
    }

    /**
     * Prevent double form submission
     */
    window.preventDoubleSubmit = function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('is-loading');
                }
            });
        });
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: MODAL/DIALOG MANAGEMENT
    // ─────────────────────────────────────────────────────────────────

    /**
     * Simple modal management system
     */
    window.Modal = {
        open: function(selector) {
            const modal = document.querySelector(selector);
            if (modal) {
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                // Focus trap
                this.setFocusTrap(modal);
            }
        },

        close: function(selector) {
            const modal = document.querySelector(selector);
            if (modal) {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        },

        setFocusTrap: function(modal) {
            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            modal.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey && document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
                if (e.key === 'Escape') {
                    this.close(modal);
                }
            });

            firstElement?.focus();
        }
    };

    /**
     * Setup modal triggers
     */
    window.setupModals = function() {
        // Open modals
        document.querySelectorAll('[data-modal-open]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const modalSelector = this.getAttribute('data-modal-open');
                window.Modal.open(modalSelector);
            });
        });

        // Close modals
        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const modalSelector = this.getAttribute('data-modal-close');
                window.Modal.close(modalSelector);
            });
        });

        // Close on backdrop click
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.addEventListener('click', function(e) {
                if (e.target === this) {
                    window.Modal.close('.' + this.className.split(' ')[0]);
                }
            });
        });
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: NOTIFICATION/TOAST MANAGEMENT
    // ─────────────────────────────────────────────────────────────────

    /**
     * Toast notification system
     */
    window.Toast = {
        show: function(message, type = 'info', duration = 5000) {
            const container = document.getElementById('toast-container') || 
                             this.createContainer();

            const toast = document.createElement('div');
            toast.className = `alert alert-${type}`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'polite');

            toast.innerHTML = `
                <span>${this.escapeHtml(message)}</span>
                <button class="alert-close" aria-label="Close notification">&times;</button>
            `;

            container.appendChild(toast);

            // Animation
            setTimeout(() => toast.classList.add('show'), 10);

            // Close button
            toast.querySelector('.alert-close').addEventListener('click', () => {
                this.remove(toast);
            });

            // Auto remove
            if (duration > 0) {
                setTimeout(() => this.remove(toast), duration);
            }

            return toast;
        },

        remove: function(toast) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        },

        createContainer: function() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = `
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 10000;
                max-width: 500px;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            `;
            document.body.appendChild(container);
            return container;
        },

        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    /**
     * Convenience functions for specific toast types
     */
    window.showToast = {
        success: (msg, duration) => window.Toast.show(msg, 'success', duration),
        error: (msg, duration) => window.Toast.show(msg, 'error', duration),
        warning: (msg, duration) => window.Toast.show(msg, 'warning', duration),
        info: (msg, duration) => window.Toast.show(msg, 'info', duration)
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: ASSET PRELOADING
    // ─────────────────────────────────────────────────────────────────

    /**
     * Preload assets for better performance
     * @param {string} href - URL of asset to preload
     * @param {string} as - Type of asset (style, script, image, etc.)
     */
    window.preloadAsset = function(href, as = 'script') {
        if (!document.querySelector(`link[href="${href}"]`)) {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = href;
            link.as = as;
            if (as === 'font') {
                link.crossOrigin = 'anonymous';
            }
            document.head.appendChild(link);
        }
    };

    /**
     * Prefetch URLs for next navigation
     * @param {string} url - URL to prefetch
     */
    window.prefetchUrl = function(url) {
        if (!document.querySelector(`link[href="${url}"]`)) {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = url;
            document.head.appendChild(link);
        }
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: PERFORMANCE MONITORING
    // ─────────────────────────────────────────────────────────────────

    /**
     * Simple performance metrics
     */
    window.Performance = {
        metrics: {},

        mark: function(name) {
            this.metrics[name] = performance.now();
        },

        measure: function(name, startName) {
            if (this.metrics[startName]) {
                const duration = performance.now() - this.metrics[startName];
                console.log(`⏱️  ${name}: ${duration.toFixed(2)}ms`);
                return duration;
            }
        },

        getWebVitals: function() {
            if ('web-vital' in window) {
                return window['web-vital'];
            }
            return null;
        }
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: ACCESSIBILITY ENHANCEMENTS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Setup keyboard navigation for custom components
     */
    window.setupKeyboardNav = function() {
        // Skip links
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Menu navigation with arrow keys
        document.querySelectorAll('[role="menuitem"]').forEach(item => {
            item.addEventListener('keydown', function(e) {
                const parent = this.parentElement;
                const siblings = Array.from(parent.children);
                const index = siblings.indexOf(this);

                switch(e.key) {
                    case 'ArrowRight':
                    case 'ArrowDown':
                        e.preventDefault();
                        siblings[(index + 1) % siblings.length].focus();
                        break;
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        siblings[(index - 1 + siblings.length) % siblings.length].focus();
                        break;
                    case 'Home':
                        e.preventDefault();
                        siblings[0].focus();
                        break;
                    case 'End':
                        e.preventDefault();
                        siblings[siblings.length - 1].focus();
                        break;
                }
            });
        });
    };

    /**
     * Announce messages to screen readers
     * @param {string} message - Message to announce
     * @param {string} priority - 'polite' or 'assertive'
     */
    window.announceToScreenReader = function(message, priority = 'polite') {
        let announcer = document.getElementById('sr-announcer');
        if (!announcer) {
            announcer = document.createElement('div');
            announcer.id = 'sr-announcer';
            announcer.className = 'sr-only';
            announcer.setAttribute('aria-live', priority);
            announcer.setAttribute('aria-atomic', 'true');
            document.body.appendChild(announcer);
        }
        announcer.textContent = message;
        announcer.setAttribute('aria-live', priority);
    };

    // ─────────────────────────────────────────────────────────────────
    // SECTION: INITIALIZATION
    // ─────────────────────────────────────────────────────────────────

    // GLOBAL: Axios + Fetch error handling (show toasts for standardized API responses)
    function setupGlobalApiErrorHandling() {
        // Axios interceptor
        if (window.axios) {
            window.axios.interceptors.response.use(function (response) {
                // If API sends success:false still show toast on message if present
                if (response?.data && response.data.success === false && response.data.message) {
                    window.showToast.error(response.data.message);
                }
                return response;
            }, function (error) {
                try {
                    const res = error?.response?.data;
                    if (res) {
                        const msg = res.message || 'An error occurred';
                        window.showToast.error(msg);
                    } else {
                        window.showToast.error('Network error. Please check your connection.');
                    }
                } catch (e) {
                    window.showToast.error('An unexpected error occurred');
                }
                return Promise.reject(error);
            });
        }

        // Wrap fetch
        if (window.fetch) {
            const nativeFetch = window.fetch.bind(window);
            window.fetch = async function(input, init) {
                try {
                    const resp = await nativeFetch(input, init);
                    const contentType = resp.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        const body = await resp.clone().json();
                        if (body && body.success === false && body.message) {
                            window.showToast.error(body.message);
                        }
                    } else if (!resp.ok) {
                        window.showToast.error('Network error. Please try again.');
                    }
                    return resp;
                } catch (err) {
                    window.showToast.error('Network error. Please check your connection.');
                    throw err;
                }
            };
        }
    }

    /**
     * Initialize all optimizations when DOM is ready
     */
    window.initOptimizations = function() {
        // Run optimizations
        setupLazyLoading();
        setupFormValidation();
        setupModals();
        preventDoubleSubmit();
        setupKeyboardNav();

        console.log('✅ CBS Optimizations initialized');
        setupGlobalApiErrorHandling();
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.initOptimizations);
    } else {
        window.initOptimizations();
    }

})(window);

/**
 * ═══════════════════════════════════════════════════════════════════════
 * End Optimization Script
 * ═══════════════════════════════════════════════════════════════════════
 */
