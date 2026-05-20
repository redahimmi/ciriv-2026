<template>
  <div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-xl font-bold text-gray-900">Participants</h1>
        <p class="text-sm text-gray-500 mt-0.5">Gérez la liste des participants et envoyez-leur des emails.</p>
      </div>
      <button @click="showAddModal = true"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-90 active:scale-95"
        style="background:linear-gradient(135deg,#006B4F,#0D9E72)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter
      </button>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row gap-3">
      <input v-model="search" placeholder="Rechercher par nom ou email…"
        class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#006B4F]/30 focus:border-[#006B4F]" />
      <!-- Invitation template button: always visible -->
      <button @click="openInvitation"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 whitespace-nowrap"
        style="background:linear-gradient(135deg,#006B4F,#0D9E72)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Envoyer invitation
      </button>
      <button v-if="selected.length" @click="showEmailModal = true"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
        style="background:linear-gradient(135deg,#1d4ed8,#3b82f6)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Envoyer email ({{ selected.length }})
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div v-if="loading" class="flex items-center justify-center py-16 text-gray-400 text-sm">Chargement…</div>
      <div v-else-if="filtered.length === 0" class="flex flex-col items-center justify-center py-16 text-gray-400">
        <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <p class="font-medium">Aucun participant trouvé</p>
      </div>
      <table v-else class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-100 bg-gray-50/60">
            <th class="px-4 py-3 text-left">
              <input type="checkbox" :checked="allSelected" @change="toggleAll"
                class="rounded border-gray-300 text-[#006B4F] focus:ring-[#006B4F]" />
            </th>
            <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase text-xs tracking-wide">Nom</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase text-xs tracking-wide">Email</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase text-xs tracking-wide hidden sm:table-cell">Institution</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase text-xs tracking-wide hidden md:table-cell">Ajouté le</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="p in filtered" :key="p.id"
            class="hover:bg-gray-50/50 transition-colors"
            :class="selected.includes(p.id) ? 'bg-blue-50/40' : ''">
            <td class="px-4 py-3">
              <input type="checkbox" :value="p.id" v-model="selected"
                class="rounded border-gray-300 text-[#006B4F] focus:ring-[#006B4F]" />
            </td>
            <td class="px-4 py-3 font-medium text-gray-900">{{ p.name || '—' }}</td>
            <td class="px-4 py-3 text-gray-600">{{ p.email }}</td>
            <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">{{ p.institution || '—' }}</td>
            <td class="px-4 py-3 text-gray-400 hidden md:table-cell">{{ formatDate(p.created_at) }}</td>
            <td class="px-4 py-3 text-right">
              <button @click="deleteParticipant(p)"
                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Count -->
    <p v-if="!loading" class="text-xs text-gray-400">
      {{ filtered.length }} participant(s) affiché(s) · {{ participants.length }} au total
    </p>

    <!-- ── ADD MODAL ── -->
    <Teleport to="body">
      <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background:rgba(0,0,0,0.35);backdrop-filter:blur(4px)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-7 space-y-5">
          <div class="flex items-center justify-between">
            <h2 class="font-bold text-gray-900 text-lg">Ajouter un participant</h2>
            <button @click="closeAdd" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Duplicate alert -->
          <div v-if="addError" class="flex items-start gap-2.5 px-4 py-3 rounded-xl text-sm"
            :class="isDuplicate ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200'">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            {{ addError }}
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
              <input v-model="addForm.email" type="email" placeholder="exemple@domaine.com"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#006B4F]/30 focus:border-[#006B4F]"
                :class="addError ? 'border-red-300' : ''" />
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom</label>
                <input v-model="addForm.name" placeholder="Nom complet"
                  class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#006B4F]/30 focus:border-[#006B4F]" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Institution</label>
                <input v-model="addForm.institution" placeholder="Université…"
                  class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#006B4F]/30 focus:border-[#006B4F]" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes</label>
              <textarea v-model="addForm.notes" rows="2" placeholder="Informations complémentaires…"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#006B4F]/30 focus:border-[#006B4F] resize-none"></textarea>
            </div>
          </div>

          <div class="flex gap-3 pt-1">
            <button @click="closeAdd"
              class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
              Annuler
            </button>
            <button @click="submitAdd" :disabled="addLoading"
              class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
              style="background:linear-gradient(135deg,#006B4F,#0D9E72)">
              {{ addLoading ? 'Ajout…' : 'Ajouter' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- ── SEND EMAIL MODAL ── -->
    <Teleport to="body">
      <div v-if="showEmailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background:rgba(0,0,0,0.35);backdrop-filter:blur(4px)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-7 space-y-5">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="font-bold text-gray-900 text-lg">Envoyer un email</h2>
              <p class="text-sm text-gray-500 mt-0.5">À {{ selected.length }} participant(s) sélectionné(s)</p>
            </div>
            <button @click="closeEmail" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Success feedback -->
          <div v-if="emailResult" class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
            </svg>
            {{ emailResult.sent }} / {{ emailResult.total }} emails envoyés avec succès.
          </div>

          <div v-else class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Objet <span class="text-red-500">*</span></label>
              <input v-model="emailForm.subject" placeholder="Objet de l'email…"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
              <textarea v-model="emailForm.body" rows="7" placeholder="Rédigez votre message ici…"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 resize-none"></textarea>
            </div>
          </div>

          <div v-if="!emailResult" class="flex gap-3 pt-1">
            <button @click="closeEmail"
              class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
              Annuler
            </button>
            <button @click="submitEmail" :disabled="emailLoading || !emailForm.subject || !emailForm.body"
              class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
              style="background:linear-gradient(135deg,#1d4ed8,#3b82f6)">
              {{ emailLoading ? 'Envoi…' : 'Envoyer' }}
            </button>
          </div>
          <div v-else class="flex gap-3 pt-1">
            <button @click="closeEmail"
              class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
              Fermer
            </button>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const participants = ref([]);
const loading      = ref(true);
const search       = ref('');
const selected     = ref([]);

// Add modal
const showAddModal = ref(false);
const addForm      = ref({ email: '', name: '', institution: '', notes: '' });
const addLoading   = ref(false);
const addError     = ref('');
const isDuplicate  = ref(false);

// Email modal
const showEmailModal = ref(false);
const emailForm      = ref({ subject: '', body: '' });
const emailLoading   = ref(false);
const emailResult    = ref(null);

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;

const filtered = computed(() => {
  const q = search.value.toLowerCase();
  if (!q) return participants.value;
  return participants.value.filter(p =>
    p.email.toLowerCase().includes(q) ||
    (p.name || '').toLowerCase().includes(q) ||
    (p.institution || '').toLowerCase().includes(q)
  );
});

const allSelected = computed(() =>
  filtered.value.length > 0 && filtered.value.every(p => selected.value.includes(p.id))
);

function toggleAll() {
  if (allSelected.value) {
    selected.value = selected.value.filter(id => !filtered.value.find(p => p.id === id));
  } else {
    const ids = filtered.value.map(p => p.id);
    selected.value = [...new Set([...selected.value, ...ids])];
  }
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
}

async function load() {
  loading.value = true;
  const res = await fetch('/admin/participants', { headers: { Accept: 'application/json' } });
  participants.value = await res.json();
  loading.value = false;
}

async function deleteParticipant(p) {
  if (!confirm(`Supprimer ${p.email} ?`)) return;
  await fetch(`/admin/participants/${p.id}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': csrf(), Accept: 'application/json' },
  });
  participants.value = participants.value.filter(x => x.id !== p.id);
  selected.value = selected.value.filter(id => id !== p.id);
}

function closeAdd() {
  showAddModal.value = false;
  addForm.value = { email: '', name: '', institution: '', notes: '' };
  addError.value = '';
  isDuplicate.value = false;
}

async function submitAdd() {
  addError.value = '';
  isDuplicate.value = false;
  addLoading.value = true;
  try {
    const res = await fetch('/admin/participants', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), Accept: 'application/json' },
      body: JSON.stringify(addForm.value),
    });
    const data = await res.json();
    if (res.status === 409) {
      isDuplicate.value = true;
      addError.value = data.message;
    } else if (!res.ok) {
      addError.value = Object.values(data.errors || {})[0]?.[0] || 'Erreur inconnue.';
    } else {
      participants.value.unshift(data);
      closeAdd();
    }
  } finally {
    addLoading.value = false;
  }
}

function closeEmail() {
  showEmailModal.value = false;
  emailForm.value = { subject: '', body: '' };
  emailResult.value = null;
}

const SITE_URL = window.location.origin;

const INVITATION_TEMPLATE = {
  subject: 'Confirmation de votre participation — CIRIV 2026',
  body: `Bonjour,

Dans le cadre de votre participation au Congrès International de la Recherche, de l'Innovation et de la Valorisation, qui se tiendra en ligne le 22 mai 2026 à partir de 8h30, nous vous prions de bien vouloir confirmer votre présence en remplissant le formulaire disponible via le lien ci-dessous :

${SITE_URL}

Dès validation de votre inscription, vous recevrez automatiquement le lien de connexion Zoom pour suivre l'événement.

Cordialement,

Equipe d'organisation CIRIV.`,
};

function openInvitation() {
  // Select all participants
  selected.value = participants.value.map(p => p.id);
  // Pre-fill template
  emailForm.value = { ...INVITATION_TEMPLATE };
  emailResult.value = null;
  showEmailModal.value = true;
}

async function submitEmail() {
  emailLoading.value = true;
  try {
    const res = await fetch('/admin/participants/send-email', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), Accept: 'application/json' },
      body: JSON.stringify({ ids: selected.value, ...emailForm.value }),
    });
    emailResult.value = await res.json();
    selected.value = [];
  } finally {
    emailLoading.value = false;
  }
}

onMounted(load);
</script>
