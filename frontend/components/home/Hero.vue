<template>
  <div class="mt-16 sm:mt-32">
    <div class="mx-auto max-w-screen-xl 2xl:max-w-screen-2xl">
      <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-[640px,auto] items-center gap-8 xl:gap-16">
        <section class="text-left">
          <h1 class="text-5xl sm:text-[clamp(3rem,1.5rem+3vw,4.5rem)] text-pretty tracking-tight font-bold text-slate-800 dark:text-stone-300">
            Zdaj teorie za pierwszym razem!
          </h1>
          <p class="mt-6 text-lg text-gray-700 dark:text-stone-400 text-balance">
            Ucz się mądrzej, niekoniecznie ciężej. Nasza aplikacja wykorzystuje kompletną i zweryfikowaną bazę pytań na 2025 rok, by Twoja nauka była
            maksymalnie efektywna. To wszystko, czego potrzebujesz, by opanować teorię.
          </p>
          <div class="flex items-center mt-10">
            <NuxtLink to="/testy-teoretyczne" class="bg-blue-500 text-neutral-50 font-medium px-3 py-2 rounded-[3px] shadow-sm">
              Tryb egzaminu
            </NuxtLink>
            <NuxtLink
              to="/testy-teoretyczne"
              class="flex items-center justify-center ml-4 bg-transparent ring-1 ring-slate-300 text-slate-800 dark:border-stone-500 dark:text-stone-300 font-medium px-3 py-2 rounded-[3px] shadow-sm">
              <span>Tryb nauki</span>
              <svg class="w-6 h-6" viewBox="0 0 24 22">
                <path
                  fill="currentColor"
                  d="M10.5 16.3q-.2 0-.35-.137T10 15.8V8.2q0-.225.15-.362t.35-.138q.05 0 .35.15l3.625 3.625q.125.125.175.25t.05.275t-.05.275t-.175.25L10.85 16.15q-.075.075-.162.113t-.188.037" />
              </svg>
            </NuxtLink>

            <!-- <NuxtLink to="/" class="font-semibold px-6 py-2 text-sm bg-[#ebebeb] flex items-center justify-center rounded">
              <span>Tryb egzaminu</span>

              <svg class="w-5 h-5" viewBox="0 0 32 29">
                <path fill="currentColor" d="M21.188 9.281L19.78 10.72L24.063 15H4v2h20.063l-4.282 4.281l1.407 1.438L27.905 16z" />
              </svg>
            </NuxtLink> -->
          </div>
        </section>
        <div class="mt-8 lg:mt-0">
          <!-- <img
            class="w-full h-auto scale-110 sm:scale-100 lg:scale-110 2xl:scale-100"
            src="/images/hero-dark.png"
            alt="Aplikacja SuperPrawko do nauki teorii prawa jazdy"
            width="832"
            height="554" /> -->
          <NuxtImg
            src="/images/hero-dark.webp"
            alt="Aplikacja SuperPrawko do nauki teorii prawa jazdy"
            width="832"
            height="554"
            format="webp"
            sizes="xs:420px md:832px"
            class="w-full h-auto scale-110 sm:scale-100 lg:scale-110 2xl:scale-100 hidden dark:block"
            preload />
          <NuxtImg
            src="images/hero-light.webp"
            alt="Aplikacja SuperPrawko do nauki teorii prawa jazdy"
            width="832"
            height="554"
            format="webp"
            sizes="xs:420px md:832px"
            class="w-full h-auto scale-110 sm:scale-100 lg:scale-110 2xl:scale-100 block dark:hidden"
            preload />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup></script>

<style scoped></style>
