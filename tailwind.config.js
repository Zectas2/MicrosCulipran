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
                primario: '#EA580C',
                secundario: '#FFFFFF',
                acento: '#65A30D',
                peligro: '#DC2626',
                fondo: '#F5F5F4',
                texto: '#292524',
            },
            fontFamily: {
                sans: ['ui-sans-serif', 'system-ui', 'sans-serif'],
            }
        },
    },
    plugins: [],
}