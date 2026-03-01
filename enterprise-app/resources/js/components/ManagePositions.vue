<template>
  <div class="p-8 bg-slate-50 min-h-screen font-ibm text-slate-600">

    <div class="mb-8 flex justify-between items-end">
      <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5">
        <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-[#2563eb]">
          <i class="fa-solid fa-users text-2xl"></i>
        </div>
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">จำนวนพนักงานทั้งหมด</p>
          <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ employees.length }} <span
              class="text-sm font-medium text-slate-400">คน</span></h2>
        </div>
      </div>
      <div class="w-full max-w-md">
        <input type="text" placeholder="ค้นหาชื่อ, เบอร์โทร หรือ Email..."
          class="w-full pl-6 pr-4 py-3.5 bg-white border border-slate-100 rounded-2xl shadow-sm outline-none focus:border-blue-500 text-sm">
      </div>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
      <table class="min-w-full divide-y divide-slate-50">
        <thead class="bg-slate-50/50">
          <tr>
            <th class="px-8 py-5 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">พนักงาน</th>
            <th class="px-6 py-5 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider">ตำแหน่ง /
              ประเภท</th>
            <th class="px-8 py-5 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">จัดการ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <tr v-for="emp in employees" :key="emp.id" class="hover:bg-slate-50/50 transition-colors group">
            <td class="px-8 py-5">
              <div class="text-sm font-bold text-[#2563eb]">{{ emp.name }}</div>
              <div class="text-[10px] text-slate-400">เริ่มงาน: {{ emp.start_work_date }} | {{ emp.email }}</div>
            </td>
            <td class="px-6 py-5 text-center">
              <div class="text-sm font-medium text-slate-600">{{ emp.position }}</div>
              <div class="mt-1">
                <span
                  :class="emp.employment_type === 'ประจำ' ? 'bg-blue-50 text-blue-500 border-blue-100' : 'bg-amber-50 text-amber-600 border-amber-100'"
                  class="text-[10px] font-bold px-2 py-0.5 rounded border border-opacity-30 uppercase">
                  พนักงาน{{ emp.employment_type }}
                  <span v-if="emp.employment_type === 'ชั่วคราว'">({{ calculateDays(emp.temp_start_date,
                    emp.temp_end_date) }} วัน)</span>
                </span>
              </div>
            </td>
            <td class="px-8 py-5 text-right">
              <div class="flex justify-end items-center gap-2">

                <button @click="openEditModal(emp)"
                  class="flex items-center gap-2 px-4 py-2 rounded-xl text-[#4c6ef5] font-bold text-sm hover:bg-blue-50 transition-all active:scale-95 group/edit">
                  <i class="fa-solid fa-pen-to-square text-xs transition-transform group-hover/edit:scale-110"></i>
                  ✏️
                  <span>แก้ไขข้อมูล</span>
                </button>

                <button @click="confirmDelete(emp)"
                  class="w-10 h-10 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-300 shadow-sm group/delete active:scale-90">
                  <i class="fa-solid fa-trash-can text-sm group-hover/delete:animate-pulse"></i>
                  🗑️
                </button>

              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>
      <div
        class="bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl z-10 overflow-hidden flex flex-col animate-modal-in">

        <div class="px-12 py-8 border-b border-slate-50">
          <h2 class="text-2xl font-bold text-[#2563eb]">แก้ไขข้อมูลพนักงาน</h2>
          <p class="text-sm font-bold text-slate-400 mt-1">ข้อมูลพนักงาน</p>
        </div>

        <div class="p-12 space-y-10 overflow-y-auto max-h-[75vh]">

          <div class="grid grid-cols-2 gap-x-12 gap-y-10">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
              <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">ชื่อ-นามสกุล</label>
              <input v-model="form.name" type="text"
                class="w-full border-b border-slate-200 py-1 outline-none focus:border-blue-500 text-sm font-bold text-slate-600 bg-transparent">
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
              <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">อีเมล</label>
              <input v-model="form.email" type="text"
                class="w-full border-b border-slate-200 py-1 outline-none focus:border-blue-500 text-sm font-bold text-slate-600 bg-transparent">
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
              <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">เบอร์โทรศัพท์</label>
              <input v-model="form.phone" type="text"
                class="w-full border-b border-slate-200 py-1 outline-none focus:border-blue-500 text-sm font-bold text-slate-600 bg-transparent">
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 opacity-70">
              <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">วันที่เริ่มงาน</label>
              <div class="py-1 text-sm font-bold text-slate-400 border-b border-transparent">{{ form.start_work_date }}
              </div>
            </div>
          </div>

          <div v-if="form.employment_type === 'ประจำ'" class="grid grid-cols-2 gap-x-12">
            <div class="p-4 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
              <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">สังกัด (แก้ไขไม่ได้)</label>
              <select v-model="form.branch" disabled
                class="w-full py-1 text-sm font-bold text-slate-400 bg-transparent cursor-not-allowed appearance-none border-none">
                <option value="บริการธุรกิจใหม่">บริการธุรกิจใหม่</option>
              </select>
            </div>
            <div class="p-4 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
              <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">ตำแหน่ง (แก้ไขไม่ได้)</label>
              <select v-model="form.position" disabled
                class="w-full py-1 text-sm font-bold text-slate-400 bg-transparent cursor-not-allowed appearance-none border-none">
                <option value="Business Support">Business Support</option>
              </select>
            </div>
          </div>

          <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-6">
            <label class="text-sm font-bold text-slate-500">ประเภทพนักงาน:</label>
            <div class="flex gap-6">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" v-model="form.employment_type" value="ประจำ" class="w-4 h-4 accent-blue-600">
                <span class="text-sm font-bold text-slate-600">พนักงานประจำ</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" v-model="form.employment_type" value="ชั่วคราว" class="w-4 h-4 accent-blue-600">
                <span class="text-sm font-bold text-slate-600">ชั่วคราว</span>
              </label>
            </div>
          </div>

          <div v-if="form.employment_type === 'ชั่วคราว'"
            class="p-10 bg-[#f8fbff] rounded-[2.5rem] border border-blue-100 space-y-10 animate-fade-in">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="h-6 w-6 rounded bg-blue-500 flex items-center justify-center text-white"><i
                    class="fa-solid fa-check text-[10px]"></i></div>
                <span class="text-sm font-bold text-slate-700">เป็นการแต่งตั้งชั่วคราว (รักษาการแทน)</span>
              </div>
              <div v-if="tempDaysCount > 0"
                class="text-blue-600 font-bold text-xs bg-white px-4 py-1.5 rounded-full border border-blue-100">
                ระยะเวลารวม: {{ tempDaysCount }} วัน
              </div>
            </div>
            <div class="grid grid-cols-2 gap-x-12">
              <div>
                <label class="text-[12px] font-bold text-blue-500 mb-2 block italic">เลือกสังกัดใหม่ *</label>
                <select v-model="form.branch"
                  class="w-full border-b-2 border-blue-400 py-1 text-sm font-bold text-slate-700 bg-transparent outline-none">
                  <option value="สำนักงานใหญ่">สำนักงานใหญ่</option>
                  <option value="บริการธุรกิจใหม่">บริการธุรกิจใหม่</option>
                </select>
              </div>
              <div>
                <label class="text-[12px] font-bold text-blue-500 mb-2 block italic">เลือกตำแหน่งใหม่ *</label>
                <select v-model="form.position"
                  class="w-full border-b-2 border-blue-400 py-1 text-sm font-bold text-slate-700 bg-transparent outline-none">
                  <option value="General Staff (STF)">General Staff (STF)</option>
                  <option value="Business Support">Business Support</option>
                </select>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-x-12">
              <input v-model="form.temp_start_date" type="date"
                class="border-b-2 border-slate-200 py-1 text-sm font-bold text-slate-600 bg-transparent outline-none">
              <input v-model="form.temp_end_date" type="date"
                class="border-b-2 border-slate-200 py-1 text-sm font-bold text-slate-600 bg-transparent outline-none">
            </div>
          </div>

        </div>

        <div class="px-12 py-10 flex justify-center gap-4 bg-slate-50/30 border-t border-slate-100">
          <button @click="showModal = false"
            class="px-10 py-3 rounded-2xl border border-slate-200 text-slate-400 font-bold text-sm hover:bg-white transition-colors">ยกเลิก</button>
          <button @click="handleSave"
            class="px-12 py-3 rounded-2xl bg-[#2563eb] text-white font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">ยืนยันบันทึก</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import Swal from 'sweetalert2';

// --- Data ---
const employees = ref([
  { id: 1, name: 'นางสาวสุพิชญา โสมาบุตร (จำ)', employment_type: 'ประจำ', email: 'supitchaya@inet.co.th', phone: '081-234-5678', position: 'Business Support', branch: 'บริการธุรกิจใหม่', start_work_date: '16 ก.ค. 2561' },
  { id: 2, name: 'นายเกรียงไกร ใจดี', employment_type: 'ชั่วคราว', email: 'gor@gmail.com', phone: '089-123-4444', position: 'General Staff (STF)', branch: 'สำนักงานใหญ่', start_work_date: '28 ก.พ. 2569', temp_start_date: '2026-02-28', temp_end_date: '2026-04-02' }
]);

const showModal = ref(false);
const form = reactive({ id: null, name: '', email: '', phone: '', employment_type: 'ประจำ', position: '', branch: '', start_work_date: '', temp_start_date: '', temp_end_date: '' });

// --- Methods ---
const calculateDays = (start, end) => {
  if (!start || !end) return 0;
  const s = new Date(start);
  const e = new Date(end);
  return Math.ceil(Math.abs(e - s) / (1000 * 60 * 60 * 24)) + 1;
};

const tempDaysCount = computed(() => calculateDays(form.temp_start_date, form.temp_end_date));

const openEditModal = (emp) => {
  Object.assign(form, { ...emp });
  showModal.value = true;
};

const handleSave = () => {
  const idx = employees.value.findIndex(e => e.id === form.id);
  if (idx !== -1) {
    employees.value[idx] = { ...form };
    showModal.value = false;

    Swal.fire({
      title: 'สำเร็จ!',
      text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
      icon: 'success',
      confirmButtonText: 'ตกลง',
      confirmButtonColor: '#2563eb',
      customClass: { popup: 'rounded-[2rem]' }
    });
  }
};

const confirmDelete = (emp) => {
  Swal.fire({
    title: 'ยืนยันการลบ?',
    text: `คุณต้องการลบข้อมูลของ "${emp.name}" ใช่หรือไม่?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#f43f5e',
    cancelButtonColor: '#94a3b8',
    confirmButtonText: '<i class="fa-solid fa-trash-can mr-2"></i> ยืนยันลบข้อมูล',
    cancelButtonText: 'ยกเลิก',
    reverseButtons: true,
    customClass: {
      popup: 'rounded-[2.5rem]',
      confirmButton: 'rounded-xl px-6 py-3 font-bold',
      cancelButton: 'rounded-xl px-6 py-3 font-bold'
    }
  }).then((result) => {
    if (result.isConfirmed) {
      employees.value = employees.value.filter(e => e.id !== emp.id);
      Swal.fire({
        title: 'ลบแล้ว!',
        text: 'ข้อมูลพนักงานถูกลบออกจากระบบแล้ว',
        icon: 'success',
        confirmButtonColor: '#2563eb',
        customClass: { popup: 'rounded-[2rem]' }
      });
    }
  });
};
</script>

<style scoped>
.font-ibm {
  font-family: 'IBM Plex Sans Thai', sans-serif;
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-out;
}

.animate-modal-in {
  animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(5px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes modalIn {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(30px);
  }

  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* ปรับแต่ง Font ให้ SweetAlert ใช้ฟอนต์เดียวกับโปรเจกต์ */
:deep(.swal2-popup) {
  font-family: 'IBM Plex Sans Thai', sans-serif;
}
</style>