<template>
    <div class="p-8 bg-[#F3F4F6] min-h-screen font-sans text-slate-700">
        <div class="bg-white p-6 rounded-t-2xl border-b border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-6">
                <div class="bg-slate-100 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#1E293B]">จัดการตำแหน่งงาน</h2>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-80">
                        <input type="text" v-model="searchQuery" placeholder="ค้นหาชื่อตำแหน่ง..."
                            class="w-full pl-4 pr-10 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm" />
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <button @click="openModal()"
                    class="bg-[#2B54A3] hover:bg-[#1E3A8A] text-white px-5 py-2 rounded-full flex items-center gap-2 text-sm font-medium transition-colors shadow-sm">
                    <span class="text-lg">+</span> สร้างตำแหน่ง
                </button>
            </div>
        </div>

        <div class="bg-white rounded-b-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-[#2B54A3] font-bold">ตำแหน่งทั้งหมด {{ filteredPositions.length }} รายการ</h3>
                <div v-if="isLoading" class="text-sm text-slate-400 font-bold animate-pulse">กำลังโหลด...</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-sm">
                            <th class="px-6 py-4 font-semibold border-b w-16 text-center">ลำดับ</th>
                            <th class="px-6 py-4 font-semibold border-b">ตำแหน่ง (ไทย)</th>
                            <th class="px-6 py-4 font-semibold border-b">ตำแหน่ง (อังกฤษ)</th>
                            <th class="px-6 py-4 font-semibold border-b text-center">Level</th>
                            <th class="px-6 py-4 font-semibold border-b text-center">สถานะ</th>
                            <th class="px-6 py-4 font-semibold border-b text-center w-32">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="(pos, index) in filteredPositions" :key="pos.position_id"
                            class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-400 text-center">{{ index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ pos.position_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ pos.position_name_en || '-' }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded text-xs font-bold text-slate-600">
                                    {{ pos.level_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span v-if="pos.is_active"
                                    class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-bold border border-green-100">
                                    ใช้งาน
                                </span>
                                <span v-else
                                    class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold border border-slate-200">
                                    ปิดใช้งาน
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openModal(pos)"
                                        class="h-9 w-9 flex items-center justify-center rounded-xl bg-slate-100 text-lg hover:bg-blue-500 hover:text-white transition-all duration-200">
                                        ✏️
                                    </button>

                                    <button @click="deletePosition(pos.position_id)"
                                        class="h-9 w-9 flex items-center justify-center rounded-xl bg-slate-100 text-lg hover:bg-rose-500 hover:text-white transition-all duration-200">
                                        🗑️
                                    </button>
                                </div>
                            </td> 
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-all">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-[#2B54A3] text-lg">{{ isEditMode ? 'แก้ไขตำแหน่ง' : 'สร้างตำแหน่งใหม่' }}</h3>
                    <button @click="closeModal"
                        class="text-slate-400 hover:text-slate-600 text-2xl transition-colors">&times;</button>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (ไทย) <span class="text-rose-500">*</span></label>
                        <input type="text" v-model="form.position_name" placeholder="เช่น ผู้จัดการฝ่ายขาย"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all" />
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (อังกฤษ)</label>
                        <input type="text" v-model="form.position_name_en" placeholder="e.g. Sales Manager"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all" />
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ระดับ (Level) <span class="text-rose-500">*</span></label>
                        <select v-model="form.level_code"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none bg-white">
                            <option value="" disabled>-- เลือกระดับ --</option>
                            <option value="CEO">CEO (ผู้บริหารระดับสูง)</option>
                            <option value="MGR">Manager (ผู้จัดการ)</option>
                            <option value="SUP">Supervisor (หัวหน้างาน)</option>
                            <option value="STF">Staff (พนักงาน)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ลำดับความสำคัญ (1=สูงสุด)</label>
                        <input type="number" v-model="form.priority_level" min="1" max="99"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none" />
                    </div>

                    <div
                        class="col-span-1 md:col-span-2 flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-sm font-bold text-slate-700">สถานะการใช้งาน</span>
                        <div @click="form.is_active = !form.is_active"
                            :class="form.is_active ? 'bg-green-500' : 'bg-slate-300'"
                            class="w-12 h-7 rounded-full relative cursor-pointer transition-colors shadow-inner">
                            <div :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"
                                class="absolute top-1 w-5 h-5 bg-white rounded-full transition-transform shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button @click="closeModal"
                        class="px-6 py-2.5 border rounded-full text-sm font-bold">ยกเลิก</button>
                    <button @click="savePosition"
                        class="px-8 py-2.5 bg-[#2B54A3] text-white rounded-full text-sm font-bold shadow-lg shadow-blue-200">
                        {{ isEditMode ? 'บันทึกการแก้ไข' : 'ยืนยันสร้าง' }}
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

const positions = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const isLoading = ref(true);
const searchQuery = ref('');

const form = ref({
    position_id: null,
    position_name: '',
    position_name_en: '',
    level_code: '',
    is_active: true
});

const fetchPositions = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get('/api/positions');
        positions.value = res.data.map(p => ({
            ...p,
            is_active: Number(p.is_active) === 1
        }));
    } catch (e) {
        console.error("Fetch Error:", e);
    } finally {
        isLoading.value = false;
    }
};

const filteredPositions = computed(() => {
    if (!searchQuery.value) return positions.value;
    const query = searchQuery.value.toLowerCase();
    return positions.value.filter(p =>
        (p.position_name || '').toLowerCase().includes(query) ||
        (p.position_name_en || '').toLowerCase().includes(query)
    );
});

const openModal = (pos = null) => {
    if (pos) {
        isEditMode.value = true;
        form.value = { ...pos, is_active: !!pos.is_active };
    } else {
        isEditMode.value = false;
        form.value = { position_id: null, position_name: '', position_name_en: '', level_code: '', is_active: true };
    }
    showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const savePosition = async () => {
    if (!form.value.position_name || !form.value.level_code) return Swal.fire('แจ้งเตือน', 'กรุณาระบุข้อมูลให้ครบถ้วน', 'warning');
    try {
        if (isEditMode.value) {
            await axios.put(`/api/positions/${form.value.position_id}`, form.value);
        } else {
            await axios.post('/api/positions', form.value);
        }
        Swal.fire({ icon: 'success', title: 'สำเร็จ', showConfirmButton: false, timer: 1000 });
        closeModal();
        fetchPositions();
    } catch (e) {
        Swal.fire('Error', 'ไม่สามารถบันทึกได้', 'error');
    }
};

const deletePosition = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/positions/${id}`);
                fetchPositions();
            } catch (e) { Swal.fire('Error', 'ไม่สามารถลบได้', 'error'); }
        }
    });
};

onMounted(() => fetchPositions());
</script>

<style scoped>
.animate-in { animation: zoomIn 0.2s ease-out; }
@keyframes zoomIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>