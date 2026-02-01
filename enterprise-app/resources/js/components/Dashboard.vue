<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">ภาพรวมระบบ (Dashboard)</h1>
            <p class="text-slate-500 mt-1 text-base">สรุปข้อมูลสถิติและการเคลื่อนไหวล่าสุด</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">พนักงานทั้งหมด</p>
                    <h2 class="text-4xl font-extrabold text-blue-600">{{ stats.employees_count }}</h2>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                    👥
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">สาขาที่เปิดใช้งาน</p>
                    <h2 class="text-4xl font-extrabold text-emerald-600">{{ stats.branches_count }}</h2>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                    🏢
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">ตำแหน่งงาน</p>
                    <h2 class="text-4xl font-extrabold text-indigo-600">{{ stats.positions_count }}</h2>
                </div>
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
                    💼
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">สถานะระบบ</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <h2 class="text-xl font-bold text-slate-800">{{ stats.system_status }}</h2>
                    </div>
                </div>
                <div class="h-12 w-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl">
                    ⚡
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">พนักงานที่เพิ่มล่าสุด (Recent Added)</h3>
                <router-link to="/employees" class="text-sm text-blue-600 font-bold hover:underline">ดูทั้งหมด →</router-link>
            </div>
            
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">ตำแหน่ง</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">สาขา</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">วันที่เพิ่ม</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="emp in stats.recent_employees" :key="emp.user_id" class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm mr-3">
                                    {{ emp.first_name.charAt(0) }}
                                </div>
                                <div class="font-bold text-slate-700">{{ emp.first_name }} {{ emp.last_name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ emp.position ? emp.position.position_name : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                {{ emp.branch ? emp.branch.branch_name : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-slate-500">
                            {{ formatDate(emp.created_at) }}
                        </td>
                    </tr>
                    <tr v-if="stats.recent_employees.length === 0">
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">ยังไม่มีข้อมูลพนักงาน</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// State เก็บข้อมูล Dashboard
const stats = ref({
    employees_count: 0,
    branches_count: 0,
    positions_count: 0,
    system_status: 'Offline',
    recent_employees: []
});

// ฟังก์ชันดึงข้อมูลจาก API
const fetchDashboardData = async () => {
    try {
        const res = await axios.get('/api/dashboard');
        stats.value = res.data;
    } catch (e) {
        console.error("Error fetching dashboard:", e);
    }
};

// ฟังก์ชันแปลงวันที่เป็นแบบไทย
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('th-TH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

onMounted(() => {
    fetchDashboardData();
});
</script>