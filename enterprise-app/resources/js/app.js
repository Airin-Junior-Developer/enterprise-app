import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

// ---------------------------------------------------------
// 1. ลงทะเบียน MainApp (สำคัญมาก! ตัวนี้คือผู้กำกับที่คอยสลับหน้า)
// ---------------------------------------------------------
import MainApp from './components/MainApp.vue';
app.component('main-app', MainApp);


// ---------------------------------------------------------
// 2. ลงทะเบียน BranchManager (หน้าระบบสาขา)
// ---------------------------------------------------------
import BranchManager from './components/BranchManager.vue';
app.component('branch-manager', BranchManager);


// ---------------------------------------------------------
// 3. ลงทะเบียน EmployeeManager (หน้าระบบพนักงาน)
// ---------------------------------------------------------
import EmployeeManager from './components/EmployeeManager.vue';
app.component('employee-manager', EmployeeManager);


// ---------------------------------------------------------
// 4. ลงทะเบียน DashboardLayout (โครงสร้างหน้าเว็บ Sidebar/Header)
// ---------------------------------------------------------
import DashboardLayout from './components/DashboardLayout.vue';
app.component('dashboard-layout', DashboardLayout);


// ---------------------------------------------------------
// (Optional)
// ---------------------------------------------------------
import EmployeeList from './components/EmployeeList.vue';
app.component('employee-list', EmployeeList);

import EmployeeForm from './components/EmployeeForm.vue';
app.component('employee-form', EmployeeForm);

import PositionManager from './components/PositionManager.vue';
app.component('position-manager', PositionManager);


app.mount('#app');