<template>
  <footer
    class="bg-white dark:bg-[#181a1b] p-4 sm:p-6 lg:p-8 border-t border-gray-200 dark:border-neutral-800 transition-border-bg duration-500 mt-auto">
    <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row justify-between">
      <div class="mb-4 md:mb-0">
        <h3 class="text-lg font-semibold mb-2 text-slate-900 dark:text-slate-50">SuperPrawko</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 max-w-xs">
          Twoja najlepsza droga do zdania egzaminu teoretycznego na prawo jazdy. Aktualne pytania i symulacje egzaminów.
        </p>
      </div>

      <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-4">
        <div></div>
        <FooterNavSection title="Aplikacja" :nav-items="nav1" />
        <FooterNavSection title="Informacje" :nav-items="nav2" />
        <FooterNavSection title="Regulaminy" :nav-items="nav3" />
      </div>
    </div>
    <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row justify-between text-gray-600 dark:text-gray-400 text-sm font-medium pt-16">
      <p>Copyright © {{ currentYear }} superprawko.pl</p>
      <!-- Copyright © {{ ownerName }} {{ copyrightDateString }} Wszelkie prawa zastrzeżone. -->

      <a href="https://github.com/pdemianowicz" target="_blank" rel="noreferrer" class="flex items-center gap-1">
        <span class="">Made with ❤️ for a better web.</span>
      </a>
    </div>
  </footer>
</template>

<script setup>
const nav1 = ref([
  { name: "Funkcje", route: "/" },
  { name: "Testy Kat. B", route: "/" },
  { name: "Znaki Drogowe", route: "/" },
]);

const nav2 = ref([
  { name: "O nas", route: "/" },
  { name: "Kontakt", route: "/" },
  // { name: "FAQ", route: "/" },
  // { name: "Blog", route: "/" },
]);

const nav3 = ref([
  { name: "Regulamin", route: "/regulamin" },
  { name: "Polityka Cookies", route: "/polityka-cookies" },
  { name: "Polityka Prywatności", route: "/polityka-prywatnosci" },
]);

const ownerName = "SuperPrawko.pl";
const startYear = 2024;
const currentYear = new Date().getFullYear();

const copyrightDateString = computed(() => {
  if (currentYear > startYear) {
    return `${startYear} - ${currentYear}`;
  } else {
    return `${startYear}`;
  }
});
</script>

<style lang="scss" scoped></style>
