<template>
  <div class="header">
    <div class="container">
      <div class="header-container">
        <h1 class="header-title mb-0">
          <img
            :src="require('@/assets/images/login/bd-logo.png')"
            alt="bd-logo"
            class="bd-logo"
          >
        </h1>
        <div class="header-options">
          <!-- <div class="language">
            <multiple-language />
          </div> -->
          <div class="user-info">
            <template v-if="profile.type === ROLE.TYPE_SUPER_ADMIN">
              {{ $t('SUPER_ADMIN') }}
            </template>
            <template v-if="profile.type === ROLE.TYPE_COMPANY_ADMIN">
              {{ $t('COMPANY_ADMIN') }}
            </template>
            <template v-if="profile.type === ROLE.TYPE_HR_MANAGER">
              {{ $t('HR_ADMIN') }}
            </template>
            <template v-if="profile.type === ROLE.TYPE_COMPANY">
              {{ `${profile.company.company_name}${$t('SAMA')}` || '' }}
            </template>
            <template v-if="profile.type === ROLE.TYPE_HR">
              {{ `${profile.hr_organization.corporate_name_en}${$t('SAMA')}` || '' }}
            </template>
          </div>
          <div
            v-if="[ROLE.TYPE_COMPANY, ROLE.TYPE_HR].includes(profile.type)"
            id="btn-favorite"
            class="header-icon"
            @click="handleClickToNewTab"
          >
            <router-link class="nav-favorite" :to="'/my-favorite/list'">
              <b-icon icon="heart-fill" class="rounded icon-hover" />
              <label class="label"> {{ $t('NAVBAR.FAVORITE') }} </label>
            </router-link>
          </div>
          <div class="header-icon">
            <b-dropdown
              id="btn-setting"
              variant="link"
              toggle-class="text-decoration-none"
              dusk="user_setting"
            >
              <template #button-content>
                <div>
                  <b-icon icon="gear-fill" class="rounded icon-hover" />
                </div>
                <label class="label">{{ $t('NAVBAR.SETTING') }}</label>
              </template>
              <b-dropdown-item
                v-if="checkType"
                id="go-to-profile-page"
                href="#"
                @click="goToProfilePage"
              >{{ $t('NAVBAR.PROFILE') }}</b-dropdown-item>
              <b-dropdown-item
                href="#"
                dusk="change_password"
                @click="goToChangePassWord"
              >{{ $t('NAVBAR.CHANGE_PASSWORD') }}</b-dropdown-item>
            </b-dropdown>
          </div>
          <div class="header-icon header-icon-logout">
            <button class="btn-logout icon-hover" @click="handleLogout()">
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
import ROLE from '@/const/role.js';
export default {
  name: 'Header',

  components: {
    // MultipleLanguage,
  },
  data() {
    return {
      ROLE: ROLE,
    };
  },

  computed: {
    profile() {
      return this.$store.getters.profile;
    },
    checkType() {
      const manager_type = [1, 2, 3];
      const PROFILE = this.$store.getters.profile;
      const current_type = PROFILE.type;
      return !manager_type.includes(current_type);
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

          // Reset giá trị job_search_data = null
          this.$store.dispatch('jobSearch/setJobSearchData', null);
          this.$store.dispatch('hrSearchQuery/setSearchParams', null);
          this.$store.dispatch(
            'app/setRedirectToModalSelectJobsToOffer',
            false
          );
          this.$store.dispatch('app/saveTabIndex', null);
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
      this.$router.push({ path: `/change-password` }, (onAbort) => {});
    },
    goToProfilePage() {
      const current_type = this.profile.type;
      if (current_type === 4) {
        if (this.profile.company) {
          const company_id = this.profile.company.id;
          this.$router.push(
            { path: `/company/detail/${company_id}` },
            (onAbort) => {}
          );
        }
      }
      if (current_type === 5) {
        if (this.profile.hr_organization) {
          const hr_org_id = this.profile.hr_organization.id;
          this.$router.push(
            { path: `/hr-organization/detail/${hr_org_id}` },
            (onAbort) => {}
          );
        }
      }
    },
    async handleClickToNewTab() {
      await this.$store.dispatch('hrSearchQuery/setSearchParams', null);
      await this.$store.dispatch('jobSearch/setJobSearchData', null);
      this.$store.dispatch('app/setRedirectToModalSelectJobsToOffer', false);
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
  .bd-logo {
    width: 250px;
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
