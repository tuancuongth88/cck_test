<!-- BTN Forget password -> Password reset - sent email - đã gửi gmail -->
<!-- /password-reset-sent-email -->
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
      <div class="login-form login-noti justify-space-between">
        <div class="login-title" style="color: #1D266A">
          <span>{{ $t('RESET_PASSWORD_TITLE') }}</span>
        </div>

        <div class="login-descriptions login-noti ">
          <span>{{ $t('PASSWORD_RESET_URL_HAS_BEEN_SENT_TO_YOUR_EMAIL_ADDRESS') }}</span>
        </div>

        <!-- <div>formData: {{ formData }}</div> -->
        <!-- <div>error: {{ error }}</div> -->
        <!-- <div>currentPasswordConfirm: {{ password.currentPasswordConfirm }}</div> -->

        <div class="login-submit-btns">
          <!-- ログイン画面へ Next to m.h login -->
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

export default {
  name: 'ResetPassWordSentEmail',
  components: {
  },
  data() {
    return {
      statusShowCurrentlyPass: false,
      statusShowCurrentlyPassConfirm: false,
      isProcess: false,
    };
  },

  methods: {
    backToLogin() {
      console.log('backToLogin');
      this.email_address = '';
      this.$router.push('/login');
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/pages/Login/Login.scss';
@import '@/scss/modules/common/common.scss';
</style>
