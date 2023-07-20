<template>
  <div class="display-user-management-create">
    <b-col>
      <b-row>
        <!-- Button back -->
        <div class="btn-return" :disabled="overlay.show" @click="handleBackList()">
          <i class="fad fa-backward" /> <span>{{ $t('BUTTON_RETURN') }}</span>
        </div>
      </b-row>

      <!-- Zone Input -->
      <b-row>
        <div class="display-create-user">
          <b-overlay
            :show="overlay.show"
            :variant="overlay.variant"
            :opacity="overlay.opacity"
            :blur="overlay.blur"
            :rounded="overlay.sm"
          >
            <div class="zone-create-user">

              <!-- Input Name -->
              <div>
                <label for="input-create-user-name">
                  {{ $t('USER_MANAGEMENT_CREATE_NAME_REQUIRED') }}
                </label>

                <b-form-input
                  id="input-create-user-name"
                  v-model="User.name"
                  type="text"
                  :disabled="overlay.show"
                  :class="{ 'validate-warning': validEmptyOrWhiteSpace(User.name) }"
                />
              </div>

              <!-- Input Email -->
              <div>
                <label for="input-create-user-email">
                  {{ $t('USER_MANAGEMENT_CREATE_EMAIL_REQUIRED') }}
                </label>

                <b-form-input
                  id="input-create-user-email"
                  v-model="User.email"
                  type="text"
                  :disabled="overlay.show"
                  :class="{ 'validate-warning': !validEmail(User.email) }"
                />
              </div>

              <!-- Input Password -->
              <div>
                <label for="input-create-user-password">
                  {{ $t('USER_MANAGEMENT_EDIT_PASSWORD_UPDATE') }}
                </label>

                <b-form-input
                  id="input-create-user-password"
                  v-model="User.password"
                  type="password"
                  :disabled="overlay.show"
                  :class="{ 'validate-warning': !validPassword(User.password) }"
                />
              </div>

              <!-- Select Authority -->
              <div>
                <label for="input-create-user-authority">
                  {{ $t('USER_MANAGEMENT_CREATE_AUTHORITY_REQUIRED') }}
                </label>

                <b-form-select
                  id="input-create-user-authority"
                  v-model="User.authority"
                  :class="{ 'validate-warning': !validateNumberMoreThanZero(User.authority) }"
                  :disabled="overlay.show"
                >
                  <b-form-select-option :value="null" disabled>{{ $t('PLEASE_SELECT_TEXT') }}</b-form-select-option>
                  <b-form-select-option
                    v-for="(itemAuthority, indexAuthority) in constAuthority.AUTHORITY"
                    :key="indexAuthority + 1"
                    :value="indexAuthority + 1"
                  >
                    {{ $t(itemAuthority) }}
                  </b-form-select-option>
                </b-form-select>
              </div>

            </div>
          </b-overlay>
        </div>
      </b-row>

      <b-row>
        <div class="btn-sign-up">
          <b-button :disabled="overlay.show" @click="handleEdit()">
            <i class="fas fa-edit" /> {{ $t('USER_MANAGEMENT_EDIT_BUTTON_SAVE') }}</b-button>
        </div>
      </b-row>

    </b-col>
  </div>
</template>

<script>

// import { editUserManagement, getUserManagement } from '@/api/modules/userManagement';

import constAuthority from '@/const/authority';
import constUserManager from '@/const/userManagement';

import {
  validEmptyOrWhiteSpace,
  validEmail,
  validPassword,
  validateNumberMoreThanZero,
} from '@/utils/validate';

import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'UserManagementEdit',
  data() {
    return {
      constAuthority: constAuthority,

      validEmptyOrWhiteSpace: validEmptyOrWhiteSpace,
      validEmail: validEmail,
      validPassword: validPassword,
      validateNumberMoreThanZero: validateNumberMoreThanZero,

      User: {
        name: '',
        email: '',
        password: '',
        authority: null,
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },
      USER: {},
    };
  },
  created(){
    this.getUser();
  },
  methods: {
    /**
     * Function handle back to User Management List
     */
    handleBackList() {
      this.$router.push({ path: '/usermanagement/list' });
    },

    /**
     * Function handle call api create
     */
    async getUser(){
      this.overlay.show = true;

      // const URL = '/user/' + this.$router.currentRoute.params.id;
      // await getUserManagement(URL)
      //   .then((res) => {
      //     this.User.name = res.data.name;
      //     this.User.email = res.data.email;
      //     this.User.authority = res.data.role[0].id;
      //   });
      this.overlay.show = false;
    },
    async handleEdit() {
      this.overlay.show = true;
      if (this.handleValidate(this.User)) {
        // const URL = '/user/' + this.$router.currentRoute.params.id;
        if (this.validPassword){
          this.USER = {
            [constUserManager.USERNAME]: this.handleCreateUsername(this.User.email),
            [constUserManager.EMAIL]: this.User.email,
            [constUserManager.NAME]: this.User.name,
            [constUserManager.PASSWORD]: this.User.password,
            [constUserManager.STATUS]: 1,
            [constUserManager.ROLE]: this.User.authority,
          };
        } else {
          this.USER = {
            [constUserManager.USERNAME]: this.handleCreateUsername(this.User.email),
            [constUserManager.EMAIL]: this.User.email,
            [constUserManager.NAME]: this.User.name,
            [constUserManager.STATUS]: 1,
            [constUserManager.ROLE]: this.User.authority,
          };
        }
        // await editUserManagement(URL, this.USER)
        //   .then((res) => {
        //     MakeToast({
        //       variant: 'success',
        //       title: this.$t('SUCCESS'),
        //       content: this.$t('USER_MANAGEMENT_EDIT_SUCCESS', { [constUserManager.NAME]: res.data[constUserManager.NAME] }),
        //     });

        //     this.$router.push({ path: `/usermanagement/detail/${res.data[constUserManager.ID]}` });
        //   })
        //   .catch(() => {
        //     MakeToast({
        //       variant: 'danger',
        //       title: this.$t('DANGER'),
        //       content: this.$t('TOAST_HAVE_ERROR'),
        //     });
        //   });
      }

      this.overlay.show = false;
    },

    /**
     * Function handle validate User and show message
     */
    handleValidate(User) {
      if (
        !validEmptyOrWhiteSpace(User.name) &&
            validEmail(User.email) &&
            validateNumberMoreThanZero(User.authority)
      ) {
        return true;
      }

      MakeToast({
        variant: 'warning',
        title: this.$t('WARNING'),
        content: this.$t('USER_MANAGEMENT_CREATE_VALIDATE_FULL'),
      });

      return false;
    },

    handleCreateUsername(email) {
      return email.substring(0, email.indexOf('@'));
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/UserManagement/UserEdit.scss';
</style>
