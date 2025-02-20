import { createApp } from 'vue';
import homepage from './components/homepage.vue';
import Login from './components/Auth/Login.vue'
import Register from './components/Auth/Resgister.vue'
import document from './components/document.vue';
import test from './components/test.vue';

import '../css/homepage.css';
import '../css/Auth/Login.css';
import '../css/Auth/Register.css';
import '../css/document.css';
import '../css/test.css';

createApp(homepage).mount('#app');
createApp(Login).mount('#login');
createApp(Register).mount('#register');
createApp(document).mount('#document');
createApp(test).mount('#test');
