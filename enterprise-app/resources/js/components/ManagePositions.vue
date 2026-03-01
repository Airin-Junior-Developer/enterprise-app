<template>
  <div class="p-8 bg-[#F8FAFC] min-h-screen font-jakarta text-slate-800">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">จัดการรายชื่อและตำแหน่งงาน</h2>
        <p class="text-sm text-slate-400 mt-1 font-medium font-ibm">ตั้งค่าและบริหารจัดการตำแหน่งงานภายในองค์กร</p>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">💼</div>
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">พนักงานทั้งหมด</p>
          <h3 class="text-2xl font-black text-slate-800">{{ positions.length }}</h3>
        </div>
      </div>
    </div>

    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm mb-6 flex flex-col md:flex-row gap-4">
      <div class="relative flex-grow">
        <input type="text" v-model="searchQuery"
          class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-400 outline-none transition-all text-sm font-medium font-ibm"
          placeholder="ค้นหาชื่อพนักงาน/ตำแหน่ง (ไทย/อังกฤษ)..." />
        <span class="absolute left-4 top-3.5 text-slate-400">🔍</span>
      </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse font-ibm whitespace-nowrap">
          <thead class="bg-slate-50/50 text-slate-400 font-bold border-b border-slate-50">
            <tr class="text-[10px] uppercase tracking-[0.15em]">
              <th class="px-8 py-5 text-center w-20">ลำดับ</th>
              <th class="px-6 py-5">รายชื่อพนักงาน</th>
              <th class="px-6 py-5">สาขา</th>
              <th class="px-6 py-5">ตำแหน่งปัจจุบัน</th>
              <th class="px-6 py-5">Email</th>
              <th class="px-6 py-5 text-center">เบอร์โทร</th>
              <th class="px-6 py-5 text-center">วันที่เริ่มงาน</th>
              <th class="px-6 py-5 text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-if="isLoading">
              <td colspan="8" class="px-6 py-12 text-center text-blue-600 font-bold animate-pulse">กำลังโหลดข้อมูล...
              </td>
            </tr>
            <tr v-else-if="filteredPositions.length === 0">
              <td colspan="8" class="px-6 py-12 text-center text-slate-400">ไม่พบข้อมูลที่ค้นหา</td>
            </tr>
            <tr v-else v-for="(item, index) in filteredPositions" :key="item.id"
              class="hover:bg-blue-50/30 transition-colors group">
              <td class="px-8 py-6 text-center text-slate-300 font-bold">{{ index + 1 }}</td>
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700">{{ item.employee_name }}</div>
              </td>
              <td class="px-6 py-6 text-slate-600 font-medium">{{ item.branch }}</td>
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ item.name }}</div>
                <div class="text-[10px] text-slate-400 italic mb-1">{{ item.name_en }}</div>

                <div v-if="item.temp_position_end_date"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-600 text-[10px] font-bold mt-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                      clip-rule="evenodd" />
                  </svg>
                  รักษาการถึง: {{ formatDate(item.temp_position_end_date) }}
                </div>
              </td>
              <td class="px-6 py-6 text-slate-500 text-xs">{{ item.email }}</td>
              <td class="px-6 py-6 text-center text-slate-600 font-medium">{{ item.phone || '-' }}</td>
              <td class="px-6 py-6 text-center text-slate-500">{{ item.start_date || '-' }}</td>
              <td class="px-6 py-6 text-center">
                <div class="flex items-center justify-center gap-3">
                  <button @click="openEditModal(item)"
                    class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white transition-colors"
                    title="แก้ไขตำแหน่ง">✏️</button>
                  <button @click="confirmDelete(item)"
                    class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-rose-500 hover:text-white transition-colors"
                    title="ลบข้อมูลพนักงาน">🗑️</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="showModal"
      class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-clear-sm">
      <div
        class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 font-ibm flex flex-col max-h-[90vh]">

        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
          <h3 class="text-lg font-bold text-blue-600">แก้ไขตำแหน่งงาน</h3>
          <button @click="showModal = false"
            class="text-slate-400 hover:text-slate-600 transition-colors text-xl">✕</button>
        </div>

        <div class="p-8 space-y-6 overflow-y-auto">

          <div class="space-y-2">
            <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">พนักงาน</label>
            <input v-model="form.employee_name" type="text" disabled
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-500 cursor-not-allowed outline-none" />
          </div>

          <div class="space-y-2">
            <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">เลือกตำแหน่งใหม่
              <span class="text-rose-500">*</span></label>
            <select v-model="form.position_id"
              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-slate-700">
              <option value="" disabled>-- กรุณาเลือกตำแหน่ง --</option>
              <option v-for="pos in allowedPositions" :key="pos.position_id" :value="pos.position_id">
                {{ pos.position_name }} ({{ pos.level_code }})
              </option>
            </select>
          </div>

          <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
            <label class="flex items-center gap-3 cursor-pointer select-none">
              <input type="checkbox" v-model="form.is_temporary"
                class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" />
              <span class="text-sm font-bold text-slate-700">เป็นการแต่งตั้งชั่วคราว (รักษาการแทน)</span>
            </label>

            <div v-if="form.is_temporary" class="mt-4 animate-in slide-in-from-top-2">
              <label
                class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">วันที่สิ้นสุดการรักษาการ
                <span class="text-rose-500">*</span></label>
              <input type="date" v-model="form.end_date" :min="todayDate"
                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none text-slate-700 font-medium" />
            </div>
          </div>

        </div>

        <div class="p-6 bg-slate-50 flex justify-end gap-3 border-t border-slate-100 shrink-0">
          <button @click="showModal = false"
            class="px-6 py-2.5 rounded-2xl border border-slate-200 text-slate-500 font-bold text-sm hover:bg-white hover:text-slate-700 transition-all">ยกเลิก</button>
          <button @click="handleSave"
            class="px-8 py-2.5 rounded-2xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-200 text-sm hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-2"
            :disabled="isSaving">
            <svg v-if="isSaving" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            {{ isSaving ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';

const router = useRouter();
const currentUser = ref(null);

// --- Configuration สำหรับ SweetAlert2 ---
const swalConfig = {
  customClass: {
    popup: 'recruit-swal-popup',
    title: 'recruit-swal-title',
    confirmButton: 'recruit-swal-confirm',
    cancelButton: 'recruit-swal-cancel'
  },
  buttonsStyling: false
};

// --- States ---
const positions = ref([]);         // ข้อมูลพนักงานและตำแหน่ง (จาก View)
const masterPositions = ref([]);   // ข้อมูลรายชื่อตำแหน่งทั้งหมด (สำหรับ Dropdown)
const isLoading = ref(true);
const isSaving = ref(false);
const showModal = ref(false);
const searchQuery = ref('');

// ตัวแปรเก็บข้อมูลฟอร์ม
const form = reactive({
  id: null,
  employee_name: '',
  position_id: '',
  is_temporary: false, // ✅ เพิ่มบรรทัดนี้
  end_date: ''         // ✅ เพิ่มบรรทัดนี้
});

// --- ดึงข้อมูลจาก Backend ---
const fetchPositions = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/manage-positions-list');
    positions.value = response.data;
  } catch (error) {
    console.error("Fetch Error:", error);
  } finally {
    isLoading.value = false;
  }
};

// ดึง Master Data ตำแหน่งทั้งหมดเพื่อทำ Dropdown
const fetchMasterPositions = async () => {
  try {
    const response = await axios.get('/api/positions');
    masterPositions.value = response.data;
  } catch (error) {
    console.error("Fetch Master Positions Error:", error);
  }
};

onMounted(() => {
  const userStr = localStorage.getItem('user');
  if (userStr) {
    currentUser.value = JSON.parse(userStr);
    const roleName = currentUser.value?.position_name?.toLowerCase() || '';
    const allowedRoles = ['super admin', 'system admin', 'hr manager'];

    if (!allowedRoles.includes(roleName)) {
      Swal.fire({ icon: 'error', title: 'ปฏิเสธการเข้าถึง', text: 'คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้', confirmButtonColor: '#3085d6' })
        .then(() => router.push('/'));
      return;
    }

    fetchPositions();
    fetchMasterPositions(); // โหลด List ตำแหน่งสำหรับ Dropdown
  } else {
    router.push('/login');
  }
});

// --- Filter ---

// 1. กรองข้อมูลในตาราง
const filteredPositions = computed(() => {
  if (!searchQuery.value) return positions.value;
  const search = searchQuery.value.toLowerCase();
  return positions.value.filter(p =>
    (p.employee_name && p.employee_name.toLowerCase().includes(search)) ||
    (p.name && p.name.toLowerCase().includes(search)) ||
    (p.name_en && p.name_en.toLowerCase().includes(search))
  );
});

// ✅ 2. (เพิ่มใหม่) กรองตำแหน่งใน Dropdown ตามสิทธิ์คนล็อกอิน
const allowedPositions = computed(() => {
  if (!currentUser.value) return [];

  const myRole = currentUser.value?.position_name?.toLowerCase() || '';

  // ถ้าเป็น Super Admin หรือ System Admin ให้เห็นตำแหน่งทั้งหมด
  if (myRole === 'super admin' || myRole === 'system admin') {
    return masterPositions.value;
  }

  // ถ้าเป็น HR Manager ให้ซ่อนตำแหน่ง Super Admin และ System Admin ออกจากตัวเลือก
  if (myRole === 'hr manager') {
    return masterPositions.value.filter(pos => {
      const posName = pos.position_name.toLowerCase();
      // ซ่อนตำแหน่งที่มีชื่อเหล่านี้ (คุณสามารถเพิ่มตำแหน่งอื่นที่ห้ามตั้งได้ที่นี่)
      return posName !== 'super admin' && posName !== 'system admin';
    });
  }

  return masterPositions.value;
});

// 3. สร้างตัวแปรคำนวณวันที่ปัจจุบัน (Format: YYYY-MM-DD)
const todayDate = computed(() => {
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth() + 1).padStart(2, '0');
  const dd = String(today.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
});

// --- Actions ---

// เปิดหน้าต่างแก้ไข
const openEditModal = (item) => {
  const matchedPos = masterPositions.value.find(p => p.position_name === item.name);
  Object.assign(form, {
    id: item.id,
    employee_name: item.employee_name,
    position_id: matchedPos ? matchedPos.position_id : '',
    is_temporary: false, //  รีเซ็ตค่า
    end_date: ''         //  รีเซ็ตค่า
  });
  showModal.value = true;
};

// กดปุ่มบันทึก
const handleSave = () => {
  if (!form.position_id) return Swal.fire('แจ้งเตือน', 'กรุณาเลือกตำแหน่งใหม่', 'warning');

  Swal.fire({
    ...swalConfig,
    title: 'ยืนยันการบันทึก?',
    text: `ต้องการเปลี่ยนตำแหน่งของ ${form.employee_name} ใช่หรือไม่`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#2563eb'
  }).then(async (result) => {
    if (result.isConfirmed) {
      isSaving.value = true;
      try {
        // ยิง API ไปอัปเดตข้อมูลพนักงาน (ตาราง users)
        await axios.patch(`/api/employees/${form.id}/position`, {
          position_id: form.position_id,
          is_temporary: form.is_temporary,
          end_date: form.end_date
        });

        showModal.value = false;
        Swal.fire({ ...swalConfig, icon: 'success', title: 'บันทึกสำเร็จ', text: 'อัปเดตตำแหน่งเรียบร้อยแล้ว', timer: 1500, showConfirmButton: false });

        // โหลดข้อมูลในตารางใหม่
        fetchPositions();
      } catch (error) {
        Swal.fire({ ...swalConfig, icon: 'error', title: 'เกิดข้อผิดพลาด', text: error.response?.data?.message || 'ไม่สามารถบันทึกข้อมูลได้' });
      } finally {
        isSaving.value = false;
      }
    }
  });
};

// กดปุ่มลบพนักงาน
const confirmDelete = (item) => {
  Swal.fire({
    ...swalConfig,
    title: 'ยืนยันการลบ?',
    text: `ต้องการลบข้อมูลของพนักงาน "${item.employee_name}" ออกจากระบบถาวรใช่หรือไม่?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยันการลบ',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#f43f5e'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        await axios.delete(`/api/employees/${item.id}`);
        Swal.fire({ ...swalConfig, icon: 'success', title: 'ลบสำเร็จ', text: 'ข้อมูลถูกลบออกจากระบบแล้ว', timer: 1500, showConfirmButton: false });
        fetchPositions();
      } catch (error) {
        Swal.fire({ ...swalConfig, icon: 'error', title: 'ลบไม่สำเร็จ', text: error.response?.data?.message || 'ไม่สามารถลบข้อมูลได้' });
      }
    }
  });
};

// ✅ ฟังก์ชันแปลงวันที่สำหรับแสดงผลในตาราง
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('th-TH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.font-jakarta {
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.font-ibm {
  font-family: 'IBM Plex Sans Thai', sans-serif;
}

.recruit-swal-popup {
  border-radius: 2.5rem !important;
  padding: 2.5rem !important;
  font-family: 'IBM Plex Sans Thai', sans-serif !important;
}

.recruit-swal-title {
  font-size: 1.5rem !important;
  font-weight: 700 !important;
  color: #1e293b !important;
}

.recruit-swal-confirm {
  background-color: #2563eb !important;
  color: white !important;
  border-radius: 1.25rem !important;
  padding: 0.8rem 2.5rem !important;
  font-weight: 700 !important;
  margin: 0.5rem !important;
  box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2) !important;
}

.recruit-swal-cancel {
  background-color: #f1f5f9 !important;
  color: #64748b !important;
  border-radius: 1.25rem !important;
  padding: 0.8rem 2.5rem !important;
  font-weight: 700 !important;
  margin: 0.5rem !important;
}

.animate-in {
  animation: zoomIn 0.2s ease-out;
}

@keyframes zoomIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }

  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>