<template>
  <div class="header">
    <div class="container">
      <div class="header-container">
        <h1 class="header-title mb-0">海外人材マッチングシステム(仮)</h1>
        <div class="header-options">
          <!-- <div class="language">
            <multiple-language />
          </div> -->
          <div class="user-info">
            <span v-if="profile.type === 1">{{
              'システム管理者/System management'
            }}</span>
            <span v-if="profile.type === 2">{{
              '企業管理/Company management'
            }}</span>
            <span v-if="profile.type === 3">{{
              '運営事務局/HR Management'
            }}</span>
            <span v-if="profile.type === 4">
              {{ profile.company.company_name }}
            </span>
            <span v-if="profile.type === 5">
              {{ profile.hr_organization.corporate_name_ja }}
            </span>
          </div>
          <div class="header-icon">
            <router-link class="nav-favorite" :to="'/my-favorite/list'">
              <b-icon icon="heart-fill" class="rounded" />
              <label class="label"> {{ $t('NAVBAR.FAVORITE') }} </label>
            </router-link>
          </div>
          <div class="header-icon">
            <b-dropdown variant="link" toggle-class="text-decoration-none">
              <template #button-content>
                <div>
                  <b-icon icon="gear-fill" class="rounded" />
                </div>
                <label class="label">{{ $t('NAVBAR.SETTING') }}</label>
              </template>
              <b-dropdown-item href="#">{{
                $t('NAVBAR.PROFILE')
              }}</b-dropdown-item>
              <b-dropdown-item href="#" @click="goToChangePassWord">{{
                $t('NAVBAR.CHANGE_PASSWORD')
              }}</b-dropdown-item>
            </b-dropdown>
          </div>
          <div class="header-icon header-icon-logout">
            <button class="btn-logout" @click="handleLogout()">
              <!-- <b-icon icon="box-arrow-right" class="rounded" /> -->
              <img
                :src="require('@/assets/images/login/logout.png')"
                alt="password confirm"
              >
              <label class="label">{{ $t('NAVBAR.LOGOUT') }} </label>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import MultipleLanguage from './MultipleLanguage.vue';
import { MakeToast } from '../../utils/toastMessage';
export default {
  name: 'Header',

  components: {
    // MultipleLanguage,
  },

  computed: {
    profile() {
      return this.$store.getters.profile;
    },
    // role() {
    //   return this.$store.getters.role[0];
    // },
  },

  methods: {
    handleLogout() {
      this.$store
        .dispatch('user/doLogout')
        .then(() => {
          this.$store
            .dispatch('permission/resetRoutes')
            .then(() => {
              this.$store
                .dispatch('permission/setCurrentRoutes', '')
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
    goToChangePassWord() {
      console.log('goToChangePassWord');
      this.$router.push({ path: `/change-password` }, (onAbort) => {});
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../scss/_variables.scss';
.header-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 0px;
  .header-title {
    font-weight: 400;
    font-size: 30px;
  }
  .language {
    margin-right: 15px;
  }
  .user-info {
    margin-right: 15px;
  }
  .header-options {
    display: flex;
    align-items: center;
    .header-icon {
      height: 56px;
      border-left: 1px solid #d5d5d5;
      padding: 0px 15px;
      &.header-icon-logout {
        border-right: 1px solid #d5d5d5;
      }
      label {
        margin-top: 6px;
        margin-bottom: 0;
        font-weight: 400;
        font-size: 11px;
        color: #333;
      }
      svg {
        width: 34px;
        height: 34px;
        color: #1d266a;
      }
      a:hover {
        text-decoration: unset;
      }
      .nav-favorite {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding-top: 2px;
        label {
          margin-top: 8px;
        }
      }
      .btn-logout {
        padding: 0;
        border: 0;
        background-color: transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        img {
          width: 32px;
        }
      }
    }
  }
}
</style>
