<template>
  <div>
    <h2>Produtos</h2>

    <div v-if="loading" class="overlay">
      <BSpinner label="Carregando..." variant="primary" class="spinner" />
    </div>

    <div class="d-flex align-items-center mb-2">
      <span class="mr-2">Mostrar </span>
      <BFormSelect
        v-model="perPage"
        :options="perPageValues"
        @change="searchProdutos"
        class="min-w-auto"
      />
    </div>

    <BAlert v-if="mensagem" :variant="tipoMensagem" :model-value="true">{{ mensagem }}</BAlert>

    <div class="d-flex mb-2">
      <input v-model="searchTerm" placeholder="Pesquisar produto" class="mr-2" />
      <input v-model="searchDescription" placeholder="Pesquisar descrição" class="mr-2" />
      <input v-model="searchCategory" placeholder="Pesquisar categoria" class="mr-2" />
      <BButton variant="primary" @click="searchProdutos">Pesquisar</BButton>
    </div>

    <BTable
      :items="produtos.value"
      :fields="[
        {
          label: 'Código',
          key: 'id',
        },
        {
          label: 'Nome',
          key: 'nome',
        },
        {
          label: 'Descrição',
          key: 'descricao',
        },
        {
          label: 'Preço',
          key: 'preco',
        },
        {
          label: 'Data de Validade',
          key: 'dt_validade',
        },
        {
          label: 'Categoria',
          key: 'categoria.nome',
        },
        {
          label: 'Imagem',
          key: 'imagem_url'
        },
        {
          label: 'Ações',
          key: 'acao',
        },
      ]"
    >
    <template #cell(imagem_url)="row">
      <img
      :src="row.item.imagem_url"
      alt="Imagem do Produto"
      style="max-width: 100px;
      max-height: 100px;" />
    </template>
      <template #cell(acao)="row">
        <div class="d-flex gap-2 flex-row-reverse">
          <router-link :to="'/produtos/edit/' + row.item.id">
            <BButton variant="primary">Editar</BButton>
          </router-link>
          <BButton variant="danger" @click="showDeleteModal(row.item.id)">Excluir</BButton>
        </div>
      </template>
    </BTable>

    <BPagination
      v-model="currentPage"
      :total-rows="totalRows"
      :per-page="perPage"
      first-text="Primeiro"
      prev-text="Anterior"
      next-text="Próximo"
      last-text="Último"
      class="mt-4"
      @click="loadProdutos"
    />

    <div class="d-flex gap-2 flex-row-reverse">
      <router-link to="/produtos/create">
        <BButton variant="primary">Novo Produto</BButton>
      </router-link>
    </div>

    <BModal v-model="showModal" title="Confirmar Exclusão" hide-footer>
      <p>Tem certeza de que deseja excluir este produto?</p>
      <div class="d-flex gap-2 flex-row-reverse">
        <BButton @click="cancelDelete">Cancelar</BButton>
        <BButton variant="primary" @click="confirmDelete">Confirmar</BButton>
      </div>
    </BModal>
  </div>
</template>

<script setup>
import {
  ref, reactive, onMounted, computed, watchEffect,
} from 'vue';
import http from '@/services/http';
import { state as storeState, methods as storeMethods } from '@/stores';

const mensagem = computed(() => storeState.mensagem);
const tipoMensagem = computed(() => storeState.tipoMensagem);
watchEffect(() => {
  if (mensagem.value) {
    setTimeout(() => {
      storeMethods.setMensagem(null, null);
    }, 2000);
  }
});

const loading = ref(false);
const searchTerm = ref('');
const searchDescription = ref('');
const searchCategory = ref('');
const showModal = ref(false);
const idParaDeletar = ref(null);
const produtos = reactive([]);
const perPageValues = [
  { value: 5, text: '5 produtos' },
  { value: 10, text: '10 produtos' },
  { value: 25, text: '25 produtos' },
  { value: 50, text: '50 produtos' },
  { value: 100, text: '100 produtos' },
];
const currentPage = ref(1);
const totalRows = ref(1);
const perPage = ref(5);

const loadProdutos = async () => {
  try {
    loading.value = true;
    const response = await http.get('/produtos', {
      params: {
        per_page: perPage.value,
        page: currentPage.value,
        nome: searchTerm.value,
        descricao: searchDescription.value,
        categoria: searchCategory.value,
      },
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    produtos.value = response.data.data;
    totalRows.value = response.data.meta.total;
    currentPage.value = response.data.meta.current_page;
  } catch (error) {
    console.error('Erro ao carregar produtos:', error);
  } finally {
    loading.value = false;
  }
};

const searchProdutos = async () => {
  currentPage.value = 1;
  loadProdutos();
};

const cancelDelete = () => {
  showModal.value = false;
  idParaDeletar.value = null;
};

const confirmDelete = async () => {
  if (idParaDeletar.value !== null) {
    try {
      loading.value = true;
      await http.delete(`/produtos/${idParaDeletar.value}`, {
        headers: {
          Authorization: localStorage.getItem('token'),
        },
      });
      loadProdutos();
      storeMethods.setMensagem('Produto excluído com sucesso!', 'success');
    } catch (error) {
      storeMethods.setMensagem(`Erro ao excluir produto:<br>${error.response.data.message}`, 'danger');
    } finally {
      cancelDelete();
      loading.value = false;
    }
  }
};

const showDeleteModal = (id) => {
  showModal.value = true;
  idParaDeletar.value = id;
};

onMounted(() => {
  loadProdutos();
});
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
