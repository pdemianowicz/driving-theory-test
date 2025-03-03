<template>
  <div>
    <!-- <Hero class="mt-[100px] mb-[400px]" /> -->

    <section class="mt-[110px] text-center">
      <h2 class="text-lg md:text-7xl font-bold mt-10 text-slate-800 dark:text-stone-300">Twórz ultraszybkie i bezpieczne strony WWW zgodne z RODO</h2>
      <p class="text-gray-700 dark:text-stone-400 text-xl px-5 mt-10 my-20 max-w-5xl mx-auto">
        Tworzenie strony internetowej nie musi być ani skomplikowane, ani kosztowne. Dzięki Publii, wyjątkowo intuicyjnemu systemowi zarządzania
        treścią, możesz szybko i bez wysiłku zbudować atrakcyjną, bezpieczną i zgodną z RODO witrynę internetową.
      </p>
      <RouterLink
        :to="{ name: 'Signup' }"
        class="inline-block bg-blue-500 border-2 border-blue-500 text-neutral-50 font-medium px-5 py-3.5 rounded-md shadow-sm transition-transform transform duration-300 hover:-translate-y-1">
        Tryb egzaminu
      </RouterLink>
      <RouterLink
        :to="{ name: 'Signup' }"
        class="inline-block ml-4 bg-transparent border-2 border-slate-800 text-slate-800 dark:border-stone-500 dark:text-stone-300 font-medium px-5 py-3.5 rounded-md shadow-sm transition transform duration-300 hover:-translate-y-1 hover:border-blue-500 hover:text-blue-500 dark:hover:border-blue-500 dark:hover:text-blue-500">
        Tryb nauki
      </RouterLink>
      <img src="../assets/hero.webp" alt="" />
    </section>

    <section class="mt-40 overflow-hidden">
      <h2 class="text-center text-lg md:text-4xl font-bold mt-10 text-slate-800 dark:text-stone-300">Redefining developer experience</h2>
      <h3 class="text-center text-gray-700 dark:text-stone-400 text-xl px-5 mt-2.5">Vite makes web development simple again</h3>
      <div
        class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-screen-xl mx-auto mt-10 text-slate-800 dark:text-stone-300 transition-border-bg duration-500 overflow-hidden">
        <div
          ref="card1"
          class="border dark:border-none rounded-xl p-8 min-h-[350px] lg:col-span-2 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
          :class="isVisible.card1 ? 'translate-x-0 opacity-100 visible' : '-translate-x-16 opacity-0 invisible'">
          Symulacja egzaminu państwowego - Obowiązują tu reguły jak na egzaminie państwowym. Liczy się czas i zdobyta punktacja! Test zawiera 32
          pytania i jest podzielony na część podstawową i specjalistyczną.
          <div
            class="absolute left-[-200px] top-[-200px] -z-10 w-[800px] h-[450px] bg-gradient-to-br from-gray-500 to-gray-700 rounded-full opacity-10 blur-3xl"
            aria-hidden="true"></div>
        </div>
        <div
          ref="card2"
          class="border dark:border-none rounded-xl p-8 min-h-[350px] lg:col-span-1 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
          :class="isVisible.card2 ? 'translate-x-0 opacity-100 visible' : 'translate-x-16 opacity-0 invisible'">
          Oficjalna baza pytań egzaminacyjnych - Aktualne, najnowsze pytania z egzaminu państwowego wraz z odpowiedziami. Oryginalne pytania w
          połączeniu z komentarzami stanowią najlepsze testy na rynku do nauki prawa jazdy.
        </div>
        <div
          ref="card3"
          class="border dark:border-none rounded-xl p-8 min-h-[350px] lg:col-span-1 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
          :class="isVisible.card3 ? 'translate-x-0 opacity-100 visible' : '-translate-x-16 opacity-0 invisible'">
          Śledzenie postępów w nauce - Śledzenie postępów w nauce. Kontroluj swoje wyniki. Przejrzysty diagram umożliwia analizę pytań wymagających
          większego zaangażowania.
        </div>
        <div
          ref="card4"
          class="border dark:border-none rounded-xl p-8 h-[440px] lg:col-span-2 transition-transform duration-1000 ease-out bg-neutral-100 dark:bg-[#1d2021]"
          :class="isVisible.card4 ? 'translate-x-0 opacity-100 visible' : 'translate-x-16 opacity-0  invisible'">
          <h2 class="text-2xl lg:text-4xl font-bold font-display mb-8 text-center mt-6">Latest from Blog</h2>
          <div class="flex justify-center items-start lg:gap-6 w-full">
            <article
              class="relative flex flex-col min-h-[300px] lg:min-h-[400px] bg-dark-800 border-dark-600/80 overflow-hidden rounded-lg border-2 group w-[270px] transition-all duration-300 ease-out translate-y-[10px] hover:-translate-y-2 rotate-[-5deg] translate-y-[40px] hover:rotate-0 hover:translate-y-0">
              <a class="relative w-full mb-2" data-discover="true" href="/blog/how-i-almost-destroyed-a-clients-website-an-invaluable-lesson-learned">
                <img
                  src="https://imagedelivery.net/KMb5EadhEKC1gAE0LkjL1g/fd972ccc-f1ea-443d-474a-c9d21b49c800/public"
                  alt=""
                  class="aspect-[16/9] w-full bg-gray-700 object-cover" />
              </a>
              <div class="hidden items-center justify-between w-full mt-3 text-xs gap-x-4 px-5 lg:flex">
                <a
                  class="relative z-10 rounded-lg bg-dark-600 px-3 py-1.5 font-medium text-gray-300 hover:bg-dark-500"
                  data-discover="true"
                  href="/blog/category/Web Development">
                  Web Development
                </a>
              </div>
              <h3 class="mt-2 text-xs lg:text-base font-semibold px-5 leading-normal text-gray-200 group-hover:text-gray-300">
                <a class="line-clamp-2" data-discover="true" href="/blog/how-i-almost-destroyed-a-clients-website-an-invaluable-lesson-learned">
                  How I Almost Destroyed a Client’s Website (An Invaluable Lesson Learned)
                </a>
              </h3>
              <p class="mt-2 px-5 text-sm text-gray-400 line-clamp-3">
                Okay, now time for me to get honest. We have all been there. You’re neck-deep in a project, the deadline is getting closer, and
                suddenly that small voice in your brain says, “Hey, screw the rules!” In my situation, the voice convinced me.
              </p>
            </article>
            <article
              class="relative flex flex-col min-h-[300px] lg:min-h-[400px] bg-dark-800 border-dark-600/80 overflow-hidden rounded-lg border-2 group w-[270px] transition-all duration-300 ease-out translate-y-[10px] hover:-translate-y-2">
              <a
                class="relative w-full mb-2"
                data-discover="true"
                href="/blog/the-future-of-front-end-automation-combining-figma-templates-with-rapid-development-tools">
                <img
                  src="https://imagedelivery.net/KMb5EadhEKC1gAE0LkjL1g/0c4729fb-e070-4fc9-641e-7bdf0a73f600/public"
                  alt=""
                  class="aspect-[16/9] w-full bg-gray-700 object-cover" />
              </a>
              <div class="hidden items-center justify-between w-full mt-3 text-xs gap-x-4 px-5 lg:flex">
                <a
                  class="relative z-10 rounded-lg bg-dark-600 px-3 py-1.5 font-medium text-gray-300 hover:bg-dark-500"
                  data-discover="true"
                  href="/blog/category/Web Development">
                  Web Development
                </a>
              </div>
              <h3 class="mt-2 text-xs lg:text-base font-semibold px-5 leading-normal text-gray-200 group-hover:text-gray-300">
                <a
                  class="line-clamp-2"
                  data-discover="true"
                  href="/blog/the-future-of-front-end-automation-combining-figma-templates-with-rapid-development-tools">
                  The Future of Front-End Automation: Combining Figma Templates with Rapid Development Tools
                </a>
              </h3>
              <p class="mt-2 px-5 text-sm text-gray-400 line-clamp-3">
                Web and app development is evolving faster than ever, and front-end automation is leading the way. As developers, we’re constantly
                searching for smarter ways to reduce repetitive work, write cleaner code, and collaborate more effectively.
              </p>
            </article>
            <article
              class="relative flex flex-col min-h-[300px] lg:min-h-[400px] bg-dark-800 border-dark-600/80 overflow-hidden rounded-lg border-2 group w-[270px] transition-all duration-300 ease-out translate-y-[10px] hover:-translate-y-2 rotate-[5deg] translate-y-[40px] hover:rotate-0 hover:translate-y-0">
              <a class="relative w-full mb-2">
                <img
                  src="https://imagedelivery.net/KMb5EadhEKC1gAE0LkjL1g/2d505a78-ebbf-487d-206d-07e825104100/public"
                  alt=""
                  class="aspect-[16/9] w-full bg-gray-700 object-cover" />
              </a>
              <div class="hidden items-center justify-between w-full mt-3 text-xs gap-x-4 px-5 lg:flex">
                <a
                  class="relative z-10 rounded-lg bg-dark-600 px-3 py-1.5 font-medium text-gray-300 hover:bg-dark-500"
                  data-discover="true"
                  href="/blog/category/UI UX">
                  UI UX
                </a>
              </div>
              <h3 class="mt-2 text-xs lg:text-base font-semibold px-5 leading-normal text-gray-200 group-hover:text-gray-300">
                <a class="line-clamp-2">Responsive design is dead. Long live fluid design!</a>
              </h3>
              <p class="mt-2 px-5 text-sm text-gray-400 line-clamp-3">
                I’ve been thinking a lot about this lately. For years, responsive design has been our go-to solution for crafting web experiences that
                adapt across devices.
              </p>
            </article>
          </div>
        </div>
      </div>
    </section>

    <ReviewsSection />

    <section class="mt-40">
      <h2 class="text-center text-lg md:text-4xl font-bold mt-10 text-slate-800 dark:text-stone-300">Wszystko co musisz wiedzieć</h2>
      <h3 class="text-center text-gray-700 dark:text-stone-400 text-xl px-5 mt-2.5">Najczęściej zadawane pytania</h3>
      <Faq />
    </section>

    <GetStarted />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { RouterLink } from "vue-router";
import { useIntersectionObserver } from "@vueuse/core";
import ReviewsSection from "../components/ReviewsSection.vue";
import Faq from "../components/Faq.vue";
import GetStarted from "../components/GetStarted.vue";

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

<style scoped></style>
