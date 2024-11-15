import preset from '../../../../vendor/filament/filament/tailwind.config.preset'
const colors = require('tailwindcss/colors');
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                gray: colors.gray,
                primary: colors.indigo,
                secondary: colors.sky,
                danger: colors.rose,
                success: colors.emerald,
                warning: colors.orange,
                info: colors.blue,
            }
        },
    },
}
