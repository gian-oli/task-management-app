<template>
  <div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard - User Management</h1>

    <div v-if="loading" class="text-center text-gray-600">Loading users...</div>

    <table v-if="!loading" class="w-full table-auto border-collapse border border-gray-300">
      <thead>
        <tr class="bg-gray-100">
          <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Role</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Toggle Admin</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
          <td class="border border-gray-300 px-4 py-2">{{ user.name }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ user.email }}</td>
          <td class="border border-gray-300 px-4 py-2">
            <span
              :class="user.is_admin ? 'text-green-600 font-semibold' : 'text-gray-600'"
            >
              {{ user.is_admin ? 'Admin' : 'User' }}
            </span>
          </td>
          <td class="border border-gray-300 px-4 py-2">
            <button
              @click="toggleAdmin(user.id)"
              class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600"
            >
              {{ user.is_admin ? 'Revoke Admin' : 'Make Admin' }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="error" class="mt-4 text-red-600 font-semibold">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'

const users = ref([])
const loading = ref(false)
const error = ref(null)
const authStore = useAuthStore()

// Fetch all users (Admin only API)
async function fetchUsers() {
  loading.value = true
  error.value = null

  try {
    const token = authStore.token
    const res = await axios.get(
      import.meta.env.VITE_API_BASE_URL + '/admin/users',
      {
        headers: { Authorization: `Bearer ${token}` }
      }
    )
    users.value = res.data
  } catch (err) {
    error.value = 'Failed to load users'
  } finally {
    loading.value = false
  }
}

// Toggle admin role for a user
async function toggleAdmin(userId) {
  error.value = null
  try {
    const token = authStore.token
    await axios.post(
      import.meta.env.VITE_API_BASE_URL + `/admin/users/${userId}/toggle-admin`,
      {},
      {
        headers: { Authorization: `Bearer ${token}` }
      }
    )
    // Refresh list after toggle
    fetchUsers()
  } catch (err) {
    error.value = 'Failed to update user role'
  }
}

// Fetch users when component mounts
onMounted(() => {
  fetchUsers()
})
</script>

<style scoped>
table {
  border-collapse: collapse;
}
</style>
