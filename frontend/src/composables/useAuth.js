import { useAuthStore } from '@/stores/authStore'
import { computed } from 'vue'

export function useAuth() {
  const auth = useAuthStore()

  const loginUser = async (login, password) => {
    await auth.login(login, password)
  }

  const logout = async () => {
    await auth.logout()
  }

  return {
    loginUser,
    logout,
    user: auth.user,
    isAuthenticated: computed(() => !!auth.token)
  }
}
