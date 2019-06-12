<template>
  <div class="page-container">
    <k-navbar></k-navbar>
    <div class="container">
      <router-view />
    </div>
    <k-loading></k-loading>
  </div>
</template>

<script>
import kNavbar from './Navbar';
import kLoading from './Loading'
export default {
  components: {
    kNavbar,
    kLoading
  }
}
</script>
