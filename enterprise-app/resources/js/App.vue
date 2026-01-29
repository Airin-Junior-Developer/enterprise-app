<template>
    <div class="flex min-h-screen bg-slate-50 font-sans">

        <Sidebar v-if="$route.path !== '/login'" :isOpen="isSidebarOpen" @toggle="toggleSidebar" />

        <main :class="[
            $route.path === '/login' ? 'w-full' : 'flex-1 p-6 transition-all duration-300',
            ($route.path !== '/login' && isSidebarOpen) ? 'ml-64' : '',
            ($route.path !== '/login' && !isSidebarOpen) ? 'ml-20' : ''
        ]">
            <router-view></router-view>
        </main>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from './components/Sidebar.vue';

// ตัวแปรควบคุมการเปิด/ปิด (ค่าเริ่มต้นคือ เปิด = true)
const isSidebarOpen = ref(true);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>