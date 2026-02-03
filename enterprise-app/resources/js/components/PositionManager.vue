<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-800">

        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">จัดการตำแหน่งงาน (Positions)</h2>
                <p class="text-sm text-slate-500">เพิ่ม ลบ แก้ไข รายชื่อตำแหน่งในบริษัท</p>
            </div>
            <button @click="openModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-lg shadow-blue-200 transition-all flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                เพิ่มตำแหน่งใหม่
            </button>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">#</th>
                            <th class="px-6 py-4">ชื่อตำแหน่ง</th>
                            <th class="px-6 py-4 text-center w-40">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="(position, index) in positions" :key="position.position_id"
                            class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center text-slate-400">{{ index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">{{ position.position_name }}</td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <button @click="openModal(position)"
                                    class="p-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button @click="deletePosition(position.position_id)"
                                    class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="positions.length === 0">
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                ยังไม่มีข้อมูลตำแหน่ง
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in-up">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800">{{ isEditMode ? 'แก้ไขตำแหน่ง' : 'เพิ่มตำแหน่งใหม่' }}
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
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">ชื่อตำแหน่ง</label>
                        <input type="text" v-model="form.position_name"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none text-slate-600"
                            placeholder="ระบุชื่อตำแหน่ง..." />
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button @click="closeModal"
                            class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition-all">ยกเลิก</button>
                        <button @click="savePosition"
                            class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-200 font-bold transition-all">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const positions = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const form = ref({ position_id: null, position_name: '' });

// โหลดข้อมูล
const fetchPositions = async () => {
    try {
        const res = await axios.get('/api/positions');
        positions.value = res.data;
    } catch (e) { console.error(e); }
};

// เปิด Modal
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

// บันทึกข้อมูล
const savePosition = async () => {
    if (!form.value.position_name) return Swal.fire('แจ้งเตือน', 'กรุณาระบุชื่อตำแหน่ง', 'warning');

    try {
        if (isEditMode.value) {
            await axios.put(`/api/positions/${form.value.position_id}`, form.value);
            Swal.fire('สำเร็จ', 'แก้ไขข้อมูลเรียบร้อย', 'success');
        } else {
            await axios.post('/api/positions', form.value);
            Swal.fire('สำเร็จ', 'เพิ่มข้อมูลเรียบร้อย', 'success');
        }
        closeModal();
        fetchPositions();
    } catch (error) {
        Swal.fire('เกิดข้อผิดพลาด', error.response?.data?.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
    }
};

// ลบข้อมูล
const deletePosition = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "ข้อมูลตำแหน่งนี้จะหายไปถาวร!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        confirmButtonText: 'ลบข้อมูล',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/positions/${id}`);
                Swal.fire('ลบสำเร็จ', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success');
                fetchPositions();
            } catch (e) {
                Swal.fire('Error', 'ไม่สามารถลบข้อมูลได้ (อาจมีการใช้งานอยู่)', 'error');
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