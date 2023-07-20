<!-- Change PassWord Confirm -->
<!-- /change-password-confirm -->
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
        <h6 class="font-weight-bold text-center mt-4">
          {{ $t('CHANGE_PASSWORD_DESCRIPTION_2') }}
        </h6>

        <div class="login-input-container" style="margin-top: 2.5rem">
          <!-- 1 新しいパスワード new password-->
          <div class="login-input-wrraper">
            <div class="login-input">
              <img
                :src="require('@/assets/images/login/key-password.png')"
                alt="password"
                style="transform: translateY(4px)"
              >
              <!-- :formatter="format12characters" -->
              <b-form-input
                v-model="password.current_password"
                :placeholder="$t('CURRENTLY_PASSWORD')"
                :type="status_show_currently_pass ? 'text' : 'password'"
                :class="error.current_password === false ? 'is-invalid' : ''"
                @input="handleChangeForm($event, 'current_password')"
              />
              <div @click="handleShowPass('password')">
                <EyeHideIcon v-if="!status_show_currently_pass" />
                <EyeShowIcon v-if="status_show_currently_pass" />
              </div>
            </div>
            <b-form-invalid-feedback
              class="error-text"
              :state="error.current_password"
            >
              {{ error.current_password_content }}
            </b-form-invalid-feedback>
          </div>

          <!-- 2 新しいパスワード（確認）- new password (confirm) -->
          <div class="login-input-wrraper">
            <div class="login-input">
              <img
                :src="require('@/assets/images/login/key-password.png')"
                alt="password"
              >
              <!-- :formatter="format12characters" -->
              <b-form-input
                v-model="password.current_password_confirm"
                :placeholder="$t('CURRENTLY_PASSWORD_CONFIRM')"
                :type="status_show_currently_pass_confirm ? 'text' : 'password'"
                :class="
                  error.current_password_confirm === false ? 'is-invalid' : ''
                "
                @input="handleChangeForm($event, 'current_password-confirm')"
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
          <div class="login-submit btn-back btn" @click="backToLogin()">
            <span>{{ $t('RETURN') }}</span>
          </div>
          <div class="login-submit btn" @click="handleSetNewPassword()">
            <span>{{ $t('TO_SET') }}</span>
          </div>
        </div>

        <!--  -->
      </div>
    </div>
  </div>
</template>

<script>
// IMPORT API
// import { login } from '@/api/auth';
// import { MakeToast } from '@/utils/toastMessage';
// import { validPassAz19 } from '@/utils/validate';
// import { parseToken } from '@/utils/handleToken';
// import { handleRole } from '@/utils/handleRole';
import img_bg from '@/assets/images/login/login-bg.png';
import EyeHideIcon from '@/components/EyeHide';
import EyeShowIcon from '@/components/EyeShow';
import { MakeToast } from '@/utils/toastMessage';

import { ChangeNewPasswordPut } from '@/api/password';
import { validPassOnlyRequireAz09 } from '@/utils/validate';
import { newePass } from '@/pages/PassWords/ChangePassWord/dataChangePass.js';

export default {
  name: 'ChangePassWordConfirm',
  components: {
    EyeHideIcon,
    EyeShowIcon,
  },
  data() {
    return {
      status_show_currently_pass: false,
      status_show_currently_pass_confirm: false,
      isProcess: false,
      pass_match: true,
      password: newePass,

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

  methods: {
    handleShowPass(typeInput) {
      if (typeInput === 'password') {
        console.log('handleShowPass password');
        if (this.status_show_currently_pass === true) {
          this.status_show_currently_pass = false;
        } else {
          this.status_show_currently_pass = true;
        }
      }
      if (typeInput === 'password-confirm') {
        console.log('handleShowPass password');
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
      console.log('handleChangeForm', event, field);
      const newValue = event;
      switch (field) {
        case 'change-password':
          this.error.current_password = null;
          if (newValue) {
            this.error.current_password = true;
          } else {
            this.error.current_password = false;
          }
          break;

        case 'change-password-confirm':
          this.error.current_password_confirm = null;
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
      console.log('checkvalidate');
      // Kiểm tra rỗng
      if (current_password === '') {
        console.log('current_password === rong');
        this.error.current_password = false;
        this.error.current_password_content = this.$t(
          'PLEASE_ENTER_NEW_PASSWORD'
        );
        this.error.pass_match = true;
        this.error.pass_match_content = '';
      }
      if (current_password_confirm === '') {
        console.log('current_password_confirm === rong');
        this.error.current_password_confirm = false;
        this.error.current_password_confirm_content = this.$t(
          'PLEASE_ENTER_NEW_PASSWORD_CONFIRMATION'
        );
        this.error.pass_match = true;
        this.error.pass_match_content = '';
      }
      // Check length
      if (current_password.length > 12) {
        console.log('length current_password');

        this.error.current_password = false;
        this.error.current_password_content = this.$t(
          'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
        );
        this.error.pass_match = true;
        this.error.pass_match_content = '';
      }
      if (current_password_confirm.length > 12) {
        console.log('length current_password_confirm');
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
          console.log('length current_password match');
          this.error.current_password = false;
          this.error.current_password_content = this.$t(
            'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
          );
          this.error.pass_match = true;
          this.error.pass_match_content = '';
        }
        if (current_password_confirm.length > 12) {
          console.log('length current_password_confirm match');
          this.error.current_password_confirm = false;
          this.error.current_password_confirm_content = this.$t(
            'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
          );
          this.error.pass_match = true;
          this.error.pass_match_content = '';
        }
        //

        if (String(current_password) === String(current_password_confirm)) {
          console.log('Match pass');
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
            console.log('length CurrentPasswordConfirm regex');
            this.error.current_password = false;
            this.error.current_password_content = this.$t(
              'VALIDATE_PASS_INPUT'
            );
            this.error.pass_match = true;
            this.error.pass_match_content = '';
          }
          if (!regexCurrentPassword) {
            console.log('length current_password regex');
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

    // GOI API xác nhận mật khẩu mới
    async handleSetNewPassword(e) {
      if (this.checkvalidate() === true) {
        console.log('Qua regex gọi API');
        // DATA
        const DATA_NEW_PASS = {
          new_password: this.password.current_password,
          new_password_confirm: this.password.current_password_confirm,
          // new_password: '123456789CCk',
          // new_password_confirm: '123456789CCk',
        };

        try {
          const response = await ChangeNewPasswordPut(DATA_NEW_PASS);
          const resCode = response.code;
          const resDataCode = response.data.code;
          const resMessage = response.message;
          const resDataMessage = response.data.message;
          // console.log('response 200', response);
          if (resCode === 200 || resDataCode === 200) {
            console.log('response 200');
            this.$router.push(
              { path: `/change-password-done` },
              (onAbort) => {}
            );
          } else {
            MakeToast({
              variant: 'warning',
              title: this.$t('warning'),
              content: resMessage || resDataMessage,
            });
          }
        } catch (error) {
          console.log(error);
        }
        // MakeToast({
        //   variant: 'warning',
        //   title: this.$t('warning'),
        //   content: this.$t('OLD_PASSWORD_IS_NOT_CORRECT'),
        // });
      } else {
        console.log('Lỗi chưa qua validate');
      }
    },

    backToLogin() {
      console.log('backToLogin');
      this.$router.push('/login');
      this.password.current_password = '';
      this.password.current_password_confirm = '';
      this.error.current_password = '';
      this.error.current_password_confirm = '';
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/pages/Login/Login.scss';
</style>
