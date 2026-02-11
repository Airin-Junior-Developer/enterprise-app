<template>
    <div class="p-6 bg-[#F8FAFC] min-h-screen font-sans text-slate-800">

        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">จัดการประเภทคำร้อง</h2>
                <p class="text-slate-500 mt-1 text-sm">กำหนดประเภทเอกสารที่พนักงานสามารถยื่นได้</p>
            </div>
            <button @click="openModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-blue-200 transition-all font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                เพิ่มประเภทใหม่
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-sm border-b border-slate-100">
                        <th class="p-5 font-bold w-20 text-center">ID</th>
                        <th class="p-5 font-bold">ชื่อประเภทคำร้อง</th>
                        <th class="p-5 font-bold text-center w-40">สถานะ</th>
                        <th class="p-5 font-bold text-center w-32">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="type in types" :key="type.id" class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-5 text-center text-slate-400 font-mono text-sm">{{ type.id }}</td>
                        <td class="p-5 font-bold text-slate-700">{{ type.Name_Type }}</td>

                        <td class="p-5 text-center">
                            <button @click="toggleStatus(type)"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                :class="type.is_active ? 'bg-emerald-500' : 'bg-slate-200'">
                                <span class="sr-only">Enable notifications</span>
                                <span
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out shadow-sm"
                                    :class="type.is_active ? 'translate-x-6' : 'translate-x-1'" />
                            </button>
                            <div class="text-[10px] mt-1 font-bold"
                                :class="type.is_active ? 'text-emerald-600' : 'text-slate-400'">
                                {{ type.is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                            </div>
                        </td>

                        <td class="p-5 text-center">
                            <button @click="openModal(type)"
                                class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="types.length === 0">
                        <td colspan="4" class="p-10 text-center text-slate-400">ไม่พบข้อมูล</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-clear-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden animate-fade-in-up">
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800">{{ isEditMode ? 'แก้ไขประเภท' : 'เพิ่มประเภทใหม่' }}
                    </h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>
                <div class="p-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อประเภทคำร้อง</label>
                    <input type="text" v-model="form.Name_Type"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none"
                        placeholder="ระบุชื่อ..." />
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                    <button @click="closeModal"
                        class="flex-1 px-4 py-2.5 border border-slate-200 bg-white rounded-xl font-bold text-slate-600 hover:bg-slate-50">ยกเลิก</button>
                    <button @click="saveType"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200">บันทึก</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const types = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const form = ref({ id: null, Name_Type: '' });

// โหลดข้อมูล
const fetchTypes = async () => {
    try {
        // ใช้ Route ใหม่ที่สร้างสำหรับ Admin (ดึงทั้งหมด)
        const res = await axios.get('/api/request-types/all');
        types.value = res.data;
    } catch (e) {
        console.error(e);
    }
};

// เปิด/ปิด การใช้งาน
const toggleStatus = async (type) => {
    try {
        // เปลี่ยนค่าใน Frontend ก่อนเพื่อให้ดูลื่นไหล (Optimistic UI)
        type.is_active = !type.is_active;

        await axios.patch(`/api/request-types/${type.id}/toggle`);

        // แจ้งเตือนเล็กๆ มุมขวา
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        Toast.fire({ icon: 'success', title: 'อัปเดตสถานะเรียบร้อย' });

    } catch (e) {
        // ถ้า Error ให้เปลี่ยนค่ากลับคืน
        type.is_active = !type.is_active;
        Swal.fire('Error', 'ไม่สามารถเปลี่ยนสถานะได้', 'error');
    }
};

const openModal = (type = null) => {
    if (type) {
        isEditMode.value = true;
        form.value = { ...type };
    } else {
        isEditMode.value = false;
        form.value = { id: null, Name_Type: '' };
    }
    showModal.value = true;
};

const closeModal = () => showModal.value = false;

const saveType = async () => {
    if (!form.value.Name_Type) return Swal.fire('เตือน', 'กรุณาระบุชื่อประเภท', 'warning');

    try {
        if (isEditMode.value) {
            await axios.put(`/api/request-types/${form.value.id}`, form.value);
        } else {
            await axios.post('/api/request-types', form.value);
        }
        Swal.fire({ icon: 'success', title: 'สำเร็จ', showConfirmButton: false, timer: 1000 });
        closeModal();
        fetchTypes();
    } catch (e) {
        // ให้มันโชว์ข้อความ Error จากหลังบ้านออกมาเลย
        let errorMsg = e.response?.data?.message || 'เกิดข้อผิดพลาด';
        Swal.fire('Error', errorMsg, 'error');
        console.error("รายละเอียด Error:", e.response?.data);
    }
};

onMounted(() => {
    fetchTypes();
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