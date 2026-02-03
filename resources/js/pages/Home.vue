<template>
  <div class="relative min-h-screen font-sans text-gray-100">
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat transition-all duration-1000" style="background-image: url('/images/bg-2.png');"><div class="absolute inset-0 bg-gray-950/85 backdrop-blur-[1px]"></div></div>
    <div class="relative z-10 max-w-6xl mx-auto space-y-10 md:space-y-14 px-4 md:px-6 pb-20 pt-6">
      <section class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/10 group mt-4">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-105" style="background-image: url('/images/bg-4.jpg');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/60 to-transparent"></div>
        <div class="relative z-10 text-center py-20 md:py-32 px-4">
          <h1 class="text-4xl sm:text-6xl md:text-7xl font-black text-white drop-shadow-[0_5px_5px_rgba(0,0,0,0.8)] mb-6 tracking-tight leading-tight">
            NOSTALE <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">INFINITY</span>
          </h1>
          <p class="text-base md:text-xl text-gray-200 font-medium max-w-2xl mx-auto opacity-90 drop-shadow-md">สัมผัสประสบการณ์ MMORPG ที่สมดุลที่สุด สังคมดี กิจกรรมแน่น<br class="hidden md:block"> พร้อมทีมงานดูแลตลอด 24 ชั่วโมง</p>
          <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4 px-4">
            <router-link to="/register" class="group/btn relative px-8 py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-full overflow-hidden shadow-lg shadow-green-900/40 transition-all hover:-translate-y-1 active:scale-95"><span class="relative z-10 flex items-center justify-center gap-2">🚀 สมัครเล่นเกม</span></router-link>
            <a href="#" class="group/btn px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-full shadow-lg shadow-blue-900/40 transition-all hover:-translate-y-1 active:scale-95 text-center">⬇️ ดาวน์โหลด</a>
          </div>
        </div>
      </section>

      <section>
        <div class="flex justify-between items-end mb-6 px-2">
          <div class="flex items-center gap-3"><div class="w-1.5 h-8 bg-gradient-to-b from-yellow-400 to-orange-500 rounded-full shadow-lg shadow-yellow-500/50"></div><h2 class="text-2xl md:text-3xl font-bold text-white tracking-wide">ข่าวสารล่าสุด</h2></div>
          <router-link to="/news" class="text-gray-400 text-sm hover:text-white transition-colors flex items-center gap-1">ดูข่าวทั้งหมด <span class="text-xs">➜</span></router-link>
        </div>
        <div class="w-full h-28 sm:h-36 md:h-48 rounded-2xl shadow-2xl mb-8 border border-white/10 bg-cover overflow-hidden relative group" style="background-image: url('/images/bg-12.jpg'); background-position: center 20%;"><div class="absolute inset-0 bg-gradient-to-r from-gray-900/50 via-transparent to-gray-900/50"></div></div>
        <div class="grid gap-4">
          <div v-if="newsList.length === 0" class="text-center py-12 bg-gray-900/30 rounded-2xl border border-white/5 border-dashed"><p class="text-gray-500 animate-pulse">กำลังโหลดข้อมูลข่าวสาร...</p></div>
          <router-link v-for="news in newsList" :key="news.id" :to="{ name: 'news-detail', params: { id: news.id } }" class="group relative bg-gray-900/40 backdrop-blur-md p-5 rounded-2xl border border-white/5 hover:border-blue-500/30 hover:bg-gray-800/60 transition-all duration-300 cursor-pointer flex flex-col sm:flex-row gap-5 overflow-hidden">
            <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="flex-shrink-0 flex flex-row sm:flex-col items-center justify-center sm:justify-start gap-2 sm:gap-1 min-w-[80px]">
              <div class="bg-blue-600/10 text-blue-400 border border-blue-500/20 px-3 py-1.5 rounded-lg text-xs font-bold text-center w-full group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">{{ formatDate(news.created_at) }}</div>
            </div>
            <div class="flex-1 min-w-0 z-10">
              <div class="flex flex-wrap items-center gap-2 mb-2">
                <span :class="getCategoryClass(news.category)" class="text-[10px] uppercase px-2 py-0.5 rounded font-bold border tracking-wider">{{ news.category }}</span>
                <h3 class="font-bold text-lg md:text-xl text-gray-100 group-hover:text-blue-400 transition-colors truncate w-full sm:w-auto">{{ news.title }}</h3>
              </div>
              <p class="text-gray-400 text-sm leading-relaxed line-clamp-2 group-hover:text-gray-300 transition-colors">{{ news.summary || stripHtml(news.content) }}</p>
            </div>
            <div class="hidden sm:flex items-center justify-center text-gray-600 group-hover:text-blue-400 group-hover:translate-x-1 transition-all duration-300"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></div>
          </router-link>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const newsList = ref([]);
const fetchNews = async () => { try { const response = await axios.get('/api/news'); newsList.value = response.data; } catch (error) { console.error("News Error:", error); } };
const formatDate = (dateString) => { if (!dateString) return '-'; const date = new Date(dateString); return date.toLocaleDateString('th-TH', { day: '2-digit', month: 'short', year: '2-digit' }); };
const stripHtml = (html) => { if (!html) return ''; const tmp = document.createElement("DIV"); tmp.innerHTML = html; return tmp.textContent || tmp.innerText || ""; };
const getCategoryClass = (category) => {
  switch (category) {
    case 'Maintenance': return 'bg-red-500/10 text-red-400 border-red-500/20';
    case 'Event': return 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
    case 'Update': return 'bg-green-500/10 text-green-400 border-green-500/20';
    default: return 'bg-gray-500/10 text-gray-400 border-gray-500/20';
  }
};
onMounted(() => { fetchNews(); });
</script>
