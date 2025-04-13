<template>
  <div class="container max-w-screen-xl mx-auto px-4 md:px-12 py-8">
    <h1 class="text-2xl font-bold p-4"></h1>

    <ul v-if="!error" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-1 text-center">
      <CategoryCard v-for="category in data" :key="category.id" :category="category" @select="goToTest(category.id)" />
    </ul>

    <div v-else-if="error" class="p-4 text-red-500 text-center">
      Nie udało się załadować kategorii testów. Przepraszamy za utrudnienia. Spróbuj ponownie później.
    </div>

    <div v-else-if="!data || data.length === 0" class="p-4 text-gray-500 text-center">Brak dostępnych kategorii testów.</div>
  </div>
</template>

<script setup>
const router = useRouter();
const config = useRuntimeConfig();

const { data, error } = await useFetch(`${config.public.apiBase}/api/categories`);

if (error.value) {
  console.error("Błąd podczas pobierania kategorii API:", error.value);
}

function goToTest(categoryId) {
  router.push(`/test?id=${categoryId}`);
}
</script>

<style lang="scss" scoped></style>
