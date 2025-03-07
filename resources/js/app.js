import { createApp } from 'vue';
import homepage from './components/homepage.vue';
import calendar from './components/calendar.vue';
import document from './components/document.vue';
import Login from './components/Auth/Login.vue'
import Register from './components/Auth/Resgister.vue'
import Profile from './components/Auth/ProfileUser.vue';
import Reset_password from './components/Auth/Reset_password.vue';
import test from './components/TestFile/test.vue';
import Testdoc from './components/TestFile/TestDoc.vue';

import '../css/homepage.css';
import '../css/Auth/Login.css';
import '../css/Auth/Register.css';
import '../css/document.css';
import '../css/TestFile/test.css';
import '../css/Auth/Reset_password.css';
import '../css/TestFile/testdoc.css'
import '../css/Auth/ProfileUser.css';
import '../css/calendar.css';

createApp(homepage).mount('#app');
createApp(Login).mount('#login');
createApp(Register).mount('#register');
createApp(Reset_password).mount('#reset_password');
createApp(document).mount('#document');
createApp(test).mount('#test');
createApp(Testdoc).mount('#testdoc');
createApp(Profile).mount('#profile');
createApp(calendar).mount('#calendar');