import { createRouter, createWebHistory } from "vue-router";
import DefaultLayout from "./components/DefaultLayout.vue";
import Home from "./pages/Home.vue";
import Test from "./pages/Test.vue";
import Results from "./pages/Results.vue";
import Signup from "./pages/Signup.vue";
import Login from "./pages/Login.vue";
import ResetPassword from "./pages/ResetPassword.vue";
import { useUserStore } from "./store/user";
import Profile from "./pages/Profile.vue";
import UserSettings from "./pages/UserSettings.vue";

const routes = [
  {
    path: "/",
    component: DefaultLayout,
    children: [
      { path: "/", name: "Home", component: Home },
      { path: "/test/:uuid", name: "Test", component: Test },
      { path: "test/:uuid/results", name: "Results", component: Results },
      { path: "/profile", name: "Profile", component: Profile },
      { path: "/settings", name: "Settings", component: UserSettings },
    ],
  },
  {
    path: "/signup",
    name: "Signup",
    component: Signup,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/resetpassword",
    name: "ResetPassword",
    component: ResetPassword,
  },
  {
    path: "/:pathMatch(.*)*",
    redirect: { name: "Home" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();

  if (!userStore.user) {
    try {
      await userStore.fetchUser();
    } catch (error) {
      console.error("Authenticated error:", error);
    }
  }
  next();
});

export default router;
