<template>
  <div class="w-full">

    <!-- Page header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Contributions</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ total }} soumission{{ total !== 1 ? 's' : '' }} au total.</p>
      </div>
      <a href="/admin/contributions/export"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#006B4F] text-white text-sm font-semibold shadow-sm hover:bg-[#005a42] active:scale-[0.98] transition-all duration-150">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Exporter CSV
      </a>
    </div>

    <!-- Filter bar -->
    <div class="bg-white rounded-2xl ring-1 ring-gray-200/80 shadow-sm p-4 mb-4 flex flex-wrap gap-3">

      <!-- Search -->
      <div class="relative flex-1 min-w-48">
        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input v-model="filters.search" @input="page = 1"
          placeholder="Nom, prénom, email…"
          class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-[#006B4F] focus:ring-2 focus:ring-[#006B4F]/10 placeholder-gray-400 transition-all" />
      </div>

      <!-- Type filter -->
      <select v-model="filters.type" @change="page = 1"
        class="px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-[#006B4F] focus:ring-2 focus:ring-[#006B4F]/10 text-gray-700 transition-all">
        <option value="">Tous les types</option>
        <option value="Poster">Poster</option>
        <option value="Communication orale">Communication orale</option>
      </select>

      <!-- Payment filter -->
      <select v-model="filters.payment" @change="page = 1"
        class="px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-[#006B4F] focus:ring-2 focus:ring-[#006B4F]/10 text-gray-700 transition-all">
        <option value="">Tout paiement</option>
        <option value="Oui">Payé</option>
        <option value="Pas encore">Non payé</option>
        <option value="">Sans paiement</option>
      </select>

      <!-- Reset -->
      <button v-if="filters.search || filters.type || filters.payment"
        @click="filters = { search: '', type: '', payment: '' }; page = 1"
        class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl transition-all font-medium">
        Réinitialiser
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl ring-1 ring-gray-200/80 shadow-sm overflow-hidden">

      <!-- Loading -->
      <div v-if="loading" class="p-8 space-y-3">
        <div v-for="i in 8" :key="i" class="h-11 bg-gray-50 rounded-xl animate-pulse"></div>
      </div>

      <!-- Empty -->
      <div v-else-if="paged.length === 0"
        class="flex flex-col items-center justify-center py-24 text-gray-400">
        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
          <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <p class="text-sm font-medium">Aucune contribution trouvée.</p>
      </div>

      <!-- Table content -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50/80 border-b border-gray-100">
              <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Participant</th>
              <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Session</th>
              <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Type</th>
              <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Paiement</th>
              <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Fichiers</th>
              <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Téléch.</th>
              <th class="text-left px-5 py-3 text-[11px] font-semibold text-gray-400 tracking-wider uppercase">Date</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="c in paged" :key="c.id" class="hover:bg-gray-50/60 transition-colors duration-100">

              <!-- Participant -->
              <td class="px-5 py-3.5">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                    style="background: linear-gradient(135deg, #006B4F, #0F8A65);">
                    {{ c.prenom?.[0]?.toUpperCase() }}{{ c.nom?.[0]?.toUpperCase() }}
                  </div>
                  <div class="min-w-0">
                    <div class="text-sm font-semibold text-gray-900 truncate">{{ c.prenom }} {{ c.nom }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ c.email }}</div>
                  </div>
                </div>
              </td>

              <td class="px-5 py-3.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-xs font-medium text-gray-600">{{ c.session }}</span>
              </td>

              <td class="px-5 py-3.5"><TypeBadge :type="c.contribution_type" /></td>
              <td class="px-5 py-3.5"><PaymentBadge :paid="c.payment_status" /></td>

              <!-- Files -->
              <td class="px-5 py-3.5">
                <div class="flex gap-1.5">
                  <button v-if="c.poster_file" @click="openPreview(c.poster_file, 'Résumé — ' + c.prenom + ' ' + c.nom, c.id, 'poster')"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Résumé
                  </button>
                  <button v-if="c.payment_receipt" @click="openPreview(c.payment_receipt, 'Reçu paiement — ' + c.prenom + ' ' + c.nom, c.id, 'receipt')"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Paiement
                  </button>
                  <span v-if="!c.poster_file && !c.payment_receipt" class="text-xs text-gray-300">—</span>
                </div>
              </td>

              <td class="px-5 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                <span v-if="c.downloads > 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 font-semibold">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                  {{ c.downloads }}
                </span>
                <span v-else class="text-gray-300">—</span>
              </td>

              <td class="px-5 py-3.5 text-xs text-gray-400 whitespace-nowrap">{{ formatDate(c.created_at) }}</td>

              <!-- Delete -->
              <td class="px-5 py-3.5">
                <button @click="confirmDelete(c)"
                  class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-gray-100 bg-gray-50/50">
        <span class="text-xs text-gray-400">Page {{ page }} / {{ totalPages }}</span>
        <div class="flex gap-1.5">
          <button @click="page--" :disabled="page === 1"
            class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium border transition-all"
            :class="page === 1 ? 'text-gray-300 border-gray-100 cursor-not-allowed' : 'text-gray-600 border-gray-200 hover:bg-white hover:shadow-sm'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <button v-for="p in pageNumbers" :key="p" @click="page = p"
            class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold border transition-all"
            :class="p === page ? 'bg-[#006B4F] text-white border-[#006B4F] shadow-sm' : 'text-gray-600 border-gray-200 hover:bg-white hover:shadow-sm'">
            {{ p }}
          </button>
          <button @click="page++" :disabled="page === totalPages"
            class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium border transition-all"
            :class="page === totalPages ? 'text-gray-300 border-gray-100 cursor-not-allowed' : 'text-gray-600 border-gray-200 hover:bg-white hover:shadow-sm'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- File Preview Modal -->
    <transition enter-from-class="opacity-0" enter-active-class="transition-opacity duration-200" leave-to-class="opacity-0" leave-active-class="transition-opacity duration-150">
      <div v-if="previewFile" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click.self="previewFile = null">
        <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-black/10 w-full max-w-4xl flex flex-col" style="height: 88vh">
          <!-- Header -->
          <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
              </div>
              <span class="text-sm font-semibold text-gray-900 truncate max-w-md">{{ previewTitle }}</span>
            </div>
            <div class="flex items-center gap-2">
              <button @click="downloadFile()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Télécharger
              </button>
              <button @click="previewFile = null" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
          </div>
          <!-- Viewer -->
          <div class="flex-1 overflow-hidden rounded-b-2xl bg-gray-50">
            <iframe :src="`/storage/${previewFile}`" class="w-full h-full border-0" :title="previewTitle"></iframe>
          </div>
        </div>
      </div>
    </transition>

    <!-- Delete modal -->
    <transition enter-from-class="opacity-0" enter-active-class="transition-opacity duration-200" leave-to-class="opacity-0" leave-active-class="transition-opacity duration-150">
      <div v-if="deleteTarget" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 max-w-sm w-full p-6">
          <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
          <h3 class="text-base font-bold text-gray-900 mb-1">Supprimer la contribution ?</h3>
          <p class="text-sm text-gray-500 mb-6">
            La contribution de <strong>{{ deleteTarget?.prenom }} {{ deleteTarget?.nom }}</strong> sera définitivement supprimée.
          </p>
          <div class="flex gap-2">
            <button @click="deleteTarget = null" class="flex-1 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
              Annuler
            </button>
            <button @click="doDelete" :disabled="deleting"
              class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors disabled:opacity-60">
              {{ deleting ? 'Suppression…' : 'Supprimer' }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import TypeBadge from './TypeBadge.vue';
import PaymentBadge from './PaymentBadge.vue';

const contributions = ref([]);
const loading       = ref(true);
const filters       = ref({ search: '', type: '', payment: '' });
const page          = ref(1);
const perPage       = 12;
const deleteTarget  = ref(null);
const deleting      = ref(false);
const previewFile   = ref(null);
const previewTitle  = ref('');
const previewContribId = ref(null);
const previewType   = ref('');

const filtered = computed(() => {
  let list = contributions.value;
  if (filters.value.search) {
    const q = filters.value.search.toLowerCase();
    list = list.filter(c => `${c.prenom} ${c.nom} ${c.email}`.toLowerCase().includes(q));
  }
  if (filters.value.type)    list = list.filter(c => c.contribution_type === filters.value.type);
  if (filters.value.payment) list = list.filter(c => c.payment_status === filters.value.payment);
  return list;
});

const total      = computed(() => contributions.value.length);
const totalPages = computed(() => Math.ceil(filtered.value.length / perPage));
const paged      = computed(() => filtered.value.slice((page.value - 1) * perPage, page.value * perPage));
const pageNumbers = computed(() => {
  const total = totalPages.value;
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
  return Array.from({ length: total }, (_, i) => i + 1).slice(Math.max(0, page.value - 3), page.value + 2);
});

watch(filters, () => { page.value = 1; }, { deep: true });

function formatDate(d) {
  return new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
}
function confirmDelete(c) { deleteTarget.value = c; }
function openPreview(file, title, contribId, type) {
  previewFile.value = file;
  previewTitle.value = title;
  previewContribId.value = contribId;
  previewType.value = type;
}

async function downloadFile() {
  const url = `/admin/files/download/${previewContribId.value}/${previewType.value}`;
  const res = await fetch(url, {
    credentials: 'include',
    headers: { 'Accept': '*/*', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content },
  });
  if (!res.ok) return;
  const blob = await res.blob();
  const disposition = res.headers.get('Content-Disposition') || '';
  const nameMatch = disposition.match(/filename[^;=\n]*=(['"]?)([^'"\n;]*)\1/);
  const filename = nameMatch ? nameMatch[2] : `fichier_${previewContribId.value}`;
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = filename;
  a.click();
  URL.revokeObjectURL(a.href);
  // Update local counter
  const contrib = contributions.value.find(c => c.id === previewContribId.value);
  if (contrib) contrib.downloads = (contrib.downloads || 0) + 1;
}

async function doDelete() {
  if (!deleteTarget.value || deleting.value) return;
  deleting.value = true;
  try {
    const res = await fetch(`/admin/contributions/${deleteTarget.value.id}`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content },
    });
    if (res.ok) contributions.value = contributions.value.filter(c => c.id !== deleteTarget.value.id);
  } finally { deleting.value = false; deleteTarget.value = null; }
}

onMounted(async () => {
  try {
    const res  = await fetch('/admin/contributions?per_page=500', { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    contributions.value = data.data ?? data;
  } finally { loading.value = false; }
});
</script>
