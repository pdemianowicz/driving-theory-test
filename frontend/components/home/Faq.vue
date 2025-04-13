<template>
  <div class="mt-10 max-w-[1100px] mx-auto">
    <div
      v-for="(item, index) in items"
      :key="index"
      :class="[
        openIndex === index
          ? 'bg-blue-500 text-white dark:text-stone-300'
          : 'bg-gray-100 hover:bg-gray-200 text-slate-800 dark:bg-[#1d2021] dark:text-stone-300 dark:hover:bg-opacity-50',
        'px-5 rounded-lg mb-4 transition-border-bg duration-200',
      ]">
      <button @click="toggle(index)" class="w-full flex justify-between items-center py-5 text-lg font-semibold">
        <span class="block text-left">{{ item.title }}</span>
        <span class="transition-all duration-300">
          <!-- Jeśli otwarty, wyświetl minus, w przeciwnym razie plus -->
          <svg v-if="openIndex === index" class="w-4 h-4" viewBox="0 0 16 16" fill="currentColor">
            <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
          </svg>
          <svg v-else class="w-4 h-4" viewBox="0 0 16 16" fill="currentColor">
            <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
          </svg>
        </span>
      </button>
      <!-- Kontener zawartości, którego max-height animujemy -->
      <div
        :ref="(el) => (contents[index] = el)"
        class="overflow-hidden transition-all duration-500 ease-in-out"
        :class="[openIndex === index ? '-mt-3' : 'mt-0']"
        :style="{ maxHeight: openIndex === index ? contentHeights[index] + 'px' : '0px' }">
        <div class="pb-5 text-base">
          {{ item.content }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted } from "vue";

// const props = defineProps({
//   items: {
//     type: Array,
//     required: true,
//     // Każdy element powinien mieć strukturę: { title: String, content: String }
//   },
// });

const openIndex = ref(null);
const contents = ref([]);
const contentHeights = ref([]);

// Funkcja do obliczania wysokości zawartości dla każdego elementu
const calculateHeights = () => {
  contentHeights.value = contents.value.map((el) => (el ? el.scrollHeight : 0));
};

// Aktualizujemy wysokości po zamontowaniu komponentu
onMounted(() => {
  nextTick(() => {
    calculateHeights();
  });
});

async function toggle(index) {
  // Jeśli kliknięty element jest już otwarty, zamykamy go
  if (openIndex.value === index) {
    openIndex.value = null;
  } else {
    openIndex.value = index;
  }
  // Po zmianie stanu upewnij się, że DOM został zaktualizowany i oblicz nowe wysokości
  await nextTick();
  calculateHeights();
}

const items = [
  {
    title: "Czym jest ta aplikacja/strona?",
    content:
      "To interaktywna platforma do nauki i ćwiczenia testów teoretycznych do egzaminu na prawo jazdy. Dzięki niej możesz sprawdzić swoją wiedzę oraz przygotować się do egzaminu w wygodny sposób.",
  },
  {
    title: "Jak mogę zacząć korzystać z aplikacji?",
    content:
      "Wybierzesz interesujący Cię test – możesz wybrać test losowy lub skupić się na konkretnych kategoriach. Dodatkowo możesz się zarejestrować aby mieć dostęp do dodatkowych statystyk, które pomagają w nauce.",
  },
  {
    title: "Czy testy są aktualizowane?",
    content:
      "Tak, regularnie aktualizujemy naszą bazę pytań, aby odzwierciedlała najnowsze zmiany w przepisach oraz standardach egzaminacyjnych. Stan aktualizacji możesz sprawdzić na dole strony lub tutaj: ostatnia aktualizacja 18.02.2025r.",
  },
  {
    title: "Czy pytania różnią się od tych na egzaminie państwowym?",
    content: "Nie, pytania oraz zdjęcia/filmy są takie same jak na egzaminie państowym w WORD dzięki czemu nic cię nie zaskoczy.",
  },
  {
    title: "Czy mogę korzystać z aplikacji na urządzeniach mobilnych?",
    content: "Oczywiście! Nasza strona jest responsywna i działa równie dobrze na komputerach, smartfonach i tabletach.",
  },
  {
    title: "Czy korzystanie z testów jest płatne?",
    content: "Korzystanie z aplikacji jest darmowe ale jeśli chcesz możesz mnie wesprzeć kawą tutaj...",
  },
  {
    title: "Czy wyniki są zapisywane?",
    content:
      "Tak, po każdym teście możesz zobaczyć swoje wyniki oraz prześledzić postępy. Użytkownicy posiadający konto mają dostęp do szczegółowych statystyk.",
  },
  {
    title: "Co zrobić, gdy natrafię na błąd lub mam pytanie?",
    content:
      "W przypadku problemów lub wątpliwości, prosimy o kontakt poprzez formularz kontaktowy dostępny na stronie lub wysłanie e-maila do naszego działu wsparcia.",
  },
];
</script>

<style scoped></style>
