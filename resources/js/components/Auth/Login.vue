<template>
  <div id="login">
    <div class="main-container">
      <h2>ĐĂNG NHẬP</h2>
      <div class="login-container">
        <div class="e-card playing">
          <div class="image"></div>
          <div class="wave"></div>
          <div class="wave"></div>
          <div class="wave"></div>
        </div>

        <form @submit.prevent="handleLogin">
          <div class="input-group">
            <label for="email">Email người dùng:</label>
            <input type="email" id="email" v-model.trim="formData.email" required />
          </div>

          <div class="input-group">
            <label for="password">Mật khẩu:</label>
            <div class="password-container">
              <input :type="showPassword ? 'text' : 'password'" id="password" v-model.trim="formData.password" required />
              <FontAwesomeIcon :icon="showPassword ? 'eye-slash' : 'eye'" class="toggle-password-icon" @click="togglePassword" />
            </div>
          </div>

          <div class="register">
            <a href="#" @click.prevent="openForgotPassword">Quên mật khẩu?</a>
            <a href="/register">Chưa có tài khoản?</a>
          </div>

          <div class="input-group-checkbox">
            <input type="checkbox" id="remember-me" v-model="rememberMe" />
            <label for="remember-me">Nhớ thông tin</label>
          </div>

          <button type="submit" class="btn">Đăng nhập</button>
        </form>

        <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
        <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import axios from 'axios';

const formData = ref({ email: '', password: '' });
const showPassword = ref(false);
const rememberMe = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const emit = defineEmits(["loginSuccess"]);

const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

const handleLogin = async () => {
  errorMessage.value = '';
  successMessage.value = '';

  if (!formData.value.email || !formData.value.password) {
    errorMessage.value = "Vui lòng nhập đầy đủ thông tin!";
    return;
  }

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/auth/login", formData.value, {
      withCredentials: true, // Giữ session để xác thực
    });

    if (response.status === 204) { // Laravel Breeze trả về 204 nếu đăng nhập thành công
      successMessage.value = "Đăng nhập thành công!";
      
      // Lưu trạng thái đăng nhập vào localStorage
      localStorage.setItem('auth', 'true');

      // Nếu chọn "Nhớ thông tin", lưu email vào localStorage
      if (rememberMe.value) localStorage.setItem('remember_email', formData.value.email);

      // Chuyển hướng sau khi đăng nhập thành công
      setTimeout(() => {
        window.location.href = "/";
      }, 500);
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.message || "Đăng nhập thất bại.";
  }
};

</script>

<style scoped>
.password-container {
  display: flex;
  align-items: center;
}

.toggle-password-icon {
  cursor: pointer;
  margin-left: 8px;
}

.error-message {
  color: red;
  margin-top: 10px;
}

.success-message {
  color: green;
  margin-top: 10px;
}
</style>
