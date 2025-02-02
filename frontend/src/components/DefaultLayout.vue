<template>
  <div class="">
    <header class="bg-gray-800">
      <nav class="flex items-center gap-5 max-w-screen-xl px-4 md:px-12 py-4 mx-auto">
        <RouterLink :to="{ name: 'Home' }" class="mr-auto text-2xl font-bold text-blue-500">Driving Theory Test</RouterLink>
        <template v-if="isAuthenticated">
          <div class="flex items-center gap-3"></div>
          <div class="relative ml-3">
            <div>
              <button @click="userMenuOpen = !userMenuOpen" class="relative flex rounded-full text-sm">
                <span class="absolute -inset-1.5"></span>
                <span class="sr-only">Open user menu</span>
                <div class="w-8 h-8 bg-gray-200 rounded-full overflow-hidden">
                  <svg class="text-gray-300 w-full h-full" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                      d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
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
                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden">
                <div class="flex flex-col">
                  <RouterLink :to="{ name: 'Profile' }" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</RouterLink>
                  <RouterLink :to="{ name: 'Settings' }" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</RouterLink>
                  <button @click="logout" class="text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                </div>
              </div>
            </transition>
          </div>
        </template>
        <template v-else>
          <RouterLink :to="{ name: 'Login' }" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">
            Login
          </RouterLink>
          <RouterLink :to="{ name: 'Signup' }" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">
            Sign up
          </RouterLink>
        </template>
      </nav>
    </header>

    <RouterView class="container max-w-screen-xl mx-auto px-4 md:px-12 py-8 mt-8 border shadow rounded-md bg-white" />
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { RouterLink, RouterView } from "vue-router";
import { useUserStore } from "../store/user";
import router from "../router.js";

const userStore = useUserStore();
const isAuthenticated = computed(() => userStore.isAuthenticated);

const logout = async () => {
  await userStore.logout();
  router.push({ name: "Home" });
};

const userMenuOpen = ref(false);
</script>

<style lang="scss" scoped></style>
