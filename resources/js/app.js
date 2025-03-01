import { createApp } from 'vue';
import homepage from './components/homepage.vue';
import Login from './components/Auth/Login.vue'
import Register from './components/Auth/Resgister.vue'
import document from './components/document.vue';
import test from './components/test.vue';
import testdoc from './components/testdoc.vue';
import profile from './components/profile.vue';


import '../css/homepage.css';
import '../css/Auth/Login.css';
import '../css/Auth/Register.css';
import '../css/document.css';
import '../css/test.css';
import '../css/testdoc.css';
import '../css/profile.css';


createApp(homepage).mount('#app');
createApp(Login).mount('#login');
createApp(Register).mount('#register');
createApp(document).mount('#document');
createApp(test).mount('#test');
createApp(testdoc).mount('#testdoc')
createApp(profile).mount('#profile')
