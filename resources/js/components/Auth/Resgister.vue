<template>
    <div class="register-container">
      <h2>Đăng ký</h2>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="username">Tên đăng nhập</label>
          <input type="text" id="username" v-model="form.username" required>
          <div v-if="errors.username" class="error">{{ errors.username }}</div>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="form.email" required>
          <div v-if="errors.email" class="error">{{ errors.email }}</div>
        </div>
        <div class="form-group">
          <label for="password">Mật khẩu</label>
          <input type="password" id="password" v-model="form.password" required>
          <div v-if="errors.password" class="error">{{ errors.password }}</div>
        </div>
        <div class="form-group">
          <label for="password_confirmation">Xác nhận mật khẩu</label>
          <input type="password" id="password_confirmation" v-model="form.password_confirmation" required>
        </div>
        <button type="submit">Đăng ký</button>
      </form>
    </div>
  </template>
  
  <script>
import axios from 'axios';
import { reactive } from 'vue';
    
    export default {
      setup() {
          const formData = reactive({
            'username': '',
            'email': '',
            'password': '',
            'password_confirmation': ''
          });

          const handleRegister = async () => {
            const respon = await axios.post('api/register', formData);
          }
          return {
            formData,
            handleRegister
            }

      //     errors: {}
      //   };
      // },
      // methods: {
      //   async handleRegister() {
      //     try {
      //       const response = await axios.post('/api/register', {
      //         name: this.form.username,
      //         email: this.form.email,
      //         password: this.form.password,
      //         password_confirmation: this.form.password_confirmation
      //       });
    
      //       if (response.status === 200) {
      //         alert('Đăng ký thành công');
      //         // Reset form hoặc thực hiện hành động khác sau khi đăng ký thành công
      //         this.form.username = '';
      //         this.form.email = '';
      //         this.form.password = '';
      //         this.form.password_confirmation = '';
      //         this.errors = {};
      //       }
      //     } catch (error) {
      //       if (error.response && error.response.status === 422) {
      //         this.errors = error.response.data.errors || {};
      //         alert('Đăng ký thất bại: ' + JSON.stringify(this.errors));
      //       } else {
      //         alert('Đã xảy ra lỗi. Vui lòng thử lại.');
      //         console.error('Error:', error); // Ghi lại chi tiết lỗi
      //       }
      //     }
      //   }
     }
  }
  </script>
  
  <style scoped>
  .register-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 5px;
  }
  
  .form-group input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }
  
  .error {
    color: red;
    font-size: 0.875em;
  }
  
  button {
    padding: 10px 15px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  </style>
  