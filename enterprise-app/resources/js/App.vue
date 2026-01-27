<template>
    <div class="flex h-screen bg-slate-50 overflow-hidden">
        <Sidebar />

        <main class="flex-1 ml-64 overflow-y-auto h-screen">
            <router-view></router-view>
        </main>
    </div>
</template>

<script setup>
// นำเข้า Sidebar จากโฟลเดอร์ components
import Sidebar from './components/Sidebar.vue';
</script>