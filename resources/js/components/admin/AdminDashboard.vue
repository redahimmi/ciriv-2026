<template>
  <div class="w-full">

    <!-- Page header -->
    <div class="mb-6">
      <h2 class="text-xl font-bold text-gray-900">Tableau de bord</h2>
      <p class="text-sm text-gray-500 mt-0.5">Vue d'ensemble des contributions reçues.</p>
    </div>

    <!-- Stat cards -->
    <div v-if="loading" class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
      <div v-for="i in 5" :key="i" class="h-28 bg-white rounded-2xl animate-pulse ring-1 ring-gray-200/80"></div>
    </div>

    <div v-else class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
      <div v-for="card in statCards" :key="card.label"
        class="bg-white rounded-2xl p-5 ring-1 ring-gray-200/80 shadow-sm hover:shadow-md transition-all duration-200 group cursor-default">
        <div class="flex items-start justify-between mb-4">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="card.iconBg">
            <svg class="w-5 h-5" :class="card.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.iconPath"/>
            </svg>
          </div>
          <div class="text-right">
            <div class="text-2xl font-bold text-gray-900 leading-none">{{ card.value }}</div>
          </div>
        </div>
        <div class="text-xs font-semibold text-gray-500 leading-tight">{{ card.label }}</div>
        <div class="mt-3 h-0.5 rounded-full" :class="card.barColor"></div>
      </div>
    </div>

    <!-- Latest submissions -->
    <div class="bg-white rounded-2xl ring-1 ring-gray-200/80 shadow-sm overflow-hidden">
      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div>
          <h3 class="text-[15px] font-bold text-gray-900">Dernières soumissions</h3>
          <p class="text-xs text-gray-400 mt-0.5">5 contributions les plus récentes</p>
        </div>
        <router-link to="/admin/contributions"
          class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#006B4F] hover:text-[#005a42] transition-colors">
          Voir tout
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </router-link>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loading" class="p-6 space-y-3">
        <div v-for="i in 5" :key="i" class="h-10 bg-gray-50 rounded-xl animate-pulse"></div>
      </div>

      <!-- Empty -->
      <div v-else-if="latest.length === 0"
        class="flex flex-col items-center justify-center py-20 text-gray-400">
        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
          <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <p class="text-sm font-medium">Aucune soumission pour le moment.</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50/80 border-b border-gray-100">
              <th class="text-left px-6 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Nom</th>
              <th class="text-left px-6 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Email</th>
              <th class="text-left px-6 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Type</th>
              <th class="text-left px-6 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Session</th>
              <th class="text-left px-6 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="c in latest" :key="c.id"
              class="hover:bg-gray-50/60 transition-colors duration-100">
              <td class="px-6 py-3.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold text-white flex-shrink-0"
                    style="background: linear-gradient(135deg, #006B4F, #0F8A65);">
                    {{ c.prenom?.[0]?.toUpperCase() }}{{ c.nom?.[0]?.toUpperCase() }}
                  </div>
                  <span class="text-sm font-semibold text-gray-900">{{ c.prenom }} {{ c.nom }}</span>
                </div>
              </td>
              <td class="px-6 py-3.5 text-sm text-gray-500">{{ c.email }}</td>
              <td class="px-6 py-3.5"><TypeBadge :type="c.contribution_type" /></td>
              <td class="px-6 py-3.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-xs font-medium text-gray-600">{{ c.session }}</span>
              </td>
              <td class="px-6 py-3.5 text-xs text-gray-400">{{ formatDate(c.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import TypeBadge from './TypeBadge.vue';

const loading = ref(true);
const stats   = ref({ total: 0, posters: 0, orales: 0, paid: 0, unpaid: 0 });
const latest  = ref([]);

// SVG icon paths
const PATH_TOTAL  = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
const PATH_POSTER = 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
const PATH_ORALE  = 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z';
const PATH_PAID   = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
const PATH_UNPAID = 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';

const statCards = computed(() => [
  { label: 'Total soumissions', value: stats.value.total,   iconPath: PATH_TOTAL,  iconBg: 'bg-blue-50',      iconColor: 'text-blue-600',    barColor: 'bg-blue-100' },
  { label: 'Posters',           value: stats.value.posters, iconPath: PATH_POSTER, iconBg: 'bg-[#006B4F]/10', iconColor: 'text-[#006B4F]',   barColor: 'bg-[#006B4F]/20' },
  { label: 'Comm. orales',      value: stats.value.orales,  iconPath: PATH_ORALE,  iconBg: 'bg-red-50',       iconColor: 'text-red-600',     barColor: 'bg-red-100' },
  { label: 'Paiements reçus',   value: stats.value.paid,    iconPath: PATH_PAID,   iconBg: 'bg-emerald-50',   iconColor: 'text-emerald-600', barColor: 'bg-emerald-100' },
  { label: 'Non payés',         value: stats.value.unpaid,  iconPath: PATH_UNPAID, iconBg: 'bg-amber-50',     iconColor: 'text-amber-600',   barColor: 'bg-amber-100' },
]);

function formatDate(d) {
  return new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
}

onMounted(async () => {
  try {
    const res = await fetch('/admin/dashboard', { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    stats.value  = data.stats;
    latest.value = data.latest;
  } finally { loading.value = false; }
});
</script>
