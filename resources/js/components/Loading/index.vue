<template>
  <div
    v-show="isLoading"
    class="site-loading loading"
    dusk="loading"
  >
    <div class="loader" />
  </div>
</template>

<script>
export default {
  name: 'Loading',
  props: {
    propsIsLoading: {
      required: true,
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isLoading: false,
    };
  },
  watch: {
    propsIsLoading(value) {
      this.isLoading = value;
    },
  },
};
</script>

<style lang="scss" scoped>

.site-loading {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ffffffc7;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: -1;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  -moz-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}

.loading {
  opacity: 1;
  visibility: visible;
  z-index: 9999;
}

.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #0282CB;
  width: 100px;
  height: 100px;
  -webkit-animation: spin 1s linear infinite;
  animation: spin 1s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% {
    -webkit-transform: rotate(0deg);
  }

  100% {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

</style>
