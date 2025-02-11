import { createApp } from 'vue';
import homepage from './components/homepage.vue';
import Login from './components/Auth/Login.vue'
import Register from './components/Auth/Resgister.vue'

import '../css/homepage.css';
import '../css/Login.css';


createApp(homepage).mount('#app');
createApp(Login).mount('#login');
createApp(Register).mount('#register');