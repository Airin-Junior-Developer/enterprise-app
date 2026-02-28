<template>
  <div class="p-8 bg-[#F8FAFC] min-h-screen font-jakarta text-slate-800">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">จัดการรายชื่อและตำแหน่งงาน</h2>
        <p class="text-sm text-slate-400 mt-1 font-medium font-ibm">ตั้งค่าและบริหารจัดการตำแหน่งงานภายในองค์กร</p>
      </div>
      <div class="flex gap-3">
        <button @click="openCreateModal"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2 font-bold text-sm active:scale-95">
          <span class="text-lg">+</span> สร้างตำแหน่งใหม่
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-4xlrder border-slate-100 shadow-sm flex items-center gap-4">
        <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">💼</div>
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ตำแหน่งทั้งหมด</p>
          <h3 class="text-2xl font-black text-slate-800">{{ positions.length }}</h3>
        </div>
      </div>
    </div>

    <div class="bg-white p-5 rounded-4xl border border-slate-100 shadow-sm mb-6 flex flex-col md:flex-row gap-4">
      <div class="relative grow">
        <input type="text" v-model="searchQuery"
          class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-400 outline-none transition-all text-sm font-medium font-ibm"
          placeholder="ค้นหาชื่อพนักงาน/ตำแหน่ง (ไทย/อังกฤษ)..." />
        <span class="absolute left-4 top-3.5 text-slate-400">🔍</span>
      </div>
      <select v-model="filterType"
        class="w-full md:w-44 border border-slate-100 rounded-2xl px-4 py-3 bg-slate-50 text-slate-600 font-bold text-sm outline-none focus:ring-4 focus:ring-blue-50">
        <option value="">ทุกประเภทการจ้าง</option>
        <option value="ถาวร">ถาวร</option>
        <option value="ชั่วคราว">ชั่วคราว</option>
      </select>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse font-ibm">
          <thead class="bg-slate-50/50 text-slate-400 font-bold border-b border-slate-50">
            <tr class="text-[10px] uppercase tracking-[0.15em]">
              <th class="px-8 py-5 text-center w-20">ลำดับ</th>
              <th class="px-6 py-5">รายชื่อพนักงาน</th>
              <th class="px-6 py-5">ตำแหน่ง</th>
              <th class="px-6 py-5">ประเภทการจ้าง</th>
              <th class="px-6 py-5 text-center">สถานะ</th>
              <th class="px-6 py-5 text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="(item, index) in filteredPositions" :key="item.id"
              class="hover:bg-blue-50/30 transition-colors group">

              <!-- ลำดับ -->
              <td class="px-8 py-6 text-center text-slate-300 font-bold">
                {{ index + 1 }}
              </td>

              <!-- ชื่อพนักงาน -->
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700">
                  {{ item.employee_name }}
                </div>
              </td>

              <!-- ตำแหน่ง -->
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700 group-hover:text-blue-600">
                  {{ item.name }}
                </div>
                <div class="text-[11px] text-slate-400 italic">
                  {{ item.name_en }}
                </div>
              </td>

              <!-- ประเภทการจ้าง -->
              <td class="px-6 py-6">
                <span :class="item.type === 'ชั่วคราว'
                  ? 'bg-amber-50 text-amber-600 border-amber-100'
                  : 'bg-blue-50 text-blue-600 border-blue-100'"
                  class="px-3 py-1.5 rounded-xl text-[10px] font-bold border">

                  {{ item.type }} {{ item.days ? `(${item.days} วัน)` : '' }}
                </span>
              </td>

              <!-- สถานะ -->
              <td class="px-6 py-6 text-center">
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black 
                 bg-emerald-50 text-emerald-600 border">
                  ใช้งาน
                </span>
              </td>

              <!-- จัดการ -->
              <td class="px-6 py-6 text-center">
                <div class="flex items-center justify-center gap-3">
                  <button @click="openEditModal(item)"
                    class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white">
                    ✏️
                  </button>

                  <button @click="confirmDelete(item)"
                    class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-rose-500 hover:text-white">
                    🗑️
                  </button>
                </div>
              </td>
            </tr>

            <tr v-if="filteredPositions.length === 0">
              <td colspan="5" class="px-6 py-12 text-center text-slate-400">ไม่พบข้อมูลตำแหน่งงานที่ค้นหา</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-slate-900/40 ">
      <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 font-ibm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
          <h3 class="text-lg font-bold text-blue-600">{{ isEditMode ? 'แก้ไขข้อมูลตำแหน่ง' : 'เพิ่มตำแหน่งใหม่' }}</h3>
          <button @click="showModal = false"
            class="text-slate-300 hover:text-slate-500 transition-colors text-xl">✕</button>
        </div>
        <div class="p-8 grid grid-cols-2 gap-6">
          <div class="col-span-1">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">ชื่อตำแหน่ง
              *</label>
            <input v-model="form.name" type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-blue-400 outline-none transition-all" />
          </div>
          <div class="col-span-1">
            <label
              class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">ชื่อจริง-นามสกุล
              *</label>
            <input v-model="form.name_en" type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-blue-400 outline-none transition-all" />
          </div>
          <div class="col-span-1">
            <label
              class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">ประเภทการจ้าง</label>
            <select v-model="form.type"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-blue-400 outline-none cursor-pointer font-bold">
              <option value="ถาวร">ถาวร (Permanent)</option>
              <option value="ชั่วคราว">ชั่วคราว (Contract)</option>
            </select>
          </div>
          <div v-if="form.type === 'ชั่วคราว'" class="col-span-1">
            <label
              class="block text-[10px] font-bold text-amber-600 uppercase mb-2 ml-1 tracking-widest">จำนวนวันสัญญา</label>
            <input v-model="form.days" type="number"
              class="w-full px-4 py-3 bg-amber-50 border border-amber-100 text-amber-700 rounded-2xl text-sm font-bold outline-none" />
          </div>
        </div>
        <div class="p-8 bg-slate-50/50 flex justify-center gap-4">
          <button @click="showModal = false"
            class="px-8 py-2.5 rounded-2xl border border-slate-200 text-slate-400 font-bold text-sm hover:bg-white transition-all">ยกเลิก</button>
          <button @click="handleSave"
            class="px-10 py-2.5 rounded-2xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-100 text-sm hover:bg-blue-700 active:scale-95 transition-all">บันทึกข้อมูล</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import Swal from 'sweetalert2';

// --- Configuration สำหรับ SweetAlert2 สไตล์ iRecruit ---
const swalConfig = {
  customClass: {
    popup: 'recruit-swal-popup',
    title: 'recruit-swal-title',
    confirmButton: 'recruit-swal-confirm',
    cancelButton: 'recruit-swal-cancel'
  },
  buttonsStyling: false
};

// --- Data & States ---
const showModal = ref(false);
const isEditMode = ref(false);
const searchQuery = ref('');
const filterType = ref('');

const positions = ref([
  {
    id: 1,
    employee_name: 'สมชาย ใจดี',
    name: 'เจ้าหน้าที่ประสานงาน',
    name_en: 'Coordinator',
    type: 'ถาวร',
    days: null
  },
  {
    id: 2,
    employee_name: 'วิภา สุขสันต์',
    name: 'พนักงานคลังสินค้า',
    name_en: 'Warehouse Staff',
    type: 'ชั่วคราว',
    days: 90
  },
]);

const form = reactive({
  id: null,
  name: '',
  name_en: '',
  type: 'ถาวร',
  days: null
});

// --- Filter Logic ---
const filteredPositions = computed(() => {
  return positions.value.filter(p => {
    const matchSearch = p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      p.name_en.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchType = filterType.value === '' || p.type === filterType.value;
    return matchSearch && matchType;
  });
});

// --- Actions ---
const openCreateModal = () => {
  isEditMode.value = false;
  Object.assign(form, { id: null, name: '', name_en: '', type: 'ถาวร', days: null });
  showModal.value = true;
};

const openEditModal = (item) => {
  isEditMode.value = true;
  Object.assign(form, { ...item });
  showModal.value = true;
};

const handleSave = () => {
  // แจ้งเตือนยืนยันก่อนบันทึก
  Swal.fire({
    ...swalConfig,
    title: 'ยืนยันการบันทึก?',
    text: "คุณต้องการบันทึกข้อมูลตำแหน่งนี้ใช่หรือไม่",
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#2563eb'
  }).then((result) => {
    if (result.isConfirmed) {
      if (isEditMode.value) {
        const index = positions.value.findIndex(p => p.id === form.id);
        if (index !== -1) positions.value[index] = { ...form };
      } else {
        const newId = positions.value.length > 0 ? Math.max(...positions.value.map(p => p.id)) + 1 : 1;
        positions.value.push({ ...form, id: newId });
      }
      showModal.value = false;

      // แจ้งเตือนสำเร็จ
      Swal.fire({
        ...swalConfig,
        icon: 'success',
        title: 'บันทึกสำเร็จ',
        text: 'ข้อมูลของคุณถูกอัปเดตเรียบร้อยแล้ว',
        timer: 1500,
        showConfirmButton: false
      });
    }
  });
};

const confirmDelete = (item) => {
  Swal.fire({
    ...swalConfig,
    title: 'ยืนยันการลบ?',
    text: `คุณแน่ใจใช่หรือไม่ที่จะลบตำแหน่ง "${item.name}"`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยันการลบ',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#f43f5e'
  }).then((result) => {
    if (result.isConfirmed) {
      positions.value = positions.value.filter(p => p.id !== item.id);
      Swal.fire({
        ...swalConfig,
        icon: 'success',
        title: 'ลบสำเร็จ',
        text: 'ข้อมูลถูกลบออกจากระบบแล้ว',
        timer: 1500,
        showConfirmButton: false
      });
    }
  });
};
</script>

<style>
/* Font Configuration */
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.font-jakarta {
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.font-ibm {
  font-family: 'IBM Plex Sans Thai', sans-serif;
}

/* Custom SweetAlert2 Style */
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

/* Animations */
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