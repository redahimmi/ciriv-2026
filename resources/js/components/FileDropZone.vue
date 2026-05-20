<template>
  <div
    class="drop-zone relative flex flex-col items-center justify-center gap-3 p-8 rounded-2xl border-2 border-dashed cursor-pointer transition-all duration-200"
    :class="[
      isDragging ? 'active' : '',
      error ? 'border-red-300 bg-red-50' : (color === 'red' ? 'border-red-200 bg-red-50/40 hover:border-red-400 hover:bg-red-50' : 'border-green-200 bg-green-50/40 hover:border-green-400 hover:bg-green-50'),
    ]"
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="onDrop"
    @click="triggerInput"
  >
    <input
      ref="inputRef"
      type="file"
      :accept="accept"
      class="sr-only"
      @change="onFileChange"
    />

    <template v-if="!selectedFile">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-200"
        :class="isDragging
          ? (color === 'red' ? 'bg-red-100' : 'bg-green-100')
          : 'bg-white shadow-sm border border-gray-100'">
        <svg class="w-7 h-7 transition-colors duration-200"
          :class="isDragging ? (color === 'red' ? 'text-red-500' : 'text-green-600') : 'text-gray-400'"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
      </div>
      <div class="text-center">
        <p class="text-sm font-semibold text-gray-700">
          Glissez-déposez votre fichier ici
        </p>
        <p class="text-xs text-gray-400 mt-1">ou <span class="font-medium" :class="color === 'red' ? 'text-red-500' : 'text-green-600'">cliquez pour parcourir</span></p>
      </div>
      <p class="text-xs text-gray-400">
        Formats acceptés : {{ accept.split(',').join(', ').replace(/\./g, '').toUpperCase() }} — max 5 Mo
      </p>
    </template>

    <template v-else>
      <div class="flex items-center gap-4 w-full">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
          :class="color === 'red' ? 'bg-red-100' : 'bg-green-100'">
          <svg class="w-6 h-6" :class="color === 'red' ? 'text-red-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-gray-800 truncate">{{ selectedFile.name }}</p>
          <p class="text-xs text-gray-500 mt-0.5">{{ formatSize(selectedFile.size) }}</p>
        </div>
        <button type="button" @click.stop="removeFile"
          class="w-8 h-8 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-red-500 hover:bg-red-100 transition-colors flex-shrink-0">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  accept: { type: String, default: '.jpg,.jpeg,.png,.pdf' },
  color: { type: String, default: 'green' },
  error: { type: Array, default: null },
});

const emit = defineEmits(['file-selected']);

const inputRef = ref(null);
const isDragging = ref(false);
const selectedFile = ref(null);

function triggerInput() {
  inputRef.value?.click();
}

function onFileChange(e) {
  const file = e.target.files[0];
  if (file) setFile(file);
}

function onDrop(e) {
  isDragging.value = false;
  const file = e.dataTransfer.files[0];
  if (file) setFile(file);
}

function setFile(file) {
  selectedFile.value = file;
  emit('file-selected', file);
}

function removeFile() {
  selectedFile.value = null;
  emit('file-selected', null);
  if (inputRef.value) inputRef.value.value = '';
}

function formatSize(bytes) {
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' Ko';
  return (bytes / (1024 * 1024)).toFixed(2) + ' Mo';
}
</script>
