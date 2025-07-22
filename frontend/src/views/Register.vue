<template>
  <form @submit.prevent="submitRegister" class="max-w-md mx-auto p-6 border rounded shadow mt-20">
    <h2 class="text-xl mb-4 font-semibold">Register</h2>

    <label class="block mb-2">Name</label>
    <input
      v-model="name"
      type="text"
      required
      class="w-full p-2 border rounded mb-4"
      placeholder="Your full name"
    />

    <label class="block mb-2">Email</label>
    <input
      v-model="email"
      type="email"
      required
      class="w-full p-2 border rounded mb-4"
      placeholder="your.email@example.com"
    />

    <label class="block mb-2">Password</label>
    <input
      v-model="password"
      type="password"
      required
      class="w-full p-2 border rounded mb-4"
      placeholder="Enter password"
    />

    <label class="block mb-2">Confirm Password</label>
    <input
      v-model="passwordConfirm"
      type="password"
      required
      class="w-full p-2 border rounded mb-4"
      placeholder="Confirm password"
    />

    <button
      type="submit"
      class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700"
      :disabled="loading"
    >
      Register
    </button>

    <p v-if="error" class="mt-4 text-red-600 font-semibold">{{ error }}</p>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirm = ref('')
const error = ref(null)
const loading = ref(false)

const { registerUser } = useAuth()
const router = useRouter()

async function submitRegister() {
  error.value = null

  if (password.value !== passwordConfirm.value) {
    error.value = 'Passwords do not match'
    return
  }

  loading.value = true
  try {
    await registerUser({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirm.value
    })
    router.push('/') // Redirect to dashboard or homepage after successful register
  } catch (err) {
    // Assuming your API returns errors in err.response.data.message or similar
    error.value = err.response?.data?.message || 'Registration failed'
  } finally {
    loading.value = false
  }
}
</script>
