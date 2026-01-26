<template>
    <dashboard-layout :current-tab="currentTab" @change-tab="switchTab">

        <div v-if="currentTab === 'dashboard' || currentTab === 'employees'">
            <div class="stats-container">
                <div class="stats-header">
                    <h2>ภาพรวมระบบ (Dashboard)</h2>
                    <button class="btn-primary">
                        <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        เพิ่มพนักงานใหม่
                    </button>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="icon-wrapper blue">
                            <svg class="stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>พนักงานทั้งหมด</h3>
                            <div class="number">24</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="icon-wrapper green">
                            <svg class="stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>สาขาที่เปิดใช้งาน</h3>
                            <div class="number">2</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="icon-wrapper orange">
                            <svg class="stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>รออนุมัติ</h3>
                            <div class="number">5</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 25px;">
                <employee-manager></employee-manager>
            </div>
        </div>

        <div v-if="currentTab === 'branches'">
            <branch-manager></branch-manager>
        </div>

        <div v-if="currentTab === 'positions'">
            <position-manager></position-manager>
        </div>

    </dashboard-layout>
</template>

<script>
import DashboardLayout from './DashboardLayout.vue';
import EmployeeManager from './EmployeeManager.vue';
import BranchManager from './BranchManager.vue';
import PositionManager from './PositionManager.vue';

export default {
    components: {
        'dashboard-layout': DashboardLayout,
        'employee-manager': EmployeeManager,
        'branch-manager': BranchManager,
        'position-manager': PositionManager
    },
    data() {
        return {
            currentTab: 'dashboard'
        }
    },
    methods: {
        switchTab(tabName) {
            this.currentTab = tabName;
        }
    }
}
</script>

<style scoped>
.stats-container {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.stats-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.stats-header h2 {
    margin: 0;
    font-size: 18px;
    color: #1e293b;
    font-weight: 600;
}

.btn-primary {
    background: #0061f2;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.2s;
}

.btn-primary:hover {
    background: #0052cc;
}

.btn-icon {
    width: 16px;
    height: 16px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
}

.stat-card {
    padding: 20px;
    border-radius: 8px;
    background: white;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-wrapper.blue {
    background: #eff6ff;
    color: #0061f2;
}

.icon-wrapper.green {
    background: #f0fdf4;
    color: #16a34a;
}

.icon-wrapper.orange {
    background: #fff7ed;
    color: #ea580c;
}

.stat-icon {
    width: 24px;
    height: 24px;
}

.stat-info h3 {
    margin: 0;
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}

.stat-info .number {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    margin-top: 2px;
}

.placeholder-box {
    background: white;
    padding: 50px;
    text-align: center;
    border-radius: 8px;
    border: 2px dashed #cbd5e1;
    color: #64748b;
}
</style>