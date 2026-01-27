import './bootstrap';
import { createApp } from 'vue';
import router from './router'; // เรียกใช้ Router ที่เราสร้างไว้
import App from './App.vue';   // เรียกใช้ Layout หลัก (ที่มี Sidebar)

// สร้าง App โดยใช้ App.vue เป็นตัวตั้งต้น
const app = createApp(App);

// ใช้งาน Router
app.use(router);

// สั่งทำงานที่ <div id="app">
app.mount('#app');