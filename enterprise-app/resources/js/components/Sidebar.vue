<template>
    <aside
        class="bg-white border-r border-slate-200 flex flex-col h-screen fixed left-0 top-0 z-50 transition-all duration-300"
        :class="isOpen ? 'w-64' : 'w-20'">

        <div class="h-16 flex items-center justify-between px-4 border-b border-slate-100 bg-white">
            <div v-if="isOpen" class="flex items-center overflow-hidden whitespace-nowrap">
                <div
                    class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold mr-3 shadow-blue-200 shadow-lg shrink-0">
                    HR
                </div>
                <span class="text-xl font-extrabold text-slate-800 tracking-tight">HR System</span>
            </div>
            <button @click="$emit('toggle')"
                class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-blue-600 transition-colors focus:outline-none"
                :class="{ 'mx-auto': !isOpen }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">

            <router-link to="/"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-4"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">📊</span>
                <span v-if="isOpen" class="font-semibold text-sm">ภาพรวมระบบ</span>
            </router-link>

            <div v-if="isOpen && canManage"
                class="px-3 pt-2 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                การจัดการข้อมูล
            </div>
            <div v-else-if="canManage" class="h-4"></div>

            <router-link v-if="canManage" to="/employees"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/employees' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">👥</span>
                <span v-if="isOpen" class="font-semibold text-sm">รายชื่อพนักงาน</span>
            </router-link>

            <router-link v-if="canManage" to="/branches"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/branches' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">🏢</span>
                <span v-if="isOpen" class="font-semibold text-sm">ข้อมูลสาขา</span>
            </router-link>

            <router-link v-if="canManage" to="/positions"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/positions' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">💼</span>
                <span v-if="isOpen" class="font-semibold text-sm">ตำแหน่งงาน</span>
            </router-link>

            <div v-if="isOpen" class="px-3 pt-6 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                คำร้อง & อนุมัติ
            </div>
            <div v-else class="h-4 border-t border-slate-100 mt-4 mb-2 mx-2"></div>

            <router-link to="/requests"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-200"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/requests' }">
                <div class="p-1 rounded-lg shrink-0 transition-colors" :class="[
                    isOpen ? 'mr-2' : 'mx-auto',
                    $route.path === '/requests' ? 'bg-white/20' : 'bg-transparent'
                ]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span v-if="isOpen" class="font-medium text-sm">รายการคำร้อง</span>
            </router-link>

            <router-link v-if="canManage" to="/approvals"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mt-1"
                active-class="bg-amber-100 text-amber-700 shadow-sm shadow-amber-100 border border-amber-200"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/approvals' }">
                <div class="p-1 rounded-lg shrink-0 transition-colors" :class="[
                    isOpen ? 'mr-2' : 'mx-auto',
                    $route.path === '/approvals' ? 'bg-amber-500 text-white' : 'text-slate-400'
                ]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span v-if="isOpen" class="font-medium text-sm">พิจารณาคำร้อง</span>
            </router-link>

        </nav>

        <div class="p-4 border-t border-slate-100 bg-white flex flex-col gap-2">

            <div class="flex items-center rounded-xl bg-slate-50 border border-slate-100 overflow-hidden hover:bg-slate-100 transition-colors cursor-pointer"
                :class="isOpen ? 'p-3 gap-3' : 'p-2 justify-center'">
                <div
                    class="h-9 w-9 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-md">
                    {{ currentUser?.first_name?.charAt(0) || 'U' }}
                </div>
                <div v-if="isOpen" class="overflow-hidden whitespace-nowrap">
                    <p class="text-sm font-bold text-slate-700 truncate">{{ currentUser?.first_name }} {{
                        currentUser?.last_name }}</p>

                    <p class="text-xs text-slate-500 truncate">{{ currentUser?.position_name || 'พนักงานทั่วไป' }}</p>
                </div>
            </div>

            <button @click="handleLogout"
                class="flex items-center rounded-xl transition-all duration-200 text-slate-400 hover:bg-rose-50 hover:text-rose-600 group"
                :class="isOpen ? 'w-full px-3 py-2 gap-3 justify-center' : 'w-9 h-9 justify-center mx-auto'">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 transition-transform group-hover:-translate-x-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>

                <span v-if="isOpen" class="font-bold text-sm">ออกจากระบบ</span>
            </button>

        </div>
    </aside>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';

const router = useRouter();

defineProps({
    isOpen: {
        type: Boolean,
        default: true
    }
});
defineEmits(['toggle']);

const currentUser = ref(null);

onMounted(() => {
    const userStr = localStorage.getItem('user');
    if (userStr) {
        try {
            currentUser.value = JSON.parse(userStr);
        } catch (e) {
            console.error("Error parsing user data", e);
        }
    }
});

// ✅ แก้ไขจุดที่ 2: Logic เช็คสิทธิ์
const canManage = computed(() => {
    // ใช้ position_name ตรงๆ เพราะข้อมูลจาก ViewEmployee เป็น Flat Object
    if (!currentUser.value || !currentUser.value.position_name) return false;

    const posName = currentUser.value.position_name.trim().toLowerCase();
    const allowedRoles = ['system admin', 'hr manager'];

    return allowedRoles.includes(posName);
});

const handleLogout = async () => {
    const result = await Swal.fire({
        title: 'ยืนยันการออกจากระบบ?',
        text: "คุณต้องการออกจากระบบใช่หรือไม่",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'ใช่, ออกจากระบบ',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    });

    if (result.isConfirmed) {
        try {
            await axios.post('/api/logout');
        } catch (error) {
            console.error('Logout API Error:', error);
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            delete axios.defaults.headers.common['Authorization'];
            Swal.fire({
                icon: 'success',
                title: 'ออกจากระบบสำเร็จ',
                showConfirmButton: false,
                timer: 1000
            }).then(() => {
                router.push('/login');
            });
        }
    }
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #e2e8f0;
    border-radius: 10px;
}

.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
}
</style>