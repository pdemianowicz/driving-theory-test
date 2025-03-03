<template>
  <header class="bg-white dark:bg-[#181a1b] shadow-sm fixed top-0 left-0 w-full z-10 transition-border-bg duration-500">
    <nav class="h-14 flex items-center text-sm font-medium max-w-[1408px] px-2 md:px-4 mx-auto">
      <RouterLink :to="{ name: 'Home' }" class="mr-auto text-base font-medium text-gray-700 px-1.5 py-1 rounded">E-prawko</RouterLink>

      <div
        id="primaryNav"
        :class="[
          'fixed md:static inset-0 top-14 z-10 flex max-md:flex-col md:items-center gap-5 max-md:p-6 max-md:border-t border-gray-300 bg-white dark:bg-[#181a1b] transition duration-500',
          isMobileMenuOpen ? 'max-md:translate-x-0' : 'max-md:translate-x-full',
        ]">
        <template v-for="navItem in navItems" :key="navItem.name">
          <RouterLink
            :to="{ name: navItem.route }"
            class="flex items-center text-gray-700 dark:text-[#c0bab2] hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
            {{ navItem.name }}
            <span v-if="navItem.label" class="inline-block px-1 ml-1.5 text-[10px] font-semibold rounded-md text-gray-50 bg-blue-500 uppercase">
              {{ navItem.label }}
            </span>
          </RouterLink>
        </template>

        <span class="w-6 h-[1px] md:w-[1px] md:h-6 bg-gray-300 dark:bg-neutral-700 transition-border-bg duration-500"></span>
        <TranslateModeSwitch />
        <span class="w-6 h-[1px] md:w-[1px] md:h-6 bg-gray-300 dark:bg-neutral-700 transition-border-bg duration-500"></span>
        <DarkModeSwitch />
        <span class="max-md:hidden w-[1px] h-6 bg-gray-300 dark:bg-neutral-700 transition-border-bg duration-500"></span>
      </div>
      <template v-if="isAuthenticated">
        <DropdownMenu />
      </template>
      <template v-else>
        <RouterLink
          :to="{ name: 'Login' }"
          class="text-neutral-50 dark:text-slate-200 bg-blue-500 px-3 py-1.5 max-md:text-xs ml-5 rounded-md transition-colors duration-500">
          {{ $t("header.login") }}
        </RouterLink>
      </template>
      <div class="md:hidden z-20">
        <button
          @click="toggleMobileMenu()"
          aria-label="Otwórz/zamknij menu"
          aria-controls="primaryNav"
          :aria-expanded="isMobileMenuOpen.toString()"
          class="relative inline-flex items-center cursor-pointer justify-center text-slate-800 dark:text-gray-300 w-[40px] h-[40px]">
          <svg
            v-if="!isMobileMenuOpen"
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2">
            <path d="M4 12h16M4 18h16M4 6h16"></path>
          </svg>
          <svg
            v-else
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { computed, watch } from "vue";
import { useUserStore } from "../store/user";
import DropdownMenu from "./DropdownMenu.vue";
import { useToggle } from "@vueuse/core";
import DarkModeSwitch from "./DarkModeSwitch.vue";
import TranslateModeSwitch from "./TranslateModeSwitch.vue";

const userStore = useUserStore();
const isAuthenticated = computed(() => userStore.isAuthenticated);

const navItems = [
  { name: "Home", route: "Home", label: "" },
  { name: "Catagory", route: "Catagory", label: "" },
  { name: "Nauka", route: "Index", label: "" },
  { name: "FAQ", route: "Egzamin", label: "" },
  { name: "Blog", route: "Blog", label: "New" },
];

const [isMobileMenuOpen, toggleMobileMenu] = useToggle(false);

// Monitorujemy isMobileMenuOpen i ustawiamy overflow na <html>
watch(isMobileMenuOpen, (newVal) => {
  if (newVal) {
    document.documentElement.style.overflow = "hidden";
  } else {
    document.documentElement.style.overflow = "";
  }
});
</script>

<style scoped></style>
