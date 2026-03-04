<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">รายชื่อพนักงาน (Employees)</h1>
                <p class="text-slate-500 mt-1 text-base">จัดการข้อมูลพนักงาน, สร้างบัญชีผู้ใช้ และกำหนดสิทธิ์</p>
            </div>
            <button @click="openModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 flex items-center gap-2 transition-all transform hover:scale-105 active:scale-95 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                <span>เพิ่มพนักงานใหม่</span>
            </button>
        </div>

        <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 mb-6 max-w-sm">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" v-model="searchQuery"
                    class="block w-full pl-10 pr-4 py-2 border-none rounded-xl bg-transparent focus:ring-0 text-slate-700 placeholder-slate-400 focus:outline-none"
                    placeholder="ค้นหาชื่อ, อีเมล..." />
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            ตำแหน่ง / สาขา</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            ประเภทพนักงาน</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            ข้อมูลติดต่อ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="emp in filteredEmployees" :key="emp.user_id"
                        class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg mr-3 shrink-0">
                                    {{ emp.first_name ? emp.first_name.charAt(0) : '?' }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800">
                                        {{ emp.prefix ? emp.prefix : '' }}{{ emp.first_name }} {{ emp.last_name }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        ID: <span class="font-mono">{{ emp.id_card_number || 'ไม่ได้ระบุ' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-slate-700">{{ emp.position_name || '-' }}</div>
                            <div class="text-[11px] text-slate-500 bg-slate-100 inline-block px-2 py-0.5 rounded mt-1">
                                {{ emp.branch_name || '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-blue-600">{{ emp.employment_type_name || '-' }}</div>
                            <div class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">{{
                                emp.employee_category_name || '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <div class="flex items-center gap-2"><span>📧</span> {{ emp.email }}</div>
                            <div class="flex items-center gap-2 mt-1"><span>📞</span> {{ emp.phone_number ||
                                'ไม่ได้ระบุ' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="editEmployee(emp)"
                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors"><svg
                                    xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg></button>
                            <button @click="deleteEmployee(emp.user_id)"
                                class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors"><svg
                                    xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg></button>
                        </td>
                    </tr>
                    <tr v-if="filteredEmployees.length === 0">
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            ไม่พบข้อมูลพนักงาน
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" @click="closeModal"></div>
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-fade-in">
                <div
                    class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <h3 class="text-lg font-bold text-slate-800">{{ isEditing ? 'แก้ไขข้อมูลพนักงาน' :
                        'เพิ่มพนักงานใหม่' }}</h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600 font-bold text-xl">✕</button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <form @submit.prevent="saveEmployee" class="space-y-6">
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">ข้อมูลส่วนตัว
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-3">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">คำนำหน้า <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="form.prefix" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled>เลือก</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">ชื่อจริง <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.first_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="สมชาย">
                                </div>
                                <div class="col-span-12 md:col-span-5">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">นามสกุล <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.last_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="ใจดี">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">เลขบัตรประชาชน</label>
                                    <input v-model="form.id_card_number" type="text" maxlength="13"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="1234567890123">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">เบอร์โทรศัพท์</label>
                                    <input v-model="form.phone_number" type="tel" maxlength="10"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="0891234567">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-t border-slate-100 pt-4">
                                ข้อมูลการจ้างงานและสังกัด</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">ประเภทการจ้างงาน <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="form.employment_type_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- เลือกประเภทการจ้าง --</option>
                                        <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{
                                            type.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">หมวดหมู่พนักงาน <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="form.employee_category_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- เลือกหมวดหมู่ --</option>
                                        <option v-for="cat in employeeCategories" :key="cat.id" :value="cat.id">{{
                                            cat.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">สาขา <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="form.branch_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- เลือกสาขา --</option>
                                        <option v-for="branch in branches" :key="branch.branch_id"
                                            :value="branch.branch_id">{{ branch.branch_name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">ตำแหน่ง <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="form.position_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        <option v-for="pos in positions" :key="pos.position_id"
                                            :value="pos.position_id">{{ pos.position_name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider">
                                    ตั้งค่าบัญชีเข้าสู่ระบบ</h4>
                                <div v-if="isEditing" class="flex items-center">
                                    <input type="checkbox" id="changePass" v-model="enablePasswordEdit"
                                        class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                    <label for="changePass"
                                        class="ml-2 text-sm font-bold text-slate-600 cursor-pointer">เปลี่ยนรหัสผ่าน?</label>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">อีเมล (Username) <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.email" type="email" required
                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="employee@enterprise.com">
                                </div>
                                <div v-if="enablePasswordEdit"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-fade-in">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">{{ isEditing ?
                                            'รหัสผ่านใหม่' : 'รหัสผ่าน' }} <span class="text-rose-500">*</span></label>
                                        <input v-model="form.password" type="password" required
                                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">ยืนยันรหัสผ่าน <span
                                                class="text-rose-500">*</span></label>
                                        <input v-model="form.password_confirmation" type="password" required
                                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                            :class="{ 'border-rose-300 ring-2 ring-rose-100': passwordMismatch }"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 shrink-0">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold">ยกเลิก</button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-md font-bold disabled:opacity-50"
                                :disabled="isLoading || (enablePasswordEdit && passwordMismatch)">
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

const employees = ref([]);
const branches = ref([]);
const positions = ref([]);
const employmentTypes = ref([]);
const employeeCategories = ref([]);

const searchQuery = ref('');
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const isLoading = ref(false);
const enablePasswordEdit = ref(false);

// ✅ เพิ่ม prefix เข้าไปในตัวแปร form
const form = ref({
    prefix: '', first_name: '', last_name: '', id_card_number: '', phone_number: '',
    email: '', branch_id: '', position_id: '',
    employment_type_id: '', employee_category_id: '',
    password: '', password_confirmation: ''
});

const filteredEmployees = computed(() => {
    if (!searchQuery.value) return employees.value;
    const q = searchQuery.value.toLowerCase();
    return employees.value.filter(e =>
        (e.first_name || '').toLowerCase().includes(q) ||
        (e.last_name || '').toLowerCase().includes(q) ||
        (e.email || '').toLowerCase().includes(q)
    );
});

const passwordMismatch = computed(() => {
    if (!enablePasswordEdit.value || !form.value.password) return false;
    return form.value.password !== form.value.password_confirmation;
});

const fetchData = async () => {
    try {
        const [empRes, branchRes, posRes, masterRes] = await Promise.all([
            axios.get('/api/employees'),
            axios.get('/api/branches'),
            axios.get('/api/positions'),
            axios.get('/api/master-data')
        ]);
        employees.value = empRes.data;
        branches.value = branchRes.data;
        positions.value = posRes.data;
        employmentTypes.value = masterRes.data.employment_types;
        employeeCategories.value = masterRes.data.employee_categories;
    } catch (e) { console.error('Fetch Error:', e); }
};

const openModal = () => {
    isEditing.value = false;
    editingId.value = null;
    enablePasswordEdit.value = true;
    form.value = {
        prefix: '', // ✅ Reset คำนำหน้าเมื่อเปิด Modal
        first_name: '', last_name: '', id_card_number: '', phone_number: '',
        email: '', branch_id: '', position_id: '',
        employment_type_id: '', employee_category_id: '',
        password: '', password_confirmation: ''
    };
    isModalOpen.value = true;
};

const editEmployee = (emp) => {
    isEditing.value = true;
    editingId.value = emp.user_id;
    enablePasswordEdit.value = false;
    form.value = {
        ...emp,
        password: '',
        password_confirmation: ''
    };
    isModalOpen.value = true;
};

const closeModal = () => isModalOpen.value = false;

const saveEmployee = async () => {
    isLoading.value = true;
    const payload = { ...form.value };

    if (isEditing.value && !enablePasswordEdit.value) {
        delete payload.password;
        delete payload.password_confirmation;
    }

    try {
        if (isEditing.value) {
            await axios.put(`/api/employees/${editingId.value}`, payload);
        } else {
            await axios.post('/api/employees', payload);
        }
        Swal.fire({ icon: 'success', title: 'สำเร็จ', timer: 1500, showConfirmButton: false });
        closeModal();
        fetchData();
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
    } finally { isLoading.value = false; }
};

const deleteEmployee = (id) => {
    Swal.fire({ title: 'ยืนยันการลบ?', text: "ข้อมูลจะถูกลบถาวร", icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
        .then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/api/employees/${id}`);
                    fetchData();
                    Swal.fire({ icon: 'success', title: 'ลบสำเร็จ!', showConfirmButton: false, timer: 1500 });
                } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
            }
        });
};

onMounted(fetchData);
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 20px;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>