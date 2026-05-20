<template>
  <div class="lp-root" style="background:#f8fafc; min-height:100vh;">

    <!-- NAVBAR -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-700 ease-out"
      :class="scrolled ? 'py-1.5 mx-3 sm:mx-6 mt-3 rounded-2xl border border-gray-200 shadow-[0_8px_40px_rgba(0,0,0,0.10)]' : 'py-4'"
      :style="scrolled ? 'background:rgba(255,255,255,0.92);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);' : ''">

      <div class="max-w-7xl mx-auto px-5 sm:px-8 flex items-center justify-between">

        <!-- Brand -->
        <a href="#hero" class="flex items-center group">
          <img :src="'/assets/logowhiteciriv.png'" alt="CIRIV" class="h-10 sm:h-14 w-auto object-contain" />
        </a>

        <!-- Desktop nav -->
        <nav class="hidden md:flex items-center gap-1">
          <a v-for="link in navLinks" :key="link.href" :href="link.href"
            class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200">
            {{ link.label }}
          </a>
          <div class="w-px h-5 mx-3 bg-gray-200"></div>
          <template v-if="!formClosed">
            <router-link to="/soumettre"
              class="group overflow-hidden px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-300 hover:scale-105 hover:shadow-[0_4px_20px_rgba(0,107,79,0.5)] active:scale-95"
              style="background:linear-gradient(135deg,#006B4F,#0D9E72);">
              <span class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-white/70 animate-pulse"></span>
                Soumettre
              </span>
            </router-link>
          </template>
          <template v-else>
            <span class="px-5 py-2.5 rounded-xl text-sm font-semibold text-red-300 border cursor-default"
              style="border-color:rgba(198,40,40,0.3); background:rgba(198,40,40,0.10);">
              Soumissions fermées
            </span>
          </template>
        </nav>

        <!-- Mobile toggle -->
        <button @click="mobileOpen=!mobileOpen"
          class="md:hidden w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors"
          style="border:1px solid rgba(0,0,0,0.08);">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <transition name="slide-down">
        <div v-if="mobileOpen"
          class="md:hidden mx-3 mt-2 rounded-2xl border overflow-hidden"
          style="background:rgba(5,13,20,0.95); backdrop-filter:blur(24px); border-color:rgba(255,255,255,0.08);">
          <div class="p-5 flex flex-col gap-1">
            <a v-for="link in navLinks" :key="link.href" :href="link.href"
              @click="mobileOpen=false"
              class="text-white/55 font-medium py-2.5 px-3 rounded-xl text-sm hover:bg-white/5 hover:text-white transition-all duration-200">
              {{ link.label }}
            </a>
            <div class="h-px my-2" style="background:rgba(0,0,0,0.06)"></div>
            <router-link v-if="!formClosed" to="/soumettre" @click="mobileOpen=false"
              class="text-center px-5 py-3 rounded-xl font-semibold text-white text-sm"
              style="background:linear-gradient(135deg,#006B4F,#0D9E72)">
              Soumettre une contribution
            </router-link>
            <div v-else class="text-center px-5 py-3 rounded-xl font-semibold text-red-300 text-sm border"
              style="border-color:rgba(198,40,40,0.3); background:rgba(198,40,40,0.10)">
              Soumissions fermées
            </div>
          </div>
        </div>
      </transition>
    </header>

    <!-- HERO -->
    <section id="hero" class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden" style="z-index:1;">

      <!-- Premium animated scientific background -->
      <HeroBackground />

      <div class="relative z-10 flex flex-col items-center justify-center w-full px-4">
        <div class="relative group anim-fade-up" style="cursor:default;">

          <!-- Ambient conic glow ring -->
          <div class="absolute -inset-14 rounded-[4.5rem] blur-3xl opacity-10 group-hover:opacity-18 transition-opacity duration-700 animate-orb-1"
            style="background:conic-gradient(from 0deg,#006B4F,#C62828,#006B4F)"></div>

          <!-- Card — transparent, no border, logo floats freely -->
          <div class="relative rounded-[2.5rem] px-10 py-10 sm:px-16 sm:py-14 flex flex-col items-center gap-8"
            style="background:transparent;">

            <!-- Logo -->
            <img :src="'/assets/logowhiteciriv.png'" alt="CIRIV"
              class="h-40 w-auto sm:h-52 lg:h-64 object-contain
                     group-hover:scale-105 transition-transform duration-700" />

            <!-- Thin separator -->
            <div class="w-full h-px" style="background:linear-gradient(90deg,transparent,rgba(0,0,0,0.10),transparent);"></div>

            <!-- CTA buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full pointer-events-auto">

              <!-- Submit button (if open) -->
              <template v-if="!formClosed">
                <router-link to="/soumettre"
                  class="flex-1 w-full sm:w-auto flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl font-bold text-white text-sm transition-all duration-300 hover:scale-105 hover:brightness-110 active:scale-95"
                  style="background:linear-gradient(135deg,#006B4F,#0D9E72); box-shadow:0 6px 28px rgba(0,107,79,0.45);">
                  <span class="w-2 h-2 rounded-full bg-white/70 animate-pulse flex-shrink-0"></span>
                  Soumettre une contribution
                </router-link>
              </template>
              <template v-else>
                <div class="flex-1 w-full sm:w-auto flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl font-bold text-sm cursor-default"
                  style="background:rgba(198,40,40,0.12); border:1px solid rgba(198,40,40,0.30); color:#f87171;">
                  <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0"></span>
                  Soumissions fermées
                </div>
              </template>



            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- STATISTICS -->
    <section id="statistiques" class="py-20 relative" style="z-index:1;">
      <div class="absolute top-0 left-0 right-0 h-px"
        style="background:linear-gradient(90deg,transparent,rgba(0,107,79,0.4),rgba(198,40,40,0.3),transparent)"></div>
      <div class="max-w-6xl mx-auto px-4 sm:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
          <div v-for="(kpi,i) in kpis" :key="i"
            class="group relative rounded-3xl p-7 border border-gray-100 bg-white overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-md anim-fade-up"
            :style="{'transition-delay':(i*0.08)+'s'}">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl"
              :style="{background:'radial-gradient(ellipse at center,'+kpi.glow+'12 0%,transparent 70%)'}"></div>
            <div class="relative">
              <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4 text-xl"
                :style="{background:kpi.glow+'16'}">{{ kpi.icon }}</div>
              <div class="text-3xl sm:text-4xl font-black text-gray-900 mb-1">{{ kpi.value }}</div>
              <div class="text-xs text-gray-500 font-medium leading-snug">{{ kpi.label }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="absolute bottom-0 left-0 right-0 h-px"
        style="background:linear-gradient(90deg,transparent,rgba(198,40,40,0.3),rgba(0,107,79,0.4),transparent)"></div>
    </section>

    <!-- SESSIONS -->
    <section id="sessions" class="py-28 relative" style="z-index:1;background:#f1f5f9;">
      <div class="absolute inset-0 pointer-events-none"
        style="background:radial-gradient(ellipse 70% 50% at 50% 100%,rgba(0,107,79,0.09) 0%,transparent 60%)"></div>
      <div class="max-w-6xl mx-auto px-4 sm:px-8 relative z-10">
        <div class="text-center mb-16 anim-fade-up">
          <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 text-xs font-bold tracking-[0.2em] uppercase mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Sessions scientifiques
          </div>
          <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight mb-4">Domaines thématiques</h2>
          <p class="text-gray-500 text-lg max-w-xl mx-auto">Six grandes thématiques pour accueillir vos contributions de recherche.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <div v-for="(ses,i) in sessions" :key="i"
            class="group relative rounded-3xl p-7 border border-gray-100 bg-white overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-md anim-fade-up">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl"
              :style="{background:'radial-gradient(ellipse at top left,'+ses.color+'22 0%,transparent 60%)'}"></div>
            <div class="relative">
              <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-5 text-2xl"
                :style="{background:ses.color+'18'}">{{ ses.icon }}</div>
              <h3 class="text-base font-bold text-gray-800 mb-2 leading-snug">{{ ses.title }}</h3>
              <p class="text-sm text-gray-500 leading-relaxed">{{ ses.desc }}</p>
              <div class="mt-6 h-0.5 rounded-full opacity-25"
                :style="{background:ses.color}"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PROCESS -->
    <section id="processus" class="py-28 relative" style="z-index:1;">
      <div class="absolute inset-0 pointer-events-none"
        style="background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(198,40,40,0.07) 0%,transparent 55%)"></div>
      <div class="max-w-5xl mx-auto px-4 sm:px-8 relative z-10">
        <div class="text-center mb-16 anim-fade-up">
          <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-red-200 bg-red-50 text-red-600 text-xs font-bold tracking-[0.2em] uppercase mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
            Guide de soumission
          </div>
          <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight mb-4">Comment participer</h2>
          <p class="text-gray-500 text-lg max-w-xl mx-auto">Un processus simple et entièrement en ligne pour soumettre vos travaux.</p>
        </div>
        <div class="relative">
          <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-px -translate-y-1/2 pointer-events-none bg-gray-200"></div>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">
            <div v-for="(step,i) in steps" :key="i"
              class="group relative rounded-3xl overflow-hidden border border-gray-100 bg-white transition-all duration-500 hover:-translate-y-2 hover:shadow-lg anim-fade-up"
              :style="{'transition-delay':(i*0.12)+'s'}">
              <div class="h-1.5 w-full" :style="{background:step.grad}"></div>
              <div class="p-7">
                <div class="flex items-center gap-4 mb-6">
                  <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-black text-lg text-white"
                    :style="{background:step.grad}">{{ i+1 }}</div>
                  <h3 class="font-bold text-gray-800 text-base leading-snug">{{ step.title }}</h3>
                </div>
                <ul class="space-y-2.5">
                  <li v-for="(item,j) in step.items" :key="j" class="flex items-start gap-3 text-sm text-gray-500">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" :style="{color:step.glow}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ item }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- FOOTER -->
    <footer class="relative pt-20 pb-10" style="z-index:1;background:#f1f5f9;">
      <div class="absolute top-0 left-0 right-0 h-px"
        style="background:linear-gradient(90deg,transparent,rgba(0,107,79,0.45),rgba(198,40,40,0.35),transparent)"></div>
      <div class="absolute inset-0 pointer-events-none"
        style="background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(0,107,79,0.06) 0%,transparent 55%)"></div>
      <div class="max-w-6xl mx-auto px-4 sm:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-14 border-b border-gray-200">

          <!-- Brand column -->
          <div class="space-y-6">
            <div class="flex items-center">
              <img :src="'/assets/logowhiteciriv.png'" alt="CIRIV" class="h-20 w-auto object-contain" />
            </div>
            <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
              Congrès International sur la Recherche, l'Innovation et la Valorisation. Plateforme de rencontre entre chercheurs du monde entier.
            </p>
            <div class="flex items-center gap-2.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span class="text-xs text-emerald-600 font-semibold">Soumissions en cours</span>
            </div>
          </div>

          <!-- Navigation -->
          <div>
            <div class="text-xs font-bold tracking-[0.2em] uppercase text-gray-400 mb-6">Navigation</div>
            <ul class="space-y-3.5">
              <li v-for="link in navLinks" :key="link.href">
                <a :href="link.href" class="group flex items-center gap-2.5 text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                  <span class="w-1 h-1 rounded-full bg-gray-300 group-hover:bg-emerald-500 transition-colors duration-200"></span>
                  {{ link.label }}
                </a>
              </li>
            </ul>
          </div>

          <!-- Info -->
          <div>
            <div class="text-xs font-bold tracking-[0.2em] uppercase text-gray-400 mb-6">Informations</div>
            <ul class="space-y-3.5 text-sm text-gray-600">
              <li class="flex items-start gap-2.5">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-emerald-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Édition 2026</span>
              </li>
              <li class="flex items-start gap-2.5">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-emerald-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                </svg>
                <span>Événement international en ligne</span>
              </li>
              <li class="flex items-start gap-2.5">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-emerald-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>6 sessions thématiques</span>
              </li>
              <li class="flex items-start gap-2.5">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-emerald-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>contact@ciriv.org</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Bottom bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p class="text-xs text-gray-400">© 2026 CIRIV — Tous droits réservés</p>
          <p class="text-xs text-gray-400">
            Développé par
            <a href="https://ascrea.dz" target="_blank" rel="noopener"
              class="text-gray-500 hover:text-emerald-600 transition-colors duration-200 font-medium">Ascrea</a>
          </p>
        </div>
      </div>
    </footer>

    <!-- MODAL -->
    <Transition name="modal-fade">
      <div v-if="showModal" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:100;">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="showModal=false"></div>
        <div class="relative w-full max-w-md rounded-3xl overflow-hidden border border-gray-200 modal-in"
          style="background:rgba(255,255,255,0.98);backdrop-filter:blur(40px);box-shadow:0 24px 80px rgba(0,0,0,0.15),0 2px 12px rgba(0,0,0,0.07);">
          <div class="absolute top-0 left-0 right-0 h-px"
            style="background:linear-gradient(90deg,transparent,rgba(0,107,79,0.6),rgba(198,40,40,0.4),transparent)"></div>
          <div class="p-8">
            <div class="flex items-center justify-between mb-8">
              <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0"
                  style="background:#f1f5f9; border:1px solid rgba(0,0,0,0.07);">
                  <img :src="'/assets/logodarkciriv.png'" alt="CIRIV" class="w-9 h-9 object-contain" />
                </div>
                <div>
                  <div class="font-black text-gray-900 text-lg">CIRIV 2026</div>
                  <div class="text-xs text-gray-400">Accès au congrès</div>
                </div>
              </div>
              <button @click="showModal=false"
                class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 hover:text-gray-800 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <div class="space-y-4">
              <a :href="formClosed ? '#' : '/participant/register'" :class="formClosed ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] cursor-pointer'"
                class="group flex items-center gap-5 p-5 rounded-2xl border transition-all duration-300"
                style="background:rgba(0,107,79,0.07);border-color:rgba(0,107,79,0.3);">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-xl"
                  style="background:rgba(0,107,79,0.2)">🎓</div>
                <div class="flex-1">
                  <div class="font-bold text-gray-800 text-base">Espace Participant</div>
                  <div class="text-xs text-gray-500 mt-0.5">{{ formClosed ? 'Soumissions fermées' : 'Soumettre une contribution' }}</div>
                </div>
                <svg v-if="!formClosed" class="w-5 h-5 text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>

            </div>
          </div>
        </div>
      </div>
    </Transition>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import HeroBackground from './HeroBackground.vue'

const scrolled   = ref(false)
const mobileOpen = ref(false)
const formClosed = ref(false)
const showModal  = ref(false)

const navLinks = [
  { href: '#hero',      label: 'Accueil'   },
  { href: '#sessions',  label: 'Sessions'  },
  { href: '#processus', label: 'Processus' },
]

const stats = [
  { display: '6',    label: 'Sessions thématiques' },
  { display: '2',    label: 'Types de contribution' },
  { display: '100%', label: 'En ligne' },
]

const kpis = [
  { value: '6',    label: 'Sessions scientifiques',    icon: '🎯', glow: '#006B4F' },
  { value: '2',    label: 'Formats de contribution',   icon: '��', glow: '#C62828' },
  { value: '100%', label: 'Processus dématérialisé',   icon: '☁️',  glow: '#4ade80' },
  { value: '24h',  label: 'Confirmation de réception', icon: '⚡', glow: '#f59e0b' },
]

const sessions = [
  { title: 'Systèmes & sécurité alimentaire', color: '#d97706', icon: '🌾', desc: 'Agro-innovation, chaînes d\'approvisionnement, sécurité alimentaire et valorisation des ressources agricoles.' },
  { title: 'Technologies émergentes, données et santé', color: '#006B4F', icon: '🔬', desc: 'Intelligence artificielle, big data, transformation numérique et leurs applications en santé.' },
  { title: 'Interfaces biologiques, émergences sanitaires et dynamiques de transmission', color: '#C62828', icon: '🧬', desc: 'Maladies émergentes, interactions hôte-pathogène, épidémiologie et prévention des risques sanitaires.' },
  { title: 'Bien-être, durabilité et résilience des systèmes vivants', color: '#059669', icon: '🌿', desc: 'Santé environnementale, développement durable, adaptation climatique et qualité de vie.' },
  { title: 'Présentations orales', color: '#0891b2', icon: '🎤', desc: 'Communications orales de recherche présentées par les participants devant le comité scientifique.' },
  { title: 'Session inaugurale / Officiels', color: '#7c3aed', icon: '🏛️', desc: 'Séance d\'ouverture, allocutions officielles et interventions des invités d\'honneur du congrès.' },
]

const steps = [
  {
    title: 'Informations personnelles',
    grad: 'linear-gradient(135deg,#006B4F,#0D9E72)',
    glow: '#006B4F',
    items: ['Nom et prénom complet', 'Institution ou université', 'Pays et contact email', 'Domaine de recherche'],
  },
  {
    title: 'Contribution scientifique',
    grad: 'linear-gradient(135deg,#C62828,#ef4444)',
    glow: '#C62828',
    items: ['Titre de la contribution', 'Résumé (300 mots max)', 'Choix de la session', 'Fichier complet (PDF/DOCX)'],
  },
  {
    title: 'Validation & confirmation',
    grad: 'linear-gradient(135deg,#334155,#475569)',
    glow: '#94a3b8',
    items: ['Révision par le comité', 'Confirmation sous 24h', 'Instructions de présentation', "Accès à l'espace personnel"],
  },
]



function initAnimations() {
  const els = document.querySelectorAll('.anim-fade-up')
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target) } })
  }, { threshold: 0.12 })
  els.forEach(el => io.observe(el))
}

async function checkFormStatus() {
  try {
    const r = await fetch('/api/form-status')
    if (r.ok) { const d = await r.json(); formClosed.value = d.closed }
  } catch {}
}

function onScroll() {
  scrolled.value = window.scrollY > 60
}

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true })
  initAnimations()
  checkFormStatus()
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
})
</script>

<style scoped>
.anim-fade-up {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.anim-fade-up.is-visible {
  opacity: 1;
  transform: translateY(0);
}

@keyframes orbFloat1 {
  0%, 100% { transform: translateY(0) scale(1); }
  50%       { transform: translateY(-28px) scale(1.06); }
}
@keyframes orbFloat2 {
  0%, 100% { transform: translateY(0) scale(1); }
  50%       { transform: translateY(22px) scale(0.94); }
}
.animate-orb-1 { animation: orbFloat1 9s ease-in-out infinite; }
.animate-orb-2 { animation: orbFloat2 12s ease-in-out infinite; }

@keyframes scrollLineKf {
  0%   { transform: translateY(-100%); }
  100% { transform: translateY(200%); }
}
.animate-scroll-line { animation: scrollLineKf 1.8s ease-in-out infinite; }

/* Slide-down for mobile drawer */
.slide-down-enter-active,
.slide-down-leave-active { transition: max-height 0.35s ease, opacity 0.25s ease; max-height: 400px; overflow: hidden; }
.slide-down-enter-from,
.slide-down-leave-to   { max-height: 0; opacity: 0; }

/* Modal fade */
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.25s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to     { opacity: 0; }

@keyframes modalInKf {
  from { transform: scale(0.93) translateY(16px); opacity: 0; }
  to   { transform: scale(1) translateY(0);       opacity: 1; }
}
.modal-in { animation: modalInKf 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards; }
</style>
