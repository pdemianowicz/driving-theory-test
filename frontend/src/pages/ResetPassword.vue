<template>
  <GuestLayout :submit="submit">
    <template #header>Reset your password</template>
    <template #default>
      <p class="text-sm text-gray-500 italic">Enter your email and we'll send you a link to reset your password.</p>
      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
        <div class="mt-2">
          <input
            type="email"
            name="email"
            id="email"
            autocomplete="email"
            placeholder="name@company.com"
            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
        </div>
      </div>
      <button
        type="submit"
        class="w-full text-white bg-indigo-600 hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Reset your password
      </button>
    </template>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from "../components/GuestLayout.vue";
</script>

<style lang="scss" scoped></style>
