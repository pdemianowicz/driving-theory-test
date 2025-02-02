<template>
  <GuestLayout :submit="submit">
    <template #header>Create new Account</template>
    <template #default>
      <div>
        <label for="name" class="block text-sm/6 font-medium text-gray-900">Full Name</label>
        <div class="mt-2">
          <input
            name="name"
            id="name"
            v-model="data.name"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
        <p class="text-sm mt-1 text-red-600">
          {{ errors.name ? errors.name[0] : "" }}
        </p>
      </div>
      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
        <div class="mt-2">
          <input
            type="email"
            name="email"
            id="email"
            autocomplete="email"
            v-model="data.email"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
        <p class="text-sm mt-1 text-red-600">
          {{ errors.email ? errors.email[0] : "" }}
        </p>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
        </div>
        <div class="mt-2">
          <input
            type="password"
            name="password"
            id="password"
            v-model="data.password"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
        <p class="text-sm mt-1 text-red-600">
          {{ errors.password ? errors.password[0] : "" }}
        </p>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="passwordConfirmation" class="block text-sm/6 font-medium text-gray-900">Repeat Password</label>
        </div>
        <div class="mt-2">
          <input
            type="password"
            name="password"
            id="passwordConfirmation"
            v-model="data.password_confirmation"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          Create an Account
        </button>
      </div>
    </template>
    <template #footer>
      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Already have an account?
        {{ " " }}
        <RouterLink :to="{ name: 'Login' }" class="font-semibold text-indigo-600 hover:text-indigo-500">Login from here</RouterLink>
      </p>
    </template>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from "../components/GuestLayout.vue";
import { ref } from "vue";
import router from "../router.js";
import axiosClient from "../axios.js";

const data = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const errors = ref({
  name: [],
  email: [],
  password: [],
});

function submit() {
  axiosClient.get("/sanctum/csrf-cookie").then((response) => {
    axiosClient
      .post("/register", data.value)
      .then((response) => {
        router.push({ name: "Home" });
      })
      .catch((error) => {
        console.log(error.response.data);
        errors.value = error.response.data.errors;
      });
  });
}
</script>

<style lang="scss" scoped></style>
