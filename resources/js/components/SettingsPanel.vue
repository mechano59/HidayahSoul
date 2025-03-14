<script setup lang="ts">
import { inject, computed } from 'vue';
import { Sun, Moon, Monitor } from 'lucide-vue-next';

interface ThemeProvider {
  value: { theme: 'light' | 'dark' | 'system' };
}
const themeProvider = inject<ThemeProvider | null>('themeProvider', null);
const currentTheme = computed(() => themeProvider?.value?.theme || 'system');

const setTheme = (theme: 'light' | 'dark' | 'system') => {
  if (themeProvider?.value) {
    themeProvider.value.theme = theme;
  }
};
</script>

<template>
  <div class="settings-panel">
    <div class="settings-card">
      <h3>Appearance</h3>
      <div class="theme-options">
        <button 
          class="theme-btn" 
          :class="{ active: currentTheme === 'light' }"
          @click="setTheme('light')"
        >
          <Sun :size="18" />
          <span>Light</span>
        </button>
        
        <button 
          class="theme-btn" 
          :class="{ active: currentTheme === 'dark' }"
          @click="setTheme('dark')"
        >
          <Moon :size="18" />
          <span>Dark</span>
        </button>
        
        <button 
          class="theme-btn" 
          :class="{ active: currentTheme === 'system' }"
          @click="setTheme('system')"
        >
          <Monitor :size="18" />
          <span>System</span>
        </button>
      </div>
      <p class="theme-description">
        Choose how HidayahSoul appears to you. Select a light or dark theme, or follow your system settings.
      </p>
    </div>
    
    <div class="settings-card">
      <h3>About HidayahSoul</h3>
      <p>
        HidayahSoul is designed to provide guidance and comfort through Quranic verses that address your current emotional state.
      </p>
      <p>
        Version: 0.1.0
      </p>
    </div>
  </div>
</template>

<style scoped>
.settings-panel {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  max-width: 600px;
  margin: 0 auto;
}

.settings-card {
  background-color: var(--card-color);
  border-radius: var(--border-radius);
  padding: 1.5rem;
  box-shadow: var(--box-shadow);
  border: 1px solid var(--border-color);
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

h3 {
  font-size: 1.2rem;
  margin-bottom: 1.2rem;
  color: var(--primary-color);
  font-weight: 500;
}

.theme-options {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.theme-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 1rem;
  background-color: var(--hover-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius);
  color: var(--text-color);
  cursor: pointer;
  transition: all 0.2s ease;
}

.theme-btn:hover {
  background-color: rgba(var(--primary-color-rgb), 0.1);
  transform: translateY(-2px);
}

.theme-btn.active {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.theme-btn span {
  font-size: 0.9rem;
  font-weight: 500;
}

.theme-description {
  margin-top: 1rem;
  font-size: 0.9rem;
  color: var(--text-color);
  opacity: 0.8;
  line-height: 1.6;
}

p {
  margin-bottom: 0.75rem;
  line-height: 1.6;
}

@media (max-width: 768px) {
  .theme-options {
    grid-template-columns: 1fr;
  }
  
  .theme-btn {
    flex-direction: row;
    justify-content: flex-start;
    padding: 0.75rem 1rem;
  }
  
  .theme-btn span {
    margin-left: 0.75rem;
  }
}
</style>

