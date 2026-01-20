<template>
    <div class="branch-container">
        <div class="header-section">
            <h3>จัดการข้อมูลสาขา (Branch Management)</h3>
            <button v-if="!isFormVisible" @click="openForm" class="btn-add">
                + เพิ่มสาขาใหม่
            </button>
        </div>

        <div v-if="isFormVisible" class="form-box">
            <h4>{{ isEditMode ? 'แก้ไขข้อมูลสาขา' : 'เพิ่มสาขาใหม่' }}</h4>
            <div class="input-group">
                <input v-model="form.code" placeholder="รหัสสาขา (เช่น HQ01)" class="input-field">
                <input v-model="form.name" placeholder="ชื่อสาขา (เช่น สำนักงานใหญ่)" class="input-field">
                <button @click="saveBranch" class="btn-save">บันทึก</button>
                <button @click="closeForm" class="btn-cancel">ยกเลิก</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="branch-table">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="20%">รหัสสาขา</th>
                        <th>ชื่อสาขา</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="branch in branches" :key="branch.id">
                        <td>{{ branch.id }}</td>
                        <td><span class="badge">{{ branch.code }}</span></td>
                        <td>{{ branch.name }}</td>
                        <td>
                            <button @click="editBranch(branch)" class="btn-sm btn-edit">แก้ไข</button>
                            <button @click="deleteBranch(branch.id)" class="btn-sm btn-delete">ลบ</button>
                        </td>
                    </tr>
                    <tr v-if="branches.length === 0">
                        <td colspan="4" class="empty-row">ยังไม่มีข้อมูลสาขา</td>
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
            branches: [],
            isFormVisible: false,
            isEditMode: false,
            editId: null,
            form: { code: '', name: '' }
        }
    },
    mounted() {
        this.fetchBranches();
    },
    methods: {
        async fetchBranches() {
            try {
                const res = await fetch('/api/branches');
                const data = await res.json();
                this.branches = data.data;
            } catch (error) { console.error('Error:', error); }
        },
        openForm() {
            this.isFormVisible = true;
            this.form = { code: '', name: '' };
            this.isEditMode = false;
        },
        closeForm() {
            this.isFormVisible = false;
        },
        editBranch(branch) {
            this.isFormVisible = true;
            this.isEditMode = true;
            this.editId = branch.id;
            this.form = { code: branch.code, name: branch.name };
        },
        async saveBranch() {
            if (!this.form.name || !this.form.code) {
                alert('กรุณากรอกข้อมูลให้ครบ'); return;
            }
            const url = this.isEditMode ? `/api/branches/${this.editId}` : '/api/branches';
            const method = this.isEditMode ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.form)
                });

                if (res.ok) {
                    this.closeForm();
                    this.fetchBranches();
                }
            } catch (error) { alert('เกิดข้อผิดพลาด'); }
        },
        async deleteBranch(id) {
            if (!confirm('ยืนยันลบสาขานี้?')) return;
            await fetch(`/api/branches/${id}`, { method: 'DELETE' });
            this.fetchBranches();
        }
    }
}
</script>

<style scoped>
.branch-container {
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
    font-size: 14px;
    font-weight: 500;
}

.btn-add:hover {
    background: #0052cc;
}

.form-box {
    background: #f8fafc;
    padding: 20px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
}

.form-box h4 {
    margin: 0 0 15px 0;
    color: #334155;
}

.input-group {
    display: flex;
    gap: 10px;
}

.input-field {
    padding: 8px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    flex: 1;
    outline: none;
}

.input-field:focus {
    border-color: #0061f2;
}

.btn-save {
    background: #16a34a;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-cancel {
    background: #64748b;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

.table-responsive {
    overflow-x: auto;
}

.branch-table {
    width: 100%;
    border-collapse: collapse;
}

.branch-table th {
    background: #f1f5f9;
    padding: 12px;
    text-align: left;
    color: #475569;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
}

.branch-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 14px;
}

.badge {
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    border: 1px solid #cbd5e1;
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

.btn-edit:hover {
    background: #dbeafe;
}

.btn-delete {
    background: #fef2f2;
    color: #ef4444;
    border: 1px solid #fecaca;
}

.btn-delete:hover {
    background: #fee2e2;
}

.empty-row {
    text-align: center;
    color: #94a3b8;
    padding: 20px;
}
</style>