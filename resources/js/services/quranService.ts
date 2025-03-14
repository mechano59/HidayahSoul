import type { VerseData } from "../types"
import axios from 'axios'; // Import axios

const NUM_VERSES = 3

// Common emotions mapping to ensure accuracy
const COMMON_EMOTIONS = new Map([
  ["cheated", "betrayed"],
  ["betrayed", "betrayed"],
  ["deceived", "betrayed"],
  ["lied to", "betrayed"],
  ["anxious", "anxious"],
  ["worried", "anxious"],
  ["nervous", "anxious"],
  ["sad", "sad"],
  ["depressed", "sad"],
  ["unhappy", "sad"],
  ["melancholy", "sad"],
  ["angry", "angry"],
  ["frustrated", "angry"],
  ["irritated", "angry"],
  ["happy", "happy"],
  ["joyful", "happy"],
  ["content", "happy"],
  ["grateful", "grateful"],
  ["thankful", "grateful"],
  ["appreciative", "grateful"],
  ["confused", "confused"],
  ["uncertain", "confused"],
  ["doubtful", "confused"],
  ["lonely", "lonely"],
  ["isolated", "lonely"],
  ["abandoned", "lonely"],
  ["hopeful", "hopeful"],
  ["optimistic", "hopeful"],
  ["inspired", "hopeful"],
  ["fearful", "fearful"],
  ["scared", "fearful"],
  ["terrified", "fearful"],
  ["guilty", "guilty"],
  ["remorseful", "guilty"],
  ["ashamed", "guilty"],
  ["peaceful", "peaceful"],
  ["calm", "peaceful"],
  ["serene", "peaceful"],
  ["stressed", "stressed"],
  ["overwhelmed", "stressed"],
  ["pressured", "stressed"],
  ["jealous", "jealous"],
  ["envious", "jealous"],
  ["resentful", "jealous"],
  ["disappointed", "disappointed"],
  ["let down", "disappointed"],
  ["disheartened", "disappointed"],
  ["proud", "proud"],
  ["accomplished", "proud"],
  ["confident", "proud"],
  ["embarrassed", "embarrassed"],
  ["humiliated", "embarrassed"],
  ["mortified", "embarrassed"],
  ["bored", "bored"],
  ["uninterested", "bored"],
  ["apathetic", "bored"],
  ["excited", "excited"],
  ["enthusiastic", "excited"],
  ["eager", "excited"],
  ["hurt", "hurt"],
  ["wounded", "hurt"],
  ["pained", "hurt"],
  ["loved", "loved"],
  ["cherished", "loved"],
  ["adored", "loved"],
  ["regretful", "regretful"],
  ["remorseful", "regretful"],
  ["sorry", "regretful"],
  ["nostalgic", "nostalgic"],
  ["reminiscent", "nostalgic"],
  ["homesick", "nostalgic"],
  ["curious", "curious"],
  ["inquisitive", "curious"],
  ["interested", "curious"],
])

// Fallback verses in case of API failure
const FALLBACK_VERSES: VerseData[] = [
  {
    verse: "And seek help through patience and prayer. Indeed, it is difficult except for the humble.",
    reference: "Surah Al-Baqarah (2:45)",
    reflection:
      "When facing challenges, this verse reminds us that patience and prayer are powerful tools to find peace and guidance.",
    arabic: "وَاسْتَعِينُوا بِالصَّبْرِ وَالصَّلَاةِ ۚ وَإِنَّهَا لَكَبِيرَةٌ إِلَّا عَلَى الْخَاشِعِينَ",
  },
]

export async function getQuranVerses(mood: string): Promise<VerseData[]> {
  try {
    console.log(`Fetching verses for mood: ${mood}`);

    const lowerMood = mood.toLowerCase().trim();
    let emotionToUse = lowerMood;

    if (COMMON_EMOTIONS.has(lowerMood)) {
      emotionToUse = COMMON_EMOTIONS.get(lowerMood) || lowerMood;
      console.log(`Using mapped emotion: ${emotionToUse}`);
    } else {
      // Use Laravel endpoint to extract emotion
        const emotionResponse = await axios.post('/api/quran/extract-emotion', { mood });

        if (emotionResponse.data && emotionResponse.data.emotion) {
          const extractedEmotion = emotionResponse.data.emotion;
          console.log("Backend extracted emotion:", extractedEmotion);

            if (
              extractedEmotion &&
              extractedEmotion !== lowerMood &&
              COMMON_EMOTIONS.has(extractedEmotion)
            ) {
              emotionToUse = extractedEmotion;
              console.log(`Using backend extracted emotion: ${emotionToUse}`);
            } else {
            console.log(`Using original mood: ${lowerMood}`);
            }

        } else {
          console.log(`Using original mood (extraction failed): ${lowerMood}`);
        }
    }

    // Use Laravel endpoint to get verses
    const versesResponse = await axios.post('/api/quran/verses', { emotion: emotionToUse });

        if (versesResponse.data && versesResponse.data.verses) {
             return versesResponse.data.verses;

        }else{
             console.error("Error from backend:", versesResponse.data);
            return FALLBACK_VERSES;
        }


  } catch (error) {
      console.error("Error in getQuranVerses:", error);
      // Handle errors from your backend (e.g., show a message to the user)
      if (axios.isAxiosError(error)) {
          // Access to config, request, and response
          console.error("Axios error:", error.response?.data || error.message);
      } else {
          // Just a stock error
          console.error("An unexpected error occurred:", error);
      }
      return FALLBACK_VERSES; // Return fallback verses
  }
}

