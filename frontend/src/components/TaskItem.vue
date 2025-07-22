<template>
  <li
    :class="[
      'flex items-center justify-between p-3 rounded mb-2 shadow',
      task.completed ? 'bg-green-100' : 'bg-white',
      priorityColor
    ]"
  >
    <div class="flex items-center space-x-3">
      <input
        type="checkbox"
        :checked="task.completed"
        @change="$emit('toggle-complete', task)"
      />
      <div>
        <p class="font-semibold">{{ task.title }}</p>
        <p class="text-sm text-gray-600">{{ task.description }}</p>
      </div>
    </div>
    <div class="flex items-center space-x-4">
      <span
        :class="[
          'text-xs font-bold px-2 py-1 rounded',
          priorityBadgeColor
        ]"
      >
        {{ task.priority }}
      </span>
      <button
        v-if="isAdmin"
        @click="$emit('delete-task', task)"
        class="text-red-500 hover:text-red-700"
      >
        Delete
      </button>
    </div>
  </li>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const props = defineProps({
  task: Object
})

const emit = defineEmits(['toggle-complete', 'delete-task'])

const auth = useAuthStore()
const isAdmin = computed(() => auth.user?.role === 'admin')

const priorityColor = computed(() => {
  switch (props.task.priority) {
    case 'high': return 'border-l-4 border-red-500'
    case 'medium': return 'border-l-4 border-yellow-400'
    case 'low': return 'border-l-4 border-green-400'
    default: return ''
  }
})

const priorityBadgeColor = computed(() => {
  switch (props.task.priority) {
    case 'high': return 'bg-red-200 text-red-800'
    case 'medium': return 'bg-yellow-200 text-yellow-800'
    case 'low': return 'bg-green-200 text-green-800'
    default: return ''
  }
})
</script>
