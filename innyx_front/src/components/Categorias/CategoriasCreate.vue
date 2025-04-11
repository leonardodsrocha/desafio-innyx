<template>
  <div>
    <h2>Criar Nova Categoria</h2>
    <div v-if="loading" class="overlay">
      <BSpinner label="Carregando..." variant="primary" class="spinner" />
    </div>
    <BAlert v-if="mensagem" variant="danger" :model-value="true">
      <span v-html="mensagem.texto"></span>
    </BAlert>
    <div class="mb-3">
      <input v-model="novaCategoria.nome" class="form-control" placeholder="Nome da categoria" />
    </div>
    <div class="d-flex gap-2 flex-row-reverse">
      <BButton @click="cancelar">Cancelar</BButton>
      <BButton variant="primary" @click="salvarCategoria">Salvar</BButton>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import http from '@/services/http';
// eslint-disable-next-line import/no-extraneous-dependencies
import { useRouter } from 'vue-router';
import { methods as storeMethods } from '@/stores';

const loading = ref(false);
const novaCategoria = ref({ nome: '' });
const mensagem = ref(null);
const router = useRouter();

const transformarErrosEmString = (errosObj) => {
  let mensagemErro = '';

  const chavesErros = Object.keys(errosObj);

  chavesErros.forEach((campo) => {
    const mensagens = errosObj[campo];
    mensagens.forEach((mens) => {
      mensagemErro += `${mens}<br>\n`;
    });
  });

  return mensagemErro.trim();
};

const salvarCategoria = async () => {
  try {
    loading.value = true;
    await http.post('/categorias', novaCategoria.value, {
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    storeMethods.setMensagem('Categoria criada com sucesso!', 'success');
    router.push({ path: '/categorias' });
  } catch (error) {
    mensagem.value = { texto: transformarErrosEmString(error.response.data.errors) };
  } finally {
    loading.value = false;
  }
};

const cancelar = () => {
  router.push('/categorias');
};
</script>
