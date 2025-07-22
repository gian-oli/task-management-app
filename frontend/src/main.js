import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
// import { setupEcho } from './plugins/pusher'
import './style.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

// const echo = setupEcho()

// window.echo = echo

app.mount('#app')
