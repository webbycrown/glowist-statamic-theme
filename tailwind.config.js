/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    // './resources/**/*.antlers.html',
    // './resources/**/*.antlers.php',
    // './resources/**/*.blade.php',
    // './resources/**/*.vue',
    // './content/**/*.md',
    // './layouts/*.html'
     './resources/views/**/*.antlers.html',
  './resources/views/**/*.blade.php',
  './resources/js/**/*.js',
  './resources/css/**/*.css',
  './content/**/*.md',
  './site/**/*.md',
  ],
  
  theme: {
    extend: {
      screens: {
        '2sm': '480px',
        // => @media (min-width: 460px) { ... }
        '3sm': '380px',
        // => @media (min-width: 380px) { ... }
      },
      colors: {
        'white': '#ffffff',
        'green': '#008000',

        gray: {
          '400':'#E6E6E6',
          '500':'#F2F2F2',
          '800': '#646464',
          '900': '#191919',
        },

      },
      
      fontFamily: {
        'matter': ['Matter', 'sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji']       
      },
    },
  },
  safelist: [
  'ps-[8px]',
  'me-[5px]',
  'flex',
  'w-full',
  '2xl:max-w-[100px]',
  '2xl:min-w-[100px]',
  '2xl:h-[80px]',
  'xl:max-w-[60px]',
  'xl:min-w-[60px]',
  'xl:h-[50px]',
  '2sm:max-w-[100px]',
  '2sm:min-w-[100px]',
  '2sm:h-[80px]',
  'max-w-[80px]',
  'min-w-[80px]',
  'h-[70px]',
  'me-3',
  '2xl:w-[20px]',
  '2xl:h-[20px]',
  'w-[16px]',
  'h-[16px]',
  'me-1',
  'items-center',
  'font-matter',
  '2xl:text-base',
  'text-sm',
  'text-gray-800',
  '2xl:mb-3',
  'xl:mb-2',
  'mb-3'
],
  plugins: [],
}

