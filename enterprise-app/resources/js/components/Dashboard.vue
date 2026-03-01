<template>
    <div class="p-6 bg-[#F8F9FD] min-h-screen font-sans text-slate-800">

        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">ภาพรวมระบบ (Dashboard)</h1>
            <p class="text-sm text-slate-500 mt-1">สรุปข้อมูลพนักงานและสถานะคำร้องล่าสุด</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div
                class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                <div class="flex justify-between items-start relative z-10">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">พนักงานทั้งหมด</span>
                        <span class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ stats?.employees || 0
                        }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1.5 bg-indigo-500"></div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                <div class="flex justify-between items-start relative z-10">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">คำร้องทั้งหมด</span>
                        <span class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ stats?.requests_total ||
                            0 }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1.5 bg-blue-500"></div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                <div class="flex justify-between items-start relative z-10">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">รออนุมัติ</span>
                        <span class="text-4xl font-extrabold text-amber-500 tracking-tight">{{ stats?.requests_pending
                            || 0 }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1.5 bg-amber-400"></div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                <div class="flex justify-between items-start relative z-10">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">อนุมัติแล้ว</span>
                        <span class="text-4xl font-extrabold text-emerald-600 tracking-tight">{{
                            stats?.requests_approved || 0 }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1.5 bg-emerald-500"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
                <h3 class="text-lg font-bold text-slate-700">รายการล่าสุด (Recent Activities)</h3>
                <router-link to="/requests" class="text-sm text-blue-600 hover:text-blue-800 font-medium">ดูทั้งหมด
                    →</router-link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-slate-50/80 text-slate-500 font-bold border-b border-slate-100 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">พนักงาน</th>
                            <th class="px-6 py-4">ประเภท</th>
                            <th class="px-6 py-4">วันที่ยื่น</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="req in recentRequests" :key="req.request_id"
                            class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs border border-white shadow-sm">
                                        {{ req.first_name ? req.first_name.charAt(0) : '?' }}
                                    </div>
                                    <div class="font-bold text-slate-700">{{ req.first_name }} {{ req.last_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-600">{{ req.request_type_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs font-mono">
                                {{ formatDate(req.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span :class="statusBadgeClass(req.status)"
                                    class="px-3 py-1 rounded-full text-[10px] font-bold border inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(req.status)"></span>
                                    {{ getStatusText(req.status) }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="recentRequests.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">ยังไม่มีรายการคำร้อง</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import Swal from 'sweetalert2';
import axios from 'axios';
import { ref, onMounted } from 'vue';

// 1. กำหนดค่าเริ่มต้นเพื่อป้องกัน undefined
const stats = ref({
    employees: 0,
    requests_total: 0,
    requests_pending: 0,
    requests_approved: 0
});
const recentRequests = ref([]);

const fetchData = async () => {
    try {
        const res = await axios.get('/api/dashboard');
        stats.value = res.data.stats;
        recentRequests.value = res.data.recent_requests;
        // ✅ เพิ่มระบบเด้งแจ้งเตือน (เช็คจากตัวแปรที่ส่งมาจาก Controller)
        if (res.data.is_notify_expired == 1) {
            Swal.fire({
                title: 'แจ้งเตือนระบบ',
                text: 'ระยะเวลาการรักษาการของคุณสิ้นสุดลงแล้ว ระบบได้ปรับเข้าสู่ตำแหน่งปกติ',
                icon: 'info',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#2563eb',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // ถ้ายอมรับแล้ว ให้ยิง API ไปปิดสวิตช์ในฐานข้อมูลเป็น 0
                    axios.post('/api/clear-expired-alert').catch(err => console.error(err));
                }
            });
        }
    } catch (e) {
        console.error('Dashboard Error:', e);
        // ไม่ต้องทำอะไร ปล่อยให้ใช้ค่า Default (0) ไป หน้าจอจะได้ไม่ขาว
    }
};

const statusBadgeClass = (status) => {
    switch (status) {
        case 'Approved': return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'Rejected': return 'bg-rose-50 text-rose-700 border-rose-200';
        default: return 'bg-amber-50 text-amber-700 border-amber-200';
    }
};

const statusDotClass = (status) => {
    switch (status) {
        case 'Approved': return 'bg-emerald-500';
        case 'Rejected': return 'bg-rose-500';
        default: return 'bg-amber-500';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'Approved': return 'อนุมัติแล้ว';
        case 'Rejected': return 'ไม่อนุมัติ';
        default: return 'รออนุมัติ';
    }
};

const formatDate = (d) => {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('th-TH', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

onMounted(() => {
    fetchData();
});
</script>