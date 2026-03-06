<template>
    <div class="p-6 md:p-8 bg-slate-50 min-h-screen font-sans text-slate-900">

        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">บุคลากรและตำแหน่งงาน</h1>
            <p class="text-slate-500 mt-1 text-base">จัดการบริหารตำแหน่งงานภายในองค์กร</p>
        </div>

        <div class="border-b border-slate-200 mb-6 flex gap-6 px-2 overflow-x-auto">
            <button @click="activeTab = 'employees'"
                class="pb-4 font-bold text-sm transition-all relative outline-none whitespace-nowrap"
                :class="activeTab === 'employees' ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600'">
                รายชื่อพนักงาน
                <div v-if="activeTab === 'employees'"
                    class="absolute bottom-0 left-0 w-full h-1 bg-blue-600 rounded-t-md"></div>
            </button>
            <button @click="activeTab = 'positions'"
                class="pb-4 font-bold text-sm transition-all relative outline-none whitespace-nowrap"
                :class="activeTab === 'positions' ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600'">
                จัดการรายชื่อและตำแหน่งงาน
                <div v-if="activeTab === 'positions'"
                    class="absolute bottom-0 left-0 w-full h-1 bg-blue-600 rounded-t-md"></div>
            </button>
        </div>

        <div v-if="activeTab === 'employees'" class="animate-fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 w-full max-w-sm">
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" v-model="searchQueryEmp"
                            class="block w-full pl-10 pr-4 py-2 border-none rounded-xl bg-transparent focus:ring-0 text-slate-700 placeholder-slate-400 focus:outline-none"
                            placeholder="ค้นหาชื่อ, อีเมล..." />
                    </div>
                </div>
                <button @click="openEmpModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 flex items-center gap-2 transition-all transform hover:scale-105 active:scale-95 font-bold shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>เพิ่มพนักงานใหม่</span>
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    ชื่อ-นามสกุล</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    ตำแหน่ง / สาขา</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    ประเภทพนักงาน</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    ข้อมูลติดต่อ</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
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
                                class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg mr-3 shrink-0">
                                            {{ emp.first_name ? emp.first_name.charAt(0) : '?' }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ emp.prefix ? emp.prefix :
                                                '' }}{{ emp.first_name }} {{ emp.last_name }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">ID: <span class="font-mono">{{
                                                emp.id_card_number || 'ไม่ได้ระบุ' }}</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-700">{{ emp.position_name || '-' }}
                                    </div>
                                    <div
                                        class="text-[11px] text-slate-500 bg-slate-100 inline-block px-2 py-0.5 rounded mt-1">
                                        {{ emp.branch_name || '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-blue-600">{{ emp.employment_type_name || '-' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">{{
                                        emp.employee_category_name || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <div class="flex items-center gap-2"><span>📧</span> {{ emp.email }}</div>
                                    <div class="flex items-center gap-2 mt-1"><span>📞</span> {{ emp.phone_number ||
                                        'ไม่ได้ระบุ' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button @click="editEmployee(emp)"
                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteEmployee(emp.user_id)"
                                        class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="activeTab === 'positions'" class="animate-fade-in">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                        💼</div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">พนักงานทั้งหมด</p>
                        <h3 class="text-2xl font-black text-slate-800">{{ managePositionsList.length }}</h3>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm mb-6 flex flex-col md:flex-row gap-4">
                <div class="relative flex-grow">
                    <input type="text" v-model="searchQueryPos"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-400 outline-none transition-all text-sm font-medium"
                        placeholder="ค้นหาชื่อพนักงาน/ตำแหน่ง (ไทย/อังกฤษ)..." />
                    <span class="absolute left-4 top-3.5 text-slate-400">🔍</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse whitespace-nowrap">
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
                            <tr v-if="isLoadingPos">
                                <td colspan="8" class="px-6 py-12 text-center text-blue-600 font-bold animate-pulse">
                                    กำลังโหลดข้อมูล...</td>
                            </tr>
                            <tr v-else-if="filteredManagePositions.length === 0">
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400">ไม่พบข้อมูลที่ค้นหา</td>
                            </tr>
                            <tr v-else v-for="(item, index) in filteredManagePositions" :key="item.id"
                                class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-8 py-6 text-center text-slate-300 font-bold">{{ index + 1 }}</td>
                                <td class="px-6 py-6">
                                    <div class="font-bold text-slate-700">{{ item.employee_name }}</div>
                                </td>
                                <td class="px-6 py-6 text-slate-600 font-medium">{{ item.branch }}</td>
                                <td class="px-6 py-6">
                                    <div class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{
                                        item.name }}</div>
                                    <div class="text-[10px] text-slate-400 italic mb-1">{{ item.name_en }}</div>
                                    <div v-if="item.temp_position_end_date"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-600 text-[10px] font-bold mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        รักษาการถึง: {{ formatDate(item.temp_position_end_date) }}
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-slate-500 text-xs">{{ item.email }}</td>
                                <td class="px-6 py-6 text-center text-slate-600 font-medium">{{ item.phone || '-' }}
                                </td>
                                <td class="px-6 py-6 text-center text-slate-500">{{ item.start_date || '-' }}</td>
                                <td class="px-6 py-6 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button @click="openPosModal(item)"
                                            class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center"
                                            title="แก้ไขตำแหน่ง">✏️</button>
                                        <button @click="deleteManagePos(item)"
                                            class="h-9 w-9 rounded-xl bg-slate-100 hover:bg-rose-500 hover:text-white transition-colors flex items-center justify-center"
                                            title="ลบข้อมูลพนักงาน">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="isEmpModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeEmpModal"></div>
            <div
                class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
                <div
                    class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <h3 class="text-lg font-bold text-slate-800">{{ isEditingEmp ? 'แก้ไขข้อมูลพนักงาน' :
                        'เพิ่มพนักงานใหม่' }}</h3>
                    <button @click="closeEmpModal"
                        class="text-slate-400 hover:text-rose-500 font-bold text-xl transition-colors">&times;</button>
                </div>
                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <form @submit.prevent="saveEmployee" class="space-y-6">
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">ข้อมูลส่วนตัว
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-3">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">คำนำหน้า <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.prefix" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="" disabled>เลือก</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">ชื่อจริง <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="formEmp.first_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="สมชาย">
                                </div>
                                <div class="col-span-12 md:col-span-5">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">นามสกุล <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="formEmp.last_name" type="text" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="ใจดี">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">เลขบัตรประชาชน</label>
                                    <input v-model="formEmp.id_card_number" type="text" maxlength="13"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="1234567890123">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-sm font-bold text-slate-700 mb-1">เบอร์โทรศัพท์</label>
                                    <input v-model="formEmp.phone_number" type="tel" maxlength="10"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="0891234567">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-t border-slate-100 pt-4">
                                ข้อมูลการจ้างงานและสังกัด</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">ประเภทการจ้างงาน <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.employment_type_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="">-- เลือกประเภทการจ้าง --</option>
                                        <option v-for="type in employmentTypes" :key="type.id" :value="type.id">{{
                                            type.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">หมวดหมู่พนักงาน <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.employee_category_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="">-- เลือกหมวดหมู่ --</option>
                                        <option v-for="cat in employeeCategories" :key="cat.id" :value="cat.id">{{
                                            cat.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">สาขา <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.branch_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="">-- เลือกสาขา --</option>
                                        <option v-for="branch in branches" :key="branch.branch_id"
                                            :value="branch.branch_id">{{ branch.branch_name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">ตำแหน่ง <span
                                            class="text-rose-500">*</span></label>
                                    <select v-model="formEmp.position_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="">-- เลือกตำแหน่ง --</option>
                                        <option v-for="pos in masterPositions" :key="pos.position_id"
                                            :value="pos.position_id">{{ pos.position_name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wider">
                                    ตั้งค่าบัญชีเข้าสู่ระบบ</h4>
                                <div v-if="isEditingEmp" class="flex items-center">
                                    <input type="checkbox" id="changePass" v-model="enablePasswordEdit"
                                        class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                    <label for="changePass"
                                        class="ml-2 text-sm font-bold text-slate-600 cursor-pointer">เปลี่ยนรหัสผ่าน?</label>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">อีเมล (Username) <span
                                            class="text-rose-500">*</span></label>
                                    <input v-model="formEmp.email" type="email" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="employee@enterprise.com">
                                </div>
                                <div v-if="enablePasswordEdit"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-fade-in">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">{{ isEditingEmp ?
                                            'รหัสผ่านใหม่' : 'รหัสผ่าน' }} <span class="text-rose-500">*</span></label>
                                        <input v-model="formEmp.password" type="password" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1">ยืนยันรหัสผ่าน <span
                                                class="text-rose-500">*</span></label>
                                        <input v-model="formEmp.password_confirmation" type="password" required
                                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                            :class="{ 'border-rose-300 ring-2 ring-rose-100': passwordMismatch }"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end gap-3 shrink-0">
                            <button type="button" @click="closeEmpModal"
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-sm transition-colors">ยกเลิก</button>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow-sm font-bold text-sm disabled:opacity-50 transition-colors"
                                :disabled="isLoadingEmp || (enablePasswordEdit && passwordMismatch)">
                                {{ isLoadingEmp ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div v-if="isPosModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closePosModal"></div>
            <div
                class="bg-white w-full max-w-lg rounded-3xl shadow-2xl z-10 overflow-hidden flex flex-col max-h-[90vh] animate-zoom-in">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <h3 class="text-lg font-bold text-blue-600">แก้ไขตำแหน่งงาน</h3>
                    <button @click="closePosModal"
                        class="text-slate-400 hover:text-rose-500 transition-colors text-xl font-bold">&times;</button>
                </div>
                <div class="p-8 space-y-6 overflow-y-auto custom-scrollbar">
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">พนักงาน</label>
                        <input v-model="formPos.employee_name" type="text" disabled
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-500 cursor-not-allowed outline-none" />
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">เลือกตำแหน่งใหม่
                            <span class="text-rose-500">*</span></label>
                        <select v-model="formPos.position_id"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-slate-700">
                            <option value="" disabled>-- กรุณาเลือกตำแหน่ง --</option>
                            <option v-for="pos in allowedPositions" :key="pos.position_id" :value="pos.position_id">
                                {{ pos.position_name }} ({{ pos.level_code }})
                            </option>
                        </select>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" v-model="formPos.is_temporary"
                                class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" />
                            <span class="text-sm font-bold text-slate-700">เป็นการแต่งตั้งชั่วคราว (รักษาการแทน)</span>
                        </label>
                        <div v-if="formPos.is_temporary" class="mt-4 animate-fade-in">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1 tracking-widest">วันที่สิ้นสุดการรักษาการ
                                <span class="text-rose-500">*</span></label>
                            <input type="date" v-model="formPos.end_date" :min="todayDate"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none text-slate-700 font-medium" />
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 flex justify-end gap-3 border-t border-slate-100 shrink-0">
                    <button @click="closePosModal"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-500 font-bold text-sm hover:bg-white transition-all">ยกเลิก</button>
                    <button @click="saveManagePos"
                        class="px-8 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-sm text-sm hover:bg-blue-700 transition-all flex items-center gap-2"
                        :disabled="isSavingPos">
                        {{ isSavingPos ? 'กำลังบันทึก...' : 'บันทึกข้อมูล' }}
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

// ตัวแปรจัดการเมนูแท็บ
const activeTab = ref('employees'); // ค่าเริ่มต้น: 'employees' หรือ 'positions'

// =====================================
// ข้อมูล Master Data (ใช้ร่วมกันทั้ง 2 แท็บ)
// =====================================
const branches = ref([]);
const masterPositions = ref([]);
const employmentTypes = ref([]);
const employeeCategories = ref([]);

// =====================================
// State สำหรับ แท็บที่ 1: รายชื่อพนักงาน
// =====================================
const employees = ref([]);
const searchQueryEmp = ref('');
const isEmpModalOpen = ref(false);
const isEditingEmp = ref(false);
const editingEmpId = ref(null);
const isLoadingEmp = ref(false);
const enablePasswordEdit = ref(false);

const formEmp = ref({
    prefix: '', first_name: '', last_name: '', id_card_number: '', phone_number: '',
    email: '', branch_id: '', position_id: '',
    employment_type_id: '', employee_category_id: '',
    password: '', password_confirmation: ''
});

// =====================================
// State สำหรับ แท็บที่ 2: จัดการตำแหน่งงาน
// =====================================
const managePositionsList = ref([]);
const searchQueryPos = ref('');
const isPosModalOpen = ref(false);
const isSavingPos = ref(false);
const isLoadingPos = ref(true);

const formPos = reactive({
    id: null,
    employee_name: '',
    position_id: '',
    is_temporary: false,
    end_date: ''
});

// =====================================
// Computed Properties
// =====================================

// แท็บ 1
const filteredEmployees = computed(() => {
    if (!searchQueryEmp.value) return employees.value;
    const q = searchQueryEmp.value.toLowerCase();
    return employees.value.filter(e =>
        (e.first_name || '').toLowerCase().includes(q) ||
        (e.last_name || '').toLowerCase().includes(q) ||
        (e.email || '').toLowerCase().includes(q)
    );
});

const passwordMismatch = computed(() => {
    if (!enablePasswordEdit.value || !formEmp.value.password) return false;
    return formEmp.value.password !== formEmp.value.password_confirmation;
});

// แท็บ 2
const filteredManagePositions = computed(() => {
    if (!searchQueryPos.value) return managePositionsList.value;
    const search = searchQueryPos.value.toLowerCase();
    return managePositionsList.value.filter(p =>
        (p.employee_name && p.employee_name.toLowerCase().includes(search)) ||
        (p.name && p.name.toLowerCase().includes(search)) ||
        (p.name_en && p.name_en.toLowerCase().includes(search))
    );
});

const allowedPositions = computed(() => {
    if (!currentUser.value) return [];
    const myRole = currentUser.value?.position?.position_name?.trim().toLowerCase()
        || currentUser.value?.position_name?.trim().toLowerCase()
        || '';

    if (myRole === 'super admin' || myRole === 'system admin') {
        return masterPositions.value;
    }
    if (myRole === 'hr manager') {
        return masterPositions.value.filter(pos => {
            const posName = pos.position_name.toLowerCase();
            return posName !== 'super admin' && posName !== 'system admin';
        });
    }
    return masterPositions.value;
});

const todayDate = computed(() => {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
});

// =====================================
// API Fetchers
// =====================================
const fetchAllData = async () => {
    isLoadingEmp.value = true;
    isLoadingPos.value = true;
    try {
        const [empRes, branchRes, posRes, masterRes, managePosRes] = await Promise.all([
            axios.get('/api/employees'),
            axios.get('/api/branches'),
            axios.get('/api/positions'),
            axios.get('/api/master-data'),
            axios.get('/api/manage-positions-list')
        ]);

        employees.value = empRes.data;
        branches.value = branchRes.data;
        masterPositions.value = posRes.data;
        employmentTypes.value = masterRes.data.employment_types;
        employeeCategories.value = masterRes.data.employee_categories;
        managePositionsList.value = managePosRes.data;

    } catch (e) {
        console.error('Fetch Data Error:', e);
    } finally {
        isLoadingEmp.value = false;
        isLoadingPos.value = false;
    }
};

const refreshEmployeeData = async () => {
    try {
        const res = await axios.get('/api/employees');
        employees.value = res.data;
    } catch (e) { console.error(e); }
};

const refreshManagePosData = async () => {
    try {
        const res = await axios.get('/api/manage-positions-list');
        managePositionsList.value = res.data;
    } catch (e) { console.error(e); }
};

// =====================================
// Methods แท็บ 1: พนักงาน
// =====================================
const openEmpModal = () => {
    isEditingEmp.value = false;
    editingEmpId.value = null;
    enablePasswordEdit.value = true;
    formEmp.value = { prefix: '', first_name: '', last_name: '', id_card_number: '', phone_number: '', email: '', branch_id: '', position_id: '', employment_type_id: '', employee_category_id: '', password: '', password_confirmation: '' };
    isEmpModalOpen.value = true;
};

const editEmployee = (emp) => {
    isEditingEmp.value = true;
    editingEmpId.value = emp.user_id;
    enablePasswordEdit.value = false;
    formEmp.value = { ...emp, password: '', password_confirmation: '' };
    isEmpModalOpen.value = true;
};

const closeEmpModal = () => isEmpModalOpen.value = false;

const saveEmployee = async () => {
    isLoadingEmp.value = true;
    const payload = { ...formEmp.value };
    if (isEditingEmp.value && !enablePasswordEdit.value) {
        delete payload.password;
        delete payload.password_confirmation;
    }

    try {
        if (isEditingEmp.value) {
            await axios.put(`/api/employees/${editingEmpId.value}`, payload);
        } else {
            await axios.post('/api/employees', payload);
        }
        Swal.fire({ icon: 'success', title: 'สำเร็จ', timer: 1500, showConfirmButton: false });
        closeEmpModal();
        refreshEmployeeData();
        refreshManagePosData(); // อัปเดตตารางหน้า 2 ด้วย
    } catch (e) {
        Swal.fire('Error', e.response?.data?.message || 'เกิดข้อผิดพลาด', 'error');
    } finally { isLoadingEmp.value = false; }
};

const deleteEmployee = (id) => {
    Swal.fire({ title: 'ยืนยันการลบ?', text: "ข้อมูลจะถูกลบถาวร", icon: 'warning', showCancelButton: true, confirmButtonText: 'ลบ', confirmButtonColor: '#f43f5e' })
        .then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.delete(`/api/employees/${id}`);
                    refreshEmployeeData();
                    refreshManagePosData();
                    Swal.fire({ icon: 'success', title: 'ลบสำเร็จ!', showConfirmButton: false, timer: 1500 });
                } catch (e) { Swal.fire('Error', 'ลบไม่สำเร็จ', 'error'); }
            }
        });
};

// =====================================
// Methods แท็บ 2: ตำแหน่งงาน
// =====================================
const openPosModal = (item) => {
    const matchedPos = masterPositions.value.find(p => p.position_name === item.name);
    Object.assign(formPos, {
        id: item.id,
        employee_name: item.employee_name,
        position_id: matchedPos ? matchedPos.position_id : '',
        is_temporary: false,
        end_date: ''
    });
    isPosModalOpen.value = true;
};

const closePosModal = () => isPosModalOpen.value = false;

const saveManagePos = () => {
    if (!formPos.position_id) return Swal.fire('แจ้งเตือน', 'กรุณาเลือกตำแหน่งใหม่', 'warning');
    if (formPos.is_temporary && !formPos.end_date) return Swal.fire('แจ้งเตือน', 'กรุณาระบุวันที่สิ้นสุดการรักษาการ', 'warning');

    Swal.fire({
        title: 'ยืนยันการบันทึก?',
        text: `ต้องการเปลี่ยนตำแหน่งของ ${formPos.employee_name} ใช่หรือไม่`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#2563eb'
    }).then(async (result) => {
        if (result.isConfirmed) {
            isSavingPos.value = true;
            try {
                await axios.patch(`/api/employees/${formPos.id}/position`, {
                    position_id: formPos.position_id,
                    is_temporary: formPos.is_temporary,
                    end_date: formPos.end_date
                });

                closePosModal();
                Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ', text: 'อัปเดตตำแหน่งเรียบร้อยแล้ว', timer: 1500, showConfirmButton: false });
                refreshManagePosData();
                refreshEmployeeData(); // อัปเดตตารางหน้า 1 ด้วย
            } catch (error) {
                Swal.fire('Error', error.response?.data?.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            } finally {
                isSavingPos.value = false;
            }
        }
    });
};

const deleteManagePos = (item) => {
    deleteEmployee(item.id); // ใช้ฟังก์ชันลบของแท็บแรกได้เลย เพราะลบพนักงานเหมือนกัน
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' });
};

// =====================================
// On Mounted
// =====================================
onMounted(() => {
    const userStr = localStorage.getItem('user');
    if (userStr) {
        currentUser.value = JSON.parse(userStr);
        fetchAllData();
    } else {
        router.push('/login');
    }
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