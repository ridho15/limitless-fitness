import { defineConfig } from "vite";

// Make laravel-vite-plugin optional so tests can run in environments
// where the plugin isn't installed (e.g., CI or local dev without full deps)
let laravelPlugin = null;
try {
    // eslint-disable-next-line global-require
    laravelPlugin = require("laravel-vite-plugin");
} catch (e) {
    // Plugin is optional for tests; we'll continue without it
    laravelPlugin = null;
}

export default defineConfig({
    plugins: laravelPlugin ? [
        laravelPlugin({
            input: ["resources/css/app.css", "resources/js/app.js"],
            // refresh: ["resources/routes/**", "routes/**", "resources/views/**"],
            refresh: true,
        })
    ] : [],
    server: {
        hmr: {
            host: 'http://127.0.0.1:8005',
            // host: 'localhost'
        },
        watch: {
            usePolling: true
        }
    },
});
