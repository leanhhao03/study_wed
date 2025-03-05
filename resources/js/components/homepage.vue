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
        <div class="noti">
          <button>
            <FontAwesomeIcon :icon="['fas', 'bell']" class="icon-bell" />
          </button>
        </div>
        <a v-if="!isLoggedIn" href="#" @click.prevent="showLogin = true" class="login-link">
          <FontAwesomeIcon :icon="['fas', 'user']" class="icon-user" /> Đăng nhập
        </a>
        <a v-else href="#" @click.prevent="redirectToProfile" class="user-info">
          <img v-if="user.profile_picture" :src="user.profile_picture" alt="User Avatar" class="user-avatar">
          <span class="user-name">{{ user.name }}</span>
        </a>
      </div>
    </header>

    <!-- Main content -->
    <main v-if="!showLogin" class="main">
      <div class="top-grid">
        <div class="top-grid-card" v-for="n in 5" :key="n"></div>
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
          <div class="card-content">Nội dung preview...</div>
        </button>
        <div class="small-card-container">
          <button class="small-card">
            <div class="card-content"></div>
          </button>
          <button class="small-card">
            <div class="card-content"></div>
          </button>
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
import { faBell, faBook, faPen, faBookOpen, faGamepad, faCalendarAlt, faUsers, faHome, faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons';
import LoginForm from '@/components/Auth/Login.vue';

library.add(faHome, faEye, faEyeSlash, faBell, faBook, faPen, faBookOpen, faGamepad, faCalendarAlt, faUsers);

const isLoggedIn = ref(false);
const user = ref({ name: "Người dùng" });
const showLogin = ref(false);

const handleLoginSuccess = () => {
  showLogin.value = false;
  isLoggedIn.value = true;
};

const getID = localStorage.getItem('user_id');

onMounted(async () => {
  try {
    if(getID) {
      console.log(getID);
      const res = await axios.get(`/api/auth/user/${getID}`);
      user.value = res.data;
      isLoggedIn.value = true;
    }
  } catch (error) {
    console.error(error);
  }
});

const redirectToProfile = () => {
  window.location.href = "/profile";
};

const buttons = ref([
  { text: "Ôn tập", icon: ['fas', 'book'], color: 'function-card-Ontap', route: "/documents" },
  { text: "Thi thử", icon: ['fas', 'pen'], color: 'function-card-ThiThu', route: "/tests" },
  { text: "Ghi nhớ", icon: ['fas', 'book-open'], color: 'function-card-GhiNho', route: "/notes" },  
  { text: "Đặt lịch", icon: ['fas', 'calendar-alt'], color: 'function-card-DatLich', route: "/calendar" },
]);

const redirectTo = (route) => {
  window.location.href = route;
};
</script>

<style scoped>
.login-header {
  position: fixed;
  top: 0;
  width: 100%;
  background: white;
  z-index: 1000;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
