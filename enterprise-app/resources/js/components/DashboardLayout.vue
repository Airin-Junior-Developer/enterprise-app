<template>
    <div class="clockin-wrapper">
        <aside class="clockin-sidebar">
            <div class="sidebar-header">
                <div class="brand-logo">
                    <svg class="logo-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="logo-text">HR System<span class="dot">.</span></span>
                </div>
            </div>

            <div class="menu-group">
                <div class="group-title">การจัดการบุคคล (PERSONNEL)</div>
                <ul class="menu-list">
                    <li :class="{ active: currentTab === 'dashboard' }" @click="$emit('change-tab', 'dashboard')">
                        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        ภาพรวมระบบ (Dashboard)
                    </li>
                    <li :class="{ active: currentTab === 'employees' }" @click="$emit('change-tab', 'employees')">
                        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        รายชื่อพนักงาน (All Staff)
                    </li>
                </ul>
            </div>

            <div class="menu-group">
                <div class="group-title">ข้อมูลองค์กร (ORGANIZATION)</div>
                <ul class="menu-list">
                    <li :class="{ active: currentTab === 'branches' }" @click="$emit('change-tab', 'branches')">
                        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        สาขา (Branches)
                    </li>
                    <li :class="{ active: currentTab === 'positions' }" @click="$emit('change-tab', 'positions')">
                        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        ตำแหน่งงาน (Positions)
                    </li>
                </ul>
            </div>

            <div class="menu-group" style="margin-top: auto;">
                <ul class="menu-list">
                    <li @click="logout" class="logout-item">
                        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        ออกจากระบบ
                    </li>
                </ul>
            </div>
        </aside>

        <div class="clockin-main">
            <header class="clockin-header">
                <div class="header-left">
                    <h3 class="page-title">ระบบบริหารจัดการทรัพยากรบุคคล</h3>
                    <span class="current-date">{{ currentDate }}</span>
                </div>
                <div class="header-spacer"></div>
                <div class="user-profile-card">
                    <div class="avatar-circle">AD</div>
                    <div class="user-details">
                        <span class="name">ผู้ดูแลระบบ (Admin)</span>
                        <span class="role">HR MANAGER</span>
                    </div>
                </div>
            </header>

            <main class="page-content">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
export default {
    props: ['currentTab'],
    data() {
        return {
            currentDate: new Date().toLocaleDateString('th-TH', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            })
        }
    },
    methods: {
        logout() {
            if (confirm('ต้องการออกจากระบบหรือไม่?')) window.location.reload();
        }
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.clockin-wrapper {
    display: flex;
    height: 100vh;
    background-color: #f4f7fa;
    font-family: 'Sarabun', sans-serif;
}

/* Sidebar */
.clockin-sidebar {
    width: 280px;
    background: #ffffff;
    border-right: 1px solid #eef2f6;
    display: flex;
    flex-direction: column;
    padding: 20px 0;
}

.sidebar-header {
    padding: 0 25px 30px;
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
}

.logo-icon {
    width: 24px;
    height: 24px;
    color: #0061f2;
}

.logo-text {
    letter-spacing: -0.5px;
}

.dot {
    color: #0061f2;
}

.menu-group {
    margin-bottom: 25px;
}

.group-title {
    padding: 0 25px;
    font-size: 11px;
    color: #94a3b8;
    margin-bottom: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.menu-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-list li {
    padding: 12px 25px;
    cursor: pointer;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    transition: all 0.2s;
    border-left: 3px solid transparent;
    font-size: 14px;
}

.menu-list li:hover {
    background-color: #f8fafc;
    color: #0061f2;
}

.menu-list li.active {
    background-color: #eff6ff;
    color: #0061f2;
    border-left-color: #0061f2;
    font-weight: 600;
}

.menu-list li .icon {
    width: 20px;
    height: 20px;
}

.logout-item:hover {
    color: #ef4444;
    background-color: #fef2f2;
}

/* Header */
.clockin-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.clockin-header {
    height: 70px;
    background: #0061f2;
    display: flex;
    align-items: center;
    padding: 0 30px;
    color: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.page-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.current-date {
    font-size: 13px;
    opacity: 0.9;
    margin-top: 2px;
}

.header-spacer {
    flex: 1;
}

.user-profile-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 5px 15px 5px 5px;
    border-radius: 40px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: 0.2s;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.user-profile-card:hover {
    background: rgba(255, 255, 255, 0.2);
}

.avatar-circle {
    width: 35px;
    height: 35px;
    background: #ffffff;
    color: #0061f2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}

.user-details {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.user-details .name {
    font-size: 13px;
    font-weight: 600;
}

.user-details .role {
    font-size: 10px;
    opacity: 0.8;
}

.page-content {
    flex: 1;
    overflow-y: auto;
    padding: 30px;
}
</style>