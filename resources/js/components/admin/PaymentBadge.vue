<template>
  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
    :class="paid === 'Oui'
      ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
      : paid === 'Pas encore'
        ? 'bg-amber-100 text-amber-700 border border-amber-200'
        : 'bg-gray-100 text-gray-400 border border-gray-200'">
    {{ paid || '—' }}
  </span>
</template>
<script setup>
defineProps({ paid: String });
</script>
