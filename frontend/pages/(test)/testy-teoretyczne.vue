<template>
  <div class="max-w-[1200px] mx-auto py-10 md:py-16 lg:py-24">
    <!-- Sekcja SEO i Nagłówek -->
    <section class="text-center mb-12 md:mb-16">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl text-pretty tracking-tight font-bold text-slate-800 dark:text-stone-300">
        Rozpocznij Test na Prawo Jazdy
      </h1>
      <p class="mt-4 sm:mt-6 text-lg text-gray-700 dark:text-stone-400 text-balance max-w-3xl mx-auto">
        Wybierz kategorię prawa jazdy oraz preferowany tryb nauki lub symulacji egzaminu państwowego. Przygotuj się skutecznie z aktualną bazą pytań!
      </p>
    </section>

    <!-- Sekcja Wyboru i Startu Testu -->
    <section class="max-w-2xl mx-auto bg-neutral-100 dark:bg-[#1d2021] p-6 sm:p-8 rounded-xl shadow-lg transition-colors duration-500">
      <!-- Komunikat o ładowaniu -->
      <div v-if="pending" class="text-center text-gray-500 dark:text-stone-400">Ładowanie dostępnych kategorii...</div>

      <!-- Komunikat o błędzie -->
      <div v-else-if="error" class="text-center text-red-500 dark:text-red-400">Nie udało się załadować kategorii. Spróbuj odświeżyć stronę.</div>

      <!-- Formularz Wyboru (gdy dane załadowane) -->
      <form v-else-if="categories && categories.length > 0" @submit.prevent="startTest">
        <!-- 1. Wybór Kategorii -->
        <div class="mb-6">
          <label for="category-select" class="block text-lg font-medium mb-2 text-slate-800 dark:text-stone-300">Wybierz kategorię:</label>
          <select
            id="category-select"
            v-model="selectedCategory"
            required
            class="w-full px-4 py-3 border border-gray-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-[#282c2d] text-slate-800 dark:text-stone-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
            <option disabled value="">-- Proszę wybrać kategorię --</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }} - {{ category.description || "Prawo jazdy" }}
            </option>
          </select>
        </div>

        <!-- 2. Wybór Trybu -->
        <div class="mb-8">
          <span class="block text-lg font-medium mb-3 text-slate-800 dark:text-stone-300">Wybierz tryb:</span>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <button
              v-for="mode in availableModes"
              :key="mode.id"
              type="button"
              @click="selectedMode = mode.id"
              :class="[
                'p-4 rounded-lg border text-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-[#1d2021] focus:ring-blue-500',
                selectedMode === mode.id
                  ? 'bg-blue-500 text-white border-blue-500 dark:border-blue-600 ring-2 ring-blue-300 dark:ring-blue-700'
                  : 'bg-white dark:bg-[#282c2d] border-gray-300 dark:border-neutral-600 text-slate-700 dark:text-stone-300 hover:bg-gray-50 dark:hover:bg-neutral-700',
              ]">
              {{ mode.name }}
            </button>
          </div>
        </div>

        <!-- 3. Przycisk Start -->
        <button
          type="submit"
          :disabled="!selectedCategory || !selectedMode"
          class="w-full bg-blue-500 text-neutral-50 font-bold px-6 py-4 rounded-lg shadow-sm text-lg transition-colors duration-300 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
          Rozpocznij Test
        </button>
      </form>

      <!-- Komunikat o braku kategorii -->
      <div v-else class="text-center text-gray-500 dark:text-stone-400">Brak dostępnych kategorii testów do wyboru.</div>
    </section>

    <!-- Sekcja Opisów Trybów (Dodatkowe SEO) -->
    <section class="mt-16 md:mt-24 max-w-4xl mx-auto text-slate-800 dark:text-stone-300">
      <h2 class="text-2xl sm:text-3xl font-bold text-center mb-8">Poznaj dostępne tryby</h2>
      <div class="space-y-8">
        <div>
          <h3 class="text-xl font-semibold mb-2">Symulacja Egzaminu Państwowego</h3>
          <p class="text-gray-700 dark:text-stone-400">
            Sprawdź swoją wiedzę w warunkach identycznych jak na prawdziwym egzaminie. Odpowiadasz na 32 losowo wybrane pytania (20 z części
            podstawowej, 12 ze specjalistycznej) w ograniczonym czasie. Wynik poznasz po zakończeniu testu. Idealny sposób na ocenę gotowości.
          </p>
        </div>
        <div>
          <h3 class="text-xl font-semibold mb-2">Tryb Nauki</h3>
          <p class="text-gray-700 dark:text-stone-400">
            Ucz się we własnym tempie. Po każdej odpowiedzi otrzymasz natychmiastową informację zwrotną, czy była poprawna. Możesz również skorzystać
            z wyjaśnień do pytań, aby lepiej zrozumieć zagadnienia. Brak limitu czasu - skup się na przyswajaniu wiedzy.
          </p>
        </div>
        <div>
          <h3 class="text-xl font-semibold mb-2">Najtrudniejsze Pytania</h3>
          <p class="text-gray-700 dark:text-stone-400">
            Zmierz się z pytaniami, które sprawiają najwięcej problemów kursantom. Ten tryb wybiera pytania z oficjalnej bazy, na które najczęściej
            udzielano błędnych odpowiedzi. Doskonały sposób na wzmocnienie najsłabszych punktów przed egzaminem.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useRuntimeConfig } from "#app"; // Lub import { useRuntimeConfig } from 'nuxt/app'; w zależności od wersji

const router = useRouter();
const config = useRuntimeConfig();

// Stan komponentu
const selectedCategory = ref(""); // Przechowuje ID wybranej kategorii
const selectedMode = ref(""); // Przechowuje ID wybranego trybu (np. 'exam', 'learn', 'hardest')

// Dostępne tryby - można to też pobierać z API, jeśli tryby są dynamiczne
const availableModes = ref([
  { id: "exam", name: "Symulacja Egzaminu" },
  { id: "learn", name: "Tryb Nauki" },
  { id: "hardest", name: "Najtrudniejsze Pytania" },
]);

// Pobieranie kategorii
const {
  data: categories,
  pending,
  error,
} = await useFetch(`${config.public.apiBase}/api/categories`, {
  lazy: false, // Czekamy na dane przed renderowaniem (lub true jeśli chcesz szkielet)
  server: true, // Pobieramy dane po stronie serwera dla SEO
});

// Obsługa błędów pobierania
if (error.value) {
  console.error("Błąd podczas pobierania kategorii na stronie /testy-teoretyczne:", error.value);
  // Można tu dodać logowanie błędów do systemu monitoringu
}

// Funkcja rozpoczynająca test
function startTest() {
  if (!selectedCategory.value || !selectedMode.value) {
    alert("Proszę wybrać kategorię i tryb testu.");
    return;
  }

  const categoryId = selectedCategory.value;
  const mode = selectedMode.value; // 'exam', 'learn', lub 'hardest'

  let targetPath = "";

  // Mapowanie 'mode' na odpowiednią ścieżkę (trasę)
  switch (mode) {
    case "exam":
      targetPath = "/exam"; // Przekieruj do strony trybu egzaminu
      break;
    case "learn":
      targetPath = "/learn"; // Przekieruj do strony trybu nauki
      break;
    case "hardest":
      targetPath = "/hardest"; // Przekieruj do strony trybu najtrudniejszych pytań
      break;
    default:
      console.error("Nieznany tryb testu:", mode);
      alert("Wystąpił błąd. Nieznany tryb testu.");
      return; // Nie przekierowuj, jeśli tryb jest nieznany
  }

  // Przekierowanie do odpowiedniej strony z ID kategorii jako parametrem query
  router.push(`${targetPath}?id=${categoryId}`);
}

// Ustawienia SEO dla strony (jeśli używasz useHead)
useHead({
  title: "Testy na Prawo Jazdy Online - Wybierz Kategorię i Tryb",
  meta: [
    {
      name: "description",
      content:
        "Rozpocznij naukę do egzaminu teoretycznego na prawo jazdy. Wybierz kategorię (B, A, C, D, T) i tryb: symulacja egzaminu, nauka lub najtrudniejsze pytania.",
    },
  ],
});
</script>

<style scoped>
/* Można dodać dodatkowe style, jeśli Tailwind nie wystarcza */
</style>
