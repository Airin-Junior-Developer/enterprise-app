<template>
    <div class="px-8 py-10 bg-[#F4F6F8] min-h-screen font-sans">
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-[32px] font-bold text-slate-800 tracking-tight leading-tight">จัดการตำแหน่งงาน</h1>
                <p class="text-[15px] font-medium text-slate-500 mt-1">กำหนดและบริหารโครงสร้างตำแหน่งงานภายในองค์กร</p>
            </div>

            <div class="flex gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-80">
                    <input type="text" v-model="searchQuery" placeholder="ค้นหาชื่อตำแหน่ง..."
                        class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-[14px] shadow-sm font-medium text-slate-700 placeholder:text-slate-400" />
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <button @click="openModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl shadow-sm border border-transparent flex items-center gap-2 text-[14px] font-bold transition-all active:scale-95 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    สร้างตำแหน่ง
                </button>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-[17px] font-bold text-slate-800">ตำแหน่งทั้งหมด {{ filteredPositions.length }}
                        รายการ</h3>
                </div>
                <div v-if="isLoading"
                    class="flex items-center gap-2 text-[13px] font-bold text-blue-500 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100">
                    <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    กำลังโหลด...
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 w-16">
                                ลำดับ</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                ตำแหน่ง (ไทย)</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                ตำแหน่ง (อังกฤษ)</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">
                                Level</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">
                                สถานะ</th>
                            <th
                                class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">
                                จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="(pos, index) in filteredPositions" :key="pos.position_id"
                            class="hover:bg-slate-50/70 transition-colors group">
                            <td class="px-8 py-4 text-[14px] font-medium text-slate-400">{{ index + 1 }}</td>
                            <td class="px-8 py-4 text-[14px] font-bold text-slate-700">{{ pos.position_name }}</td>
                            <td class="px-8 py-4 text-[14px] font-medium text-slate-500">{{ pos.position_name_en || '-'
                            }}</td>
                            <td class="px-8 py-4 text-center">
                                <span
                                    class="px-2.5 py-1 bg-slate-100 rounded border border-slate-200/60 text-[11px] font-bold text-slate-600 tracking-wider">
                                    {{ pos.level_code }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span v-if="pos.is_active == 1 || pos.is_active === true"
                                    class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-200 inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    ใช้งาน
                                </span>
                                <span v-else
                                    class="px-3.5 py-1.5 bg-slate-100 text-slate-500 rounded-full text-[11px] font-bold border border-slate-200 inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    ระงับ
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openModal(pos)"
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="แก้ไข">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button @click="deletePosition(pos.position_id)"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="ลบ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredPositions.length === 0 && !isLoading">
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-4 text-slate-300"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="font-medium text-[14px]">ไม่พบข้อมูลตำแหน่งงาน</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-100 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="closeModal"></div>
            <div
                class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden z-10 animate-scale-up flex flex-col max-h-[90vh]">
                <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                        <h3 class="font-bold text-slate-800 text-lg">{{ isEditMode ? 'แก้ไขตำแหน่งงาน' :
                            'สร้างตำแหน่งงานใหม่' }}</h3>
                    </div>
                    <button @click="closeModal"
                        class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6 overflow-y-auto custom-scrollbar">
                    <div class="space-y-1.5">
                        <label class="text-[13px] font-bold text-slate-700">ชื่อตำแหน่ง (ไทย) <span
                                class="text-rose-500">*</span></label>
                        <input type="text" v-model="form.position_name" placeholder="เช่น ผู้จัดการฝ่ายขาย"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400 font-medium" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-bold text-slate-700">ชื่อตำแหน่ง (อังกฤษ)</label>
                        <input type="text" v-model="form.position_name_en" placeholder="e.g. Sales Manager"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400 font-medium" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-bold text-slate-700">ระดับ (Level) <span
                                class="text-rose-500">*</span></label>
                        <select v-model="form.level_code"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium">
                            <option value="" disabled>-- เลือกระดับ --</option>
                            <option value="CEO">CEO (ผู้บริหารระดับสูง)</option>
                            <option value="MGR">Manager (ผู้จัดการ)</option>
                            <option value="SUP">Supervisor (หัวหน้างาน)</option>
                            <option value="STF">Staff (พนักงาน)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-bold text-slate-700">ลำดับความสำคัญ (1=สูงสุด)</label>
                        <input type="number" v-model="form.priority_level" min="1" max="99"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-bold text-slate-700">ประเภทการจ้าง</label>
                        <select v-model="form.employment_type_id"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium">
                            <option :value="null">-- เลือกประเภท --</option>
                            <option v-for="type in masterData.employment_types" :key="type.id" :value="type.id">{{
                                type.name }}</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[13px] font-bold text-slate-700">ประเภทพนักงาน</label>
                        <select v-model="form.employee_category_id"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none font-medium">
                            <option :value="null">-- เลือกประเภท --</option>
                            <option v-for="cat in masterData.employee_categories" :key="cat.id" :value="cat.id">{{
                                cat.name }}</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2 mt-4">
                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
                            <h4 class="text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-4">
                                โครงสร้างเงินเดือน (Salary Base)</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">ขั้นต่ำ (Min)</label>
                                    <input type="number" v-model="form.min_salary" placeholder="0"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:border-blue-500 outline-none text-[14px] font-medium" />
                                </div>
                                <div>
                                    <label class="block text-[12px] font-bold text-slate-500 mb-1">ขั้นสูง (Max)</label>
                                    <input type="number" v-model="form.max_salary" placeholder="0"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:border-blue-500 outline-none text-[14px] font-medium" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="col-span-1 md:col-span-2 flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-slate-100 mt-2">
                        <span class="text-[14px] font-bold text-slate-700">สถานะการใช้งานระบบ</span>
                        <div @click="form.is_active = !form.is_active"
                            :class="form.is_active ? 'bg-emerald-500' : 'bg-slate-300'"
                            class="w-12 h-7 rounded-full relative cursor-pointer transition-colors duration-300 hover:shadow-inner">
                            <div :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"
                                class="absolute top-1 w-5 h-5 bg-white rounded-full transition-transform duration-300 shadow-sm border border-black/5">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-5 bg-slate-50/80 border-t border-slate-100 flex justify-end gap-3 shrink-0">
                    <button @click="closeModal"
                        class="px-6 py-2.5 border border-slate-200 hover:bg-white rounded-xl text-[14px] font-bold text-slate-600 transition-colors">ยกเลิก</button>
                    <button @click="savePosition"
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[14px] font-bold shadow-sm transition-colors flex items-center gap-2">
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

const form = ref({
    position_id: null,
    position_name: '',
    position_name_en: '',
    level_code: '',
    employment_type_id: null,
    employee_category_id: null,
    min_salary: '',
    max_salary: '',
    is_active: true
});

const fetchMasterData = async () => {
    try {
        const res = await axios.get('/api/master-data');
        masterData.value = res.data;
    } catch (e) { console.error("Master Data Error:", e); }
};

const fetchPositions = async () => {
    isLoading.value = true;
    try {
        const res = await axios.get('/api/positions');
        // ✅ บังคับให้เป็น Boolean เพื่อให้ UI แสดงผลแม่นยำ
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
        form.value = {
            ...pos,
            // ✅ แปลงค่าจาก Database ให้เป็น Boolean ก่อนเข้า Form
            is_active: Number(pos.is_active) === 1
        };
    } else {
        isEditMode.value = false;
        form.value = {
            position_id: null, position_name: '', position_name_en: '', level_code: '',
            employment_type_id: null, employee_category_id: null,
            min_salary: '', max_salary: '', is_active: true // Default เป็นเปิด
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
        confirmButtonText: 'ลบข้อมูล',
        cancelButtonText: 'ยกเลิก'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/positions/${id}`);
                Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1000 });
                fetchPositions();
            } catch (e) { Swal.fire('Error', 'ไม่สามารถลบได้', 'error'); }
        }
    });
};

onMounted(() => {
    fetchPositions();
    fetchMasterData();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 20px;
}

@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.97);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-up {
    animation: scaleUp 0.2s ease-out forwards;
}
</style>