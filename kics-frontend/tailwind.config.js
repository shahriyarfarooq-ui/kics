/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    screens: {
      'xs': '475px',
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    },
    extend: {
      colors: {
        // Modern Blue + Cyan Tech palette
        primary: {
          50:  '#eff6ff',   // Very light blue mist
          100: '#dbeafe',   // Light blue
          200: '#bfdbfe',   // Soft blue tint
          300: '#93c5fd',   // Light tech blue
          400: '#60a5fa',   // Medium blue
          500: '#3b82f6',   // Main blue
          600: '#2563eb',   // Deep blue
          700: '#1d4ed8',   // Rich blue
          800: '#1e40af',   // Dark tech blue
          900: '#1e3a8a',   // Deep navy blue
          950: '#0B2447',   // Deepest tech navy
        },
        accent: {
          400: '#22d3ee',   // Neon cyan
          500: '#06b6d4',   // Main cyan
          600: '#0891b2',   // Deep teal
        },
        slate: {
          50:  '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        },
        // Legacy support — updated to tech blue
        navy: { DEFAULT: '#0B1833', 950: '#0B1833', light: '#1e40af', dark: '#1e3a8a' },
        gold: { DEFAULT: '#B45309', light: '#D97706', tint: '#FEF3C7' },
      },
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
        heading: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Arial', 'sans-serif'],
      },
      backgroundImage: {
        'hero-gradient':    'linear-gradient(135deg, rgba(11,36,71,0.9) 0%, rgba(30,64,175,0.85) 100%)',
        'primary-gradient': 'linear-gradient(135deg, #2563eb, #06b6d4)',
        'card-gradient':    'linear-gradient(145deg, #ffffff, #eff6ff)',
      },
      boxShadow: {
        'card':       '0 4px 24px rgba(37,99,235,0.08)',
        'card-hover': '0 12px 40px rgba(37,99,235,0.16)',
        'primary':    '0 4px 20px rgba(6,182,212,0.28)',
        'gold':       '0 4px 16px rgba(180,83,9,0.40)',
        'nav':        '0 2px 8px rgba(0,0,0,0.08)',
      },
      animation: {
        marquee:          'marquee 12s linear infinite',
        'marquee-slow':   'marquee 20s linear infinite',
        fadeUp:           'fadeUp 0.7s ease forwards',
        fadeIn:           'fadeIn 0.6s ease forwards',
        fadeInUp:         'fadeInUp 0.7s cubic-bezier(.16,1,.3,1)',
        slideLeft:        'slideLeft 0.6s ease forwards',
        slideRight:       'slideRight 0.6s ease forwards',
        slideInUp:        'slideInUp 0.7s cubic-bezier(.16,1,.3,1)',
        slideInDown:      'slideInDown 0.7s cubic-bezier(.16,1,.3,1)',
        scaleIn:          'scaleIn 0.6s cubic-bezier(.16,1,.3,1)',
        float:            'float 3s ease-in-out infinite',
        'pulse-glow':     'pulse-glow 2s ease-in-out infinite',
        pulse2:           'pulse2 2s ease-in-out infinite',
        shimmer:          'shimmer 2s ease-in-out infinite',
        'rotate-slow':    'rotate-slow 20s linear infinite',
        'bounce-gentle':  'bounce-gentle 2s ease-in-out infinite',
        'gradient-shift': 'gradient-shift 8s ease infinite',
        // ✅ Added for skeleton loader
        'shimmer':        'shimmer 1.5s ease-in-out infinite',
      },
      keyframes: {
        marquee:      { '0%': { transform: 'translateX(0%)' }, '100%': { transform: 'translateX(-50%)' } },
        fadeUp:       { from: { opacity: 0, transform: 'translateY(40px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
        fadeIn:       { from: { opacity: 0 }, to: { opacity: 1 } },
        fadeInUp:     { from: { opacity: 0, transform: 'translateY(20px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
        slideLeft:    { from: { opacity: 0, transform: 'translateX(-40px)' }, to: { opacity: 1, transform: 'translateX(0)' } },
        slideRight:   { from: { opacity: 0, transform: 'translateX(40px)' }, to: { opacity: 1, transform: 'translateX(0)' } },
        slideInUp:    { from: { opacity: 0, transform: 'translateY(40px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
        slideInDown:  { from: { opacity: 0, transform: 'translateY(-40px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
        scaleIn:      { from: { opacity: 0, transform: 'scale(0.85)' }, to: { opacity: 1, transform: 'scale(1)' } },
        float:        { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
        'pulse-glow': {
          '0%,100%': { opacity: 1, boxShadow: '0 0 20px rgba(6,182,212,0.4)' },
          '50%':      { opacity: 0.85, boxShadow: '0 0 40px rgba(6,182,212,0.6)' },
        },
        pulse2:           { '0%,100%': { opacity: 1 }, '50%': { opacity: 0.5 } },
        shimmer:          { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
        'rotate-slow':    { from: { transform: 'rotate(0deg)' }, to: { transform: 'rotate(360deg)' } },
        'bounce-gentle':  { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-8px)' } },
        'gradient-shift': {
          '0%':   { backgroundPosition: '0% 50%' },
          '50%':  { backgroundPosition: '100% 50%' },
          '100%': { backgroundPosition: '0% 50%' },
        },
        // ✅ Added for skeleton loader
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
      },
      transitionDuration: { 400: '400ms' },
    },
  },
  plugins: [],
}