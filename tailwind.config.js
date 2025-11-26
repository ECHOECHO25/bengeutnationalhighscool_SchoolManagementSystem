import preset from './vendor/filament/support/tailwind.config.preset'
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
     theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                main: '#111828',
                main1: '#202938'
            }
        },
    },

    plugins: [forms],

}
