<template>
    <div class="p-6 bg-[#F8FAFC] min-h-screen font-sans text-slate-800">

        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">ตำแหน่งงาน (Positions)</h2>
                    <p class="text-slate-500 mt-1 text-sm">กำหนดบทบาทและหน้าที่รับผิดชอบในองค์กร ทั้งหมด {{
                        positions.length }} ตำแหน่ง</p>
                </div>
                <button @click="openModal()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2 font-bold group">
                    <div class="bg-indigo-500 rounded-lg p-1 group-hover:bg-indigo-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span>เพิ่มตำแหน่งใหม่</span>
                </button>
            </div>

            <div class="mt-6 max-w-md relative">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" v-model="searchQuery"
                    class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none text-slate-600 shadow-sm transition-all"
                    placeholder="ค้นหาชื่อตำแหน่ง..." />
            </div>
        </div>

        <div v-if="isLoading" class="flex justify-center py-20">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else-if="filteredPositions.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div v-for="position in filteredPositions" :key="position.position_id"
                class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group relative overflow-hidden">

                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform duration-500">
                </div>

                <div class="flex items-start justify-between relative z-10">
                    <div class="flex gap-4 items-center">
                        <div
                            class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-indigo-200">
                            {{ position.position_name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-800">{{ position.position_name }}</h3>
                            <p class="text-xs text-slate-400 mt-1">ID: {{ String(position.position_id).padStart(4, '0')
                                }}</p>
                        </div>
                    </div>

                    <div class="flex gap-1">
                        <button @click="openModal(position)"
                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button @click="deletePosition(position.position_id)"
                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">ไม่พบข้อมูลตำแหน่ง</h3>
            <button @click="openModal()" class="text-indigo-600 font-bold hover:underline mt-2">สร้างตำแหน่งแรก
                +</button>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-all">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in-up">

                <div class="bg-white px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-xl text-slate-800">{{ isEditMode ? 'แก้ไขตำแหน่ง' : 'สร้างตำแหน่งใหม่' }}
                    </h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อตำแหน่ง <span
                                class="text-rose-500">*</span></label>
                        <input type="text" v-model="form.position_name"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none text-slate-700 font-medium transition-all"
                            placeholder="เช่น Programmer, HR Manager..." />
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                    <button @click="closeModal"
                        class="flex-1 px-4 py-3 border border-slate-200 bg-white rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition-all">ยกเลิก</button>
                    <button @click="savePosition"
                        class="flex-1 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200 font-bold transition-all transform active:scale-95">บันทึก</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted, computed } from 'vue';

const positions = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const isLoading = ref(true);
const searchQuery = ref('');
const form = ref({ position_id: null, position_name: '' });

// Fetch Data
const fetchPositions = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get('/api/positions');
        positions.value = res.data;
    } catch (e) {
        console.error(e);
        if (positions.value.length === 0) positions.value = [];
    } finally {
        isLoading.value = false;
    }
};

// Filter Logic
const filteredPositions = computed(() => {
    if (!searchQuery.value) return positions.value;
    const lowerQuery = searchQuery.value.toLowerCase();
    return positions.value.filter(p =>
        p.position_name.toLowerCase().includes(lowerQuery)
    );
});

// Modal Actions
const openModal = (pos = null) => {
    if (pos) {
        isEditMode.value = true;
        form.value = { ...pos };
    } else {
        isEditMode.value = false;
        form.value = { position_id: null, position_name: '' };
    }
    showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

// Save Data
const savePosition = async () => {
    if (!form.value.position_name) return Swal.fire('แจ้งเตือน', 'กรุณาระบุชื่อตำแหน่ง', 'warning');

    try {
        if (isEditMode.value) {
            await axios.put(`/api/positions/${form.value.position_id}`, form.value);
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'แก้ไขข้อมูลเรียบร้อย', timer: 1500, showConfirmButton: false });
        } else {
            await axios.post('/api/positions', form.value);
            Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'เพิ่มข้อมูลเรียบร้อย', timer: 1500, showConfirmButton: false });
        }
        closeModal();
        fetchPositions();
    } catch (error) {
        Swal.fire('เกิดข้อผิดพลาด', error.response?.data?.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
    }
};

// Delete Data
const deletePosition = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "ข้อมูลนี้จะหายไปถาวร!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'ลบข้อมูล',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/positions/${id}`);
                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1000 });
                fetchPositions();
            } catch (e) {
                Swal.fire('Error', 'ไม่สามารถลบได้ (อาจมีการใช้งานอยู่)', 'error');
            }
        }
    });
};

onMounted(() => {
    fetchPositions();
});
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