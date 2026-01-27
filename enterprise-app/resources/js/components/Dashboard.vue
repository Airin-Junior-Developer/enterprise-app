<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">ภาพรวมระบบ (Dashboard)</h1>
            <p class="text-slate-500 mt-1">สรุปข้อมูลสถิติและการเคลื่อนไหวล่าสุด</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">พนักงานทั้งหมด</p>
                    <h2 class="text-3xl font-bold text-blue-600">{{ stats.employees }}</h2>
                </div>
                <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                    👥
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">สาขาที่เปิดใช้งาน</p>
                    <h2 class="text-3xl font-bold text-emerald-600">{{ stats.branches }}</h2>
                </div>
                <div
                    class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl">
                    🏢
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">ตำแหน่งงาน</p>
                    <h2 class="text-3xl font-bold text-indigo-600">{{ stats.positions }}</h2>
                </div>
                <div
                    class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-xl">
                    💼
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">สถานะระบบ</p>
                    <h2 class="text-lg font-bold text-green-500 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                        Online
                    </h2>
                </div>
                <div class="h-12 w-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-xl">
                    ⚡
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">พนักงานที่เพิ่มล่าสุด (Recent Added)</h3>
                <router-link to="/employees" class="text-sm text-blue-600 hover:text-blue-800 font-medium">ดูทั้งหมด
                    →</router-link>
            </div>
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">ตำแหน่ง</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">สาขา</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">วันที่เพิ่ม</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="emp in recentEmployees" :key="emp.id" class="hover:bg-slate-50/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">
                                    {{ emp.first_name ? emp.first_name.charAt(0) : '?' }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ emp.first_name }} {{ emp.last_name
                                }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ emp.position ? emp.position.name : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ emp.branch ? emp.branch.name : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500 text-right">{{ emp.created_at ? new
                            Date(emp.created_at).toLocaleDateString('th-TH') : '-' }}</td>
                    </tr>
                    <tr v-if="recentEmployees.length === 0">
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">ยังไม่มีข้อมูลพนักงาน</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// เก็บข้อมูลสถิติ
const stats = ref({
    employees: 0,
    branches: 0,
    positions: 0,
    pending: 0
});

// เก็บรายชื่อพนักงานล่าสุด
const recentEmployees = ref([]);

// ฟังก์ชันดึงข้อมูลจาก API
const fetchDashboardData = async () => {
    try {
        const res = await axios.get('/api/dashboard/stats');
        if (res.data) {
            stats.value = res.data.summary || stats.value;
            recentEmployees.value = res.data.recent_employees || [];
        }
    } catch (error) {
        console.error("Error loading dashboard:", error);
    }
};

onMounted(() => {
    fetchDashboardData();
});
</script>