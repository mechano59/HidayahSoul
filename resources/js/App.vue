<script setup lang="ts">
import { ref, provide } from 'vue';
import Sidebar from './components/Sidebar.vue';
import MainContent from './components/MainContent.vue';
import ThemeProvider from './components/ThemeProvider.vue';
import { VerseData } from './types';

const isSidebarOpen = ref(false);
const activeTab = ref('quran');
const versesData = ref<VerseData[]>([]);
const isLoading = ref(false);
const themeProviderRef = ref();

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const handleMoodSelection = (mood: string) => {
  console.log(`Selected mood: ${mood}`);
};

const handleVersesLoaded = (verses: VerseData[]) => {
  versesData.value = verses;
};

const handleLoadingChange = (loading: boolean) => {
  isLoading.value = loading;
};

// Provide theme context to all components
provide('themeProvider', themeProviderRef);
</script>

<template>
  <ThemeProvider ref="themeProviderRef" initialTheme="system">
    <template #default="{ theme, setTheme }">
      <div class="app-container" :class="{ 'sidebar-open': isSidebarOpen }">
        <Sidebar 
          :isOpen="isSidebarOpen" 
          :activeTab="activeTab"
          :currentTheme="theme"
          @update:activeTab="activeTab = $event"
          @update:theme="setTheme($event)"
          @close="isSidebarOpen = false"
        />
        
        <div class="main-wrapper">
          <header class="app-header">
            <button class="sidebar-toggle" @click="toggleSidebar">
              <span class="sr-only">Toggle Sidebar</span>
              <div class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
              </div>
            </button>
            <h1>HidayahSoul</h1>
            <p class="subtitle">Guidance for your heart and soul</p>
          </header>
          
          <MainContent 
            :activeTab="activeTab"
            @mood-selected="handleMoodSelection"
            @verses-loaded="handleVersesLoaded"
            @loading-change="handleLoadingChange"
          />
        </div>
      </div>
    </template>
  </ThemeProvider>
</template>

<style scoped>
.app-container {
  display: flex;
  min-height: 100vh;
  position: relative;
  background-color: var(--background-color);
  background-image: linear-gradient(150deg, 
    rgba(var(--primary-color-rgb), 0.05), 
    rgba(var(--secondary-color-rgb), 0.03));
  transition: background-color 0.3s ease, color 0.3s ease;
}

.main-wrapper {
  flex: 1;
  transition: margin-left 0.3s ease;
  width: 100%;
  overflow-x: hidden;
}

.sidebar-open .main-wrapper {
  margin-left: 280px;
}

.app-header {
  text-align: center;
  padding: 2.5rem 1rem 2rem;
  color: var(--primary-color);
  position: relative;
}

.app-header:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 2px;
  background: linear-gradient(to right, transparent, var(--primary-color), transparent);
}

h1 {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
  letter-spacing: 1.5px;
  font-weight: 600;
}

.subtitle {
  font-size: 1.1rem;
  opacity: 0.85;
  font-weight: 300;
  letter-spacing: 0.7px;
}

.sidebar-toggle {
  position: absolute;
  left: 1.5rem;
  top: 2.5rem;
  background: transparent;
  border: none;
  cursor: pointer;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  transition: background-color 0.3s ease;
}

.sidebar-toggle:hover {
  background-color: rgba(var(--primary-color-rgb), 0.1);
}

.hamburger-icon {
  width: 24px;
  height: 18px;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.hamburger-icon span {
  display: block;
  height: 2px;
  width: 100%;
  background-color: var(--primary-color);
  border-radius: 2px;
  transition: all 0.3s ease;
}

@media (max-width: 768px) {
  .sidebar-open .main-wrapper {
    margin-left: 0;
    opacity: 0.5;
    pointer-events: none;
  }
  
  h1 {
    font-size: 2rem;
  }
  
  .subtitle {
    font-size: 0.95rem;
  }
}
</style>

