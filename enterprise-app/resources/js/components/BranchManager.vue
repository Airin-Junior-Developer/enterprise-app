<template>
    <div class="p-6 bg-[#F4F7F9] min-h-screen font-sans text-slate-800">

        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">ข้อมูลสาขา (Branches)</h2>
                <p class="text-sm text-slate-500">จัดการรายละเอียด ที่ตั้ง และ HR ประจำสาขาทั้งหมด</p>
            </div>
            <div class="flex gap-2">
                <button @click="fetchBranches"
                    class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2.5 rounded-full shadow-sm transition-all flex items-center gap-2 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    รีโหลดข้อมูล
                </button>
                <button @click="openModal()"
                    class="bg-[#2B54A3] hover:bg-[#1E3A8A] text-white px-5 py-2.5 rounded-full flex items-center gap-2 text-sm font-bold transition-colors shadow-sm">
                    <span class="text-lg leading-none">+</span> เพิ่มสาขาใหม่
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="relative w-full md:w-96">
                    <input type="text" v-model="searchQuery"
                        class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-full focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                        placeholder="ค้นหาชื่อสาขา หรือที่ตั้ง..." />
                    <svg class="h-5 w-5 text-blue-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="px-6 pt-6 pb-2">
                <h3 class="text-lg font-bold text-[#1E40AF]">
                    รายชื่อสาขาทั้งหมด {{ filteredBranches.length }} แห่ง
                </h3>
            </div>

            <div class="overflow-x-auto px-6 pb-6">
                <table class="w-full text-sm text-left border-b border-slate-200">
                    <thead class="text-slate-700 font-bold border-b-2 border-slate-200 bg-[#F8FAFC]">
                        <tr>
                            <th class="py-4 px-4 text-center w-16">ลำดับ</th>
                            <th class="py-4 px-4">ชื่อสาขา</th>
                            <th class="py-4 px-4">ที่ตั้ง</th>
                            <th class="py-4 px-4 text-center">HR (คน)</th>
                            <th class="py-4 px-4 text-center w-32">จัดการ</th>
                            <th class="py-4 px-4 text-center w-24">ดูข้อมูล</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template v-for="(branch, index) in paginatedBranches" :key="branch.branch_id">
                            <tr @click="toggleBranch(branch.branch_id)"
                                class="hover:bg-slate-50 transition-colors cursor-pointer bg-white">
                                <td class="py-4 px-4 text-center text-slate-500 font-mono">{{ (currentPage - 1) *
                                    itemsPerPage + index + 1 }}</td>
                                <td class="py-4 px-4 font-bold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs border border-blue-200">
                                            {{ branch.branch_name.charAt(0) }}
                                        </div>
                                        {{ branch.branch_name }}
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    <div class="flex items-center gap-1.5 truncate max-w-[250px]"
                                        :title="branch.address">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ branch.address || 'ไม่ระบุ' }}
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-blue-600">
                                    {{ branch.responsible_users ? branch.responsible_users.length : 0 }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click.stop="openModal(branch)"
                                            class="text-slate-400 hover:text-blue-600 transition-colors p-1"
                                            title="แก้ไข">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button @click.stop="deleteBranch(branch.branch_id)"
                                            class="text-slate-400 hover:text-rose-600 transition-colors p-1" title="ลบ">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <svg :class="expandedBranches.includes(branch.branch_id) ? 'rotate-180 text-blue-600' : 'text-blue-500'"
                                        class="w-5 h-5 mx-auto transition-transform duration-200" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </td>
                            </tr>

                            <tr v-if="expandedBranches.includes(branch.branch_id)" class="bg-[#F8FAFC]">
                                <td colspan="6" class="p-0 border-b-2 border-slate-200 shadow-inner">
                                    <div class="px-8 py-5">
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                                            รายชื่อ HR ประจำสาขา</p>
                                        <table
                                            class="w-full text-[13px] text-left border border-slate-200 rounded-lg overflow-hidden bg-white">
                                            <thead class="text-slate-500 border-b border-slate-200 bg-slate-50">
                                                <tr>
                                                    <th class="py-2.5 px-4 font-semibold text-center w-16">ลำดับ</th>
                                                    <th class="py-2.5 px-4 font-semibold">ชื่อ-นามสกุล</th>
                                                    <th class="py-2.5 px-4 font-semibold">ตำแหน่ง</th>
                                                    <th class="py-2.5 px-4 font-semibold text-center">สถานะ</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                <tr v-for="(user, uIndex) in branch.responsible_users"
                                                    :key="user.user_id" class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="py-3 px-4 text-center text-slate-400">{{ uIndex + 1 }}
                                                    </td>
                                                    <td class="py-3 px-4 font-bold text-slate-700">
                                                        <div class="flex items-center gap-2">
                                                            <div
                                                                class="h-6 w-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-600 font-bold border border-white shadow-sm">
                                                                {{ user.first_name ? user.first_name.charAt(0) : '?' }}
                                                            </div>
                                                            {{ user.first_name }} {{ user.last_name }}
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4 text-blue-600 font-medium">{{
                                                        user.position_name || 'HR' }}</td>
                                                    <td class="py-3 px-4 text-center">
                                                        <span
                                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">ใช้งาน</span>
                                                    </td>
                                                </tr>
                                                <tr
                                                    v-if="!branch.responsible_users || branch.responsible_users.length === 0">
                                                    <td colspan="4"
                                                        class="py-6 px-4 text-center text-slate-400 italic text-sm">
                                                        - ยังไม่มีรายชื่อ HR ประจำสาขานี้ -
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr v-if="paginatedBranches.length === 0">
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                ไม่พบข้อมูลสาขาตามเงื่อนไขที่ค้นหา
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-6 flex flex-col sm:flex-row items-center justify-end gap-6 text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <span>จำนวนแถว</span>
                        <select v-model="itemsPerPage"
                            class="border-b-2 border-slate-300 py-1 pr-4 focus:outline-none focus:border-blue-500 bg-transparent cursor-pointer font-bold">
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="50">50</option>
                        </select>
                    </div>

                    <div class="font-medium">
                        {{ startItem }}-{{ endItem }} of {{ filteredBranches.length }}
                    </div>

                    <div class="flex items-center gap-2">
                        <button @click="currentPage--" :disabled="currentPage === 1"
                            class="p-1 hover:text-blue-600 disabled:opacity-30 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="currentPage++" :disabled="currentPage >= totalPages"
                            class="p-1 hover:text-blue-600 disabled:opacity-30 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-clear-sm p-4 transition-all">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in-up border border-slate-100">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-[#1E40AF] text-lg">{{ isEditMode ? 'แก้ไขข้อมูลสาขา' : 'สร้างสาขาใหม่' }}
                    </h3>
                    <button @click="closeModal"
                        class="text-slate-400 hover:text-rose-500 transition-colors text-2xl leading-none">&times;</button>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อสาขา <span
                                class="text-rose-500">*</span></label>
                        <input type="text" v-model="form.branch_name"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                            placeholder="เช่น สำนักงานใหญ่..." />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ที่อยู่ / สถานที่ตั้ง</label>
                        <textarea v-model="form.address" rows="3"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                            placeholder="ระบุที่ตั้งสาขา..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button @click="closeModal"
                        class="px-6 py-2 border border-slate-300 bg-white rounded-full text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">ยกเลิก</button>
                    <button @click="saveBranch"
                        class="px-8 py-2 bg-[#2B54A3] hover:bg-[#1E3A8A] text-white rounded-full font-bold text-sm transition-colors shadow-md">
                        {{ isEditMode ? 'บันทึกการแก้ไข' : 'ยืนยันสร้างสาขา' }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted, computed, watch } from 'vue';

const branches = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const isLoading = ref(true);
const searchQuery = ref('');
const form = ref({ branch_id: null, branch_name: '', address: '' });

// Pagination & Accordion States
const currentPage = ref(1);
const itemsPerPage = ref(10);
const expandedBranches = ref([]);

const fetchBranches = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get('/api/branches');
        branches.value = res.data;
    } catch (e) {
        console.error('Fetch Error:', e);
        branches.value = [];
    } finally {
        isLoading.value = false;
    }
};

// Filter & Pagination Logic
// Filter & Pagination Logic
const filteredBranches = computed(() => {
    if (!searchQuery.value) return branches.value;
    const lowerQuery = searchQuery.value.toLowerCase();

    return branches.value.filter(b => {
        // 1. ค้นหาจากชื่อสาขาและที่อยู่
        const matchBranch = (b.branch_name && b.branch_name.toLowerCase().includes(lowerQuery)) ||
            (b.address && b.address.toLowerCase().includes(lowerQuery));

        // 2. ค้นหาจากชื่อ-นามสกุล ของ HR ในสาขานั้น
        const matchHR = b.responsible_users && b.responsible_users.some(user => {
            const fullName = `${user.first_name || ''} ${user.last_name || ''}`.toLowerCase();
            return fullName.includes(lowerQuery);
        });

        // ถ้าตรงกับเงื่อนไขใดเงื่อนไขหนึ่ง (สาขา หรือ ชื่อคน) ให้แสดงข้อมูล
        return matchBranch || matchHR;
    });
});

const totalPages = computed(() => Math.ceil(filteredBranches.value.length / itemsPerPage.value));
const startItem = computed(() => paginatedBranches.value.length > 0 ? (currentPage.value - 1) * itemsPerPage.value + 1 : 0);
const endItem = computed(() => Math.min(currentPage.value * itemsPerPage.value, filteredBranches.value.length));

const paginatedBranches = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredBranches.value.slice(start, end);
});

// Watchers
watch([searchQuery, itemsPerPage], () => {
    currentPage.value = 1;
    expandedBranches.value = [];
});

// Actions
const toggleBranch = (id) => {
    if (expandedBranches.value.includes(id)) {
        expandedBranches.value = expandedBranches.value.filter(branchId => branchId !== id);
    } else {
        expandedBranches.value.push(id);
    }
};

const openModal = (branch = null) => {
    if (branch) {
        isEditMode.value = true;
        form.value = { ...branch };
    } else {
        isEditMode.value = false;
        form.value = { branch_id: null, branch_name: '', address: '' };
    }
    showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const saveBranch = async () => {
    if (!form.value.branch_name) return Swal.fire('แจ้งเตือน', 'กรุณาระบุชื่อสาขา', 'warning');
    try {
        if (isEditMode.value) {
            await axios.put(`/api/branches/${form.value.branch_id}`, form.value);
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'แก้ไขเรียบร้อย', timer: 1500, showConfirmButton: false });
        } else {
            await axios.post('/api/branches', form.value);
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'เพิ่มเรียบร้อย', timer: 1500, showConfirmButton: false });
        }
        closeModal();
        fetchBranches();
    } catch (error) {
        Swal.fire('Error', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
    }
};

const deleteBranch = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        confirmButtonText: 'ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/branches/${id}`);
                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1000 });
                fetchBranches();
            } catch (e) {
                Swal.fire('Error', 'ไม่สามารถลบข้อมูลได้', 'error');
            }
        }
    });
};

onMounted(() => fetchBranches());
</script>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.2s ease-out;
}
</style>