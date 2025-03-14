<script setup lang="ts">
import { ref } from 'vue';
import { VerseData } from '../types';

defineProps<{
  verse: VerseData;
}>();

const showArabic = ref(false);

const toggleArabic = () => {
  showArabic.value = !showArabic.value;
};
</script>

<template>
  <div class="verse-card">
    <div class="verse-text">{{ verse.verse }}</div>
    <div class="verse-reference">{{ verse.reference }}</div>
    
    <div v-if="verse.arabic" class="verse-actions">
      <button @click="toggleArabic" class="toggle-arabic-btn">
        {{ showArabic ? 'Hide Arabic' : 'Show Arabic' }}
      </button>
    </div>
    
    <div v-if="showArabic && verse.arabic" class="verse-arabic" dir="rtl">
      {{ verse.arabic }}
    </div>
    
    <audio v-if="verse.audioURL" controls class="verse-audio">
      <source :src="verse.audioURL" type="audio/mp3">
      Your browser does not support the audio element.
    </audio>
    
    <div class="verse-reflection">{{ verse.reflection }}</div>
  </div>
</template>

<style scoped>
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
  margin-bottom: 1.5rem;
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

.verse-reference {
  font-style: italic;
  margin-bottom: 1.5rem;
  color: var(--primary-color);
  font-size: 1rem;
  opacity: 0.85;
}

.verse-actions {
  margin: 1rem 0;
}

.toggle-arabic-btn {
  background-color: var(--hover-color);
  border: 1px solid var(--border-color);
  color: var(--primary-color);
  padding: 0.5rem 1rem;
  border-radius: var(--border-radius);
  cursor: pointer;
  font-size: 0.9rem;
  transition: var(--transition);
}

.toggle-arabic-btn:hover {
  background-color: var(--primary-color);
  color: white;
}

.verse-arabic {
  font-family: 'Amiri', serif;
  font-size: 1.7rem;
  margin: 1.5rem 0;
  line-height: 2;
  color: var(--primary-color);
  text-align: center;
  opacity: 0.9;
}

.verse-audio {
  width: 100%;
  margin: 1rem 0;
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
  }
  
  .verse-arabic {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .verse-card {
    padding: 1.5rem;
  }
  
  .verse-text {
    font-size: 1.2rem;
    padding: 0;
  }
  
  .verse-arabic {
    font-size: 1.3rem;
  }
}
</style>

