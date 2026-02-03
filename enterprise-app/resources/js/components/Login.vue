<template>
    <div class="min-h-screen flex items-center justify-center bg-[#0F172A] p-4 font-sans">

        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-[400px] animate-fade-in-up">

            <div class="flex flex-col items-center mb-8">
                <div
                    class="h-12 w-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-200 mb-4">
                    HR
                </div>
                <h1 class="text-2xl font-bold text-slate-800">HR System</h1>
                <p class="text-sm text-slate-500 mt-1">เข้าสู่ระบบเพื่อจัดการงานบุคคล</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-5">

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Email</label>
                    <input type="email" v-model="form.email" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none text-slate-600 text-sm transition-all"
                        placeholder="name@company.com" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Password</label>
                    <input type="password" v-model="form.password" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none text-slate-600 text-sm transition-all"
                        placeholder="••••••••" />
                </div>

                <div v-if="errorMessage"
                    class="bg-rose-50 border border-rose-100 text-rose-600 text-xs p-3 rounded-lg flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 flex-shrink-0" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ errorMessage }}</span>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95 flex justify-center items-center"
                    :disabled="isLoading">
                    <span v-if="isLoading" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        กำลังเข้าสู่ระบบ...
                    </span>
                    <span v-else>เข้าสู่ระบบ</span>
                </button>

            </form>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = ref({ email: '', password: '' });
const errorMessage = ref('');
const isLoading = ref(false);

const handleLogin = async () => {
    errorMessage.value = '';
    isLoading.value = true;

    try {
        // ยิง API Login (ต้องตรวจสอบว่า Route ตรงกับ backend ไหม)
        const res = await axios.post('/api/login', form.value);

        // ถ้า Login ผ่าน
        if (res.data.token) {
            // เก็บ Token และข้อมูล User
            localStorage.setItem('token', res.data.token);
            localStorage.setItem('user', JSON.stringify(res.data.user));

            // ตั้งค่า Header ให้ Axios
            axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`;

            // Redirect ไปหน้า Dashboard หรือหน้าแรก
            router.push('/');
        }
    } catch (error) {
        // จัดการ Error
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage.value = error.response.data.message;
        } else {
            errorMessage.value = 'เกิดข้อผิดพลาดในการเชื่อมต่อระบบ';
        }
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
/* Animation เล็กน้อยตอนโหลดหน้า */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.5s ease-out;
}
</style>