import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Login.vue'; // Import
import Dashboard from './components/Dashboard.vue';
import EmployeeManager from './components/EmployeeManager.vue';
import BranchManager from './components/BranchManager.vue';
import PositionManager from './components/PositionManager.vue';
import RequestManager from './components/RequestManager.vue';
import ApprovalManager from './components/ApprovalManager.vue';

const routes = [
    { path: '/login', component: Login }, // เพิ่ม Route Login
    { path: '/', component: Dashboard },
    { path: '/employees', component: EmployeeManager },
    { path: '/branches', component: BranchManager },
    { path: '/positions', component: PositionManager },
    { path: '/requests', component: RequestManager },
    { path: '/approvals', component: ApprovalManager },
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ระบบป้องกัน: ถ้าไม่มี Token ให้เด้งไปหน้า Login
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.path !== '/login' && !token) {
        next('/login');
    } else {
        next();
    }
});

export default router;