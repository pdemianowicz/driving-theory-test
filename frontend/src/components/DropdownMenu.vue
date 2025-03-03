<template>
  <div class="relative">
    <div>
      <button @click="toggleUserMenu" class="avatar-button relative flex items-center rounded-full text-sm">
        <span class="absolute -inset-1.5"></span>
        <span class="sr-only">Open user menu</span>
        <div class="w-6 h-6 bg-gray-200 dark:bg-neutral-600 rounded-full overflow-hidden transition-border-bg duration-500">
          <svg class="text-gray-300 dark:text-gray-400 w-full h-full" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path
              d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
          </svg>
        </div>
        <div class="w-6 h-6 rounded-full overflow-hidden transition-transform duration-300" :class="{ 'rotate-180': userMenuOpen }">
          <svg class="text-gray-300 w-full h-full" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor" d="M7.41 8.58L12 13.17l4.59-4.59L18 10l-6 6l-6-6z" />
          </svg>
        </div>
      </button>
    </div>
    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95">
      <div
        v-if="userMenuOpen"
        ref="userMenuRef"
        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-[#181a1b] dark:border dark:border-neutral-800 py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden">
        <div class="flex flex-col">
          <template v-for="item in menuItems" :key="item.route">
            <RouterLink
              :to="{ name: item.route }"
              @click="closeUserMenu"
              class="flex gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#c0bab2] dark:hover:bg-[#2d2f31]">
              <span>
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                  <path fill="currentColor" :d="item.icon" />
                </svg>
              </span>
              {{ item.name }}
            </RouterLink>
          </template>

          <button
            @click="logout"
            class="flex gap-2 text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-[#c0bab2] dark:hover:bg-[#2d2f31]">
            <span>
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path
                  fill="currentColor"
                  d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h6q.425 0 .713.288T12 4t-.288.713T11 5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.175-8H10q-.425 0-.712-.288T9 12t.288-.712T10 11h7.175L15.3 9.125q-.275-.275-.275-.675t.275-.7t.7-.313t.725.288L20.3 11.3q.3.3.3.7t-.3.7l-3.575 3.575q-.3.3-.712.288t-.713-.313q-.275-.3-.262-.712t.287-.688z" />
              </svg>
            </span>
            Logout
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from "vue";
import router from "../router.js";
import { onClickOutside } from "@vueuse/core";
import { useUserStore } from "../store/user.js";

const userStore = useUserStore();
const userMenuOpen = ref(false);
const userMenuRef = ref(null);
const menuItems = [
  {
    name: "Your Profile",
    route: "Profile",
    icon: "M5.85 17.1q1.275-.975 2.85-1.537T12 15t3.3.563t2.85 1.537q.875-1.025 1.363-2.325T20 12q0-3.325-2.337-5.663T12 4T6.337 6.338T4 12q0 1.475.488 2.775T5.85 17.1M12 13q-1.475 0-2.488-1.012T8.5 9.5t1.013-2.488T12 6t2.488 1.013T15.5 9.5t-1.012 2.488T12 13m0 9q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22",
  },
  {
    name: "Settings",
    route: "Settings",
    icon: "M10.825 22q-.675 0-1.162-.45t-.588-1.1L8.85 18.8q-.325-.125-.612-.3t-.563-.375l-1.55.65q-.625.275-1.25.05t-.975-.8l-1.175-2.05q-.35-.575-.2-1.225t.675-1.075l1.325-1Q4.5 12.5 4.5 12.337v-.675q0-.162.025-.337l-1.325-1Q2.675 9.9 2.525 9.25t.2-1.225L3.9 5.975q.35-.575.975-.8t1.25.05l1.55.65q.275-.2.575-.375t.6-.3l.225-1.65q.1-.65.588-1.1T10.825 2h2.35q.675 0 1.163.45t.587 1.1l.225 1.65q.325.125.613.3t.562.375l1.55-.65q.625-.275 1.25-.05t.975.8l1.175 2.05q.35.575.2 1.225t-.675 1.075l-1.325 1q.025.175.025.338v.674q0 .163-.05.338l1.325 1q.525.425.675 1.075t-.2 1.225l-1.2 2.05q-.35.575-.975.8t-1.25-.05l-1.5-.65q-.275.2-.575.375t-.6.3l-.225 1.65q-.1.65-.587 1.1t-1.163.45zm1.225-6.5q1.45 0 2.475-1.025T15.55 12t-1.025-2.475T12.05 8.5q-1.475 0-2.488 1.025T8.55 12t1.013 2.475T12.05 15.5",
  },
];

const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value;
};

const closeUserMenu = () => {
  userMenuOpen.value = false;
};

const logout = async () => {
  await userStore.logout();
  router.push({ name: "Home" });
  closeUserMenu();
};

onClickOutside(userMenuRef, (event) => {
  const avatarButton = document.querySelector(".avatar-button");
  if (avatarButton && avatarButton.contains(event.target)) {
    return;
  }
  closeUserMenu();
});
</script>

<style lang="scss" scoped></style>
