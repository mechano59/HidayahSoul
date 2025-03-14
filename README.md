# HidayahSoul - Find Peace and Guidance in the Quran

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://your-build-system-url)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

HidayahSoul is a web application designed to help users find relevant verses from the Quran based on their current emotional state.  Utilizing the power of AI (Gemini API via OpenRouter) and a clean, intuitive interface built with Vue.js and Laravel, HidayahSoul provides a modern way to connect with the divine wisdom of the Quran.

## Features

*   **Mood-Based Verse Retrieval:**  Enter your mood (e.g., "I feel anxious," "grateful," "lost") or select from a predefined list of emotions.
*   **AI-Powered Emotion Extraction:**  The Gemini API (accessed via OpenRouter) intelligently extracts the core emotion from your input. For instance, "I'm feeling overwhelmed and stressed" becomes "stress."
*   **Relevant Quranic Verses:** The extracted emotion is used to fetch relevant verses from the Quran.  The Gemini API is prompted to provide a specific number of verses (currently 3) along with a short reflection on each verse's connection to the emotion.
*   **Accurate Verse Data:** The application fetches:
    *   **Arabic Text:**  From a reliable Quran API (GitHub repository).
    *   **English Translation:** From a corresponding text file in the same repository.
    *   **Audio Recitation:**  Provides a link to an audio recitation of each verse.
*   **Clear and Focused Presentation:** Verses are displayed with:
    *   Arabic text (right-to-left rendering).
    *   English translation.
    *   Verse reference (Surah and verse number).
    *   Audio player.
    *   AI-generated reflection.
*   **Robust Parsing:** The application includes a robust parsing system to handle variations in the Gemini API's output format, ensuring that verses are displayed correctly.
*   **Responsive Design:** The application works seamlessly on desktops, tablets, and mobile devices.
*   **Dark Mode Support:** A built-in dark mode toggle provides a comfortable viewing experience in low-light conditions.
*   **Error Handling:** Includes comprehensive error handling to provide a smooth user experience, even when API calls fail or unexpected data is received.
*   **Loading Indicators:**  Displays loading spinners while fetching data, informing the user that the application is working.
*  **Laravel Backend**: Uses a robust laravel backend, that provides and store data, and provides security.

## Technologies Used

*   **Frontend:**
    *   Vue.js (v3)
    *   TypeScript
    *   Tailwind CSS
    *   Vite
*   **Backend:**
    *   Laravel (12.x)
    *   PHP
*   **AI:**
    *   Gemini API (via OpenRouter)
*   **Quran Data:**
    *   [Quran JSON GitHub Repository](https://github.com/semarketir/quranjson) (for Arabic text, English translation, and audio URLs)

## Setup and Installation

**Prerequisites:**

*   PHP (>= 8.2) with required extensions (see `composer.json`)
*   Composer
*   Node.js (>= 18)
*   npm
*   MySQL  (or your preferred database server)
*   An API key for Gemini (obtained via OpenRouter: [https://openrouter.ai/](website.com))

**Steps:**

1.  **Clone the Repository:**

    ```bash
    git clone <repository-url>
    cd HidayahSoul
    ```

2.  **Install PHP Dependencies:**

    ```bash
    composer install
    ```

3.  **Install JavaScript Dependencies:**

    ```bash
    npm install
    ```

4.  **Create Environment File:**

    ```bash
    cp .env.example .env
    ```

5.  **Configure Environment (`.env`):**

    *   **Database:** Set `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` to your database credentials.
    *   **Gemini API:**  Set `GEMINI_API_KEY` to your Gemini API key (from OpenRouter) and `GEMINI_API_URL` to the OpenRouter API endpoint (`https://openrouter.ai/api/v1/chat/completions`).
    * **APP_URL**: Correctly set the APP_URL
    *  **QURAN_API_URL, AUDIO_BASE_URL and TRANSLATION_BASE_URL** Set correctly the URLs from the Quran JSON GitHub Repository.

    ```
    APP_NAME=HidayahSoul
    APP_ENV=local
    APP_KEY=base64:Czw3WUHzuN46KGr9rBXYxtU/HvbFAX8tY7kLi8MwLWw=  # Generate with `php artisan key:generate`
    APP_DEBUG=true
    APP_URL=http://hidayahsoul.test # Your application URL (Important)

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=HidayahSoul #your db name
    DB_USERNAME=root #your db username
    DB_PASSWORD=root #your db password
    ...
    GEMINI_API_KEY=Your Gemini API key
    GEMINI_API_URL=Your API URL
    QURAN_API_URL=https://raw.githubusercontent.com/semarketir/quranjson/refs/heads/master/source/surah/surah_
    AUDIO_BASE_URL=https://raw.githubusercontent.com/semarketir/quranjson/master/source/audio/
    TRANSLATION_BASE_URL=https://raw.githubusercontent.com/semarketir/quranjson/master/source/translation/en/
    ```

6.  **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

7.  **Run Database Migrations:**

    ```bash
    php artisan migrate
    ```

8.  **Build Frontend Assets:**

    ```bash
    npm run build
    ```

9.  **Serve the Application:**

    ```bash
    php artisan serve
    ```

    The application will be accessible at `http://localhost:8000` (or the URL shown in the output of `php artisan serve`).

## Contributing

Contributions are welcome!  Please follow these guidelines:

1.  Fork the repository.
2.  Create a new branch for your feature or bug fix.
3.  Make your changes and commit them with clear, descriptive messages.
4.  Write tests for your changes (if applicable).
5.  Submit a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements

*   The creators and maintainers of Vue.js, Laravel, Tailwind CSS, and Vite.
*   The OpenRouter team for providing access to the Gemini API.
*   The contributors to the Quran JSON GitHub repository.
* Original Idea was from https://serenequran.netlify.app/

## Contact
If you have any doubt, post an issue, and it will be answered.