import { defineStore } from "pinia";
import axiosClient from "../axios.js";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null,
    hasFetchedUser: false,
  }),
  actions: {
    async fetchUser() {
      if (this.hasFetchedUser) return;
      try {
        const { data } = await axiosClient.get("/api/user");
        this.user = data;
      } catch (error) {
        console.error("Error fetching user:", error);
      } finally {
        this.hasFetchedUser = true;
      }
    },
    async logout() {
      await axiosClient.post("/logout");
      this.user = null;
      this.hasFetchedUser = false;
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.user,
  },
});
