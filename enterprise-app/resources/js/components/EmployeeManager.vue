<template>
    <div class="manager-container">
        <employee-form :edit-data="selectedEmployee" @saved="refreshTable"></employee-form>

        <hr class="divider">

        <employee-list ref="listComponent" @edit-employee="prepareEdit"></employee-list>
    </div>
</template>

<script>
// นำเข้าลูกน้องทั้ง 2 คน
import EmployeeForm from './EmployeeForm.vue';
import EmployeeList from './EmployeeList.vue';

export default {
    components: {
        'employee-form': EmployeeForm,
        'employee-list': EmployeeList
    },
    data() {
        return {
            selectedEmployee: null // ตัวแปรเก็บข้อมูลคนที่จะแก้ไข
        }
    },
    methods: {
        // ฟังก์ชัน 1: เมื่อตารางส่งคนมาให้แก้ไข
        prepareEdit(employee) {
            this.selectedEmployee = employee; // ส่งต่อให้ฟอร์ม
            window.scrollTo({ top: 0, behavior: 'smooth' }); // เลื่อนหน้าจอขึ้นบนสุด
        },
        // ฟังก์ชัน 2: เมื่อฟอร์มบันทึกเสร็จ ให้รีเฟรชตาราง
        refreshTable() {
            this.selectedEmployee = null; // ล้างค่าการแก้ไข
            this.$refs.listComponent.fetchEmployees(); // สั่งตารางให้โหลดข้อมูลใหม่
        }
    }
}
</script>

<style scoped>
.manager-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.divider {
    margin: 30px 0;
    border: 0;
    border-top: 1px solid #ddd;
}
</style>