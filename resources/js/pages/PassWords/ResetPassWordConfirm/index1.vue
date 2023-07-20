<!-- Reset Password/forget pass - comfirm -->
<!-- /reset-password-confirm -->
<template>
  <div class="pass-page">
    <div class="pass-bg">
      <div class="pass-wrapper">
        <div class="pass-form" style="justify-content: center">
          <div class="pass-title">
            <span>{{ $t('RESET_PASS_WORD_TITLE') }}</span>
          </div>

          <!-- Mô tả -->
          <div class="pass-description">
            <span v-if="new_password_confirmed">{{
              $t('NEW_PASSWORD_RESET_COMPLETED')
            }}</span>
          </div>

          <!-- Confirm new pass -:type="nunber" -->
          <div v-if="!new_password_confirmed" class="pass-input-wrraper">
            <div class="pass-input">
              <img
                :src="require('@/assets/images/login/key-password.png')"
                alt="password"
                style="transform: translateY(4px)"
              >
              <b-form-input
                v-model="password.new_password"
                :placeholder="$t('NEW_PASSWORD')"
                type="password"
              />
            </div>

            <div class="pass-input">
              <img
                :src="require('@/assets/images/login/key-password.png')"
                alt="password confirm"
                style="transform: translateY(4px)"
              >
              <b-form-input
                v-model="password.new_pass_confirm"
                :placeholder="$t('NEW_PASSWORD_CONFIRM')"
                :formatter="maxLengthEmail"
                type="password"
              />
            </div>
          </div>

          <div class="pass-btn">
            <b-button
              v-if="!new_password_confirmed"
              :disabled="!allow_do_send"
              class="pass-submit btn"
              @click="confirmNewPassword()"
            >
              <span>{{ $t('TO_SET') }}</span>
            </b-button>
            <b-button
              v-if="new_password_confirmed"
              class="pass-submit btn"
              @click="backToLogin()"
            >
              <span>{{ $t('GO_TO_LOGIN_SCREEN') }}</span>
            </b-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// IMPORT API
// import { postLogin } from '@/api/modules/login';
// import { MakeToast } from '@/utils/toastMessage';

// IMPORT VALIDATE
import { validEmail, validPassword } from '@/utils/validate';
// import { parseToken } from '@/utils/handleToken';

// import { handleRole } from '@/utils/handleRole';

// const urlAPI = {
//   urlLogin: `/auth/login`,
// };
export default {
  name: 'ResetPasswordConfirm',
  data() {
    return {
      allow_do_send: true,
      new_password_confirmed: false,

      password: {
        new_password: '',
        new_pass_confirm: '',
      },

      // STATUS PROCESS RESET PASSWORD
      isProcess: false,

      // STATUS VALIDATE ACCOUNT USER LOGIN
      message: {
        isShowMessage: false,
        isMessage: '',
      },
    };
  },

  methods: {
    async handleLogin() {
      this.isProcess = true;

      // CHECK VALIDATE
      if (
        validEmail(this.account.email) &&
        validPassword(this.account.password)
      ) {
        this.message.isShowMessage = false;
        this.message.isMessage = '';

        // const URL = urlAPI.urlLogin;

        // const DATA = {
        //   user_name: this.account.email,
        //   password: this.account.password,
        // };

        // await postLogin(URL, DATA)
        //   .then((response) => {
        //     if (response.code === 200) {
        //       const TOKEN = response.data.access_token;
        //       const PROFILE = response.data.profile;
        //       const EXP_TOKEN = parseToken(TOKEN);

        //       const USER = {
        //         address: PROFILE.address || '',
        //         avatar: PROFILE.avatar || '',
        //         email: PROFILE.email || '',
        //         fax: PROFILE.fax || '',
        //         gender: PROFILE.gender || '',
        //         id: PROFILE.id || '',
        //         name: PROFILE.name || '',
        //         phone: PROFILE.phone || '',
        //         status: PROFILE.status || '',
        //         expToken: EXP_TOKEN.exp || '',
        //       };

        //       const { ROLES, PERMISSIONS } = handleRole(PROFILE.role);

        //       console.log(ROLES);
        //       console.log(PERMISSIONS);

        //       // STORE DATA USER LOGIN
        //       this.$store.dispatch('user/saveLogin', { USER, ROLES, PERMISSIONS, TOKEN })
        //         .then(() => {
        //           MakeToast({
        //             variant: 'success',
        //             title: this.$t('SUCCESS'),
        //             content: this.$t('LOGIN_SUCCESS'),
        //           });

        //           this.$router.push('/');
        //         })
        //         .catch(() => {
        //           MakeToast({
        //             variant: 'danger',
        //             title: this.$t('DANGER'),
        //             content: this.$t('TOAST_HAVE_ERROR'),
        //           });
        //         });
        //     } else if ([401, 422].includes(response.code)) {
        //       this.message.isShowMessage = true;
        //       this.message.isMessage = response.message;
        //     }
        //   })
        //   .catch((error) => {
        //     this.message.isShowMessage = true;
        //     this.message.isMessage = error.message;
        //   });
      } else {
        this.message.isShowMessage = true;
        this.message.isMessage = this.$t('ERROR_VALIDATE_EMAIL_PASSWORD');
      }

      this.isProcess = false;
    },

    maxLengthEmail(e) {
      return String(e).substring(0, 16);
    },
    // Quên mật khẩu
    confirmNewPassword() {
      console.log('confirmNewPassword');
      this.new_password_confirmed = true;
    },

    backToLogin() {
      console.log('backToLogin');
      this.$router.push('/login');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Login/ResetPassWord.scss';
</style>
