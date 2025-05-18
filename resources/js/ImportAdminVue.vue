<template>
  <div class="plugins-transfer-press">
    <TitleVue/>

    <div class="import-container">
      <form @submit.prevent="uploadPlugin" enctype="multipart/form-data">
        <div class="form-group">
          <input type="file" accept=".zip" @change="handleFile" />
        </div>

        <button type="submit" :disabled="loading">
          <span v-if="!loading">Import Plugin</span>
          <span v-else>Importing...</span>
        </button>

        <ProgressVue/>
      </form>

      <div v-if="pluginUrl" style="margin-top: 1.5rem; text-align: center">
        <a :href="pluginUrl" target="_blank" class="info-text">Go to Plugins Page →</a>
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
    try {
      const result = JSON.parse(xhr.responseText)
      pluginUrl.value = result.data.plugin_url || ''
      if (result.success) {
        toast.success(result.data.message)
        if (result.data.activate_url) {
          setTimeout(() => {
            window.location.href = result.data.activate_url
          }, 1500)
        }
      } else {
        toast.error(result.data.message || 'Import failed.')
      }
    } catch (e) {
      toast.error('Upload completed but failed to parse server response.')
    }
  }

  xhr.onerror = () => {
    loading.value = false
    toast.error('An error occurred during the upload.')
  }

  xhr.open('POST', ajaxurl)
  xhr.send(formData)
}
</script>


<style>

.plugins-transfer-press {
  padding: 2rem;
  background-color: #f9fafc;
  min-height: 100vh;
}
.import-container {
  max-width: 500px;
  background: #fff;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: rgb(2 128 128 / 15%) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
  flex: 1 1 45%;
  min-width: 300px;
  transition: transform 0.2s ease;
  border: none;
}
.import-container h2 {
  font-size: 1.5rem;
  margin-bottom: 1.2rem;
  color: #333;
}
.form-group {
  margin-bottom: 1rem;
}
input[type="file"] {
  display: block;
  padding: 0.5rem;
  font-size: 0.95rem;
}
button {
  padding: 0.7rem 1.5rem;
  background-color: #008080;
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}
button:hover {
  background-color: orange;
}
button:disabled {
  background-color: #aaa;
  cursor: not-allowed;
}
.info-text{
  background: #3c434a;
  padding: 0.5rem 1rem;
  color: white;
  text-decoration: none;
  font-size: 16px;
  font-family: 'Open sans', sans-serif;
  border-radius: 5px;
}
.info-text:hover{
  background: #008080;
  padding: 0.5rem 1rem;
  color: white;
  text-decoration: none;
  font-size: 16px;
  font-family: 'Open sans', sans-serif;
}
.error p{
  color: red;
}
</style>
