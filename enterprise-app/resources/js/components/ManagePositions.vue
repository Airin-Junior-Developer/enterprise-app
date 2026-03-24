<template>
  <div class="p-8 bg-slate-50 min-h-screen font-ibm text-slate-600">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-6">
      <div class="flex gap-4">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5">
          <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-[#2563eb]">
            <i class="fa-solid fa-users text-2xl"></i>
          </div>
          <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">พนักงานทั้งหมด</p>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">
              {{ positions.length }} <span class="text-sm font-medium text-slate-400">คน</span>
            </h2>
          </div>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-4">
          <div class="h-12 w-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl">⏱️</div>
          <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">รักษาการ (ชั่วคราว)</p>
            <h3 class="text-2xl font-black text-slate-800">{{ temporaryCount }}</h3>
          </div>
        </div>
      </div>

      <div class="w-full max-w-md relative">
        <input type="text" v-model="searchQuery" placeholder="ค้นหาชื่อพนักงาน หรือ ตำแหน่ง..." 
          class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-100 rounded-2xl shadow-sm outline-none focus:border-blue-500 text-sm transition-all">
        <span class="absolute left-4 top-4 text-slate-400">🔍</span>
      </div>
    </div>

    <div class="bg-white p-4 rounded-[2rem] border border-slate-100 shadow-sm mb-6 flex gap-4">
      <div class="md:w-64 relative">
        <select v-model="selectedType"
          class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm font-bold text-slate-600 appearance-none cursor-pointer">
          <option value="all">ทุกประเภทการจ้าง</option>
          <option value="permanent">พนักงานปกติ</option>
          <option value="temporary">พนักงานรักษาการ (ชั่วคราว)</option>
        </select>
        <span class="absolute right-4 top-3.5 pointer-events-none text-slate-400">▼</span>
      </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse whitespace-nowrap">
          <thead class="bg-slate-50/50 text-slate-400 font-bold border-b border-slate-50">
            <tr class="text-[10px] uppercase tracking-[0.15em]">
              <th class="px-8 py-5 text-center w-20">ลำดับ</th>
              <th class="px-6 py-5">รายชื่อพนักงาน</th>
              <th class="px-6 py-5">สาขา / สังกัด</th>
              <th class="px-6 py-5">ตำแหน่งปัจจุบัน</th>
              <th class="px-6 py-5 text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-if="isLoading">
              <td colspan="5" class="px-6 py-12 text-center text-blue-600 font-bold animate-pulse">กำลังโหลดข้อมูล...</td>
            </tr>
            <tr v-else-if="filteredPositions.length === 0">
              <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-bold italic">ไม่พบข้อมูลที่ค้นหา</td>
            </tr>
            <tr v-else v-for="(item, index) in filteredPositions" :key="item.id" class="hover:bg-blue-50/30 transition-colors group">
              <td class="px-8 py-6 text-center text-slate-300 font-bold">{{ index + 1 }}</td>
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700">{{ item.employee_name }}</div>
                <div class="text-[10px] text-slate-400">{{ item.email || '-' }}</div>
              </td>
              <td class="px-6 py-6 text-slate-600 font-medium">{{ item.branch || 'ไม่ระบุ' }}</td>
              <td class="px-6 py-6">
                <div class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ item.name }}</div>
                <div v-if="item.temp_position_end_date" 
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-600 text-[10px] font-bold mt-1">
                  ⏱️ รักษาการถึง: {{ formatDate(item.temp_position_end_date) }}
                </div>
              </td>
              <td class="px-6 py-6 text-center">
                <div class="flex items-center justify-center gap-2">
                  <button @click="openEditModal(item)" class="h-9 w-9 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white transition-all shadow-sm">✏️</button>
                  <button @click="confirmDelete(item)" class="h-9 w-9 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-rose-500 hover:text-white transition-all shadow-sm">🗑️</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
      <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-modal-in">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
          <h3 class="text-lg font-bold text-blue-600">แก้ไขตำแหน่งงาน</h3>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
        </div>

        <div class="p-8 space-y-6 overflow-y-auto">
          <div class="space-y-1">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">พนักงาน</label>
            <input v-model="form.employee_name" type="text" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-500 cursor-not-allowed outline-none" />
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">เลือกตำแหน่งใหม่ <span class="text-rose-500">*</span></label>
            <select v-model="form.position_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all">
              <option value="" disabled>-- กรุณาเลือกตำแหน่ง --</option>
              <option v-for="pos in masterPositions" :key="pos.position_id" :value="pos.position_id">
                {{ pos.position_name }} ({{ pos.level_code }})
              </option>
            </select>
          </div>

          <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
            <label class="flex items-center gap-3 cursor-pointer select-none">
              <input type="checkbox" v-model="form.is_temporary" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
              <span class="text-sm font-bold text-slate-700">แต่งตั้งชั่วคราว (รักษาการแทน)</span>
            </label>
            <div v-if="form.is_temporary" class="mt-4 animate-fade-in">
              <label class="block text-[10px] font-bold text-blue-500 mb-2 tracking-widest uppercase">วันที่สิ้นสุดการรักษาการ *</label>
              <input type="date" v-model="form.end_date" :min="todayDate" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm outline-none focus:border-blue-400" />
            </div>
          </div>
        </div>

        <div class="p-6 bg-slate-50 flex justify-end gap-3 border-t border-slate-100">
          <button @click="showModal = false" class="px-6 py-2.5 rounded-2xl border border-slate-200 text-slate-500 font-bold text-sm hover:bg-white transition-all">ยกเลิก</button>
          <button @click="handleSave" :disabled="isSaving" class="px-8 py-2.5 rounded-2xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-200 text-sm hover:bg-blue-700 transition-all flex items-center gap-2">
            <span v-if="isSaving" class="animate-spin text-lg">🌀</span>
            {{ isSaving ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

// --- States ---
const positions = ref([]);
const masterPositions = ref([]);
const isLoading = ref(true);
const isSaving = ref(false);
const showModal = ref(false);
const searchQuery = ref('');
const selectedType = ref('all');

const form = reactive({
  id: null,
  employee_name: '',
  position_id: '',
  is_temporary: false,
  end_date: ''
});

// --- Fetch Data ---
const fetchPositions = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/manage-positions-list');
    positions.value = response.data;
  } catch (error) {
    console.error("Fetch Positions Error:", error);
  } finally {
    isLoading.value = false;
  }
};

const fetchMasterPositions = async () => {
  try {
    const response = await axios.get('/api/positions');
    masterPositions.value = response.data;
  } catch (error) {
    console.error("Fetch Master Error:", error);
  }
};

onMounted(() => {
  fetchPositions();
  fetchMasterPositions();
});

// --- Computed ---
const temporaryCount = computed(() => {
  return positions.value.filter(p => p.temp_position_end_date !== null).length;
});

const filteredPositions = computed(() => {
  let result = positions.value;
  if (selectedType.value !== 'all') {
    result = result.filter(p => 
      selectedType.value === 'temporary' ? p.temp_position_end_date !== null : p.temp_position_end_date === null
    );
  }
  if (searchQuery.value) {
    const search = searchQuery.value.toLowerCase();
    result = result.filter(p => 
      (p.employee_name?.toLowerCase().includes(search)) || 
      (p.name?.toLowerCase().includes(search))
    );
  }
  return result;
});

const todayDate = computed(() => new Date().toISOString().split('T')[0]);

// --- Actions ---
const openEditModal = (item) => {
  // พยายามจับคู่ตำแหน่งเดิมกับ ID ใน Master
  const matchedPos = masterPositions.value.find(p => p.position_name === item.name);
  
  Object.assign(form, { 
    id: item.id, 
    employee_name: item.employee_name, 
    position_id: matchedPos?.position_id || '', 
    is_temporary: !!item.temp_position_end_date, 
    end_date: item.temp_position_end_date || '' 
  });
  showModal.value = true;
};

const handleSave = async () => {
  if (!form.position_id) return Swal.fire('แจ้งเตือน', 'กรุณาเลือกตำแหน่งใหม่', 'warning');
  if (form.is_temporary && !form.end_date) return Swal.fire('แจ้งเตือน', 'กรุณาระบุวันที่สิ้นสุดการรักษาการ', 'warning');

  isSaving.value = true;
  try {
    await axios.patch(`/api/employees/${form.id}/position`, { 
      position_id: form.position_id, 
      is_temporary: form.is_temporary, 
      end_date: form.is_temporary ? form.end_date : null 
    });
    showModal.value = false;
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1500, showConfirmButton: false });
    fetchPositions();
  } catch (error) {
    Swal.fire({ 
      icon: 'error', 
      title: 'เกิดข้อผิดพลาด', 
      text: error.response?.data?.message || 'ไม่สามารถบันทึกข้อมูลได้' 
    });
  } finally {
    isSaving.value = false;
  }
};

const confirmDelete = async (item) => {
  const result = await Swal.fire({ 
    title: 'ยืนยันการลบ?', 
    text: `คุณต้องการลบข้อมูลพนักงาน "${item.employee_name}" หรือไม่?`, 
    icon: 'warning', 
    showCancelButton: true, 
    confirmButtonColor: '#f43f5e',
    confirmButtonText: 'ยืนยันลบ',
    cancelButtonText: 'ยกเลิก'
  });

  if (result.isConfirmed) {
    try {
      await axios.delete(`/api/employees/${item.id}`);
      Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', timer: 1000, showConfirmButton: false });
      fetchPositions();
    } catch (e) {
      Swal.fire('Error', 'ไม่สามารถลบข้อมูลได้', 'error');
    }
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('th-TH', { 
    year: 'numeric', month: 'short', day: 'numeric' 
  });
};
</script>

<style scoped>
.animate-modal-in {
  animation: zoomIn 0.3s ease-out;
}

@keyframes zoomIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>