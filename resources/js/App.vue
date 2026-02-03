<template>
  <div class="min-h-screen bg-gray-950 text-gray-100 font-sans selection:bg-blue-500 selection:text-white">
    <nav class="bg-gray-900/80 backdrop-blur-md border-b border-white/5 sticky top-0 z-50 transition-all duration-300">
      <div class="container mx-auto px-4 md:px-6 py-4">
        <div class="flex justify-between items-center relative">

          <router-link to="/" class="text-xl md:text-2xl font-black tracking-tighter hover:opacity-80 transition z-50 relative">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-500 drop-shadow-[0_0_10px_rgba(59,130,246,0.5)]">
              NOSTALE INFINITY
            </span>
          </router-link>

          <button @click="isMenuOpen = !isMenuOpen" class="relative z-50 md:hidden text-gray-300 focus:outline-none p-2 rounded-lg hover:bg-white/5 active:bg-white/10 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <div class="hidden md:flex items-center space-x-1">
            <router-link to="/" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-all duration-200">หน้าหลัก</router-link>
            <router-link to="/news" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-all duration-200">ข่าวสาร</router-link>
            <router-link to="/download" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-all duration-200">ดาวน์โหลด</router-link>
            <a href="#" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-all duration-200">เติมเงิน</a>

            <div class="h-6 w-px bg-white/10 mx-4"></div>

            <template v-if="user">
              <div class="relative group">
                <button class="flex items-center gap-3 px-2 py-1.5 rounded-full hover:bg-white/5 transition-all duration-300 border border-transparent hover:border-white/10">
                  <div class="text-right hidden lg:block">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Welcome</p>
                    <p class="text-sm font-bold text-white leading-none">{{ user.username || user.name }}</p>
                  </div>
                  <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-[0_0_15px_rgba(37,99,235,0.4)] border-2 border-gray-900 group-hover:scale-110 transition-transform">
                    {{ (user?.username || user?.name || 'U').charAt(0).toUpperCase() }}
                  </div>
                </button>
                <div class="absolute right-0 mt-2 w-56 bg-gray-900 rounded-2xl border border-white/10 shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transform group-hover:translate-y-0 translate-y-2 transition-all duration-200 overflow-hidden">
                  <div class="p-4 border-b border-white/5 bg-white/5">
                    <p class="text-xs text-gray-500 font-bold uppercase">Cash Points</p>
                    <p class="text-xl font-black text-green-400">0 <span class="text-xs text-gray-400">P</span></p>
                  </div>
                  <router-link to="/dashboard" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-300 hover:bg-blue-600 hover:text-white transition-colors"><span>📊</span> จัดการบัญชี</router-link>
                  <router-link v-if="user.role === 'admin'" to="/admin" class="flex items-center gap-3 px-4 py-3 text-sm text-yellow-400 hover:bg-yellow-600 hover:text-white transition-colors"><span>⚡</span> GM Panel</router-link>
                  <button @click="handleLogout" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-400 hover:bg-red-600 hover:text-white transition-colors text-left"><span>🚪</span> ออกจากระบบ</button>
                </div>
              </div>
            </template>
            <template v-else>
              <router-link to="/login" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white transition-colors">เข้าสู่ระบบ</router-link>
              <router-link to="/register" class="group relative px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-full overflow-hidden shadow-lg shadow-blue-900/30 transition-all hover:bg-blue-500 hover:shadow-blue-600/50 hover:-translate-y-0.5"><span class="relative z-10">สมัครสมาชิก</span></router-link>
            </template>
          </div>
        </div>

        <transition name="slide">
          <div v-if="isMenuOpen" class="md:hidden mt-4 pb-4 space-y-2 border-t border-gray-800 pt-4 bg-gray-900/95 backdrop-blur-xl rounded-xl p-4 border border-white/5 relative z-40">

            <template v-if="user">
              <div class="flex items-center gap-3 px-4 py-3 bg-white/5 rounded-xl mb-4 border border-white/5">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                  {{ (user?.username || user?.name || 'U').charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="text-white font-bold">{{ user.username || user.name }}</p>
                  <p class="text-xs text-green-400">0 Points</p>
                </div>
              </div>
              <router-link @click="isMenuOpen = false" to="/dashboard" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition font-medium">📊 จัดการบัญชี</router-link>
              <router-link v-if="user.role === 'admin'" @click="isMenuOpen = false" to="/admin" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-yellow-400 hover:text-white transition font-medium">⚡ GM Panel</router-link>
              <button @click="handleLogout" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-red-400 hover:text-white transition font-medium w-full text-left">🚪 ออกจากระบบ</button>
            </template>

            <template v-else>
              <div class="grid grid-cols-2 gap-3 mb-4">
                <router-link @click="isMenuOpen = false" to="/login" class="flex items-center justify-center px-4 py-3 rounded-xl border border-white/10 text-gray-300 font-bold hover:bg-white/5 hover:text-white transition">
                  เข้าสู่ระบบ
                </router-link>
                <router-link @click="isMenuOpen = false" to="/register" class="flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-500 transition">
                  สมัครสมาชิก
                </router-link>
              </div>
            </template>

            <div class="h-px bg-white/10 my-2"></div>

            <router-link @click="isMenuOpen = false" to="/" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition font-medium">หน้าหลัก</router-link>
            <router-link @click="isMenuOpen = false" to="/news" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition font-medium">ข่าวสาร</router-link>
            <router-link @click="isMenuOpen = false" to="/download" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition font-medium">ดาวน์โหลด</router-link>
          </div>
        </transition>
      </div>
    </nav>

    <main class="container mx-auto px-4 md:px-6 py-6 md:py-8 min-h-[80vh]">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import useAuth from './useAuth';

const { user, fetchUser, logout } = useAuth();
const isMenuOpen = ref(false);

onMounted(() => {
  fetchUser();
});

const handleLogout = async () => {
  await logout();
  isMenuOpen.value = false;
};
</script>

<style>
.slide-enter-active, .slide-leave-active { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); max-height: 500px; opacity: 1; }
.slide-enter-from, .slide-leave-to { max-height: 0; opacity: 0; transform: translateY(-10px); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
