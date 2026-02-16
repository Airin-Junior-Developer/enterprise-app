<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-800">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">รายการคำร้อง</h2>
                <p class="text-sm text-slate-500">จัดการและตรวจสอบสถานะเอกสารคำร้องทั้งหมด</p>
            </div>
            <button @click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                สร้างคำร้องใหม่
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
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
            <div @click="setFilter('Pending')"
                class="cursor-pointer p-5 rounded-xl border transition-all duration-200 flex items-center justify-between group"
                :class="currentStatusFilter === 'Pending' ? 'bg-amber-50 border-amber-500 ring-1 ring-amber-500 shadow-md' : 'bg-white border-slate-100 hover:border-amber-300 hover:shadow-sm'">
                <div>
                    <p class="text-sm font-medium mb-1"
                        :class="currentStatusFilter === 'Pending' ? 'text-amber-700' : 'text-slate-500'">รออนุมัติ</p>
                    <h3 class="text-3xl font-bold"
                        :class="currentStatusFilter === 'Pending' ? 'text-amber-900' : 'text-amber-500'">{{
                            stats.pending }}</h3>
                </div>
                <div class="h-12 w-12 rounded-full flex items-center justify-center transition-colors"
                    :class="currentStatusFilter === 'Pending' ? 'bg-amber-200 text-amber-700' : 'bg-amber-50 text-amber-500 group-hover:bg-amber-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div @click="setFilter('Approved')"
                class="cursor-pointer p-5 rounded-xl border transition-all duration-200 flex items-center justify-between group"
                :class="currentStatusFilter === 'Approved' ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500 shadow-md' : 'bg-white border-slate-100 hover:border-emerald-300 hover:shadow-sm'">
                <div>
                    <p class="text-sm font-medium mb-1"
                        :class="currentStatusFilter === 'Approved' ? 'text-emerald-700' : 'text-slate-500'">อนุมัติแล้ว
                    </p>
                    <h3 class="text-3xl font-bold"
                        :class="currentStatusFilter === 'Approved' ? 'text-emerald-900' : 'text-emerald-600'">{{
                            stats.approved }}</h3>
                </div>
                <div class="h-12 w-12 rounded-full flex items-center justify-center transition-colors"
                    :class="currentStatusFilter === 'Approved' ? 'bg-emerald-200 text-emerald-700' : 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div @click="setFilter('Rejected')"
                class="cursor-pointer p-5 rounded-xl border transition-all duration-200 flex items-center justify-between group"
                :class="currentStatusFilter === 'Rejected' ? 'bg-rose-50 border-rose-500 ring-1 ring-rose-500 shadow-md' : 'bg-white border-slate-100 hover:border-rose-300 hover:shadow-sm'">
                <div>
                    <p class="text-sm font-medium mb-1"
                        :class="currentStatusFilter === 'Rejected' ? 'text-rose-700' : 'text-slate-500'">ไม่อนุมัติ</p>
                    <h3 class="text-3xl font-bold"
                        :class="currentStatusFilter === 'Rejected' ? 'text-rose-900' : 'text-rose-500'">{{
                            stats.rejected }}</h3>
                </div>
                <div class="h-12 w-12 rounded-full flex items-center justify-center transition-colors"
                    :class="currentStatusFilter === 'Rejected' ? 'bg-rose-200 text-rose-700' : 'bg-rose-50 text-rose-500 group-hover:bg-rose-100'">
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
                        placeholder="ค้นหาชื่อ, รหัสเอกสาร หรือประเภทคำร้อง..." />
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
                            <th class="px-6 py-4">ผู้ยื่นคำร้อง</th>
                            <th class="px-6 py-4">รายละเอียด</th>
                            <th class="px-6 py-4">วัน-เวลาที่เริ่ม</th>
                            <th class="px-6 py-4">วัน-เวลาที่สิ้นสุด</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                            <th class="px-6 py-4 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="(req, index) in paginatedRequests" :key="req.request_id"
                            class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center text-slate-400">{{ (currentPage - 1) * itemsPerPage + index
                                + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">{{ req.requester_first_name }} {{
                                    req.requester_last_name }}</div>
                                <div class="text-xs text-slate-400">{{ req.requester_branch || '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-700">{{ req.request_type_name }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-[200px]">{{ req.subject || req.reason
                                    || '-' }}</div>
                                <div v-if="req.amount" class="text-xs font-mono text-blue-600 mt-1">{{
                                    Number(req.amount).toLocaleString() }} บาท</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ req.start_date ? formatDate(req.start_date) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ req.end_date ? formatDate(req.end_date) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span :class="statusBadgeClass(req.status)"
                                    class="px-3 py-1 rounded-full text-xs font-bold border">{{ getStatusText(req.status)
                                    }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button v-if="req.status === 'Pending'" @click="deleteRequest(req.request_id)"
                                    class="p-2 rounded-full text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors"
                                    title="ยกเลิกคำร้อง">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <span v-else class="text-slate-300 text-xs">-</span>
                            </td>
                        </tr>
                        <tr v-if="paginatedRequests.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-3xl mb-2">📄</span>
                                    <span>ไม่พบข้อมูลคำร้องตามเงื่อนไขที่เลือก</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="text-xs text-slate-500">แสดง {{ paginatedRequests.length > 0 ? (currentPage - 1) *
                    itemsPerPage + 1 : 0 }} ถึง {{ Math.min(currentPage * itemsPerPage, filteredRequests.length) }} จาก
                    {{ filteredRequests.length }} รายการ</div>
                <div class="flex gap-1">
                    <button @click="currentPage--" :disabled="currentPage === 1"
                        class="px-3 py-1 border rounded hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed text-xs text-slate-600">ก่อนหน้า</button>
                    <span class="px-3 py-1 border bg-blue-600 text-white rounded text-xs font-bold">{{ currentPage
                    }}</span>
                    <button @click="currentPage++" :disabled="currentPage >= totalPages"
                        class="px-3 py-1 border rounded hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed text-xs text-slate-600">ถัดไป</button>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-clear-sm transition-opacity" @click="closeModal"></div>
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-800">สร้างคำร้องใหม่</h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>
                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <form @submit.prevent="saveRequest" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">พนักงาน (ผู้ยื่น) <span
                                    class="text-rose-500">*</span></label>
                            <div
                                class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2.5 text-slate-600 font-medium cursor-not-allowed flex items-center gap-2">
                                <div
                                    class="h-6 w-6 rounded-full bg-slate-300 flex items-center justify-center text-xs text-white font-bold">
                                    {{ currentUser?.first_name ? currentUser.first_name.charAt(0) : 'U' }}
                                </div>
                                <span>{{ currentUser?.first_name }} {{ currentUser?.last_name }}</span>
                                <span class="text-xs text-slate-400 border-l border-slate-300 pl-2 ml-1">{{
                                    currentUser?.position?.position_name || 'My Request' }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">หัวข้อคำร้อง <span
                                    class="text-rose-500">*</span></label>
                            <input v-model="form.subject" type="text" required
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="ระบุหัวข้อคำร้อง..." />
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">ประเภทคำร้อง <span
                                    class="text-rose-500">*</span></label>
                            <select v-model="form.request_type_id" required
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 outline-none bg-white focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>-- กรุณาเลือกประเภท --</option>
                                <option v-for="type in requestTypes" :key="type.id" :value="type.id">{{ type.Name_Type
                                }}</option>
                            </select>
                        </div>

                        <div v-if="isLeaveType" class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">วันที่เริ่ม</label>
                                <input v-model="form.start_date" type="date"
                                    class="w-full border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">ถึงวันที่</label>
                                <input v-model="form.end_date" :min="form.start_date" :disabled="!form.start_date"
                                    type="date"
                                    class="w-full border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-slate-100 disabled:cursor-not-allowed" />
                            </div>
                        </div>

                        <div v-if="isFinancialType">
                            <label class="block text-xs font-bold text-slate-500 mb-1">จำนวนเงิน (บาท)</label>
                            <input v-model="form.amount" type="number" step="0.01"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="0.00" />
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">รายละเอียด</label>
                            <textarea v-model="form.reason" rows="3"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="ระบุเหตุผล..."></textarea>
                        </div>

                        <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-6">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">ยกเลิก</button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-bold shadow-md"
                                :disabled="isLoading">
                                {{ isLoading ? '...' : 'บันทึก' }}
                            </button>
                        </div>
                    </form>
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
const currentUser = ref(null);
const searchQuery = ref('');
const filterYear = ref('');
const currentStatusFilter = ref(null);
const isModalOpen = ref(false);
const isLoading = ref(false);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const form = ref({ user_id: '', request_type_id: '', subject: '', reason: '', start_date: '', end_date: '', amount: '' });
const requestTypes = ref([]);

// ✅ Logic Logic ที่ได้รับการแก้ไขให้ทำงานถูกต้อง
const isLeaveType = computed(() => {
    if (!form.value.request_type_id) return false;
    const selected = requestTypes.value.find(t => t.id === form.value.request_type_id);
    return selected && ['ลากิจ', 'ลาป่วย', 'ลาพักร้อน'].some(t => selected.Name_Type.includes(t));
});

const isFinancialType = computed(() => {
    if (!form.value.request_type_id) return false;
    const selected = requestTypes.value.find(t => t.id === form.value.request_type_id);
    return selected && ['ปรับเงินเดือน', 'เบิกค่าใช้จ่าย', 'เบิกค่าเดินทาง', 'เบิกค่ารักษาพยาบาล'].some(t => selected.Name_Type.includes(t));
});

watch(() => form.value.start_date, (newStart) => {
    if (newStart && form.value.end_date) {
        if (new Date(form.value.end_date) < new Date(newStart)) {
            form.value.end_date = '';
        }
    }
});

const stats = computed(() => {
    return {
        total: requests.value.length,
        pending: requests.value.filter(r => r.status === 'Pending').length,
        approved: requests.value.filter(r => r.status === 'Approved').length,
        rejected: requests.value.filter(r => r.status === 'Rejected').length
    };
});

const filteredRequests = computed(() => {
    let result = requests.value;
    if (currentStatusFilter.value) result = result.filter(req => req.status === currentStatusFilter.value);
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(req =>
            (req.requester_first_name?.toLowerCase().includes(q)) ||
            (req.requester_last_name?.toLowerCase().includes(q)) ||
            (req.request_type_name?.toLowerCase().includes(q)) ||
            (req.subject?.toLowerCase().includes(q))
        );
    }
    if (filterYear.value) result = result.filter(req => req.created_at?.includes(filterYear.value));
    return result;
});

const totalPages = computed(() => Math.ceil(filteredRequests.value.length / itemsPerPage.value));
const paginatedRequests = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    return filteredRequests.value.slice(start, start + itemsPerPage.value);
});

const setFilter = (status) => { currentStatusFilter.value = status; currentPage.value = 1; };
const resetFilters = () => { searchQuery.value = ''; filterYear.value = ''; currentStatusFilter.value = null; currentPage.value = 1; };
watch([searchQuery, filterYear, currentStatusFilter], () => { currentPage.value = 1; });

// ✅ Fetching Logic
const fetchData = async () => {
    try {
        const res = await axios.get('/api/requests');
        requests.value = res.data;
    } catch (e) { console.error(e); }
};

const fetchRequestTypes = async () => {
    try {
        // ดึงเฉพาะประเภทที่เปิดใช้งานอยู่มาแสดงใน Dropdown
        const res = await axios.get('/api/request-types');
        requestTypes.value = res.data.filter(t => t.is_active);
    } catch (e) { console.error(e); }
};

// ✅ Open Modal Logic (Auto-fill User ID)
const openModal = () => {
    const userStr = localStorage.getItem('user');
    if (userStr) {
        currentUser.value = JSON.parse(userStr);
        // Reset form but keep user_id
        form.value = {
            user_id: currentUser.value.user_id,
            request_type_id: '',
            subject: '',
            reason: '',
            start_date: '',
            end_date: '',
            amount: ''
        };
        isModalOpen.value = true;
    } else {
        Swal.fire('Error', 'ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่', 'error');
    }
};

const closeModal = () => isModalOpen.value = false;

const saveRequest = async () => {
    if (!form.value.user_id) return Swal.fire('Error', 'ไม่ระบุผู้ส่งคำร้อง', 'error');
    isLoading.value = true;
    try {
        await axios.post('/api/requests', form.value);
        Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', showConfirmButton: false, timer: 1500 });
        closeModal();
        fetchData();
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
    } finally {
        isLoading.value = false;
    }
};

const deleteRequest = (id) => {
    Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#ef4444' })
        .then(async (result) => { if (result.isConfirmed) { await axios.delete(`/api/requests/${id}`); fetchData(); } });
};

const statusBadgeClass = (status) => {
    switch (status) {
        case 'Approved': return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        case 'Rejected': return 'bg-rose-50 text-rose-600 border-rose-100';
        default: return 'bg-amber-50 text-amber-600 border-amber-100';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'Approved': return 'อนุมัติแล้ว';
        case 'Rejected': return 'ไม่อนุมัติ';
        default: return 'รออนุมัติ';
    }
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('th-TH', { day: '2-digit', month: '2-digit', year: 'numeric' }) : '-';

onMounted(() => {
    fetchData();
    fetchRequestTypes();
    const userStr = localStorage.getItem('user');
    if (userStr) currentUser.value = JSON.parse(userStr);
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