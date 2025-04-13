<template>
  <div>
    <HomeHero />
    <HomeFeatureCard />

    <section class="mt-20 md:mt-40 max-w-screen-xl mx-auto">
      <h2 class="text-center text-lg md:text-4xl font-bold mt-10 text-slate-800 dark:text-stone-300">Dostępne kategorie prawa jazdy</h2>
      <h3 class="text-center text-gray-700 dark:text-stone-400 text-xl px-5 mt-2.5">Wybierz kategorię i rozpocznij naukę</h3>
      <ul class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 text-center mt-10"></ul>

      <!-- <LogoScroller /> -->
    </section>

    <HomeReviewsSection />

    <section class="mt-20 md:mt-40 max-w-screen-xl mx-auto">
      <h2 class="text-center text-lg md:text-4xl font-bold mt-10 text-slate-800 dark:text-stone-300">Wszystko co musisz wiedzieć</h2>
      <h3 class="text-center text-gray-700 dark:text-stone-400 text-xl px-5 mt-2.5">Najczęściej zadawane pytania</h3>
      <HomeFaq />
    </section>

    <HomeGetStarted />
  </div>
</template>

<script setup>
useHead({
  title: "SuperPrawko - Testy na Prawo Jazdy Online | Egzamin Teoretyczny",
  meta: [
    {
      name: "description",
      content:
        "Przygotuj się skutecznie do egzaminu teoretycznego na prawo jazdy z SuperPrawko! Oferujemy aktualne testy online na kategorie A, B, C, D i inne, symulację egzaminu państwowego i śledzenie postępów. Rozpocznij naukę już dziś!",
    },
  ],
});
</script>

<style scoped></style>
