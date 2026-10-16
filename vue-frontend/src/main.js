/**
 * @module main
 * @description Vue application entry point.
 * Bootstraps Vue with Pinia (state management) and Vue Router.
 */
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './style.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
