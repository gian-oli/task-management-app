import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/lib/api' // <-- our axios instance with interceptor

export const useTaskStore = defineStore('task', () => {
    const tasks = ref([])
    const loading = ref(false)

    const fetchTasks = async () => {
        loading.value = true
        try {
            const res = await api.get('/tasks')
            tasks.value = res.data.data
            return res.data.data
        } finally {
            loading.value = false
        }
    }

    const addTask = async (taskData) => {
        const res = await api.post('/tasks', taskData)
        tasks.value.push(res.data.data)
    }

    const updateTask = async (id, updates) => {
        const res = await api.put(`/tasks/${id}`, updates)
        const index = tasks.value.findIndex(t => t.id === id)
        if (index !== -1) tasks.value[index] = res.data
    }

    const deleteTask = async (id) => {
        await api.delete(`/tasks/${id}`)
        tasks.value = tasks.value.filter(t => t.id !== id)
    }

    const reorderTasks = async (orderedIds) => {
        // if (!orderedIds.length <= 1) return;
        await api.post('/tasks/reorder', JSON.stringify({ ordered_task_ids: orderedIds }), {
            headers: {
                'Content-Type': 'application/json',
            }
        })
    }

    return {
        tasks,
        loading,
        fetchTasks,
        addTask,
        updateTask,
        deleteTask,
        reorderTasks
    }
})
