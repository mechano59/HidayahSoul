<script setup lang="ts">
import { VerseData } from '../types';
import VerseCard from './VerseCard.vue';
import { Loader2 } from 'lucide-vue-next';

defineProps<{
  verses: VerseData[];
  isLoading: boolean;
  showWelcomeMessage: boolean;
}>();
</script>

<template>
  <div class="verses-container">
    <div v-if="isLoading" class="loader">
      <Loader2 class="animate-spin" :size="40" />
    </div>
    
    <div v-else-if="showWelcomeMessage" class="welcome-message">
      <div class="verse-card">
        <div class="verse-text">Welcome to HidayahSoul</div>
        <div class="verse-reflection">
          Select a mood above or type in how you're feeling to receive verses from the Quran that can provide guidance and comfort for your current emotional state.
        </div>
      </div>
    </div>
    
    <div v-else-if="verses.length === 0" class="error-message">
      <div class="verse-card">
        <div class="verse-text">We're sorry, we couldn't retrieve verses at this time.</div>
        <div class="verse-reflection">
          Please check your connection and try again later. You might try being more specific with your mood description or try one of the predefined moods. Remember, 'Allah does not burden a soul beyond that it can bear.' (Quran 2:286)
        </div>
      </div>
    </div>
    
    <div v-else class="verses-content">
      <VerseCard 
        v-for="(verse, index) in verses" 
        :key="index" 
        :verse="verse" 
      />
    </div>
  </div>
</template>

<style scoped>
.verses-container {
  background-color: var(--card-color);
  border-radius: var(--border-radius);
  padding: 0.5rem;
  box-shadow: var(--box-shadow);
  margin-bottom: 2rem;
  min-height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  border: 1px solid var(--border-color);
  width: 100%;
  position: relative;
  height: auto;
  min-height: 300px;
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

.loader {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100px;
  width: 100%;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 5;
  color: var(--primary-color);
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.verses-content, .welcome-message, .error-message {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 1rem;
  height: auto;
  min-height: 100px;
  margin-bottom: 1rem;
  flex: 1;
}

.verse-card {
  padding: 2rem;
  border-radius: var(--border-radius);
  background-color: var(--card-color);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
  position: relative;
  overflow: hidden;
  border: 1px solid var(--border-color);
  transition: var(--transition);
  width: 100%;
  height: auto;
  min-height: 100px;
  box-sizing: border-box;
}

.verse-card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
  transform: translateY(-2px);
}

.verse-card:before {
  content: '"';
  position: absolute;
  top: 15px;
  left: 15px;
  font-size: 4rem;
  color: rgba(var(--primary-color-rgb), 0.07);
  font-family: 'Georgia', serif;
  line-height: 1;
}

.verse-text {
  font-family: 'Amiri', serif;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  line-height: 1.8;
  color: var(--primary-color);
  position: relative;
  padding: 0 0.5rem 0 1.5rem;
}

.verse-reflection {
  background-color: var(--hover-color);
  padding: 1.2rem 1.5rem;
  border-radius: var(--border-radius);
  margin-top: 1.5rem;
  font-size: 0.95rem;
  line-height: 1.7;
  color: var(--text-color);
  position: relative;
  border-left: 3px solid var(--secondary-color);
}

@media (max-width: 768px) {
  .verse-text {
    font-size: 1.3rem;
    padding: 0 0.5rem;
  }
  
  .verse-card {
    padding: 1.5rem;
  }
}

@media (max-width: 480px) {
  .verse-text {
    font-size: 1.2rem;
  }
  
  .verse-card {
    padding: 1.5rem;
  }
  
  .verse-card:before {
    font-size: 3rem;
  }
}
</style>

