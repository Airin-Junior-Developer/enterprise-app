<template>
    <div class="emp-container">
        <div class="header-section">
            <h3>จัดการข้อมูลพนักงาน (Employee Management)</h3>
            <button v-if="!isFormVisible" @click="openForm" class="btn-add">
                + เพิ่มพนักงานใหม่
            </button>
        </div>

        <div v-if="isFormVisible" class="form-box">
            <h4>{{ isEditMode ? 'แก้ไขข้อมูลพนักงาน' : 'ลงทะเบียนพนักงานใหม่' }}</h4>

            <div class="form-grid">
                <div class="form-group">
                    <label>คำนำหน้า</label>
                    <select v-model="form.prefix" class="input-field">
                        <option value="">เลือก</option>
                        <option>นาย</option>
                        <option>นาง</option>
                        <option>นางสาว</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>ชื่อจริง</label>
                    <input v-model="form.firstname" class="input-field" placeholder="ระบุชื่อจริง">
                </div>
                <div class="form-group">
                    <label>นามสกุล</label>
                    <input v-model="form.lastname" class="input-field" placeholder="ระบุนามสกุล">
                </div>

                <div class="form-group">
                    <label>ตำแหน่ง (Position)</label>
                    <select v-model="form.position" class="input-field">
                        <option value="">-- เลือกตำแหน่ง --</option>
                        <option v-for="pos in positionList" :key="pos.id" :value="pos.name">
                            {{ pos.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>สาขา (Branch)</label>
                    <select v-model="form.branch_id" class="input-field">
                        <option value="">-- เลือกสาขา --</option>
                        <option v-for="branch in branchList" :key="branch.id" :value="branch.id">
                            {{ branch.name }} ({{ branch.code }})
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>อีเมล (Login)</label>
                    <input type="email" v-model="form.email" class="input-field" placeholder="employee@company.com">
                </div>

                <div class="form-group">
                    <label>วันที่เริ่มงาน</label>
                    <input type="date" v-model="form.start_date" class="input-field">
                </div>
                <div class="form-group">
                    <label>เบอร์โทรศัพท์</label>
                    <input v-model="form.phone" class="input-field" placeholder="08x-xxx-xxxx">
                </div>
            </div>

            <div class="form-actions">
                <button @click="saveEmployee" class="btn-save">บันทึกข้อมูล</button>
                <button @click="closeForm" class="btn-cancel">ยกเลิก</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="emp-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ตำแหน่ง</th>
                        <th>สาขา</th>
                        <th>วันที่เริ่มงาน</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="emp in employees" :key="emp.id">
                        <td>{{ emp.id }}</td>
                        <td>
                            <div class="user-cell">
                                <strong>{{ emp.profile?.prefix }} {{ emp.profile?.firstname }} {{ emp.profile?.lastname
                                    }}</strong>
                                <span class="sub-text">{{ emp.email }}</span>
                            </div>
                        </td>
                        <td><span class="badge-pos">{{ emp.profile?.position }}</span></td>
                        <td>
                            <span class="branch-badge">{{ emp.branch?.name || '-' }}</span>
                        </td>
                        <td>{{ formatDate(emp.profile?.start_date) }}</td>
                        <td>
                            <button @click="editEmployee(emp)" class="btn-sm btn-edit">แก้ไข</button>
                            <button @click="deleteEmployee(emp.id)" class="btn-sm btn-delete">ลบ</button>
                        </td>
                    </tr>
                    <tr v-if="employees.length === 0">
                        <td colspan="6" class="empty-row">ไม่พบข้อมูลพนักงาน</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            employees: [],
            branchList: [],
            positionList: [], // 🔥 ตัวแปรใหม่เก็บรายชื่อตำแหน่ง
            isFormVisible: false,
            isEditMode: false,
            editId: null,
            form: {
                prefix: '', firstname: '', lastname: '', email: '',
                position: '', branch_id: '',
                start_date: '', phone: ''
            }
        }
    },
    mounted() {
        this.fetchEmployees();
        this.fetchBranches();
        this.fetchPositions(); // 🔥 เรียกดึงข้อมูลตำแหน่งเมื่อเริ่ม
    },
    methods: {
        async fetchEmployees() {
            try {
                const res = await fetch('/api/employees');
                const data = await res.json();
                this.employees = data.data;
            } catch (error) { console.error(error); }
        },
        async fetchBranches() {
            try {
                const res = await fetch('/api/branches');
                const data = await res.json();
                this.branchList = data.data || data;
            } catch (error) { console.error(error); }
        },
        // 🔥 ฟังก์ชันใหม่: ดึงข้อมูลตำแหน่ง
        async fetchPositions() {
            try {
                const res = await fetch('/api/positions');
                const data = await res.json();
                this.positionList = data.data || [];
            } catch (error) { console.error(error); }
        },
        openForm() {
            this.isFormVisible = true;
            this.isEditMode = false;
            this.resetForm();
        },
        closeForm() {
            this.isFormVisible = false;
        },
        resetForm() {
            this.form = {
                prefix: '', firstname: '', lastname: '', email: '',
                position: '', branch_id: '',
                start_date: '', phone: ''
            };
        },
        editEmployee(emp) {
            this.isFormVisible = true;
            this.isEditMode = true;
            this.editId = emp.id;

            // Map ข้อมูลเข้าฟอร์ม
            this.form = {
                prefix: emp.profile?.prefix,
                firstname: emp.profile?.firstname,
                lastname: emp.profile?.lastname,
                email: emp.email,
                position: emp.profile?.position,
                branch_id: emp.branch_id,
                start_date: emp.profile?.start_date,
                phone: emp.profile?.phone
            };
        },
        async saveEmployee() {
            if (!this.form.firstname || !this.form.position || !this.form.branch_id) {
                alert('กรุณากรอกข้อมูลให้ครบถ้วน (ชื่อ, ตำแหน่ง, สาขา)'); return;
            }

            const url = this.isEditMode ? `/api/employees/${this.editId}` : '/api/employees';
            const method = this.isEditMode ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.form)
                });

                const result = await res.json();

                if (res.ok) {
                    this.closeForm();
                    this.fetchEmployees();
                    alert('บันทึกสำเร็จ');
                } else {
                    alert('เกิดข้อผิดพลาด: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error(error);
                alert('Server Error');
            }
        },
        async deleteEmployee(id) {
            if (!confirm('ยืนยันลบพนักงานคนนี้?')) return;
            await fetch(`/api/employees/${id}`, { method: 'DELETE' });
            this.fetchEmployees();
        },
        formatDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('th-TH');
        }
    }
}
</script>

<style scoped>
.emp-container {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-section h3 {
    margin: 0;
    color: #1e293b;
    font-size: 18px;
    font-weight: 600;
}

.btn-add {
    background: #0061f2;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
}

.btn-add:hover {
    background: #0052cc;
}

.form-box {
    background: #f8fafc;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 25px;
    border: 1px solid #e2e8f0;
}

.form-box h4 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #334155;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 10px;
    font-size: 16px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
}

.input-field {
    padding: 9px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    outline: none;
    background: white;
    font-size: 14px;
}

.input-field:focus {
    border-color: #0061f2;
}

.form-actions {
    margin-top: 25px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-save {
    background: #16a34a;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-cancel {
    background: white;
    color: #64748b;
    border: 1px solid #cbd5e1;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
}

.table-responsive {
    overflow-x: auto;
}

.emp-table {
    width: 100%;
    border-collapse: collapse;
}

.emp-table th {
    background: #f8fafc;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13px;
}

.emp-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 14px;
    vertical-align: middle;
}

.user-cell {
    display: flex;
    flex-direction: column;
    line-height: 1.4;
}

.sub-text {
    font-size: 12px;
    color: #94a3b8;
}

.branch-badge {
    background: #eff6ff;
    color: #0061f2;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #bfdbfe;
}

.badge-pos {
    background: #fdf2f8;
    color: #db2777;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #fce7f3;
}

.empty-row {
    text-align: center;
    padding: 30px;
    color: #94a3b8;
}

.btn-sm {
    border: none;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    margin-right: 5px;
}

.btn-edit {
    background: #eff6ff;
    color: #0061f2;
    border: 1px solid #bfdbfe;
}

.btn-delete {
    background: #fef2f2;
    color: #ef4444;
    border: 1px solid #fecaca;
}
</style>