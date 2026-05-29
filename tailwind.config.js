/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        vehica: {
          50: '#effafc',
          100: '#d9f4fb',
          200: '#b6eaf7',
          300: '#84d8ef',
          400: '#4fc1e4',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0f172a',
        },
        // Premium Brand Colors
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c3d66',
        },
        // Accent - Deep Indigo for sophistication
        accent: {
          50: '#f0f4ff',
          100: '#e6edff',
          200: '#c7d9ff',
          300: '#a8c5ff',
          400: '#7fa3ff',
          500: '#5681ff',
          600: '#3d5cff',
          700: '#2d42dd',
          800: '#1f2fa8',
          900: '#162175',
        },
        // Success - Emerald
        success: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#145231',
        },
        // Warning - Amber
        warning: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#d97706',
          700: '#b45309',
          800: '#92400e',
          900: '#78350f',
        },
        // Danger - Rose
        danger: {
          50: '#fff5f7',
          100: '#ffe4eb',
          200: '#ffc9d5',
          300: '#ffadd1',
          400: '#ff8cb5',
          500: '#f472b6',
          600: '#ec4899',
          700: '#be185d',
          800: '#9d174d',
          900: '#831843',
        },
        // Info - Cyan
        info: {
          50: '#ecf9ff',
          100: '#cff3ff',
          200: '#a8e6ff',
          300: '#81d9ff',
          400: '#59ccff',
          500: '#31bfff',
          600: '#0ab8ff',
          700: '#0a96d8',
          800: '#0a7aa3',
          900: '#084d6e',
        },
        // Dark Neutral
        dark: {
          50: '#f9fafb',
          100: '#f3f4f6',
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
        },
      },
      fontFamily: {
        sans: ['Inter', 'Sora', 'system-ui', 'sans-serif'],
        inter: ['Inter', 'system-ui', 'sans-serif'],
        display: ['Sora', 'Inter', 'system-ui', 'sans-serif'],
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'pulse-subtle': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'glow': 'glow 2s ease-in-out infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        glow: {
          '0%, 100%': { boxShadow: '0 0 20px rgba(14, 165, 233, 0.3)' },
          '50%': { boxShadow: '0 0 30px rgba(14, 165, 233, 0.5)' },
        },
      },
      boxShadow: {
        'xs': '0 1px 1px 0 rgb(0 0 0 / 0.05)',
        'sm-light': '0 1px 2px 0 rgb(0 0 0 / 0.05)',
        'card': '0 1px 3px 0 rgb(0 0 0 / 0.08), 0 1px 2px 0 rgb(0 0 0 / 0.04)',
        'card-hover': '0 10px 24px 0 rgb(0 0 0 / 0.12)',
        'hover': '0 4px 12px 0 rgb(0 0 0 / 0.1)',
        'lg': '0 20px 40px -10px rgb(0 0 0 / 0.1)',
        'premium': '0 15px 35px 0 rgb(14, 165, 233, 0.15)',
      },
      backdropFilter: {
        'glass': 'blur(20px)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
