import path from "path";
import { defineConfig } from "vite";

export default defineConfig({
    root: "assets",
    
    build: {
        outDir: path.resolve(__dirname, "public/build"),
        emptyOutDir: true,
        manifest: "manifest.json",

        rollupOptions: {
            input: {
                css: "assets/css/app.scss",
                main: "assets/js/main.js",
                graphs: "assets/js/graphs.js"
            }
        }
    }
})
