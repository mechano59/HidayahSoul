<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';

const props = defineProps<{
  initialTheme?: 'light' | 'dark' | 'system';
}>();

const theme = ref(props.initialTheme || 'system');

const applyTheme = (newTheme: string) => {
  if (newTheme === 'system') {
    // Check system preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.classList.toggle('dark', prefersDark);
  } else {
    document.documentElement.classList.toggle('dark', newTheme === 'dark');
  }
};

// Watch for theme changes
watch(theme, (newTheme) => {
  applyTheme(newTheme);
  localStorage.setItem('theme-preference', newTheme);
});

// Watch for system preference changes
onMounted(() => {
  // Check for saved preference
  const savedTheme = localStorage.getItem('theme-preference');
  if (savedTheme) {
    theme.value = savedTheme as 'light' | 'dark' | 'system';
  }
  
  applyTheme(theme.value);
  
  // Add listener for system preference changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (theme.value === 'system') {
      document.documentElement.classList.toggle('dark', e.matches);
    }
  });
});

defineExpose({ theme });
</script>

<template>
  <slot :theme="theme" :setTheme="(newTheme: 'light' | 'dark' | 'system') => theme = newTheme"></slot>
</template>

