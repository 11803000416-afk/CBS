<?php

/**
 * ═══════════════════════════════════════════════════════════════════════
 * CBS OPTIMIZATION CONFIGURATION
 * Clean • Responsive • User-Friendly • Best Performance • Accessibility
 * ═══════════════════════════════════════════════════════════════════════
 */

return [

    /**
     * PERFORMANCE SETTINGS
     */
    'performance' => [
        // Enable asset minification
        'minify_assets' => env('APP_ENV') === 'production',

        // Cache duration (seconds)
        'cache_duration' => 3600,

        // Enable lazy loading by default
        'lazy_load_images' => true,

        // Image optimization quality (1-100)
        'image_quality' => 85,

        // Enable CDN for static assets
        'use_cdn' => env('USE_CDN', false),
        'cdn_url' => env('CDN_URL', ''),
    ],

    /**
     * ACCESSIBILITY SETTINGS (WCAG 2.1 AA)
     */
    'accessibility' => [
        // Minimum color contrast ratio (WCAG AA = 4.5:1)
        'min_contrast_ratio' => 4.5,

        // Enable focus visible on all interactive elements
        'show_focus_outline' => true,

        // Focus outline width (px)
        'focus_outline_width' => 2,

        // Minimum touch target size (px - should be 44px minimum)
        'min_touch_target' => 44,

        // Enable reduced motion support
        'respect_prefers_reduced_motion' => true,

        // Enable screen reader announcements
        'enable_aria_live' => true,

        // Skip link enabled
        'show_skip_link' => true,
    ],

    /**
     * RESPONSIVE DESIGN SETTINGS
     */
    'responsive' => [
        // Breakpoints matching Tailwind
        'breakpoints' => [
            'sm' => '640px',
            'md' => '768px',
            'lg' => '1024px',
            'xl' => '1280px',
            '2xl' => '1536px',
        ],

        // Mobile-first approach
        'mobile_first' => true,

        // Container max widths
        'max_widths' => [
            'sm' => '640px',
            'md' => '768px',
            'lg' => '1024px',
            'xl' => '1280px',
            '2xl' => '1536px',
        ],

        // Responsive spacing scale (4px = 1 unit)
        'spacing_scale' => [
            'xs' => '0.25rem',
            'sm' => '0.5rem',
            'md' => '1rem',
            'lg' => '1.5rem',
            'xl' => '2rem',
            '2xl' => '3rem',
        ],
    ],

    /**
     * USER EXPERIENCE SETTINGS
     */
    'ux' => [
        // Form validation timing (ms) before showing error
        'form_validation_debounce' => 300,

        // Toast notification duration (ms)
        'toast_duration' => 5000,

        // Button loading state text
        'button_loading_text' => 'Loading...',

        // Show loading spinners
        'show_spinners' => true,

        // Smooth scroll behavior
        'smooth_scroll' => true,

        // Confirm before leaving unsaved changes
        'confirm_unsaved_changes' => true,

        // Show tooltips on hover
        'enable_tooltips' => true,

        // Tooltip delay (ms)
        'tooltip_delay' => 500,
    ],

    /**
     * CACHING STRATEGY
     */
    'cache' => [
        // Cache static assets (images, CSS, JS)
        'cache_static' => true,
        'static_ttl' => 31536000, // 1 year

        // Cache API responses
        'cache_api' => true,
        'api_ttl' => 300, // 5 minutes

        // Cache database queries
        'cache_queries' => true,
        'query_ttl' => 30, // 30 seconds

        // Enable query result caching
        'enable_query_cache' => true,
    ],

    /**
     * LOADING & PRELOADING
     */
    'preload' => [
        // Preload critical paths
        'enabled' => true,

        // Prefetch probable next pages
        'prefetch_enabled' => true,

        // DNS prefetch for external domains
        'dns_prefetch' => [
            'https://fonts.googleapis.com',
            'https://cdnjs.cloudflare.com',
        ],

        // Preconnect to external domains
        'preconnect' => [
            'https://fonts.gstatic.com',
        ],
    ],

    /**
     * FORM SETTINGS
     */
    'forms' => [
        // Validate on blur
        'validate_on_blur' => true,

        // Validate on input (with debounce)
        'validate_on_input' => true,
        'validation_debounce' => 300,

        // Show inline error messages
        'inline_errors' => true,

        // Prevent double form submission
        'prevent_double_submit' => true,

        // Auto-focus first invalid field
        'auto_focus_invalid' => true,

        // Show required indicator
        'show_required_indicator' => true,
    ],

    /**
     * IMAGE OPTIMIZATION
     */
    'images' => [
        // Format to serve (webp, jpg, png)
        'default_format' => 'webp',

        // Lazy load images by default
        'lazy_load' => true,

        // Generate responsive srcsets
        'generate_srcset' => true,

        // Responsive image sizes
        'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw',

        // Supported widths for srcset
        'widths' => [320, 640, 1024, 1280],

        // Placeholder while loading
        'placeholder' => 'blur', // 'blur' or 'color'
        'placeholder_color' => '#e5e7eb',
    ],

    /**
     * FONT LOADING
     */
    'fonts' => [
        // Font display strategy
        'display' => 'swap', // 'auto', 'block', 'swap', 'fallback', 'optional'

        // Preload font files
        'preload' => true,

        // Font sizes (base for responsive scaling)
        'base_size' => 16,
        'sizes' => [
            'xs' => 0.75,   // 12px
            'sm' => 0.875,  // 14px
            'base' => 1,    // 16px
            'lg' => 1.125,  // 18px
            'xl' => 1.25,   // 20px
            '2xl' => 1.5,   // 24px
            '3xl' => 1.875, // 30px
            '4xl' => 2.25,  // 36px
            '5xl' => 3,     // 48px
        ],
    ],

    /**
     * ANIMATIONS & TRANSITIONS
     */
    'animations' => [
        // Default animation duration (ms)
        'duration' => 200,

        // Animation timing function
        'timing' => 'ease',

        // Disable animations on slow connections
        'disable_on_slow_network' => true,

        // Respect prefers-reduced-motion
        'respect_motion_preference' => true,

        // Available animations
        'enabled' => [
            'fade',
            'slide',
            'scale',
            'rotate',
        ],
    ],

    /**
     * MONITORING & ANALYTICS
     */
    'monitoring' => [
        // Enable performance monitoring
        'enabled' => true,

        // Monitor Core Web Vitals
        'track_vitals' => true,

        // Send metrics to server
        'send_metrics' => env('APP_ENV') === 'production',

        // Endpoint for metrics
        'metrics_endpoint' => '/api/metrics',

        // Enable error tracking
        'track_errors' => true,

        // Error tracking endpoint
        'error_endpoint' => '/api/errors',
    ],

    /**
     * BROWSER SUPPORT
     */
    'browser_support' => [
        // Minimum supported browser versions
        'chrome' => 90,
        'firefox' => 88,
        'safari' => 14,
        'edge' => 90,

        // Show browser compatibility warning
        'show_compatibility_warning' => true,

        // Fallback polyfills
        'enable_polyfills' => true,
    ],

    /**
     * DEBUG & DEVELOPMENT
     */
    'debug' => [
        // Enable debug mode
        'enabled' => env('APP_DEBUG', false),

        // Show performance metrics in console
        'show_metrics' => true,

        // Show optimization notices
        'show_notices' => true,

        // Enable verbose logging
        'verbose_logging' => false,
    ],

];
