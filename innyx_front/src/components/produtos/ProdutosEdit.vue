<!-- eslint-disable vuejs-accessibility/form-control-has-label -->
<template>
    <div>
      <h2>Editar Produto</h2>
      <div v-if="loading" class="overlay">
        <BSpinner label="Carregando..." variant="primary" class="spinner" />
      </div>
      <BAlert v-if="mensagem" variant="danger" :model-value="true">
        <span v-html="mensagem.texto"></span>
      </BAlert>
      <div class="mb-3">
        <input v-model="produto.nome" class="form-control" placeholder="Nome do produto" />
      </div>
      <div class="mb-3">
        <input
        v-model="produto.descricao" class="form-control" placeholder="Descrição do produto" />
      </div>
      <div class="mb-3">
        <input v-model="produto.preco" class="form-control" placeholder="Preço do produto" />
      </div>
      <div class="mb-3">
        <input v-model="produto.dt_validade" type="date" class="form-control" />
      </div>
      <div class="mb-3">
        <select v-model="produto.categoria_id" class="form-control">
          <option value="" disabled>Selecione a categoria</option>
          <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
            {{ categoria.nome }}
          </option>
        </select>
      </div>
      <!-- <div class="mb-3">
        <input type="file" ref="novaImagem" @change="novaImagemSelecionada" />
      </div>-->
      <div class="mb-3">
        <img v-if="produto.imagem" :src="produto.imagem" alt="Imagem do Produto"
        style="height: 100px;" />
      </div>
      <!-- <div class="mb-3">
        <button @click="removerImagem" v-if="produto.imagem">Remover Imagem</button>
      </div>-->
      <div class="d-flex gap-2 flex-row-reverse">
        <BButton @click="cancelar">Cancelar</BButton>
        <BButton variant="primary" @click="atualizarProduto">Salvar</BButton>
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
const mensagem = ref(null);
const produto = ref({
  id: null,
  nome: '',
  descricao: '',
  preco: null,
  dt_validade: '',
  categoria_id: null,
  // imagem: null,
});
const categorias = ref([]);
const router = useRouter();
const route = useRoute();
const produtoId = ref(route.params.id);

const novaImagem = ref(null);

const novaImagemSelecionada = () => {
  const fileInput = novaImagem.value;
  if (fileInput && fileInput.files.length > 0) {
    // Carrega a nova imagem selecionada
    const file = fileInput.files[0];
    const reader = new FileReader();
    reader.onload = (event) => {
      produto.value = {
        ...produto.value,
        magem: event.target.result,
      };
      novaImagem.value = event.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const removerImagem = () => {
  produto.value = {
    ...produto.value,
    imagem: null,
  };
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

const formatarDataParaInputDate = (data) => {
  if (!data) return null;

  const partes = data.split('/');
  if (partes.length === 3) {
    const [dia, mes, ano] = partes;
    return `${ano}-${mes}-${dia}`;
  }

  return null;
};

const carregarProduto = async () => {
  try {
    loading.value = true;
    const response = await http.get(`/produtos/${produtoId.value}`, {
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    const produtoData = response.data.data;
    produto.value = {
      ...produto.value,
      id: produtoData.id,
      nome: produtoData.nome,
      descricao: produtoData.descricao,
      preco: produtoData.preco,
      dt_validade: formatarDataParaInputDate(produtoData.dt_validade),
      categoria_id: produtoData.categoria.id,
      // imagem: produtoData.imagem_url,
    };
  } catch (error) {
    mensagem.value = { texto: 'Erro ao carregar produto.' };
  } finally {
    loading.value = false;
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

const atualizarProduto = async () => {
  try {
    loading.value = true;
    await http.put(`/produtos/${produtoId.value}`, produto.value, {
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    storeMethods.setMensagem('Produto atualizado com sucesso!', 'success');
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
  carregarProduto();
  carregarCategorias();
});
</script>
