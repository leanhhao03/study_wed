<template>
  <div id="test">
    <div class="header">
      <div class="logo">logo</div>
      <div class="title">Thinksync</div>
    </div>

    <div class="test-container">
      <!-- Thanh tìm kiếm -->
      <div class="search-container">
        <div class="search-bar">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Nhập từ khóa..." 
            @keyup.enter="fetchExams"
          />
          <FontAwesomeIcon :icon="['fas', 'magnifying-glass']" class="search-icon" />
          <button class="btn-search" @click="fetchExams">Tìm kiếm</button>
        </div>

        <!-- Danh sách môn học -->
        <div class="search-trending">
          <div class="trending-row">
            <span class="trending-title">Môn học:</span>
            <div 
              class="trending-item" 
              v-for="subject in subjects" 
              :key="subject"
              :class="{ active: selectedSubjects.includes(subject) }"
              @click="toggleSubject(subject)"
            >
              {{ subject }}
            </div>
          </div>
        </div>
      </div>

      <!-- Danh sách bài thi -->
      <div class="test-list">
        <div v-if="loading">Đang tải...</div>
        <div v-else-if="exams.length === 0">Không có bài thi nào</div>
        <div class="test-card" v-for="exam in exams" :key="exam.id">
          <router-link :to="'/exam/' + exam.id" class="test-card-link">
            <div class="test-title">{{ exam.title }}</div>
            <div class="test-info">
              <span>Thời gian: {{ exam.duration }} phút</span>
              <span>Môn học: {{ exam.subject }}</span>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';

library.add(faMagnifyingGlass);

const searchQuery = ref('');
const exams = ref([]);
const loading = ref(false);
const subjects = ref([]);
const selectedSubjects = ref([]); 

const API_URL = 'http://127.0.0.1:8000/api';

// Lấy danh sách môn học từ API
const fetchSubjects = async () => {
  try {
    const response = await axios.get(`${API_URL}/exams`);
    const examList = response.data;

    // Lọc danh sách môn học duy nhất
    subjects.value = [...new Set(examList.map(exam => exam.subject))];
  } catch (error) {
    console.error('Lỗi khi tải danh sách môn học:', error);
    subjects.value = [];
  }
};

// Chọn/bỏ chọn môn học
const toggleSubject = (subject) => {
  if (selectedSubjects.value === subject) {
    selectedSubjects.value = ""; 
  } else {
    selectedSubjects.value = subject;
  }
  fetchExams();
};

// Gọi API lấy danh sách bài thi với filter
const fetchExams = async () => {
  loading.value = true;
  try {
    const params = {};
    
    if (selectedSubjects.value) {
      params.subject = selectedSubjects.value; // Gửi 1 môn duy nhất
    }
    if (searchQuery.value){
      params.search = searchQuery.value;
    };

    const response = await axios.get(`${API_URL}/exams`, { params });
    exams.value = response.data;
  } catch (error) {
    console.error("Lỗi khi tải danh sách bài thi:", error);
  } finally {
    loading.value = false;
  }
};



onMounted(() => {
  fetchExams();
  fetchSubjects();
});
</script>

<style scoped>
.trending-item {
  padding: 8px 15px;
  margin: 5px;
  border-radius: 15px;
  cursor: pointer;
  background: #e0e0e0;
  display: inline-block;
}

.trending-item.active {
  background: #007bff;
  color: white;
}
</style>
