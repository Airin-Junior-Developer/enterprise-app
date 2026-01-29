import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import App from './App.vue';
import axios from 'axios'; // Import axios มาด้วย

const app = createApp(App);

// --- ส่วนจัดการ Token ---
const token = localStorage.getItem('token');
if (token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// ✅ เพิ่มโค้ดชุดนี้: ดักจับ Error 401 (กุญแจผิด/หมดอายุ)
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            // ถ้า Server บอกว่า Unauthorized (401)
            // ให้ลบ Token ทิ้ง แล้วถีบไปหน้า Login
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);
// -----------------------

app.use(router);
app.mount('#app');