<template>
  <FormClosed v-if="formClosed" />
  <div v-else class="min-h-screen bg-gray-50">
    <!-- Top bar -->
    <div class="hero-gradient py-6 px-4 sm:px-6">
      <div class="max-w-3xl mx-auto flex items-center justify-between">
        <router-link to="/" class="flex items-center gap-3 group">
          <svg class="w-5 h-5 text-white/70 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          <img :src="'/assets/logodarkciriv.png'" alt="CIRIV" class="h-10 w-auto max-w-[120px] object-contain flex-shrink-0" />
        </router-link>
        <div class="text-white/80 text-sm font-medium">Soumission de contribution</div>
      </div>
    </div>

    <!-- Progress indicator -->
    <div class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center gap-4">
          <template v-for="(stepLabel, idx) in steps" :key="idx">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300"
                :class="{
                  'bg-green-700 text-white shadow-md shadow-green-200': currentStep > idx + 1 || (submitted && idx < 2),
                  'bg-green-700 text-white ring-4 ring-green-100': currentStep === idx + 1 && !submitted,
                  'bg-gray-100 text-gray-400': currentStep < idx + 1 && !submitted,
                }">
                <svg v-if="currentStep > idx + 1 || submitted" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span v-else>{{ idx + 1 }}</span>
              </div>
              <span class="hidden sm:block text-sm font-medium transition-colors duration-300"
                :class="currentStep === idx + 1 ? 'text-green-700' : 'text-gray-400'">
                {{ stepLabel }}
              </span>
            </div>
            <div v-if="idx < steps.length - 1" class="flex-1 h-0.5 rounded transition-all duration-500"
              :class="currentStep > idx + 1 ? 'bg-green-600' : 'bg-gray-200'">
            </div>
          </template>
        </div>
      </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

      <!-- SUCCESS STATE -->
      <transition name="scale-up">
        <div v-if="submitted" class="text-center py-20 animate-fade-in">
          <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 mb-8">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h2 class="font-playfair text-3xl font-bold text-gray-900 mb-4">Contribution soumise !</h2>
          <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            Votre contribution a été soumise avec succès. Vous recevrez une confirmation prochainement.
          </p>
          <div class="inline-block bg-green-50 border border-green-200 rounded-2xl px-8 py-5 mb-8">
            <p class="text-green-800 font-semibold">✓ Votre contribution a été soumise avec succès.</p>
          </div>
          <div class="flex flex-wrap justify-center gap-4">
            <router-link to="/"
              class="px-8 py-3 bg-green-700 hover:bg-green-800 text-white rounded-xl font-semibold transition-all duration-200 hover:scale-105 shadow-md">
              Retour à l'accueil
            </router-link>
            <button @click="resetForm"
              class="px-8 py-3 bg-white border-2 border-green-700 text-green-700 rounded-xl font-semibold hover:bg-green-50 transition-all duration-200 hover:scale-105">
              Nouvelle soumission
            </button>
          </div>
        </div>
      </transition>

      <!-- FORM -->
      <form v-if="!submitted" @submit.prevent="handleSubmit" novalidate>

        <!-- PHASE 1 -->
        <transition name="fade-slide">
          <div v-if="currentStep === 1" key="step1" class="animate-fade-in-up">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
              <!-- Phase header -->
              <div class="flex items-center gap-4 p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
                <div class="w-12 h-12 rounded-2xl bg-green-700 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">1</div>
                <div>
                  <h2 class="text-xl font-bold text-gray-900">Informations personnelles</h2>
                  <p class="text-sm text-gray-500">Renseignez vos coordonnées et choisissez une session</p>
                </div>
              </div>

              <div class="p-6 sm:p-8 space-y-6">
                <!-- Nom & Prénom -->
                <div class="grid sm:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Nom <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.nom"
                      type="text"
                      placeholder="Votre nom de famille"
                      class="w-full px-4 py-3 rounded-xl border bg-gray-50 text-gray-900 placeholder-gray-400 text-sm input-focus transition-all"
                      :class="errors.nom ? 'border-red-400 bg-red-50' : 'border-gray-200 focus:bg-white'"
                    />
                    <p v-if="errors.nom" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errors.nom[0] }}
                    </p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Prénom <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.prenom"
                      type="text"
                      placeholder="Votre prénom"
                      class="w-full px-4 py-3 rounded-xl border bg-gray-50 text-gray-900 placeholder-gray-400 text-sm input-focus transition-all"
                      :class="errors.prenom ? 'border-red-400 bg-red-50' : 'border-gray-200 focus:bg-white'"
                    />
                    <p v-if="errors.prenom" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errors.prenom[0] }}
                    </p>
                  </div>
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Adresse e-mail <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input
                      v-model="form.email"
                      type="email"
                      placeholder="exemple@domaine.com"
                      class="w-full pl-10 pr-4 py-3 rounded-xl border bg-gray-50 text-gray-900 placeholder-gray-400 text-sm input-focus transition-all"
                      :class="errors.email ? 'border-red-400 bg-red-50' : 'border-gray-200 focus:bg-white'"
                    />
                  </div>
                  <p v-if="errors.email" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ errors.email[0] }}
                  </p>
                </div>

                <!-- Session -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Session <span class="text-red-500">*</span>
                  </label>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label v-for="session in sessions" :key="session"
                      class="relative flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:border-green-400 hover:bg-green-50 group"
                      :class="form.session === session
                        ? 'border-green-600 bg-green-50 shadow-sm shadow-green-100'
                        : 'border-gray-200 bg-white'">
                      <input type="radio" :value="session" v-model="form.session" class="sr-only"/>
                      <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-200 flex-shrink-0"
                        :class="form.session === session ? 'border-green-600 bg-green-600' : 'border-gray-300 bg-white group-hover:border-green-400'">
                        <div v-if="form.session === session" class="w-2 h-2 rounded-full bg-white"></div>
                      </div>
                      <span class="text-sm font-medium leading-snug"
                        :class="form.session === session ? 'text-green-800' : 'text-gray-700'">
                        {{ session }}
                      </span>
                    </label>
                  </div>
                  <p v-if="errors.session" class="mt-2 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ errors.session[0] }}
                  </p>
                </div>
              </div>

              <div class="px-6 sm:px-8 pb-8 flex justify-end">
                <button type="button" @click="goToStep2"
                  class="group inline-flex items-center gap-2 px-8 py-3.5 bg-green-700 hover:bg-green-800 text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 active:scale-95">
                  Continuer
                  <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </transition>

        <!-- PHASE 2 -->
        <transition name="fade-slide">
          <div v-if="currentStep === 2" key="step2" class="animate-fade-in-up">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
              <!-- Phase header -->
              <div class="flex items-center gap-4 p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white">
                <div class="w-12 h-12 rounded-2xl bg-red-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">2</div>
                <div>
                  <h2 class="text-xl font-bold text-gray-900">Type de contribution & pièces jointes</h2>
                  <p class="text-sm text-gray-500">Sélectionnez le type et joignez les fichiers requis</p>
                </div>
              </div>

              <div class="p-6 sm:p-8 space-y-7">

                <!-- Contribution type -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Type de contribution <span class="text-red-500">*</span>
                  </label>
                  <div class="grid sm:grid-cols-2 gap-4">
                    <!-- Poster -->
                    <label
                      class="relative flex flex-col gap-3 p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 hover:border-green-400 group"
                      :class="form.contribution_type === 'Poster'
                        ? 'border-green-600 bg-green-50 shadow-md shadow-green-100'
                        : 'border-gray-200 bg-white hover:bg-green-50/50'">
                      <input type="radio" value="Poster" v-model="form.contribution_type" class="sr-only"/>
                      <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200"
                          :class="form.contribution_type === 'Poster' ? 'bg-green-700' : 'bg-gray-100 group-hover:bg-green-100'">
                          <svg class="w-5 h-5" :class="form.contribution_type === 'Poster' ? 'text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                          </svg>
                        </div>
                        <div>
                          <div class="font-semibold text-sm" :class="form.contribution_type === 'Poster' ? 'text-green-800' : 'text-gray-800'">Poster</div>
                          <div class="text-xs text-gray-500">Présentation visuelle</div>
                        </div>
                        <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
                          :class="form.contribution_type === 'Poster' ? 'border-green-600 bg-green-600' : 'border-gray-300'">
                          <div v-if="form.contribution_type === 'Poster'" class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                      </div>
                    </label>

                    <!-- Communication orale -->
                    <label
                      class="relative flex flex-col gap-3 p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 hover:border-red-400 group"
                      :class="form.contribution_type === 'Communication orale'
                        ? 'border-red-500 bg-red-50 shadow-md shadow-red-100'
                        : 'border-gray-200 bg-white hover:bg-red-50/50'">
                      <input type="radio" value="Communication orale" v-model="form.contribution_type" class="sr-only"/>
                      <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200"
                          :class="form.contribution_type === 'Communication orale' ? 'bg-red-600' : 'bg-gray-100 group-hover:bg-red-100'">
                          <svg class="w-5 h-5" :class="form.contribution_type === 'Communication orale' ? 'text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                          </svg>
                        </div>
                        <div>
                          <div class="font-semibold text-sm" :class="form.contribution_type === 'Communication orale' ? 'text-red-800' : 'text-gray-800'">Communication orale</div>
                          <div class="text-xs text-gray-500">Présentation verbale</div>
                        </div>
                        <div class="ml-auto w-5 h-5 rounded-full border-2 flex items-center justify-center"
                          :class="form.contribution_type === 'Communication orale' ? 'border-red-500 bg-red-500' : 'border-gray-300'">
                          <div v-if="form.contribution_type === 'Communication orale'" class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                      </div>
                    </label>
                  </div>
                  <p v-if="errors.contribution_type" class="mt-2 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ errors.contribution_type[0] }}
                  </p>
                </div>

                <!-- Poster upload (conditional) -->
                <transition name="expand">
                  <div v-if="form.contribution_type === 'Poster'">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                      Joindre le poster <span class="text-red-500">*</span>
                      <span class="ml-2 text-xs font-normal text-gray-400">jpg, jpeg, png, pdf — max 5 Mo</span>
                    </label>
                    <FileDropZone
                      :error="errors.poster_file"
                      accept=".jpg,.jpeg,.png,.pdf"
                      color="green"
                      @file-selected="(f) => form.poster_file = f"
                    />
                    <p v-if="errors.poster_file" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                      <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errors.poster_file[0] }}
                    </p>
                  </div>
                </transition>

                <!-- Payment status (conditional for Communication orale) -->
                <transition name="expand">
                  <div v-if="form.contribution_type === 'Communication orale'" class="space-y-6">
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Statut de paiement <span class="text-red-500">*</span>
                      </label>
                      <div class="flex flex-wrap gap-4">
                        <label v-for="status in paymentStatuses" :key="status"
                          class="flex items-center gap-3 px-5 py-3.5 rounded-xl border-2 cursor-pointer transition-all duration-200"
                          :class="form.payment_status === status
                            ? 'border-green-600 bg-green-50 shadow-sm'
                            : 'border-gray-200 bg-white hover:border-green-300 hover:bg-green-50/30'">
                          <input type="radio" :value="status" v-model="form.payment_status" class="sr-only"/>
                          <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                            :class="form.payment_status === status ? 'border-green-600 bg-green-600' : 'border-gray-300'">
                            <div v-if="form.payment_status === status" class="w-2 h-2 rounded-full bg-white"></div>
                          </div>
                          <span class="text-sm font-medium" :class="form.payment_status === status ? 'text-green-800' : 'text-gray-700'">
                            {{ status }}
                          </span>
                        </label>
                      </div>
                      <p v-if="errors.payment_status" class="mt-2 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ errors.payment_status[0] }}
                      </p>
                    </div>

                    <!-- Receipt upload (conditional for Oui) -->
                    <transition name="expand">
                      <div v-if="form.payment_status === 'Oui'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                          Joindre reçu de paiement <span class="text-red-500">*</span>
                          <span class="ml-2 text-xs font-normal text-gray-400">jpg, jpeg, png, pdf — max 5 Mo</span>
                        </label>
                        <FileDropZone
                          :error="errors.payment_receipt"
                          accept=".jpg,.jpeg,.png,.pdf"
                          color="red"
                          @file-selected="(f) => form.payment_receipt = f"
                        />
                        <p v-if="errors.payment_receipt" class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                          {{ errors.payment_receipt[0] }}
                        </p>
                      </div>
                    </transition>
                  </div>
                </transition>

                <!-- General error -->
                <div v-if="generalError" class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                  <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ generalError }}
                </div>
              </div>

              <!-- Form actions -->
              <div class="px-6 sm:px-8 pb-8 flex items-center justify-between gap-4">
                <button type="button" @click="currentStep = 1"
                  class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                  </svg>
                  Retour
                </button>
                <button type="submit" :disabled="submitting"
                  class="group inline-flex items-center gap-2 px-8 py-3.5 bg-green-700 hover:bg-green-800 disabled:bg-green-400 text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 active:scale-95 disabled:cursor-not-allowed disabled:scale-100">
                  <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                  </svg>
                  <svg v-else class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  {{ submitting ? 'Envoi en cours...' : 'Soumettre la contribution' }}
                </button>
              </div>
            </div>
          </div>
        </transition>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue';
import FileDropZone from './FileDropZone.vue';
import FormClosed from './FormClosed.vue';

const formClosed = ref(false);
onMounted(async () => {
  try {
    const res = await fetch('/api/form-status');
    const data = await res.json();
    formClosed.value = data.form_status === 'closed';
  } catch {}
});

const steps = ['Informations personnelles', 'Type & pièces jointes'];
const sessions = [
  'Systèmes & sécurité alimentaire',
  'Technologies émergentes, données et santé',
  'Interfaces biologiques, émergences sanitaires et dynamiques de transmission',
  'Bien-être, durabilité et résilience des systèmes vivants',
];
const paymentStatuses = ['Oui', 'Pas encore'];

const currentStep = ref(1);
const submitted = ref(false);
const submitting = ref(false);
const generalError = ref('');
const errors = ref({});

const form = reactive({
  nom: '',
  prenom: '',
  email: '',
  session: '',
  contribution_type: '',
  payment_status: '',
  poster_file: null,
  payment_receipt: null,
});

// Reset conditionals on type change
watch(() => form.contribution_type, () => {
  form.payment_status = '';
  form.poster_file = null;
  form.payment_receipt = null;
  errors.value = {};
});

watch(() => form.payment_status, () => {
  form.payment_receipt = null;
  if (errors.value.payment_receipt) delete errors.value.payment_receipt;
});

function goToStep2() {
  const step1Errors = {};
  if (!form.nom.trim()) step1Errors.nom = ['Le nom est obligatoire.'];
  if (!form.prenom.trim()) step1Errors.prenom = ['Le prénom est obligatoire.'];
  if (!form.email.trim()) {
    step1Errors.email = ['L\'adresse e-mail est obligatoire.'];
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    step1Errors.email = ['Veuillez entrer une adresse e-mail valide.'];
  }
  if (!form.session) step1Errors.session = ['Veuillez sélectionner une session.'];

  errors.value = step1Errors;
  if (Object.keys(step1Errors).length === 0) {
    currentStep.value = 2;
  }
}

async function handleSubmit() {
  submitting.value = true;
  generalError.value = '';
  errors.value = {};

  const fd = new FormData();
  fd.append('nom', form.nom);
  fd.append('prenom', form.prenom);
  fd.append('email', form.email);
  fd.append('session', form.session);
  fd.append('contribution_type', form.contribution_type);
  if (form.payment_status) fd.append('payment_status', form.payment_status);
  if (form.poster_file) fd.append('poster_file', form.poster_file);
  if (form.payment_receipt) fd.append('payment_receipt', form.payment_receipt);

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const res = await fetch('/contributions', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken || '',
        'Accept': 'application/json',
      },
      body: fd,
    });

    const data = await res.json();

    if (res.ok) {
      submitted.value = true;
    } else if (res.status === 422) {
      errors.value = data.errors || {};
      // If errors in step 1 fields, go back
      const step1Fields = ['nom', 'prenom', 'email', 'session'];
      if (step1Fields.some(f => errors.value[f])) {
        currentStep.value = 1;
      }
    } else {
      generalError.value = data.message || 'Une erreur est survenue. Veuillez réessayer.';
    }
  } catch (e) {
    generalError.value = 'Erreur de connexion. Veuillez vérifier votre connexion internet.';
  } finally {
    submitting.value = false;
  }
}

function resetForm() {
  Object.assign(form, {
    nom: '', prenom: '', email: '', session: '',
    contribution_type: '', payment_status: '',
    poster_file: null, payment_receipt: null,
  });
  errors.value = {};
  generalError.value = '';
  currentStep.value = 1;
  submitted.value = false;
}
</script>

<style scoped>
.fade-slide-enter-active, .fade-slide-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-slide-enter-from { opacity: 0; transform: translateX(20px); }
.fade-slide-leave-to   { opacity: 0; transform: translateX(-20px); }

.expand-enter-active, .expand-leave-active {
  transition: max-height 0.4s ease, opacity 0.3s ease;
  overflow: hidden;
  max-height: 400px;
}
.expand-enter-from, .expand-leave-to {
  max-height: 0;
  opacity: 0;
}

.scale-up-enter-active {
  transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
}
.scale-up-enter-from { transform: scale(0.8); opacity: 0; }
</style>
