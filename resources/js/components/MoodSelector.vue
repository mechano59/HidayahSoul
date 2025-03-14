<script setup lang="ts">
import { ref } from 'vue';
import { Search, ChevronDown, ChevronUp } from 'lucide-vue-next';

const emit = defineEmits<{
  (e: 'mood-selected', mood: string): void;
}>();

const moodInput = ref('');
const showMoreEmotions = ref(false);

const commonMoods = [
  'Anxious', 'Sad', 'Confused', 'Stressed',
  'Grateful', 'Hopeful', 'Peaceful', 'Lonely'
];

const additionalMoods = [
  'Frustrated', 'Guilty', 'Joyful', 'Wonder',
  'Grief', 'Worried', 'Regretful', 'Uncertain'
];

const handleSearch = () => {
  if (moodInput.value.trim()) {
    emit('mood-selected', moodInput.value.trim());
  }
};

const selectMood = (mood: string) => {
  emit('mood-selected', mood);
};

const toggleMoreEmotions = () => {
  showMoreEmotions.value = !showMoreEmotions.value;
};
</script>

<template>
  <div class="mood-section">
    <h2>How are you feeling today?</h2>
    
    <div class="search-container">
      <input 
        v-model="moodInput"
        type="text" 
        placeholder="Type any mood or feeling..." 
        class="mood-search"
        @keyup.enter="handleSearch"
      >
      <button @click="handleSearch" class="search-btn">
        <Search :size="18" />
      </button>
    </div>
    
    <p class="or-divider">or select a common feeling:</p>
    
    <div class="mood-grid">
      <button 
        v-for="mood in commonMoods" 
        :key="mood"
        class="mood-btn"
        @click="selectMood(mood)"
      >
        {{ mood }}
      </button>
    </div>
    
    <div class="more-emotions-container">
      <button class="more-emotions-btn" @click="toggleMoreEmotions">
        {{ showMoreEmotions ? 'Less emotions' : 'More emotions' }}
        <component :is="showMoreEmotions ? ChevronUp : ChevronDown" :size="16" />
      </button>
      
      <div class="mood-grid more-emotions-grid" :class="{ 'hidden': !showMoreEmotions }">
        <button 
          v-for="mood in additionalMoods" 
          :key="mood"
          class="mood-btn"
          @click="selectMood(mood)"
        >
          {{ mood }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.mood-section {
  margin-bottom: 2rem;
  position: relative;
  z-index: 1;
}

h2 {
  text-align: center;
  margin-bottom: 1.8rem;
  color: var(--primary-color);
  font-weight: 500;
  font-size: 1.4rem;
  letter-spacing: 0.3px;
}

.search-container {
  display: flex;
  margin-bottom: 1.5rem;
  width: 100%;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
  border: 1px solid var(--border-color);
  transition: border-color 0.3s ease;
}

.mood-search {
  flex-grow: 1;
  padding: 0.9rem 1.2rem;
  border: none;
  font-family: 'Poppins', sans-serif;
  font-size: 0.95rem;
  outline: none;
  transition: var(--transition);
  background-color: var(--card-color);
  color: var(--text-color);
}

.mood-search::placeholder {
  color: var(--text-color);
  opacity: 0.5;
}

.mood-search:focus {
  box-shadow: 0 0 0 2px var(--secondary-color);
}

.search-btn {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 0 1.5rem;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  justify-content: center;
}

.search-btn:hover {
  background-color: var(--accent-color);
}

.or-divider {
  text-align: center;
  margin: 2rem 0 1.5rem;
  color: var(--text-color);
  opacity: 0.6;
  position: relative;
  font-size: 0.9rem;
  font-weight: 400;
}

.or-divider::before, .or-divider::after {
  content: '';
  position: absolute;
  top: 50%;
  width: 80px;
  height: 1px;
  background-color: var(--border-color);
}

.or-divider::before {
  left: calc(50% - 100px);
}

.or-divider::after {
  right: calc(50% - 100px);
}

.mood-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0.8rem;
  margin-bottom: 1rem;
  width: 100%;
}

.mood-btn {
  background-color: var(--card-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius);
  padding: 0.9rem 0.7rem;
  text-align: center;
  cursor: pointer;
  transition: var(--transition);
  font-family: 'Poppins', sans-serif;
  color: var(--primary-color);
  font-weight: 500;
  font-size: 0.9rem;
  letter-spacing: 0.2px;
  min-height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 3rem;
  width: 100%;
  box-sizing: border-box;
}

.mood-btn:hover {
  background-color: var(--hover-color);
  transform: translateY(-2px);
  box-shadow: var(--box-shadow);
  border-color: var(--secondary-color);
}

.more-emotions-container {
  text-align: center;
  margin-top: 1.2rem;
}

.more-emotions-btn {
  background-color: transparent;
  color: var(--primary-color);
  border: none;
  padding: 0.6rem 1rem;
  border-radius: var(--border-radius);
  font-family: 'Poppins', sans-serif;
  font-size: 0.85rem;
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.more-emotions-btn:hover {
  background-color: var(--hover-color);
  transform: translateY(-1px);
}

.more-emotions-grid {
  margin-top: 1rem;
}

.more-emotions-grid.hidden {
  display: none;
}

@media (max-width: 992px) {
  .mood-grid {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .or-divider::before {
    left: calc(50% - 90px);
    width: 70px;
  }
  
  .or-divider::after {
    right: calc(50% - 90px);
    width: 70px;
  }
}

@media (max-width: 768px) {
  .mood-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .or-divider::before, .or-divider::after {
    width: 60px;
  }
  
  .or-divider::before {
    left: calc(50% - 70px);
  }
  
  .or-divider::after {
    right: calc(50% - 70px);
  }
}

@media (max-width: 480px) {
  .or-divider::before, .or-divider::after {
    width: 40px;
  }
  
  .or-divider::before {
    left: calc(50% - 60px);
  }
  
  .or-divider::after {
    right: calc(50% - 60px);
  }
}
</style>

