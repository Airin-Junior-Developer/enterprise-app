<!-- <template>
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
</script> -->

<template>
    <div class="min-h-screen flex items-center justify-center bg-slate-900">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <div
                    class="inline-flex h-12 w-12 bg-blue-600 rounded-xl items-center justify-center text-white font-bold text-xl mb-4 shadow-lg shadow-blue-200">
                    HR
                </div>
                <h1 class="text-3xl font-bold text-slate-800">HR System</h1>
                <p class="text-slate-500 mt-2">เข้าสู่ระบบเพื่อจัดการงานบุคคล</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" v-model="email" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="กรอกอีเมลของคุณ">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" v-model="password" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="••••••••">
                </div>

                <div v-if="error"
                    class="flex items-center gap-2 text-rose-600 text-sm bg-rose-50 p-3 rounded-lg border border-rose-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ error }}
                </div>

                <button type="submit" :disabled="isLoading"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold hover:bg-blue-700 transition-all flex justify-center shadow-lg shadow-blue-200 active:scale-95">
                    <span v-if="isLoading" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        กำลังตรวจสอบ...
                    </span>
                    <span v-else>เข้าสู่ระบบ</span>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

// กำหนดค่าเริ่มต้นเป็นว่าง '' เพื่อให้ผู้ใช้กรอกเอง
// ข้อมูลจะถูกส่งไปตรวจสอบกับ Database ผ่าน API /api/login
const email = ref('');
const password = ref('');
const error = ref('');
const isLoading = ref(false);
const router = useRouter();

const handleLogin = async () => {
    isLoading.value = true;
    error.value = '';

    try {
        // Axios จะส่งค่าที่ user พิมพ์ (email.value, password.value) ไปให้ Backend ตรวจสอบกับ DB
        const res = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        });

        // ถ้า Login ผ่าน (Database ตอบว่าใช่)
        const token = res.data.token;
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(res.data.user));

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        router.push('/');

    } catch (e) {
        // ถ้าไม่ผ่าน (เช่น รหัสผิด หรือไม่มีอีเมลนี้ใน DB)
        if (e.response && e.response.status === 401) {
            error.value = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
        } else {
            error.value = e.response?.data?.message || 'เกิดข้อผิดพลาดในการเชื่อมต่อระบบ';
        }
    } finally {
        isLoading.value = false;
    }
};
</script>