<template>
    <div class="p-6 bg-[#F8FAFC] min-h-screen font-sans text-slate-800">
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">ข้อมูลสาขา</h2>
                    <p class="text-slate-500 mt-1 text-sm">จัดการรายละเอียดและที่ตั้งของสาขาทั้งหมด {{ branches.length
                        }} แห่ง</p>
                </div>
                <button @click="openModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-blue-200 transition-all font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>เพิ่มสาขาใหม่</span>
                </button>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" v-model="searchQuery"
                        class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none text-slate-600 shadow-sm transition-all"
                        placeholder="ค้นหาชื่อสาขา..." />
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="flex justify-center py-20">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
        </div>

        <div v-else-if="filteredBranches.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div v-for="branch in filteredBranches" :key="branch.branch_id"
                class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group relative overflow-hidden">

                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform duration-500">
                </div>

                <div class="flex items-start justify-between relative z-10">
                    <div class="flex gap-4">
                        <div
                            class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-blue-200">
                            {{ branch.branch_name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">{{ branch.branch_name }}</h3>
                            <div class="flex items-center gap-1.5 mt-1 text-sm text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ branch.address || 'ไม่ระบุที่อยู่' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-1">
                        <button @click="openModal(branch)"
                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button @click="deleteBranch(branch.branch_id)"
                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-50 flex justify-between items-center text-sm">
                    <span class="text-slate-400">ID: #{{ String(branch.branch_id).padStart(4, '0') }}</span>
                    <span
                        class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 font-medium text-xs border border-emerald-100">Active</span>
                </div>
            </div>
        </div>

        <div v-else class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
            <div class="flex flex-col items-center opacity-40">
                <div class="text-5xl mb-4">🔎</div>
                <h3 class="text-lg font-bold text-slate-800">ไม่พบข้อมูลสาขา</h3>
                <p class="text-sm text-slate-500">ลองเปลี่ยนคำค้นหาหรือเพิ่มสาขาใหม่เข้าสู่ระบบ</p>
            </div>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-clear-sm p-4 transition-all">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in-up">
                <div class="bg-white px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-xl text-slate-800">{{ isEditMode ? 'แก้ไขข้อมูล' : 'สร้างสาขาใหม่' }}</h3>
                    <button @click="closeModal"
                        class="h-8 w-8 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อสาขา <span
                                class="text-rose-500">*</span></label>
                        <input type="text" v-model="form.branch_name"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none"
                            placeholder="เช่น สำนักงานใหญ่..." />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ที่อยู่ / สถานที่ตั้ง</label>
                        <textarea v-model="form.address" rows="2"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 outline-none"
                            placeholder="ระบุที่ตั้งสาขา..."></textarea>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                    <button @click="closeModal"
                        class="flex-1 px-4 py-3 border border-slate-200 bg-white rounded-xl text-slate-600 font-bold">ยกเลิก</button>
                    <button @click="saveBranch"
                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg font-bold flex justify-center items-center gap-2">
                        <span>บันทึกข้อมูล</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted, computed } from 'vue';

const branches = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const isLoading = ref(true);
const searchQuery = ref('');
const form = ref({ branch_id: null, branch_name: '', address: '' });

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

const filteredBranches = computed(() => {
    if (!searchQuery.value) return branches.value;
    const lowerQuery = searchQuery.value.toLowerCase();
    return branches.value.filter(b => b.branch_name.toLowerCase().includes(lowerQuery));
});

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
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out;
}
</style>