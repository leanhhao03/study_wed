<template>
    <div id="profile">
      <header class="header">
        <div class="logo">logo</div>
        <div class="title">
          <a href="/">Thinksync</a>
        </div>
      </header>
  
      <div class="profile-container">
        <!-- Left Menu -->
        <nav class="left-menu">
          <a 
            v-for="btn in leftMenuButtons" 
            :key="btn.text" 
            :href="btn.link" 
            :class="btn.color" 
            class="left-menu-button"
          >
            <FontAwesomeIcon :icon="btn.icon" class="left-menu-icon" />
            <span class="left-menu-text">{{ btn.text }}</span>
          </a>
        </nav>
  
        <!-- Main Content -->
        <main class="main">
          <!-- User Info -->
          <section class="user-info">
            <div class="user-image-container">
              <div class="user-image">
                <img :src="user.image || defaultImage" alt="User Picture">
              </div>
              <button class="edit-picture-button">
                <FontAwesomeIcon :icon="['fas', 'user-pen']" />
              </button>
            </div>
  
            <div class="user-details">
              <div class="details-item">
                <h2>{{ user.name }}</h2>
                <p>Tham gia: {{ user.joinDate }}</p>
              </div>
              <div class="details-item">
                <h2>Grades</h2>
                <p>{{ user.favoriteCourse }}</p>
              </div>
              <div class="edit-btn">
                <button class="edit-profile-button" @click="openEditForm">
                  <FontAwesomeIcon :icon="['fas', 'user-pen']" />
                </button>
              </div>
            </div>
          </section>
  
          <!-- Edit Form Modal -->
          <div v-if="showEditForm" class="overlay" @click.self="closeEditForm">
            <div class="edit-form">
              <h2>Thay đổi thông tin</h2>
              <div class="input-box" v-for="(value, key) in editForm" :key="key">
                <label :for="key">{{ labels[key] }}</label>
                <input :type="inputTypes[key]" v-model="editForm[key]" />
              </div>
              <button class="save-btn" @click="saveChanges">Lưu</button>
            </div>
          </div>
  
          <!-- Statistics -->
          <section class="statistics">
            <h2>Statistics</h2>
            <div class="stat-item" v-for="(text, key) in statistics" :key="key">
              <p>{{ text }}</p>
              <p>EX: {{ user.stats[key] }}</p>
            </div>
          </section>
        </main>
      </div>
    </div>
  </template>

<script setup>
    import { ref } from "vue";
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import { library } from '@fortawesome/fontawesome-svg-core';
    import { faBook, faPen, faBookOpen, faCalendarAlt, faUserPen } from '@fortawesome/free-solid-svg-icons';

    library.add(faBook, faPen, faBookOpen, faCalendarAlt, faUserPen);


    const showEditForm = ref(false);

    const openEditForm = () => {
    showEditForm.value = true;
    };

    const closeEditForm = () => {
    showEditForm.value = false;
    };
</script>
  
  <script>
  export default {
    data() {
      return {
        defaultImage: "https://github.com/github.png",
        showEditForm: false,
        user: {
          image: "",
          name: "Tên người dùng",
          joinDate: "01/01/2024",
          favoriteCourse: "Toán học",
          stats: {
            roomsJoined: "Đã tham gia 20 phòng học",
            testsCompleted: "Đã hoàn thành 15 bài kiểm tra",
            exercisesDone: "Đã hoàn thành ôn tập 24 tài liệu",
            onTime: "Đã tham gia học đúng giờ 32 lần"
          }
        },
        editForm: {
          name: "",
          emailCurrent: "",
          emailNew: "",
          passwordCurrent: "",
          passwordNew: ""
        },
        inputTypes: {
          name: "text",
          emailCurrent: "email",
          emailNew: "email",
          passwordCurrent: "password",
          passwordNew: "password"
        },
        labels: {
          name: "Tên người dùng",
          emailCurrent: "Gmail hiện tại",
          emailNew: "Gmail mới",
          passwordCurrent: "Mật khẩu hiện tại",
          passwordNew: "Mật khẩu mới"
        },
        statistics: {
          roomsJoined: "Số lần tham gia phòng học:",
          testsCompleted: "Số lần làm bài kiểm tra:",
          exercisesDone: "Số lần ôn tập tài liệu:",
          onTime: "Số lần tham gia đúng giờ:"
        },
        leftMenuButtons: [
            { text: "Ôn tập", icon: "book", link: "/documents" },
            { text: "Thi thử", icon: "pen", link: "/tests" },
            { text: "Ghi nhớ", icon: "book-open", link: "/notes" },
            { text: "Đặt lịch", icon: "calendar-alt", link: "/calendar" },
        ]
      };
    },
    methods: {
      openEditForm() {
        this.showEditForm = true;
      },
      closeEditForm() {
        this.showEditForm = false;
      },
      saveChanges() {
        this.user.name = this.editForm.name || this.user.name;
        this.showEditForm = false;
      }
    }
  };
  </script>
  