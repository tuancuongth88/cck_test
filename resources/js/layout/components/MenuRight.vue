<template>
  <div>
    <b-nav-item-dropdown right class="display-menu-right">
      <template #button-content>
        <em>
          <span>{{ username }}</span>
          <br>
          <span>({{ $t(role) }})</span>
        </em>
      </template>

      <b-dropdown-item @click="handleLogout()">{{ $t('MENU_RIGHT_LOGOUT') }}</b-dropdown-item>
    </b-nav-item-dropdown>
  </div>
</template>

<script>

import { MakeToast } from '../../utils/toastMessage';

export default {
  name: 'MenuRight',
  computed: {
    username() {
      return this.$store.getters.name;
    },
    role() {
      return this.$store.getters.role[0];
    },
  },
  methods: {
    handleLogout() {
      this.$store.dispatch('user/doLogout')
        .then(() => {
          this.$store.dispatch('permission/resetRoutes')
            .then(() => {
              this.$store.dispatch('permission/setCurrentRoutes', '')
                .then(() => {
                  this.$router.push('/login');
                })
                .catch(() => {
                  MakeToast({
                    variant: 'danger',
                    title: this.$t('DANGER'),
                    content: this.$t('TOAST_HAVE_ERROR'),
                  });
                });
            })
            .catch(() => {
              MakeToast({
                variant: 'danger',
                title: this.$t('DANGER'),
                content: this.$t('TOAST_HAVE_ERROR'),
              });
            });
        })
        .catch(() => {
          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: this.$t('TOAST_HAVE_ERROR'),
          });
        });
    },
  },
};
</script>

<style lang="scss" scoped>
    @import "../../scss/_variables.scss";

    .display-menu-right {
        span {
            font-size: 15px;
            color: $shark;
            font-weight: 700;
        }
    }
</style>
