import { createApp } from 'vue';
import homepage from './components/homepage.vue';
import Login from './components/Auth/Login.vue'
import Register from './components/Auth/Resgister.vue'
import document from './components/document.vue';
import test from './components/test.vue';
import Forget_Password from './components/Auth/Forget_Password.vue';
import Reset_password from './components/Auth/Reset_password.vue';
import note from './components/note.vue';


import '../css/homepage.css';
import '../css/Auth/Login.css';
import '../css/Auth/Register.css';
import '../css/document.css';
import '../css/test.css';
import '../css/Auth/Forget_Password.css';
import '../css/Auth/Reset_password.css';
import '../css/note.css';


createApp(homepage).mount('#app');
createApp(Login).mount('#login');
createApp(Register).mount('#register');
createApp(document).mount('#document');
createApp(test).mount('#test');
createApp(Forget_Password).mount('#forget_password');
createApp(Reset_password).mount('#reset_password');
createApp(note).mount('#note');
