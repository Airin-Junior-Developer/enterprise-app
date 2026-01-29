<template>
    <div class="min-h-screen flex items-center justify-center bg-slate-900">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-800">HR System</h1>
                <p class="text-slate-500">เข้าสู่ระบบเพื่อจัดการงานบุคคล</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" v-model="email" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="admin@enterprise.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" v-model="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="••••••••">
                </div>

                <div v-if="error" class="text-red-500 text-sm text-center bg-red-50 p-2 rounded">
                    {{ error }}
                </div>

                <button type="submit" :disabled="isLoading"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold hover:bg-blue-700 transition-all flex justify-center">
                    <span v-if="isLoading">กำลังตรวจสอบ...</span>
                    <span v-else>เข้าสู่ระบบ</span>
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-400">
                Demo Account: admin@enterprise.com / 12345678
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const email = ref('admin@enterprise.com'); // ใส่ค่า Default ไว้เทสต์ง่ายๆ
const password = ref('12345678');
const error = ref('');
const isLoading = ref(false);
const router = useRouter();

const handleLogin = async () => {
    isLoading.value = true;
    error.value = '';

    try {
        // 1. ยิง API Login
        const res = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        });

        // 2. เก็บ Token ไว้ใน LocalStorage (เหมือนกระเป๋าตังค์)
        const token = res.data.token;
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(res.data.user));

        // 3. ตั้งค่า Axios ให้ถือ Token นี้ไปทุกครั้งที่ยิง API
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // 4. ไปหน้าแรก
        router.push('/');

    } catch (e) {
        error.value = e.response?.data?.message || 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ';
    } finally {
        isLoading.value = false;
    }
};
</script>