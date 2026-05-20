<template>
  <div class="min-h-screen bg-[#F5F5F7] flex items-center justify-center px-4 relative overflow-hidden">
    <!-- Subtle background pattern -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.025]"
      style="background-image: radial-gradient(circle, #006B4F 1px, transparent 1px); background-size: 32px 32px;"></div>

    <div class="relative w-full max-w-sm">

      <!-- Logo above card -->
      <div class="flex flex-col items-center mb-8">
        <div class="bg-white rounded-2xl p-3 shadow-lg shadow-black/10 mb-4 ring-1 ring-black/5">
          <img :src="'/assets/logociriv.png'" alt="IRIV" class="h-14 w-14 object-contain" />
        </div>
        <h1 class="text-[22px] font-bold text-gray-900 tracking-tight">IRIV Backoffice</h1>
        <p class="text-sm text-gray-500 mt-1">Connectez-vous pour accéder à l'administration</p>
      </div>

      <!-- Card -->
      <div class="bg-white rounded-3xl shadow-xl shadow-black/8 ring-1 ring-black/5 p-8">

        <!-- Error -->
        <div v-if="error" class="mb-5 flex items-center gap-2.5 px-4 py-3 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700">
          <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          </svg>
          {{ error }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Email -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse e-mail</label>
            <div class="relative">
              <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <input v-model="form.email" type="email" placeholder="admin@iriv.ma" required
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:border-[#006B4F] focus:ring-2 focus:ring-[#006B4F]/15 transition-all" />
            </div>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mot de passe</label>
            <div class="relative">
              <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              <input v-model="form.password" :type="showPass ? 'text' : 'password'" placeholder="••••••••" required
                class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:border-[#006B4F] focus:ring-2 focus:ring-[#006B4F]/15 transition-all" />
              <button type="button" @click="showPass = !showPass"
                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-if="!showPass" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M3 3l18 18"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Submit -->
          <button type="submit" :disabled="loading"
            class="w-full mt-2 py-2.5 bg-[#006B4F] hover:bg-[#005a42] disabled:opacity-60 text-white rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-md hover:shadow-[#006B4F]/25">
            <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
            </svg>
            {{ loading ? 'Connexion…' : 'Se connecter' }}
          </button>
        </form>
      </div>

      <!-- Back link -->
      <div class="text-center mt-6">
        <router-link to="/" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Retour au site public
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
const error = ref('');
const loading = ref(false);
const showPass = ref(false);
const form = reactive({ email: '', password: '' });

async function handleLogin() {
  loading.value = true;
  error.value = '';
  try {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const res = await fetch('/admin/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify(form),
    });
    const data = await res.json();
    if (res.ok) router.push('/admin/dashboard');
    else error.value = data.message || 'Identifiants invalides.';
  } catch { error.value = 'Erreur de connexion. Réessayez.'; }
  finally { loading.value = false; }
}
</script>
