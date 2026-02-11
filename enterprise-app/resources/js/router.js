import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import EmployeeManager from './components/EmployeeManager.vue';
import BranchManager from './components/BranchManager.vue';
import PositionManager from './components/PositionManager.vue';
import RequestManager from './components/RequestManager.vue';
import ApprovalManager from './components/ApprovalManager.vue';
import RequestTypeManager from './components/RequestTypeManager.vue';

const routes = [
    { path: '/login', component: Login },
    { path: '/', component: Dashboard },
    { path: '/employees', component: EmployeeManager },
    { path: '/branches', component: BranchManager },
    { path: '/positions', component: PositionManager, meta: { requiresSuperAdmin: true } }, 
    { path: '/requests', component: RequestManager },
    { path: '/approvals', component: ApprovalManager },
    { path: '/request-types', component: RequestTypeManager },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');

    if (to.path !== '/login' && !token) {
        next('/login');
        return;
    }

    // ด่านที่ 2: ถ้าหน้าที่จะไป ต้องการสิทธิ์ Super Admin
    if (to.meta.requiresSuperAdmin) {
        const userStr = localStorage.getItem('user');
        
        if (userStr) {
            try {
                const currentUser = JSON.parse(userStr);
                const posName = currentUser?.position_name?.trim().toLowerCase();

                // เช็คว่าเป็น Super Admin หรือ System Admin หรือไม่
                if (posName === 'super admin' || posName === 'system admin') {
                    next(); // ✅ มีสิทธิ์ ผ่านได้!
                } else {
                    alert('ไม่อนุญาตให้เข้าถึง: เฉพาะ Super Admin เท่านั้น');
                    next('/'); // ❌ ไม่มีสิทธิ์ เตะกลับหน้าแรก (Dashboard)
                }
            } catch (e) {
                console.error("Error parsing user data");
                next('/login'); // ข้อมูลพัง เตะไปล็อกอินใหม่
            }
        } else {
            next('/login'); // ไม่มีข้อมูล User เตะไปล็อกอินใหม่
        }
    } else {
        // ด่านที่ 3: หน้าทั่วไปที่ไม่ได้ล็อกสิทธิ์ (และมี Token แล้ว) ปล่อยผ่านได้เลย
        next();
    }
});

export default router;