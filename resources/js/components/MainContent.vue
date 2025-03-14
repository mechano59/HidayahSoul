<script setup lang="ts">
import { ref, watch } from 'vue';
import MoodSelector from './MoodSelector.vue';
import VersesDisplay from './VersesDisplay.vue';
import SettingsPanel from './SettingsPanel.vue';
import { VerseData } from '../types';
import { getQuranVerses } from '../services/quranService';

const props = defineProps<{
  activeTab: string;
}>();

const emit = defineEmits<{
  (e: 'mood-selected', mood: string): void;
  (e: 'verses-loaded', verses: VerseData[]): void;
  (e: 'loading-change', loading: boolean): void;
}>();

const isLoading = ref(false);
const versesData = ref<VerseData[]>([]);
const showWelcomeMessage = ref(true);

const handleMoodSelection = async (mood: string) => {
  emit('mood-selected', mood);
  showWelcomeMessage.value = false;
  isLoading.value = true;
  emit('loading-change', true);
  
  try {
    const verses = await getQuranVerses(mood);
    versesData.value = verses;
    emit('verses-loaded', verses);
  } catch (error) {
    console.error('Error fetching verses:', error);
    versesData.value = [];
  } finally {
    isLoading.value = false;
    emit('loading-change', false);
  }
};

// Reset when tab changes
watch(() => props.activeTab, (newTab) => {
  if (newTab !== 'quran') {
    showWelcomeMessage.value = true;
    versesData.value = [];
  }
});
</script>

<template>
  <main class="main-content">
    <div v-if="activeTab === 'quran'" class="quran-tab">
      <MoodSelector @mood-selected="handleMoodSelection" />
      
      <VersesDisplay 
        :verses="versesData" 
        :isLoading="isLoading"
        :showWelcomeMessage="showWelcomeMessage"
      />
    </div>
    
    <div v-else-if="activeTab === 'settings'" class="settings-tab">
      <SettingsPanel />
    </div>
    
    <div v-else-if="activeTab === 'about'" class="about-tab">
      <div class="content-card">
        <h2>About HidayahSoul</h2>
        <p>HidayahSoul is designed to help you find guidance and comfort through Quranic verses that address your current emotional state.</p>
        <p>Simply select or type how you're feeling, and the application will provide relevant verses along with reflections on how they relate to your emotional state.</p>
        <p>The name "Hidayah" means "guidance" in Arabic, reflecting our mission to provide spiritual guidance for your soul through the wisdom of the Quran.</p>
      </div>
    </div>
    
    <footer class="app-footer">
      <p>HidayahSoul &copy; 2025 | <a href="#" class="footer-link">FAQ</a></p>
    </footer>
  </main>
</template>

<style scoped>
.main-content {
  max-width: 800px;
  margin: 0 auto;
  padding: 0 2rem 2rem;
  display: flex;
  flex-direction: column;
  min-height: calc(100vh - 180px);
}

.content-card {
  background-color: var(--card-color);
  border-radius: var(--border-radius);
  padding: 2rem;
  box-shadow: var(--box-shadow);
  margin-bottom: 2rem;
  border: 1px solid var(--border-color);
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

.content-card h2 {
  color: var(--primary-color);
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
  font-weight: 500;
}

.content-card p {
  margin-bottom: 1rem;
  line-height: 1.6;
}

.app-footer {
  text-align: center;
  margin-top: auto;
  padding: 2rem 0 1rem;
  color: var(--primary-color);
  font-size: 0.85rem;
  opacity: 0.7;
}

.footer-link {
  color: var(--primary-color);
  text-decoration: none;
  border-bottom: 1px dotted var(--primary-color);
  padding-bottom: 1px;
  transition: var(--transition);
}

.footer-link:hover {
  color: var(--accent-color);
  border-bottom-color: var(--accent-color);
}

@media (max-width: 768px) {
  .main-content {
    padding: 0 1rem 1rem;
  }
  
  .content-card {
    padding: 1.5rem;
  }
}
</style>

