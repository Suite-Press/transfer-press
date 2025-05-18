import { createApp } from 'vue'
import Toast, { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import ExportAdminVue from "./ExportAdminVue.vue"
import ImportAdminVue from "./ImportAdminVue.vue"

const toastOptions = {
    position: POSITION.BOTTOM_RIGHT,
    timeout: 3000,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true,
}

function mountApp(component, selector) {
    const el = document.querySelector(selector)
    if (el) {
        const app = createApp(component)
        app.use(Toast, toastOptions)
        app.mount(selector)
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('transfer-press-export-app')) {
        mountApp(ExportAdminVue, '#transfer-press-export-app')
    } else if (document.getElementById('transfer-press-import-app')) {
        mountApp(ImportAdminVue, '#transfer-press-import-app')
    } else {
        mountApp(ExportAdminVue, '#my-vue-app')
    }
})
