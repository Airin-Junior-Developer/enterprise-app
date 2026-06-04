<template>
  <div class="p-6">
    <!-- Add Form -->
    <div v-if="showForm" class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-6">
      <h4 class="text-sm font-bold text-slate-700 mb-4">➕ บันทึกการเลื่อนขั้นเงินเดือน</h4>
      <form @submit.prevent="saveRecord" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">วันที่มีผล <span class="text-rose-500">*</span></label>
          <input v-model="form.effective_date" type="date" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">ประเภทการปรับ <span class="text-rose-500">*</span></label>
          <select v-model="form.promotion_type" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <option value="" disabled>-- เลือก --</option>
            <option value="step_increment">เลื่อนขั้น</option>
            <option value="level_promotion">เลื่อนระดับ</option>
            <option value="qualification_adjustment">ปรับวุฒิ</option>
            <option value="special_adjustment">ปรับพิเศษ</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">เงินเดือนเดิม (บาท) <span class="text-rose-500">*</span></label>
          <input v-model.number="form.old_salary" type="number" min="0" step="0.01" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-600 mb-1">เงินเดือนใหม่ (บาท) <span class="text-rose-500">*</span></label>
          <input v-model.number="form.new_salary" type="number" min="0" step="0.01" required
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-bold text-slate-600 mb-1">หมายเหตุ</label>
          <textarea v-model="form.notes" rows="2" placeholder="เหตุผล / รายละเอียด..."
            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
        </div>
        <div v-if="form.old_salary && form.new_salary" class="md:col-span-2 p-3 rounded-xl text-sm font-bold"
          :class="form.new_salary >= form.old_salary ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'">
          {{ form.new_salary >= form.old_salary ? '▲' : '▼' }}
          ส่วนต่าง: {{ formatMoney(Math.abs(form.new_salary - form.old_salary)) }} บาท
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
      <h3 class="text-sm font-bold text-slate-700">ประวัติเงินเดือน</h3>
      <button v-if="!showForm" @click="openAdd" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 flex items-center gap-1.5">
        <span class="text-base leading-none">+</span> บันทึกการปรับ
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
      <div v-if="isLoading" class="py-10 text-center text-blue-600 font-bold text-sm animate-pulse">กำลังโหลด...</div>
      <div v-else-if="records.length === 0" class="py-10 text-center text-slate-400 text-sm">ยังไม่มีประวัติการปรับเงินเดือน</div>
      <table v-else class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">วันที่มีผล</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">เดิม (บาท)</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">ใหม่ (บาท)</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">ส่วนต่าง</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">ประเภท</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">บันทึกโดย</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">ลบ</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="rec in records" :key="rec.id" class="hover:bg-slate-50/50">
            <td class="px-4 py-3 text-slate-600 text-xs">{{ formatDate(rec.effective_date) }}</td>
            <td class="px-4 py-3 text-right text-slate-600 font-mono">{{ formatMoney(rec.old_salary) }}</td>
            <td class="px-4 py-3 text-right text-slate-800 font-bold font-mono">{{ formatMoney(rec.new_salary) }}</td>
            <td class="px-4 py-3 text-right font-bold font-mono"
              :class="(rec.new_salary - rec.old_salary) >= 0 ? 'text-emerald-600' : 'text-rose-600'">
              {{ (rec.new_salary - rec.old_salary) >= 0 ? '▲' : '▼' }}
              {{ formatMoney(Math.abs(rec.new_salary - rec.old_salary)) }}
            </td>
            <td class="px-4 py-3 text-center">
              <span class="px-2 py-0.5 rounded-md text-xs font-bold" :class="typeBadge(rec.promotion_type)">{{ typeLabel(rec.promotion_type) }}</span>
            </td>
            <td class="px-4 py-3 text-slate-500 text-xs">
              {{ rec.recorder ? `${rec.recorder.first_name} ${rec.recorder.last_name}` : '-' }}
            </td>
            <td class="px-4 py-3 text-center">
              <button @click="deleteRecord(rec.id)" class="h-7 w-7 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg flex items-center justify-center text-xs mx-auto" title="ลบ">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({ employee: { type: Object, required: true } });

const records = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const showForm = ref(false);

const lastNewSalary = computed(() => records.value.length > 0 ? records.value[0].new_salary : 0);
const emptyForm = () => ({ effective_date: '', old_salary: lastNewSalary.value, new_salary: 0, promotion_type: '', notes: '' });
const form = ref(emptyForm());

const fetchRecords = async () => {
  isLoading.value = true;
  try {
    const res = await axios.get(`/api/employees/${props.employee.user_id}/salary-history`);
    records.value = res.data;
  } catch (e) { console.error(e); }
  finally { isLoading.value = false; }
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' }) : '';
const formatMoney = (n) => Number(n).toLocaleString('th-TH', { minimumFractionDigits: 2 });

const typeLabel = (t) => ({ step_increment: 'เลื่อนขั้น', level_promotion: 'เลื่อนระดับ', qualification_adjustment: 'ปรับวุฒิ', special_adjustment: 'ปรับพิเศษ' })[t] || t;
const typeBadge = (t) => ({ step_increment: 'bg-blue-50 text-blue-700 border border-blue-100', level_promotion: 'bg-violet-50 text-violet-700 border border-violet-100', qualification_adjustment: 'bg-amber-50 text-amber-700 border border-amber-100', special_adjustment: 'bg-emerald-50 text-emerald-700 border border-emerald-100' })[t] || 'bg-slate-100 text-slate-600';

const openAdd = () => { form.value = emptyForm(); showForm.value = true; };
const cancelForm = () => { showForm.value = false; };

const saveRecord = async () => {
  isSaving.value = true;
  try {
    await axios.post(`/api/employees/${props.employee.user_id}/salary-history`, form.value);
    cancelForm();
    await fetchRecords();
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1200, showConfirmButton: false });
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally { isSaving.value = false; }
};

const deleteRecord = (id) => {
  Swal.fire({ title: 'ยืนยันการลบ?', text: 'ประวัติเงินเดือนที่ลบแล้วไม่สามารถกู้คืนได้', icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
    .then(async (r) => {
      if (r.isConfirmed) {
        try {
          await axios.delete(`/api/employees/${props.employee.user_id}/salary-history/${id}`);
          await fetchRecords();
          Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', timer: 1200, showConfirmButton: false });
        } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
      }
    });
};

watch(() => props.employee.user_id, () => { cancelForm(); fetchRecords(); });
onMounted(fetchRecords);
</script>
