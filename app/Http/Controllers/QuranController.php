<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class QuranController extends Controller
{
    private $numVerses = 3; // Or get from config/env

    public function extractEmotion(Request $request)
    {
        $mood = $request->input('mood');

        if (!$mood) {
            return response()->json(['error' => 'Mood is required'], 400);
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('GEMINI_API_KEY'),
            ])->post(env('GEMINI_API_URL'), [
                'model' => 'google/gemini-pro:free',  // Or your specific model
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are a knowledgeable Islamic scholar specializing in Quranic exegesis.\n\nYour task is to analyze the user's input and identify the EXACT emotion being expressed.\nDO NOT generalize or categorize the emotion - preserve the specific emotion the user has mentioned.\n\nFor example:\n- If the user says \"cheated\", return \"cheated\" not \"anger\"\n- If the user says \"betrayed\", return \"betrayed\" not \"hurt\"\n- If the user says \"anxious\", return \"anxious\" not \"fear\"\n\nReturn ONLY the emotion keyword in lowercase. Do not include any other text or explanation and respond in one word.\nDo not include backticks or any special characters.",
                    ],
                    [
                        'role' => 'user',
                        'content' => $mood,
                    ],
                ],
                'temperature' => 0.1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                 // Check for valid response structure (Gemini API might change)
                 if (isset($data['choices'][0]['message']['content'])) {
                    $emotion = strtolower(trim($data['choices'][0]['message']['content']));
                    return response()->json(['emotion' => $emotion]);
                } else {
                    // Log the unexpected response for debugging
                    Log::error('Unexpected Gemini API response:', $data);
                    return response()->json(['error' => 'Invalid API response'], 500); // Internal Server Error
                }

            } else {
               Log::error('Gemini API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['error' => 'Failed to extract emotion', 'status' => $response->status()], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error in extractEmotion:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function getVerses(Request $request)
    {
        $emotion = $request->input('emotion');

        if (!$emotion) {
            return response()->json(['error' => 'Emotion is required'], 400);
        }

    try {
       $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('GEMINI_API_KEY'),
        ])->post(env('GEMINI_API_URL'), [
            'model' => 'google/gemini-pro:free',
            'messages' => [
                    [
                        'role' => 'system',
                       'content' => "You are a knowledgeable Islamic scholar who specializes in the Quran. Your task is to provide {$this->numVerses} relevant verses from the Quran that address the provided emotion: \"{$emotion}\".\n\nIt's important to find verses that are SPECIFICALLY relevant to the exact emotion provided, not a generalized category.\n\nFormat each verse exactly as follows:\n1. Start with \"VERSE_START\" followed by a placeholder like \"Translation will be fetched\".\n2. Then \"REF_START\" followed by the surah name and verse number in format \"Surah Name (chapter:verse)\".\n3. Then \"REFL_START\" followed by a brief reflection (2-3 sentences) on how this verse helps with the specific emotion of \"{$emotion}\". Please reference this exact emotion when doing the reflection.\n4. End with \"VERSE_END\".\n\nUse plain text only. Be accurate with verse references, always including the numerical chapter and verse numbers.",

                   ],
                    [
                        'role' => 'user',
                        'content' => "Emotion: {$emotion}. Please provide {$this->numVerses} relevant verses that specifically address the feeling of {$emotion}.",
                    ],
            ],
            'temperature' => 0.5,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            //error checking
            if (isset($data['choices'][0]['message']['content'])) {
                $content = $data['choices'][0]['message']['content'];
                $verses = $this->parseVersesWithMarkers($content);

                // Fetch additional data (Arabic, audio, translation)
                $enhancedVerses = [];
                foreach ($verses as $verse) {
                    $enhancedVerses[] = $this->enhanceVerseData($verse);
                }


                return response()->json(['verses' => $enhancedVerses]);
            } else {
                Log::error('Unexpected Gemini API response:', $data);
                return response()->json(['error' => 'Could not parse verses from content. Invalid API response'], 500);
            }

       } else {
           Log::error('Gemini API request failed', ['status' => $response->status(), 'body' => $response->body()]);
           return response()->json(['error' => 'Failed to fetch verses', 'status' => $response->status()], $response->status());
       }
    } catch (\Exception $e) {
        Log::error('Error in getVerses:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);

    }
}


private function parseVersesWithMarkers(string $content): array
{
     $verses = [];
      $verseBlocks = explode("VERSE_END", $content);
      $verseBlocks = array_filter($verseBlocks, function($block) {
          return trim($block) !== '';
      });

      foreach ($verseBlocks as $block) {
          $verseData = [
              'verse' => '',
              'reference' => '',
              'reflection' => '',
              'arabic' => null,       // Initialize
              'audioURL' => null,     // Initialize
              'surahName' => null,   // Initialize
          ];

          // Extract verse text
          if (preg_match('/VERSE_START([\s\S]*?)(?=REF_START|$)/', $block, $verseMatch)) {
              $verseData['verse'] = trim($verseMatch[1]);
          }

          // Extract reference
          if (preg_match('/REF_START([\s\S]*?)(?=REFL_START|$)/', $block, $refMatch)) {
              $verseData['reference'] = trim($refMatch[1]);
          }

          // Extract reflection (handle both correct and typo versions)
          if (preg_match('/REFL_START([\s\S]*?)(?=$)/', $block, $reflMatch)) {
              $verseData['reflection'] = trim($reflMatch[1]);
          } elseif (preg_match('/REFLE_START([\s\S]*?)(?=$)/', $block, $typoReflMatch)) { //check the typo version
              $verseData['reflection'] = trim($typoReflMatch[1]);
          }

          // Only add if we have at least verse and reference
          if ($verseData['verse'] && $verseData['reference']) {
              $verses[] = $verseData;
          } else {
              Log::warning("Incomplete verse data parsed", $verseData); //log
          }
      }

      return $verses;
    }

private function enhanceVerseData(array $verseData): array
{
        if (!isset($verseData['reference'])) {
           return $verseData;
        }

        $parsedRef = $this->parseQuranReference($verseData['reference']);
        if (!$parsedRef) {
           return $verseData;
        }

        try {
            // Arabic
            $arabicResponse = Http::get(env('QURAN_API_URL') . $parsedRef['surah'] . '.json');
            if ($arabicResponse->successful()) {
               $surahData = $arabicResponse->json();
                $verseKey = "verse_" . $parsedRef['ayah'];
                if (isset($surahData['verse'][$verseKey])) {
                   $verseData['arabic'] = $surahData['verse'][$verseKey];
                }
            } else {
               // Log specific error
                Log::error('Failed to fetch Arabic verse', ['status' => $arabicResponse->status(),'reference'=>$verseData['reference']]);
            }


            // --- Audio ---
            $surahIndex = str_pad($parsedRef['surah'], 3, "0", STR_PAD_LEFT);
            $ayahIndex = $parsedRef['ayah'];
            $audioIndexPath = env('AUDIO_BASE_URL') . $surahIndex . '/index.json';
             $indexResponse = Http::get($audioIndexPath);

            if ($indexResponse->successful()) {
               $indexData = $indexResponse->json();
               $verseKey = "verse_" . $ayahIndex;

                if (isset($indexData['verse'][$verseKey]['file'])) {
                   $fileName = $indexData['verse'][$verseKey]['file'];
                    $verseData['audioURL'] = env('AUDIO_BASE_URL') . $surahIndex . '/' . $fileName;
                }
            }else{
                //log error
                Log::error('Failed to fetch audio index', ['status' => $indexResponse->status(), 'reference' => $verseData['reference']]);

            }


            // Surah Name (if not already extracted)
            if (!isset($verseData['surahName']) && isset($parsedRef['surahName'])) {
               $verseData['surahName'] = $parsedRef['surahName'];
            } elseif(!isset($verseData['surahName'])) {
                 $surahNameResponse = Http::get(env('QURAN_API_URL') . $parsedRef['surah'] . '.json');
                if ($surahNameResponse->successful()) {
                   $surahData = $surahNameResponse->json();
                    $verseData['surahName'] = $surahData['name'] ?? null;
                }else{
                    //log error
                    Log::error('Failed to fetch surah name', ['status' => $surahNameResponse->status(),'reference' => $verseData['reference']]);

                }
            }

            // Translation
            $surahIndex = str_pad($parsedRef['surah'], 1, "0", STR_PAD_LEFT);  //Padding to 1
             $ayahIndex = $parsedRef['ayah'];
            $translationPath = env('TRANSLATION_BASE_URL') . "en_translation_" . $surahIndex . ".json";
            $transResponse = Http::get($translationPath);

            if ($transResponse->successful()) {
               $translationData = $transResponse->json();
              $verseKey = "verse_" . $ayahIndex;

                if (isset($translationData['verse'][$verseKey])) {
                    $verseData['verse'] = $translationData['verse'][$verseKey]; //override verse

                } else { //try without prefix
                     $verseKey = (string)$ayahIndex; //no prefix
                     if(isset($translationData['verse'][$verseKey])){
                          $verseData['verse'] = $translationData['verse'][$verseKey]; //override
                     }

                }
            }else{
                Log::error('Failed to fetch translation', ['status' => $transResponse->status(),'reference' => $verseData['reference']]);

            }

        } catch (\Exception $e) {
           Log::error('Error enhancing verse data:', ['message' => $e->getMessage(),'reference' => $verseData['reference']]);
           // Return what we have, even if incomplete
        }
        return $verseData;
}


    // Copied and adapted from your original service, made non-async, and fixed regex issue
private function parseQuranReference(string $reference): ?array
    {
        // Helper function to extract numbers from a string
        $extractNumbers = function (string $str): array {
            $numbers = [];
            if (preg_match_all('/\d+/', $str, $matches)) {
                foreach ($matches[0] as $match) {
                    $numbers[] = (int)$match;
                }
            }
            return $numbers;
        };

        // Try to extract surah and ayah numbers
       $numbers = $extractNumbers($reference);
        if (count($numbers) >= 2) {
            // We have at least two numbers, assume first is surah, second is ayah
           $surah = $numbers[0];
            $ayah = $numbers[1];

           // Try to extract surah name if present
         $surahName = null;
            if (strpos($reference, "Surah") !== false) {
                if (preg_match('/Surah\s+([\w\s-]+)/i', $reference, $surahMatch)) {
                   $surahName = trim($surahMatch[1]);
                }
            }

           return [
              'surahName' => $surahName,
                'surah' => $surah,
                'ayah' => $ayah,
            ];
        }

       return null;
      }

}
