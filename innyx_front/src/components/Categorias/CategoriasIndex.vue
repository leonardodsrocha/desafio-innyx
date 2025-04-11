<template>
  <div>
    <h2>Categorias</h2>

    <div v-if="loading" class="overlay">
      <BSpinner label="Carregando..." variant="primary" class="spinner" />
    </div>

    <div class="d-flex align-items-center mb-2">
      <span class="mr-2">Mostrar </span>
      <BFormSelect
        v-model="perPage"
        :options="perPageValues"
        @change="searchCategorias"
        class="min-w-auto"
      />
    </div>

    <BAlert v-if="mensagem" :variant="tipoMensagem" :model-value="true">{{ mensagem }}</BAlert>

    <div class="d-flex mb-2">
      <input v-model="searchTerm" placeholder="Pesquisar categoria" class="mr-2" />
      <BButton variant="primary" @click="searchCategorias">Pesquisar</BButton>
    </div>

    <BTable
      :items="categorias.value"
      :fields="[
        {
          label: 'Código',
          key: 'id',
        },
        {
          label: 'Categoria',
          key: 'nome',
        },
        {
          label: 'Ações',
          key: 'acao'
        },
      ]"
    >
    <template #cell(acao)="row">
      <div class="d-flex gap-2 flex-row-reverse">
          <router-link :to="'/categorias/edit/' + row.item.id">
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
      @click="loadCategorias"
    />

    <div class="d-flex gap-2 flex-row-reverse">
      <router-link to="/categorias/create">
        <BButton variant="primary">Nova Categoria</BButton>
      </router-link>
    </div>

    <BModal v-model="showModal" title="Confirmar Exclusão" hide-footer>
      <p>Tem certeza de que deseja excluir esta categoria?</p>
      <div class="d-flex gap-2 flex-row-reverse">
        <BButton @click="cancelDelete">Cancelar</BButton>
        <BButton variant="primary" @click="confirmDelete">Confirmar</BButton>
      </div>
    </BModal>
  </div>
</template>

<script setup>
import http from '@/services/http';
import {
  ref, reactive, onMounted, computed, watchEffect,
} from 'vue';
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
const showModal = ref(false);
const idParaDeletar = ref(null);
const categorias = reactive([]);
const perPageValues = [
  { value: 5, text: '5 categorias' },
  { value: 10, text: '10 categorias' },
  { value: 25, text: '25 categorias' },
  { value: 50, text: '50 categorias' },
  { value: 100, text: '100 categorias' },
];
const currentPage = ref(1);
const totalRows = ref(1);
const perPage = ref(5);

const loadCategorias = async () => {
  try {
    loading.value = true;
    const response = await http.get('/categorias', {
      params: {
        per_page: perPage.value,
        page: currentPage.value,
        nome: searchTerm.value,
      },
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    categorias.value = response.data.data;
    totalRows.value = response.data.meta.total;
    currentPage.value = response.data.meta.current_page;
  } catch (error) {
    console.error('Erro ao carregar categorias:', error);
  } finally {
    loading.value = false;
  }
};

const searchCategorias = async () => {
  currentPage.value = 1;
  loadCategorias();
};

const cancelDelete = () => {
  showModal.value = false;
  idParaDeletar.value = null;
};

const confirmDelete = async () => {
  if (idParaDeletar.value !== null) {
    try {
      loading.value = true;
      await http.delete(`/categorias/${idParaDeletar.value}`, {
        headers: {
          Authorization: localStorage.getItem('token'),
        },
      });
      loadCategorias();
      storeMethods.setMensagem('Categoria excluída com sucesso!', 'success');
    } catch (error) {
      storeMethods.setMensagem(`Erro ao excluir categoria:<br>${error.response.data.message}`, 'danger');
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
  loadCategorias();
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
