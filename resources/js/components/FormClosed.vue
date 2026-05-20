<template>
  <div class="min-h-screen hero-gradient flex items-center justify-center px-4 py-16 relative overflow-hidden">
    <!-- Decorative -->
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white/5 -translate-y-1/3 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-red-900/25 translate-y-1/3 -translate-x-1/4 pointer-events-none"></div>

    <div class="relative max-w-lg w-full text-center">
      <!-- Logo -->
      <div class="flex justify-center mb-8">
        <div class="bg-white rounded-2xl p-3 shadow-xl shadow-black/30 inline-flex">
          <img :src="'/assets/logociriv.png'" alt="IRIV Congress" class="h-14 w-14 object-contain" />
        </div>
      </div>

      <!-- Card -->
      <div class="glass-card rounded-3xl border border-white/15 shadow-2xl p-10">
        <!-- Icon -->
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-600/20 border border-red-500/30 mb-7 mx-auto">
          <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>

        <!-- French message -->
        <h1 class="font-playfair text-2xl font-bold text-white mb-3">
          Formulaire fermé
        </h1>
        <p class="text-white/75 text-base leading-relaxed mb-7">
          Le formulaire de soumission est actuellement fermé. Merci pour votre intérêt.
        </p>

        <!-- Divider with IRIV accent -->
        <div class="flex items-center gap-3 mb-7">
          <div class="flex-1 h-px bg-white/15"></div>
          <div class="w-1.5 h-1.5 rounded-full bg-red-400"></div>
          <div class="flex-1 h-px bg-white/15"></div>
        </div>

        <!-- Arabic message -->
        <p class="text-white/70 text-base leading-relaxed font-medium" dir="rtl" lang="ar"
          style="font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
          تم إغلاق استمارة إرسال المساهمات حالياً. شكراً لاهتمامكم.
        </p>

        <!-- Back to home -->
        <router-link to="/"
          class="mt-8 inline-flex items-center gap-2 px-7 py-3.5 bg-white/15 hover:bg-white/25 border border-white/25 text-white rounded-xl font-semibold text-sm transition-all duration-200 hover:scale-105">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Retour à l'accueil
        </router-link>
      </div>

      <p class="mt-6 text-white/40 text-xs">
        © {{ new Date().getFullYear() }} IRIV Congress
      </p>
    </div>
  </div>
</template>

<script setup>
</script>
