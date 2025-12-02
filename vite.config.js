import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
  plugins: [
    vue(),
    vueDevTools()
  ],

  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },

  // ⬇⬇⬇ DETTE ER NYT ⬇⬇⬇
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8085', // din PHP API
        changeOrigin: true,
        // secure: false, // kun nødvendigt ved https-self-signed, så vi lader den være
      }
    }
  },
  // ⬆⬆⬆ DETTE ER NYT ⬆⬆⬆

  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `
        @use "@/assets/style/globalVariables.scss" as *;
        @use "@/assets/style/main.scss";
      `
      }
    }
  },

  base: '/app/'
})
