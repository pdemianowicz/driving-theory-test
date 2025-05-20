<template>
  <header
    class="bg-white dark:bg-[#181a1b] fixed top-0 left-0 w-full z-10 border-b border-[#EEEEEE] dark:border-[#262626] transition-border-bg duration-500 shadow dark:shadow-none">
    <nav class="h-14 flex items-center text-sm font-medium max-w-[1408px] px-2 md:px-4 mx-auto">
      <NuxtLink to="/" class="mr-auto text-lg font-semibold text-slate-900 dark:text-slate-50 px-1.5">SuperPrawko</NuxtLink>

      <div
        id="primaryNav"
        :class="[
          'fixed md:static inset-0 top-14 mt-px z-10 flex max-md:flex-col md:items-center max-md:p-6   bg-white dark:bg-[#181a1b] transition duration-500',
          isMobileMenuOpen ? 'max-md:translate-x-0' : 'max-md:translate-x-full',
        ]">
        <template v-for="navItem in navItems" :key="navItem.name">
          <NuxtLink
            :to="navItem.route"
            @click="closeMobileMenu"
            class="flex items-center pr-3 p-2 text-gray-700 dark:text-[#dfdfd6] hover:text-[#98989f] dark:hover:text-[#98989f] transition-colors">
            {{ navItem.name }}
            <span v-if="navItem.label" class="inline-block px-1 ml-1.5 text-[10px] font-semibold rounded-md text-gray-50 bg-blue-500 uppercase">
              {{ navItem.label }}
            </span>
          </NuxtLink>
        </template>

        <DropdownMenu :menuItems="dropdownItems" aria-label="Select link">
          <template #button>Baza wiedzy</template>
        </DropdownMenu>
        <span class="w-full h-[1px] md:w-[1px] md:h-6 bg-gray-300 dark:bg-neutral-700 transition-border-bg duration-500"></span>
        <HeaderLanguageSelector />
        <span class="w-full h-[1px] md:w-[1px] md:h-6 bg-gray-300 dark:bg-neutral-700 transition-border-bg duration-500"></span>
        <HeaderDarkModeSwitch />
        <span class="max-md:hidden w-[1px] h-6 bg-gray-300 dark:bg-neutral-700 transition-border-bg duration-500"></span>
      </div>
      <template v-if="isAuthenticated">
        <DropdownMenu />
      </template>
      <template v-else>
        <NuxtLink
          to="/login"
          class="text-neutral-50 dark:text-slate-200 bg-blue-500 hover:bg-blue-500/90 px-3 py-1.5 max-md:text-xs ml-5 rounded-md transition-colors duration-500">
          Zaloguj się
        </NuxtLink>
      </template>
      <div class="md:hidden ml-3">
        <HeaderMobileNav :isOpen="isMobileMenuOpen" @toggle-menu="toggleMobileMenu" />
      </div>
    </nav>
  </header>
</template>

<script setup>
// const userStore = useUserStore();
// const isAuthenticated = computed(() => userStore.isAuthenticated);
const isAuthenticated = ref(false);

const navItems = [
  { name: "Testy Teoretyczne", route: "/testy-teoretyczne/", label: "" },
  // { name: "Blog", route: "/blog", label: "New" },
  { name: "Blog", route: "/blog/", label: "" },
];

const [isMobileMenuOpen, toggleMobileMenu] = useToggle(false);

function closeMobileMenu() {
  if (isMobileMenuOpen.value) {
    toggleMobileMenu(false);
  }
}

watch(isMobileMenuOpen, (newVal) => {
  if (typeof document !== "undefined") {
    document.documentElement.style.overflow = newVal ? "hidden" : "";
  }
});

const dropdownItems = [
  { code: "pt", label: "Kategorie Prawa Jazdy", href: "/kategorie-prawa-jazdy" },
  { code: "en", label: "Egzamin teoretyczny", href: "/egzamin-teoretyczny" },
  { code: "en", label: "Egzamin praktyczny", href: "/egzamin-praktyczny" },
];
</script>

<style scoped></style>
