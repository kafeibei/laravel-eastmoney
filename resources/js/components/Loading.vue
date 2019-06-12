<template>
  <div class="f-loading" v-if="isLoading">
    <div class="spinner">
      <div class="bounce1"></div>
      <div class="bounce2"></div>
      <div class="bounce3"></div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    isLoading () {
      return this.$store.state.global.isLoading
    }
  }
}
</script>

<style lang="scss" scoped>
$primary: #1b7fe4;
.f-loading {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 72px;
  z-index: 999;
  transform:translate(-8px, -36px);
  .spinner {
    width: 70px;
    text-align: center;
  }

  .spinner > div {
    width: 12px;
    height: 12px;
    background-color: $primary;

    border-radius: 100%;
    display: inline-block;
    animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  }

  .spinner .bounce1 {
    animation-delay: -0.32s;
  }

  .spinner .bounce2 {
    animation-delay: -0.16s;
  }

  @keyframes sk-bouncedelay {
    0%, 80%, 100% {
      transform: scale(0);
    }
    40% {
      transform: scale(1.0);
    }
  }
}
</style>
