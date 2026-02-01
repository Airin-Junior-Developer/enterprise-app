<template>
    <div class="p-6 bg-slate-50 min-h-screen font-sans text-slate-900">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">พิจารณาคำร้อง (Approvals)</h1>
                <p class="text-slate-500 mt-1 text-base">รายการคำขอที่รอการอนุมัติจากคุณ</p>
            </div>
            <div class="bg-amber-100 text-amber-700 px-4 py-2 rounded-xl font-bold border border-amber-200 shadow-sm flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                รออนุมัติ {{ pendingRequests.length }} รายการ
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div v-for="req in pendingRequests" :key="req.request_id" 
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition-shadow">
                
                <div class="flex items-start gap-4 grow">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg shrink-0">
                        {{ req.user ? req.user.first_name.charAt(0) : '?' }}
                    </div>
                    
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-bold text-slate-800">{{ req.user ? req.user.first_name + ' ' + req.user.last_name : 'ไม่ระบุ' }}</h3>
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                {{ req.user && req.user.position ? req.user.position.position_name : '-' }}
                            </span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-600">
                            <span class="font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                {{ req.request_type }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ req.start_date ? formatDate(req.start_date) : '' }} 
                                <span v-if="req.end_date"> - {{ formatDate(req.end_date) }}</span>
                                <span v-if="req.amount"> ({{ Number(req.amount).toLocaleString() }} บาท)</span>
                            </span>
                        </div>
                        
                        <p class="text-slate-500 mt-2 text-sm bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                            "{{ req.reason || 'ไม่มีรายละเอียดเพิ่มเติม' }}"
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 shrink-0 w-full md:w-auto mt-2 md:mt-0">
                    <button @click="updateStatus(req.request_id, 'rejected')" 
                        class="flex-1 md:flex-none px-4 py-2.5 rounded-xl border border-rose-200 text-rose-600 font-bold hover:bg-rose-50 transition-colors flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        ไม่อนุมัติ
                    </button>
                    <button @click="updateStatus(req.request_id, 'approved')" 
                        class="flex-1 md:flex-none px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-transform active:scale-95 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        อนุมัติ
                    </button>
                </div>
            </div>

            <div v-if="pendingRequests.length === 0" class="text-center py-20 bg-white rounded-2xl border border-dashed border-slate-200">
                <div class="h-16 w-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                    ✓
                </div>
                <h3 class="text-lg font-bold text-slate-400">ไม่มีรายการรออนุมัติ</h3>
                <p class="text-slate-400 text-sm">คุณจัดการงานครบหมดแล้ว!</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted, computed } from 'vue';

const requests = ref([]);

// กรองเอาเฉพาะ Pending
const pendingRequests = computed(() => {
    return requests.value.filter(r => r.status === 'pending');
});

const fetchData = async () => {
    try {
        const res = await axios.get('/api/requests');
        requests.value = res.data;
    } catch (e) { console.error(e); }
};

const updateStatus = async (id, status) => {
    // ถามยืนยันก่อน
    const confirmText = status === 'approved' ? 'ยืนยันการอนุมัติ?' : 'ยืนยันการปฏิเสธ?';
    const confirmColor = status === 'approved' ? '#059669' : '#e11d48';

    const result = await Swal.fire({
        title: confirmText,
        text: "สถานะจะถูกอัปเดตทันที",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    });

    if (result.isConfirmed) {
        try {
            await axios.put(`/api/requests/${id}`, { status });
            // ลบออกจากรายการหน้าจอนี้ทันที (เพราะมันจะไม่ pending แล้ว)
            requests.value = requests.value.filter(r => r.request_id !== id);
            
            Swal.fire({
                title: 'เรียบร้อย!',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        } catch (e) {
            Swal.fire('Error', 'เกิดข้อผิดพลาด', 'error');
        }
    }
};

const formatDate = (d) => {
    if(!d) return '';
    return new Date(d).toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: '2-digit' });
};

onMounted(() => {
    fetchData();
});
</script>