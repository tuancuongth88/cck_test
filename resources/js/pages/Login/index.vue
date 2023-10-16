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
          <!-- <span>{{ $t('LOGIN_TITLE') }}</span> -->
          <img
            :src="require('@/assets/images/login/bd-logo.png')"
            alt="bd-logo"
            class="bd-logo"
          >
        </div>

        <div class="login-input-container">
          <div class="login-input-wrraper">
            <div class="login-input">
              <img
                :src="require('@/assets/images/login/avata-default.png')"
                alt="avata"
              >
              <b-form-input
                id="mail-address-input"
                v-model="account.mail_address"
                :placeholder="$t('LOGIN_ID')"
                type="text"
                dusk="email"
                :class="error.mail_address === false ? 'is-invalid' : ''"
                :formatter="formatNotJapanese"
                @keyup.enter="handleLogin()"
                @input="handleChangeForm($event, 'email')"
              />
            </div>
            <b-form-invalid-feedback
              class="error-text"
              :state="error.mail_address"
            >
              <!-- {{ $t('VALIDATE.REQUIRED_TEXT') }} -->
              {{ error.email_address_text }}
            </b-form-invalid-feedback>
          </div>

          <div class="login-input-wrraper">
            <div class="login-input">
              <img
                :src="require('@/assets/images/login/key-password.png')"
                alt="password"
              >

              <b-form-input
                id="password-input"
                v-model="account.password"
                :placeholder="$t('PASSWORD')"
                :formatter="format12characters"
                :type="statusPassword ? 'text' : 'password'"
                dusk="password"
                :class="error.password === false ? 'is-invalid' : ''"
                @keyup.enter="handleLogin()"
                @input="handleChangeForm($event, 'password')"
              />

              <div @click="handleShowPass('password')">
                <EyeHideIcon v-if="!statusPassword" />
                <EyeShowIcon v-if="statusPassword" />
              </div>
            </div>

            <b-form-invalid-feedback class="error-text" :state="error.password">
              <!-- {{ $t('VALIDATE.REQUIRED_TEXT') }} -->
              {{ error.password_text }}
            </b-form-invalid-feedback>
          </div>
        </div>

        <div class="login-submit-btns">
          <div class="login-submit btn" @click="handleLogin()">
            <span>{{ $t('BUTTON_LOGIN') }}</span>
          </div>
        </div>

        <div class="login-other-btn-link">
          <span
            id="btn-forget-pass"
            dusk="btn-forget-pass"
            class="btn"
            @click="goToForgetPass()"
          >
            {{ $t('IF_YOU_FORGET_YOUR_PASSWORD') }}
          </span>
          <span
            id="btn-create-hr"
            dusk="btn-create-hr"
            class="btn"
            @click="goToRegister('HUMAN_RESOURCE')"
          >
            {{ $t('REGISTRATION_FOR_HUMAN_RESOURCE_ORGANIZATIONS') }}
          </span>
          <span
            id="btn-create-company"
            dusk="btn-create-company"
            class="btn"
            @click="goToRegister('CORPORATE')"
          >
            {{ $t('CORPORATE_REGISTRATION') }}
          </span>
        </div>
      </div>
      <div class="login-footer-text">
        {{ $t('LOGIN_FOOTER_TEXT') }}
      </div>
    </div>
  </div>
</template>

<script>
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import { validEmail, validPassword } from '@/utils/validate';

import img_bg from '@/assets/images/login/login-bg.png';
import EyeHideIcon from '@/components/EyeHide';
import EyeShowIcon from '@/components/EyeShow';
import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'Login',
  components: {
    EyeHideIcon,
    EyeShowIcon,
  },
  data() {
    return {
      statusPassword: false,

      account: {
        mail_address: '', // 1okuridashi_hanoi
        password: '', // 123456789CCk
      },

      error: {
        mail_address: true,
        email_address_text: '',
        password: true,
        password_text: '',
      },

      isProcess: false,

      message: {
        isShowMessage: false,
        isMessage: '',
      },

      img_bg: img_bg,
    };
  },
  methods: {
    handleShowPass(typeInput) {
      if (typeInput === 'password') {
        if (this.statusPassword === true) {
          this.statusPassword = false;
        } else {
          this.statusPassword = true;
        }
      }
    },
    format12characters(e) {
      const inputValue = String(e).substring(0, 12); // Giới hạn 12 ký tự

      // Loại bỏ các ký tự tiếng Nhật từ giá trị nhập vào
      const filteredValue = inputValue.replace(/[ぁ-んァ-ン一-龯。]/g, '');

      return filteredValue;
    },
    formatNotJapanese(e) {
      return String(e).replace(/[ぁ-んァ-ン一-龯。]/g, ''); // không nhập chữ nhật
    },

    handleChangeForm(event, field) {
      const newValue = event;

      switch (field) {
        case 'email':
          this.error.mail_address = null;
          if (newValue) {
            this.error.mail_address = true;
          } else {
            this.error.mail_address = false;
            this.error.email_address_text = this.$t('VALIDATE.REQUIRED_TEXT');
          }
          break;

        case 'password':
          this.error.password = null;
          if (newValue) {
            this.error.password = true;
          } else {
            this.error.password = false;
            this.error.password_text = this.$t('VALIDATE.REQUIRED_TEXT');
          }
          break;
        default:
          break;
      }
    },

    checkvalidateEmail() {
      if (this.account.mail_address === '') {
        this.error.mail_address = false;
        // this.error.email_address_text = this.$t(
        //   'PLEASE_ENTER_YOUR_EMAIL_ADDRESS'
        // );
        this.error.email_address_text = this.$t('VALIDATE.REQUIRED_TEXT');
      } else {
        // Nếu có giá trị thì -> Regex
        if (validEmail(this.account.mail_address)) {
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

    checkvalidatePassword() {
      if (this.account.password === '') {
        this.error.password = false;
        this.error.password_text = this.$t('VALIDATE.REQUIRED_TEXT');
      } else {
        if (validPassword(this.account.password)) {
          this.error.password = true;
          this.error.password_text = '';
          return true;
        } else {
          this.error.password = false;
          this.error.password_text = this.$t(
            'PLEASE_ENTER_THE_CORRECT_PASSWORD_FORMAT'
          );
          return false;
        }
      }
    },

    async handleLogin() {
      // this.checkvalidate();
      this.checkvalidateEmail();
      this.checkvalidatePassword();
      if (this.checkvalidateEmail() && this.checkvalidatePassword()) {
        await this.$store.dispatch('app/setLoading', true);

        try {
          const params = {
            mail_address: this.account.mail_address,
            password: this.account.password,
          };

          const response = await login(params);
          const { message } = response.data;

          if (response['data']['code'] === 200) {
            const TOKEN = response['data']['data']['access_token'];
            const USER = response['data']['data']['profile'];

            const { ROLES, PERMISSIONS } = handleRole([]);

            const USER_INFO = {
              token: TOKEN,
              profile: USER,
              roles: ROLES,
              permissions: PERMISSIONS,
            };

            await this.$store.dispatch('user/saveLogin', USER_INFO).then(() => {
              this.$store
                .dispatch('permission/generateRoutes', {
                  roles: ROLES,
                  permissions: [],
                })
                .then((routes) => {
                  for (let route = 0; route < routes.length; route++) {
                    this.$router.addRoute(routes[route]);
                  }

                  this.$router.push({ path: '/home' });
                });
            });

            this.redirectByAuth(USER);
          } else {
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: message,
            });
          }

          await this.$store.dispatch('app/setLoading', false);
        } catch (error) {
          await this.$store.dispatch('app/setLoading', false);

          const message = error.response.data.message || '';

          this.$toast.error(message);
        }
      } else {
        this.message.isShowMessage = true;
        this.message.isMessage = this.$t('ERROR_VALIDATE_EMAIL_PASSWORD');
      }
      // if (
      //   validEmail(this.account.mail_address) &&
      //   validPassword(this.account.password)
      // ) {
      //   await this.$store.dispatch('app/setLoading', true);

      //   try {
      //     const params = {
      //       mail_address: this.account.mail_address,
      //       password: this.account.password,
      //     };

      //     const response = await login(params);
      //     const { message } = response.data;

      //     if (response['data']['code'] === 200) {
      //       const TOKEN = response['data']['data']['access_token'];
      //       const USER = response['data']['data']['profile'];

      //       const { ROLES, PERMISSIONS } = handleRole([]);

      //       const USER_INFO = {
      //         token: TOKEN,
      //         profile: USER,
      //         roles: ROLES,
      //         permissions: PERMISSIONS,
      //       };

      //       await this.$store.dispatch('user/saveLogin', USER_INFO).then(() => {
      //         this.$store
      //           .dispatch('permission/generateRoutes', {
      //             roles: ROLES,
      //             permissions: [],
      //           })
      //           .then((routes) => {
      //             for (let route = 0; route < routes.length; route++) {
      //               this.$router.addRoute(routes[route]);
      //             }

      //             this.$router.push({ path: '/home' });
      //           });
      //       });

      //       this.redirectByAuth(USER);
      //     } else {
      //       MakeToast({
      //         variant: 'warning',
      //         title: 'warning',
      //         content: message,
      //       });
      //     }

      //     await this.$store.dispatch('app/setLoading', false);
      //   } catch (error) {
      //     await this.$store.dispatch('app/setLoading', false);

      //     const message = error.response.data.message || '';

      //     this.$toast.error(message);
      //   }
      // } else {
      //   this.message.isShowMessage = true;
      //   this.message.isMessage = this.$t('ERROR_VALIDATE_EMAIL_PASSWORD');
      // }
    },
    redirectByAuth(user) {
      this.$router.push('/home');
    },
    goToForgetPass() {
      this.$router.push({ path: '/forget-password' });
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
@import '@/pages/Login/Login.scss';
</style>
