<template>
  <div class="min-h-screen bg-gray-950 font-sans text-gray-100 pb-20 pt-4">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6">

      <aside class="md:col-span-1 space-y-4">
        <div class="bg-gray-900 rounded-2xl p-6 border border-white/10 text-center shadow-lg">
          <div class="w-20 h-20 bg-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center text-3xl font-black shadow-lg shadow-blue-500/30">
            {{ user?.name?.charAt(0).toUpperCase() || 'U' }}
          </div>
          <h2 class="text-xl font-bold text-white truncate">{{ user?.name || 'Guest' }}</h2>
          <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">Member</p>
          <div class="mt-4 pt-4 border-t border-white/5 flex justify-between text-sm">
             <span class="text-gray-400">Cash Point:</span>
             <span class="text-green-400 font-bold">0 P</span>
          </div>
        </div>

        <div class="bg-gray-900 rounded-2xl overflow-hidden border border-white/10">
          <button class="w-full text-left px-6 py-4 bg-blue-600/10 text-blue-400 font-bold border-l-4 border-blue-500">
             📊 ข้อมูลทั่วไป
          </button>
          <button class="w-full text-left px-6 py-4 text-gray-400 hover:bg-gray-800 hover:text-white transition">
             🔒 เปลี่ยนรหัสผ่าน
          </button>
          <button @click="logout" class="w-full text-left px-6 py-4 text-red-400 hover:bg-red-500/10 hover:text-red-500 transition border-t border-white/5">
             🚪 ออกจากระบบ
          </button>
        </div>
      </aside>

      <main class="md:col-span-3 space-y-6">

        <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-blue-900 to-indigo-900 p-8 shadow-2xl border border-white/10">
           <div class="absolute right-0 top-0 h-full w-1/2 bg-[url('/images/bg-5.jpg')] bg-cover bg-center opacity-20 mask-image-gradient"></div>
           <div class="relative z-10">
             <h1 class="text-3xl font-bold text-white mb-2">สวัสดี, {{ user?.name }} 👋</h1>
             <p class="text-blue-200">ยินดีต้อนรับสู่ระบบจัดการบัญชี Nostale Ultimate</p>
           </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="bg-gray-900 p-6 rounded-2xl border border-white/5 flex items-center gap-4">
             <div class="p-3 bg-yellow-500/20 text-yellow-400 rounded-xl text-2xl">💰</div>
             <div>
               <p class="text-xs text-gray-400 uppercase">Cash Point</p>
               <p class="text-xl font-bold text-white">0</p>
             </div>
          </div>
          <div class="bg-gray-900 p-6 rounded-2xl border border-white/5 flex items-center gap-4">
             <div class="p-3 bg-green-500/20 text-green-400 rounded-xl text-2xl">🎮</div>
             <div>
               <p class="text-xs text-gray-400 uppercase">สถานะไอดี</p>
               <p class="text-xl font-bold text-green-400">ปกติ</p>
             </div>
          </div>
          <div class="bg-gray-900 p-6 rounded-2xl border border-white/5 flex items-center gap-4">
             <div class="p-3 bg-purple-500/20 text-purple-400 rounded-xl text-2xl">📅</div>
             <div>
               <p class="text-xs text-gray-400 uppercase">วันที่สมัคร</p>
               <p class="text-sm font-bold text-white">{{ formatDate(user?.created_at) }}</p>
             </div>
          </div>
        </div>

        <div class="bg-gray-900 rounded-2xl border border-white/10 p-6">
          <h3 class="text-lg font-bold text-white mb-4 border-b border-white/5 pb-2">ตัวละครในเกม</h3>
          <div class="text-center py-10 text-gray-500 border-2 border-dashed border-white/5 rounded-xl">
             <p class="text-4xl mb-2">🐉</p>
             <p>ยังไม่พบข้อมูลตัวละคร</p>
             <p class="text-sm opacity-60">(ต้องสร้างตัวละครในเกมก่อน ระบบถึงจะแสดงข้อมูล)</p>
          </div>
        </div>

      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const user = ref(null);

const fetchUser = async () => {
  try {
    const response = await axios.get('/api/user');
    user.value = response.data;
  } catch (error) {
    // ถ้าดึงข้อมูลไม่ได้ (เช่น session หมดอายุ) ให้เด้งไป Login
    window.location.href = '/login';
  }
};

const logout = async () => {
  if(!confirm('ยืนยันออกจากระบบ?')) return;
  try {
    await axios.post('/api/logout');
    window.location.href = '/login';
  } catch (error) {
    alert('Logout Error');
  }
};

const formatDate = (date) => {
  if(!date) return '-';
  return new Date(date).toLocaleDateString('th-TH');
}

onMounted(() => {
  fetchUser();
});
</script>

<style scoped>
.mask-image-gradient {
  mask-image: linear-gradient(to left, black, transparent);
  -webkit-mask-image: linear-gradient(to left, black, transparent);
}
</style>
