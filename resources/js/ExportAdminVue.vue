<template>
  <div class="plugins-transfer-press" style="margin-left: -20px!important;">
    <TitleVue />
    <div class="tranpr-docs-button">
      <a target="_blank" class="" href="https://suitepress.org/docs/how-to-export-any-plugin-from-wordpress-using-transferpress/">
        <span class="dashicons dashicons-media-document"></span>
        View Documentation		</a>
    </div>
    <div style="padding: 0 2rem;">
      <div class="tranpr-grid">
        <div class="tranpr-card">
          <h3 class="tranpr-card-title">Export Plugin</h3>
          <p class="tranpr-card-desc">
            Select a plugin from the list and export it as a
            <span style="color: green"> ZIP file.</span>
          </p>

          <div class="tranpr-form-group">
            <label for="export-select">Choose Plugin →</label>

            <Multiselect
                v-model="selected"
                :options="pluginOptions"
                placeholder="Choose a Plugin"
            />

          </div>

          <button class="action-button" @click="exportPlugin" :disabled="!selected">Export Plugin</button>

          <div v-if="exporting" class="tranpr-progress-container">
            <div class="tranpr-progress-bar" :style="{ width: progress + '%' }"></div>
            <span class="tranpr-progress-text">{{ progress }} %</span>
          </div>
        </div>

        <div class="tranpr-card">
          <div v-if="loadingDetails">
            <p class="tranpr-card-desc">Loading plugin details...</p>
          </div>
          <div v-else-if="pluginDetails">
            <h2>{{ pluginDetails.name }} <small>(v{{ pluginDetails.version }})</small></h2>
            <p class="tranpr-card-desc"><strong>Author:</strong> {{ pluginDetails.author }}</p>
            <p class="tranpr-card-desc"><strong>Description:</strong> {{ pluginDetails.description }}</p>
            <p class="tranpr-card-desc"><strong>Path:</strong> {{ pluginDetails.path }}</p>
            <p class="tranpr-card-desc"><strong>Size:</strong> {{ pluginDetails.size }}</p>
            <p class="tranpr-card-desc"><strong>Status:</strong> {{ pluginDetails.active ? 'Active' : 'Inactive' }}</p>
          </div>
          <div v-else>
            <p class="tranpr-card-desc">Details of the plugin will be displayed here.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import TitleVue from "./Components/TitleVue.vue"
import { useToast } from 'vue-toastification'
import Multiselect from "@vueform/multiselect";
import '@vueform/multiselect/themes/default.css'

const toast = useToast()
const selected = ref('')
const plugins = ref({})
const pluginDetails = ref(null)
const loadingDetails = ref(false)
const exporting = ref(false)
const progress = ref(0)

const selectedPlugin = computed(() => {
  return plugins.value[selected.value] || null
})

const pluginOptions = computed(() => {
  return Object.entries(plugins.value).map(([slug, plugin]) => ({
    label: plugin.Name,
    value: slug,
  }))
})

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
<style>

@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

h2, span, p, h1, h3, h4, h5, h6, label, select, button, .tranpr-card-desc {
  font-family: "Open Sans", sans-serif;
}
.tranpr-grid {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  justify-content: space-between;
}

.tranpr-card {
  background: #ffffff;
  border-radius: 5px;
  box-shadow: rgb(2 128 128 / 15%) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
  padding: 2rem;
  flex: 1 1 40%;
  min-width: 300px;
  transition: transform 0.2s ease;
  border: none;
}

.tranpr-card:hover {
  transform: translateY(-4px);
}
.wp-core-ui select:focus {
  border-color: #028080;
  box-shadow: rgba(0, 0, 0, 0.20) 0px 25px 10px -20px;
  color: #028080;
}
.tranpr-card-title {
  font-size: 1.3rem;
  color: #2d3748;
  margin-bottom: 0.5rem;
}

.tranpr-card-desc {
  color: #6b7280;
  margin-bottom: .8rem;
  font-size: 16px;
}

.tranpr-form-group {
  margin-bottom: 1.5rem;
}

.tranpr-form-group label {
  display: block;
  margin-bottom: 0.4rem;
  font-weight: 600;
  color: #374151;
}

.tranpr-form-group select{
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

@media (max-width: 768px) {
  .tranpr-grid {
    flex-direction: column;
  }
  .tranpr-card {
    flex: 1 1 100%;
  }
}


.tranpr-progress-container {
  position: relative;
  width: 100%;
  height: 35px;
  background-color: #e5e7eb;
  border-radius: 10px;
  overflow: hidden;
  margin-top: 1rem;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.tranpr-progress-bar {
  height: 100%;
  background: linear-gradient(to right, #009d1b, #028080);
  width: 0%;
  transition: width 0.4s ease-in-out;
  border-radius: 10px 0 0 10px;
}
.tranpr-progress-text {
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
.multiselect-option.is-selected {
  background: #008080;
  color: white;
}
.tranpr-docs-button a {
  background: white;
  padding: .7rem;
  border-radius: 5px;
  text-decoration: none;
  font-weight: 500;
  color: #028080;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}
.tranpr-docs-button {
  margin: 1.5rem 2rem;
  text-align: end;
}
.tranpr-docs-button a:hover {
  color: white;
  background: #028080;
  transition: all 0.5s ease;
}

</style>
