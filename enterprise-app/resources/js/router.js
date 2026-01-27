import { createRouter, createWebHistory } from 'vue-router';

// นำเข้าหน้าจอต่างๆ
import Dashboard from './components/Dashboard.vue';
import EmployeeManager from './components/EmployeeManager.vue';
import BranchManager from './components/BranchManager.vue';
import PositionManager from './components/PositionManager.vue';

const routes = [
    { path: '/', component: Dashboard },
    { path: '/employees', component: EmployeeManager },
    { path: '/branches', component: BranchManager },
    { path: '/positions', component: PositionManager },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;