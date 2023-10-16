<template>
  <div id="change-pass-page" class="login-page">
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

        <div class="login-input-container mt-5">
          <template v-if="step === 1">
            <h6 class="font-weight-bold text-center mt-4">
              {{ $t('CHANGE_PASSWORD_DESCRIPTION') }}
            </h6>
            <!-- 現在のパスワード Currently Password -->
            <div class="login-input-wrraper">
              <div class="login-input">
                <img
                  :src="require('@/assets/images/login/key-password.png')"
                  alt="password"
                  style="transform: translateY(4px)"
                >
                <b-form-input
                  id="current-password"
                  v-model="password.current_password"
                  :formatter="format12characters"
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

            <!-- 現在のパスワード（確認） Currently Password（Confirm）-->
            <div class="login-input-wrraper">
              <div class="login-input">
                <img
                  :src="require('@/assets/images/login/key-password.png')"
                  alt="password"
                >
                <b-form-input
                  id="current-password-confirm"
                  v-model="password.current_password_confirm"
                  :placeholder="$t('CURRENTLY_PASSWORD_CONFIRM')"
                  :type="
                    status_show_currently_pass_confirm ? 'text' : 'password'
                  "
                  :class="
                    !error.current_password_confirm || !error.pass_match
                      ? 'is-invalid'
                      : ''
                  "
                  :formatter="format12characters"
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
              <b-form-invalid-feedback
                class="error-text"
                :state="error.pass_match"
              >
                {{ error.pass_match_content }}
              </b-form-invalid-feedback>
            </div>
          </template>

          <template v-if="step === 2">
            <h6 class="font-weight-bold text-center mt-4">
              {{ $t('CHANGE_PASSWORD_DESCRIPTION_2') }}
            </h6>
            <!-- 現在のパスワード New Password -->
            <div class="login-input-wrraper">
              <div class="login-input">
                <img
                  :src="require('@/assets/images/login/key-password.png')"
                  alt="password"
                  style="transform: translateY(4px)"
                >
                <b-form-input
                  id="current-password"
                  v-model="password.new_password"
                  :formatter="format12characters"
                  :placeholder="$t('NEW_PASSWORD')"
                  :type="status_show_new_pass ? 'text' : 'password'"
                  :class="error.new_password === false ? 'is-invalid' : ''"
                  @input="handleChangeForm($event, 'new_password')"
                />
                <div @click="handleShowPass('new_password')">
                  <EyeHideIcon v-if="!status_show_new_pass" />
                  <EyeShowIcon v-if="status_show_new_pass" />
                </div>
              </div>
              <b-form-invalid-feedback
                class="error-text"
                :state="error.new_password"
              >
                {{ error.new_password_content }}
              </b-form-invalid-feedback>
            </div>

            <!-- 現在のパスワード（確認） New Password（Confirm）-->
            <div class="login-input-wrraper">
              <div class="login-input">
                <img
                  :src="require('@/assets/images/login/key-password.png')"
                  alt="password"
                >
                <b-form-input
                  id="new-password-confirm"
                  v-model="password.new_password_confirm"
                  :placeholder="$t('NEW_PASSWORD_CONFIRM')"
                  :type="status_show_new_pass_confirm ? 'text' : 'password'"
                  :class="
                    !error.new_password_confirm || !error.pass_match_set
                      ? 'is-invalid'
                      : ''
                  "
                  :formatter="format12characters"
                  @input="handleChangeForm($event, 'new_password-confirm')"
                />
                <div @click="handleShowPass('new_password_confirm')">
                  <EyeHideIcon v-if="!status_show_new_pass_confirm" />
                  <EyeShowIcon v-if="status_show_new_pass_confirm" />
                </div>
              </div>
              <b-form-invalid-feedback
                class="error-text"
                :state="error.new_password_confirm"
              >
                {{ error.new_password_confirm_content }}
              </b-form-invalid-feedback>
              <b-form-invalid-feedback
                class="error-text"
                :state="error.pass_match_set"
              >
                {{ error.pass_match_set_content }}
              </b-form-invalid-feedback>
            </div>
          </template>

          <template v-if="step === 3">
            <h1 class="text-center mt-4">
              {{ $t('CHANGE_PASSWORD_DESCRIPTION_3') }}
            </h1>
          </template>
        </div>

        <div v-if="step === 1" class="login-submit-btns">
          <div class="login-submit btn-back btn" @click="backToHome()">
            <span>{{ $t('RETURN') }}</span>
          </div>
          <div
            id="btn-confirm-current-pass"
            class="login-submit btn"
            @click="handleConfirmCurrentPass()"
          >
            <span>{{ $t('NEXT_BTN') }}</span>
          </div>
        </div>

        <div v-if="step === 2" class="login-submit-btns">
          <div class="login-submit btn-back btn" @click="backToHome()">
            <span>{{ $t('RETURN') }}</span>
          </div>
          <div
            id="btn-confirm-current-pass"
            class="login-submit btn"
            @click="handleChangeNewPass()"
          >
            <span>{{ $t('NEXT_BTN') }}</span>
          </div>
        </div>
        <div v-if="step === 3" class="login-submit-btns">
          <div class="login-submit btn-back btn" @click="backToLogin()">
            <span>{{ $t('RETURN') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// IMPORT API
// import { validPassOnlyRequireAz09 } from '@/utils/validate';
import { MakeToast } from '@/utils/toastMessage';
// import { parseToken } from '@/utils/handleToken';
// import { handleRole } from '@/utils/handleRole';
import img_bg from '@/assets/images/login/login-bg.png';
import EyeHideIcon from '@/components/EyeHide';
import EyeShowIcon from '@/components/EyeShow';
import { changePassConfirmPost, ChangeNewPasswordPut } from '@/api/password';
import {
  // validPassword,
  // validPasswordLength,
  validPasswordFormat,
} from '@/utils/validate';

export default {
  name: 'ChangePassWord',
  components: {
    EyeHideIcon,
    EyeShowIcon,
  },
  data() {
    return {
      // step 1
      status_show_currently_pass: false,
      status_show_currently_pass_confirm: false,
      pass_match: true,
      // step 2
      status_show_new_pass: false,
      status_show_new_pass_confirm: false,
      pass_match_set: true,
      isProcess: false,
      //
      password: {
        // step 1
        current_password: '',
        current_password_confirm: '',
        // step 2
        new_password: '',
        new_password_confirm: '',
      },

      error: {
        // step 1
        current_password: true,
        current_password_content: '',
        current_password_confirm: true,
        current_password_confirm_content: '',
        pass_match: true,
        pass_match_content: '',
        // step 2
        new_password: true,
        new_password_content: '',
        new_password_confirm: true,
        new_password_confirm_content: '',
        pass_match_set: true,
        pass_match_set_content: '',
      },
      img_bg: img_bg,
      step: 1,
    };
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

      if (typeInput === 'new_password') {
        if (this.status_show_new_pass === true) {
          this.status_show_new_pass = false;
        } else {
          this.status_show_new_pass = true;
        }
      }
      if (typeInput === 'new_password_confirm') {
        if (this.status_show_new_pass_confirm === true) {
          this.status_show_new_pass_confirm = false;
        } else {
          this.status_show_new_pass_confirm = true;
        }
      }
    },

    format12characters(e) {
      const inputValue = String(e).substring(0, 12); // Giới hạn 12 ký tự

      // Loại bỏ các ký tự tiếng Nhật từ giá trị nhập vào
      const filteredValue = inputValue.replace(/[ぁ-んァ-ン一-龯。]/g, '');

      return filteredValue;
    },

    handleChangeForm(event, field) {
      const newValue = event;
      switch (field) {
        case 'current_password':
          this.error.current_password = true;
          if (newValue) {
            this.error.current_password = true;
          } else {
            this.error.current_password = false;
            this.error.current_password_content =
              '新しいパスワードを入力してください。';
          }
          break;

        case 'current_password-confirm':
          this.error.current_password_confirm = true;
          this.error.pass_match = true;
          if (newValue) {
            this.error.current_password_confirm = true;
            this.error.pass_match = true;
          } else {
            this.error.current_password_confirm = false;

            this.error.current_password_confirm_content =
              '新しいパスワード（確認）を入力してください。';

            this.error.pass_match = false;
            this.error.pass_match_content = '';
          }
          break;
        default:
          break;
      }
    },

    // checkvalidate() {
    //   const current_password = this.password.current_password;
    //   const current_password_confirm = this.password.current_password_confirm;
    //   // Kiểm tra rỗng
    //   if (current_password === '') {
    //     this.error.current_password = false;
    //     this.error.current_password_content = this.$t(
    //       'PLEASE_ENTER_NEW_PASSWORD'
    //     );
    //     this.error.pass_match = true;
    //     this.error.pass_match_content = '';
    //   }
    //   if (current_password_confirm === '') {
    //     this.error.current_password_confirm = false;
    //     this.error.current_password_confirm_content = this.$t(
    //       'PLEASE_ENTER_NEW_PASSWORD_CONFIRMATION'
    //     );
    //     this.error.pass_match = true;
    //     this.error.pass_match_content = '';
    //   }
    //   // Check length
    //   if (current_password.length > 12) {

    //     this.error.current_password = false;
    //     this.error.current_password_content = this.$t(
    //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
    //     );
    //     this.error.pass_match = true;
    //     this.error.pass_match_content = '';
    //   }
    //   if (current_password_confirm.length > 12) {
    //     this.error.current_password_confirm = false;
    //     this.error.current_password_confirm_content = this.$t(
    //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
    //     );
    //     this.error.pass_match = true;
    //     this.error.pass_match_content = '';
    //   }

    //   if (current_password !== '' && current_password_confirm !== '') {
    //     this.error.current_password_confirm = true;
    //     this.error.current_password = true;
    //     // Sau Đã nhập -> Kiểm tra trùng
    //     this.error.pass_match = true;
    //     this.error.pass_match_content = '';
    //     // Cả khi trùng pass kiểm tra độ dài
    //     if (current_password.length > 12) {
    //       this.error.current_password = false;
    //       this.error.current_password_content = this.$t(
    //         'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
    //       );
    //       this.error.pass_match = true;
    //       this.error.pass_match_content = '';
    //     }
    //     if (current_password_confirm.length > 12) {
    //       this.error.current_password_confirm = false;
    //       this.error.current_password_confirm_content = this.$t(
    //         'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
    //       );
    //       this.error.pass_match = true;
    //       this.error.pass_match_content = '';
    //     }
    //     //

    //     if (String(current_password) === String(current_password_confirm)) {
    //       this.error.pass_match = true;
    //       this.error.pass_match_content = '';
    //       // Check Regex
    //       const regexCurrentPassword = validPassOnlyRequireAz09(
    //         this.password.current_password
    //       );
    //       const regexCurrentPasswordConfirm = validPassOnlyRequireAz09(
    //         this.password.current_password_confirm
    //       );
    //       if (!regexCurrentPasswordConfirm) {
    //         this.error.current_password = false;
    //         this.error.current_password_content = this.$t(
    //           'VALIDATE_PASS_INPUT'
    //         );
    //         this.error.pass_match = true;
    //         this.error.pass_match_content = '';
    //       }
    //       if (!regexCurrentPassword) {
    //         this.error.current_password_confirm = false;
    //         this.error.current_password_confirm_content = this.$t(
    //           'VALIDATE_PASS_INPUT'
    //         );
    //         this.error.pass_match = true;
    //         this.error.pass_match_content = '';
    //       }
    //       if (regexCurrentPassword && regexCurrentPasswordConfirm) {
    //         return true;
    //       }
    //     } else {
    //       this.error.pass_match = false;
    //       this.error.pass_match_content = this.$t('PASSWORD_NOT_MATCH');
    //       return false;
    //     }
    //   }
    //   //
    // },

    checkValidate1() {
      const current_password = this.password.current_password;
      // Kiểm tra rỗng
      if (current_password === '') {
        this.error.current_password = false;

        this.error.current_password_content =
          '新しいパスワードを入力してください。';
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return false;
      }

      // Check length và format
      if (current_password.length !== 12) {
        this.error.current_password = false;
        this.error.current_password_content =
          'パスワードは大文字を含む半角英数字12桁で入力してください';
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return false;
      } else {
        if (validPasswordFormat(current_password)) {
          this.error.current_password = true;
          this.error.current_password_content = '';
          return true;
        } else {
          this.error.current_password = false;
          this.error.current_password_content =
            'パスワードは大文字を含む半角英数字12桁で入力してください';
          this.error.pass_match = true;
          this.error.pass_match_content = '';
          return false;
        }
      }

      // Check validate bắt buộc mật khẩu phải có ít nhất 1 số và 1 chữ cái viết hoa
      // if (validPasswordFormat(current_password)) {
      //   this.error.current_password = true;
      //   this.error.current_password_content = '';
      //   return true;
      // } else {
      //   this.error.current_password = false;
      //   this.error.current_password_content =
      //     '正しいパスワード形式を入力してください。';
      //   this.error.pass_match = true;
      //   this.error.pass_match_content = '';
      //   return false;
      // }

      // if (current_password !== '' && current_password_confirm !== '') {
      //   this.error.current_password_confirm = true;
      //   this.error.current_password = true;
      //   // Sau Đã nhập -> Kiểm tra trùng
      //   this.error.pass_match = true;
      //   this.error.pass_match_content = '';
      //   // Cả khi trùng pass kiểm tra độ dài
      //   if (current_password.length > 12) {
      //     this.error.current_password = false;
      //     this.error.current_password_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   if (current_password_confirm.length > 12) {
      //     this.error.current_password_confirm = false;
      //     this.error.current_password_confirm_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   //

      //   if (String(current_password) === String(current_password_confirm)) {
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //     // Check Regex
      //     const regexCurrentPassword = validPassOnlyRequireAz09(
      //       this.password.current_password
      //     );
      //     const regexCurrentPasswordConfirm = validPassOnlyRequireAz09(
      //       this.password.current_password_confirm
      //     );
      //     if (!regexCurrentPasswordConfirm) {
      //       this.error.current_password = false;
      //       this.error.current_password_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (!regexCurrentPassword) {
      //       this.error.current_password_confirm = false;
      //       this.error.current_password_confirm_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (regexCurrentPassword && regexCurrentPasswordConfirm) {
      //       return true;
      //     }
      //   } else {
      //     this.error.pass_match = false;
      //     this.error.pass_match_content = this.$t('PASSWORD_NOT_MATCH');
      //     return false;
      //   }
      // }
    },

    checkValidate2() {
      const current_password_confirm = this.password.current_password_confirm;
      // Kiểm tra rỗng
      if (current_password_confirm === '') {
        this.error.current_password_confirm = false;

        this.error.current_password_confirm_content =
          '新しいパスワード（確認）を入力してください。';
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return false;
      }

      // Check length và format
      if (current_password_confirm.length !== 12) {
        this.error.current_password_confirm = false;
        this.error.current_password_confirm_content =
          'パスワードは大文字を含む半角英数字12桁で入力してください';
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return false;
      } else {
        if (validPasswordFormat(current_password_confirm)) {
          this.error.current_password_confirm = true;
          this.error.current_password_confirm_content = '';
          return true;
        } else {
          this.error.current_password_confirm = false;
          this.error.current_password_confirm_content =
            'パスワードは大文字を含む半角英数字12桁で入力してください';
          this.error.pass_match = true;
          this.error.pass_match_content = '';
          return false;
        }
      }

      // if (validPasswordFormat(current_password_confirm)) {
      //   this.error.current_password_confirm = true;
      //   this.error.current_password_confirm_content = '';
      //   return true;
      // } else {
      //   this.error.current_password_confirm = false;
      //   this.error.current_password_confirm_content =
      //     '正しいパスワード形式を入力してください。';
      //   this.error.pass_match = true;
      //   this.error.pass_match_content = '';
      //   return false;
      // }

      // if (current_password !== '' && current_password_confirm !== '') {
      //   this.error.current_password_confirm = true;
      //   this.error.current_password = true;
      //   // Sau Đã nhập -> Kiểm tra trùng
      //   this.error.pass_match = true;
      //   this.error.pass_match_content = '';
      //   // Cả khi trùng pass kiểm tra độ dài
      //   if (current_password.length > 12) {
      //     this.error.current_password = false;
      //     this.error.current_password_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   if (current_password_confirm.length > 12) {
      //     this.error.current_password_confirm = false;
      //     this.error.current_password_confirm_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   //

      //   if (String(current_password) === String(current_password_confirm)) {
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //     // Check Regex
      //     const regexCurrentPassword = validPassOnlyRequireAz09(
      //       this.password.current_password
      //     );
      //     const regexCurrentPasswordConfirm = validPassOnlyRequireAz09(
      //       this.password.current_password_confirm
      //     );
      //     if (!regexCurrentPasswordConfirm) {
      //       this.error.current_password = false;
      //       this.error.current_password_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (!regexCurrentPassword) {
      //       this.error.current_password_confirm = false;
      //       this.error.current_password_confirm_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (regexCurrentPassword && regexCurrentPasswordConfirm) {
      //       return true;
      //     }
      //   } else {
      //     this.error.pass_match = false;
      //     this.error.pass_match_content = this.$t('PASSWORD_NOT_MATCH');
      //     return false;
      //   }
      // }
    },

    checkValidate3() {
      const new_password = this.password.new_password;
      // Kiểm tra rỗng
      if (new_password === '') {
        this.error.new_password = false;

        this.error.new_password_content =
          '新しいパスワードを入力してください。';
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return false;
      }

      // Check length và format
      if (new_password.length !== 12) {
        this.error.new_password = false;
        this.error.new_password_content =
          'パスワードは大文字を含む半角英数字12桁で入力してください';
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return false;
      } else {
        if (validPasswordFormat(new_password)) {
          this.error.new_password = true;
          this.error.new_password_content = '';
          return true;
        } else {
          this.error.new_password = false;
          this.error.new_password_content =
            'パスワードは大文字を含む半角英数字12桁で入力してください';
          this.error.pass_match = true;
          this.error.pass_match_content = '';
          return false;
        }
      }

      // Check validate bắt buộc mật khẩu phải có ít nhất 1 số và 1 chữ cái (không phân biệt chữ hoa chữ thường)
      // if (validPasswordFormat(new_password)) {
      //   this.error.new_password = true;
      //   this.error.new_password_content = '';
      //   return true;
      // } else {
      //   this.error.new_password = false;
      //   this.error.new_password_content =
      //     '正しいパスワード形式を入力してください。';
      //   this.error.pass_match = true;
      //   this.error.pass_match_content = '';
      //   return false;
      // }

      // if (current_password !== '' && current_password_confirm !== '') {
      //   this.error.current_password_confirm = true;
      //   this.error.current_password = true;
      //   // Sau Đã nhập -> Kiểm tra trùng
      //   this.error.pass_match = true;
      //   this.error.pass_match_content = '';
      //   // Cả khi trùng pass kiểm tra độ dài
      //   if (current_password.length > 12) {
      //     this.error.current_password = false;
      //     this.error.current_password_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   if (current_password_confirm.length > 12) {
      //     this.error.current_password_confirm = false;
      //     this.error.current_password_confirm_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   //

      //   if (String(current_password) === String(current_password_confirm)) {
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //     // Check Regex
      //     const regexCurrentPassword = validPassOnlyRequireAz09(
      //       this.password.current_password
      //     );
      //     const regexCurrentPasswordConfirm = validPassOnlyRequireAz09(
      //       this.password.current_password_confirm
      //     );
      //     if (!regexCurrentPasswordConfirm) {
      //       this.error.current_password = false;
      //       this.error.current_password_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (!regexCurrentPassword) {
      //       this.error.current_password_confirm = false;
      //       this.error.current_password_confirm_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (regexCurrentPassword && regexCurrentPasswordConfirm) {
      //       return true;
      //     }
      //   } else {
      //     this.error.pass_match = false;
      //     this.error.pass_match_content = this.$t('PASSWORD_NOT_MATCH');
      //     return false;
      //   }
      // }
    },

    checkValidate4() {
      const new_password_confirm = this.password.new_password_confirm;
      // Kiểm tra rỗng
      if (new_password_confirm === '') {
        this.error.new_password_confirm = false;

        this.error.new_password_confirm_content =
          '新しいパスワード（確認）を入力してください。';
        this.error.pass_match_set = true;
        this.error.pass_match_set_content = '';
        return false;
      }

      // Check length
      if (new_password_confirm.length !== 12) {
        this.error.new_password_confirm = false;
        this.error.new_password_confirm_content =
          'パスワードは大文字を含む半角英数字12桁で入力してください';
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return false;
      } else {
        if (validPasswordFormat(new_password_confirm)) {
          this.error.new_password_confirm = true;
          this.error.new_password_confirm_content = '';
          return true;
        } else {
          this.error.new_password_confirm = false;
          this.error.new_password_confirm_content =
            'パスワードは大文字を含む半角英数字12桁で入力してください';
          this.error.pass_match_set = true;
          this.error.pass_match_set_content = '';
          return false;
        }
      }

      // if (validPasswordFormat(new_password_confirm)) {
      //   this.error.new_password_confirm = true;
      //   this.error.new_password_confirm_content = '';
      //   return true;
      // } else {
      //   this.error.new_password_confirm = false;
      //   this.error.new_password_confirm_content =
      //     '正しいパスワード形式を入力してください。';
      //   this.error.pass_match_set = true;
      //   this.error.pass_match_set_content = '';
      //   return false;
      // }

      // if (current_password !== '' && current_password_confirm !== '') {
      //   this.error.current_password_confirm = true;
      //   this.error.current_password = true;
      //   // Sau Đã nhập -> Kiểm tra trùng
      //   this.error.pass_match = true;
      //   this.error.pass_match_content = '';
      //   // Cả khi trùng pass kiểm tra độ dài
      //   if (current_password.length > 12) {
      //     this.error.current_password = false;
      //     this.error.current_password_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   if (current_password_confirm.length > 12) {
      //     this.error.current_password_confirm = false;
      //     this.error.current_password_confirm_content = this.$t(
      //       'PLEASE_ENTER_LESS_THAN_TWELVE_CHARACTERS'
      //     );
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //   }
      //   //

      //   if (String(current_password) === String(current_password_confirm)) {
      //     this.error.pass_match = true;
      //     this.error.pass_match_content = '';
      //     // Check Regex
      //     const regexCurrentPassword = validPassOnlyRequireAz09(
      //       this.password.current_password
      //     );
      //     const regexCurrentPasswordConfirm = validPassOnlyRequireAz09(
      //       this.password.current_password_confirm
      //     );
      //     if (!regexCurrentPasswordConfirm) {
      //       this.error.current_password = false;
      //       this.error.current_password_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (!regexCurrentPassword) {
      //       this.error.current_password_confirm = false;
      //       this.error.current_password_confirm_content = this.$t(
      //         'VALIDATE_PASS_INPUT'
      //       );
      //       this.error.pass_match = true;
      //       this.error.pass_match_content = '';
      //     }
      //     if (regexCurrentPassword && regexCurrentPasswordConfirm) {
      //       return true;
      //     }
      //   } else {
      //     this.error.pass_match = false;
      //     this.error.pass_match_content = this.$t('PASSWORD_NOT_MATCH');
      //     return false;
      //   }
      // }
    },

    checkMatchPassword() {
      const current_password = this.password.current_password;
      const current_password_confirm = this.password.current_password_confirm;
      if (String(current_password) === String(current_password_confirm)) {
        this.error.pass_match = true;
        this.error.pass_match_content = '';
        return true;
      } else {
        this.error.pass_match = false;
        this.error.pass_match_content = this.$t('PASSWORD_NOT_MATCH');
        return false;
      }
    },

    checkMatchSetPassword() {
      const new_password = this.password.new_password;
      const new_password_confirm = this.password.new_password_confirm;
      if (String(new_password) === String(new_password_confirm)) {
        this.error.pass_match_set = true;
        this.error.pass_match_set_content = '';
        return true;
      } else {
        this.error.pass_match_set = false;
        this.error.pass_match_set_content = this.$t('PASSWORD_NOT_MATCH');
        return false;
      }
    },

    // GOI API xác nhận mật khẩu hiện tại
    async handleConfirmCurrentPass() {
      this.checkValidate1();
      this.checkValidate2();
      if (this.checkValidate1() && this.checkValidate2()) {
        this.checkMatchPassword();
        if (this.checkMatchPassword()) {
          const DATA_CONFIRM_PASS = {
            current_password: this.password.current_password,
            current_password_confirm: this.password.current_password_confirm,
          };

          try {
            const response = await changePassConfirmPost(DATA_CONFIRM_PASS);
            const { code, data } = response.data;
            if (code === 200) {
              this.step = 2;
            } else {
              MakeToast({
                variant: 'warning',
                title: this.$t('warning'),
                content: data,
              });
            }
          } catch (error) {
            console.log(error);
          }
        }
      }
    },

    // GOI API thay đổi mật khẩu
    async handleChangeNewPass() {
      this.checkValidate3();
      this.checkValidate4();
      if (this.checkValidate3() && this.checkValidate4()) {
        this.checkMatchSetPassword();
        if (this.checkMatchSetPassword()) {
          const DATA_NEW_PASS = {
            new_password: this.password.new_password,
            new_password_confirm: this.password.new_password_confirm,
          };

          try {
            const response = await ChangeNewPasswordPut(DATA_NEW_PASS);
            const { code, message } = response.data;
            if (code === 200) {
              this.step = 3;
            } else {
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
      }
    },

    backToLogin() {
      // gọi hàm logout
      this.$store
        .dispatch('user/doLogout')
        .then(() => {
          this.$store
            .dispatch('permission/resetRoutes')
            .then(() => {
              this.$store
                .dispatch('permission/setCurrentRoutes', '')
                .then(() => {
                  this.$router.push('/login');
                })
                .catch(() => {
                  MakeToast({
                    variant: 'danger',
                    title: this.$t('DANGER'),
                    content: this.$t('TOAST_HAVE_ERROR'),
                  });
                });
            })
            .catch(() => {
              MakeToast({
                variant: 'danger',
                title: this.$t('DANGER'),
                content: this.$t('TOAST_HAVE_ERROR'),
              });
            });
        })
        .catch(() => {
          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: this.$t('TOAST_HAVE_ERROR'),
          });
        });
      // this.$router.push('/login');
      this.password.current_password = '';
      this.password.current_password_confirm = '';
      this.error.current_password = '';
      this.error.current_password_confirm = '';
      //
      this.password.new_password = '';
      this.password.new_password_confirm = '';
      this.error.new_password = '';
      this.error.new_password_confirm = '';
    },

    backToHome() {
      this.$router.push('/home');
      this.password.current_password = '';
      this.password.current_password_confirm = '';
      this.error.current_password = '';
      this.error.current_password_confirm = '';
      //
      this.password.new_password = '';
      this.password.new_password_confirm = '';
      this.error.new_password = '';
      this.error.new_password_confirm = '';
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/pages/Login/Login.scss';
</style>
