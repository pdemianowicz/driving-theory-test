<template>
  <form>
    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base/7 font-semibold">Profile</h2>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <h3 class="text-sm/6 font-medium">
              Username:
              <span class="italic font-normal">{{ userStore.user.name }}</span>
            </h3>
          </div>

          <div class="col-span-full">
            <label for="photo" class="block text-sm/6 font-medium">Photo</label>
            <div class="mt-2 flex items-center gap-x-3">
              <!-- <UserCircleIcon class="size-12 text-gray-300" aria-hidden="true" /> -->
              <button
                type="button"
                class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">
                Change
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base/7 font-semibold">Personal Information</h2>
        <p class="mt-1 text-sm/6 text-gray-600">Use a permanent address where you can receive mail.</p>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <label for="first-name" class="block text-sm/6 font-medium">First name</label>
            <div class="mt-2">
              <input
                type="text"
                name="first-name"
                id="first-name"
                autocomplete="given-name"
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <div class="sm:col-span-3">
            <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Last name</label>
            <div class="mt-2">
              <input
                type="text"
                name="last-name"
                id="last-name"
                autocomplete="family-name"
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <div class="sm:col-span-4">
            <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
            <div class="mt-2">
              <input
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>
        </div>
      </div>

      <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base/7 font-semibold text-gray-900">Notifications</h2>
        <p class="mt-1 text-sm/6 text-gray-600">We'll always let you know about important changes, but you pick what else you want to hear about.</p>

        <div class="mt-10 space-y-10">
          <fieldset>
            <legend class="text-sm/6 font-semibold text-gray-900">By email</legend>
            <div class="mt-6 space-y-6">
              <div class="flex gap-3">
                <div class="flex h-6 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input
                      id="comments"
                      aria-describedby="comments-description"
                      name="comments"
                      type="checkbox"
                      checked=""
                      class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                    <svg
                      class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                      viewBox="0 0 14 14"
                      fill="none">
                      <path
                        class="opacity-0 group-has-checked:opacity-100"
                        d="M3 8L6 11L11 3.5"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                      <path
                        class="opacity-0 group-has-indeterminate:opacity-100"
                        d="M3 7H11"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                  </div>
                </div>
                <div class="text-sm/6">
                  <label for="comments" class="font-medium text-gray-900">Comments</label>
                  <p id="comments-description" class="text-gray-500">Get notified when someones posts a comment on a posting.</p>
                </div>
              </div>
              <div class="flex gap-3">
                <div class="flex h-6 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input
                      id="candidates"
                      aria-describedby="candidates-description"
                      name="candidates"
                      type="checkbox"
                      class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                    <svg
                      class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                      viewBox="0 0 14 14"
                      fill="none">
                      <path
                        class="opacity-0 group-has-checked:opacity-100"
                        d="M3 8L6 11L11 3.5"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                      <path
                        class="opacity-0 group-has-indeterminate:opacity-100"
                        d="M3 7H11"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                  </div>
                </div>
                <div class="text-sm/6">
                  <label for="candidates" class="font-medium text-gray-900">Candidates</label>
                  <p id="candidates-description" class="text-gray-500">Get notified when a candidate applies for a job.</p>
                </div>
              </div>
              <div class="flex gap-3">
                <div class="flex h-6 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input
                      id="offers"
                      aria-describedby="offers-description"
                      name="offers"
                      type="checkbox"
                      class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto" />
                    <svg
                      class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                      viewBox="0 0 14 14"
                      fill="none">
                      <path
                        class="opacity-0 group-has-checked:opacity-100"
                        d="M3 8L6 11L11 3.5"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                      <path
                        class="opacity-0 group-has-indeterminate:opacity-100"
                        d="M3 7H11"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                  </div>
                </div>
                <div class="text-sm/6">
                  <label for="offers" class="font-medium text-gray-900">Offers</label>
                  <p id="offers-description" class="text-gray-500">Get notified when a candidate accepts or rejects an offer.</p>
                </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div>

    <div class="mt-6 flex items-center justify-end gap-x-6">
      <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
      <button
        type="submit"
        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Save
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref } from "vue";
import { useUserStore } from "../store/user";
const userStore = useUserStore();
</script>

<style lang="scss" scoped></style>
