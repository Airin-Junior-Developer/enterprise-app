<template>
    <aside
        class="bg-white border-r border-slate-200 flex flex-col h-screen fixed left-0 top-0 z-50 transition-all duration-300"
        :class="isOpen ? 'w-64' : 'w-20'">

        <div class="h-16 flex items-center justify-between px-4 border-b border-slate-100 bg-white">
            <div v-if="isOpen" class="flex items-center overflow-hidden whitespace-nowrap">
                <div
                    class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold mr-3 shadow-lg shrink-0">
                    HR</div>
                <span class="text-xl font-extrabold text-slate-800 tracking-tight">HR System</span>
            </div>
            <button @click="$emit('toggle')"
                class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-blue-600 transition-colors focus:outline-none shrink-0"
                :class="{ 'mx-auto': !isOpen }">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">

            <router-link to="/"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">📊</span>
                <span v-if="isOpen" class="font-semibold text-sm">ภาพรวมระบบ</span>
            </router-link>

            <div v-if="isOpen && canManage"
                class="pt-4 pb-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">การจัดการข้อมูล
            </div>
            <div v-else-if="canManage" class="h-4 mt-4 mb-2"></div>

            <router-link v-if="canManage" to="/employees"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/employees' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">👥</span>
                <span v-if="isOpen" class="font-semibold text-sm">รายชื่อพนักงาน</span>
            </router-link>

            <router-link v-if="canManage" to="/branches"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/branches' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">🏢</span>
                <span v-if="isOpen" class="font-semibold text-sm">ข้อมูลสาขา</span>
            </router-link>

            <router-link v-if="isSuperAdmin" to="/positions"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/positions' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">💼</span>
                <span v-if="isOpen" class="font-semibold text-sm">ตำแหน่งงาน</span>
            </router-link>

<<<<<<< HEAD
            <router-link v-if="isSuperAdmin" to="/manage-positions"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 group overflow-hidden whitespace-nowrap mb-1 relative"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100 after:absolute after:left-0 after:w-1 after:h-6 after:bg-blue-600 after:rounded-r-full"
                :class="{ 'text-slate-500 hover:bg-slate-50 hover:text-blue-600': $route.path !== '/manage-positions' }">

                <span
                    class="text-xl shrink-0 transition-all duration-300 group-hover:scale-110 flex items-center justify-center"
                    :class="isOpen ? 'mr-3' : 'mx-auto w-10'">
                    <i class="fa-solid fa-sitemap text-[1.1rem]">🛠️</i>
                </span>

                <span v-if="isOpen" class="font-bold text-sm tracking-tight transition-opacity duration-300">
                    จัดการตำแหน่งงาน
                </span>

                <div v-if="!isOpen"
                    class="absolute left-14 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 whitespace-nowrap">
                    จัดการตำแหน่งงาน
                </div>
=======
            <router-link v-if="canManage" to="/manage-positions"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-blue-50 text-blue-600 shadow-sm shadow-blue-100"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/manage-positions' }">
                <span class="text-xl shrink-0 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'mr-3' : 'mx-auto'">🛠️</span>
                <span v-if="isOpen" class="font-semibold text-sm">จัดการตำแหน่งงาน</span>
>>>>>>> origin/sea
            </router-link>

            <div v-if="isOpen" class="pt-4 pb-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                คำร้อง & อนุมัติ</div>
            <div v-else class="h-4 border-t border-slate-100 mt-4 mb-2 mx-2"></div>

            <router-link to="/requests"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-blue-600 text-white shadow-lg shadow-blue-200"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/requests' }">
                <div class="p-1 rounded-lg shrink-0 transition-colors"
                    :class="[isOpen ? 'mr-2' : 'mx-auto', $route.path === '/requests' ? 'bg-white/20' : 'bg-transparent']">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span v-if="isOpen" class="font-medium text-sm">รายการคำร้อง</span>
            </router-link>

            <router-link v-if="canManage" to="/approvals"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-amber-100 text-amber-700 shadow-sm shadow-amber-100 border border-amber-200"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/approvals' }">
                <div class="p-1 rounded-lg shrink-0 transition-colors"
                    :class="[isOpen ? 'mr-2' : 'mx-auto', $route.path === '/approvals' ? 'bg-amber-500 text-white' : 'text-slate-400']">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span v-if="isOpen" class="font-medium text-sm">พิจารณาคำร้อง</span>
            </router-link>

            <router-link v-if="isSuperAdmin" to="/request-types"
                class="flex items-center px-3 py-2.5 rounded-xl transition-all group overflow-hidden whitespace-nowrap mb-1"
                active-class="bg-amber-100 text-amber-700 shadow-sm shadow-amber-100 border border-amber-200"
                :class="{ 'text-slate-600 hover:bg-slate-50 hover:text-slate-900': $route.path !== '/request-types' }">
                <div class="p-1 rounded-lg shrink-0 transition-colors"
                    :class="[isOpen ? 'mr-2' : 'mx-auto', $route.path === '/request-types' ? 'bg-amber-500 text-white' : 'text-slate-400']">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span v-if="isOpen" class="font-medium text-sm">ประเภทคำร้อง</span>
            </router-link>

        </nav>

        <div class="p-4 border-t border-slate-100 relative" ref="adminMenuRef">
            <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 translate-y-4"
                enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-2">
                <div v-if="isMenuOpen"
                    class="absolute bottom-20 bg-white border border-slate-200 rounded-2xl shadow-xl z-60 py-2 w-48"
                    :class="isOpen ? 'left-4 right-4 w-auto' : 'left-4'">
                    <button @click="handleLogout"
                        class="w-full flex items-center px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 font-medium transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0 transition-transform group-hover:-translate-x-1 mr-3" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        ออกจากระบบ
                    </button>
                </div>
            </transition>

            <button @click="toggleMenu" class="flex items-center rounded-xl hover:bg-slate-50 transition-colors w-full"
                :class="isOpen ? 'p-2' : 'justify-center p-1'">
                <div
                    class="h-10 w-10 bg-linear-to-tr from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold shadow-md shrink-0">
                    {{ currentUser?.first_name?.charAt(0) || 'U' }}
                </div>
                <div v-if="isOpen" class="ml-3 text-left overflow-hidden whitespace-nowrap">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ currentUser?.first_name || 'Admin User' }}
                    </p>
                    <p class="text-[11px] text-slate-500 truncate">{{ currentUser?.position?.position_name }}
                    </p>
                </div>
            </button>
        </div>
    </aside>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';

const router = useRouter();
const isMenuOpen = ref(false);
const adminMenuRef = ref(null);
const currentUser = ref(null);

defineProps({
    isOpen: {
        type: Boolean,
        default: true
    }
});
defineEmits(['toggle']);

onMounted(() => {
    const userStr = localStorage.getItem('user');
    if (userStr) {
        try {
            currentUser.value = JSON.parse(userStr);
        } catch (e) {
            console.error("Error parsing user data", e);
        }
    }
    document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => document.removeEventListener('mousedown', handleClickOutside));

const toggleMenu = () => isMenuOpen.value = !isMenuOpen.value;

const handleClickOutside = (event) => {
    if (adminMenuRef.value && !adminMenuRef.value.contains(event.target)) {
        isMenuOpen.value = false;
    }
};

// Sidebar.vue

const canManage = computed(() => {
    if (!currentUser.value || !currentUser.value.position) return false;

    // ดึงชื่อตำแหน่งออกมาล้างช่องว่างและทำเป็นตัวเล็กทั้งหมด
    const posName = currentUser.value.position.position_name.trim().toLowerCase();

    // ✅ ตรวจสอบชื่อให้ตรงกับคำว่า "super admin" (ตามใน DB ของคุณ)
    const allowedRoles = ['super admin', 'hr manager', 'system admin'];

    return allowedRoles.includes(posName);
});

const isSuperAdmin = computed(() => {
    if (!currentUser.value || !currentUser.value.position) return false;

    const posName = currentUser.value.position.position_name.trim().toLowerCase();

    // ✅ เช็คคำว่า "super admin"
    return posName === 'super admin' || posName === 'system admin';
});

const handleLogout = async () => {
    isMenuOpen.value = false;

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