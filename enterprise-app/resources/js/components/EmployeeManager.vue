<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">ทำเนียบบุคลากร</h1>
                <p class="text-slate-500 mt-1 text-base">บริหารจัดการข้อมูลพนักงานและสิทธิ์การเข้าใช้งาน</p>
            </div>
            <button @click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 flex items-center gap-2 transition-all transform hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-semibold">เพิ่มพนักงานใหม่</span>
            </button>
        </div>

        <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 mb-8 max-w-lg">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" v-model="searchQuery"
                    class="block w-full pl-10 pr-4 py-3 border-none rounded-xl bg-transparent focus:ring-0 text-slate-700 placeholder-slate-400"
                    placeholder="ค้นหาด้วยชื่อ, อีเมล, หรือเบอร์โทร..." />
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ตำแหน่ง / สังกัด
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase">เบอร์โทร</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase">สถานะ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="emp in filteredEmployees" :key="emp.id" class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md mr-3">
                                    {{ emp.first_name ? emp.first_name.charAt(0) : '?' }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800">{{ emp.first_name }} {{ emp.last_name
                                        }}</div>
                                    <div class="text-xs text-slate-500">{{ emp.user ? emp.user.email : '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-700">{{ emp.position ? emp.position.name : '-' }}
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 mt-1">{{
                                    emp.branch ? emp.branch.name : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-600 font-mono">{{ emp.phone_number || '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                {{ emp.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="editEmployee(emp)"
                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button @click="deleteEmployee(emp.id)"
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
                    <tr v-if="employees.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">ไม่พบข้อมูลพนักงาน</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" @click="closeModal"></div>

            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div
                    class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">{{ isEditing ? 'แก้ไขข้อมูลพนักงาน' :
                            'เพิ่มพนักงานใหม่' }}</h3>
                        <p class="text-sm text-slate-500 mt-1">กรอกข้อมูลให้ครบถ้วนเพื่อ{{ isEditing ? 'อัปเดต' :
                            'สร้าง' }}บัญชี</p>
                    </div>
                    <button @click="closeModal"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-full p-2 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto p-8 custom-scrollbar grow">
                    <form @submit.prevent="saveEmployee" class="space-y-6">

                        <div class="space-y-4">
                            <h4
                                class="text-xs font-bold text-blue-600 uppercase tracking-wider flex items-center gap-2">
                                <span
                                    class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-[10px]">1</span>
                                ข้อมูลส่วนตัว (Personal Info)
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">คำนำหน้า</label>
                                    <select v-model="form.prefix"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none text-slate-700">
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                                <div class="md:col-span-9">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">เลขบัตรประชาชน</label>
                                    <input v-model="form.id_card_number" type="text" maxlength="13"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อจริง <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.first_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">นามสกุล <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.last_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">เบอร์โทรศัพท์</label>
                                <input v-model="form.phone_number" type="tel"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all outline-none" />
                            </div>
                        </div>

                        <hr class="border-slate-100" />

                        <div class="space-y-4">
                            <h4
                                class="text-xs font-bold text-indigo-600 uppercase tracking-wider flex items-center gap-2">
                                <span
                                    class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-[10px]">2</span>
                                สังกัดและการทำงาน (Work)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">สาขา</label>
                                    <select v-model="form.branch_id"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none text-slate-700">
                                        <option value="">-- เลือกสาขา --</option>
                                        <option v-for="b in branches" :key="b.id" :value="b.id">{{ b.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ตำแหน่ง</label>
                                    <select v-model="form.position_id"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all outline-none text-slate-700">
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        <option v-for="p in positions" :key="p.id" :value="p.id">{{ p.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100" />

                        <div class="space-y-4">
                            <h4
                                class="text-xs font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-2">
                                <span
                                    class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-[10px]">3</span>
                                บัญชีผู้ใช้ (Account)
                            </h4>
                            <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-100/50">
                                <label class="block text-sm font-medium text-slate-700 mb-1">อีเมลสำหรับเข้าสู่ระบบ
                                    (Login Email) <span class="text-rose-500">*</span></label>
                                <input v-model="form.email" type="email" required
                                    class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 transition-all outline-none"
                                    :disabled="isEditing" />
                                <p v-if="!isEditing" class="text-xs text-emerald-600 mt-2">* รหัสผ่านเริ่มต้นคือ
                                    12345678</p>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3 shrink-0">
                    <button @click="closeModal"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800 transition-all">ยกเลิก</button>

                    <button @click="saveEmployee"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all transform active:scale-95 flex items-center gap-2"
                        :class="{ 'opacity-75 cursor-not-allowed': isLoading }" :disabled="isLoading">
                        <svg v-if="isLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        {{ isLoading ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// นำเข้า Library ที่จำเป็น
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

// --- ประกาศตัวแปร (State) ---
const searchQuery = ref('');    // คำค้นหา
const isModalOpen = ref(false); // สถานะเปิด/ปิด Modal
const isEditing = ref(false);   // สถานะว่ากำลังแก้ไขหรือไม่
const editingId = ref(null);    // ID ของพนักงานที่กำลังแก้ไข
const isLoading = ref(false);   // สถานะกำลังโหลด
const employees = ref([]);      // ข้อมูลพนักงานทั้งหมด
const branches = ref([]);       // ข้อมูลสาขา (สำหรับ Dropdown)
const positions = ref([]);      // ข้อมูลตำแหน่ง (สำหรับ Dropdown)

// ข้อมูลในฟอร์ม
const form = ref({
    prefix: 'นาย',
    first_name: '',
    last_name: '',
    phone_number: '',
    id_card_number: '',
    branch_id: '',
    position_id: '',
    email: ''
});

// --- ดึงข้อมูล (Fetch Data) ---
const fetchEmployees = async () => {
    try {
        const res = await axios.get('/api/employees');
        employees.value = res.data.data;
    } catch (e) { console.error(e); }
};

const fetchOptions = async () => {
    try {
        // ดึงข้อมูลสาขาและตำแหน่งพร้อมกัน
        const [bRes, pRes] = await Promise.all([
            axios.get('/api/branches'),
            axios.get('/api/positions')
        ]);
        branches.value = bRes.data.data || bRes.data;
        positions.value = pRes.data.data || pRes.data;
    } catch (e) { console.error(e); }
};

// --- การจัดการ Modal ---
const openModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.value = { prefix: 'นาย', first_name: '', last_name: '', phone_number: '', id_card_number: '', branch_id: '', position_id: '', email: '' };
    isModalOpen.value = true;
};

// เตรียมข้อมูลสำหรับแก้ไข
const editEmployee = (emp) => {
    isEditing.value = true;
    editingId.value = emp.id;
    form.value = {
        prefix: emp.prefix,
        first_name: emp.first_name,
        last_name: emp.last_name,
        phone_number: emp.phone_number,
        id_card_number: emp.id_card_number,
        branch_id: emp.branch_id,
        position_id: emp.position_id,
        email: emp.user ? emp.user.email : ''
    };
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

// --- บันทึกข้อมูล (Create / Update) ---
const saveEmployee = async () => {
    // ตรวจสอบความถูกต้องเบื้องต้น
    if (!form.value.first_name) {
        Swal.fire('ข้อมูลไม่ครบ', 'กรุณากรอกชื่อจริง', 'warning');
        return;
    }

    isLoading.value = true;
    try {
        if (isEditing.value) {
            // กรณีแก้ไข (PUT)
            await axios.put(`/api/employees/${editingId.value}`, form.value);
        } else {
            // กรณีเพิ่มใหม่ (POST)
            await axios.post('/api/employees', form.value);
        }

        // แจ้งเตือนความสำเร็จ
        Swal.fire({
            icon: 'success',
            title: isEditing.value ? 'อัปเดตสำเร็จ!' : 'บันทึกสำเร็จ!',
            showConfirmButton: false,
            timer: 1500
        });

        closeModal();
        fetchEmployees();
    } catch (e) {
        Swal.fire('เกิดข้อผิดพลาด', (e.response?.data?.message || e.message), 'error');
    } finally {
        isLoading.value = false;
    }
};

// --- ลบข้อมูล (Delete) ---
const deleteEmployee = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "คุณต้องการลบพนักงานคนนี้ใช่หรือไม่? กู้คืนไม่ได้นะ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'ใช่, ลบเลย',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/employees/${id}`);
                Swal.fire({
                    icon: 'success',
                    title: 'ลบสำเร็จ!',
                    text: 'ข้อมูลถูกลบเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 1500
                });
                fetchEmployees();
            } catch (e) {
                Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถลบข้อมูลได้', 'error');
            }
        }
    });
};

// --- ระบบค้นหา (Search Logic) ---
const filteredEmployees = computed(() => {
    // ถ้าไม่มีคำค้นหา ให้คืนค่าทั้งหมด
    if (!searchQuery.value) return employees.value;

    const lowerSearch = searchQuery.value.toLowerCase();
    // กรองข้อมูลตาม ชื่อ, นามสกุล, เบอร์โทร, หรืออีเมล
    return employees.value.filter(emp =>
        emp.first_name.toLowerCase().includes(lowerSearch) ||
        emp.last_name.toLowerCase().includes(lowerSearch) ||
        (emp.phone_number && emp.phone_number.includes(lowerSearch)) ||
        (emp.user && emp.user.email.toLowerCase().includes(lowerSearch))
    );
});

// เริ่มทำงานเมื่อเปิดหน้าเว็บ
onMounted(() => {
    fetchEmployees();
    fetchOptions();
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
</style>