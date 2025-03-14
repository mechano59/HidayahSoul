<script setup lang="ts">
import { computed } from 'vue';
import { X, BookOpen, BookMarked, Settings, Info, Sun, Moon, Monitor } from 'lucide-vue-next';

const props = defineProps<{
  isOpen: boolean;
  activeTab: string;
  currentTheme: string;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
  (e: 'update:activeTab', value: string): void;
  (e: 'update:theme', value: 'light' | 'dark' | 'system'): void;
}>();

const tabs = [
  { id: 'quran', label: 'Quran Verses', icon: BookOpen },
  { id: 'hadith', label: 'Hadith', icon: BookMarked, disabled: true },
  { id: 'settings', label: 'Settings', icon: Settings },
  { id: 'about', label: 'About', icon: Info },
];

const selectTab = (tabId: string) => {
  if (tabs.find(tab => tab.id === tabId && !tab.disabled)) {
    emit('update:activeTab', tabId);
    if (window.innerWidth < 768) {
      emit('close');
    }
  }
};

const sidebarClass = computed(() => ({
  'sidebar': true,
  'open': props.isOpen
}));

const setTheme = (theme: 'light' | 'dark' | 'system') => {
  emit('update:theme', theme);
};
</script>

<template>
  <aside :class="sidebarClass">
    <div class="sidebar-header">
      <h2>HidayahSoul</h2>
      <button class="close-btn" @click="emit('close')">
        <X :size="20" />
      </button>
    </div>
    
    <nav class="sidebar-nav">
      <ul>
        <li v-for="tab in tabs" :key="tab.id">
          <button 
            :class="[
              'nav-item', 
              { 'active': activeTab === tab.id },
              { 'disabled': tab.disabled }
            ]"
            @click="selectTab(tab.id)"
            :disabled="tab.disabled"
          >
            <component :is="tab.icon" :size="18" />
            <span>{{ tab.label }}</span>
            <span v-if="tab.disabled" class="coming-soon">Coming Soon</span>
          </button>
        </li>
      </ul>
      
      <div v-if="activeTab === 'settings'" class="settings-section">
        <h3>Theme</h3>
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
      </div>
    </nav>
    
    <div class="sidebar-footer">
      <p>&copy; 2025 HidayahSoul</p>
    </div>
  </aside>
</template>

<style scoped>
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 280px;
  background-color: var(--card-color);
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
  z-index: 1000;
  display: flex;
  flex-direction: column;
  transform: translateX(-100%);
  transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
}

.sidebar.open {
  transform: translateX(0);
}

.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 1px solid var(--border-color);
}

.sidebar-header h2 {
  color: var(--primary-color);
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
}

.close-btn {
  background: transparent;
  border: none;
  color: var(--text-color);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem;
  border-radius: 50%;
  transition: background-color 0.3s ease;
}

.close-btn:hover {
  background-color: var(--hover-color);
}

.sidebar-nav {
  flex: 1;
  padding: 1.5rem 0;
  overflow-y: auto;
}

.sidebar-nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  width: 100%;
  text-align: left;
  background: transparent;
  border: none;
  color: var(--text-color);
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
  position: relative;
}

.nav-item:hover:not(.disabled) {
  background-color: var(--hover-color);
}

.nav-item.active {
  background-color: rgba(var(--primary-color-rgb), 0.1);
  color: var(--primary-color);
  font-weight: 500;
}

.nav-item.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 4px;
  background-color: var(--primary-color);
}

.nav-item.disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.nav-item span {
  margin-left: 0.75rem;
}

.coming-soon {
  font-size: 0.7rem;
  background-color: var(--secondary-color);
  color: var(--card-color);
  padding: 0.2rem 0.4rem;
  border-radius: 4px;
  margin-left: auto;
}

.settings-section {
  padding: 1.5rem;
  border-top: 1px solid var(--border-color);
  margin-top: 1rem;
}

.settings-section h3 {
  font-size: 1rem;
  margin-bottom: 1rem;
  color: var(--text-color);
}

.theme-options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.theme-btn {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  background-color: var(--hover-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius);
  color: var(--text-color);
  cursor: pointer;
  transition: all 0.2s ease;
}

.theme-btn:hover {
  background-color: rgba(var(--primary-color-rgb), 0.1);
}

.theme-btn.active {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.theme-btn span {
  margin-left: 0.75rem;
}

.sidebar-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--border-color);
  font-size: 0.8rem;
  color: var(--text-color);
  opacity: 0.7;
  text-align: center;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    max-width: 300px;
  }
}
</style>

