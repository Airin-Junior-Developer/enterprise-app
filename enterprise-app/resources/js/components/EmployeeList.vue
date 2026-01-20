<template>
    <div class="list-container">
        <div class="header-box">
            <h2>📋 รายชื่อพนักงาน (Employee List)</h2>
            <button @click="fetchEmployees" class="btn-refresh">🔄 รีเฟรช</button>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">ชื่อ-นามสกุล</th>
                        <th width="15%">ตำแหน่ง</th>
                        <th width="15%">สาขา</th>
                        <th width="15%">วันที่เริ่มงาน</th>
                        <th width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="emp in employees" :key="emp.id">
                        <td>{{ emp.id }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ emp.profile ? emp.profile.prefix + ' ' +
                                emp.profile.firstname : emp.name }}</div>
                            <div style="font-size: 12px; color: #888;">{{ emp.profile ? emp.profile.lastname : '' }}
                            </div>
                        </td>
                        <td>{{ emp.profile ? emp.profile.position : '-' }}</td>
                        <td>
                            <span class="branch-badge" v-if="emp.branch">
                                {{ emp.branch.name }}
                            </span>
                            <span v-else style="color: #ccc;">-</span>
                        </td>
                        <td>{{ emp.profile ? emp.profile.start_date : '-' }}</td>
                        <td>
                            <button class="btn-action btn-edit" @click="editEmployee(emp)">
                                ✏️
                            </button>

                            <button class="btn-action btn-delete" @click="deleteEmployee(emp.id)">
                                🗑️
                            </button>
                        </td>
                    </tr>

                    <tr v-if="employees.length === 0">
                        <td colspan="6" class="text-center">ไม่พบข้อมูลพนักงาน...</td>
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
            employees: []
        }
    },
    mounted() {
        this.fetchEmployees();
    },
    methods: {
        fetchEmployees() {
            fetch('/api/employees')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.employees = data.data;
                    }
                })
                .catch(err => console.error(err));
        },
        // ฟังก์ชันลบข้อมูล (Delete)
        async deleteEmployee(id) {
            if (!confirm("⚠️ คุณแน่ใจหรือไม่ที่จะลบพนักงานคนนี้? (กู้คืนไม่ได้นะ)")) return;

            try {
                const res = await fetch(`/api/employees/${id}`, {
                    method: 'DELETE', // สั่งให้ใช้ Method DELETE
                    headers: {
                        'Content-Type': 'application/json'
                        // ปกติถ้ามี Auth ต้องใส่ Token ตรงนี้ แต่เราทำระบบเปิดไปก่อน
                    }
                });

                if (res.ok) {
                    alert('✅ ลบข้อมูลเรียบร้อย');
                    this.fetchEmployees(); // โหลดตารางใหม่ทันที
                } else {
                    alert('❌ ลบไม่สำเร็จ');
                }
            } catch (error) {
                console.error(error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        },
        editEmployee(emp) {
            // ส่งสัญญาณชื่อ 'edit-employee' พร้อมข้อมูล ไปให้ Manager
            this.$emit('edit-employee', emp);
        }
    }
}
</script>

<style scoped>
/* CSS ปรับปรุงใหม่ให้ดู Modern ขึ้น */
.list-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.header-box {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}

h2 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.btn-refresh {
    background: #f0f0f0;
    border: 1px solid #ccc;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-refresh:hover {
    background: #e0e0e0;
}

.table-responsive {
    overflow-x: auto;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}

.custom-table th {
    background: #f8f9fa;
    padding: 12px;
    text-align: left;
    font-size: 14px;
    color: #666;
    font-weight: 600;
    border-bottom: 2px solid #eee;
}

.custom-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    vertical-align: middle;
}

.custom-table tr:hover {
    background-color: #f9f9f9;
}

.branch-badge {
    background: #e6f7ff;
    color: #0050b3;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
    border: 1px solid #91d5ff;
}

.btn-action {
    border: none;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 5px;
    transition: 0.2s;
}

.btn-edit {
    background: #fff7e6;
    color: #fa8c16;
    border: 1px solid #ffd591;
}

.btn-edit:hover {
    background: #fa8c16;
    color: white;
}

.btn-delete {
    background: #fff1f0;
    color: #f5222d;
    border: 1px solid #ffa39e;
}

.btn-delete:hover {
    background: #f5222d;
    color: white;
}

.text-center {
    text-align: center;
    color: #999;
    padding: 20px;
}
</style>