<template>
  <div class="min-h-screen bg-gray-950 font-sans text-gray-100 pb-20">
    <div class="relative h-[300px] md:h-[400px] overflow-hidden">
      <div class="absolute inset-0 bg-cover" style="background-image: url('/images/bg-6.jpg'); background-position: center 32%;"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/80 to-gray-950"></div>
      <div class="relative z-10 container mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-4 drop-shadow-2xl tracking-tight">NEWS & UPDATES</h1>
        <p class="text-blue-200 text-lg md:text-xl font-medium max-w-2xl">ติดตามข่าวสาร กิจกรรม และการอัปเดตล่าสุดของ Nostale Infinity</p>
      </div>
    </div>

    <div class="container mx-auto px-4 -mt-10 relative z-20">
      <div class="flex flex-wrap justify-center gap-2 mb-10">
        <button v-for="cat in categories" :key="cat.value" @click="selectCategory(cat.value)" class="px-6 py-2.5 rounded-full font-bold text-sm transition-all duration-300 border backdrop-blur-md" :class="currentCategory === cat.value ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-600/30 scale-105' : 'bg-gray-900/60 text-gray-400 border-white/10 hover:bg-gray-800 hover:text-white'">{{ cat.label }}</button>
      </div>

      <div v-if="loading" class="flex flex-col items-center justify-center py-20 text-gray-500"><div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div><p>กำลังโหลดข้อมูล...</p></div>
      <div v-else-if="newsList.length === 0" class="text-center py-20 border border-white/5 rounded-2xl bg-white/5"><div class="text-6xl mb-4">📭</div><h3 class="text-xl font-bold text-white mb-2">ไม่พบข่าวสารในหมวดหมู่นี้</h3><p class="text-gray-400">ลองเปลี่ยนหมวดหมู่ หรือกลับมาดูใหม่ภายหลังนะครับ</p></div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <router-link v-for="news in newsList" :key="news.id" :to="{ name: 'news-detail', params: { id: news.id } }" class="group bg-gray-900 rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:shadow-2xl hover:shadow-blue-900/20 transition-all duration-300 flex flex-col h-full">
          <div class="h-48 overflow-hidden relative">
             <div class="absolute inset-0 bg-cover transition-transform duration-700 group-hover:scale-110" :style="{ backgroundImage: `url(${news.image_url || '/images/bg-1.png'})`, backgroundPosition: `${news.image_position_x || 50}% ${news.image_position_y || 50}%` }"></div>
             <div class="absolute top-3 left-3"><span :class="getCategoryBadge(news.category)" class="px-3 py-1 text-xs font-bold rounded-md uppercase tracking-wide shadow-sm">{{ news.category }}</span></div>
          </div>
          <div class="p-5 flex-1 flex flex-col">
            <div class="mb-3 text-xs text-gray-500 flex items-center gap-2 font-mono"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>{{ formatDate(news.created_at) }}</div>
            <h3 class="text-xl font-bold text-white mb-3 leading-snug group-hover:text-blue-400 transition-colors line-clamp-2">{{ news.title }}</h3>
            <p class="text-gray-400 text-sm line-clamp-3 mb-4 flex-1">{{ news.summary || stripHtml(news.content) }}</p>
            <div class="flex items-center text-blue-500 text-sm font-bold mt-auto group-hover:translate-x-1 transition-transform">อ่านเพิ่มเติม <span class="ml-1">→</span></div>
          </div>
        </router-link>
      </div>

      <div v-if="totalPages > 1" class="flex justify-center mt-12 gap-2">
        <button v-for="page in totalPages" :key="page" @click="changePage(page)" class="w-10 h-10 rounded-lg font-bold border transition-colors" :class="currentPage === page ? 'bg-blue-600 border-blue-600 text-white' : 'bg-gray-900 border-gray-700 text-gray-400 hover:bg-gray-800 hover:text-white'">{{ page }}</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const newsList = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const currentCategory = ref('All');

const categories = [
  { label: 'ทั้งหมด', value: 'All' },
  { label: 'ประกาศทั่วไป', value: 'General' },
  { label: 'อัปเดตระบบ', value: 'Update' },
  { label: 'กิจกรรม', value: 'Event' },
  { label: 'ปิดปรับปรุง', value: 'Maintenance' },
];

const fetchNews = async (page = 1) => {
  loading.value = true;
  try {
    const response = await axios.get('/api/news/archive', { params: { page: page, category: currentCategory.value } });
    newsList.value = response.data.data || [];
    currentPage.value = response.data.current_page || 1;
    totalPages.value = response.data.last_page || 1;
  } catch (error) { newsList.value = []; } finally { loading.value = false; }
};

const selectCategory = (cat) => { currentCategory.value = cat; currentPage.value = 1; fetchNews(1); };
const changePage = (page) => { if (page === currentPage.value) return; fetchNews(page); window.scrollTo({ top: 0, behavior: 'smooth' }); };
const formatDate = (date) => new Date(date).toLocaleDateString('th-TH', { day: '2-digit', month: 'short', year: 'numeric' });
const stripHtml = (html) => { if (!html) return ''; const tmp = document.createElement("DIV"); tmp.innerHTML = html; return tmp.textContent || tmp.innerText || ""; };
const getCategoryBadge = (cat) => {
  if(cat === 'Maintenance') return 'bg-red-500 text-white';
  if(cat === 'Event') return 'bg-yellow-500 text-black';
  if(cat === 'Update') return 'bg-green-500 text-white';
  return 'bg-blue-500 text-white';
};

onMounted(() => fetchNews());
</script>
