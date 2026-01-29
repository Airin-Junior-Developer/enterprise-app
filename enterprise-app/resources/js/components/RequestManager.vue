<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">รายการคำร้อง (Requests)</h1>
                <p class="text-slate-500 mt-1 text-base">ยื่นเรื่องลา, ปรับเงินเดือน และคำร้องอื่นๆ</p>
            </div>
            <button @click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 flex items-center gap-2 transition-all transform hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">สร้างคำร้องใหม่</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ประเภท</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ผู้ยื่นคำร้อง
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">รายละเอียด</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase">สถานะ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">วันที่ยื่น</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="req in requests" :key="req.request_id" class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <span v-if="req.type === 'leave'"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                🏖️ ลางาน
                            </span>
                            <span v-else-if="req.type === 'salary'"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                💰 ปรับเงินเดือน
                            </span>
                            <span v-else
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                📄 อื่นๆ
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 mr-3">
                                    {{ req.employee ? req.employee.first_name.charAt(0) : '?' }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ req.employee ? req.employee.first_name + ' ' + req.employee.last_name :
                                        'Unknown' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ req.employee && req.employee.branch ? req.employee.branch.branch_name : '-'
                                        }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-600">
                                <div v-if="req.type === 'leave'">
                                    <span class="font-semibold text-purple-600">{{ req.details.leave_type }}</span>:
                                    {{ req.details.start_date }} ถึง {{ req.details.end_date }}
                                </div>
                                <div v-else-if="req.type === 'salary'">
                                    ขอปรับเป็น: <span class="font-bold text-emerald-600">{{
                                        Number(req.details.requested_salary).toLocaleString() }}</span>
                                </div>
                                <div class="text-xs text-slate-400 mt-1">"{{ req.reason }}"</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span :class="{
                                'bg-yellow-100 text-yellow-800': req.status === 'pending',
                                'bg-green-100 text-green-800': req.status === 'approved',
                                'bg-red-100 text-red-800': req.status === 'rejected'
                            }" class="px-3 py-1 text-xs font-bold rounded-full border border-opacity-20">
                                {{ req.status.toUpperCase() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-slate-500">
                            {{ new Date(req.created_at).toLocaleDateString('th-TH') }}
                        </td>
                    </tr>
                    <tr v-if="requests.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">ยังไม่มีรายการคำร้อง</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" @click="closeModal"></div>
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden flex flex-col max-h-[90vh]">

                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-800">สร้างคำร้องใหม่</h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="p-6 overflow-y-auto">
                    <form @submit.prevent="saveRequest" class="space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ประเภทคำร้อง</label>
                            <select v-model="form.type"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="leave">🏖️ ขอลาหยุด (Leave)</option>
                                <option value="salary">💰 ขอปรับเงินเดือน (Salary Adjustment)</option>
                            </select>
                        </div>

                        <div v-if="form.type === 'leave'"
                            class="bg-purple-50 p-4 rounded-xl border border-purple-100 space-y-3">
                            <h4 class="text-xs font-bold text-purple-600 uppercase">รายละเอียดการลา</h4>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">ประเภทการลา</label>
                                <select v-model="form.leave_type" class="w-full border-slate-200 rounded-lg text-sm">
                                    <option value="sick">ป่วย (Sick Leave)</option>
                                    <option value="vacation">พักร้อน (Vacation)</option>
                                    <option value="personal">กิจธุระ (Personal)</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">ตั้งแต่วันที่</label>
                                    <input type="date" v-model="form.start_date"
                                        class="w-full border-slate-200 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">ถึงวันที่</label>
                                    <input type="date" v-model="form.end_date"
                                        class="w-full border-slate-200 rounded-lg text-sm" required>
                                </div>
                            </div>
                        </div>

                        <div v-if="form.type === 'salary'"
                            class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 space-y-3">
                            <h4 class="text-xs font-bold text-emerald-600 uppercase">รายละเอียดเงินเดือน</h4>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">เงินเดือนปัจจุบัน</label>
                                <input type="number" v-model="form.current_salary"
                                    class="w-full border-slate-200 rounded-lg text-sm bg-slate-100" readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">เงินเดือนที่ต้องการ</label>
                                <input type="number" v-model="form.requested_salary"
                                    class="w-full border-slate-200 rounded-lg text-sm focus:ring-emerald-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">เหตุผลประกอบ <span
                                    class="text-rose-500">*</span></label>
                            <textarea v-model="form.reason" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                                required placeholder="โปรดระบุเหตุผล..."></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-medium shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex justify-center items-center gap-2"
                                :disabled="isLoading">
                                <span v-if="isLoading" class="animate-spin text-xl">⏳</span>
                                {{ isLoading ? 'กำลังส่งข้อมูล...' : 'ส่งคำร้อง' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const requests = ref([]);
const isModalOpen = ref(false);
const isLoading = ref(false);

// Form Data (รวมทุก field ไว้ในนี้ แล้วค่อยเลือกส่ง)
const form = ref({
    type: 'leave',
    reason: '',
    // Leave Fields
    leave_type: 'sick',
    start_date: '',
    end_date: '',
    // Salary Fields
    current_salary: 25000, // TODO: ดึงจาก User จริง
    requested_salary: 0
});

// ดึงข้อมูลคำร้อง
const fetchRequests = async () => {
    try {
        const res = await axios.get('/api/requests');
        requests.value = res.data;
    } catch (e) {
        console.error(e);
    }
};

const openModal = () => {
    form.value.reason = '';
    form.value.start_date = '';
    form.value.end_date = '';
    form.value.requested_salary = 0;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

// บันทึกคำร้อง
const saveRequest = async () => {
    isLoading.value = true;

    try {
        // เตรียมข้อมูล JSON (Details) ตามประเภทที่เลือก
        let details = {};

        if (form.value.type === 'leave') {
            details = {
                leave_type: form.value.leave_type,
                start_date: form.value.start_date,
                end_date: form.value.end_date
            };
        } else if (form.value.type === 'salary') {
            details = {
                current_salary: form.value.current_salary,
                requested_salary: form.value.requested_salary
            };
        }

        // ส่งข้อมูลไป API
        await axios.post('/api/requests', {
            type: form.value.type,
            reason: form.value.reason,
            details: details // ส่งเป็น Object เดี๋ยว Laravel แปลงเป็น JSON ให้เอง (ถ้า Model ตั้ง casts ไว้)
        });

        Swal.fire('สำเร็จ!', 'ส่งคำร้องเรียบร้อยแล้ว', 'success');
        closeModal();
        fetchRequests();

    } catch (e) {
        Swal.fire('เกิดข้อผิดพลาด', e.response?.data?.message || e.message, 'error');
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchRequests();
});
</script>