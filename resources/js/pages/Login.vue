<template>
  <div class="relative min-h-[90vh] flex items-center justify-center p-4">
    <div class="fixed inset-0 z-0 bg-cover bg-center" style="background-image: url('/images/bg-3.jpg');">
      <div class="absolute inset-0 bg-gray-950/40 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden p-8 md:p-10">
      <div class="text-center mb-8">
        <h2 class="text-4xl font-black text-white tracking-tighter mb-2">WELCOME BACK</h2>
        <p class="text-blue-200 text-sm font-medium">เข้าสู่ระบบเพื่อจัดการไอดีของคุณ</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div class="space-y-1">
          <label class="text-xs font-bold text-white/60 ml-1 uppercase">Username (ID)</label>
          <input v-model="form.username" type="text" required
            class="w-full bg-black/20 border border-white/10 rounded-2xl p-4 text-white placeholder-white/30 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold text-white/60 ml-1 uppercase">Password</label>
          <input v-model="form.password" type="password" required
            class="w-full bg-black/20 border border-white/10 rounded-2xl p-4 text-white placeholder-white/30 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
        </div>

        <button type="submit" :disabled="isLoading"
          class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black rounded-2xl shadow-xl shadow-blue-900/40 transform active:scale-95 transition-all uppercase tracking-widest disabled:opacity-50">
          {{ isLoading ? 'Loading...' : 'Login Now' }}
        </button>
      </form>

      <div class="mt-8 text-center border-t border-white/10 pt-6">
        <p class="text-white/60 text-sm">ยังไม่มีบัญชีใช่ไหม?</p>
        <router-link to="/register" class="text-blue-400 font-bold hover:text-blue-300 underline underline-offset-4 transition">
          สร้างไอดีใหม่ที่นี่
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import useAuth from '../useAuth'; // นำเข้าตัวจัดการ Login

const { login } = useAuth(); // ดึงฟังก์ชัน login มาใช้
const form = ref({ username: '', password: '' });
const isLoading = ref(false);

const handleLogin = async () => {
  isLoading.value = true;
  try {
    // ใช้ฟังก์ชัน login จาก useAuth แทน axios ตรงๆ
    // มันจะจัดการดึงข้อมูล User และเปลี่ยนหน้าให้เอง
    await login(form.value);

    // (ไม่ต้อง window.location.href แล้ว ระบบจะเปลี่ยนหน้าแบบ Smooth เอง)
  } catch (error) {
    alert('❌ เข้าสู่ระบบไม่สำเร็จ: ' + (error.response?.data?.message || 'โปรดตรวจสอบข้อมูล'));
  } finally {
    isLoading.value = false;
  }
};
</script>
