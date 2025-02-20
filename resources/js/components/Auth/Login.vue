<template>
    <div id="login">
      <header class="header">
        <div class="logo">Logo</div>
        <div class="title">Thinksync</div>
        <div class="home-return">
          <a href="./">
            <FontAwesomeIcon :icon="['fas', 'home']" class="icon-home" />
            Trang chủ
          </a>
        </div>
      </header>
  
      <div class="main-container">
        <h2>ĐĂNG NHẬP</h2>
        <div class="login-container">
          <div class="e-card playing">
    <div class="image"></div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
        <div class="infotop">
        </div>
    </div>
          <form @submit.prevent="handleLogin">
            <div class="input-group">
              <label for="name">Email người dùng:</label>
              <input 
                type="text" 
                id="name" 
                v-model="formData.email" 
                name="email" 
                required 
              />
            </div>
            <div class="input-group">
              <label for="password">Mật khẩu:</label>
              <div class="password-container">
                <input 
  :type="showPassword ? 'text' : 'password'" 
  id="password" 
  v-model="formData.password" 
  name="password" 
  autocomplete="new-password"
/>
<FontAwesomeIcon 
  :icon="showPassword ? 'fa-eye-slash' : 'fa-eye'" 
  class="toggle-password-icon"
  @click="togglePasswordVisibility" 
/>
              </div>
            </div>
            <div class="register">
              <a href="#">Quên mật khẩu?</a>
              <a href="/register">Chưa có tài khoản?</a>
            </div>
            <div class="input-group-checkbox">
              <input type="checkbox" id="remem-infor" name="remem-infor" />
              <label for="remem-infor">Nhớ thông tin</label>
            </div>
            <button type="submit" class="btn">Đăng nhập</button>
          </form>
          <div v-if="errorMessage" class="error-message">
            {{ errorMessage }}
          </div>
          <div v-if="successMessage" class="success-message">
            {{ successMessage }}
          </div>
        </div>
      </div>
    </div>

  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { library } from '@fortawesome/fontawesome-svg-core';
  import { faHome, faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import axios from 'axios';
  
  library.add(faHome, faEye, faEyeSlash);
  
  const formData = ref({
    email: '',
    password: '',
  });
  
  const errorMessage = ref('');
  const successMessage = ref('');
  
  const showPassword = ref(false);
  const passwordFieldType = ref('password');
  
  const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
    passwordFieldType.value = showPassword.value ? 'text' : 'password';
  };
  
  const handleLogin = async () => {
    try {
      errorMessage.value = '';
      successMessage.value = '';
  
      const response = await axios.post('/api/auth/login', formData.value);
  
      if (response.status === 200) {
        successMessage.value = response.data.message;
        console.log('Đăng nhập thành công!');
        window.location.href = '/';
      }
    } catch (error) {
      if (error.response) {
        errorMessage.value = error.response.data.errors?.msg || 'Tên người dùng hoặc mật khẩu không đúng.';
      } else {
        errorMessage.value = 'Lỗi kết nối đến máy chủ.';
      }
    }
  };
  </script>
