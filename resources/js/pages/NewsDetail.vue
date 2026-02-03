<template>
  <div class="min-h-screen bg-gray-950 py-10 px-4 font-sans text-gray-100">

    <div class="max-w-4xl mx-auto mb-6">
      <router-link to="/" class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 transition-colors font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        กลับหน้าหลัก
      </router-link>
    </div>

    <div v-if="news" class="max-w-4xl mx-auto bg-gray-900 rounded-3xl overflow-hidden shadow-2xl border border-white/10">

      <div v-if="news.image_url"
           class="h-64 md:h-96 w-full bg-cover bg-no-repeat relative transition-all duration-500"
           :style="{
             backgroundImage: `url(${news.image_url})`,
             backgroundPosition: `center ${news.image_position || 50}%`
           }">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
      </div>

      <div class="p-8 md:p-12 relative">

        <div class="flex items-center gap-3 mb-6">
          <span :class="getCategoryBadge(news.category)"
                class="px-3 py-1 text-xs font-bold rounded uppercase tracking-wider border">
            {{ news.category }}
          </span>
          <span class="text-gray-400 text-sm font-medium flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ formatDate(news.created_at) }}
          </span>
        </div>

        <h1 class="text-3xl md:text-5xl font-black text-white mb-8 leading-tight drop-shadow-lg">
          {{ news.title }}
        </h1>

        <div class="h-px w-full bg-gradient-to-r from-transparent via-white/20 to-transparent mb-10"></div>

        <div class="prose prose-invert prose-lg max-w-none text-gray-300 leading-loose"
             v-html="news.content">
        </div>

      </div>

    </div>

    <div v-else class="min-h-[50vh] flex flex-col items-center justify-center text-gray-500">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
      <p>กำลังโหลดเนื้อหา...</p>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const news = ref(null);

const fetchNewsDetail = async () => {
  try {
    const response = await axios.get(`/api/news/${route.params.id}`);
    news.value = response.data;
  } catch (error) {
    console.error("News not found", error);
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('th-TH', {
    day: '2-digit', month: 'long', year: 'numeric'
  });
};

const getCategoryBadge = (cat) => {
  if(cat === 'Maintenance') return 'bg-red-500/10 text-red-400 border-red-500/20';
  if(cat === 'Event') return 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
  if(cat === 'Update') return 'bg-green-500/10 text-green-400 border-green-500/20';
  return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
};

onMounted(() => {
  fetchNewsDetail();
});
</script>

<style>
/* [แก้ตรงนี้] CSS จัดการรูปในข่าว (หน้าจริง) */
.prose img {
  border-radius: 1rem;
  margin-top: 2.5rem;
  margin-bottom: 2.5rem;
  max-width: 100%; /* ห้ามล้นจอ */
  width: auto;     /* รูปเล็กให้เล็กเท่าเดิม (ไม่ยืดเต็มจอ) */
  height: auto;
  display: block;  /* ขึ้นบรรทัดใหม่ */
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.prose p {
  margin-bottom: 1.5em;
  line-height: 1.8;
}
.prose a {
  color: #60a5fa;
  text-decoration: none;
  border-bottom: 1px dashed #60a5fa;
}
.prose a:hover {
  color: #93c5fd;
  border-bottom-style: solid;
}
.prose h2, .prose h3 {
  color: #fff;
  font-weight: 800;
  margin-top: 2em;
  margin-bottom: 1em;
}
.prose ul {
  list-style-type: disc;
  padding-left: 1.5em;
  margin-bottom: 1.5em;
}
.prose li {
  margin-bottom: 0.5em;
}
</style>
