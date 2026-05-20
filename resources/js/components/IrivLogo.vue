<template>
  <span :class="darkBg
    ? 'inline-flex items-center gap-2.5 bg-white rounded-xl px-3 py-1.5 shadow-sm'
    : 'inline-flex items-center gap-2.5'">
    <img :class="className" :src="'/assets/logociriv.png'" alt="CIRIV" />
    <span class="font-bold text-lg tracking-tight leading-none"
      :class="darkBg ? 'text-gray-900' : 'text-white'">CIRIV</span>
  </span>
</template>

<script setup>
defineProps({
  className: { type: String, default: 'h-8 w-auto' },
  darkBg:    { type: Boolean, default: false },
});
</script>
