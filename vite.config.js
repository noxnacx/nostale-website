import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'; // Import มาแล้ว

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(), // <--- เพิ่มบรรทัดนี้เพื่อเปิดใช้งาน Vue Plugin
        tailwindcss(),
    ],
    // ส่วน server watch สามารถคงไว้ได้ถ้าต้องการลดภาระการโหลดเครื่อง
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
