<template>
  <div class="container max-w-screen-xl mx-auto px-4 md:px-12 py-8">
    <h1 class="text-2xl font-semibold text-center p-4">Driving Theory Test - Testy Teoretyczne Na Prawo Jazdy!!</h1>
    <ul class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-4 text-center">
      <li
        v-for="category in categories"
        :key="category.id"
        class="sm:max-w-sm p-6 border border-gray-200 rounded-lg shadow hover:bg-gray-200 cursor-pointer"
        @click="handleClick(category.id)">
        <a class="text-2xl font-bold text-gray-900 flex items-center justify-center">{{ category.name }}</a>
        <p>{{ category.description }}</p>
      </li>
    </ul>
  </div>
</template>

<script setup>
import axios from "axios";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";

const router = useRouter();
const categories = ref([]);

const API_URL = "http://127.0.0.1:8000/api/categories";

const loadCategories = async () => {
  // loading.value = true;
  // error.value = null;

  try {
    const cachedCategories = localStorage.getItem("categories");

    if (cachedCategories) {
      categories.value = JSON.parse(cachedCategories);
      console.log("Loaded categories from localStorage");
      // console.log(categories.value);
    } else {
      const response = await axios.get(API_URL);
      categories.value = response.data;
      localStorage.setItem("categories", JSON.stringify(response.data));
      console.log("Loaded categories from API and saved to localStorage");
    }
  } catch (e) {
    // error.value = "Failed to load categories. Please try again later.";
    console.error("Error loading categories:", e);
  } finally {
    // loading.value = false;
  }
};

onMounted(() => {
  loadCategories();
});

function handleClick(category) {
  const API_URL = `http://127.0.0.1:8000/api/test/${category}/start`;
  axios
    .post(
      API_URL,
      {},
      {
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
      }
    )
    .then((response) => {
      const uuid = response.data;
      router.push({ name: "Test", params: { uuid: uuid } });
    })
    .catch((error) => {
      console.error("Błąd:", error);
    });
}
</script>

<style lang="scss" scoped></style>
