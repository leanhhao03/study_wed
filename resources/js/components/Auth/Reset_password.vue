<template>
    <div id="reset_password">
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
            <h1 class="no-select">Đặt lại mật khẩu nào !</h1>
            <p class="no-select">Gmail đã được xác nhận hãy tạo lại mật khẩu đủ mạnh và dễ nhớ nhé!</p>
            <form action="">
                <div class="input-group">
                <label class="no-select" for="password">Mật khẩu mới:</label>
                <input class="no-select" type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                <label class="no-select" for="confirm_password">Nhập lại mật khẩu:</label>
                <input class="no-select" type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-submit">Đổi mật khẩu</button>
            </form>
        </div>
      </div>
    
    </div>
</template>