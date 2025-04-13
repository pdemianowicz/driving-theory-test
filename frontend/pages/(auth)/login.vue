<template>
  <div class="w-full">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <NuxtLink to="/" class="font-semibold text-indigo-600 hover:text-indigo-500">
        <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&amp;shade=600" alt="Your Company" />
      </NuxtLink>

      <h2 class="text-center text-2xl/9 font-bold tracking-tight text-gray-900 dark:text-stone-300">Sign in to your account</h2>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-[480px] bg-white dark:bg-[#181a1b] px-6 py-12 rounded-lg shadow mt-6">
      <form @submit.prevent="submit" class="space-y-6" action="#" method="POST">
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

        <div></div>
        <button
          type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          Sign in
        </button>
      </form>

      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Not a member?
        {{ " " }}
        <NuxtLink to="/" class="font-semibold text-indigo-600 hover:text-indigo-500">Create an account</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: "guest",
});

const data = ref({
  email: "",
  password: "",
});

const errorMessage = ref("");

const submit = async () => {
  await $fetch("http://localhost:8000/sanctum/csrf-cookie", {
    credentials: "include",
  });

  const csrfToken = useCookie("XSRF-TOKEN").value;

  const res = await $fetch(`http://localhost:8000/api/login`, {
    method: "POST",
    credentials: "include",
    headers: {
      "X-XSRF-TOKEN": csrfToken,
    },
    body: { answers: userAnswers.value },
  });

  navigateTo({ name: "/" }, { replace: true });

  // axiosClient.get("/sanctum/csrf-cookie").then((response) => {
  //   axiosClient
  //     .post("/login", data.value)
  //     .then(async (response) => {
  //       const userStore = useUserStore();
  //       await userStore.fetchUser();
  //       // router.push({ name: "Home" });
  //       window.location.href = "/";
  //     })
  //     .catch((error) => {
  //       errorMessage.value = error.response.data.message;
  //     });
  // });
};
</script>

<style lang="scss" scoped></style>
