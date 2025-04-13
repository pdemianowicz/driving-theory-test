<template>
  <section class="mt-20 md:mt-40 overflow-hidden max-w-[1200px] mx-auto">
    <h2 class="text-center text-lg md:text-4xl font-bold text-slate-800 dark:text-stone-300">Najlepszy sposób na naukę do prawa jazdy</h2>
    <h3 class="text-center text-gray-700 dark:text-stone-400 text-xl px-5 mt-2.5">Sprawdź, dlaczego tysiące kursantów wybierają nasze testy</h3>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-10 text-slate-800 dark:text-stone-300 transition-border-bg duration-500 overflow-hidden">
      <div
        ref="card1"
        class="border dark:border-none rounded-xl p-8 min-h-[350px] lg:col-span-2 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
        :class="isVisible.card1 ? 'translate-x-0 opacity-100 visible' : '-translate-x-16 opacity-0 invisible'">
        <h2 class="text-xl font-bold sm:text-3xl text-pretty font-display">Symulacja egzaminu państwowego!</h2>
        <p class="text-base my-3 text-pretty tracking-tight text-gray-400">
          Obowiązują tu reguły jak na egzaminie państwowym. Liczy się czas i zdobyta punktacja! Test zawiera 32 pytania i jest podzielony na część
          podstawową i specjalistyczną.
        </p>
        <div
          class="absolute left-[-200px] top-[-200px] -z-10 w-[800px] h-[450px] bg-gradient-to-br from-gray-500 to-gray-700 rounded-full opacity-10 blur-3xl"
          aria-hidden="true"></div>
      </div>
      <div
        ref="card2"
        class="border dark:border-none rounded-xl p-8 min-h-[350px] lg:col-span-1 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
        :class="isVisible.card2 ? 'translate-x-0 opacity-100 visible' : 'translate-x-16 opacity-0 invisible'">
        <h2 class="text-xl font-bold sm:text-3xl text-pretty font-display">Oficjalna baza pytań!</h2>
        <p class="text-base my-3 text-pretty tracking-tight text-gray-400">
          Aktualne, najnowsze pytania z egzaminu państwowego wraz z odpowiedziami. Oryginalne pytania w połączeniu z wyjaśnieniami stanowią najlepsze
          testy na rynku do nauki prawa jazdy.
        </p>

        <p class="relative flex items-center gap-4 mb-4 text-green-400 max-w-fit">
          <span class="relative flex w-3 h-3">
            <span class="absolute inline-flex w-full h-full bg-green-400 rounded-full animate-ping opacity-30"></span>
            <span class="relative inline-flex w-3 h-3 bg-green-500 rounded-full"></span>
          </span>
          Baza pytań aktualna
        </p>
        <!-- <div
            class="absolute left-1/2 bottom-[80%] z-10 -translate-x-1/2 w-[100%] h-full bg-gradient-to-br from-gray-500 to-gray-700 rounded-full opacity-20 blur-3xl"
            aria-hidden="true"></div> -->
      </div>
      <div
        ref="card3"
        class="border dark:border-none rounded-xl p-8 min-h-[350px] lg:col-span-1 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
        :class="isVisible.card3 ? 'translate-x-0 opacity-100 visible' : '-translate-x-16 opacity-0 invisible'">
        <h2 class="text-xl font-bold sm:text-3xl text-pretty font-display">Śledzenie postępów w nauce!</h2>
        <p class="text-base my-3 text-pretty tracking-tight text-gray-400">
          Śledzenie postępów w nauce. Kontroluj swoje wyniki. Przejrzysty diagram umożliwia analizę pytań wymagających większego zaangażowania.
        </p>
      </div>
      <div
        ref="card4"
        class="border dark:border-none rounded-xl p-8 h-[350px] lg:col-span-2 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
        :class="isVisible.card4 ? 'translate-x-0 opacity-100 visible' : 'translate-x-16 opacity-0  invisible'">
        <h2 class="text-xl font-bold sm:text-3xl text-pretty font-display">Intuicyjny i łatwy w obsłudze interfejs!</h2>
        <p class="text-base my-3 text-pretty tracking-tight text-gray-400">
          Przejrzysty i intuicyjny interfejs pozwoli Ci od razu rozpocząć naukę. Niezależnie od tego, czy wolisz uczyć się przy komputerze, czy wolisz
          elastyczność smartfona, nasza aplikacja dostosuje się do Ciebie. Co więcej udostępniamy testy w kilku językach: polskim, angielskim,
          niemieckim i ukraińskim.
        </p>
      </div>
    </div>
  </section>
</template>

<script setup>
const card1 = ref(null);
const card2 = ref(null);
const card3 = ref(null);
const card4 = ref(null);

const isVisible = ref({
  card1: false,
  card2: false,
  card3: false,
  card4: false,
});

const observeElement = (el, key) => {
  if (!el) return;
  useIntersectionObserver(el, ([entry]) => {
    if (entry.isIntersecting) {
      isVisible.value[key] = true;
    }
  });
};

onMounted(() => {
  observeElement(card1.value, "card1");
  observeElement(card2.value, "card2");
  observeElement(card3.value, "card3");
  observeElement(card4.value, "card4");
});
</script>

<style lang="scss" scoped></style>
