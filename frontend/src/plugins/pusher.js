import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import axios from 'axios'
import { useTaskStore } from '@/stores/taskStore'

window.Pusher = Pusher

export function setupEcho() {
  const taskStore = useTaskStore()

  const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: 443,
    wssPort: 443,
    encrypted: true,
    // Disable default auth method and use custom authorizer below
    authorizer: (channel, options) => {
      return {
        authorize: (socketId, callback) => {
          axios.post(
            `${import.meta.env.VITE_API_BASE_URL}/broadcasting/auth`,
            {
              socket_id: socketId,
              channel_name: channel.name,
            },
            {
              headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
              },
            }
          )
          .then(response => {
            callback(false, response.data)
          })
          .catch(error => {
            console.error('Echo auth error:', error)
            callback(true, error)
          })
        }
      }
    }
  })

  echo.private('tasks')
    .listen('TaskReordered', event => {
      taskStore.tasks = event.tasks
    })

  return echo
}
