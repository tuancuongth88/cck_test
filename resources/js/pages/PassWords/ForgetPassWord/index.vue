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
        <!-- <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p> -->
      </div>
    </template>
    <!--  -->
    <div id="reset-pass-page" class="login-page">
      <div
        class="login-bg"
        :style="{
          'background-image': 'url(@/assets/images/login/login-bg.png)',
        }"
      >
        <img
          class="login-bg__img"
          :src="require('@/assets/images/login/login-bg.png')"
          alt="avata"
        >
        <div class="login-form d-flex flex-column justify-content-between">
          <div class="login-title" style="color: #1d266a">
            <span>{{ $t('RESET_PASSWORD_TITLE') }} </span>
          </div>

          <template v-if="step === 1">
            <div class="login-descriptions">
              <span>{{ $t('RESET_PASS_DESCRIPTION_1') }}</span>
              <span>{{ $t('RESET_PASS_DESCRIPTION_2') }}</span>
              <span>{{ $t('RESET_PASS_DESCRIPTION_3') }}</span>
            </div>

            <div class="login-input-container mt-5">
              <!-- 1 メールアドレス Mail address -->
              <div class="login-input-wrraper">
                <div class="login-input">
                  <img
                    :src="require('@/assets/images/login/mail.png')"
                    alt="password"
                    style="transform: translateY(6px)"
                  >

                  <b-form-input
                    v-model="formData.mail_address"
                    dusk="mail_address"
                    :placeholder="$t('MAIL_ADDRESS')"
                    type="text"
                    :class="error.mail_address === false ? 'is-invalid' : ''"
                    @input="
                      handleChangeForm($event, 'mail_address'), saveEmail()
                    "
                    @keyup.enter="goToSendEmail"
                    @keyup.alt.enter="goToSendEmail"
                  />
                </div>

                <b-form-invalid-feedback
                  class="error-text"
                  :state="error.mail_address"
                >
                  {{ error.email_address_text }}
                </b-form-invalid-feedback>
              </div>
            </div>
            <div class="login-submit-btns">
              <div
                id="btn-send-email"
                dusk="btn-send-email"
                class="login-submit btn-send-mail btn"
                @click="goToSendEmail()"
              >
                <span>{{ $t('SEND_MAIL') }}</span>
              </div>
              <div class="login-submit btn" @click="backToLogin()">
                <span>{{ $t('GO_TO_LOGIN_SCREEN') }}</span>
              </div>
            </div>
          </template>
          <template v-if="step === 2">
            <div class="login-descriptions login-noti">
              <span>
                {{
                  $t('PASSWORD_RESET_URL_HAS_BEEN_SENT_TO_YOUR_EMAIL_ADDRESS')
                }}
              </span>
            </div>

            <div class="login-submit-btns">
              <div class="login-submit btn" @click="backToLogin()">
                <span>{{ $t('GO_TO_LOGIN_SCREEN') }}</span>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div></b-overlay>
</template>

<script>
// IMPORT API
import { MakeToast } from '@/utils/toastMessage';
// import { validPassAz19 } from '@/utils/validate';
import { validEmail } from '@/utils/validate';
import { ResetPassSendEmailPost } from '@/api/password';

export default {
  name: 'ForgetPassWord',
  components: {},
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

      formData: {
        mail_address: '',
      },
      resetPassComfirm: {
        token: '',
        mail_address: '',
        current_password: '',
        current_password_confirm: '',
      },

      error: {
        mail_address: true,
        email_address_text: '',
      },

      step: 1,

      //
    };
  },
  created() {
    // Object.assign(this.formData, {
    //   email: '',
    // });
  },

  methods: {
    format12characters(e) {
      const inputValue = String(e).substring(0, 12); // Giới hạn 12 ký tự

      // Loại bỏ các ký tự tiếng Nhật từ giá trị nhập vào
      const filteredValue = inputValue.replace(/[ぁ-んァ-ン一-龯。]/g, '');

      return filteredValue;
    },

    saveEmail() {
      this.resetPassComfirm.mail_address = this.formData.mail_address;
      // localStorage.setItem('email_reset_pass', `${this.formData.mail_address}`);
    },

    handleChangeForm(event, field) {
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
        this.error.mail_address = false;
        // this.error.email_address_text = this.$t(
        //   'PLEASE_ENTER_YOUR_EMAIL_ADDRESS'
        // );
        this.error.email_address_text = this.$t('VALIDATE.REQUIRED_TEXT');
      } else {
        // Nếu có giá trị thì -> Regex
        if (validEmail(this.formData.mail_address)) {
          this.error.mail_address = true;
          this.error.email_address_text = '';
          return true;
        } else {
          this.error.mail_address = false;
          this.error.email_address_text = this.$t(
            'PLEASE_ENTER_THE_CORRECT_EMAIL_ADDRESS_FORMAT'
          );
          return false;
        }
      }
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
          const { code, message } = response.data;
          // const resCode = response.code;
          // const resDataCode = response.data.code;
          // const resMessage = response.message;
          // const resDataMessage = response.data.message;
          // const resDataData = response.data.data;
          if (code === 200) {
            this.overlay.show = false;
            this.step = 2;
            // this.$router.push(
            //   { path: `/reset-password-sent-email` },
            //   (onAbort) => {}
            // );
          } else {
            this.overlay.show = false;
            MakeToast({
              variant: 'warning',
              title: this.$t('warning'),
              content: message,
            });
          }
        } catch (error) {
          console.log(error);
        }
      }
    },

    backToLogin() {
      this.$router.push('/login');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/pages/Login/Login.scss';
@import '@/scss/modules/common/common.scss';
</style>
