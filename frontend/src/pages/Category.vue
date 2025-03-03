<template>
  <div class="container max-w-screen-xl mx-auto px-4 md:px-12 py-8">
    <h1 class="text-2xl font-bold p-4">Driving Theory Test</h1>

    <ul class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-1 text-center">
      <template v-if="loading">
        <CategoryCardSkeleton v-for="i in 12" :key="i" />
      </template>
      <template v-else>
        <CategoryCard v-for="category in categories" :key="category.id" :category="category" @start-test="startTest" />
      </template>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axiosClient from "../axios.js";
import CategoryCard from "../components/CategoryCard.vue";
import CategoryCardSkeleton from "../components/CategoryCardSkeleton.vue";

const router = useRouter();
const categories = ref([]);
const loading = ref(true);

const loadCategories = async () => {
  try {
    const cachedCategories = localStorage.getItem("categories");

    if (cachedCategories) {
      categories.value = JSON.parse(cachedCategories);
    } else {
      const response = await axiosClient.get("/api/categories");
      categories.value = response.data;
      localStorage.setItem("categories", JSON.stringify(response.data));
    }
  } catch (e) {
    console.error("Error loading categories:", e);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadCategories();
});

function startTest(category) {
  axiosClient
    .post(`/api/test/${category}/start`)
    .then((response) => {
      const uuid = response.data;
      router.push({ name: "Test", params: { uuid: uuid } });
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
</script>

<style lang="scss" scoped></style>
