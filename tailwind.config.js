/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'forest': '#2D5A3D',
        'forest-dark': '#1E3D2A',
        'cream': '#FAF8F5',
        'sand': '#F5F1EA',
        'ochre': '#C4A35A',
        'terracotta': '#C67B5C',
        'sage': '#8FAE8B',
      },
      fontFamily: {
        'display': ['Playfair Display', 'serif'],
        'body': ['Inter', 'sans-serif'],
      }
    },
  },
  plugins: [],
}
