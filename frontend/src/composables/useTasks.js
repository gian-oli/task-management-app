// src/composables/useTasks.js
import { ref, onMounted, reactive, watch } from 'vue'
import { useTaskStore } from '@/stores/taskStore'

export function useTasks() {
    const taskStore = useTaskStore()
    const {
        loading,
        fetchTasks,
        addTask,
        updateTask,
        deleteTask,
        reorderTasks
    } = taskStore

    const filterStatus = ref('all')
    const filterPriority = ref('all')
    const searchQuery = ref('')

    const filteredTasks = ref([])

    const initialNewTask = {
        title: '',
        description: '',
        priority: '',
        status: 'pending'
    }

    const newTask = reactive({ ...initialNewTask })

    const applyFilters = () => {
        const allTasks = taskStore.tasks
        filteredTasks.value = allTasks.filter(task => {
            const statusMatch =
                filterStatus.value === 'all' ||
                (filterStatus.value === 'completed' && task.completed) ||
                (filterStatus.value === 'pending' && !task.completed)

            const priorityMatch =
                filterPriority.value === 'all' || task.priority === filterPriority.value

            const searchMatch =
                task.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                task.description?.toLowerCase().includes(searchQuery.value.toLowerCase())

            return statusMatch && priorityMatch && searchMatch
        })
    }

    onMounted(async () => {
        await fetchTasks()
        applyFilters()
    })

    // Watch filters and reapply
    watch([filterStatus, filterPriority, searchQuery, () => taskStore.tasks], applyFilters)

    const handleAddTask = async () => {
        if (!newTask.title || !newTask.priority) return
        await addTask({ ...newTask })
        Object.assign(newTask, { ...initialNewTask })
        applyFilters()
    }

    const toggleComplete = async (task) => {
        await updateTask(task.id, { completed: !task.completed })
        applyFilters()
    }

    const handleDeleteTask = async (task) => {
        if (confirm('Delete this task?')) {
            await deleteTask(task.id)
            applyFilters()
        }
    }

    const onDragEnd = async () => {
        const orderedIds = filteredTasks.value.map(task => task.id)
        await reorderTasks(orderedIds)
        await fetchTasks()
    }

    return {
        tasks: taskStore.tasks,
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
    }
}
