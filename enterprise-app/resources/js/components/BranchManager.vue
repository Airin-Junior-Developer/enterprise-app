<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">ข้อมูลสาขา (Branches)</h1>
                <p class="text-slate-500 mt-1 text-base">บริหารจัดการรายชื่อสาขาและที่ตั้งสำนักงาน</p>
            </div>
            <button @click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 flex items-center gap-2 transition-all transform hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">เพิ่มสาขาใหม่</span>
            </button>
        </div>

        <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 mb-6 max-w-sm">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" v-model="searchQuery"
                    class="block w-full pl-10 pr-4 py-2 border-none rounded-xl bg-transparent focus:ring-0 text-slate-700 placeholder-slate-400 focus:outline-none"
                    placeholder="ค้นหาสาขา..." />
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ชื่อสาขา</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ที่อยู่ /
                            รายละเอียด</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="branch in filteredBranches" :key="branch.branch_id"
                        class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg mr-3">
                                    🏢
                                </div>
                                <div class="text-sm font-bold text-slate-800">{{ branch.branch_name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ branch.description || '-' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="editBranch(branch)"
                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button @click="deleteBranch(branch.branch_id)"
                                class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="filteredBranches.length === 0">
                        <td colspan="3" class="px-6 py-10 text-center text-slate-400">ยังไม่มีข้อมูลสาขา</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" @click="closeModal"></div>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">{{ isEditing ? 'แก้ไขสาขา' : 'เพิ่มสาขาใหม่' }}</h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="p-6">
                    <form @submit.prevent="saveBranch" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อสาขา <span
                                    class="text-rose-500">*</span></label>
                            <input v-model="form.branch_name" type="text" required
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none"
                                placeholder="เช่น สำนักงานใหญ่" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ที่อยู่ / รายละเอียด</label>
                            <textarea v-model="form.description" rows="3"
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none"
                                placeholder="ระบุที่ตั้ง..."></textarea>
                        </div>

                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">ยกเลิก</button>
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-md"
                                :disabled="isLoading">
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

// Logic กรองสาขา (แก้ให้ตรงกับชื่อฟิลด์ใหม่)
const filteredBranches = computed(() => {
    if (!searchQuery.value) return branches.value;
    const lowerSearch = searchQuery.value.toLowerCase();
    return branches.value.filter(b =>
        b.branch_name.toLowerCase().includes(lowerSearch) ||
        (b.description && b.description.toLowerCase().includes(lowerSearch))
    );
});

// State
const branches = ref([]);
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const isLoading = ref(false);

// Form Data (แก้ชื่อตัวแปร)
const form = ref({
    branch_name: '',
    description: ''
});

// Fetch Data
const fetchBranches = async () => {
    try {
        const res = await axios.get('/api/branches');
        // API เราส่ง Array ตรงๆ มาเลย ไม่ได้ห่อ data.data
        branches.value = res.data;
    } catch (e) {
        console.error("Error fetching branches:", e);
    }
};

// Open Modal
const openModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.value = { branch_name: '', description: '' };
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

// Edit Branch (แก้ให้แมพกับตัวแปรใหม่)
const editBranch = (branch) => {
    isEditing.value = true;
    editingId.value = branch.branch_id; // ใช้ branch_id
    form.value = {
        branch_name: branch.branch_name,
        description: branch.description
    };
    isModalOpen.value = true;
};

// Save Branch
const saveBranch = async () => {
    if (!form.value.branch_name) return;

    isLoading.value = true;
    try {
        if (isEditing.value) {
            await axios.put(`/api/branches/${editingId.value}`, form.value);
        } else {
            await axios.post('/api/branches', form.value);
        }
        Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ!', showConfirmButton: false, timer: 1500 });
        closeModal();
        fetchBranches();
    } catch (e) {
        Swal.fire('Error', e.message, 'error');
    } finally {
        isLoading.value = false;
    }
};

// Delete Branch
const deleteBranch = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "ข้อมูลพนักงานในสาขานี้จะกลายเป็น 'ไม่มีสังกัด'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/branches/${id}`);
                Swal.fire('ลบสำเร็จ!', '', 'success');
                fetchBranches();
            } catch (e) {
                Swal.fire('Error', 'ลบไม่สำเร็จ', 'error');
            }
        }
    });
};

onMounted(() => {
    fetchBranches();
});
</script>