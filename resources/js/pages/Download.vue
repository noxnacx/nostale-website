<template>
  <div class="min-h-screen bg-gray-950 font-sans text-gray-100 pb-20 pt-16 md:pt-24 px-4">

    <div class="max-w-5xl mx-auto">
      <div class="text-center mb-8 md:mb-12">
        <h1 class="text-3xl sm:text-4xl md:text-6xl font-black text-white mb-3 md:mb-4 drop-shadow-[0_0_15px_rgba(59,130,246,0.5)] leading-tight">
          DOWNLOAD <span class="text-blue-500">CENTER</span>
        </h1>
        <p class="text-gray-400 text-sm md:text-lg">เลือกดาวน์โหลดตัวเกมในแบบที่คุณต้องการ</p>
      </div>

      <div class="flex overflow-x-auto pb-4 md:pb-0 md:justify-center gap-2 md:gap-4 mb-8 no-scrollbar">
        <button v-for="type in types" :key="type.value"
          @click="currentType = type.value"
          class="whitespace-nowrap px-5 py-2 md:px-6 md:py-2.5 rounded-full font-bold text-xs md:text-sm transition-all duration-300 border shrink-0"
          :class="currentType === type.value
            ? 'bg-blue-600 border-blue-500 text-white shadow-lg shadow-blue-500/30 scale-105'
            : 'bg-gray-900 border-white/10 text-gray-500 hover:text-white'">
          {{ type.label }}
        </button>
      </div>

      <div v-if="filteredDownloads.length > 0" class="grid gap-4 md:gap-6">
        <div v-for="item in filteredDownloads" :key="item.id"
             class="group relative bg-gray-900 rounded-2xl border border-white/10 p-5 md:p-8 flex flex-col md:flex-row items-center gap-4 md:gap-6 hover:border-blue-500/50 transition-all duration-300 overflow-hidden">

          <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>

          <div class="w-14 h-14 md:w-20 md:h-20 rounded-xl md:rounded-2xl bg-gray-800 flex items-center justify-center text-3xl md:text-4xl shadow-inner border border-white/5 shrink-0">
            {{ getIcon(item.type) }}
          </div>

          <div class="flex-1 text-center md:text-left z-10 w-full">
            <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
              <span class="text-[10px] md:text-xs font-bold px-2 py-0.5 rounded bg-white/10 text-gray-300 uppercase tracking-wide border border-white/5">
                {{ item.type }}
              </span>
              <span v-if="item.version" class="text-[10px] md:text-xs text-blue-400 font-mono">v{{ item.version }}</span>
            </div>
            <h3 class="text-xl md:text-2xl font-bold text-white mb-1 group-hover:text-blue-400 transition-colors leading-tight">
              {{ item.name }}
            </h3>
            <p class="text-gray-500 text-xs md:text-sm">
              ขนาดไฟล์: <span class="text-gray-300">{{ item.file_size || 'ไม่ระบุ' }}</span>
            </p>
          </div>

          <a :href="item.link" target="_blank"
             class="relative z-10 w-full md:w-auto px-6 py-3 md:px-8 md:py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all hover:-translate-y-1 hover:shadow-blue-600/30 flex items-center justify-center gap-2 group/btn text-sm md:text-base">
            <span>ดาวน์โหลด</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 group-hover/btn:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
          </a>

        </div>
      </div>

      <div v-else class="text-center py-16 md:py-20 bg-white/5 rounded-2xl border border-white/5 border-dashed">
        <p class="text-gray-500 text-base md:text-lg">ยังไม่มีไฟล์ดาวน์โหลดในหมวดหมู่นี้</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const downloads = ref([]);
const currentType = ref('All');
const loading = ref(true);

const types = [
  { label: 'ทั้งหมด', value: 'All' },
  { label: 'ตัวเกมเต็ม (Client)', value: 'Client' },
  { label: 'แพทช์ (Patch)', value: 'Patch' },
  { label: 'โปรแกรมเสริม (Tool)', value: 'Tool' },
];

const fetchDownloads = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/downloads');
    downloads.value = response.data;
  } catch (error) {
    console.error("Load failed", error);
  } finally {
    loading.value = false;
  }
};

const filteredDownloads = computed(() => {
  if (currentType.value === 'All') return downloads.value;
  return downloads.value.filter(d => d.type === currentType.value);
});

const getIcon = (type) => {
  if (type === 'Client') return '🎮';
  if (type === 'Patch') return '🔧';
  return '📁';
};

onMounted(fetchDownloads);
</script>

<style scoped>
/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>
