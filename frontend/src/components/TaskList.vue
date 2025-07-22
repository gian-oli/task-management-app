<template>
  <div>
    <div class="flex flex-wrap justify-between mb-4 gap-4">
      <select v-model="filterStatus" class="p-2 border rounded">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
      </select>
      <select v-model="filterPriority" class="p-2 border rounded">
        <option value="all">All Priorities</option>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search tasks..."
        class="p-2 border rounded flex-grow"
      />
    </div>

    <form @submit.prevent="handleAddTask" class="mb-4 flex space-x-2">
      <input
        v-model="newTask.title"
        type="text"
        placeholder="Task title"
        required
        class="flex-grow p-2 border rounded"
      />
      <input
        v-model="newTask.description"
        type="text"
        placeholder="Task description"
        class="flex-grow p-2 border rounded"
      />
      <select v-model="newTask.priority" required class="p-2 border rounded">
        <option disabled value="">Priority</option>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
      <button type="submit" class="bg-blue-500 text-white px-4 rounded">Add</button>
    </form>

    <Draggable
      v-model="filteredTasks"
      item-key="id"
      @end="onDragEnd"
      tag="ul"
      class="space-y-2"
    >
      <template #item="{ element }">
        <TaskItem
          :task="element"
          @toggle-complete="toggleComplete"
          @delete-task="handleDeleteTask"
        />
      </template>
    </Draggable>

    <div v-if="loading" class="mt-4 text-center text-gray-600">Loading tasks...</div>
  </div>
</template>

<script setup>
import Draggable from 'vuedraggable'
import TaskItem from './TaskItem.vue'
import { useTasks } from '@/composables/useTasks'

const {
  loading,
  filterStatus,
  filterPriority,
  searchQuery,
  newTask,
  filteredTasks,
  handleAddTask,
  toggleComplete,
  handleDeleteTask,
  onDragEnd
} = useTasks()
</script>
