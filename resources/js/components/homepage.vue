
<template>
      <div id="app">
        <header class="header">
          <div class="logo">Logo</div>
          <div class="title">Thinksync</div>
          <div class="noti-login">
            <div class="noti">
              <button>
                <FontAwesomeIcon :icon="['fas', 'bell']" class="icon-bell" />
              </button>
            </div>
            <a href="" class="login-link">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-user">
                <path
                  d="M12 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12zm0 14c-4.418 0-8 2.015-8 4.5V22h16v-1.5c0-2.485-3.582-4.5-8-4.5z"
                />
              </svg>Đăng nhập
            </a>
          </div>
        </header>
    
        <main class="main">
          <div class="top-grid">
            <div class="top-grid-card" v-for="n in 7" :key="n"></div>
          </div>
          <div class="function-card">
            <button v-for="btn in buttons" :key="btn.text" :class="btn.color">
              <FontAwesomeIcon :icon="btn.icon" class="function-card-icon" />
              <span class="function-card-text">{{ btn.text }}</span>
            </button>
          </div>
          
          <div class="bottom-grid">
            <button class="large-card">
              <div class="card-content">cái ô preview này khi nó có nội dung vượt quá chiều cao cố định của grid large-card là nó sẽ scroll á nghen ^^</div>
            </button>
            <div class="small-card-container">
              <button class="small-card">
                <div class="card-content">.............</div>
              </button>
              <button class="small-card">
                <div class="card-content">.............</div>
              </button>
            </div>
          </div>
        </main>
      </div>
    </template>
    
    <script setup>
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import { library } from '@fortawesome/fontawesome-svg-core';
    import {
        faBell, faBook, faPen, faBookOpen, faGamepad, faCalendarAlt, faUsers,
    } from '@fortawesome/free-solid-svg-icons';
    
    library.add(faBell,faBook, faPen, faBookOpen, faGamepad, faCalendarAlt, faUsers);
    </script>
    
    <script>
    export default {
      components: {
        FontAwesomeIcon,
      },
      data() {
        return {
          buttons: [
            { text: "Ôn tập", icon: ['fas', 'book'], color: 'function-card-Ontap' },
            { text: "Thi thử", icon: ['fas', 'pen'], color: 'function-card-ThiThu' },
            { text: "Ghi nhớ", icon: ['fas', 'book-open'], color: 'function-card-GhiNho' },
            { text: "Giải trí", icon: ['fas', 'gamepad'], color: 'function-card-GiaiTri' },
            { text: "Room", icon: ['fas', 'users'], color: 'function-card-Room' },
            { text: "Đặt lịch", icon: ['fas', 'calendar-alt'], color: 'function-card-DatLich' },
          ],
        };
      },
    };
    </script>
    