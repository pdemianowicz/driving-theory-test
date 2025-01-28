import { createRouter, createWebHistory } from "vue-router";

import DefaultLayout from "./components/DefaultLayout.vue";
import Home from "./pages/Home.vue";
import Test from "./pages/Test.vue";
import Results from "./pages/Results.vue";

const routes = [
  {
    path: "/",
    component: DefaultLayout,
    children: [
      { path: "/", name: "Home", component: Home },
      { path: "/test/:uuid", name: "Test", component: Test },
      { path: "test/:uuid/results", name: "Results", component: Results },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
