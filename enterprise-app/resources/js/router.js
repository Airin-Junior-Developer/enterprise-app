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
    { path: '/positions', component: PositionManager, meta: { requiresAuth: true, requiresSuperAdmin: true } }, 
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

    if (to.meta.requiresAuth && !token) {
        next('/login');
        return;
    }

    if (to.meta.guest && token) {
        next('/');
        return;
    }

    if (to.meta.requiresSuperAdmin) {
        if (!userStr) {
            next('/login');
            return;
        }
        try {
            const currentUser = JSON.parse(userStr);
            const posName = currentUser?.position?.position_name?.trim().toLowerCase() 
                         || currentUser?.position_name?.trim().toLowerCase();

            if (posName === 'super admin' || posName === 'system admin') {
                next();
            } else {
                alert('ไม่อนุญาตให้เข้าถึง: ส่วนนี้เฉพาะผู้ดูแลระบบระดับสูงเท่านั้น');
                next('/');
            }
        } catch (e) {
            localStorage.clear();
            next('/login');
        }    
    }
    else if (to.meta.requiresManager) {
        if (!userStr) {
            next('/login');
            return;
        }
        try {
            const currentUser = JSON.parse(userStr);
            const posName = currentUser?.position?.position_name?.trim().toLowerCase() 
                         || currentUser?.position_name?.trim().toLowerCase();
            
            const allowedRoles = ['super admin', 'system admin', 'hr manager'];

            if (allowedRoles.includes(posName)) {
                next();
            } else {
                alert('ไม่อนุญาตให้เข้าถึง: ส่วนนี้เฉพาะผู้จัดการและผู้ดูแลระบบเท่านั้น');
                next('/');
            }
        } catch (e) {
            localStorage.clear();
            next('/login');
        }
    }
    else {
        next();
    }
});

export default router;