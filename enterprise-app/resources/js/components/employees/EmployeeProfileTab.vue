<template>
  <div class="p-6 space-y-6">
    <form @submit.prevent="save">
      <!-- Personal Info -->
      <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
          <span class="w-4 h-1 bg-blue-500 rounded-full"></span> ข้อมูลส่วนตัว
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
          <div class="col-span-12 md:col-span-3">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">คำนำหน้า</label>
            <select v-model="form.prefix"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium">
              <option value="">เลือก</option>
              <option value="นาย">นาย</option>
              <option value="นาง">นาง</option>
              <option value="นางสาว">นางสาว</option>
            </select>
          </div>
          <div class="col-span-12 md:col-span-4">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">ชื่อจริง <span class="text-rose-500">*</span></label>
            <input v-model="form.first_name" type="text" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="ชื่อ" />
          </div>
          <div class="col-span-12 md:col-span-5">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">นามสกุล <span class="text-rose-500">*</span></label>
            <input v-model="form.last_name" type="text" required
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="นามสกุล" />
          </div>
          <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">เลขบัตรประชาชน</label>
            <input v-model="form.id_card_number" type="text" maxlength="13"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="เลข 13 หลัก" />
          </div>
          <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์</label>
            <input v-model="form.phone_number" type="tel" maxlength="10"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
              placeholder="08XXXXXXXX" />
          </div>
        </div>
      </div>

      <!-- Login Info -->
      <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 shadow-sm mb-6">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider">🔐 บัญชีเข้าสู่ระบบ</h4>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" v-model="enablePasswordEdit" class="w-4 h-4 text-blue-600 rounded border-slate-300" />
            <span class="text-sm font-bold text-slate-600">เปลี่ยนรหัสผ่าน?</span>
          </label>
        </div>
        <div>
          <label class="block text-sm font-bold text-slate-700 mb-1.5">อีเมล (Username) <span class="text-rose-500">*</span></label>
          <input v-model="form.email" type="email" required
            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 text-sm font-medium"
            placeholder="employee@enterprise.com" />
        </div>
        <div v-if="enablePasswordEdit" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-blue-100/50">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1.5">รหัสผ่านใหม่ <span class="text-rose-500">*</span></label>
            <input v-model="form.password" type="password"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm"
              placeholder="••••••••" />
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1.5">ยืนยันรหัสผ่าน <span class="text-rose-500">*</span></label>
            <input v-model="form.password_confirmation" type="password"
              class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none text-sm"
              :class="{ 'border-rose-400 bg-rose-50': passwordMismatch }"
              placeholder="••••••••" />
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <button type="submit"
          class="px-8 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow-md font-bold text-sm disabled:opacity-50 transition-colors"
          :disabled="isSaving || (enablePasswordEdit && passwordMismatch)">
          {{ isSaving ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  employee: { type: Object, required: true },
});

const emit = defineEmits(['updated']);

const isSaving = ref(false);
const enablePasswordEdit = ref(false);

const form = ref({ ...props.employee, password: '', password_confirmation: '' });

watch(() => props.employee, (newEmp) => {
  form.value = { ...newEmp, password: '', password_confirmation: '' };
  enablePasswordEdit.value = false;
});

const passwordMismatch = computed(() =>
  enablePasswordEdit.value && form.value.password && form.value.password !== form.value.password_confirmation
);

const save = async () => {
  isSaving.value = true;
  const payload = { ...form.value };
  if (!enablePasswordEdit.value) {
    delete payload.password;
    delete payload.password_confirmation;
  }
  try {
    await axios.put(`/api/employees/${props.employee.user_id}`, payload);
    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1500, showConfirmButton: false });
    emit('updated');
  } catch (e) {
    Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
  } finally {
    isSaving.value = false;
  }
};
</script>
