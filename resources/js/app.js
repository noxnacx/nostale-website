import './bootstrap';
import { createApp } from 'vue';
import router from './router'; // <-- เพิ่มบรรทัดนี้
import App from './App.vue';

const app = createApp(App);
app.use(router); // <-- สั่งให้ใช้ Router
app.mount('#app');
