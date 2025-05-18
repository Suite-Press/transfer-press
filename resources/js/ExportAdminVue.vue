<template>
  <div class="plugins-transfer-press">
    <TitleVue />

    <div class="grid">

      <div class="card">
        <h3 class="card-title">Export Plugin</h3>
        <p class="card-desc">Select a plugin from the list and export it as a ZIP file.</p>

        <div class="form-group">
          <label for="export-select">Choose Plugin → </label>
          <select id="export-select" v-model="selected">
            <option disabled value="">Choose a Plugin</option>
            <option v-for="(plugin, slug) in plugins" :key="slug" :value="slug">
              {{ plugin.Name }}
            </option>
          </select>
        </div>

        <button class="action-button" @click="exportPlugin" :disabled="!selected">Export Plugin</button>

        <div v-if="exporting" class="progress-container">
          <div class="progress-bar" :style="{ width: progress + '%' }"></div>
          <span class="progress-text">{{ progress }} %</span>
        </div>
      </div>

      <div class="card">
        <div v-if="loadingDetails">
          <p class="card-desc">Loading plugin details...</p>
        </div>
        <div v-else-if="pluginDetails">
          <h2>{{ pluginDetails.name }} <small>(v{{ pluginDetails.version }})</small></h2>
          <p class="card-desc"><strong>Author:</strong> {{ pluginDetails.author }}</p>
          <p class="card-desc"><strong>Description:</strong> {{ pluginDetails.description }}</p>
          <p class="card-desc"><strong>Path:</strong> {{ pluginDetails.path }}</p>
          <p class="card-desc"><strong>Size:</strong> {{ pluginDetails.size }}</p>
          <p class="card-desc"><strong>Status:</strong> {{ pluginDetails.active ? 'Active' : 'Inactive' }}</p>
        </div>
        <div v-else>
          <p class="card-desc">Details of the plugin will be displayed here.</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import TitleVue from "./Components/TitleVue.vue"
import { useToast } from 'vue-toastification'
const toast = useToast()

const selected = ref('')
const plugins = ref({})
const pluginDetails = ref(null)

const loadingDetails = ref(false)
const exporting = ref(false)
const progress = ref(0)

onMounted(async () => {
  const res = await fetch(ajaxurl + '?action=plugins_transfer_press_list')
  plugins.value = await res.json()
})

watch(selected, async (newVal) => {
  if (!newVal) return
  loadingDetails.value = true
  pluginDetails.value = null
  try {
    const response = await fetch(`${ajaxurl}?action=plugins_transfer_press_details&plugin=${newVal}&nonce=${TransferPressSettings.nonce}`)
    const data = await response.json()
    if (data.success) {
      pluginDetails.value = data.data.plugin
    } else {
      toast.error(data.data.message || 'Failed to load plugin details.')
    }
  } catch (err) {
    toast.error('Failed to load plugin details.')
  } finally {
    loadingDetails.value = false
  }
})

const exportPlugin = async () => {
  if (!selected.value) {
    toast.error('Please select a plugin first.')
    return
  }

  exporting.value = true
  progress.value = 0

  const xhr = new XMLHttpRequest()
  xhr.open('POST', ajaxurl, true)

  xhr.upload.onprogress = (event) => {
    if (event.lengthComputable) {
      progress.value = Math.round((event.loaded / event.total) * 100)
    }
  }

  xhr.onload = function () {
    exporting.value = false
    if (xhr.status === 200) {
      const blob = xhr.response
      const url = URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      const pluginFolder = selected.value.split('/')[0]
      link.download = `${pluginFolder}.zip`
      link.click()

      toast.success('Plugin exported successfully.')
    } else {
      toast.error('Failed to export plugin.')
    }
  }

  xhr.onerror = function () {
    exporting.value = false
    toast.error('An error occurred while exporting the plugin.')
  }

  const formData = new URLSearchParams()
  formData.append('action', 'plugins_transfer_press_export')
  formData.append('plugin', selected.value)
  formData.append('nonce', TransferPressSettings.nonce)

  xhr.responseType = 'blob'
  xhr.send(formData)
}
</script>


<style scoped>

@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

h2, span, p, h1, h3, h4, h5, h6, label, select, button, .card-desc {
  font-family: "Open Sans", sans-serif;
}

.plugins-transfer-press {
  padding: 2rem;
  background-color: #f9fafc;
  min-height: 100vh;
}

.grid {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  justify-content: space-between;
}

.card {
  background: #ffffff;
  border-radius: 5px;
  box-shadow: rgb(2 128 128 / 15%) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
  padding: 2rem;
  flex: 1 1 45%;
  min-width: 300px;
  transition: transform 0.2s ease;
  border: none;
}

.card:hover {
  transform: translateY(-4px);
}
.wp-core-ui select:focus {
  border-color: #028080;
  box-shadow: rgba(0, 0, 0, 0.20) 0px 25px 10px -20px;
  color: #028080;
}
.card-title {
  font-size: 1.3rem;
  color: #2d3748;
  margin-bottom: 0.5rem;
}

.card-desc {
  color: #6b7280;
  margin-bottom: .8rem;
  font-size: 16px;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.4rem;
  font-weight: 600;
  color: #374151;
}

select{
  width: 100%;
  padding: 0.55rem;
  font-size: 1rem;
  border-radius: 0.5rem;
  border: 1px solid #cbd5e1;
  background: #FFFFFF url("data:image/svg+xml,%3Csvg fill='gray' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 7l3-3 3 3H7zm0 6l3 3 3-3H7z'/%3E%3C/svg%3E") no-repeat right 0.75rem center;
  background-size: 1rem;
  cursor: pointer;
}

.action-button {
  padding: 0.7rem 1.5rem;
  background-color: #008080;
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.action-button:disabled {
  background-color: #94a3b8;
  cursor: not-allowed;
}

.action-button:hover:not(:disabled) {
  background-color: #008080;
}

/* Responsive layout for small screens */
@media (max-width: 768px) {
  .grid {
    flex-direction: column;
  }
  .card {
    flex: 1 1 100%;
  }
}


.progress-container {
  position: relative;
  width: 100%;
  height: 35px;
  background-color: #e5e7eb;
  border-radius: 10px;
  overflow: hidden;
  margin-top: 1rem;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.progress-bar {
  height: 100%;
  background: linear-gradient(to right, #009d1b, #028080);
  width: 0%;
  transition: width 0.4s ease-in-out;
  border-radius: 10px 0 0 10px;
}
.progress-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #ffffff;
  font-weight: 700;
  font-size: 16px;
}
.success p {
  color: green;
  font-size: 16px;
  text-align: center;
  font-weight: 700;
}
.status-message {
  margin-top: 1rem;
  font-weight: bold;
}
.success {
  color: green;
}
.error {
  color: red;
}
</style>
