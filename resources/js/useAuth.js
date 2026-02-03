import { ref } from 'vue';
import axios from 'axios';
import router from './router';

// ตัวแปร Global เก็บข้อมูล User (ใช้ร่วมกันทุกหน้า)
const user = ref(null);

export default function useAuth() {

    // ฟังก์ชันเช็คว่าล็อกอินอยู่ไหม (เรียกตอนเปิดเว็บ)
    const fetchUser = async () => {
        try {
            const response = await axios.get('/api/user');
            user.value = response.data;
        } catch (error) {
            user.value = null;
        }
    };

    // ฟังก์ชัน Login (ใช้ในหน้า Login.vue)
    const login = async (credentials) => {
        await axios.post('/api/login', credentials);
        await fetchUser(); // ดึงข้อมูล User ทันทีหลัง Login ผ่าน
        router.push('/dashboard'); // เด้งไปหน้า Dashboard โดยไม่ต้องรีโหลดหน้า
    };

    // ฟังก์ชัน Logout
    const logout = async () => {
        try {
            await axios.post('/api/logout');
            user.value = null; // เคลียร์ค่า User
            router.push('/login');
        } catch (error) {
            console.error('Logout Failed', error);
        }
    };

    return {
        user,
        fetchUser,
        login,
        logout
    };
}
