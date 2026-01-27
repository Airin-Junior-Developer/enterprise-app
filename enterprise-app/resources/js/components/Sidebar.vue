<template>
    <aside
        class="w-64 bg-white border-r border-slate-200 flex flex-col h-screen fixed left-0 top-0 z-50 transition-all">
        <div class="h-16 flex items-center px-6 border-b border-slate-100">
            <div
                class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold mr-3 shadow-blue-200 shadow-lg">
                HR
            </div>
            <span class="text-xl font-extrabold text-slate-800 tracking-tight">HR System</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">

            <router-link to="/" class="flex items-center px-3 py-2.5 rounded-xl transition-all group"
                :class="$route.path === '/' ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                <span class="text-xl mr-3"
                    :class="$route.path === '/' ? 'opacity-100' : 'opacity-70 group-hover:opacity-100'">📊</span>
                <span class="font-semibold text-sm">ภาพรวมระบบ</span>
            </router-link>

            <div class="pt-4 pb-2 px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">การจัดการ (Management)
            </div>

            <router-link to="/employees" class="flex items-center px-3 py-2.5 rounded-xl transition-all group"
                active-class="bg-blue-50 text-blue-600">
                <span class="text-xl mr-3 opacity-70 group-hover:opacity-100">👥</span>
                <span class="font-semibold text-sm">รายชื่อพนักงาน</span>
            </router-link>

            <router-link to="/branches" class="flex items-center px-3 py-2.5 rounded-xl transition-all group"
                active-class="bg-blue-50 text-blue-600">
                <span class="text-xl mr-3 opacity-70 group-hover:opacity-100">🏢</span>
                <span class="font-semibold text-sm">ข้อมูลสาขา</span>
            </router-link>

            <router-link to="/positions" class="flex items-center px-3 py-2.5 rounded-xl transition-all group"
                active-class="bg-blue-50 text-blue-600">
                <span class="text-xl mr-3 opacity-70 group-hover:opacity-100">💼</span>
                <span class="font-semibold text-sm">ตำแหน่งงาน</span>
            </router-link>

        </nav>

        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <div
                    class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs">
                    AD
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-slate-700 truncate">Admin User</p>
                    <p class="text-xs text-slate-500 truncate">ผู้ดูแลระบบ</p>
                </div>
            </div>
        </div>
    </aside>
</template>

<script setup>
// ไม่ต้องมี Logic อะไรซับซ้อน ใช้ router-link จัดการ active class ให้เองครับ
</script>