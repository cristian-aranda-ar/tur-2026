/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary:   '#35C071',
        secondary: '#E87219',
        tertiary:  '#0C3423',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
  // Gutenberg compatibility: evitar que Preflight rompa los bloques en el editor
  corePlugins: {
    preflight: true,
  },
}
