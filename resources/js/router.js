import { createRouter, createWebHistory } from 'vue-router';

// เดี๋ยวเราจะไปสร้างไฟล์หน้าเว็บ (Pages) เหล่านี้ในขั้นตอนถัดไป
// ตอนนี้อาจจะขึ้น Error สีแดงๆ ว่าหาไฟล์ไม่เจอ ไม่ต้องตกใจนะครับ
import Home from './pages/Home.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import AdminDashboard from './pages/AdminDashboard.vue';
import NewsDetail from './pages/NewsDetail.vue';
import NewsArchive from './pages/NewsArchive.vue';
import UserDashboard from './pages/UserDashboard.vue';
import Download from './pages/Download.vue';

const routes = [
    { path: '/', component: Home, name: 'home' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/register', component: Register, name: 'register' },
    { path: '/admin', component: AdminDashboard, name: 'admin' },
    { path: '/news/:id', component: NewsDetail, name: 'news-detail' },
    { path: '/news', component: NewsArchive, name: 'news-archive' },
    { path: '/dashboard', component: UserDashboard, name: 'dashboard' },
    { path: '/download', component: Download, name: 'download' }, // เพิ่ม Route
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
