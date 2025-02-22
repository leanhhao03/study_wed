<template>
    <div id="forget_password">
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

      <div class="content">
            <div class="container">
                <h1 class="no-select">Hi bạn!</h1>
                <p class="no-select">Có vẻ như bạn đã quên gì đó ? Hãy nhập gmail bạn dùng để tạo tài khoàn để nhận yêu cầu đổi mật khẩu</p>
                <form action="">
                    <div class="input-group">
                        <label class="Gmail no-select" for="email">Gmail:</label>
                        <input class="no-select" type="email" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn-submit">Gửi Gmail</button>
                </form>
            </div>
      </div>
    </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import {faHome} from '@fortawesome/free-solid-svg-icons';


library.add(faHome);
</script>