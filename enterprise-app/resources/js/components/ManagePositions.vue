<template>
<<<<<<< HEAD
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
=======
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
              <td colspan="8" class="px-6 py-12 text-center text-blue-600 font-bold animate-pulse">กำลังโหลดข้อมูล...</td>
            </tr>
            <tr v-else-if="filteredPositions.length === 0">
              <td colspan="8" class="px-6 py-12 text-center text-slate-400">ไม่พบข้อมูลที่ค้นหา</td>
            </tr>
            <tr v-else v-for="(item, index) in filteredPositions" :key="item.id" class="hover:bg-blue-50/30 transition-colors group">
              <td class="px-8 py-6 text-center text-slate-300 font-bold">{{ index + 1 }}</td>
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700">{{ item.employee_name }}</div>
              </td>
              <td class="px-6 py-6 text-slate-600 font-medium">{{ item.branch }}</td>
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ item.name }}</div>
                <div class="text-[10px] text-slate-400 italic mb-1">{{ item.name_en }}</div>
                
                <div v-if="item.temp_position_end_date" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-600 text-[10px] font-bold mt-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                  </svg>
                  รักษาการถึง: {{ formatDate(item.temp_position_end_date) }}
                </div>
              </td>
              <td class="px-6 py-6 text-slate-500 text-xs">{{ item.email }}</td>
              <td class="px-6 py-6 text-center text-slate-600 font-medium">{{ item.phone || '-' }}</td>
              <td class="px-6 py-6 text-center text-slate-500">{{ item.start_date || '-' }}</td>
              <td class="px-6 py-6 text-center">
                <div class="flex items-center justify-center gap-3">
                  <button @click="openEditModal(item)" class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white transition-colors" title="แก้ไขตำแหน่ง">✏️</button>
                  <button @click="confirmDelete(item)" class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-rose-500 hover:text-white transition-colors" title="ลบข้อมูลพนักงาน">🗑️</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-clear-sm">
      <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 font-ibm flex flex-col max-h-[90vh]">
        
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
          <h3 class="text-lg font-bold text-blue-600">แก้ไขตำแหน่งงาน</h3>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition-colors text-xl">✕</button>
        </div>
        
        <div class="p-8 space-y-6 overflow-y-auto">
          
          <div class="space-y-2">
            <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">พนักงาน</label>
            <input v-model="form.employee_name" type="text" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-500 cursor-not-allowed outline-none" />
          </div>

          <div class="space-y-2">
            <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">เลือกตำแหน่งใหม่ <span class="text-rose-500">*</span></label>
            <select v-model="form.position_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-slate-700">
              <option value="" disabled>-- กรุณาเลือกตำแหน่ง --</option>
              <option v-for="pos in allowedPositions" :key="pos.position_id" :value="pos.position_id">
              {{ pos.position_name }} ({{ pos.level_code }})
              </option>
            </select>
          </div>

          <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
            <label class="flex items-center gap-3 cursor-pointer select-none">
              <input type="checkbox" v-model="form.is_temporary" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" />
              <span class="text-sm font-bold text-slate-700">เป็นการแต่งตั้งชั่วคราว (รักษาการแทน)</span>
            </label>

            <div v-if="form.is_temporary" class="mt-4 animate-in slide-in-from-top-2">
              <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">วันที่สิ้นสุดการรักษาการ <span class="text-rose-500">*</span></label>
              <input type="date" v-model="form.end_date" :min="todayDate" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none text-slate-700 font-medium" />
            </div>
          </div>
          
        </div>

        <div class="p-6 bg-slate-50 flex justify-end gap-3 border-t border-slate-100 shrink-0">
          <button @click="showModal = false" class="px-6 py-2.5 rounded-2xl border border-slate-200 text-slate-500 font-bold text-sm hover:bg-white hover:text-slate-700 transition-all">ยกเลิก</button>
          <button @click="handleSave" class="px-8 py-2.5 rounded-2xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-200 text-sm hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-2" :disabled="isSaving">
            <svg v-if="isSaving" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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
.font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
.font-ibm { font-family: 'IBM Plex Sans Thai', sans-serif; }

.recruit-swal-popup { border-radius: 2.5rem !important; padding: 2.5rem !important; font-family: 'IBM Plex Sans Thai', sans-serif !important; }
.recruit-swal-title { font-size: 1.5rem !important; font-weight: 700 !important; color: #1e293b !important; }
.recruit-swal-confirm { background-color: #2563eb !important; color: white !important; border-radius: 1.25rem !important; padding: 0.8rem 2.5rem !important; font-weight: 700 !important; margin: 0.5rem !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2) !important; }
.recruit-swal-cancel { background-color: #f1f5f9 !important; color: #64748b !important; border-radius: 1.25rem !important; padding: 0.8rem 2.5rem !important; font-weight: 700 !important; margin: 0.5rem !important; }

.animate-in { animation: zoomIn 0.2s ease-out; }
@keyframes zoomIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
>>>>>>> origin/sea
</style>