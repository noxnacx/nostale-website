<template>
  <div class="flex min-h-screen bg-gray-100 text-gray-900 font-sans">
    <aside class="w-64 bg-gray-900 text-white p-6 space-y-6 hidden md:block shadow-xl sticky top-0 h-screen z-20">
      <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">GM PANEL</h2>
      <div class="space-y-1">
        <p class="text-xs font-bold text-gray-500 uppercase px-4 mb-2">News System</p>
        <button @click="switchTab('create-news')" :class="tabClass('create-news')">✍️ เขียนข่าวใหม่</button>
        <button @click="switchTab('manage-news')" :class="tabClass('manage-news')">📋 จัดการข่าวสาร</button>
      </div>
      <div class="space-y-1 border-t border-gray-800 pt-4">
        <p class="text-xs font-bold text-gray-500 uppercase px-4 mb-2">Game Content</p>
        <button @click="switchTab('downloads')" :class="tabClass('downloads')">📥 จัดการดาวน์โหลด</button>
      </div>
      <div class="space-y-1 border-t border-gray-800 pt-4">
        <p class="text-xs font-bold text-gray-500 uppercase px-4 mb-2">User System</p>
        <button @click="switchTab('users')" :class="tabClass('users')">👥 จัดการผู้เล่น</button>
        <button @click="switchTab('topup')" :class="tabClass('topup')">💰 ประวัติเติมเงิน</button>
      </div>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto h-screen relative">
      <div v-if="currentTab === 'create-news'" class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 flex items-center gap-2"><span class="text-blue-600">{{ isEditing ? '✏️ แก้ไขข่าว' : '✨ เขียนประกาศใหม่' }}</span></h1>
        <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
          <form @submit.prevent="showPreview = true" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="md:col-span-2 space-y-1">
                <label class="font-bold text-gray-700">หัวข้อข่าว <span class="text-red-500">*</span></label>
                <input v-model="newsForm.title" type="text" required class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div class="space-y-1">
                <label class="font-bold text-gray-700">หมวดหมู่ <span class="text-red-500">*</span></label>
                <select v-model="newsForm.category" class="w-full p-3 border border-gray-300 rounded-lg bg-white">
                  <option value="General">📢 ทั่วไป</option>
                  <option value="Update">🔥 อัปเดต</option>
                  <option value="Event">🎉 กิจกรรม</option>
                  <option value="Maintenance">🔧 ปิดปรับปรุง</option>
                </select>
              </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-4">
              <div class="space-y-1">
                <label class="font-bold text-gray-700">ลิงก์รูปปก (URL)</label>
                <input v-model="newsForm.image_url" type="text" placeholder="https://..." class="w-full p-3 border border-gray-300 rounded-lg outline-none font-mono text-sm text-blue-600">
              </div>
              <div v-if="newsForm.image_url" class="space-y-4">
                 <div class="flex justify-between text-sm text-gray-600 font-bold">
                   <span>ตัวอย่างรูปปก (Preview)</span>
                   <span>Pos: {{ newsForm.image_position_x }}% / {{ newsForm.image_position_y }}%</span>
                 </div>
                 <div class="h-48 w-full rounded-lg bg-gray-200 overflow-hidden border relative shadow-inner">
                    <img :src="newsForm.image_url" class="w-full h-full object-cover transition-all duration-100"
                         :style="{ objectPosition: `${newsForm.image_position_x}% ${newsForm.image_position_y}%` }">
                 </div>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                   <div class="flex items-center gap-3"><span class="text-xs font-bold text-gray-500 w-8">ซ้าย</span><input type="range" v-model="newsForm.image_position_x" min="0" max="100" class="w-full h-2 bg-blue-200 rounded-lg cursor-pointer accent-blue-600"><span class="text-xs font-bold text-gray-500 w-8 text-right">ขวา</span></div>
                   <div class="flex items-center gap-3"><span class="text-xs font-bold text-gray-500 w-8">บน</span><input type="range" v-model="newsForm.image_position_y" min="0" max="100" class="w-full h-2 bg-green-200 rounded-lg cursor-pointer accent-green-600"><span class="text-xs font-bold text-gray-500 w-8 text-right">ล่าง</span></div>
                 </div>
              </div>
            </div>

            <div class="space-y-1">
              <label class="font-bold text-gray-700">คำโปรย</label>
              <input v-model="newsForm.summary" type="text" required class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="space-y-1">
              <label class="font-bold text-gray-700">เนื้อหาข่าว</label>
              <div class="h-96 border border-gray-300 rounded-lg overflow-hidden bg-white flex flex-col"><QuillEditor v-model:content="newsForm.content" contentType="html" theme="snow" toolbar="full" /></div>
            </div>
            <div class="flex gap-4 pt-4 border-t">
              <button type="submit" class="flex-1 py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2"><span>👁️</span> ดูตัวอย่าง</button>
              <button v-if="isEditing" type="button" @click="cancelEdit" class="px-6 py-3 bg-red-100 hover:bg-red-200 text-red-600 font-bold rounded-xl transition-all">ยกเลิก</button>
            </div>
          </form>
        </div>
      </div>

      <div v-if="currentTab === 'manage-news'" class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-800">📋 รายการข่าวทั้งหมด</h1>
          <button @click="switchTab('create-news')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-bold shadow transition">+ เขียนข่าวใหม่</button>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
          <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-bold"><tr><th class="p-4">ID</th><th class="p-4">รูปปก</th><th class="p-4">หัวข้อข่าว</th><th class="p-4">หมวดหมู่</th><th class="p-4">จัดการ</th></tr></thead>
            <tbody class="divide-y divide-gray-100 text-sm">
              <tr v-for="news in newsList" :key="news.id" class="hover:bg-gray-50 transition">
                <td class="p-4 text-gray-400 font-mono">#{{ news.id }}</td>
                <td class="p-4"><div class="w-16 h-10 rounded bg-gray-200 overflow-hidden border"><img :src="news.image_url || '/images/bg-1.png'" class="w-full h-full object-cover" :style="{ objectPosition: `${news.image_position_x || 50}% ${news.image_position_y || 50}%` }"></div></td>
                <td class="p-4 font-bold text-gray-700">{{ news.title }}</td>
                <td class="p-4"><span :class="getCategoryBadge(news.category)" class="px-2 py-1 rounded text-xs font-bold uppercase">{{ news.category }}</span></td>
                <td class="p-4 space-x-2"><button @click="editNews(news)" class="text-yellow-600 font-bold hover:underline">แก้ไข</button><button @click="deleteNews(news.id)" class="text-red-600 font-bold hover:underline">ลบ</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-if="currentTab === 'downloads'" class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">📥 จัดการลิงก์ดาวน์โหลด</h1>
        <div class="bg-white p-6 rounded-2xl shadow-sm border mb-8 border-gray-200">
          <h3 class="font-bold text-lg mb-4 text-gray-700">เพิ่มไฟล์ใหม่</h3>
          <form @submit.prevent="addDownload" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input v-model="dlForm.name" type="text" placeholder="ชื่อไฟล์ (เช่น Full Client v.1.0)" required class="p-3 border rounded-lg md:col-span-2 outline-none focus:ring-2 focus:ring-blue-500">
            <input v-model="dlForm.link" type="url" placeholder="ลิงก์ดาวน์โหลด (Google Drive, Mega)" required class="p-3 border rounded-lg md:col-span-2 outline-none focus:ring-2 focus:ring-blue-500">
            <select v-model="dlForm.type" class="p-3 border rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-500">
              <option value="Client">Client (ตัวเกมเต็ม)</option>
              <option value="Patch">Patch (แพทช์แก้)</option>
              <option value="Tool">Tool (โปรแกรมช่วย)</option>
            </select>
            <input v-model="dlForm.version" type="text" placeholder="เวอร์ชั่น (เช่น 1.0)" class="p-3 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
            <input v-model="dlForm.file_size" type="text" placeholder="ขนาดไฟล์ (เช่น 4.5 GB)" class="p-3 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
            <button class="bg-green-600 text-white font-bold py-3 rounded-lg md:col-span-2 hover:bg-green-500 transition shadow-lg">+ เพิ่มลิงก์ดาวน์โหลด</button>
          </form>
        </div>
        <div class="space-y-3">
          <div v-for="dl in downloads" :key="dl.id" class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex justify-between items-center group hover:border-blue-300 transition">
            <div>
              <div class="flex items-center gap-3">
                <span class="px-2 py-0.5 rounded text-xs font-bold uppercase" :class="dl.type === 'Client' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600'">{{ dl.type }}</span>
                <h4 class="font-bold text-gray-800">{{ dl.name }}</h4><span v-if="dl.version" class="text-xs text-gray-400 bg-gray-100 px-2 rounded">v{{ dl.version }}</span>
              </div>
              <a :href="dl.link" target="_blank" class="text-xs text-blue-500 hover:underline truncate block max-w-md mt-1">{{ dl.link }}</a>
            </div>
            <button @click="deleteDownload(dl.id)" class="text-gray-400 hover:text-red-500 p-2 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
          </div>
          <div v-if="downloads.length === 0" class="text-center py-10 text-gray-400">ยังไม่มีลิงก์ดาวน์โหลด</div>
        </div>
      </div>

      <div v-if="showPreview" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fade-in">
        <div class="bg-gray-900 w-full max-w-4xl h-[90vh] rounded-2xl overflow-hidden flex flex-col shadow-2xl border border-white/10">
          <div class="bg-gray-800 p-4 flex justify-between items-center border-b border-gray-700">
            <h3 class="text-white font-bold text-lg">👁️ ตัวอย่างการแสดงผล</h3>
            <button @click="showPreview = false" class="text-gray-400 hover:text-white text-sm">ปิด [ESC]</button>
          </div>
          <div class="flex-1 overflow-y-auto bg-gray-950 scrollbar-hide">
            <div v-if="newsForm.image_url" class="h-64 w-full bg-cover relative transition-all duration-300"
                 :style="{ backgroundImage: `url(${newsForm.image_url})`, backgroundPosition: `${newsForm.image_position_x}% ${newsForm.image_position_y}%` }">
              <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
            </div>
            <div class="p-8 md:p-12 max-w-3xl mx-auto">
              <div class="flex items-center gap-3 mb-4"><span class="px-3 py-1 bg-yellow-500/20 text-yellow-500 text-xs font-bold rounded uppercase border border-yellow-500/20">{{ newsForm.category }}</span></div>
              <h1 class="text-3xl md:text-5xl font-black text-white mb-6">{{ newsForm.title }}</h1>
              <div class="prose prose-invert prose-lg max-w-none text-gray-300" v-html="newsForm.content"></div>
            </div>
          </div>
          <div class="bg-gray-800 p-4 border-t border-gray-700 flex justify-end gap-3">
            <button @click="showPreview = false" class="px-6 py-3 bg-gray-700 text-white rounded-lg font-bold hover:bg-gray-600 transition">กลับไปแก้ไข</button>
            <button @click="confirmSubmit" class="px-8 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-500 shadow-lg">{{ isEditing ? 'ยืนยันการแก้ไข' : 'ยืนยันการลงประกาศ' }}</button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const currentTab = ref('create-news');
const newsList = ref([]);
const isEditing = ref(false);
const editingId = ref(null);
const showPreview = ref(false);
const newsForm = ref({ title: '', category: 'General', image_url: '', image_position_x: 50, image_position_y: 50, summary: '', content: '' });
const downloads = ref([]);
const dlForm = ref({ name: '', link: '', type: 'Client', version: '', file_size: '' });

const switchTab = (tab) => {
  currentTab.value = tab;
  if (tab === 'manage-news') fetchAllNews();
  if (tab === 'create-news' && !isEditing.value) resetNewsForm();
  if (tab === 'downloads') fetchDownloads();
};

const fetchAllNews = async () => { try { const res = await axios.get('/api/news'); newsList.value = res.data; } catch (e) { console.error(e); } };
const confirmSubmit = async () => {
  try {
    if (isEditing.value) { await axios.put(`/api/admin/news/${editingId.value}`, newsForm.value); alert('✅ แก้ไขสำเร็จ!'); }
    else { await axios.post('/api/admin/news', newsForm.value); alert('✅ ลงประกาศสำเร็จ!'); }
    showPreview.value = false; resetNewsForm(); switchTab('manage-news');
  } catch (e) { alert('❌ Error: ' + (e.response?.data?.message || 'Check Data')); }
};
const editNews = (news) => {
  isEditing.value = true; editingId.value = news.id;
  newsForm.value = { ...news, image_position_x: news.image_position_x ?? 50, image_position_y: news.image_position_y ?? (news.image_position ?? 50) };
  switchTab('create-news');
};
const deleteNews = async (id) => { if (!confirm('ยืนยันลบ?')) return; await axios.delete(`/api/admin/news/${id}`); fetchAllNews(); };
const cancelEdit = () => { resetNewsForm(); switchTab('manage-news'); };
const resetNewsForm = () => { isEditing.value = false; editingId.value = null; newsForm.value = { title: '', category: 'General', image_url: '', image_position_x: 50, image_position_y: 50, summary: '', content: '' }; };

const fetchDownloads = async () => { try { const res = await axios.get('/api/downloads'); downloads.value = res.data; } catch (e) {} };
const addDownload = async () => { try { await axios.post('/api/admin/downloads', dlForm.value); alert('✅ เพิ่มสำเร็จ!'); dlForm.value = { name: '', link: '', type: 'Client', version: '', file_size: '' }; fetchDownloads(); } catch (e) { alert('Error'); } };
const deleteDownload = async (id) => { if(!confirm('ลบ?')) return; await axios.delete(`/api/admin/downloads/${id}`); fetchDownloads(); };

const tabClass = (tab) => [ 'w-full text-left px-4 py-3 rounded-lg transition', currentTab.value === tab ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-gray-800 text-gray-400' ];
const getCategoryBadge = (cat) => {
  if(cat === 'Maintenance') return 'bg-red-100 text-red-600';
  if(cat === 'Event') return 'bg-yellow-100 text-yellow-600';
  if(cat === 'Update') return 'bg-green-100 text-green-600';
  return 'bg-blue-100 text-blue-600';
};
onMounted(() => fetchAllNews());
</script>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
.ql-toolbar { border-color: #e5e7eb !important; background-color: #f9fafb; border-radius: 8px 8px 0 0; }
.ql-container { border-color: #e5e7eb !important; font-family: 'Sarabun', sans-serif; font-size: 16px; border-radius: 0 0 8px 8px; flex: 1; overflow-y: auto; }
.ql-editor { min-height: 100%; }
.animate-fade-in { animation: fade-in 0.2s ease-out; }
@keyframes fade-in { from { opacity: 0; transform: scale(0.98); } to { opacity: 1; transform: scale(1); } }
.prose img { max-width: 100%; width: auto; height: auto; display: block; margin: 1.5rem auto; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
</style>
