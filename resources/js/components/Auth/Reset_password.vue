<template>
  <div id="reset_password">
    <header class="header">
      <div class="logo">Logo</div>
      <div class="title">Thinksync</div>
      <div class="home-return">
        <a href="./">
          <FontAwesomeIcon :icon="['fas', 'home']" class="icon-home" />
          Trang chủ
        </a>
      </div>
    </header>

    <div class="content">
      <div class="container">
        <!-- Hiển thị thông báo -->
        <div v-if="message" class="message">{{ message }}</div>

        <!-- Bước 1: Nhập Email để gửi OTP -->
        <div v-if="step === 1">
          <h1 class="no-select">Hi bạn!</h1>
          <p class="no-select">
            Có vẻ như bạn đã quên gì đó? Hãy nhập gmail bạn dùng để tạo tài khoản để nhận yêu cầu đổi mật khẩu.
          </p>

          <form @submit.prevent="sendOtp">
            <div class="input-group">
              <label class="no-select" for="email">Gmail:</label>
              <input class="no-select" type="email" v-model="email" required />
            </div>
            <button type="submit" class="btn-submit">Gửi mã OTP</button>
          </form>
        </div>

        <!-- Bước 2: Nhập OTP & Mật khẩu mới -->
        <div v-else-if="step === 2">
          <h1 class="no-select">Đặt lại mật khẩu</h1>
          <p class="no-select">Nhập mã OTP và mật khẩu mới.</p>

          <form @submit.prevent="resetPassword">
            <div class="input-group">
              <label class="no-select" for="otp">Nhập OTP:</label>
              <input class="no-select" type="text" v-model="otp" required />

              <label class="no-select" for="password">Mật khẩu mới:</label>
              <input class="no-select" type="password" v-model="newpassword" required />

              <label class="no-select" for="confirm_password">Nhập lại mật khẩu:</label>
              <input class="no-select" type="password" v-model="confirmpassword" required />
            </div>
            <button type="submit" class="btn-submit">Đổi mật khẩu</button>
          </form>
        </div>

        <!-- Hoàn tất -->
        <div v-else>
          <h1 class="no-select">Thành công!</h1>
          <p class="no-select">Mật khẩu đã được thay đổi. Hãy đăng nhập nhé!</p>
          <button @click="goToLogin" class="btn-Login">Quay về Login</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faHome } from "@fortawesome/free-solid-svg-icons";

library.add(faHome);

const step = ref(1); // Bước hiện tại
const email = ref("");
const newpassword = ref("");
const confirmpassword = ref("");
const otp = ref("");
const message = ref(""); // Thông báo cho người dùng

const sendOtp = async () => {
  if (!email.value) {
    message.value = "Vui lòng nhập email!";
    return;
  }

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/send-email", {
      email: email.value,
    });

    message.value = response.data.message; // Cập nhật thông báo
    step.value = 2; // Chuyển sang bước nhập OTP
  } catch (error) {
    message.value = error.response?.data?.message || "Đã xảy ra lỗi!";
  }
};

const resetPassword = async () => {
  if (!otp.value || !newpassword.value || !confirmpassword.value) {
    message.value = "Vui lòng nhập đầy đủ thông tin!";
    return;
  }

  if (newpassword.value !== confirmpassword.value) {
    message.value = "Mật khẩu không khớp!";
    return;
  }

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/reset-password", {
      email: email.value,
      otp: otp.value,
      password: newpassword.value,
      password_confirmation: confirmpassword.value,
    });

    message.value = response.data.message; // Cập nhật thông báo
    step.value = 3; // Chuyển sang bước hoàn tất
  } catch (error) {
    message.value = error.response?.data?.message || "Đã xảy ra lỗi!";
  }
};

const goToLogin = () => {
  window.location.href = "/login";
};
</script>

<style>
/* Thêm CSS để hiển thị message */
.message {
  padding: 10px;
  margin: 10px 0;
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
  border-radius: 5px;
  text-align: center;
}
</style>
