<template>
    <div class="px-8 py-10 bg-[#F4F6F8] min-h-screen font-sans">
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-[32px] font-bold text-slate-800 tracking-tight leading-tight">ภาพรวมระบบ</h1>
                <p class="text-[15px] font-medium text-slate-500 mt-1">สรุปข้อมูลพนักงานและสถานะคำร้องล่าสุดภายในองค์กร
                </p>
            </div>

            <div v-if="stats?.last_updated"
                class="flex items-center gap-2 text-xs font-semibold text-slate-400 bg-white px-3 py-1.5 rounded-full border border-slate-200/60 shadow-sm">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                อัปเดตล่าสุด: {{ formatDate(stats.last_updated) }}
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            <div
                class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">พนักงานทั้งหมด</span>
                        <span class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ stats.employees }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Requests -->
            <div
                class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">คำร้องทั้งหมด</span>
                        <span class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ stats.requests_total
                            }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div
                class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">รออนุมัติ</span>
                        <span class="text-4xl font-extrabold text-amber-500 tracking-tight">{{ stats.requests_pending
                            }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white group-hover:border-amber-500 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Approved Requests -->
            <div
                class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-500 mb-1">อนุมัติแล้ว</span>
                        <span class="text-4xl font-extrabold text-emerald-600 tracking-tight">{{ stats.requests_approved
                            }}</span>
                    </div>
                    <div
                        class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white group-hover:border-emerald-500 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Table -->
        <div
            class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-[17px] font-bold text-slate-800">คำร้องล่าสุด (Recent Activities)</h3>
                </div>
                <router-link to="/requests"
                    class="text-[13px] font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                    ดูรายการทั้งหมด
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </router-link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                พนักงาน</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                ประเภทคำร้อง</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                วันที่ยื่น</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">
                                สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="req in recentRequests" :key="req.request_id"
                            class="hover:bg-slate-50/70 transition-colors group">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs border border-white shadow-sm">
                                        {{ req.requester_first_name ? req.requester_first_name.charAt(0) : '?' }}
                                    </div>
                                    <div class="font-bold text-slate-700">{{ req.requester_first_name }} {{
                                        req.requester_last_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-600">{{ req.request_type }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-[13px] font-medium text-slate-500">{{ formatDate(req.created_at)
                                    }}</span>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span :class="statusBadgeClass(req.status)"
                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold border inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(req.status)"></span>
                                    {{ getStatusText(req.status) }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="recentRequests.length === 0">
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-4 text-slate-300"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="font-medium text-[14px]">ยังไม่มีรายการคำร้องในระบบ</p>
                                </div>
                            </td>
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
        // เพิ่มระบบเด้งแจ้งเตือน (เช็คจากตัวแปรที่ส่งมาจาก Controller)
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
                    // 1. ถ้ายอมรับแล้ว ให้ยิง API ไปปิดสวิตช์ในฐานข้อมูลเป็น 0
                    axios.post('/api/clear-expired-alert').catch(err => console.error(err));

                    // 2. เคลียร์ข้อมูลการล็อกอินเดิมทิ้งก่อน (สำคัญมาก!)
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');

                    router.replace('/login');
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