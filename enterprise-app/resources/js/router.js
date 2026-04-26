import { createRouter, createWebHistory } from 'vue-router';
import Swal from 'sweetalert2';

import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import EmployeeManager from './components/EmployeeManager.vue'; // ตอนนี้เราใช้หน้านี้หน้าเดียวจบ
import BranchManager from './components/BranchManager.vue';
import RequestManager from './components/RequestManager.vue';
import ApprovalManager from './components/ApprovalManager.vue';
import RequestTypeManager from './components/RequestTypeManager.vue';

const routes = [
    { path: '/login', component: Login, meta: { guest: true } },
    
    // โซนที่ 1: พนักงานทั่วไปเข้าได้ (ล็อคแค่ requiresAuth)
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/requests', component: RequestManager, meta: { requiresAuth: true } },
    { path: '/approvals', component: ApprovalManager, meta: { requiresAuth: true } },
    
    // โซนที่ 2: เฉพาะ HR Manager และ Admin เข้าได้ (ล็อคด้วย requiresManager)
    { path: '/employees', component: EmployeeManager, meta: { requiresAuth: true, requiresManager: true } },
    { path: '/branches', component: BranchManager, meta: { requiresAuth: true, requiresManager: true } },
    { path: '/request-types', component: RequestTypeManager, meta: { requiresAuth: true, requiresManager: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// --- ด่านตรวจสิทธิ์ (Navigation Guard) ---
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const userStr = localStorage.getItem('user');

    // 1. ถ้าไม่ได้ Login ให้เด้งไปหน้า Login
    if (to.meta.requiresAuth && !token) {
        return next('/login');
    }

    // 2. ถ้า Login แล้ว ห้ามเข้าหน้า Login ซ้ำ
    if (to.meta.guest && token) {
        return next('/');
    }

    // 3. ตรวจสอบหน้าของ HR Manager / Admin
    if (to.meta.requiresManager) {
        if (!userStr) return next('/login');
        
        try {
            const currentUser = JSON.parse(userStr);
            const posName = (currentUser?.position?.position_name || currentUser?.position_name || '').trim().toLowerCase();
            
            // กำหนดสิทธิ์ให้ 3 ตำแหน่งนี้เข้าหน้าจัดการได้
            const allowedRoles = ['super admin', 'system admin', 'hr manager'];

            if (allowedRoles.includes(posName)) {
                return next(); // ผ่านได้
            } else {
                Swal.fire({ icon: 'error', title: 'ไม่มีสิทธิ์เข้าถึง', text: 'หน้านี้เฉพาะฝ่าย HR หรือผู้ดูแลระบบเท่านั้น' });
                return next('/'); // สิทธิ์ไม่ถึง เด้งกลับหน้าแรก
            }
        } catch (e) {
            localStorage.clear();
            return next('/login');
        }
    }

    // 4. ปล่อยผ่านหน้าทั่วไป
    next();
});

export default router;