<template>
  <div id="app">
    <header>
      <h1>Manutenção de Produtos</h1>
    </header>
    <BNav v-show="token">
      <BNavItem href="/">Home</BNavItem>
      <BNavItem href="/categorias">Categorias</BNavItem>
      <BNavItem href="/produtos">Produtos</BNavItem>
      <BNavItem href="/logout">Sair</BNavItem>
    </BNav>
    <main>
      <router-view></router-view>
    </main>
    <footer>
      &copy; 2023 INNYX Tecnologia
    </footer>
  </div>
</template>

<script setup>
const token = localStorage.getItem('token');
</script>

<style>
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh; /* Garante que o conteúdo ocupe pelo menos 100% da altura da tela */
}

header {
  background-color: #42b983;
  padding: 20px 0;
  text-align: center;
  color: white;
}

main {
  flex-grow: 1; /* Faz com que o conteúdo principal cresça para ocupar todo o espaço disponível */
}

footer {
  background-color: #333;
  padding: 10px 0;
  color: white;
  text-align: center;
}

.nav {
  background-color: #333; /* Cor de fundo */
  padding: 10px; /* Espaçamento interno */
}

.nav .nav-item {
  margin: 0 15px; /* Espaçamento entre os itens */
}

.nav .nav-link {
  color: white; /* Cor do texto */
  font-weight: bold; /* Estilo da fonte */
  text-decoration: none; /* Remover sublinhado dos links */
  transition: color 0.3s; /* Transição de cor ao passar o mouse */
}

.nav .nav-link:hover {
  color: #42b983; /* Cor ao passar o mouse */
  text-decoration: underline; /* Sublinhar ao passar o mouse */
}
</style>
