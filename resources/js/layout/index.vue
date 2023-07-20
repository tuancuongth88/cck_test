<template>
  <div>
    <Header />
    <Navbar />
    <div class="display-app container">
      <AppMain />
      <Loading :props-is-loading="propsIsLoading" />
    </div>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue';
import AppMain from './components/AppMain.vue';
import Header from './components/Header.vue';
import Loading from '@/components/Loading';
import { MakeToast } from '@/utils/toastMessage';
import constLang from '@/const/language';

export default {
  name: 'Layout',
  components: {
    Header,
    Loading,
    Navbar,
    AppMain,
  },
  computed: {
    keyChange() {
      return this.$route.fullPath;
    },
    propsIsLoading() {
      return this.$store.getters.loading;
    },
  },
  watch: {
    keyChange() {
      if (this.$route.fullPath === '/') {
        this.$router.push({ path: '/home' });
      }

      this.$store.dispatch('permission/setCurrentRoutes', this.$route.fullPath);
    },
  },
  async created() {
    // set language = ja
    this.$store
      .dispatch('app/setLanguage', constLang.JAPANESE)
      .then(() => {
        this.$i18n.locale = constLang.JAPANESE;
        // MakeToast({
        //   variant: 'success',
        //   title: 'Success',
        //   content: 'Change language successfully',
        // });
      })
      .catch(() => {
        MakeToast({
          variant: 'danger',
          title: 'Danger',
          content: 'Language change failed',
        });
      });

    this.$bus.on('responseStatusCode', (statusCode) => {
      if (statusCode === 401) {
        this.$store.commit('auth/logout');
      }
    });
    this.$bus.on('responseErrorCode', (errorCode) => {
      if (errorCode === 403) {
        this.$toast.error(this.$t('error_message.not_permission'));
        this.$store.commit('auth/logout');
      }
    });
  },
};
</script>

<style lang="scss" scoped>
.display-app {
  margin-top: 25px;
}
</style>
