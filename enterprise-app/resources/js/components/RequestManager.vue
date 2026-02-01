<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">รายการคำร้อง (Requests)</h1>
                <p class="text-slate-500 mt-1 text-base">ประวัติคำขอวันลา, ปรับเงินเดือน และคำร้องทั้งหมด</p>
            </div>
            <button @click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 flex items-center gap-2 transition-all transform hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">สร้างคำร้องใหม่</span>
            </button>
        </div>

        <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 mb-6 max-w-sm">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" v-model="searchQuery"
                    class="block w-full pl-10 pr-4 py-2 border-none rounded-xl bg-transparent focus:ring-0 text-slate-700 placeholder-slate-400 focus:outline-none"
                    placeholder="ค้นหาชื่อ, ประเภทคำขอ..." />
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ผู้ยื่นคำร้อง</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ประเภท / เหตุผล</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase">วันที่ / ข้อมูลเพิ่มเติม</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase">สถานะ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="req in filteredRequests" :key="req.request_id" class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-sm mr-3">
                                    {{ req.user ? req.user.first_name.charAt(0) : '?' }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800">{{ req.user ? req.user.first_name + ' ' + req.user.last_name : 'ไม่ระบุ' }}</div>
                                    <div class="text-xs text-slate-500">{{ formatDate(req.created_at) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 text-xs font-semibold bg-blue-50 text-blue-700 rounded-md border border-blue-100 mb-1">
                                {{ req.request_type }}
                            </span>
                            <div class="text-sm text-slate-600 line-clamp-1">{{ req.reason || '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-600">
                            <div v-if="req.start_date">
                                <span class="font-mono">{{ formatDateShort(req.start_date) }}</span>
                                <span v-if="req.end_date" class="mx-1 text-slate-400">➜</span>
                                <span v-if="req.end_date" class="font-mono">{{ formatDateShort(req.end_date) }}</span>
                            </div>
                            <div v-else-if="req.amount">
                                {{ Number(req.amount).toLocaleString() }} บาท
                            </div>
                            <div v-else>-</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span :class="statusClass(req.status)" class="px-3 py-1 text-xs font-bold rounded-full border uppercase">
                                {{ req.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="deleteRequest(req.request_id)" 
                                class="p-2 rounded-lg bg-slate-100 text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition-colors"
                                title="ยกเลิก/ลบคำร้อง">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="filteredRequests.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">ไม่พบรายการคำร้อง</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" @click="closeModal"></div>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">สร้างคำร้องใหม่</h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <form @submit.prevent="saveRequest" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">พนักงาน (ผู้ยื่น) <span class="text-rose-500">*</span></label>
                            <select v-model="form.user_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">-- เลือกพนักงาน --</option>
                                <option v-for="user in users" :key="user.user_id" :value="user.user_id">
                                    {{ user.first_name }} {{ user.last_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ประเภทคำร้อง <span class="text-rose-500">*</span></label>
                            <select v-model="form.request_type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="ลากิจ">ลากิจ (Personal Leave)</option>
                                <option value="ลาป่วย">ลาป่วย (Sick Leave)</option>
                                <option value="ลาพักร้อน">ลาพักร้อน (Vacation)</option>
                                <option value="ปรับเงินเดือน">ปรับเงินเดือน (Salary Adjustment)</option>
                                <option value="เบิกค่าใช้จ่าย">เบิกค่าใช้จ่าย (Reimbursement)</option>
                                <option value="อื่นๆ">อื่นๆ (Others)</option>
                            </select>
                        </div>

                        <div v-if="['ลากิจ', 'ลาป่วย', 'ลาพักร้อน'].includes(form.request_type)" class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">วันที่เริ่ม</label>
                                <input v-model="form.start_date" type="date" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">ถึงวันที่</label>
                                <input v-model="form.end_date" type="date" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>

                        <div v-if="['ปรับเงินเดือน', 'เบิกค่าใช้จ่าย'].includes(form.request_type)">
                            <label class="block text-sm font-medium text-slate-700 mb-1">จำนวนเงิน (บาท)</label>
                            <input v-model="form.amount" type="number" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">รายละเอียด / เหตุผล</label>
                            <textarea v-model="form.reason" rows="3" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                        </div>

                        <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-6">
                            <button type="button" @click="closeModal" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">ยกเลิก</button>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-md" :disabled="isLoading">
                                {{ isLoading ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
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
import { ref, onMounted, computed } from 'vue';

const searchQuery = ref('');

// Computed Filter
const filteredRequests = computed(() => {
    if (!searchQuery.value) return requests.value;
    const lowerSearch = searchQuery.value.toLowerCase();
    return requests.value.filter(req => 
        (req.user && (req.user.first_name.toLowerCase().includes(lowerSearch) || req.user.last_name.toLowerCase().includes(lowerSearch))) ||
        req.request_type.toLowerCase().includes(lowerSearch)
    );
});

// State
const requests = ref([]);
const users = ref([]);
const isModalOpen = ref(false);
const isLoading = ref(false);

const form = ref({
    user_id: '',
    request_type: 'ลากิจ',
    reason: '',
    start_date: '',
    end_date: '',
    amount: ''
});

// Fetch Data
const fetchData = async () => {
    try {
        const [reqRes, userRes] = await Promise.all([
            axios.get('/api/requests'),
            axios.get('/api/employees')
        ]);
        requests.value = reqRes.data;
        users.value = userRes.data;
    } catch (e) { console.error(e); }
};

// Actions
const openModal = () => {
    form.value = { user_id: '', request_type: 'ลากิจ', reason: '', start_date: '', end_date: '', amount: '' };
    isModalOpen.value = true;
};
const closeModal = () => isModalOpen.value = false;

// Create Request
const saveRequest = async () => {
    if(!form.value.user_id) return;
    isLoading.value = true;
    try {
        await axios.post('/api/requests', form.value);
        Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ!', showConfirmButton: false, timer: 1500 });
        closeModal();
        fetchData();
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || e.message, 'error');
    } finally {
        isLoading.value = false;
    }
};

// Delete
const deleteRequest = (id) => {
    Swal.fire({
        title: 'ยืนยันการยกเลิก?',
        text: 'ข้อมูลคำร้องจะถูกลบออกจากระบบ',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ลบเลย'
    }).then(async (result) => {
        if (result.isConfirmed) {
            await axios.delete(`/api/requests/${id}`);
            fetchData();
            Swal.fire('ลบสำเร็จ!', '', 'success');
        }
    });
};

// Helpers
const statusClass = (status) => {
    switch(status) {
        case 'approved': return 'bg-emerald-100 text-emerald-700 border-emerald-200';
        case 'rejected': return 'bg-rose-100 text-rose-700 border-rose-200';
        default: return 'bg-amber-100 text-amber-700 border-amber-200';
    }
};

const formatDate = (d) => {
    if(!d) return '-';
    return new Date(d).toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: '2-digit' });
};
const formatDateShort = (d) => {
    if(!d) return '';
    return new Date(d).toLocaleDateString('th-TH', { day: 'numeric', month: 'short' });
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
</style>