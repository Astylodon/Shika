import path from "path";
import fs from "fs";
import { defineConfig } from "vite";
import react from '@vitejs/plugin-react'

export default defineConfig({
    plugins: [
        react(),
        {
            name: "hot",
            configureServer(server) {
                fs.writeFileSync(path.resolve(__dirname, ".env.hot"), `VITE_HOST=http://localhost:${server.config.server.port}`)
            }
        }
    ],

    build: {
        outDir: path.resolve(__dirname, "public/build"),
        emptyOutDir: true,
        manifest: "manifest.json",

        rollupOptions: {
            input: ["resources/js/main.tsx"]
        }
    }
})
