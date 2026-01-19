<template>
    <div class="container">
        <h2>รายชื่อพนักงาน (Employee List)</h2>

        <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>ID</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>ตำแหน่ง</th>
                    <th>สาขา</th>
                    <th>วันที่เริ่มงาน</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="emp in employees" :key="emp.id">
                    <td>{{ emp.id }}</td>
                    <td>{{ emp.profile ? (emp.profile.prefix + ' ' + emp.profile.firstname + ' ' + emp.profile.lastname)
                        : emp.name }}</td>
                    <td>{{ emp.profile ? emp.profile.position : '-' }}</td>
                    <td>
                        <span style="background-color: #e6f7ff; padding: 2px 8px; border-radius: 4px;">
                            {{ emp.branch ? emp.branch.name : 'ไม่ระบุ' }}
                        </span>
                    </td>
                    <td>{{ emp.profile ? emp.profile.start_date : '-' }}</td>
                </tr>

                <tr v-if="employees.length === 0">
                    <td colspan="5" style="text-align: center; color: red;">ไม่พบข้อมูลพนักงาน</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            employees: [] // ตัวแปรสำหรับเก็บรายชื่อพนักงาน
        }
    },
    mounted() {
        // เมื่อหน้าเว็บโหลดเสร็จ ให้เรียกฟังก์ชันดึงข้อมูลทันที
        this.fetchEmployees();
    },
    methods: {
        fetchEmployees() {
            // ยิงไปขอข้อมูลจากหลังบ้าน
            fetch('/api/employees')
                .then(response => response.json())
                .then(data => {
                    // เอาข้อมูลที่ได้ ใส่ตัวแปร employees
                    if (data.status === 'success') {
                        this.employees = data.data;
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
}
</script>

<style scoped>
.container {
    font-family: 'Sarabun', sans-serif;
    /* ถ้ามีฟอนต์ไทย */
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}
</style>