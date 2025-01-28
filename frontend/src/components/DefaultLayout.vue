<template>
  <div class="">
    <header class="shadow">
      <nav class="flex items-center gap-5 max-w-screen-xl px-12 py-4 mx-auto">
        <RouterLink :to="{ name: 'Home' }" class="mr-auto text-2xl font-bold text-blue-500">Driving Theory Test</RouterLink>
        <RouterLink to="">Link 1</RouterLink>
        <RouterLink to="">Link 2</RouterLink>
        <RouterLink to="">Link 3</RouterLink>
      </nav>
    </header>

    <RouterView class="container max-w-screen-xl mx-auto px-4 md:px-12 py-8 mt-8 border shadow rounded-md" />
  </div>
</template>

<script setup></script>

<style lang="scss" scoped></style>
