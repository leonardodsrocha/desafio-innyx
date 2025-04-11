<!-- eslint-disable vuejs-accessibility/form-control-has-label -->
<template>
  <form @submit.prevent="login" class="d-flex flex-column align-items-center">
    <h2>Login</h2>
    <div v-if="loading" class="overlay">
        <BSpinner label="Carregando..." variant="primary" class="spinner" />
      </div>
    <div class="mb-3">
      <input
      type="text" class="form-control" placeholder="Informe seu E-Mail" v-model="usuario.email">
    </div>
    <div class="mb-3">
      <input
      type="password" class="form-control" placeholder="Informe sua senha"
      v-model="usuario.password">
    </div>
    <button type="submit" class="btn btn-primary">Login
    </button>
    <div v-if="erro" class="alert alert-danger mt-3" role="alert">
      {{ erro }}
    </div>
  </form>
</template>

<script setup>
import http from '@/services/http';
import { ref } from 'vue';

const usuario = ref({
  email: '',
  password: '',
});

const loading = ref(false);
const erro = ref('');

const login = async () => {
  try {
    loading.value = true;
    const response = await http.post('/login', usuario.value);
    localStorage.setItem('token', `Bearer ${response.data.token}`);
    window.location.reload();
  } catch (error) {
    console.log(error);
    if (error.response && error.response.data && error.response.data.message) {
      erro.value = error.response.data.message;
    } else {
      erro.value = 'Erro desconhecido ao efetuar login.';
    }
  } finally {
    loading.value = false;
  }
};

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
