<template>
    <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4 relative overflow-hidden font-sans">

        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#60A5FA 1px, transparent 1px); background-size: 30px 30px;">
        </div>

        <div class="max-w-4xl w-full bg-slate-50 rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden
        flex flex-col md:flex-row min-h-[550px] relative z-10 border border-white/20">


            <div class="md:w-1/2 p-8 flex items-center justify-center">
                <div
                    class="w-full h-full bg-slate-800 rounded-4xl flex flex-col items-center justify-center text-center p-10 relative shadow-2xl border border-slate-700">
                    <div class="absolute left-6 top-1/4 w-1.5 h-20 bg-yellow-500 rounded-full"></div>

                    <div class="mb-6 text-blue-400/80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>


                    <h1 class="text-white text-3xl font-black tracking-tighter mb-2">HR SYSTEM</h1>
                    <p class="text-yellow-500 text-xs font-bold tracking-[0.4em] uppercase">ระบบบริหารจัดการบุคลากร
                    </p>
                    <div class="w-12 h-1 bg-yellow-600 mt-6 rounded-full"></div>
                </div>
            </div>

            <div class="md:w-1/2 p-10 md:p-14 flex flex-col justify-center">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-slate-800 text-2xl font-bold tracking-tight">Login to account</h2>
                    <p class="text-slate-500 text-sm mt-1">กรุณากรอกข้อมูลเพื่อเข้าใช้งาน</p>
                </div>


                <form @submit.prevent="handleLogin" class="space-y-6">
                    <div class="space-y-2">
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Email</label>
                        <input v-model="form.email" type="email" required placeholder="name@company.com"
                            class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-slate-800 shadow-sm" />
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative">
                            <input v-model="form.password" :type="showPassword ? 'text' : 'password'" required
                                placeholder="••••••••"
                                class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-slate-800 shadow-sm pr-14" />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none">
                                <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div v-if="errorMessage"
                        class="bg-red-50 text-red-600 text-xs p-3 rounded-xl border border-red-100 animate-pulse font-bold flex items-center gap-2">
                        <span>⚠️</span>
                        <span>{{ errorMessage }}</span>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="isLoading"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.97] flex items-center justify-center space-x-2 disabled:opacity-75 disabled:cursor-not-allowed">
                            <span v-if="isLoading" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังเข้าสู่ระบบ...
                            </span>
                            <span v-else>เข้าสู่ระบบ</span>
                        </button>
                    </div>

                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">หรือ</span>
                        </div>
                    </div>

                    <a
                        href="/auth/google/redirect"
                        class="w-full flex items-center justify-center gap-3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        เข้าสู่ระบบด้วย Google
                    </a>
                </form>

                <div class="mt-16 text-center pt-6 border-t border-slate-200">
                    <p class="text-slate-400 text-xs font-bold tracking-widest uppercase">
                        © 2026 HR System Management
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const showPassword = ref(false);
const form = ref({ email: '', password: '' });
const errorMessage = ref('');
const isLoading = ref(false);

// ตรวจสอบว่าถ้า Login อยู่แล้วให้เด้งไปหน้า Dashboard เลย
onMounted(() => {
    if (localStorage.getItem('token')) {
        router.push('/');
    }
});

const handleLogin = async () => {
    errorMessage.value = '';
    isLoading.value = true;

    try {
        // 1. เรียก API Login
        const res = await axios.post('/api/login', form.value);

        if (res.data.token) {
            // 2. เก็บข้อมูลลง Storage
            localStorage.setItem('token', res.data.token);
            localStorage.setItem('user', JSON.stringify(res.data.user));

            // 3. ตั้งค่า Header สำหรับ Request ต่อๆ ไป
            axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`;

            // 4. ไปหน้าแรก
            router.push('/').then(() => {
                // อัปเดตหน้าจอหนึ่งครั้งเพื่อให้ Component อื่นๆ รับรู้ค่า User ใหม่
                window.location.reload();
            });
        }
    } catch (error) {
        console.error('Login Failed:', error);
        if (error.response) {
            // กรณีรหัสผิด หรือ Validation ไม่ผ่าน
            errorMessage.value = error.response.data.message || 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
        } else {
            // กรณี Server ล่ม หรือเชื่อมต่อไม่ได้
            errorMessage.value = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้';
        }
    } finally {
        isLoading.value = false;
    }
};
</script>