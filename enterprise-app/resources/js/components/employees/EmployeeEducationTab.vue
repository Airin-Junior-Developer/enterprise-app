<template>
  <div class="p-6">
    <!-- Add / Edit Form -->
    <div v-if="showForm" class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-6">
      <h4 class="text-sm font-bold text-slate-700 mb-4">{{ editingRecord ? '✏️ แก้ไขประวัติการศึกษา' : '➕ เพิ่มประวัติการศึกษา' }}</h4>
      <form @submit.prevent="saveRecord" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">สถาบันการศึกษา <span class="text-rose-500">*</span></label>
          <input v-model="form.institution_name" type="text" required placeholder="เช่น มหาวิทยาลัยจุฬาลงกรณ์"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">ระดับการศึกษา <span class="text-rose-500">*</span></label>
          <select v-model="form.degree_level" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <option value="" disabled>-- เลือก --</option>
            <option value="High School">มัธยมศึกษา / ม.6</option>
            <option value="Vocational">ปวช. / ปวส.</option>
            <option value="Bachelor's">ปริญญาตรี</option>
            <option value="Master's">ปริญญาโท</option>
            <option value="PhD">ปริญญาเอก</option>
            <option value="Other">อื่นๆ</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">สาขาวิชา <span class="text-rose-500">*</span></label>
          <input v-model="form.field_of_study" type="text" required placeholder="เช่น วิทยาการคอมพิวเตอร์"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">ปีที่จบ (พ.ศ.)</label>
          <input v-model.number="form.graduation_year" type="number" min="1900" :max="currentYear" placeholder="เช่น 2565"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">เกรดเฉลี่ย (GPA)</label>
          <input v-model.number="form.gpa" type="number" step="0.01" min="0" max="4" placeholder="เช่น 3.50"
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">หมายเหตุ</label>
          <textarea v-model="form.notes" rows="2" placeholder="รายละเอียดเพิ่มเติม..."
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
        </div>
        <div class="md:col-span-2 flex justify-end gap-3 pt-2">
          <button type="button" @click="cancelForm" class="px-5 py-2 rounded-xl border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-bold text-sm">ยกเลิก</button>
          <button type="submit" :disabled="isSaving" class="px-6 py-2 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 disabled:opacity-50">
            {{ isSaving ? 'กำลังบันทึก...' : 'บันทึก' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Toolbar -->
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-sm font-bold text-slate-700">ประวัติการศึกษา</h3>
      <button v-if="!showForm" @click="openAdd"
        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-1.5">
        <span class="text-base leading-none">+</span> เพิ่ม
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
      <div v-if="isLoading" class="py-10 text-center text-blue-600 font-bold text-sm animate-pulse">กำลังโหลด...</div>
      <div v-else-if="records.length === 0" class="py-10 text-center text-slate-400 text-sm">ไม่มีข้อมูลการศึกษา</div>
      <table v-else class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">สถาบัน</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">ระดับ</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">สาขา</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">ปี (พ.ศ.)</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">GPA</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">จัดการ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="rec in records" :key="rec.id" class="hover:bg-slate-50/50">
            <td class="px-4 py-3 font-medium text-slate-800">{{ rec.institution_name }}</td>
            <td class="px-4 py-3 text-slate-600">{{ rec.degree_level }}</td>
            <td class="px-4 py-3 text-slate-600">{{ rec.field_of_study }}</td>
            <td class="px-4 py-3 text-center text-slate-500">{{ rec.graduation_year ? rec.graduation_year + 543 : '-' }}</td>
            <td class="px-4 py-3 text-center text-slate-500">{{ rec.gpa ?? '-' }}</td>
            <td class="px-4 py-3 text-center">
              <div class="flex items-center justify-center gap-1.5">
                <button @click="openEdit(rec)" class="h-7 w-7 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg flex items-center justify-center text-xs" title="แก้ไข">✏️</button>
                <button @click="deleteRecord(rec.id)" class="h-7 w-7 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg flex items-center justify-center text-xs" title="ลบ">🗑️</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({ employee: { type: Object, required: true } });

const records = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const showForm = ref(false);
const editingRecord = ref(null);
const currentYear = new Date().getFullYear();

const emptyForm = () => ({ institution_name: '', degree_level: '', field_of_study: '', graduation_year: null, gpa: null, notes: '' });
const form = ref(emptyForm());

const fetchRecords = async () => {
  isLoading.value = true;
  try {
    const res = await axios.get(`/api/employees/${props.employee.user_id}/education`);
    records.value = res.data;
  } catch (e) { console.error(e); }
  finally { isLoading.value = false; }
};

const openAdd = () => { form.value = emptyForm(); editingRecord.value = null; showForm.value = true; };
const openEdit = (rec) => { form.value = { ...rec }; editingRecord.value = rec; showForm.value = true; };
const cancelForm = () => { showForm.value = false; editingRecord.value = null; };

const saveRecord = async () => {
  isSaving.value = true;
  try {
    if (editingRecord.value) {
      await axios.put(`/api/employees/${props.employee.user_id}/education/${editingRecord.value.id}`, form.value);
    } else {
      await axios.post(`/api/employees/${props.employee.user_id}/education`, form.value);
    }
    cancelForm();
    await fetchRecords();
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1200, showConfirmButton: false });
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally { isSaving.value = false; }
};

const deleteRecord = (id) => {
  Swal.fire({ title: 'ยืนยันการลบ?', icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
    .then(async (r) => {
      if (r.isConfirmed) {
        try {
          await axios.delete(`/api/employees/${props.employee.user_id}/education/${id}`);
          await fetchRecords();
          Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', timer: 1200, showConfirmButton: false });
        } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
      }
    });
};

watch(() => props.employee.user_id, () => { cancelForm(); fetchRecords(); });
onMounted(fetchRecords);
</script>
