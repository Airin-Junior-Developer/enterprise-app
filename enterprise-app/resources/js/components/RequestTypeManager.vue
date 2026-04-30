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
                        <th class="p-5 font-bold w-20 text-center">ลำดับ</th>
                        <th class="p-5 font-bold">ชื่อประเภทคำร้อง</th>
                        <th class="p-5 font-bold text-center">รูปแบบ</th>
                        <th class="p-5 font-bold text-center w-40">สถานะ</th>
                        <th class="p-5 font-bold text-center w-32">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="(type, index) in types" :key="type.id"
                        class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-5 text-center text-slate-400 font-mono text-sm">{{ index + 1 }}</td>
                        <td class="p-5 font-bold text-slate-700">{{ type.Name_Type }}</td>

                        <td class="p-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border"
                                :class="type.category === 'money' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-blue-50 text-blue-600 border-blue-200'">
                                {{ type.category === 'money' ? 'เบิกเงิน' : 'ระบุวัน' }}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <button @click="toggleStatus(type)"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                :class="type.is_active ? 'bg-emerald-500' : 'bg-slate-200'">
                                <span class="sr-only">Enable status</span>
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
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openModal(type)"
                                    class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                    title="แก้ไข">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="deleteType(type.id)"
                                    class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                    title="ลบ">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="types.length === 0">
                        <td colspan="4" class="p-10 text-center text-slate-400">ไม่พบข้อมูล</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-clear-sm p-4 transition-all">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden animate-fade-in-up">
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800">{{ isEditMode ? 'แก้ไขประเภท' : 'เพิ่มประเภทใหม่' }}
                    </h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ชื่อประเภทคำร้อง <span
                                class="text-rose-500">*</span></label>
                        <input type="text" v-model="form.Name_Type"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition-all"
                            placeholder="เช่น ลาพักร้อน, เบิกค่าเดินทาง..." />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">รูปแบบคำร้อง <span
                                class="text-rose-500">*</span></label>
                        <select v-model="form.category"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                            <option value="date">ระบุช่วงวัน (เช่น การลา)</option>
                            <option value="money">ระบุจำนวนเงิน (เช่น เบิกค่าใช้จ่าย)</option>
                        </select>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                    <button @click="closeModal"
                        class="flex-1 px-4 py-2.5 border border-slate-200 bg-white rounded-xl font-bold text-slate-600">ยกเลิก</button>
                    <button @click="saveType"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg transition-all">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

// --- States ---
const types = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const form = ref({ id: null, Name_Type: '', category: 'date', is_active: true });

// ดึงข้อมูลประเภทคำร้องทั้งหมด
const fetchTypes = async () => {
    try {
        const res = await axios.get('/api/request-types/all');

        // ✅ นำข้อมูลมาเรียงลำดับตาม id จากน้อยไปมาก (รายการใหม่จะอยู่ล่างสุด)
        const sortedData = res.data.sort((a, b) => a.id - b.id);

        // ✅ แปลงค่า boolean ให้ปุ่ม Toggle ทำงานได้ปกติ
        types.value = sortedData.map(t => ({ ...t, is_active: Boolean(t.is_active) }));

    } catch (e) {
        console.error("Fetch Error:", e);
    }
};

// ฟังก์ชันเปิด/ปิดการใช้งาน (Toggle)
const toggleStatus = async (type) => {
    try {
        // Optimistic UI update: เปลี่ยนที่หน้าจอทันทีเพื่อความรวดเร็ว
        const originalStatus = type.is_active;
        type.is_active = !type.is_active;

        await axios.patch(`/api/request-types/${type.id}/toggle`, {
            is_active: type.is_active
        });

        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        Toast.fire({ icon: 'success', title: 'อัปเดตสถานะสำเร็จ' });
    } catch (e) {
        // ถ้า API Error ให้เปลี่ยนสถานะกลับคืน
        type.is_active = !type.is_active;
        Swal.fire('Error', 'ไม่สามารถอัปเดตสถานะได้', 'error');
    }
};

// จัดการ Modal
const openModal = (type = null) => {
    if (type) {
        isEditMode.value = true;
        form.value = { ...type };
    } else {
        isEditMode.value = false;
        form.value = { id: null, Name_Type: '', category: 'date', is_active: true };
    }
    showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

// บันทึกข้อมูล (Create/Update)
const saveType = async () => {
    if (!form.value.Name_Type) return Swal.fire('แจ้งเตือน', 'กรุณาระบุชื่อประเภทคำร้อง', 'warning');

    try {
        if (isEditMode.value) {
            await axios.put(`/api/request-types/${form.value.id}`, form.value);
            Swal.fire({ icon: 'success', title: 'แก้ไขข้อมูลสำเร็จ', showConfirmButton: false, timer: 1500 });
        } else {
            await axios.post('/api/request-types', form.value);
            Swal.fire({ icon: 'success', title: 'เพิ่มประเภทใหม่เรียบร้อย', showConfirmButton: false, timer: 1500 });
        }
        closeModal();
        fetchTypes();
    } catch (e) {
        const errorMsg = e.response?.data?.message || 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์';
        Swal.fire('Error', errorMsg, 'error');
    }
};

const deleteType = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "คุณแน่ใจหรือไม่ที่จะลบประเภทคำร้องนี้?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/request-types/${id}`);
                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1500 });
                fetchTypes();
            } catch (e) {
                Swal.fire('Error', e.response?.data?.message || 'ไม่สามารถลบได้ (อาจมีการใช้งานอยู่)', 'error');
            }
        }
    });
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