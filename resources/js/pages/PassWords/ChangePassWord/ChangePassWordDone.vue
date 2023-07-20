<!-- Change PassWord Done -->
<!-- /change-password-done -->
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
        <h1 class="text-center text-blue">
          {{ $t('CHANGE_PASSWORD_TITLE') }}
        </h1>

        <h6 class="font-weight-bold text-center mt-5">
          {{ $t('CHANGE_PASSWORD_DESCRIPTION_3') }}
        </h6>

        <div class="login-submit-btns" style="margin-top: 8rem">
          <div class="login-submit btn" @click="backToLogin()">
            <span>{{ $t('GO_TO_LOGIN_SCREEN') }}</span>
          </div>
        </div>

        <!--  -->
      </div>
    </div>
  </div>
</template>

<script>
import {
  oldPass,
  newePass,
} from '@/pages/PassWords/ChangePassWord/dataChangePass.js';

export default {
  name: 'ChangePassWordDone',
  components: {},
  data() {
    return {
      oldPass: oldPass,
      newePass: newePass,
    };
  },

  methods: {
    backToLogin() {
      // console.log('backToLogin');
      this.$router.push('/login');
      this.oldPass.currentPassword = '';
      this.oldPass.currentPasswordConfirm = '';
      this.newePass.currentPassword = '';
      this.newePass.currentPasswordConfirm = '';
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/pages/Login/Login.scss';
</style>
