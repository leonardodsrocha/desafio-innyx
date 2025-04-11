import { reactive } from 'vue';

const state = reactive({
  mensagem: null,
  tipoMensagem: null,
});

const token = reactive({
  valor: null,
});

const methods = {
  setMensagem(novaMensagem: null, novoTipo: null) {
    state.mensagem = novaMensagem;
    state.tipoMensagem = novoTipo;
  },
  setToken(novoValor: null) {
    token.valor = novoValor;
  },
};

export { state, token, methods };
