<template>
      <div id="register">
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
          <h2>ĐĂNG KÝ</h2>
          <div class="register-container">
            <div class="e-card playing">
              <div class="image"></div>
              <div class="wave"></div>
              <div class="wave"></div>
              <div class="wave"></div>
              <div class="infotop"></div>
            </div>
            <form @submit.prevent="handleRegister">
              <div class="input-group">
                <label for="email">Gmail:</label>
                <input 
                  type="email" 
                  id="email" 
                  v-model="formData.email" 
                  name="email" 
                  required 
                />
              </div>
  
              <div class="input-group">
                <label for="name">Tên người dùng:</label>
                <input 
                  type="text" 
                  id="name" 
                  v-model="formData.name" 
                  name="name" 
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
                          required
                      />
                      <FontAwesomeIcon 
                          :icon="showPassword ? 'fa-eye-slash' : 'fa-eye'" 
                          class="toggle-password-icon"
                          @click="togglePasswordVisibility" 
                      />
                  </div>

                  <div class="input-group">
                    <label for="password_confirmation">Xác nhận mật khẩu:</label>
                    <input 
                      :type="showPassword ? 'text' : 'password'" 
                      id="password_confirmation" 
                      v-model="formData.password_confirmation" 
                      name="password_confirmation" 
                      autocomplete="new-password"
                      required
                    />
                  </div>
              </div>
  
              <div class="input-group-checkbox">
                <input type="checkbox" id="accept-clause" name="accept-clause" />
                <label for="accept-clause">Đồng ý với các điều khoản?</label>
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
    name: '',
    password: '',
    password_confirmation: ''
  });
  
  const errorMessage = ref('');
  const successMessage = ref('');
  
  const showPassword = ref(false);
  
  const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
  };
  const handleRegister = async () => {
  errorMessage.value = '';
  successMessage.value = '';
  
  if (formData.value.password !== formData.value.password_confirmation) {
    errorMessage.value = 'Mật khẩu xác nhận không khớp.';
    return;
  }
  
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/auth/register', formData.value);
    successMessage.value = response.data.message;
    window.location.href = '/login';
  } catch (error) {
    if (error.response && error.response.data.errors) {
      errorMessage.value = Object.values(error.response.data.errors).join(' ');
    } else {
      errorMessage.value = 'Đăng ký thất bại. Vui lòng thử lại.';
    }
  }
};

  </script>
  