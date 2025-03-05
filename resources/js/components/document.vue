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
              <!-- Nếu có PDF URL thì hiển thị bằng iframe -->
              <iframe 
                v-if="pdfUrl" 
                :src="`${pdfUrl}#toolbar=0`"
                style="background-color: #f4f4f9;" 
                frameborder="0" 
                scrolling="no"
                class="pdf-viewer"
                >
              </iframe>
              <!-- Hiển thị thông báo nếu không có PDF -->
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
    import { faBars } from '@fortawesome/free-solid-svg-icons';

library.add(faBars);

    const menuVisible = ref(false);
    const menuItems = ["Thi thử", "Ghi nhớ", "Giải trí", "Room", "Đặt lịch"];
    const subjects = ref([]);
    const previews = ref([]);  
    const activeDocumentId = ref(null);
    const pdfUrl = ref(null);
    const documentTitle = ref('Tiêu đề tài liệu');
    const selectedSubject = ref(null);

// Gọi API khi component được mount
onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/documents/subjects');
    subjects.value = response.data;
  } catch (error) {
    console.error('Lỗi khi lấy subjects:', error);
  }

  await getPreviews();
});


// Chọn một môn học
const selectSubject = async (subject) => {
  selectedSubject.value = subject;
  await getPreviews();
};

    //api lấy previews
    const getPreviews = async () => {
  try {
    // Nếu đã chọn subject thì gọi API lấy previews theo subject
    const url = selectedSubject.value 
      ? `http://127.0.0.1:8000/api/documents/preview?subject=${selectedSubject.value}` 
      : 'http://127.0.0.1:8000/api/documents/preview';
    
    const response = await axios.get(url);
    previews.value = response.data;
  } catch (error) {
    console.error('Lỗi khi lấy previews:', error);
  }
};
    // Xử lý URL của hình ảnh
    const getPreviewUrl = (path) => {
      return `http://127.0.0.1:8000/storage/${path}`;
    };

    const getFullPdf = async (id) => {
      try {
            // Lưu lại ID của tài liệu đang chọn
          activeDocumentId.value = id;

           // Gọi API lấy thông tin file (bao gồm title và file URL)
            const response = await axios.get(`http://127.0.0.1:8000/api/documents/full/${id}`);
            const data = response.data;

            // Cập nhật pdfUrl để hiển thị PDF
            pdfUrl.value = data.file_url;

          // Cập nhật documentTitle để hiển thị tiêu đề
            documentTitle.value = data.title;
      } catch (error) {
          console.error('Lỗi khi lấy PDF:', error);
  }
};

</script>