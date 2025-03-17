<template>
  <div id="document">
    <header class="header">
      <div class="document-menu">
        <button @click="toggleMenu" class="logo-button">
          <font-awesome-icon :icon="['fas', 'bars']" />
        </button>
        <span>Ôn tập</span>
        <transition name="fade">
          <div v-if="menuVisible" class="dropdown-menu" @click.stop>
            <div v-for="item in menuItems" :key="item" class="menu-item">
              {{ item }}
            </div>
          </div>
        </transition>
      </div>
      <div class="title">Thinksync</div>
      <div class="noti-login"></div>
    </header>

    <div class="document-title">{{ documentTitle }}</div>

    <div class="document-container">
      <div class="left-container">
        <div class="left-menu">
          <div v-for="subject in subjects" 
          :key="subject" 
          class="menu-item"
          :class="{ active: selectedSubject === subject}"
          @click="selectSubject(subject)"
          >
            {{ subject }}
          </div>
        </div>
      </div>

      <div class="document-area">
        <div class="document-content"></div>
          <iframe 
            v-if="pdfUrl" 
            :src="`${pdfUrl}#toolbar=0`"
            style="background-color: #f4f4f9;" 
            frameborder="0" 
            scrolling="no"
            class="pdf-viewer"
            >
          </iframe>
          <p v-else>Chọn một tài liệu từ menu bên phải để xem nội dung.</p>
      </div>

      <div class="right-container">
        <div class="right-menu">
          <div v-for="preview in previews" 
          :key="preview.Dcm_id" 
          class="menu-item"
          @click="getFullPdf(preview.Dcm_id)"
          >
            <img :src="getPreviewUrl(preview.dcm_preview_path)" 
            alt="Preview" 
            class="preview-image" 
            />
            <button class="favorite-button" @click.stop="toggleFavorite(preview.Dcm_id)">
                <FontAwesomeIcon :icon="[isFavorited(preview.Dcm_id) ? 'fas' : 'far', 'heart']" 
                 :class="{ 'favorited': isFavorited(preview.Dcm_id) }" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBars, faHeart } from '@fortawesome/free-solid-svg-icons';
import { faHeart as faHeartRegular } from '@fortawesome/free-regular-svg-icons';

library.add(faBars, faHeart, faHeartRegular);

const menuVisible = ref(false);
const menuItems = ["Thi thử", "Ghi nhớ", "Giải trí", "Room", "Đặt lịch"];
const subjects = ref([]);
const previews = ref([]);  
const activeDocumentId = ref(null);
const pdfUrl = ref(null);
const documentTitle = ref('Tiêu đề tài liệu');
const selectedSubject = ref(null);
const favorites = ref(new Set());
const isFavorited = (id) => favorites.value.has(id);

onMounted(async () => {
try {
const response = await axios.get('http://127.0.0.1:8000/api/documents/subjects');
subjects.value = response.data;
} catch (error) {
console.error('Lỗi khi lấy subjects:', error);
}
await getPreviews();
await fetchFavorites();
});

const fetchFavorites = async () => {
try {
const res = await axios.get("http://127.0.0.1:8000/api/favorites");
favorites.value = new Set(res.data.map(doc => doc.Dcm_id));
} catch (error) {
console.error("Lỗi khi lấy danh sách yêu thích:", error);
}
};

const toggleFavorite = async (id) => {
if (isFavorited(id)) {
try {
  await axios.delete("http://127.0.0.1:8000/api/favorites", { data: { document_id: id } });
  favorites.value.delete(id);
} catch (error) {
  console.error("Lỗi khi bỏ yêu thích:", error);
}
} else {
try {
  await axios.post("http://127.0.0.1:8000/api/favorites", { document_id: id });
  favorites.value.add(id);
} catch (error) {
  console.error("Lỗi khi thêm vào danh sách yêu thích:", error);
}
}
};

const selectSubject = async (subject) => {
selectedSubject.value = subject;
await getPreviews();
};

const getPreviews = async () => {
try {
const url = selectedSubject.value 
  ? `http://127.0.0.1:8000/api/documents/preview?subject=${selectedSubject.value}` 
  : 'http://127.0.0.1:8000/api/documents/preview';

const response = await axios.get(url);
previews.value = response.data;
} catch (error) {
console.error('Lỗi khi lấy previews:', error);
}
};

const getPreviewUrl = (path) => {
return `http://127.0.0.1:8000/storage/${path}`;
};

const getFullPdf = async (id) => {
try {
activeDocumentId.value = id;
const response = await axios.get(`http://127.0.0.1:8000/api/documents/full/${id}`);
const data = response.data;
pdfUrl.value = data.file_url;
documentTitle.value = data.title;
} catch (error) {
console.error('Lỗi khi lấy PDF:', error);
}
};
</script>
