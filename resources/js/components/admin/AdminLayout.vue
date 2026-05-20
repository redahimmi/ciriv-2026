<template>
  <div class="flex h-screen bg-[#F5F5F7] overflow-hidden">

    <!-- ── SIDEBAR ── -->
    <aside class="fixed inset-y-0 left-0 z-30 flex flex-col w-60 bg-white border-r border-gray-200/80 shadow-sm transition-transform duration-300"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

      <!-- Logo / Brand -->
      <div class="flex items-center gap-3 px-5 pt-5 pb-4 border-b border-gray-100">
        <div class="bg-[#F5F5F7] rounded-xl p-1.5 ring-1 ring-gray-200 flex-shrink-0">
          <img :src="'/assets/logociriv.png'" alt="IRIV" class="h-8 w-8 object-contain" />
        </div>
        <div class="min-w-0">
          <div class="text-[13px] font-bold text-gray-900 leading-tight truncate">CIRIV</div>
          <div class="text-[11px] text-gray-400 font-medium tracking-wide uppercase">Backoffice</div>
        </div>
      </div>

      <!-- Nav section label -->
      <div class="px-4 pt-5 pb-1.5">
        <span class="text-[10px] font-semibold tracking-widest text-gray-400 uppercase">Navigation</span>
      </div>

      <!-- Nav items -->
      <nav class="flex-1 px-3 pb-3 space-y-0.5 overflow-y-auto">
        <router-link v-for="item in navItems" :key="item.to" :to="item.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13.5px] font-medium transition-all duration-150 group"
          :class="$route.path.startsWith(item.to)
            ? 'bg-[#006B4F]/10 text-[#006B4F]'
            : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900'">
          <!-- Icon -->
          <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-150"
            :class="$route.path.startsWith(item.to)
              ? 'bg-[#006B4F] text-white shadow-sm'
              : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 group-hover:text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.iconPath"/>
            </svg>
          </span>
          {{ item.label }}
          <!-- Active indicator -->
          <span v-if="$route.path.startsWith(item.to)"
            class="ml-auto w-1.5 h-1.5 rounded-full bg-[#006B4F]"></span>
        </router-link>
      </nav>

      <!-- Divider -->
      <div class="mx-4 border-t border-gray-100"></div>

      <!-- User section -->
      <div class="px-3 py-4 space-y-0.5">
        <div class="flex items-center gap-3 px-3 py-2 rounded-xl mb-1">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
            style="background: linear-gradient(135deg, #006B4F, #0F8A65);">A</div>
          <div class="min-w-0">
            <div class="text-[13px] font-semibold text-gray-900 truncate">Admin IRIV</div>
            <div class="text-[11px] text-gray-400 truncate">admin@iriv.ma</div>
          </div>
        </div>
        <button @click="handleLogout"
          class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13.5px] font-medium text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all duration-150 group">
          <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 group-hover:bg-red-100 group-hover:text-red-500 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
          </span>
          Déconnexion
        </button>
      </div>
    </aside>

    <!-- Mobile overlay -->
    <div v-if="sidebarOpen" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-20 md:hidden"
      @click="sidebarOpen = false"></div>

    <!-- ── MAIN ── -->
    <div class="flex-1 flex flex-col overflow-hidden md:pl-60">

      <!-- Top bar -->
      <header class="flex items-center gap-4 px-6 py-3.5 bg-white/90 backdrop-blur-xl border-b border-gray-200/80 flex-shrink-0 sticky top-0 z-10">
        <!-- Mobile burger -->
        <button @click="sidebarOpen = !sidebarOpen"
          class="md:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>

        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm">
          <span class="text-gray-400 font-medium">Administration</span>
          <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
          <span class="font-semibold text-gray-900">{{ pageTitle }}</span>
        </div>

        <div class="flex-1"></div>

        <!-- Public site link -->
        <a href="/" target="_blank"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
          </svg>
          Site public
        </a>
      </header>

      <!-- Page content -->
      <main class="flex-1 overflow-y-auto p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route  = useRoute();
const router = useRouter();
const sidebarOpen = ref(false);

const pageTitles = {
  '/admin/dashboard':     'Tableau de bord',
  '/admin/contributions': 'Contributions',
  '/admin/participants':  'Participants',
  '/admin/settings':      'Paramètres',
};
const pageTitle = computed(() => pageTitles[route.path] || 'Administration');

// SVG icon paths
const PATH_DASHBOARD = 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6';
const PATH_LIST      = 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01';
const PATH_PEOPLE    = 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z';
const PATH_SETTINGS  = 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4';

const navItems = [
  { to: '/admin/dashboard',     label: 'Tableau de bord', iconPath: PATH_DASHBOARD },
  { to: '/admin/contributions', label: 'Contributions',   iconPath: PATH_LIST },
  { to: '/admin/participants',  label: 'Participants',    iconPath: PATH_PEOPLE },
  { to: '/admin/settings',      label: 'Paramètres',      iconPath: PATH_SETTINGS },
];

async function handleLogout() {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  await fetch('/admin/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
  router.push('/admin/login');
}
</script>
