import { createApp } from 'vue';
import homepage from './components/homepage.vue';
import Login from './components/Auth/Login.vue'
import Register from './components/Auth/Resgister.vue'
import document from './components/document.vue';
import test from './components/test.vue';
import Reset_password from './components/Auth/Reset_password.vue';
import Profile from './components/Auth/ProfileUser.vue';

import '../css/homepage.css';
import '../css/Auth/Login.css';
import '../css/Auth/Register.css';
import '../css/document.css';
import '../css/test.css';
import '../css/Auth/Reset_password.css';
import '../css/Auth/ProfileUser.css';

createApp(homepage).mount('#app');
createApp(Login).mount('#login');
createApp(Register).mount('#register');
createApp(document).mount('#document');
createApp(test).mount('#test');
createApp(Reset_password).mount('#reset_password');
createApp(Profile).mount('#profile');