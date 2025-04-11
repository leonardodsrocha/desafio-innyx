<template>
  <div class="overlay">
    <BSpinner label="Carregando..." variant="primary" class="spinner" />
  </div>
</template>
<script setup>
import http from '@/services/http';

try {
  await http.post('/logout', {}, {
    headers: {
      Authorization: localStorage.getItem('token'),
    },
  });
  localStorage.removeItem('token');
  window.location.reload();
} catch (error) {
  console.log(error);
}

</script>
<style>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}
</style>
