<template>
  <form @submit.prevent="submitLogin" class="max-w-md mx-auto p-6 border rounded shadow mt-20">
    <h2 class="text-xl mb-4 font-semibold">Login</h2>

    <label class="block mb-2">Email or Username</label>
    <input
      v-model="login"
      type="text"
      required
      class="w-full p-2 border rounded mb-4"
    />

    <label class="block mb-2">Password</label>
    <input
      v-model="password"
      type="password"
      required
      class="w-full p-2 border rounded mb-4"
    />

    <button
      type="submit"
      class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
    >
      Login
    </button>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'

const login = ref('')
const password = ref('')
const { loginUser } = useAuth()
const router = useRouter()

async function submitLogin() {
  try {
    await loginUser(login.value, password.value)
    router.push('/')
  } catch (err) {
    alert('Login failed: ' + (err.response?.data?.message || err.message))
  }
}
</script>
