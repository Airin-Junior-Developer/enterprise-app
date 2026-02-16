<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-800">

        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">พิจารณาอนุมัติ (Approval)</h2>
                <p class="text-sm text-slate-500">ตรวจสอบและจัดการคำร้องขอจากพนักงาน</p>
            </div>
            <button @click="fetchData"
                class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2 font-medium text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                รีโหลดข้อมูล
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <div @click="setFilter('pending')"
                class="cursor-pointer p-5 rounded-xl border transition-all duration-200 flex items-center justify-between group relative overflow-hidden"
                :class="currentStatusFilter === 'pending' ? 'bg-amber-50 border-amber-500 ring-1 ring-amber-500 shadow-md' : 'bg-white border-slate-100 hover:border-amber-300 hover:shadow-sm'">
                <div class="relative z-10">
                    <p class="text-sm font-medium mb-1"
                        :class="currentStatusFilter === 'pending' ? 'text-amber-700' : 'text-slate-500'">รออนุมัติ</p>
                    <h3 class="text-3xl font-bold"
                        :class="currentStatusFilter === 'pending' ? 'text-amber-900' : 'text-amber-500'">{{
                            stats.pending }}</h3>
                </div>
                <div class="h-12 w-12 rounded-full flex items-center justify-center transition-colors relative z-10"
                    :class="currentStatusFilter === 'pending' ? 'bg-amber-200 text-amber-700' : 'bg-amber-50 text-amber-500 group-hover:bg-amber-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div @click="setFilter(null)"
                class="cursor-pointer p-5 rounded-xl border transition-all duration-200 flex items-center justify-between group relative overflow-hidden"
                :class="currentStatusFilter === null ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500 shadow-md' : 'bg-white border-slate-100 hover:border-blue-300 hover:shadow-sm'">
                <div class="relative z-10">
                    <p class="text-sm font-medium mb-1"
                        :class="currentStatusFilter === null ? 'text-blue-700' : 'text-slate-500'">ทั้งหมด</p>
                    <h3 class="text-3xl font-bold"
                        :class="currentStatusFilter === null ? 'text-blue-900' : 'text-slate-800'">{{ stats.total }}
                    </h3>
                </div>
                <div class="h-12 w-12 rounded-full flex items-center justify-center transition-colors relative z-10"
                    :class="currentStatusFilter === null ? 'bg-blue-200 text-blue-700' : 'bg-blue-50 text-blue-600 group-hover:bg-blue-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            <div @click="setFilter('approved')"
                class="cursor-pointer p-5 rounded-xl border transition-all duration-200 flex items-center justify-between group"
                :class="currentStatusFilter === 'approved' ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500 shadow-md' : 'bg-white border-slate-100 hover:border-emerald-300 hover:shadow-sm'">
                <div>
                    <p class="text-sm font-medium mb-1"
                        :class="currentStatusFilter === 'approved' ? 'text-emerald-700' : 'text-slate-500'">อนุมัติแล้ว
                    </p>
                    <h3 class="text-3xl font-bold"
                        :class="currentStatusFilter === 'approved' ? 'text-emerald-900' : 'text-emerald-600'">{{
                            stats.approved }}</h3>
                </div>
                <div class="h-12 w-12 rounded-full flex items-center justify-center transition-colors"
                    :class="currentStatusFilter === 'approved' ? 'bg-emerald-200 text-emerald-700' : 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div @click="setFilter('rejected')"
                class="cursor-pointer p-5 rounded-xl border transition-all duration-200 flex items-center justify-between group"
                :class="currentStatusFilter === 'rejected' ? 'bg-rose-50 border-rose-500 ring-1 ring-rose-500 shadow-md' : 'bg-white border-slate-100 hover:border-rose-300 hover:shadow-sm'">
                <div>
                    <p class="text-sm font-medium mb-1"
                        :class="currentStatusFilter === 'rejected' ? 'text-rose-700' : 'text-slate-500'">ไม่อนุมัติ</p>
                    <h3 class="text-3xl font-bold"
                        :class="currentStatusFilter === 'rejected' ? 'text-rose-900' : 'text-rose-500'">{{
                            stats.rejected }}</h3>
                </div>
                <div class="h-12 w-12 rounded-full flex items-center justify-center transition-colors"
                    :class="currentStatusFilter === 'rejected' ? 'bg-rose-200 text-rose-700' : 'bg-rose-50 text-rose-500 group-hover:bg-rose-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative grow">
                    <input type="text" v-model="searchQuery"
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all"
                        placeholder="ค้นหาชื่อพนักงาน, รหัส หรือประเภท..." />
                    <svg class="h-5 w-5 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <select v-model="filterYear"
                    class="w-full md:w-40 border border-slate-200 rounded-lg px-3 py-2.5 bg-white text-slate-600 outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">ทุกปี</option>
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
                <button @click="resetFilters"
                    class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-lg font-medium hover:bg-slate-200 transition-colors">ล้างตัวกรอง</button>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">ลำดับ</th>
                            <th class="px-6 py-4">พนักงาน (Requester)</th>
                            <th class="px-6 py-4">รายละเอียด</th>
                            <th class="px-6 py-4">วัน-เวลา / ยอดเงิน</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                            <th class="px-6 py-4 text-center w-40">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="(req, index) in paginatedRequests" :key="req.request_id"
                            class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center text-slate-400">{{ (currentPage - 1) * itemsPerPage + index
                                + 1 }}</td>

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs mr-3 border-2 border-white shadow-sm">
                                        {{ req.requester_first_name ? req.requester_first_name.charAt(0) : '?' }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-700">{{ req.requester_first_name }} {{
                                            req.requester_last_name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ req.requester_position || 'พนักงาน'
                                        }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-700">{{ req.request_type_name }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-[200px]" :title="req.reason">{{
                                    req.reason || '-' }}</div>
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                <div v-if="req.start_date">
                                    <div
                                        class="text-[11px] bg-slate-100 px-2 py-0.5 rounded inline-block text-slate-500 mb-1">
                                        เริ่ม: {{ formatDate(req.start_date) }}</div>
                                    <div class="text-[11px] text-slate-400">ถึง: {{ formatDate(req.end_date) }}</div>
                                </div>
                                <div v-else-if="req.amount" class="text-blue-600 font-bold font-mono">
                                    {{ Number(req.amount).toLocaleString() }} THB
                                </div>
                                <div v-else class="text-slate-300">-</div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span :class="statusBadgeClass(req.status)"
                                    class="px-3 py-1 rounded-full text-xs font-bold border">
                                    {{ getStatusText(req.status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div v-if="req.status === 'Pending'" class="flex items-center justify-center gap-2">
                                    <button @click="updateStatus(req.request_id, 'approved')"
                                        class="h-8 px-3 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 text-xs font-bold transition-colors flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        อนุมัติ
                                    </button>
                                    <button @click="updateStatus(req.request_id, 'rejected')"
                                        class="h-8 px-3 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200 text-xs font-bold transition-colors flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        ไม่
                                    </button>
                                </div>
                                <div v-else class="text-xs text-slate-400 italic">ดำเนินการแล้ว</div>
                            </td>
                        </tr>
                        <tr v-if="paginatedRequests.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-3xl mb-2">📄</span>
                                    <span>ไม่พบรายการคำร้องตามเงื่อนไขที่เลือก</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="text-xs text-slate-500">
                    แสดง {{ paginatedRequests.length > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0 }}
                    ถึง {{ Math.min(currentPage * itemsPerPage, filteredRequests.length) }}
                    จาก {{ filteredRequests.length }} รายการ
                </div>
                <div class="flex gap-1">
                    <button @click="currentPage--" :disabled="currentPage === 1"
                        class="px-3 py-1 border rounded hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed text-xs text-slate-600 transition-colors">ก่อนหน้า</button>
                    <span class="px-3 py-1 border bg-blue-600 text-white rounded text-xs font-bold">{{ currentPage
                    }}</span>
                    <button @click="currentPage++" :disabled="currentPage >= totalPages"
                        class="px-3 py-1 border rounded hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed text-xs text-slate-600 transition-colors">ถัดไป</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted, computed, watch } from 'vue';

const requests = ref([]);
const searchQuery = ref('');
// ✅ แก้ไข: Default filter เป็น lowercase ให้ตรงกับ UI ที่ส่งมา
const currentStatusFilter = ref('pending');
const filterYear = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Stats Calculation
// ✅ แก้ไข: เช็คสถานะแบบ Case-Insensitive (ครอบคลุมทั้ง 'pending' และ 'Pending' จาก DB)
const stats = computed(() => {
    const total = requests.value.length;
    const pending = requests.value.filter(r => r.status.toLowerCase() === 'pending').length;
    const approved = requests.value.filter(r => r.status.toLowerCase() === 'approved').length;
    const rejected = requests.value.filter(r => r.status.toLowerCase() === 'rejected').length;
    return { total, pending, approved, rejected };
});

// Filter Logic
const filteredRequests = computed(() => {
    let result = requests.value;

    // 1. Status Filter
    if (currentStatusFilter.value) {
        // ✅ แก้ไข: เปรียบเทียบแบบ lowercase ทั้งคู่
        result = result.filter(req => req.status.toLowerCase() === currentStatusFilter.value.toLowerCase());
    }

    // 2. Search Filter
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(req =>
            (req.requester_first_name && req.requester_first_name.toLowerCase().includes(q)) ||
            (req.requester_last_name && req.requester_last_name.toLowerCase().includes(q)) ||
            (req.request_type_name && req.request_type_name.toLowerCase().includes(q)) // ✅ แก้ชื่อฟิลด์ให้ตรงกับ API (request_type_name)
        );
    }

    // 3. Year Filter
    if (filterYear.value) {
        result = result.filter(req => req.created_at && req.created_at.includes(filterYear.value));
    }

    return result;
});

const totalPages = computed(() => Math.ceil(filteredRequests.value.length / itemsPerPage.value));
const paginatedRequests = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredRequests.value.slice(start, end);
});

// Actions
const setFilter = (status) => { currentStatusFilter.value = status; currentPage.value = 1; };
const resetFilters = () => { searchQuery.value = ''; filterYear.value = ''; currentStatusFilter.value = null; currentPage.value = 1; };
watch([searchQuery, currentStatusFilter, filterYear], () => { currentPage.value = 1; });

const fetchData = async () => {
    try {
        const res = await axios.get('/api/requests');
        requests.value = res.data;
    } catch (e) { console.error(e); }
};

// Approve / Reject Function
const updateStatus = (id, status) => {
    // ✅ Logic: UI ส่ง 'approved' (ตัวเล็ก) -> แปลงเป็น 'Approved' (ตัวใหญ่) ส่งให้ DB
    const isApprove = status === 'approved';
    const dbStatus = isApprove ? 'Approved' : 'Rejected'; // แปลงค่าให้ตรงกับ ENUM ในฐานข้อมูล

    const actionText = isApprove ? 'อนุมัติ' : 'ไม่อนุมัติ';
    const confirmColor = isApprove ? '#10b981' : '#f43f5e';

    Swal.fire({
        title: `ยืนยันการ${actionText}?`,
        text: `คุณต้องการ${actionText}คำร้องนี้ใช่หรือไม่`,
        icon: isApprove ? 'success' : 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                // ✅ ส่งค่า status ที่แปลงแล้วไปหลังบ้าน
                await axios.put(`/api/requests/${id}/status`, { status: dbStatus });
                Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', showConfirmButton: false, timer: 1000 });
                fetchData();
            } catch (e) {
                Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
            }
        }
    });
};

// UI Helpers
const statusBadgeClass = (status) => {
    // ✅ รองรับทั้งตัวเล็กตัวใหญ่
    switch (status.toLowerCase()) {
        case 'approved': return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        case 'rejected': return 'bg-rose-50 text-rose-600 border-rose-100';
        default: return 'bg-amber-50 text-amber-600 border-amber-100';
    }
};
const getStatusText = (status) => {
    switch (status.toLowerCase()) {
        case 'approved': return 'อนุมัติแล้ว';
        case 'rejected': return 'ไม่อนุมัติ';
        default: return 'รออนุมัติ';
    }
};
const formatDate = (d) => {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('th-TH', { day: '2-digit', month: '2-digit', year: 'numeric' });
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 20px;
}
</style>