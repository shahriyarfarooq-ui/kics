import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  // The frontend is served from the domain root; absolute paths also keep
  // assets working when a nested SPA route is refreshed directly.
  base: '/',
  plugins: [react()],
  build: {
    // Use esbuild for faster minification (default, faster than terser)
    minify: 'esbuild',
    // Target modern browsers for smaller bundles
    target: 'es2015',
    // Manual chunk splitting for better caching
    rollupOptions: {
      output: {
        manualChunks: {
          // Split vendor chunks for better caching
          'react-vendor': ['react', 'react-dom', 'react-router-dom'],
          'icons': ['react-icons', 'lucide-react'],
        },
      },
    },
    // Chunk size warnings
    chunkSizeWarningLimit: 1000,
    // Source maps for debugging (disable in production for smaller size)
    sourcemap: false,
  },
  // Optimize dependencies
  optimizeDeps: {
    include: ['react', 'react-dom', 'react-router-dom'],
  },
  // CSS code splitting
  css: {
    devSourcemap: false,
  },
})
