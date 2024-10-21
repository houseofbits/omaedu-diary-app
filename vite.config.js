import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import { resolve } from 'node:path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    liveReload([
      // edit live reload paths according to your source code
      // for example:
      __dirname + '/(app|config|views)/**/*.php',
      // using this for our example:
      __dirname + '/../public/*.php',
    ]),
  ],
  root: 'src',

  build: {
    outDir: '../public/dist',
    manifest: true,
    emptyOutDir: true,

    rollupOptions: {
      input: resolve(__dirname, 'src/main.js'),
      output: {
        manualChunks(id) {
          if (id.includes('node_modules')) {
            return 'vendor'
          }
        },
      },
    }        
  },
  server: {
    // we need a strict port to match on PHP side
    // change freely, but update on PHP to match the same port
    // tip: choose a different port per project to run them at the same time
    strictPort: true,
    port: 5133
  },

  // required for in-browser template compilation
  // https://vuejs.org/guide/scaling-up/tooling.html#note-on-in-browser-template-compilation
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js'
    }
  }  
})
