<template>
  <div class="max-w-2xl">

    <!-- Page header -->
    <div class="mb-6">
      <h2 class="text-xl font-bold text-gray-900">Paramètres</h2>
      <p class="text-sm text-gray-500 mt-0.5">Gérez la disponibilité du formulaire de soumission.</p>
    </div>

    <!-- Success toast -->
    <transition enter-from-class="opacity-0 -translate-y-2" enter-active-class="transition-all duration-300" leave-to-class="opacity-0 -translate-y-2" leave-active-class="transition-all duration-200">
      <div v-if="saved" class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700">
        <div class="w-5 h-5 flex-shrink-0">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <span class="text-sm font-medium">Paramètre sauvegardé avec succès.</span>
      </div>
    </transition>

    <!-- Card -->
    <div class="bg-white rounded-2xl ring-1 ring-gray-200/80 shadow-sm overflow-hidden">

      <!-- Section: form status -->
      <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex items-center justify-between">
          <div class="flex-1 pr-6">
            <div class="flex items-center gap-2.5 mb-1">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                :class="formOpen ? 'bg-emerald-50' : 'bg-gray-100'">
                <svg class="w-4 h-4 transition-colors duration-300"
                  :class="formOpen ? 'text-emerald-600' : 'text-gray-400'"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <span class="text-[15px] font-bold text-gray-900">Formulaire de soumission</span>
            </div>
            <p class="text-xs text-gray-400 ml-10.5 leading-relaxed">
              Active ou désactive le formulaire public de soumission des contributions.
            </p>
          </div>

          <!-- Toggle -->
          <button @click="toggle" :disabled="saving"
            class="relative w-12 h-6.5 rounded-full transition-colors duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#006B4F]/40 flex-shrink-0"
            :class="formOpen ? 'bg-[#006B4F]' : 'bg-gray-200'"
            role="switch" :aria-checked="formOpen">
            <span class="absolute top-0.5 left-0.5 w-5.5 h-5.5 bg-white rounded-full shadow transition-transform duration-300"
              :class="formOpen ? 'translate-x-5.5' : 'translate-x-0'">
              <!-- spinner overlay -->
              <span v-if="saving" class="absolute inset-0 flex items-center justify-center">
                <svg class="w-3 h-3 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
              </span>
            </span>
          </button>
        </div>

        <!-- Status badge -->
        <div class="ml-10.5 mt-3">
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
            :class="formOpen ? 'bg-emerald-100 text-emerald-700' : 'bg-red-50 text-red-600'">
            <span class="w-1.5 h-1.5 rounded-full" :class="formOpen ? 'bg-emerald-500' : 'bg-red-400'"></span>
            {{ formOpen ? 'Ouvert — Les soumissions sont acceptées' : 'Fermé — Les soumissions sont désactivées' }}
          </span>
        </div>
      </div>

      <!-- Info section -->
      <div class="px-6 py-5 bg-gray-50/50">
        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">À propos de ce paramètre</h4>
        <ul class="space-y-2">
          <li v-for="info in infos" :key="info" class="flex items-start gap-2.5 text-sm text-gray-500">
            <svg class="w-4 h-4 text-gray-300 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
            </svg>
            {{ info }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const formOpen = ref(false);
const saving   = ref(false);
const saved    = ref(false);

const infos = [
  'Lorsque fermé, le formulaire public affiche un message bilingue (FR/AR).',
  'Le changement est immédiat et ne nécessite pas de redéploiement.',
  'Les contributions déjà soumises ne sont pas affectées.',
];

async function toggle() {
  if (saving.value) return;
  saving.value = true;
  const newVal = !formOpen.value;
  try {
    const res = await fetch('/admin/settings', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content },
      body: JSON.stringify({ form_status: newVal ? 'open' : 'closed' }),
    });
    if (res.ok) {
      formOpen.value = newVal;
      saved.value = true;
      setTimeout(() => saved.value = false, 3000);
    }
  } finally { saving.value = false; }
}

onMounted(async () => {
  const res  = await fetch('/admin/settings', { headers: { 'Accept': 'application/json' } });
  const data = await res.json();
  formOpen.value = data.form_status === 'open';
});
</script>
