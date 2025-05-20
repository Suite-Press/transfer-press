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
    if (document.getElementById('tranpr-export-dashboard')) {
        mountApp(ExportAdminVue, '#tranpr-export-dashboard')
    } else if (document.getElementById('tranpr-import-dashboard')) {
        mountApp(ImportAdminVue, '#tranpr-import-dashboard')
    } else {
        mountApp(ExportAdminVue, '#tranpr-export-dashboard')
    }
})
