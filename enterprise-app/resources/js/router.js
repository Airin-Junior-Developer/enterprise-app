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
    { path: '/login', component: Login, meta: { guest: true } },
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/employees', component: EmployeeManager, meta: { requiresAuth: true } },
    { path: '/branches', component: BranchManager, meta: { requiresAuth: true } },
    { 
        path: '/positions', 
        component: PositionManager, 
        meta: { requiresAuth: true, requiresSuperAdmin: true } 
    }, 
    { path: '/requests', component: RequestManager, meta: { requiresAuth: true } },
    { path: '/approvals', component: ApprovalManager, meta: { requiresAuth: true } },
    { path: '/request-types', component: RequestTypeManager, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// --- Navigation Guard ---
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const userStr = localStorage.getItem('user');

    // 1. ถ้าหน้าต้องการล็อกอิน แต่ไม่มี Token
    if (to.meta.requiresAuth && !token) {
        next('/login');
        return;
    }

    // 2. ถ้าล็อกอินอยู่แล้ว แต่พยายามเข้าหน้า Login (เตะไปหน้าแรก)
    if (to.meta.guest && token) {
        next('/');
        return;
    }

    // 3. ตรวจสอบสิทธิ์ Super Admin
    if (to.meta.requiresSuperAdmin) {
        if (!userStr) {
            next('/login');
            return;
        }

        try {
            const currentUser = JSON.parse(userStr);
            // ดึงค่า position_name จาก User Object (ตัวเล็กทั้งหมด)
            const posName = currentUser?.position_name?.trim().toLowerCase();

            if (posName === 'super admin' || posName === 'system admin') {
                next(); // ✅ ผ่าน
            } else {
                alert('ไม่อนุญาตให้เข้าถึง: ส่วนนี้เฉพาะผู้ดูแลระบบระดับสูงเท่านั้น');
                next('/'); // ❌ กลับหน้าแรก
            }
        } catch (e) {
            localStorage.clear();
            next('/login');
        }
    } else {
        // 4. หน้าทั่วไป ปล่อยผ่าน
        next();
    }
});

export default router;