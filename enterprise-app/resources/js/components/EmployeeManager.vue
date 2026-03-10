<template>
    <div class="p-6 md:p-8 bg-[#F8FAFC] min-h-screen font-sans text-slate-800">

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">บุคลากรและตำแหน่งงาน
                </h1>
                <p class="text-sm font-medium text-slate-500 mt-1">จัดการรายชื่อพนักงาน บริหารการสับเปลี่ยน
                    และโครงสร้างตำแหน่ง</p>
            </div>
        </div>

        <div class="flex gap-2 mb-8 bg-slate-200/50 p-1.5 rounded-2xl w-fit border border-slate-200/80 shadow-inner">
            <button @click="activeTab = 'employees'"
                class="px-6 py-2.5 font-bold text-sm rounded-xl transition-all duration-300 relative outline-none flex items-center gap-2"
                :class="activeTab === 'employees' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                พนักงานและการมอบหมาย
            </button>
            <button @click="activeTab = 'master_positions'"
                class="px-6 py-2.5 font-bold text-sm rounded-xl transition-all duration-300 relative outline-none flex items-center gap-2"
                :class="activeTab === 'master_positions' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                ตั้งค่าโครงสร้างตำแหน่ง
            </button>
        </div>

        <div v-if="activeTab === 'employees'" class="animate-fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="relative w-full md:w-96">
                    <input type="text" v-model="searchQueryEmp"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200/80 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all text-sm shadow-sm font-medium placeholder:text-slate-400"
                        placeholder="ค้นหาชื่อ, นามสกุล, อีเมล..." />
                    <svg class="h-5 w-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button @click="openEmpModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg shadow-blue-600/20 flex items-center gap-2 transition-all active:scale-95 font-bold shrink-0">
                    <span class="text-xl leading-none">+</span> เพิ่มพนักงานใหม่
                </button>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left whitespace-nowrap">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                    ชื่อ-นามสกุล</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                    ตำแหน่ง / สาขา</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                    ประเภทพนักงาน</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                    ข้อมูลติดต่อ</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">
                                    จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-if="isLoadingEmp">
                                <td colspan="5" class="px-6 py-12 text-center text-blue-600 font-bold animate-pulse">
                                    กำลังโหลดข้อมูล...</td>
                            </tr>
                            <tr v-else-if="filteredEmployees.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">ไม่พบข้อมูลพนักงาน</td>
                            </tr>
                            <tr v-else v-for="emp in filteredEmployees" :key="emp.user_id"
                                class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm ring-2 ring-white shadow-sm shrink-0">
                                            {{ emp.first_name ? emp.first_name.charAt(0) : '?' }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ emp.prefix || '' }}{{
                                                emp.first_name }} {{ emp.last_name }}</div>
                                            <div class="text-xs font-medium text-slate-400 mt-0.5">ID: <span
                                                    class="font-mono">{{ emp.id_card_number || 'ไม่ได้ระบุ' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">
                                        {{ emp.position_name || '-' }}</div>
                                    <div v-if="emp.temp_position_end_date"
                                        class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded bg-amber-50 text-amber-600 text-[10px] font-bold border border-amber-200">
                                        ⏱️ รักษาการถึง: {{ formatDate(emp.temp_position_end_date) }}
                                    </div>
                                    <div v-else
                                        class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-bold border border-slate-200">
                                        🏢 {{ emp.branch_name || '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-emerald-600">{{ emp.employment_type_name || '-'
                                    }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 tracking-wider">{{
                                        emp.employee_category_name || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium space-y-1">
                                    <div class="flex items-center gap-2"><span class="text-slate-400">📧</span> {{
                                        emp.email }}</div>
                                    <div class="flex items-center gap-2"><span class="text-slate-400">📞</span> {{
                                        emp.phone_number || 'ไม่ได้ระบุ' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openAssignModal(emp)"
                                            class="h-9 w-9 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 rounded-xl flex items-center justify-center transition-colors"
                                            title="เปลี่ยนตำแหน่ง / รักษาการ">
                                            💼
                                        </button>
                                        <button @click="editEmployee(emp)"
                                            class="h-9 w-9 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 rounded-xl flex items-center justify-center transition-colors"
                                            title="แก้ไขข้อมูลส่วนตัว">
                                            ✏️
                                        </button>
                                        <button @click="deleteEmployee(emp.user_id)"
                                            class="h-9 w-9 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 rounded-xl flex items-center justify-center transition-colors"
                                            title="ลบพนักงาน">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="activeTab === 'master_positions'" class="animate-fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="relative w-full md:w-96">
                    <input type="text" v-model="searchQueryMasterPos"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200/80 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all text-sm shadow-sm font-medium placeholder:text-slate-400"
                        placeholder="ค้นหาชื่อตำแหน่งตั้งต้น..." />
                    <svg class="h-5 w-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button @click="openMasterPosModal()"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-lg flex items-center gap-2 transition-all active:scale-95 font-bold shrink-0">
                    <span class="text-xl leading-none">+</span> สร้างตำแหน่งใหม่
                </button>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 w-16 text-center">
                                    ID</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                    ชื่อตำแหน่ง</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">
                                    ระดับ (Level)</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">
                                    สถานะ</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">
                                    จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-if="isLoadingMasterPos">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold animate-pulse">
                                    กำลังโหลดข้อมูล...</td>
                            </tr>
                            <tr v-else-if="filteredMasterPositions.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">ไม่พบข้อมูลตำแหน่งงาน</td>
                            </tr>
                            <tr v-else v-for="(pos, index) in filteredMasterPositions" :key="pos.position_id"
                                class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 text-center font-mono text-xs text-slate-400">{{ index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-800">{{ pos.position_name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ pos.position_name_en || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600 border border-slate-200/60">{{
                                            pos.level_code }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span v-if="pos.is_active == 1 || pos.is_active === true"
                                        class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[11px] font-bold border border-emerald-200">เปิดใช้งาน</span>
                                    <span v-else
                                        class="px-3 py-1 bg-slate-50 text-slate-500 rounded-full text-[11px] font-bold border border-slate-200">ปิดใช้งาน</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openMasterPosModal(pos)"
                                            class="h-8 w-8 bg-slate-100 text-slate-500 hover:bg-blue-100 hover:text-blue-600 rounded-lg flex items-center justify-center transition-colors"
                                            title="แก้ไข">✏️</button>
                                        <button @click="deleteMasterPosition(pos.position_id)"
                                            class="h-8 w-8 bg-slate-100 text-slate-500 hover:bg-rose-100 hover:text-rose-600 rounded-lg flex items-center justify-center transition-colors"
                                            title="ลบ">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="isEmpModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-clear-sm transition-opacity" @click="closeEmpModal">
            </div>
            <div
                class="bg-white rounded-4xl shadow-2xl w-full max-w-3xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
                <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
                    <h3 class="text-xl font-extrabold text-slate-800">{{ isEditingEmp ? '✏️ แก้ไขข้อมูลพนักงาน' : `✨
                        เพิ่มพนักงานใหม่` }}</h3>
                    <button @click="closeEmpModal"
                        class="text-slate-400 hover:text-rose-500 font-bold text-2xl transition-colors">&times;</button>
                </div>
                <div class="p-8 overflow-y-auto custom-scrollbar bg-slate-50/30">
                    <form @submit.prevent="saveEmployee" class="space-y-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <h4
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-4 h-1 bg-blue-500 rounded-full"></span> ข้อมูลส่วนตัว
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-3">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">คำนำหน้า <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.prefix" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium">
                                        <option value="" disabled>เลือก</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">ชื่อจริง <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="formEmp.first_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
                                        placeholder="ชื่อ">
                                </div>
                                <div class="col-span-12 md:col-span-5">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">นามสกุล <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="formEmp.last_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
                                        placeholder="นามสกุล">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">เลขบัตรประชาชน</label>
                                    <input v-model="formEmp.id_card_number" type="text" maxlength="13"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
                                        placeholder="เลข 13 หลัก">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">เบอร์โทรศัพท์</label>
                                    <input v-model="formEmp.phone_number" type="tel" maxlength="10"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium"
                                        placeholder="08XXXXXXXX">
                                </div>
                            </div>
                        </div>

                        <div v-if="!isEditingEmp" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <h4
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-4 h-1 bg-amber-400 rounded-full"></span> สังกัดตำแหน่ง (ตั้งต้น)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">สาขา <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.branch_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium">
                                        <option value="">-- เลือกสาขา --</option>
                                        <option v-for="branch in branches" :key="branch.branch_id"
                                            :value="branch.branch_id">{{ branch.branch_name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">ตำแหน่ง <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.position_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium">
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        <option v-for="pos in masterPositions" :key="pos.position_id"
                                            :value="pos.position_id">{{ pos.position_name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">ประเภทการจ้างงาน <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.employment_type_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium">
                                        <option value="">-- เลือกประเภท --</option>
                                        <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{
                                            type.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">หมวดหมู่พนักงาน <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.employee_category_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-medium">
                                        <option value="">-- เลือกหมวดหมู่ --</option>
                                        <option v-for="cat in employeeCategories" :key="cat.id" :value="cat.id">{{
                                            cat.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h4
                                    class="text-xs font-bold text-blue-700 uppercase tracking-wider flex items-center gap-2">
                                    🔐 ตั้งค่าบัญชีเข้าสู่ระบบ</h4>
                                <div v-if="isEditingEmp" class="flex items-center">
                                    <input type="checkbox" id="changePass" v-model="enablePasswordEdit"
                                        class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                    <label for="changePass"
                                        class="ml-2 text-sm font-bold text-slate-600 cursor-pointer">เปลี่ยนรหัสผ่าน?</label>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">อีเมล (Username) <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="formEmp.email" type="email" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 text-sm font-medium"
                                        placeholder="employee@enterprise.com">
                                </div>
                                <div v-if="enablePasswordEdit"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-fade-in border-t border-blue-100/50 pt-4 mt-2">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">{{ isEditingEmp ?
                                            'รหัสผ่านใหม่' : 'รหัสผ่าน' }} <span class="text-rose-500">*</span></label>
                                        <input v-model="formEmp.password" type="password" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 text-sm"
                                            placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">ยืนยันรหัสผ่าน
                                            <span class="text-rose-500">*</span></label>
                                        <input v-model="formEmp.password_confirmation" type="password" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 text-sm"
                                            :class="{ 'border-rose-400 ring-2 ring-rose-100 bg-rose-50': passwordMismatch }"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end gap-3 shrink-0">
                            <button type="button" @click="closeEmpModal"
                                class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-bold text-sm transition-colors shadow-sm">ยกเลิก</button>
                            <button type="submit"
                                class="px-8 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow-md font-bold text-sm disabled:opacity-50 transition-colors"
                                :disabled="isLoadingEmp || (enablePasswordEdit && passwordMismatch)">
                                บันทึกข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div v-if="isAssignModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-clear-sm transition-opacity" @click="closeAssignModal">
            </div>
            <div
                class="bg-white w-full max-w-lg rounded-3xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">💼 มอบหมายตำแหน่งงาน</h3>
                    <button @click="closeAssignModal"
                        class="text-slate-400 hover:text-rose-500 transition-colors text-2xl font-bold">&times;</button>
                </div>
                <div class="p-8 space-y-6 overflow-y-auto custom-scrollbar">

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <label
                            class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">พนักงาน</label>
                        <div class="text-base font-bold text-slate-800">{{ formAssign.employee_name }}</div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">เลือกตำแหน่งใหม่ <span
                                class="text-rose-500">*</span></label>
                        <select v-model="formAssign.position_id"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10 outline-none cursor-pointer font-bold text-slate-700 shadow-sm">
                            <option value="" disabled>-- กรุณาเลือกตำแหน่ง --</option>
                            <option v-for="pos in allowedPositions" :key="pos.position_id" :value="pos.position_id">
                                {{ pos.position_name }} ({{ pos.level_code }})
                            </option>
                        </select>
                    </div>

                    <div class="p-5 bg-amber-50/50 rounded-2xl border border-amber-100">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" v-model="formAssign.is_temporary"
                                class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500 cursor-pointer" />
                            <span class="text-sm font-bold text-slate-700">เป็นการแต่งตั้งชั่วคราว (รักษาการแทน)</span>
                        </label>
                        <div v-if="formAssign.is_temporary" class="mt-4 animate-fade-in">
                            <label
                                class="block text-[11px] font-bold text-slate-400 uppercase mb-2">วันที่สิ้นสุดการรักษาการ
                                <span class="text-rose-500">*</span></label>
                            <input type="date" v-model="formAssign.end_date" :min="todayDate"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-100 outline-none text-slate-700 font-bold shadow-sm" />
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 flex justify-end gap-3 border-t border-slate-100 shrink-0">
                    <button @click="closeAssignModal"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm bg-white hover:bg-slate-100 transition-all">ยกเลิก</button>
                    <button @click="saveAssignPos"
                        class="px-8 py-2.5 rounded-xl bg-slate-800 text-white font-bold shadow-md text-sm hover:bg-slate-900 transition-all"
                        :disabled="isSavingAssign">
                        {{ isSavingAssign ? 'กำลังบันทึก...' : 'ยืนยันมอบหมาย' }}
                    </button>
                </div>
            </div>
        </div>

        <div v-if="isMasterPosModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-claer-sm transition-opacity"
                @click="closeMasterPosModal">
            </div>
            <div
                class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
                <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
                    <h3 class="text-xl font-extrabold text-slate-800">{{ isEditingMasterPos ? '✏️ แก้ไขโครงสร้างตำแหน่ง'
                        : '🏢 สร้างตำแหน่งใหม่' }}</h3>
                    <button @click="closeMasterPosModal"
                        class="text-slate-400 hover:text-rose-500 font-bold text-2xl transition-colors">&times;</button>
                </div>
                <div class="p-8 overflow-y-auto custom-scrollbar bg-slate-50/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (ไทย) <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" v-model="formMasterPos.position_name" placeholder="เช่น ผู้จัดการฝ่ายขาย"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all shadow-sm" />
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">ชื่อตำแหน่ง (อังกฤษ)</label>
                            <input type="text" v-model="formMasterPos.position_name_en" placeholder="e.g. Sales Manager"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all shadow-sm" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">ระดับ (Level) <span
                                    class="text-rose-500">*</span></label>
                            <select v-model="formMasterPos.level_code"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 outline-none shadow-sm">
                                <option value="" disabled>-- เลือกระดับ --</option>
                                <option value="CEO">CEO (ผู้บริหารระดับสูง)</option>
                                <option value="MGR">Manager (ผู้จัดการ)</option>
                                <option value="SUP">Supervisor (หัวหน้างาน)</option>
                                <option value="STF">Staff (พนักงาน)</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">ลำดับความสำคัญ (1=สูงสุด)</label>
                            <input type="number" v-model="formMasterPos.priority_level" min="1" max="99"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 outline-none shadow-sm" />
                        </div>

                        <div
                            class="col-span-1 md:col-span-2 mt-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm font-bold text-slate-700">สถานะการเปิดใช้งานตำแหน่งนี้</span>
                                <div class="relative">
                                    <input type="checkbox" v-model="formMasterPos.is_active" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white flex justify-end gap-3 border-t border-slate-100 shrink-0">
                    <button @click="closeMasterPosModal"
                        class="px-6 py-3 border border-slate-200 bg-white rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">ยกเลิก</button>
                    <button @click="saveMasterPosition"
                        class="px-8 py-3 bg-slate-800 text-white rounded-xl text-sm font-bold shadow-md hover:bg-slate-900 transition-colors">
                        บันทึกโครงสร้าง
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

// แท็บที่เลือก ('employees' หรือ 'master_positions')
const activeTab = ref('employees');

// =====================================
// Shared Master Data 
// =====================================
const branches = ref([]);
const masterPositions = ref([]);
const employmentTypes = ref([]);
const employeeCategories = ref([]);

// =====================================
// แท็บ 1: พนักงาน (Employees)
// =====================================
const employees = ref([]);
const searchQueryEmp = ref('');
const isLoadingEmp = ref(false);

// Modal ข้อมูลพนักงาน
const isEmpModalOpen = ref(false);
const isEditingEmp = ref(false);
const editingEmpId = ref(null);
const enablePasswordEdit = ref(false);
const formEmp = ref({
    prefix: '', first_name: '', last_name: '', id_card_number: '', phone_number: '',
    email: '', branch_id: '', position_id: '', employment_type_id: '', employee_category_id: '', password: '', password_confirmation: ''
});

// Modal มอบหมายตำแหน่ง (Assign Position)
const isAssignModalOpen = ref(false);
const isSavingAssign = ref(false);
const formAssign = reactive({
    id: null, employee_name: '', position_id: '', is_temporary: false, end_date: ''
});

// =====================================
// แท็บ 2: โครงสร้างตำแหน่ง (Master Positions)
// =====================================
const searchQueryMasterPos = ref('');
const isLoadingMasterPos = ref(false);

// Modal สร้าง/แก้ไข โครงสร้างตำแหน่ง
const isMasterPosModalOpen = ref(false);
const isEditingMasterPos = ref(false);
const formMasterPos = ref({
    position_id: null, position_name: '', position_name_en: '', level_code: '', priority_level: 99, is_active: true
});

// =====================================
// Computed Properties
// =====================================
const filteredEmployees = computed(() => {
    if (!searchQueryEmp.value) return employees.value;
    const q = searchQueryEmp.value.toLowerCase();
    return employees.value.filter(e =>
        (e.first_name || '').toLowerCase().includes(q) || (e.last_name || '').toLowerCase().includes(q) || (e.email || '').toLowerCase().includes(q)
    );
});

const filteredMasterPositions = computed(() => {
    if (!searchQueryMasterPos.value) return masterPositions.value;
    const search = searchQueryMasterPos.value.toLowerCase();
    return masterPositions.value.filter(p =>
        (p.position_name || '').toLowerCase().includes(search) || (p.position_name_en || '').toLowerCase().includes(search)
    );
});

const passwordMismatch = computed(() => enablePasswordEdit.value && formEmp.value.password && formEmp.value.password !== formEmp.value.password_confirmation);

const allowedPositions = computed(() => {
    if (!currentUser.value) return [];
    const myRole = currentUser.value?.position?.position_name?.trim().toLowerCase() || currentUser.value?.position_name?.trim().toLowerCase() || '';
    if (myRole === 'super admin' || myRole === 'system admin') return masterPositions.value;
    return masterPositions.value.filter(pos => pos.position_name.toLowerCase() !== 'super admin' && pos.position_name.toLowerCase() !== 'system admin');
});

const todayDate = computed(() => new Date().toISOString().split('T')[0]);

// =====================================
// API Fetchers
// =====================================
const fetchAllData = async () => {
    isLoadingEmp.value = true;
    isLoadingMasterPos.value = true;
    try {
        const [empRes, branchRes, posRes, masterRes] = await Promise.all([
            axios.get('/api/employees'),
            axios.get('/api/branches'),
            axios.get('/api/positions'),
            axios.get('/api/master-data')
        ]);

        employees.value = empRes.data;
        branches.value = branchRes.data;
        masterPositions.value = posRes.data.map(p => ({ ...p, is_active: Number(p.is_active) === 1 }));
        employmentTypes.value = masterRes.data.employment_types;
        employeeCategories.value = masterRes.data.employee_categories;
    } catch (e) { console.error('Fetch Error:', e); }
    finally { isLoadingEmp.value = false; isLoadingMasterPos.value = false; }
};

// =====================================
// Functions: แท็บ 1 (Employee CRUD)
// =====================================
const openEmpModal = () => {
    isEditingEmp.value = false; editingEmpId.value = null; enablePasswordEdit.value = true;
    formEmp.value = { prefix: '', first_name: '', last_name: '', id_card_number: '', phone_number: '', email: '', branch_id: '', position_id: '', employment_type_id: '', employee_category_id: '', password: '', password_confirmation: '' };
    isEmpModalOpen.value = true;
};

const editEmployee = (emp) => {
    isEditingEmp.value = true; editingEmpId.value = emp.user_id; enablePasswordEdit.value = false;
    formEmp.value = { ...emp, password: '', password_confirmation: '' };
    isEmpModalOpen.value = true;
};
const closeEmpModal = () => isEmpModalOpen.value = false;

const saveEmployee = async () => {
    isLoadingEmp.value = true;
    const payload = { ...formEmp.value };
    if (isEditingEmp.value && !enablePasswordEdit.value) { delete payload.password; delete payload.password_confirmation; }
    try {
        if (isEditingEmp.value) await axios.put(`/api/employees/${editingEmpId.value}`, payload);
        else await axios.post('/api/employees', payload);
        Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', timer: 1500, showConfirmButton: false });
        closeEmpModal(); fetchAllData();
    } catch (e) { Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error'); }
    finally { isLoadingEmp.value = false; }
};

const deleteEmployee = (id) => {
    Swal.fire({ title: 'ยืนยันการลบพนักงาน?', text: "ข้อมูลนี้จะถูกลบถาวร", icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
        .then(async (result) => {
            if (result.isConfirmed) {
                try { await axios.delete(`/api/employees/${id}`); fetchAllData(); Swal.fire({ icon: 'success', title: 'ลบสำเร็จ!', showConfirmButton: false, timer: 1500 }); }
                catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
            }
        });
};

// =====================================
// Functions: แท็บ 1 (Assign Position)
// =====================================
const openAssignModal = (emp) => {
    Object.assign(formAssign, {
        id: emp.user_id,
        employee_name: `${emp.prefix || ''}${emp.first_name} ${emp.last_name}`,
        position_id: emp.position_id || '',
        is_temporary: !!emp.temp_position_end_date,
        end_date: emp.temp_position_end_date || ''
    });
    isAssignModalOpen.value = true;
};
const closeAssignModal = () => isAssignModalOpen.value = false;

const saveAssignPos = () => {
    if (!formAssign.position_id) return Swal.fire('แจ้งเตือน', 'กรุณาเลือกตำแหน่งใหม่', 'warning');
    if (formAssign.is_temporary && !formAssign.end_date) return Swal.fire('แจ้งเตือน', 'กรุณาระบุวันที่สิ้นสุดการรักษาการ', 'warning');

    Swal.fire({ title: 'ยืนยันการมอบหมายตำแหน่ง?', text: `ต้องการเปลี่ยนตำแหน่งของ ${formAssign.employee_name} ใช่หรือไม่`, icon: 'question', showCancelButton: true, confirmButtonText: 'ยืนยัน', confirmButtonColor: '#1e293b' })
        .then(async (result) => {
            if (result.isConfirmed) {
                isSavingAssign.value = true;
                try {
                    await axios.patch(`/api/employees/${formAssign.id}/position`, {
                        position_id: formAssign.position_id,
                        is_temporary: formAssign.is_temporary,
                        end_date: formAssign.end_date,
                        is_notify_expired: 1 // ✅ บังคับเป็น 1
                    });
                    closeAssignModal();
                    Swal.fire({ icon: 'success', title: 'อัปเดตตำแหน่งสำเร็จ', timer: 1500, showConfirmButton: false });
                    fetchAllData();
                } catch (e) { Swal.fire('Error', e.response?.data?.message || 'บันทึกไม่สำเร็จ', 'error'); }
                finally { isSavingAssign.value = false; }
            }
        });
};

// =====================================
// Functions: แท็บ 2 (Master Position CRUD)
// =====================================
const openMasterPosModal = (pos = null) => {
    if (pos) {
        isEditingMasterPos.value = true;
        formMasterPos.value = { ...pos, is_active: Number(pos.is_active) === 1 };
    } else {
        isEditingMasterPos.value = false;
        formMasterPos.value = { position_id: null, position_name: '', position_name_en: '', level_code: '', priority_level: 99, is_active: true };
    }
    isMasterPosModalOpen.value = true;
};
const closeMasterPosModal = () => isMasterPosModalOpen.value = false;

const saveMasterPosition = async () => {
    if (!formMasterPos.value.position_name || !formMasterPos.value.level_code) return Swal.fire('แจ้งเตือน', 'กรอกข้อมูลสำคัญให้ครบถ้วน', 'warning');
    try {
        if (isEditingMasterPos.value) await axios.put(`/api/positions/${formMasterPos.value.position_id}`, formMasterPos.value);
        else await axios.post('/api/positions', formMasterPos.value);
        Swal.fire({ icon: 'success', title: 'บันทึกโครงสร้างสำเร็จ', showConfirmButton: false, timer: 1000 });
        closeMasterPosModal(); fetchAllData();
    } catch (e) { Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error'); }
};

const deleteMasterPosition = (id) => {
    Swal.fire({ title: 'ลบโครงสร้างตำแหน่ง?', text: "อาจกระทบพนักงานที่ใช้ตำแหน่งนี้อยู่", icon: 'warning', showCancelButton: true, confirmButtonColor: '#f43f5e', confirmButtonText: 'ลบ' })
        .then(async (result) => {
            if (result.isConfirmed) {
                try { await axios.delete(`/api/positions/${id}`); Swal.fire({ icon: 'success', title: 'ลบสำเร็จ', showConfirmButton: false, timer: 1000 }); fetchAllData(); }
                catch (e) { Swal.fire('Error', 'ไม่สามารถลบได้ (อาจมีคนใช้อยู่)', 'error'); }
            }
        });
};

// Helpers
const formatDate = (d) => { if (!d) return ''; return new Date(d).toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' }); };

onMounted(() => {
    const userStr = localStorage.getItem('user');
    if (userStr) { currentUser.value = JSON.parse(userStr); fetchAllData(); }
    else { router.push('/login'); }
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
        transform: translateY(-5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.97);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-zoom-in {
    animation: zoomIn 0.2s ease-out;
}
</style>