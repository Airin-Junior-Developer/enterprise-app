<template>
    <div class="content-container">
        <div class="header-section">
            <h3>จัดการตำแหน่งงาน (Position Management)</h3>
            <button v-if="!isFormVisible" @click="openForm" class="btn-add">
                + เพิ่มตำแหน่งใหม่
            </button>
        </div>

        <div v-if="isFormVisible" class="form-box">
            <h4>{{ isEditMode ? 'แก้ไขตำแหน่ง' : 'เพิ่มตำแหน่งใหม่' }}</h4>
            <div class="input-group">
                <input v-model="form.name" placeholder="ระบุชื่อตำแหน่ง (เช่น Software Engineer)" class="input-field">
                <button @click="savePosition" class="btn-save">บันทึก</button>
                <button @click="closeForm" class="btn-cancel">ยกเลิก</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th>ชื่อตำแหน่ง</th>
                        <th width="20%">วันที่สร้าง</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pos in positions" :key="pos.id">
                        <td>{{ pos.id }}</td>
                        <td><span class="badge">{{ pos.name }}</span></td>
                        <td>{{ formatDate(pos.created_at) }}</td>
                        <td>
                            <button @click="editPosition(pos)" class="btn-sm btn-edit">แก้ไข</button>
                            <button @click="deletePosition(pos.id)" class="btn-sm btn-delete">ลบ</button>
                        </td>
                    </tr>
                    <tr v-if="positions.length === 0">
                        <td colspan="4" class="empty-row">ยังไม่มีข้อมูลตำแหน่งงาน</td>
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
            positions: [],
            isFormVisible: false,
            isEditMode: false,
            editId: null,
            form: { name: '' }
        }
    },
    mounted() {
        this.fetchPositions();
    },
    methods: {
        async fetchPositions() {
            try {
                const res = await fetch('/api/positions');
                const data = await res.json();
                this.positions = data.data;
            } catch (error) { console.error(error); }
        },
        openForm() {
            this.isFormVisible = true;
            this.form = { name: '' };
            this.isEditMode = false;
        },
        closeForm() {
            this.isFormVisible = false;
        },
        editPosition(pos) {
            this.isFormVisible = true;
            this.isEditMode = true;
            this.editId = pos.id;
            this.form = { name: pos.name };
        },
        async savePosition() {
            if (!this.form.name) return alert('กรุณาระบุชื่อตำแหน่ง');

            const url = this.isEditMode ? `/api/positions/${this.editId}` : '/api/positions';
            const method = this.isEditMode ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.form)
                });

                if (res.ok) {
                    this.closeForm();
                    this.fetchPositions();
                    alert('บันทึกสำเร็จ');
                }
            } catch (error) { alert('เกิดข้อผิดพลาด'); }
        },
        async deletePosition(id) {
            if (!confirm('ยืนยันลบตำแหน่งนี้?')) return;
            await fetch(`/api/positions/${id}`, { method: 'DELETE' });
            this.fetchPositions();
        },
        formatDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('th-TH');
        }
    }
}
</script>

<style scoped>
.content-container {
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

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f1f5f9;
    padding: 12px;
    text-align: left;
    color: #475569;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
}

.data-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 14px;
}

.badge {
    background: #f0fdf4;
    color: #15803d;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid #dcfce7;
}

.empty-row {
    text-align: center;
    color: #94a3b8;
    padding: 20px;
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