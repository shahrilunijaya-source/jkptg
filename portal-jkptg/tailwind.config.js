import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/Livewire/**/*.php',
    './app/Filament/**/*.php',
    './vendor/filament/**/*.blade.php',
    './storage/framework/views/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#243D57',
          50: '#F1F5F9',
          100: '#DBEAFE',
          200: '#BAD0E5',
          300: '#88AECF',
          400: '#5C8EBA',
          500: '#4C75A0',
          600: '#3A5C82',
          700: '#264F73',
          800: '#243D57',
          900: '#0F1E33',
          light: '#4C75A0',
          pale: '#DBEAFE',
          mute: '#264F73',
        },
        footer: '#0F1E33',
        jata: {
          yellow: '#FBBF24',
          red: '#B91C1C',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
        display: ['Inter', 'system-ui', 'sans-serif'],
        mono: ['"JetBrains Mono"', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'monospace'],
      },
      fontFeatureSettings: {
        tabular: '"tnum","lnum"',
      },
      letterSpacing: {
        'mono-cap': '0.12em',
      },
      maxWidth: {
        prose: '65ch',
      },
      boxShadow: {
        'focus-ring': '0 0 0 2px #FFFFFF, 0 0 0 4px #243D57',
      },
      transitionTimingFunction: {
        'out-quart': 'cubic-bezier(0.22, 1, 0.36, 1)',
      },
    },
  },
  plugins: [forms, typography],
};
