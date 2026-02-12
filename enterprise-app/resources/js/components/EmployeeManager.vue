<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">รายชื่อพนักงาน (Employees)</h1>
                <p class="text-slate-500 mt-1 text-base">จัดการข้อมูลพนักงาน, สร้างบัญชีผู้ใช้ และกำหนดสิทธิ์</p>
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

        <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 mb-6 max-w-sm">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ตำแหน่ง / สาขา
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ข้อมูลติดต่อ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="emp in filteredEmployees" :key="emp.user_id"
                        class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg mr-3">
                                    {{ emp.first_name.charAt(0) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800">{{ emp.first_name }} {{ emp.last_name
                                        }}</div>
                                    <div class="text-xs text-slate-500">ID: {{ emp.id_card_number || '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-slate-700">{{ emp.position_name || '-' }}</div>
                            <div class="text-xs text-slate-500 bg-slate-100 inline-block px-2 py-0.5 rounded mt-1">
                                {{ emp.branch_name || '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <div>📧 {{ emp.email }}</div>
                            <div>📞 {{ emp.phone_number }}</div>
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
                            <button @click="deleteEmployee(emp.user_id)"
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
                </tbody>
            </table>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/75 transition-opacity" @click="closeModal"></div>

            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">{{ isEditing ? 'แก้ไขข้อมูลพนักงาน' :
                        'เพิ่มพนักงานใหม่' }}</h3>
                    <button @click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <form @submit.prevent="saveEmployee" class="space-y-6">

                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">ข้อมูลส่วนตัว
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อจริง <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.first_name" type="text" required
                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="สมชาย">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">นามสกุล <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.last_name" type="text" required
                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="ใจดี">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">เลขบัตรประชาชน</label>
                                    <input v-model="form.id_card_number" type="text" maxlength="13"
                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">เบอร์โทรศัพท์</label>
                                    <input v-model="form.phone_number" type="tel"
                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-t border-slate-100 pt-4">
                                ตำแหน่งงาน</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">สาขา <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="form.branch_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- เลือกสาขา --</option>
                                        <option v-for="branch in branches" :key="branch.branch_id"
                                            :value="branch.branch_id">{{ branch.branch_name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ตำแหน่ง <span
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
                                <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider">ตั้งค่าบัญชีผู้ใช้
                                    (สำหรับเข้าสู่ระบบ)</h4>

                                <div v-if="isEditing" class="flex items-center">
                                    <input type="checkbox" id="changePass" v-model="enablePasswordEdit"
                                        class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                    <label for="changePass"
                                        class="ml-2 text-sm font-bold text-slate-600 cursor-pointer">ต้องการเปลี่ยนรหัสผ่าน?</label>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">อีเมล (Username) <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="form.email" type="email" required
                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="employee@enterprise.com">
                                </div>

                                <div v-if="enablePasswordEdit"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-fade-in">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">
                                            {{ isEditing ? 'รหัสผ่านใหม่' : 'รหัสผ่าน' }} <span
                                                class="text-rose-500">*</span>
                                        </label>
                                        <input v-model="form.password" type="password" required
                                            autocomplete="new-password"
                                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">
                                            ยืนยันรหัสผ่าน <span class="text-rose-500">*</span>
                                        </label>
                                        <input v-model="form.password_confirmation" type="password" required
                                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                                            :class="{ 'border-rose-300 ring-2 ring-rose-100': passwordMismatch }"
                                            placeholder="••••••••">
                                        <p v-if="passwordMismatch" class="text-xs text-rose-500 mt-1">รหัสผ่านไม่ตรงกัน
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">ยกเลิก</button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-md font-bold disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="isLoading || passwordMismatch">
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

const searchQuery = ref('');

const filteredEmployees = computed(() => {
    if (!searchQuery.value) return employees.value;
    const lowerSearch = searchQuery.value.toLowerCase();
    return employees.value.filter(e =>
        e.first_name.toLowerCase().includes(lowerSearch) ||
        e.last_name.toLowerCase().includes(lowerSearch) ||
        e.email.toLowerCase().includes(lowerSearch)
    );
});

// ✅ 3. เช็ค Password Match เฉพาะตอนที่เปิดใช้งานช่องรหัสผ่าน
const passwordMismatch = computed(() => {
    if (!enablePasswordEdit.value) return false; // ถ้าซ่อนอยู่ ถือว่าผ่าน
    return form.value.password && form.value.password !== form.value.password_confirmation;
});

const employees = ref([]);
const branches = ref([]);
const positions = ref([]);
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const isLoading = ref(false);

// ✅ 4. ตัวแปรควบคุมการเปิด/ปิดช่องรหัสผ่าน
const enablePasswordEdit = ref(false);

const form = ref({
    first_name: '', last_name: '', id_card_number: '', phone_number: '',
    email: '', branch_id: '', position_id: '',
    password: '', password_confirmation: ''
});

const fetchData = async () => {
    try {
        const [empRes, branchRes, posRes] = await Promise.all([
            axios.get('/api/employees'),
            axios.get('/api/branches'),
            axios.get('/api/positions')
        ]);
        employees.value = empRes.data;
        branches.value = branchRes.data;
        positions.value = posRes.data;
    } catch (e) { console.error(e); }
};

const openModal = () => {
    isEditing.value = false;
    editingId.value = null;
    enablePasswordEdit.value = true; // ✅ ถ้าเพิ่มใหม่ ให้โชว์ช่องรหัสเสมอ
    form.value = {
        first_name: '', last_name: '', id_card_number: '', phone_number: '',
        email: '', branch_id: '', position_id: '',
        password: '', password_confirmation: ''
    };
    isModalOpen.value = true;
};

const editEmployee = (emp) => {
    isEditing.value = true;
    editingId.value = emp.user_id;
    enablePasswordEdit.value = false; // ✅ ถ้าแก้ไข ให้ซ่อนช่องรหัสไว้ก่อน (แก้ Autofill)
    form.value = {
        first_name: emp.first_name,
        last_name: emp.last_name,
        id_card_number: emp.id_card_number,
        phone_number: emp.phone_number,
        email: emp.email,
        branch_id: emp.branch_id,
        position_id: emp.position_id,
        password: '',
        password_confirmation: ''
    };
    isModalOpen.value = true;
};

const closeModal = () => isModalOpen.value = false;

const saveEmployee = async () => {
    if (enablePasswordEdit.value && form.value.password && form.value.password !== form.value.password_confirmation) {
        Swal.fire('Error', 'รหัสผ่านไม่ตรงกัน', 'error');
        return;
    }

    // ✅ ถ้าไม่ได้เปิดแก้รหัสผ่าน ให้ลบค่าทิ้งก่อนส่ง เพื่อความชัวร์
    if (!enablePasswordEdit.value) {
        delete form.value.password;
        delete form.value.password_confirmation;
    }

    isLoading.value = true;
    try {
        if (isEditing.value) {
            await axios.put(`/api/employees/${editingId.value}`, form.value);
        } else {
            await axios.post('/api/employees', form.value);
        }
        Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ!', showConfirmButton: false, timer: 1500 });
        closeModal();
        fetchData();
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || e.message, 'error');
    } finally {
        isLoading.value = false;
    }
};

const deleteEmployee = (id) => {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "ข้อมูลพนักงานและบัญชีผู้ใช้จะถูกลบถาวร",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ลบเลย'
    }).then(async (result) => {
        if (result.isConfirmed) {
            await axios.delete(`/api/employees/${id}`);
            fetchData();
            Swal.fire('ลบสำเร็จ!', '', 'success');
        }
    });
};

onMounted(() => {
    fetchData();
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