<!-- eslint-disable vuejs-accessibility/form-control-has-label -->
<template>
    <div>
      <h2>Criar Novo Produto</h2>
      <div v-if="loading" class="overlay">
        <BSpinner label="Carregando..." variant="primary" class="spinner" />
      </div>
      <BAlert v-if="mensagem" variant="danger" :model-value="true">
        <span v-html="mensagem.texto"></span>
      </BAlert>
      <div class="mb-3">
        <input v-model="novoProduto.nome" class="form-control" placeholder="Nome do produto" />
      </div>
      <div class="mb-3">
        <input
        v-model="novoProduto.descricao" class="form-control" placeholder="Descrição do produto" />
      </div>
      <div class="mb-3">
        <input v-model="novoProduto.preco" class="form-control" placeholder="Preço do produto" />
      </div>
      <div class="mb-3">
        <input v-model="novoProduto.dt_validade" type="date" class="form-control" />
      </div>
      <div class="mb-3">
        <select v-model="novoProduto.categoria_id" class="form-control">
            <option value="" disabled selected>Selecione a categoria</option>
            <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                {{ categoria.nome }}</option>
        </select>
      </div>
      <div class="mb-3">
        <input type="file" @change="handleImageChange" class="form-control" />
      </div>
      <div class="d-flex gap-2 flex-row-reverse">
        <BButton @click="cancelar">Cancelar</BButton>
        <BButton variant="primary" @click="salvarProduto">Salvar</BButton>
      </div>
    </div>
  </template>

<script setup>
import { ref, onMounted } from 'vue';
import http from '@/services/http';
// eslint-disable-next-line import/no-extraneous-dependencies
import { useRouter } from 'vue-router';
import { methods as storeMethods } from '@/stores';

const loading = ref(false);
const mensagem = ref(null);
const novoProduto = ref({
  nome: '',
  descricao: '',
  preco: null,
  dt_validade: '',
  categoria_id: null,
});
const categorias = ref([]);
const router = useRouter();
const imagemSelecionada = ref(null);

const handleImageChange = (event) => {
  const [file] = event.target.files;
  if (file) {
    imagemSelecionada.value = file;
  }
};

const carregarCategorias = async () => {
  try {
    loading.value = true;
    const response = await http.get('/categorias/all', {
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    categorias.value = response.data.data;
  } catch (error) {
    console.error('Erro ao carregar categorias:', error);
  } finally {
    loading.value = false;
  }
};

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

const salvarProduto = async () => {
  try {
    loading.value = true;
    const formData = new FormData();
    formData.append('nome', novoProduto.value.nome);
    formData.append('descricao', novoProduto.value.descricao);
    formData.append('preco', novoProduto.value.preco);
    formData.append('dt_validade', novoProduto.value.dt_validade);
    formData.append('categoria_id', novoProduto.value.categoria_id);
    formData.append('imagem', imagemSelecionada.value);

    await http.post('/produtos', formData, {
      headers: {
        Authorization: localStorage.getItem('token'),
        'Content-Type': 'multipart/form-data',
      },
    });
    storeMethods.setMensagem('Produto criado com sucesso!', 'success');
    router.push({ path: '/produtos' });
  } catch (error) {
    mensagem.value = { texto: transformarErrosEmString(error.response.data.errors) };
  } finally {
    loading.value = false;
  }
};

const cancelar = () => {
  router.push('/produtos');
};

onMounted(() => {
  carregarCategorias();
});
</script>
