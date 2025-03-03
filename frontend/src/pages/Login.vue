<template>
  <GuestLayout :submit="submit" :errorMessage="errorMessage">
    <template #header>Sign in to your account</template>
    <template #default>
      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900 dark:text-stone-300">Email address</label>
        <div class="mt-2">
          <input
            type="email"
            name="email"
            id="email"
            autocomplete="email"
            required
            v-model="data.email"
            class="block w-full rounded-md bg-white dark:bg-[#1d2021] px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
      </div>

      <div>
        <label for="password" class="block text-sm/6 font-medium text-gray-900 dark:text-stone-300">Password</label>
        <div class="mt-2">
          <input
            type="password"
            name="password"
            id="password"
            autocomplete="current-password"
            required
            v-model="data.password"
            class="block w-full rounded-md bg-white dark:bg-[#1d2021] px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <input id="remember-me" name="remember-me" type="checkbox" class="" />
          <label for="remember-me" class="block text-sm/6 font-medium text-gray-900 dark:text-stone-300">Remember me</label>
        </div>
        <div class="text-sm">
          <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          Sign in
        </button>
      </div>
    </template>
    <template #footer>
      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Not a member?
        {{ " " }}
        <RouterLink :to="{ name: 'Signup' }" class="font-semibold text-indigo-600 hover:text-indigo-500">Create an account</RouterLink>
      </p>
    </template>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from "../components/GuestLayout.vue";
import { ref } from "vue";
import router from "../router.js";
import axiosClient from "../axios.js";
import { useUserStore } from "../store/user";

const data = ref({
  email: "",
  password: "",
});

const errorMessage = ref("");

function submit() {
  axiosClient.get("/sanctum/csrf-cookie").then((response) => {
    axiosClient
      .post("/login", data.value)
      .then(async (response) => {
        const userStore = useUserStore();
        await userStore.fetchUser();
        // router.push({ name: "Home" });
        window.location.href = "/";
      })
      .catch((error) => {
        errorMessage.value = error.response.data.message;
      });
  });
}
</script>

<style lang="scss" scoped></style>
