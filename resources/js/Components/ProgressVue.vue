<template>
  <div v-if="loading" class="progress-wrapper">
    <div class="progress-bar" :style="{ width: progress + '%' }"></div>
    <span class="progress-text">{{ progress }} %</span>
  </div>

</template>
<style>

.progress-wrapper {
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
</style>
