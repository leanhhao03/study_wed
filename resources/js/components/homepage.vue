<template>
  <div id="app">
    <!-- Header -->
    <header class="header" :class="{ 'login-header': showLogin }">
      <div class="logo">Logo</div>
      <div class="title">Thinksync</div>
      <div class="home-return" v-if="showLogin">
        <a href="#" @click="showLogin = false">
          <FontAwesomeIcon :icon="['fas', 'home']" class="icon-home" /> Trang chủ
        </a>
      </div>
      <div class="noti-login" v-else>
        <div class="noti" v-if="isLoggedIn">
          <button>
            <FontAwesomeIcon :icon="['fas', 'bell']" class="icon-bell" />
          </button>
        </div>
        <div class="user-menu" v-if="isLoggedIn" @click="toggleDropdown">
          <span class="user-name">{{ user.name }}</span>
          <div v-if="dropdownVisible" class="dropdown">
            <a href="#" @click.prevent="redirectToProfile">Thông tin cá nhân</a>
            <a href="#" @click.prevent="logout">Đăng xuất</a>
          </div>
        </div>
        <a v-else href="#" @click.prevent="showLogin = true" class="login-link">
          <FontAwesomeIcon :icon="['fas', 'user']" class="icon-user" /> Đăng nhập
        </a>
      </div>
    </header>

    <!-- Main content -->
    <main v-if="!showLogin" class="main">
      <div class="top-grid">
        <div class="reminder-card">
          <p>Bạn có lịch học nè!</p>
          <table v-if="appointments.length > 0" class="appointment-table">
            <thead>
              <tr>
                <th>Thời gian</th>
                <th>Nội dung</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(appointment, index) in appointments" :key="appointment.id">
                <td>{{ formatDate(appointment.end_time) }}</td>
                <td>{{ appointment.description }}</td>
              </tr>
            </tbody>
          </table>
          <div v-else class="no-reminder">Không có lịch học sắp tới.</div>
        </div>
        <div v-if="recentNotes.length === 0" class="empty-note">❌ Không có ghi chú nào!</div>
        <div v-else class="recent-notes">
          <div class="recent-note-group" v-for="note in recentNotes" :key="note.id">
            <div class="recent-note-card">
              <div class="recent-note-content">
                <strong>{{ note.title }}</strong>
                <p>{{ note.content }}</p>
              </div>
              <div class="recent-note-info">
                <p>{{ formatDate(note.createdAt) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="function-card">
        <button 
          v-for="btn in buttons" 
          :key="btn.text" 
          :class="btn.color" 
          @click="redirectTo(btn.route)">
          <FontAwesomeIcon :icon="btn.icon" class="function-card-icon" />
          <span class="function-card-text">{{ btn.text }}</span>
        </button>
      </div>
      <div class="bottom-grid">
        <button class="large-card">
          <div v-if="selectedPdf" class="pdf-container">
            <iframe :src="selectedPdf" frameborder="0"></iframe>
          </div>
          <div v-else class="card-content">Nội dung preview...</div>
        </button>

        <div class="small-card-container">
          <div v-for="doc in favoriteDocuments" :key="doc.Dcm_id" class="small-card-wrapper">
            <button class="small-card menu-item" @click="getFullPdf(doc.Dcm_id)">
              <img :src="getPreviewUrl(doc.dcm_preview_path)" alt="Preview" class="preview-image" />
            </button>
            <button class="favorite-button" @click.stop="toggleFavorite(doc.Dcm_id)">
              <FontAwesomeIcon :icon="[isFavorited(doc.Dcm_id) ? 'fas' : 'far', 'heart']" :class="{ 'favorited': isFavorited(doc.Dcm_id) }" />
            </button>
          </div>
        </div>
      </div>
    </main>

    <!-- Login Form -->
    <transition name="fade">
      <LoginForm v-if="showLogin" @closeLogin="showLogin = false" @loginSuccess="handleLoginSuccess" />
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBell, faBook, faPen, faBookOpen, faCalendarAlt, faHome, faEye, faEyeSlash, faHeart, faUser } from '@fortawesome/free-solid-svg-icons';
import { faHeart as faHeartRegular } from '@fortawesome/free-regular-svg-icons';
import LoginForm from '@/components/Auth/Login.vue';

library.add(faHome, faEye, faEyeSlash, faBell, faBook, faPen, faBookOpen, faCalendarAlt, faHeart, faHeartRegular, faUser);
axios.defaults.withCredentials = true;

const buttons = ref([
  { text: "Ôn tập", icon: ['fas', 'book'], color: 'function-card-Ontap', route: "/documents" },
  { text: "Thi thử", icon: ['fas', 'pen'], color: 'function-card-ThiThu', route: "/tests" },
  { text: "Ghi nhớ", icon: ['fas', 'book-open'], color: 'function-card-GhiNho', route: "/notes" },  
  { text: "Đặt lịch", icon: ['fas', 'calendar-alt'], color: 'function-card-DatLich', route: "/calendar" },
]);

const isLoggedIn = ref(false);
const user = ref({ name: "Người dùng" });
const showLogin = ref(false);
const dropdownVisible = ref(false);
const favoriteDocuments = ref([]);
const favorites = ref(new Set());
const isFavorited = (id) => favorites.value.has(id);
const selectedPdf = ref(null);
const recentNotes = ref([]);
const appointments = ref([]);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const fetchAppointment = async () => {
  try {
    const response = await axios.get("api/appointments", {
      withCredentials: true,
    });

    console.log("Danh sách lịch học:", response.data);

    if (response.data && response.data.length > 0) {
      appointments.value = response.data; 
    } else {
      appointments.value = [];
    }
  } catch (error) {
    console.error("Lỗi tải lịch học:", error);
    appointments.value = [];
  }
};

const handleLoginSuccess = async () => {
  showLogin.value = false;
  try {
    await axios.get("/sanctum/csrf-cookie");
    const res = await axios.get("/api/auth/user");
    if (res.status === 200) {
      user.value = res.data;
      isLoggedIn.value = true;
    }
  } catch (error) {
    console.error("Không thể lấy thông tin người dùng sau khi đăng nhập:", error);
  }
};


const fetchFavorites = async () => {
  try {
    const res = await axios.get("/api/favorites");
    favorites.value = new Set(res.data.map(doc => doc.Dcm_id));
    favoriteDocuments.value = res.data;
  } catch (error) {
    console.error("Lỗi khi lấy danh sách yêu thích:", error);
  }
};

const fetchNotes = async () => {
  try{
    const response = await axios.get("/api/notes", {withCredentials: true});

    recentNotes.value = response.data.map(note => ({
            id: note.id,
            title: note.title,
            content: note.content,
            createdAt: new Date(note.created_at),
        }));

    notes.value = recentNotes.value.slice(0, 3);
  }catch (error) {
    console.log("lỗi không thể lấy Notes:", error)
  }
}

const getPreviewUrl = (path) => {
  return `http://127.0.0.1:8000/storage/${path}`;
};

const getFullPdf = async (id) => {
  try {
    const response = await axios.get(`/api/documents/full/${id}`);
    selectedPdf.value = response.data.file_url;
  } catch (error) {
    console.error("Lỗi khi mở tài liệu:", error);
  }
};

onMounted(async () => {
  try {
    await axios.get("/sanctum/csrf-cookie");
    const res = await axios.get("/api/auth/user");
    if (res.status === 200) {
      user.value = res.data;
      isLoggedIn.value = true;
      fetchFavorites();
      fetchNotes();
      fetchAppointment();
    }
  } catch (error) {
    console.error("Không thể lấy thông tin người dùng:", error);
  }
});

const toggleDropdown = () => {
  dropdownVisible.value = !dropdownVisible.value;
};

const redirectToProfile = () => {
  window.location.href = "/profile";
};

const logout = async () => {
  try {
    await axios.post("/api/auth/logout");
    isLoggedIn.value = false;
    user.value = { name: "Người dùng" };
  } catch (error) {
    console.error("Lỗi khi đăng xuất:", error);
  }
};

const redirectTo = (route) => {
  window.location.href = route;
};
</script>