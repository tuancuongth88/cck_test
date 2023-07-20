<!-- BTN Forget password -> Password reset - send email -->
<!-- /reset-password-confirm -->
<!-- /api/auth/password-reset -->
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
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>
    <!--  -->
    <div class="login-page">
      <div
        class="login-bg"
        :style="{
          'background-image': 'url(@/assets/images/login/login-bg.png)',
        }"
      >
        <!-- パスワードの再設定 Password reset -->
        <div class="login-form">
          <div class="login-title" style="color: #1d266a">
            <span>{{ $t('RESET_PASSWORD_TITLE') }}</span>
          </div>
          <div class="login-descriptions">
            <h6>{{ $t('CHANGE_PASSWORD_DESCRIPTION_2') }}</h6>
          </div>

          <!-- <div class="w-100 d-flex justify-center align-center"><span>password: {{ password }}</span></div> <br> -->
          <!-- <div class="w-100 d-flex justify-center align-center"><span>error: {{ error }}</span></div> -->

          <div class="login-input-container" style="margin-top: 3.75rem">
            <!-- 1 Pass mới -->
            <div class="login-input-wrraper">
              <div class="login-input">
                <img
                  :src="require('@/assets/images/login/key-password.png')"
                  alt="password"
                  style="transform: translateY(4px)"
                >
                <b-form-input
                  v-model="password.current_pass"
                  :formatter="format12characters"
                  :placeholder="$t('NEW_PASSWORD')"
                  :type="status_show_currently_pass ? 'text' : 'password'"
                  :class="error.current_pass === false ? 'is-invalid' : ''"
                  @input="handleChangeForm($event, 'current_pass')"
                />
                <div @click="handleShowPass('password')">
                  <EyeHideIcon v-if="!status_show_currently_pass" />
                  <EyeShowIcon v-if="status_show_currently_pass" />
                </div>
                <b-form-invalid-feedback
                  class="error-text"
                  :state="error.current_password"
                >
                  {{ error.current_password_content }}
                </b-form-invalid-feedback>
              </div>

              <!-- 2 Pass mới xác nhận 現在のパスワード（確認） Currently Password（Confirm）-->
              <div class="login-input-wrraper">
                <div class="login-input">
                  <img
                    :src="require('@/assets/images/login/key-password.png')"
                    alt="password"
                  >
                  <!-- :formatter="format12characters" -->
                  <b-form-input
                    v-model="password.current_password_confirm"
                    :placeholder="$t('NEW_PASSWORD_CONFIRM')"
                    :type="
                      status_show_currently_pass_confirm ? 'text' : 'password'
                    "
                    :class="
                      error.current_password_confirm === false
                        ? 'is-invalid'
                        : ''
                    "
                    @input="
                      handleChangeForm($event, 'current_password-confirm')
                    "
                  />
                  <div @click="handleShowPass('password-confirm')">
                    <EyeHideIcon v-if="!status_show_currently_pass_confirm" />
                    <EyeShowIcon v-if="status_show_currently_pass_confirm" />
                  </div>
                </div>
                <b-form-invalid-feedback
                  class="error-text"
                  :state="error.current_password_confirm"
                >
                  {{ error.current_password_confirm_content }}
                </b-form-invalid-feedback>
              </div>
              <!--  -->
            </div>

            <div class="login-submit-btns">
              <div class="login-submit btn" @click="handleSetNewPassword()">
                <span>{{ $t('TO_SET') }}</span>
              </div>
              <div class="text-noti">
                <span>{{ error.pass_match_content }}</span>
              </div>
            </div>

            <!--  -->
          </div>
        </div>
      </div>
    </div>
  </b-overlay>
</template>

<script>
// IMPORT API
// import { login } from '@/api/auth';
import { MakeToast } from '@/utils/toastMessage';
import { validPassOnlyRequireAz09 } from '@/utils/validate';
// import { parseToken } from '@/utils/handleToken';
// import { handleRole } from '@/utils/handleRole';
import img_bg from '@/assets/images/login/login-bg.png';
import EyeHideIcon from '@/components/EyeHide';
import EyeShowIcon from '@/components/EyeShow';
import { resetPassComfirm } from '@/pages/PassWords/ResetPassWord/dataResetPass.js';
import { ResetPassConfirmPut } from '@/api/password';

export default {
  name: 'ResetPassWordConfirm',
  components: {
    EyeHideIcon,
    EyeShowIcon,
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
      status_show_currently_pass: false,
      status_show_currently_pass_confirm: false,
      isProcess: false,
      pass_match: true,

      password: resetPassComfirm,

      error: {
        current_password: true,
        current_password_content: '',
        current_password_confirm: true,
        current_password_confirm_content: '',
        pass_match: true,
        pass_match_content: '',
      },
      img_bg: img_bg,
    };
  },

  created() {
    this.getTokenFormURLbyEmail();
    this.getEmailFormLocal();
  },

  methods: {
    handleShowPass(typeInput) {
      if (typeInput === 'password') {
        if (this.status_show_currently_pass === true) {
          this.status_show_currently_pass = false;
        } else {
          this.status_show_currently_pass = true;
        }
      }
      if (typeInput === 'password-confirm') {
        if (this.status_show_currently_pass_confirm === true) {
          this.status_show_currently_pass_confirm = false;
        } else {
          this.status_show_currently_pass_confirm = true;
        }
      }
    },

    format12characters(e) {
      return String(e).substring(0, 12);
    },

    handleChangeForm(event, field) {
      const newValue = event;
      switch (field) {
        case 'current_password':
          // this.checkvalidate();
          this.error.current_password = true;
          if (newValue) {
            this.error.current_password = true;
          } else {
            this.error.current_password = false;
          }
          break;

        case 'current_password-confirm':
          // this.checkvalidate();
          this.error.current_password_confirm = true;
          if (newValue) {
            this.error.current_password_confirm = true;
          } else {
            this.error.current_password_confirm = false;
          }
          break;
        default:
          break;
      }
    },

    checkvalidate() {
      const current_password = this.password.current_password;
      const current_password_confirm = this.password.current_password_confirm;
      // Kiểm tra rỗng
      if (current_password === '') {
        this.error.current_password = false;
        this.error.current_password_content = this.$t(
          'PLEASE_ENTER_NEW_PASSWORD'
        );
        this.error.pass_match = true;
        this.error.pass_match_content = '';
      }
      if (current_password_confirm === '') {
        this.error.current_password_confirm = false;
        this.error.current_password_confirm_content = this.$t(
          'PLEASE_ENTER_NEW_PASSWORD_CONFIRMATION'
        );
        this.error.pass_match = true;
        this.error.pass_match_content = '';
      }
      // Check length
      if (current_password.length > 12) {
        this.error.current_password = false;
        this.error.current_password_content = this.$t(
          'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
        );
        this.error.pass_match = true;
        this.error.pass_match_content = '';
      }
      if (current_password_confirm.length > 12) {
        this.error.current_password_confirm = false;
        this.error.current_password_confirm_content = this.$t(
          'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
        );
        this.error.pass_match = true;
        this.error.pass_match_content = '';
      }

      if (current_password !== '' && current_password_confirm !== '') {
        this.error.current_password_confirm = true;
        this.error.current_password = true;
        // Sau Đã nhập -> Kiểm tra trùng
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        // Cả khi trùng pass kiểm tra độ dài
        if (current_password.length > 12) {
          this.error.current_password = false;
          this.error.current_password_content = this.$t(
            'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
          );
          this.error.pass_match = true;
          this.error.pass_match_content = '';
        }
        if (current_password_confirm.length > 12) {
          this.error.current_password_confirm = false;
          this.error.current_password_confirm_content = this.$t(
            'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
          );
          this.error.pass_match = true;
          this.error.pass_match_content = '';
        }
        //

        if (String(current_password) === String(current_password_confirm)) {
          this.error.pass_match = true;
          this.error.pass_match_content = '';
          // Check Regex
          const regexCurrentPassword = validPassOnlyRequireAz09(
            this.password.current_password
          );
          const regexCurrentPasswordConfirm = validPassOnlyRequireAz09(
            this.password.current_password_confirm
          );
          if (!regexCurrentPasswordConfirm) {
            this.error.current_password = false;
            this.error.current_password_content = this.$t(
              'VALIDATE_PASS_INPUT'
            );
            this.error.pass_match = true;
            this.error.pass_match_content = '';
          }
          if (!regexCurrentPassword) {
            this.error.current_password_confirm = false;
            this.error.current_password_confirm_content = this.$t(
              'VALIDATE_PASS_INPUT'
            );
            this.error.pass_match = true;
            this.error.pass_match_content = '';
          }
          if (regexCurrentPassword && regexCurrentPasswordConfirm) {
            return true;
          }
        } else {
          this.error.pass_match = false;
          this.error.pass_match_content = this.$t('PASS_NOT_MATCH');
          return false;
        }
      }
      //
    },

    getTokenFormURLbyEmail() {
      const TOKEN = this.$route?.query.token;
      this.password.token = TOKEN;
    },
    getEmailFormLocal() {
      const email_reset_pass = localStorage.getItem('email_reset_pass');
      this.password.mail_address = email_reset_pass;
    },

    // GOI API SET mật khẩu mới
    async handleSetNewPassword(e) {
      if (this.checkvalidate() === true) {
        // DATA
        const DATA_CONFIRM_PASS = {
          token: this.password.token,
          mail_address: this.password.mail_address,
          new_password: this.password.current_password,
          new_password_confirm: this.password.current_password_confirm,
        };

        try {
          this.overlay.show = true;
          const response = await ResetPassConfirmPut(DATA_CONFIRM_PASS);
          const resCode = response.code;
          const resDataCode = response.data.code;
          const resMessage = response.message;
          const resDataMessage = response.data.message;
          const resDataData = response.data.data;
          if (resCode === 200 || resDataCode === 200) {
            this.overlay.show = false;
            this.$router.push(
              { path: `/reset-password-done` },
              (onAbort) => {}
            );
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
      } else {
        console.log('Lỗi chưa qua validate');
      }
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/pages/Login/Login.scss';
</style>
