<template>
    <div class="flex min-h-screen bg-slate-50 font-sans overflow-x-hidden">

        <Sidebar v-if="!isLoginPage" :isOpen="isSidebarOpen" @toggle="toggleSidebar" />

        <main :class="mainClass">
            <router-view v-slot="{ Component }">
                <transition name="fade" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
        </main>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';
import Sidebar from './components/Sidebar.vue';

const route = useRoute();
const isSidebarOpen = ref(true);

// เช็คว่าเป็นหน้า Login หรือไม่
const isLoginPage = computed(() => route.path === '/login');

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

// คำนวณคลาสของ Main Content แบบ Dynamic
const mainClass = computed(() => {
    if (isLoginPage.value) return 'w-full min-h-screen';

    return [
        'flex-1 p-4 md:p-6 transition-all duration-300 ease-in-out min-w-0',
        isSidebarOpen.value ? 'md:ml-64' : 'md:ml-20'
    ];
});
</script>

<style>
/* เพิ่ม Transition เล็กน้อยให้การเปลี่ยนหน้าดูนุ่มนวลขึ้น */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* ลบ Scrollbar แนวนอนที่อาจเกิดจาก margin */
body {
    overflow-x: hidden;
}
</style>