import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import Home from '@/components/HomePage.vue';
import Login from '@/components/LoginView.vue';
import Logout from '@/components/LogoutView.vue';
import CategoriasIndex from '@/components/Categorias/CategoriasIndex.vue';
import CategoriasCreate from '@/components/Categorias/CategoriasCreate.vue';
import CategoriasEdit from '@/components/Categorias/CategoriasEdit.vue';
import ProdutosIndex from '@/components/produtos/ProdutosIndex.vue';
import ProdutosCreate from '@/components/produtos/ProdutosCreate.vue';
import ProdutosEdit from '@/components/produtos/ProdutosEdit.vue';
import http from '@/services/http';
import { ref } from 'vue';

const tokenValidado = ref(false);

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      noAuth: true,
    },
  },
  {
    path: '/logout',
    name: 'logout',
    component: Logout,
  },
  {
    path: '/categorias',
    name: 'Categorias',
    component: CategoriasIndex,
  },
  {
    path: '/categorias/create',
    name: 'CategoriasCreate',
    component: CategoriasCreate,
  },
  {
    path: '/categorias/edit/:id',
    name: 'CategoriasEdit',
    component: CategoriasEdit,
  },
  {
    path: '/produtos',
    name: 'Produtos',
    component: ProdutosIndex,
  },
  {
    path: '/produtos/create',
    name: 'ProdutosCreate',
    component: ProdutosCreate,
  },
  {
    path: '/produtos/edit/:id',
    name: 'ProdutosEdit',
    component: ProdutosEdit,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

const validarToken = async () => {
  tokenValidado.value = false;
  const token = localStorage.getItem('token');
  if (!token) return;
  try {
    const response = await http.post('/valida-token', {}, {
      headers: {
        Authorization: localStorage.getItem('token'),
      },
    });
    tokenValidado.value = response.data.valid;
    if (!tokenValidado.value) {
      localStorage.removeItem('token');
    }
  } catch (error) {
    console.log(error);
  }
};

router.beforeEach(async (to, from, next) => {
  if (to.meta.noAuth) {
    if (to.name === 'login') {
      await validarToken();
      if (tokenValidado.value) {
        next({ name: 'home' });
      } else {
        next();
      }
    }
  } else {
    await validarToken();
    if (!tokenValidado.value) {
      next({ name: 'login' });
    } else {
      next();
    }
  }
});

export default router;
