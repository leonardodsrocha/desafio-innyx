<template>
  <div>
    <h2>Editar Categoria</h2>
    <div v-if="loading" class="overlay">
      <BSpinner label="Carregando..." variant="primary" class="spinner" />
    </div>
    <BAlert v-if="mensagem" variant="danger" :model-value="true">
      <span v-html="mensagem.texto"></span>
    </BAlert>
    <div class="mb-3">
      <input v-model="categoria.nome" class="form-control" placeholder="Nome da categoria" />
    </div>
    <div class="d-flex gap-2 flex-row-reverse">
      <BButton @click="cancelar">Cancelar</BButton>
      <BButton variant="primary" @click="atualizarCategoria">Salvar</BButton>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import http from '@/services/http';
// eslint-disable-next-line import/no-extraneous-dependencies
import { useRoute, useRouter } from 'vue-router';
import { methods as storeMethods } from '@/stores';

const loading = ref(false);
const categoria = ref({ id: null, nome: '' });
const mensagem = ref(null);
const router = useRouter();
const route = useRoute();
const categoriaId = ref(route.params.id);

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

const carregarCategoria = async () => {
  try {
    loading.value = true;
    const response = await http.get(`/categorias/${categoriaId.value}`, {
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    categoria.value = {
      ...categoria.value, id: response.data.data.id, nome: response.data.data.nome,
    };
  } catch (error) {
    mensagem.value = { texto: 'Erro ao carregar categoria.' };
  } finally {
    loading.value = false;
  }
};

const atualizarCategoria = async () => {
  try {
    loading.value = true;
    await http.put(`/categorias/${categoriaId.value}`, categoria.value, {
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    storeMethods.setMensagem('Categoria atualizada com sucesso!', 'success');
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

onMounted(() => {
  carregarCategoria();
});
</script>
