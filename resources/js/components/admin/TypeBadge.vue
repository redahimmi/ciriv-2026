<template>
  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
    :class="type === 'Poster'
      ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
      : 'bg-red-100 text-red-600 border border-red-200'">
    {{ type }}
  </span>
</template>
<script setup>
defineProps({ type: String });
</script>
