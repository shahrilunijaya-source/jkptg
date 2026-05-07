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
          DEFAULT: '#1B5E3F',
          50: '#F1F7F3',
          100: '#E1ECE5',
          200: '#C2D8CB',
          300: '#9EBFA9',
          400: '#6F9D7E',
          500: '#4F7E60',
          600: '#346548',
          700: '#1B5E3F',
          800: '#144A30',
          900: '#0B3220',
          light: '#4F7E60',
          pale: '#E8F0EA',
          mute: '#144A30',
        },
        canvas: {
          DEFAULT: '#FAF7F0',
          mute: '#F2EEE2',
          ink: '#2A2620',
        },
        bronze: {
          DEFAULT: '#8B6F3A',
          dark: '#6B5429',
          light: '#C9B58A',
        },
        footer: '#0B3220',
        jata: {
          yellow: '#FBBF24',
          red: '#B91C1C',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
        display: ['Poppins', 'Inter', 'system-ui', 'sans-serif'],
      },
      maxWidth: {
        prose: '65ch',
      },
      boxShadow: {
        'focus-ring': '0 0 0 3px rgba(27, 94, 63, 0.45)',
      },
    },
  },
  plugins: [forms, typography],
};
