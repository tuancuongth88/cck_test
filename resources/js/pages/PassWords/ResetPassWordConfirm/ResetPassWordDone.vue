<!-- BTN reset password confirm - Reset mật khẩu xong -->
<!-- /reset-password-done -->
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
        <!-- 新しいパスワードを設定しました。 -->
        <div class="login-descriptions login-noti ">
          <span>{{ $t('NEW_PASSWORD_RESET_COMPLETED') }}</span>
        </div>

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
  name: 'ResetPassWordDone',
  components: {
  },
  data() {
    return {
    };
  },

  methods: {
    backToLogin() {
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
