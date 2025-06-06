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

      <div class="test-list">
        <div v-if="loading">Đang tải...</div>
        <div v-else-if="exams.length === 0">Không có bài thi nào</div>
        <div class="test-card" 
            v-for="exam in exams" 
            :key="exam.id" 
            @click="openConfirmPopup(exam)">
          <div class="test-card-link">
            <div class="test-title">{{ exam.title }}</div>
            <div class="test-info">
              <span>Thời gian: {{ exam.duration }} phút</span>
              <span>Môn học: {{ exam.subject }}</span>
            </div>
          </div>
        </div>    
      </div>
    </div>

    <!-- Popup xác nhận làm bài thi -->
    <div v-if="selectedExam" class="popup-overlay">
      <div class="popup">
        <p>Bạn sẽ bắt đầu vào làm bài chứ?</p>
        <div class="popup-buttons">
          <button class="btn-confirm" @click="confirmStartExam">Vào làm</button>
          <button class="btn-cancel" @click="selectedExam = null">Không</button>
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
const selectedExam = ref(null);

const API_URL = 'http://127.0.0.1:8000/api';

// Lấy danh sách môn học từ API
const fetchSubjects = async () => {
  try {
    const response = await axios.get(`${API_URL}/exams`);
    const examList = response.data;
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

const openConfirmPopup = (exam) => {
  selectedExam.value = exam;
};

const confirmStartExam = async () => {
  if (!selectedExam.value) return;
  try {
    // Lấy user từ session Laravel Breeze
    const { data: user } = await axios.get("/api/auth/user");
    if (!user || !user.id) {
      alert("Bạn chưa đăng nhập!");
      return;
    }

    // Gọi API để bắt đầu bài thi
    const response = await axios.post(`${API_URL}/exams/start/${selectedExam.value.id}`);

    // Kiểm tra nếu đã bắt đầu trước đó
    if (response.data.message.includes("trước đó")) {
      if (!confirm("Bạn đã bắt đầu bài thi trước đó. Tiếp tục làm bài?")) return;
    }

    // Chuyển hướng đến trang làm bài
    window.location.href = `/test-doc?id=${selectedExam.value.id}`;
  } catch (error) {
    console.error("Lỗi khi bắt đầu bài thi:", error.response?.data || error);
  } finally {
    selectedExam.value = null;
  }
};
// Gọi API lấy danh sách bài thi với filter
const fetchExams = async () => {
  loading.value = true;
  try {
    const params = {};
    
    if (selectedSubjects.value) {
      params.subject = selectedSubjects.value; 
    }
    if (searchQuery.value){
      params.search = searchQuery.value;
    }

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

