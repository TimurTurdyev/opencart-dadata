<template>
  <div id="app" class="min_height">
    <navbar :base="location_base" :token="token"/>
    <div class="container is-fluid">
      <br>
      <BodyContent :base="location_base" :token="token"/>
      <br>
    </div>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import BodyContent from './components/BodyContent.vue'

// eslint-disable-next-line no-unused-vars
let location_base = ''
// eslint-disable-next-line no-unused-vars
let token = ''
if (process.env.NODE_ENV !== 'production') {
  location_base = 'http://opencart23.local/admin/'
  token = '&token=vtd76ObZsGbKWCS6rssl4TlO4hPaHOjJ'
} else {
  location_base = location.origin + '/admin/'
  token = location.href.replace(/.{0,}(&token=.*|&user_token=.*)/gi, '$1').replace(/#.+/gi, '')
}

export default {
  name: 'App',
  components: {
    Navbar,
    BodyContent
  },
  data() {
    return {
      location_base,
      token
    }
  }
}
</script>

<style>
#app {
  position: relative;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
  font-size: 16px;
  line-height: 1.5;
  height: 100%;
  background: #ECF0F3;
}
.min_height {
  min-height: 100vh;
}
</style>
