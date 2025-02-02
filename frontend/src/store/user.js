import { defineStore } from "pinia";
import axiosClient from "../axios.js";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null,
  }),
  actions: {
    fetchUser() {
      return axiosClient.get("/api/user").then(({ data }) => {
        console.log(data);
        this.user = data;
      });
    },
    logout() {
      return axiosClient.post("/logout").then(() => {
        this.user = null;
      });
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.user,
  },
});
