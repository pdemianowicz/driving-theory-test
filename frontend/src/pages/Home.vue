<template>
  <div class="container max-w-screen-xl mx-auto px-4 md:px-12 py-8">
    <h1 class="text-2xl font-bold p-4">Driving Theory Test</h1>
    <ul class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-1 text-center">
      <li
        v-for="category in categories"
        :key="category.id"
        class="sm:max-w-sm p-6 border border-gray-200 rounded-lg shadow hover:bg-gray-200 cursor-pointer"
        @click="startTest(category.id)">
        <a class="text-2xl font-bold text-gray-900 flex items-center justify-center">{{ category.name }}</a>
        <p>{{ category.description }}</p>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axiosClient from "../axios.js";

const router = useRouter();
const categories = ref([]);

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
