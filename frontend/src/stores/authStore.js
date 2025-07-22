import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/lib/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || '')

  const setToken = (newToken) => {
    token.value = newToken
    localStorage.setItem('token', newToken)
  }

  const clearAuth = () => {
    token.value = ''
    user.value = null
    localStorage.removeItem('token')
  }

  const login = async (login, password) => {
    const res = await api.post('/login', { login, password })
    setToken(res.data.token ?? res.data.data?.token)
    await fetchUser()
  }

  const fetchUser = async () => {
    const res = await api.get('/user')
    user.value = res.data
  }

  const logout = async () => {
    await api.post('/logout')
    clearAuth()
  }

  return { user, token, login, fetchUser, logout, setToken, clearAuth }
})
