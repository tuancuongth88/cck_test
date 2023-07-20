<!-- BTN Forget password -> Password reset - send email -->
<!-- /reset-password -->
<!-- /reset-password-send-email -->
<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t("PLEASE_WAIT") }}</p>
      </div>
    </template>
    <!--  -->
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
          <div class="login-title" style="color: #1D266A">
            <span>{{ $t('RESET_PASSWORD_TITLE') }}</span>
          </div>

          <div class="login-descriptions">
            <span>{{ $t('RESET_PASS_DESCRIPTION_1') }}</span>
            <span>{{ $t('RESET_PASS_DESCRIPTION_2') }}</span>
            <span>{{ $t('RESET_PASS_DESCRIPTION_3') }}</span>
          </div>

          <!-- <div>formData: {{ formData }}</div> -->
          <!-- <div>resetPassComfirm: {{ resetPassComfirm }}</div> -->
          <!-- <div>currentPasswordConfirm: {{ password.currentPasswordConfirm }}</div> -->

          <div class="login-input-container" style="margin-top: 2.5rem">
            <!-- 1 メールアドレス Mail address -->
            <div class="login-input-wrraper">
              <div class="login-input">
                <img
                  :src="require('@/assets/images/login/mail.png')"
                  alt="password"
                  style="transform: translateY(6px);"
                >

                <b-form-input
                  v-model="formData.mail_address"
                  :placeholder="$t('MAIL_ADDRESS')"
                  type="text"
                  :class="error.mail_address === false ? 'is-invalid' : ''"
                  @input="handleChangeForm($event, 'mail_address'), saveEmail()"
                  @keyup.enter="goToSendEmail"
                  @keyup.alt.enter="goToSendEmail"
                />
              </div>

              <b-form-invalid-feedback class="error-text" :state="error.mail_address">
                {{ error.email_address_text }}
              </b-form-invalid-feedback>
            </div>
          <!--  -->
          </div>
          <div class="login-submit-btns">
            <div class="login-submit btn-send-mail btn" @click="goToSendEmail()">
              <span>{{ $t('SEND_MAIL') }}</span>
            </div>
            <div class="login-submit btn" @click="backToLogin()">
              <span>{{ $t('GO_TO_LOGIN_SCREEN') }}</span>
            </div>
          </div>

        <!--  -->
        </div>
      </div>
    </div>
    <!--  -->

  </b-overlay>
</template>

<script>
// IMPORT API
import { MakeToast } from '@/utils/toastMessage';
// import { validPassAz19 } from '@/utils/validate';
import { validEmail } from '@/utils/validate';
import { email, resetPassComfirm } from '@/pages/PassWords/ResetPassWord/dataResetPass.js';
import { ResetPassSendEmailPost } from '@/api/password';

export default {
  name: 'ResetPassWordSendEmail',
  components: {
  },
  data() {
    return {
      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        backgroundColor: 'transparent !important',
        bgColor: 'transparent !important',
      },
      statusShowCurrentlyPass: false,
      statusShowCurrentlyPassConfirm: false,
      isProcess: false,

      formData: email,
      resetPassComfirm: resetPassComfirm,

      error: {
        mail_address: true,
        email_address_text: '',
      },

      //
    };
  },

  methods: {
    format12characters(e) {
      return String(e).substring(0, 12);
    },

    saveEmail() {
      this.resetPassComfirm.mail_address = this.formData.mail_address;
      localStorage.setItem('email_reset_pass', `${this.formData.mail_address}`);
    },

    handleChangeForm(event, field) {
      console.log('handleChangeForm');
      const newValue = event;
      switch (field) {
        case 'mail_address':
          this.checkvalidate();
          this.error.mail_address = true;
          if (newValue) {
            this.error.mail_address = true;
            this.resetPassComfirm.mail_address = this;
          } else {
            this.error.mail_address = false;
          }
          break;
        default:
          break;
      }
    },

    checkvalidate() {
      if (this.formData.mail_address === '') {
        console.log('mail_address empty');
        this.error.mail_address = false;
        this.error.email_address_text = this.$t('PLEASE_ENTER_YOUR_EMAIL_ADDRESS');
      } else {
        // Nếu có giá trị thì -> Regex
        if (validEmail(this.formData.mail_address)) {
          console.log('mail_address regex');
          this.error.mail_address = true;
          this.error.email_address_text = '';
          return true;
        } else {
          console.log('mail_address fail regex');
          this.error.mail_address = false;
          this.error.email_address_text = this.$t('PLEASE_ENTER_THE_CORRECT_EMAIL_ADDRESS_FORMAT');
          return false;
        }
      }

      //
    },
    // Call API Send email
    async goToSendEmail() {
      this.checkvalidate();
      const DATA_EMAL = {
        mail_address: this.formData.mail_address,
      };
      if (this.checkvalidate()) {
        this.saveEmail();

        try {
          this.overlay.show = true;
          const response = await ResetPassSendEmailPost(DATA_EMAL);
          const resCode = response.code;
          const resDataCode = response.data.code;
          const resMessage = response.message;
          const resDataMessage = response.data.message;
          const resDataData = response.data.data;
          if (resCode === 200 || resDataCode === 200) {
            // console.log('response 200');
            this.overlay.show = false;
            this.$router.push({ path: `/reset-password-sent-email` }, (onAbort) => {});
          } else {
            this.overlay.show = false;
            MakeToast({
              variant: 'warning',
              title: this.$t('warning'),
              content: resMessage || resDataMessage || resDataData,
            });
          }
        } catch (error) {
          console.log(error);
        }
      }

    //
    },

    backToLogin() {
      console.log('backToLogin');
      this.formData.mail_address = '';
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
