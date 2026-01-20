<template>
    <div class="form-card">
        <h2 class="title">
            {{ isEditMode ? '✏️ แก้ไขข้อมูลพนักงาน' : '📝 เพิ่มพนักงานใหม่' }}
        </h2>

        <form @submit.prevent="submitForm">
            <div class="row">
                <div class="col-sm">
                    <label>คำนำหน้า</label>
                    <select v-model="form.prefix" required>
                        <option value="">เลือก</option>
                        <option value="นาย">นาย</option>
                        <option value="นาง">นาง</option>
                        <option value="นางสาว">นางสาว</option>
                    </select>
                </div>
                <div class="col-lg">
                    <label>ชื่อจริง</label>
                    <input type="text" v-model="form.firstname" required>
                </div>
                <div class="col-lg">
                    <label>นามสกุล</label>
                    <input type="text" v-model="form.lastname" required>
                </div>
            </div>

            <div class="row">
                <div class="col-lg">
                    <label>อีเมล (Login)</label>
                    <input type="email" v-model="form.email" required>
                </div>
                <div class="col-lg">
                    <label>ตำแหน่ง</label>
                    <input type="text" v-model="form.position" required>
                </div>
                <div class="col-lg">
                    <label>สาขา</label>
                    <select v-model="form.branch_id" required>
                        <option value="">-- เลือกสาขา --</option>
                        <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                            {{ branch.name }} ({{ branch.code }})
                        </option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <label>วันที่เริ่มงาน</label>
                    <input type="date" v-model="form.start_date" required>
                </div>
                <div class="col-lg">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" v-model="form.phone">
                </div>
            </div>

            <div class="action-area">
                <button v-if="isEditMode" type="button" class="btn-cancel" @click="resetForm">ยกเลิกแก้ไข</button>

                <button type="submit" class="btn-save" :class="{ 'btn-update': isEditMode }" :disabled="isSubmitting">
                    {{ isSubmitting ? 'กำลังบันทึก...' : (isEditMode ? '💾 บันทึกการแก้ไข' : '💾 บันทึกข้อมูล') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: ['editData'], // รับข้อมูลมาจาก Manager
    data() {
        return {
            branches: [],
            isSubmitting: false,
            isEditMode: false,
            editId: null, // เก็บ ID ที่กำลังแก้ไข
            form: {
                prefix: '', firstname: '', lastname: '',
                email: '', position: '', branch_id: '',
                phone: '', start_date: new Date().toISOString().split('T')[0]
            }
        }
    },
    watch: {
        // เฝ้าดูว่าถ้า Manager ส่งข้อมูลมาใหม่ ให้เอาใส่ฟอร์มทันที
        editData(newData) {
            if (newData) {
                this.isEditMode = true;
                this.editId = newData.id;
                // แตกข้อมูลใส่ฟอร์ม
                this.form = {
                    prefix: newData.profile?.prefix || '',
                    firstname: newData.profile?.firstname || '',
                    lastname: newData.profile?.lastname || '',
                    email: newData.email,
                    position: newData.profile?.position || '',
                    branch_id: newData.branch_id,
                    phone: newData.profile?.phone || '',
                    start_date: newData.profile?.start_date || ''
                };
            }
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
            } catch (error) { console.error(error); }
        },
        resetForm() {
            this.isEditMode = false;
            this.editId = null;
            this.form = {
                prefix: '', firstname: '', lastname: '',
                email: '', position: '', branch_id: '',
                phone: '', start_date: new Date().toISOString().split('T')[0]
            };
            this.$emit('saved'); // บอก Manager ว่าเลิกแก้แล้ว
        },
        async submitForm() {
            this.isSubmitting = true;

            // ถ้าโหมดแก้ไข ให้ยิง PUT ไปที่ /api/employees/{id}
            // ถ้าโหมดเพิ่ม  ให้ยิง POST ไปที่ /api/employees
            const url = this.isEditMode ? `/api/employees/${this.editId}` : '/api/employees';
            const method = this.isEditMode ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.form)
                });

                if (res.ok) {
                    alert(this.isEditMode ? '✅ แก้ไขข้อมูลสำเร็จ!' : '✅ เพิ่มข้อมูลสำเร็จ!');
                    this.resetForm(); // ล้างฟอร์ม
                    this.$emit('saved'); // ส่งสัญญาณบอก Manager ให้รีเฟรชตาราง
                } else {
                    const err = await res.json();
                    alert('❌ เกิดข้อผิดพลาด: ' + (err.message || 'บันทึกไม่สำเร็จ'));
                }
            } catch (error) {
                alert('เชื่อมต่อ Server ไม่ได้');
            }
            this.isSubmitting = false;
        }
    }
}
</script>

<style scoped>
.form-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    border: 1px solid #eee;
}

.title {
    border-bottom: 2px solid #42b883;
    display: inline-block;
    padding-bottom: 5px;
    margin-bottom: 20px;
    color: #333;
}

.row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.col-sm {
    flex: 1;
    min-width: 150px;
}

.col-lg {
    flex: 2;
    min-width: 250px;
}

input,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.action-area {
    text-align: right;
    margin-top: 20px;
}

.btn-save {
    background: #42b883;
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.btn-update {
    background: #f0ad4e;
}

/* สีส้มเมื่อแก้ไข */
.btn-cancel {
    background: #999;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
}
</style>