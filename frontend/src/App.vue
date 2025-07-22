<template>
  <div class="flex flex-col h-screen">
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white px-4 py-3 flex justify-between items-center">
      <router-link to="/" class="text-xl font-bold">TaskApp</router-link>
      <div class="space-x-4">
        <router-link v-if="isAuthenticated" to="/" class="hover:underline">Dashboard</router-link>
        <router-link v-if="isAdmin" to="/admin" class="hover:underline"
          >Admin</router-link
        >
        <button v-if="isAuthenticated" @click="logout" class="hover:underline">
          Logout
        </button>
        <!-- <router-link v-else to="/login" class="hover:underline">Login</router-link> -->
      </div>
    </nav>

    <!-- Main content area -->
    <main class="flex-1 overflow-y-auto p-4 bg-gray-50">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useAuth } from "@/composables/useAuth";

const router = useRouter();
const auth = useAuth();

if (auth.token) {
  auth.fetchUser().catch(() => auth.clearAuth()); // clean up invalid tokens
}

const isAuthenticated = auth.isAuthenticated;
const isAdmin = auth.user?.role === "admin";

const logout = () => {
  auth.logout();
  router.push("/login");
};
</script>
