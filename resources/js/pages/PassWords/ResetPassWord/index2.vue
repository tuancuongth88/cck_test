<template>
  <div class="login-page">
    <div
      class="login-bg"
      :style="{ 'background-image': 'url(@/assets/images/login/login-bg.png)' }"
    >
      <img
        class="login-bg__img"
        :src="require('@/assets/images/login/login-bg.png')"
        alt="avata"
      >
      <div class="login-form">
        <div class="login-title">
          <span>{{ $t('LOGIN_TITLE') }}</span>
        </div>

        <div class="login-input-wrraper">
          <div class="login-input">
            <img
              :src="require('@/assets/images/login/avata-default.png')"
              alt="avata"
            >
            <b-form-input
              v-model="account.mail_address"
              :placeholder="$t('LOGIN_ID')"
              @keyup.enter="handleLogin()"
            />
          </div>

          <div class="login-input">
            <img
              :src="require('@/assets/images/login/key-password.png')"
              alt="password"
            >
            <b-form-input
              v-model="account.password"
              :placeholder="$t('PASSWORD')"
              type="password"
              @keyup.enter="handleLogin()"
            />
          </div>
        </div>

        <b-button
          class="login-submit btn"
          @click="handleLogin()"
        ><span>{{ $t('BUTTON_LOGIN') }}</span></b-button>

        <div class="login-other-btn-link">
          <!-- If you forget your password -->
          <span class="btn" @click="goToForgetPass()">{{
            $t('IF_YOU_FORGET_YOUR_PASSWORD')
          }}</span>
          <!-- Click here for new registration for human resource organizations -->
          <span class="btn" @click="goToRegister('HUMAN_RESOURCE')">{{
            $t('REGISTRATION_FOR_HUMAN_RESOURCE_ORGANIZATIONS')
          }}</span>
          <span class="btn" @click="goToRegister('CORPORATE')">{{
            $t('CORPORATE_REGISTRATION')
          }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// IMPORT API
import { login } from '@/api/auth';
// import { MakeToast } from '@/utils/toastMessage';
// IMPORT VALIDATE
import { validEmail, validPassword } from '@/utils/validate';
// import { parseToken } from '@/utils/handleToken';
import { handleRole } from '@/utils/handleRole';
import img_bg from '@/assets/images/login/login-bg.png';

export default {
  name: 'Login',
  data() {
    return {
      // ACCOUNT LOGIN
      account: {
        mail_address: '1okuridashi_hanoi@gmail.vn',
        password: '123456789CCk',
      },
      // STATUS PROCESS LOGIN
      isProcess: false,
      // STATUS VALIDATE ACCOUNT USER LOGIN
      message: {
        isShowMessage: false,
        isMessage: '',
      },
      img_bg: img_bg,
    };
  },

  methods: {
    async handleLogin() {
      // CHECK VALIDATE
      if (
        validEmail(this.account.mail_address) &&
        validPassword(this.account.password)
      ) {
        this.$store.dispatch('app/setLoading', true);
        try {
          const params = {
            mail_address: this.account.mail_address,
            password: this.account.password,
          };
          const res = await login(params);
          console.log('res login ==>', res.data);
          if (res.data.data.access_token) {
            const token = res.data.data.access_token;
            const currentUser = res.data.data.profile;
            const { ROLES, PERMISSIONS } = handleRole([]);
            const userInfo = {
              token,
              profile: currentUser,
              roles: ROLES,
              permissions: PERMISSIONS,
            };
            this.$store.dispatch('user/saveLogin', userInfo);
            this.redirectByAuth(currentUser);
          }
          this.$store.dispatch('app/setLoading', false);
        } catch (e) {
          this.$store.dispatch('app/setLoading', false);
          const message = e.response.data.message || '';
          this.$toast.error(message);
        }
      } else {
        this.message.isShowMessage = true;
        this.message.isMessage = this.$t('ERROR_VALIDATE_EMAIL_PASSWORD');
      }
    },

    redirectByAuth(user) {
      console.log('push router home');
      this.$router.push('/home');
    },
    // Quên mật khẩu
    goToForgetPass() {
      console.log('goToForgetPass');
      this.$router.push({ path: '/password-reset' });
    },

    goToRegister(type_register) {
      if (type_register === 'HUMAN_RESOURCE') {
        this.$router.push('/human-resourse-register');
      } else if (type_register === 'CORPORATE') {
        this.$router.push('/company-register');
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Login/Login.scss';
</style>
