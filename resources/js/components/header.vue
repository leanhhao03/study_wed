<template>
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
</template>