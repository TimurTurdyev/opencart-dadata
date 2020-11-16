<template>
  <nav class="navbar is-white">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item brand-text" :href="base + 'index.php?route=extension/extension' + token">
          Назад в модули
        </a>
      </div>
      <div class="navbar-menu">
        <div class="navbar-end">
          <a class="navbar-item">Документация</a>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  props: {
    base: String,
    token: String
  },
}
</script>
<style>
nav.navbar {
  border-top: 4px solid #276cda;
  margin-bottom: 1rem;
}
</style>