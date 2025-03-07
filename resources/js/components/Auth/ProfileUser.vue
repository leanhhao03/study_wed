<template>
  <div id="profile">
      <div class="header">
          <div class="logo">logo</div>
          <div class="title">
              <a href="/">Thinksync</a>
          </div>
      </div>

      <div class="profile-container">
          <div class="left-menu">
              <a v-for="btn in leftMenuButtons" :key="btn.text" :href="btn.link" :class="btn.color" class="left-menu-button">
                  <FontAwesomeIcon :icon="btn.icon" class="left-menu-icon" />
                  <span class="left-menu-text">{{ btn.text }}</span>
              </a>
          </div>
          <div class="main">
              <div class="user-info">
                  <div class="user-image-container">
                      <div class="user-image">
                          <img src="" alt="Picture of user">
                      </div>
                      <button class="edit-picture-button">
                          <FontAwesomeIcon :icon="['fas', 'user-pen']" />
                      </button>
                  </div>
                  <div class="user-details">
                      <div class="details-item">
                          <h2>Name of user: {{ user.name }}</h2>
                          <p>Time to join: {{ user.created_at }}</p>
                      </div>
                      <div class="details-item">
                          <h2>Grades</h2>
                          <p>Favorite course</p>
                      </div>
                      <div class="edit-btn">
                          <button class="edit-profile-button" @click="openEditForm">
                              <FontAwesomeIcon :icon="['fas', 'user-pen']" />
                          </button>
                      </div>
                  </div>

                  <div v-if="showEditForm" class="overlay" @click.self="closeEditForm">
                      <div class="edit-form">
                        <div class="e-card playing">
                        <div class="image"></div>
                        <div class="wave"></div>
                          <div class="wave"></div>
                          <div class="wave"></div>
                              <div class="infotop"></div> </div>
                              <h2>Thay đổi thông tin cá nhân</h2>
                          <div class="input-box">
                              <label for="name">Tên người dùng</label>
                              <input type="text" v-model="name">
                          </div>
                          <div class="input-box">
                              <label for="phone">Số điện thoại</label>
                              <input type="tel" v-model="phone" @input="validatePhone">
                          </div>
                          <div class="input-box">
                              <label for="sex">Giới tính</label>
                              <select v-model="gender">
                                  <option value="male">Male</option>
                                  <option value="female">Female</option>
                                  <option value="other">other</option>
                              </select>
                          </div>
                          <div class="nav-btn">
                              <button class="change-btn" @click="openEditGmailForm">Đổi Gmail</button>
                              <button class="change-btn" @click="openEditPasswordForm">Đổi mật khẩu</button>
                          </div>
                          <button class="save-btn" @click="updateProfile">Lưu</button>
                      </div>
                  </div>
                  <div v-if="showEditGmailForm" class="overlay" @click.self="closeEditGmailForm">
                      <div class="edit-form">
                        <div class="e-card playing">
                        <div class="image"></div>
                        <div class="wave"></div>
                          <div class="wave"></div>
                          <div class="wave"></div>
                              <div class="infotop"></div> </div>
                          <h2>Đổi Gmail</h2>
                          <div class="input-box">
                              <label for="old-email">Gmail cũ</label>
                              <input type="email" v-model="oldEmail">
                          </div>
                          <div class="input-box">
                              <label for="new-email">Gmail mới</label>
                              <input type="email" v-model="newEmail">
                          </div>
                          <div class="nav-btn">
                              <button class="change-btn" @click="openEditForm">Đổi thông tin cá nhân</button>
                              <button class="change-btn" @click="openEditPasswordForm">Đổi mật khẩu</button>
                          </div>
                          <button class="save-btn" @click="validateOldEmail">Lưu</button>
                      </div>
                  </div>
                  <div v-if="showEditPasswordForm" class="overlay" @click.self="closeEditPasswordForm">
                      <div class="edit-form">
                          <h2>Đổi mật khẩu</h2>
                          <div class="e-card playing">
                        <div class="image"></div>
                        <div class="wave"></div>
                          <div class="wave"></div>
                          <div class="wave"></div>
                              <div class="infotop"></div> </div>
                          <div class="input-box">
                              <label for="old-password">Mật khẩu cũ</label>
                              <input type="password" v-model="oldPassword">
                          </div>
                          <div class="input-box">
                              <label for="new-password">Mật khẩu mới</label>
                              <input type="password" v-model="newPassword">
                          </div>
                          <div class="nav-btn">
                              <button class="change-btn" @click="openEditForm">Đổi thông tin cá nhân</button>
                              <button class="change-btn" @click="openEditGmailForm">Đổi Gmail</button>
                          </div>
                          <button class="save-btn" @click="updateProfile">Lưu</button>
                      </div>
                  </div>
              </div>

              <div class="statistics">
                  <div class="stat-title">
                      <h2>Statistics</h2>
                  </div>
                  <div class="stat-item">
                      <div class="star-1">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-2">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-3">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-4">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-5">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-6">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
                      <p>Number of times to join the room:</p>
                      <p>EX: Đã tham gia 20 phòng học</p> 
                  </div>
                  <div class="stat-item">
                    <div class="star-1">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-2">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-3">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-4">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-5">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-6">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
                      <p>Number of times to done the test:</p>
                      <p>EX: Đã hoàn thành 15 bài kiểm tra</p> 
                  </div>
                  <div class="stat-item">
                    <div class="star-1">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-2">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-3">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-4">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-5">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-6">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
                      <p>Number of times to do exercises:</p>
                      <p>EX: Đã hoàn thành ôn tập 24 tài liệu</p> 
                  </div>
                  <div class="stat-item">
                    <div class="star-1">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-2">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-3">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-4">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-5">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
<div class="star-6">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xml:space="preserve"
    version="1.1"
    style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
    viewBox="0 0 784.11 815.53"
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <defs></defs>
    <g id="Layer_x0020_1">
      <metadata id="CorelCorpID_0Corel-Layer"></metadata>
      <path
        class="fil0"
        d="M392.05 0c-20.9,210.08 -184.06,378.41 -392.05,407.78 207.96,29.37 371.12,197.68 392.05,407.74 20.93,-210.06 184.09,-378.37 392.05,-407.74 -207.98,-29.38 -371.16,-197.69 -392.06,-407.78z"
      ></path>
    </g>
  </svg>
</div>
                      <p>Number of the time join to learn meet the deadline:</p>
                      <p>EX: Đã tham gia học đúng giờ 32 lần</p> 
                  </div>
  
              </div>
          </div>
          <p v-if="showEmailError" :class="{'error-message': true, 'hide': !showEmailError}">
              Gmail cũ không chính xác!
          </p>
          <p v-if="showPasswordError" :class="{'error-message': true, 'hide': !showPasswordError}">
              Mật khẩu cũ không chính xác!
            </p>
      </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from "vue";
  import axios from "axios";
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { library } from '@fortawesome/fontawesome-svg-core';
  import { faBook, faPen, faBookOpen, faCalendarAlt, faUserPen } from '@fortawesome/free-solid-svg-icons';

  library.add(faBook, faPen, faBookOpen, faCalendarAlt, faUserPen);

  const showEditForm = ref(false);
  const showEditGmailForm = ref(false);
  const showEditPasswordForm = ref(false);
  const name = ref("");
  const phone = ref("");
  const gender = ref("male");
  const profilePicture = ref(null);
  const oldEmail = ref("");
  const newEmail = ref("");
  const oldPassword = ref("");
  const newPassword = ref("");
  const showEmailError = ref(false);
  const showPasswordError = ref(false);
  const UserId = localStorage.getItem('user_id');
  const user = ref({});

  const openEditForm = () => {
      showEditGmailForm.value = false;
      showEditPasswordForm.value = false;
      showEditForm.value = true;
  };
  const openEditGmailForm = () => {
      showEditForm.value = false;
      showEditPasswordForm.value = false;
      showEditGmailForm.value = true;
  };
  const openEditPasswordForm = () => {
      showEditForm.value = false;
      showEditGmailForm.value = false;
      showEditPasswordForm.value = true;
  };
  const closeEditForm = () => { showEditForm.value = false; };
  const closeEditGmailForm = () => { showEditGmailForm.value = false; };
  const closeEditPasswordForm = () => { showEditPasswordForm.value = false};
  const validatePhone = () => { phone.value = phone.value.replace(/\D/g, '').slice(0, 10); };
  
  const validateOldEmail = () => {
  if (oldEmail.value !== "...@example.com") {
      showEmailError.value = true;
      setTimeout(() => { showEmailError.value = false; }, 3000);
  } else {
      closeEditGmailForm();
  }
};

const fetchUserInfo = async () =>{
  try{
    const response = await axios.get(`/api/auth/user/${UserId}`);
    user.value = response.data;
  }catch (error){
    console.error("Lỗi khi lấy thông tin người dùng:", error.response?.data || error );
  }
};

onMounted(() => {
    if (UserId) {
        fetchUserInfo();
    }
});

const updateProfile = async () => {
  const formData = new FormData();
    
    if (name.value) formData.append("name", name.value);
    if (phone.value) formData.append("number_phone", phone.value);
    if (gender.value) formData.append("gender", gender.value);
    if (profilePicture.value) formData.append("profile_picture", profilePicture.value);


    try {
        const response = await axios.post(`/api/auth/edit/${UserId}`, formData, {
        });
        showEditForm.value = false;
        setTimeout(() => {
          fetchUserInfo();
          }, 500);
        alert(response.data.message);
    } catch (error) {
        console.error("Lỗi khi cập nhật:", error.response?.data || error);
    }
};

  const validateOldPassword = () => {
      if (oldPassword.value !== "correct_password") {
          showPasswordError.value = true;
          setTimeout(() => { showPasswordError.value = false; }, 3000);
      } else {
          closeEditPasswordForm();
      }
  };
</script>
<script>
  export default {
      components: {
          FontAwesomeIcon
      },
      data() {
          return {
              leftMenuButtons: [
                  { text: "Ôn tập", icon: "book", link: "/document" },
                  { text: "Thi thử", icon: "pen", link: "/test" },
                  { text: "Ghi nhớ", icon: "book-open", link: "/note" },
                  { text: "Đặt lịch", icon: "calendar-alt", link: "/calendar" },
              ]
          };
      }
  };

</script>