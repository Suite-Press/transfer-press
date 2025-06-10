<template>
  <div class="plugins-transfer-press" style="margin-left: -20px!important;">
    <TitleVue />
    <div class="tranpr-docs-button">
      <a target="_blank" class="" href="https://suitepress.org/docs/how-to-import-and-activate-any-plugin-with-one-click-using-transferpress/">
        <span class="dashicons dashicons-media-document"></span>
        View Documentation		</a>
    </div>
    <div style="padding: 0 2rem;">

      <div class="tranpr-main-grid">
        <div class="tranpr-import-container">
          <form @submit.prevent="uploadPlugin" enctype="multipart/form-data">
            <div class="tranpr-form-group">
              <label class="upload-label">Select Plugin (.zip)</label>
              <input type="file" accept=".zip" @change="handleFile" class="file-input" />
            </div>

            <button type="submit" :disabled="loading" class="tranpr-submit-button">
              <span v-if="!loading">Import Plugin</span>
              <span v-else>Importing...</span>
            </button>

            <ProgressVue :loading="loading"/>
          </form>

          <div v-if="pluginUrl" class="tranpr-plugin-url-wrapper">
            <a :href="pluginUrl" target="_blank" class="tranpr-info-text">Go to Plugins Page →</a>
          </div>
        </div>

        <div v-if="statusMessages.length" class="tranpr-status-log">
          <h3 class="tranpr-status-heading">Status Log</h3>
          <div v-for="(msg, index) in statusMessages" :key="index" class="tranpr-status-line">
            {{ msg }}
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import TitleVue from "./Components/TitleVue.vue"
import ProgressVue from "./Components/ProgressVue.vue"
import { useToast } from 'vue-toastification'
const toast = useToast()

const file = ref(null)
const loading = ref(false)
const pluginUrl = ref('')
const progress = ref(0)
const statusMessages = ref([])

function logStatus(message) {
  statusMessages.value.push(message)
}

function handleFile(event) {
  progress.value = 0
  const selectedFile = event.target.files[0]

  if (!selectedFile) {
    toast.error('Please select a file.')
    return
  }

  if (!selectedFile.name.endsWith('.zip')) {
    toast.error('Only ZIP files are allowed.')
    file.value = null
    event.target.value = ''
    return
  }

  file.value = selectedFile
}

function uploadPlugin() {
  if (!file.value) {
    toast.error('Please select a valid .zip file.')
    return
  }

  loading.value = true
  progress.value = 0
  statusMessages.value = []
  logStatus("Uploading ZIP...")

  const formData = new FormData()
  formData.append('action', 'plugins_transfer_press_import')
  formData.append('nonce', TransferPressSettings.nonce)
  formData.append('plugin_zip', file.value)

  const xhr = new XMLHttpRequest()

  xhr.upload.onprogress = (event) => {
    if (event.lengthComputable) {
      progress.value = Math.round((event.loaded / event.total) * 100)
    }
  }

  xhr.onload = () => {
    loading.value = false
    let result = null

    try {
      result = JSON.parse(xhr.responseText)
    } catch (e) {
      toast.error('Upload completed, but the response was not valid JSON.')
      logStatus("Invalid JSON response received.")
      return
    }

    if (result.success) {
      pluginUrl.value = result.data.plugin_url || ''
      logStatus("Plugin installed successfully.")
      toast.success(result.data.message)
    } else {
      toast.error(result.data.message || 'Import failed.')
      logStatus("Server responded with an error.")
    }
  }

  xhr.onerror = () => {
    loading.value = false
    toast.error('An error occurred during the upload.')
    logStatus("Upload failed due to a network/server error.")
  }

  xhr.open('POST', ajaxurl)
  xhr.send(formData)
}
</script>
<style scoped>
.tranpr-main-grid {
  display: flex;
  gap: 2rem;
  justify-content: left;
  align-items: flex-start;
  flex-wrap: wrap;
  max-width: 1100px;
  width: 100%;
  flex: 1 1 450px;
}

.tranpr-import-container,
.tranpr-status-log {
  flex: 1 1 450px;
  background: #ffffff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: rgb(2 128 128 / 15%) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
  transition: transform 0.2s ease;
}
.upload-label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #333;
}
.file-input {
  display: block;
  padding: 0.6rem;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  width: 100%;
}
.tranpr-submit-button {
  padding: 0.75rem 1.8rem;
  background-color: #008080;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  width: auto;
  transition: background-color 0.3s ease;
  margin-top: 1rem;
}
.tranpr-submit-button:hover {
  background-color: #ff8c00;
}
.tranpr-submit-button:disabled {
  background-color: #aaa;
  cursor: not-allowed;
}
.tranpr-plugin-url-wrapper {
  margin-top: 2rem;
  text-align: center;
}
.tranpr-info-text {
  background: #3c434a;
  padding: 0.6rem 1.2rem;
  color: white;
  text-decoration: none;
  font-size: 1rem;
  border-radius: 6px;
  transition: background 0.3s ease;
  display: inline-block;
}
.tranpr-info-text:hover {
  background: #008080;
}
.tranpr-status-heading {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: #333;
}
.tranpr-status-line {
  background-color: #f1f5f9;
  padding: 0.5rem 1rem;
  margin-bottom: 0.5rem;
  border-radius: 6px;
  font-size: 0.95rem;
  color: #333;
}

</style>
