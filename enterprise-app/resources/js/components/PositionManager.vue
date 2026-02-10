<template>
    <div class="p-8 bg-[#F3F4F6] min-h-screen font-sans text-slate-700">
        <div class="bg-white p-6 rounded-t-2xl border-b border-slate-200">
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
                <div v-if="isLoading" class="text-sm text-slate-400">กำลังโหลด...</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-sm">
                            <th class="px-6 py-4 font-semibold border-b w-16">ลำดับ</th>
                            <th class="px-6 py-4 font-semibold border-b">ตำแหน่ง (ไทย)</th>
                            <th class="px-6 py-4 font-semibold border-b">ตำแหน่ง (อังกฤษ)</th>
                            <th class="px-6 py-4 font-semibold border-b text-center">Level</th>
                            <th class="px-6 py-4 font-semibold border-b text-center">สถานะ</th>
                            <th class="px-6 py-4 font-semibold border-b text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="(pos, index) in filteredPositions" :key="pos.position_id"
                            class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-400">{{ index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ pos.position_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ pos.position_name_en || '-' }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded text-xs font-bold text-slate-600">
                                    {{ pos.level_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span v-if="pos.is_active"
                                    class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-bold border border-green-100">ใช้งาน</span>
                                <span v-else
                                    class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold border border-slate-200">ปิดใช้งาน</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-4">
                                    <button @click="openModal(pos)"
                                        class="flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        แก้ไข
                                    </button>
                                    <button @click="deletePosition(pos.position_id)"
                                        class="flex items-center gap-1 text-slate-400 hover:text-rose-600 text-sm font-medium transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        ลบ
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
                    <h3 class="font-bold text-[#2B54A3] text-lg">{{ isEditMode ? 'แก้ไขตำแหน่ง' : 'สร้างตำแหน่งใหม่' }}
                    </h3>
                    <button @click="closeModal"
                        class="text-slate-400 hover:text-slate-600 text-2xl transition-colors">&times;</button>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (ไทย) <span
                                class="text-rose-500">*</span></label>
                        <input type="text" v-model="form.position_name" placeholder="เช่น ผู้จัดการฝ่ายขาย"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all" />
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (อังกฤษ)</label>
                        <input type="text" v-model="form.position_name_en" placeholder="e.g. Sales Manager"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all" />
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ระดับ (Level) <span
                                class="text-rose-500">*</span></label>
                        <select v-model="form.level_code"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none bg-white">
                            <option value="" disabled>-- เลือกระดับ --</option>
                            <option value="CEO">CEO (ผู้บริหารระดับสูง)</option>
                            <option value="M">Manager (ผู้จัดการ)</option>
                            <option value="O1">Officer (เจ้าหน้าที่)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ประเภทการจ้าง</label>
                        <select v-model="form.employment_type_id"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none bg-white">
                            <option :value="null" disabled>-- เลือกประเภท --</option>
                            <option v-for="type in masterData.employment_types" :key="type.id" :value="type.id">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">ประเภทพนักงาน</label>
                        <select v-model="form.employee_category_id"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none bg-white">
                            <option :value="null" disabled>-- เลือกประเภท --</option>
                            <option v-for="cat in masterData.employee_categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2 mt-2 pt-4 border-t border-slate-100">
                        <p class="text-sm font-bold text-[#2B54A3] mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd" />
                            </svg>
                            โครงสร้างเงินเดือน (Salary Base)
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">ต่ำสุด
                                    (Min)</label>
                                <div class="relative">
                                    <input type="number" v-model="form.min_salary" placeholder="0.00"
                                        class="w-full pl-4 pr-12 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 outline-none font-mono" />
                                    <span
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs">THB</span>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">สูงสุด
                                    (Max)</label>
                                <div class="relative">
                                    <input type="number" v-model="form.max_salary" placeholder="0.00"
                                        class="w-full pl-4 pr-12 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 outline-none font-mono" />
                                    <span
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs">THB</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="col-span-1 md:col-span-2 flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-700">สถานะการใช้งาน</span>
                            <span class="text-xs text-slate-500">เปิด/ปิด การใช้งานตำแหน่งนี้ในระบบ</span>
                        </div>
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
                        class="px-6 py-2.5 border border-slate-300 text-slate-600 rounded-full text-sm font-bold hover:bg-white hover:border-slate-400 transition-all">ยกเลิก</button>
                    <button @click="savePosition"
                        class="px-8 py-2.5 bg-[#2B54A3] text-white rounded-full text-sm font-bold hover:bg-[#1E3A8A] shadow-lg shadow-blue-200 hover:shadow-xl transition-all transform active:scale-95">
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

const masterData = ref({
    employment_types: [],
    employee_categories: []
});

// ✅ แก้ไข: ชื่อตัวแปรต้องตรงกับ DB (_id) และ Default ต้องเป็น null
const form = ref({
    position_id: null,
    position_name: '',
    position_name_en: '',
    level_code: '',
    employment_type_id: null,   // ✅ แก้จาก employment_type
    employee_category_id: null, // ✅ แก้จาก employee_category
    min_salary: '',
    max_salary: '',
    is_active: true
});

const fetchMasterData = async () => {
    try {
        const res = await axios.get('/api/master-data');
        masterData.value = res.data;
    } catch (e) {
        console.error("Master Data Error:", e);
    }
};

const fetchPositions = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get('/api/positions');
        positions.value = res.data.map(p => ({
            ...p,
            is_active: Boolean(p.is_active)
        }));
    } catch (e) {
        console.error("Fetch Error:", e);
        if (positions.value.length === 0) positions.value = [];
    } finally {
        isLoading.value = false;
    }
};

const filteredPositions = computed(() => {
    if (!searchQuery.value) return positions.value;
    const query = searchQuery.value.toLowerCase();
    return positions.value.filter(p =>
        p.position_name.includes(query) ||
        (p.position_name_en && p.position_name_en.toLowerCase().includes(query))
    );
});

const openModal = (pos = null) => {
    if (pos) {
        isEditMode.value = true;
        form.value = { ...pos };
    } else {
        isEditMode.value = false;
        // ✅ แก้ไข: Reset ให้ชื่อตัวแปรถูกต้องและเป็น null
        form.value = {
            position_id: null,
            position_name: '',
            position_name_en: '',
            level_code: '',
            employment_type_id: null,   // ✅ ต้องเป็น _id และ null
            employee_category_id: null, // ✅ ต้องเป็น _id และ null
            min_salary: '',
            max_salary: '',
            is_active: true
        };
    }
    showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const savePosition = async () => {
    if (!form.value.position_name) return Swal.fire('แจ้งเตือน', 'กรุณาระบุชื่อตำแหน่ง (ไทย)', 'warning');
    if (!form.value.level_code) return Swal.fire('แจ้งเตือน', 'กรุณาเลือกระดับ (Level)', 'warning');

    try {
        if (isEditMode.value) {
            await axios.put(`/api/positions/${form.value.position_id}`, form.value);
            Swal.fire({ icon: 'success', title: 'แก้ไขสำเร็จ', showConfirmButton: false, timer: 1000 });
        } else {
            await axios.post('/api/positions', form.value);
            Swal.fire({ icon: 'success', title: 'สร้างสำเร็จ', showConfirmButton: false, timer: 1000 });
        }
        closeModal();
        fetchPositions();
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
    }
};

const deletePosition = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "ข้อมูลนี้จะถูกลบถาวร",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'ลบข้อมูล',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/positions/${id}`);
                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1000 });
                fetchPositions();
            } catch (e) {
                Swal.fire('Error', 'ไม่สามารถลบได้', 'error');
            }
        }
    });
};

onMounted(() => {
    fetchPositions();
    fetchMasterData();
});
</script>