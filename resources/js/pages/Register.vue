<template>
  <div class="relative min-h-[90vh] flex items-center justify-center p-4">
    <div class="fixed inset-0 z-0 bg-cover bg-center" style="background-image: url('/images/bg-3.jpg');">
      <div class="absolute inset-0 bg-gray-950/40 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8 md:p-10 my-10">
      <div class="text-center mb-8">
        <h2 class="text-4xl font-black text-white tracking-tighter mb-2 underline decoration-blue-500 decoration-4">REGISTER</h2>
        <p class="text-blue-200 text-sm font-medium">ร่วมเป็นส่วนหนึ่งของ Nostale Ultimate วันนี้</p>
      </div>

      <form @submit.prevent="handleRegister" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1">
          <label class="text-xs font-bold text-white/60 ml-1 uppercase">Username</label>
          <input v-model="form.username" type="text" placeholder="ID เกม" required
            class="w-full bg-black/20 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold text-white/60 ml-1 uppercase">Email</label>
          <input v-model="form.email" type="email" placeholder="example@gmail.com" required
            class="w-full bg-black/20 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        <div class="space-y-1 md:col-span-2">
          <label class="text-xs font-bold text-white/60 ml-1 uppercase">Password</label>
          <input v-model="form.password" type="password" placeholder="รหัสผ่านอย่างน้อย 6 ตัว" required
            class="w-full bg-black/20 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        <div class="space-y-1 md:col-span-2">
          <label class="text-xs font-bold text-white/60 ml-1 uppercase">Phone Number</label>
          <input v-model="form.phone" type="text" placeholder="08X-XXXXXXX"
            class="w-full bg-black/20 border border-white/10 rounded-2xl p-4 text-white focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        <button type="submit"
          class="md:col-span-2 mt-4 py-4 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-500 hover:to-teal-500 text-white font-black rounded-2xl shadow-xl shadow-green-900/40 transform active:scale-95 transition-all uppercase tracking-widest">
          Create Account
        </button>
      </form>

      <div class="mt-8 text-center">
        <router-link to="/login" class="text-white/60 text-sm hover:text-white transition">
          มีไอดีอยู่แล้ว? <span class="text-blue-400 font-bold underline">เข้าสู่ระบบ</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = ref({ username: '', password: '', email: '', phone: '' });

const handleRegister = async () => {
  try {
    await axios.post('/api/register', form.value);
    alert('สมัครสมาชิกสำเร็จ!');
    router.push('/login');
  } catch (error) {
    alert('เกิดข้อผิดพลาด: ' + (error.response?.data?.message || 'สมัครไม่สำเร็จ'));
  }
};
</script>
