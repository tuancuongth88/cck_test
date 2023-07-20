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

        <div class="login-input-container">
          <div class="login-input-wrraper">
            <div class="login-input">
              <img
                :src="require('@/assets/images/login/avata-default.png')"
                alt="avata"
              >

              <b-form-input
                v-model="account.mail_address"
                :placeholder="$t('LOGIN_ID')"
                type="text"
                @keyup.enter="handleLogin()"
                @input="handleChangeForm($event, 'email')"
              />
            </div>
            <b-form-invalid-feedback
              class="error-text"
              :state="error.mail_address"
            >
              {{ $t('VALIDATE.REQUIRED_TEXT') }}
            </b-form-invalid-feedback>
          </div>

          <div class="login-input-wrraper">
            <div class="login-input">
              <img
                :src="require('@/assets/images/login/key-password.png')"
                alt="password"
              >

              <b-form-input
                v-model="account.password"
                :placeholder="$t('PASSWORD')"
                :formatter="format12characters"
                :type="statusPassword ? 'text' : 'password'"
                @keyup.enter="handleLogin()"
                @input="handleChangeForm($event, 'password')"
              />

              <div @click="handleShowPass('password')">
                <EyeHideIcon v-if="!statusPassword" />
                <EyeShowIcon v-if="statusPassword" />
              </div>
            </div>

            <b-form-invalid-feedback class="error-text" :state="error.password">
              {{ $t('VALIDATE.REQUIRED_TEXT') }}
            </b-form-invalid-feedback>
          </div>
        </div>

        <div class="login-submit-btns">
          <div class="login-submit btn" @click="handleLogin()">
            <span>{{ $t('BUTTON_LOGIN') }}</span>
          </div>
        </div>

        <div class="login-other-btn-link">
          <span class="btn" @click="goToForgetPass()">
            {{ $t('IF_YOU_FORGET_YOUR_PASSWORD') }}
          </span>
          <span class="btn" @click="goToRegister('HUMAN_RESOURCE')">
            {{ $t('REGISTRATION_FOR_HUMAN_RESOURCE_ORGANIZATIONS') }}
          </span>
          <span class="btn" @click="goToRegister('CORPORATE')">
            {{ $t('CORPORATE_REGISTRATION') }}
          </span>
        </div>
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
        mail_address: '1okuridashi_hanoi@gmail.vn',
        password: '123456789CCk',
      },

      error: {
        mail_address: true,
        password: true,
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
        console.log('handleShowPass password');
        if (this.statusPassword === true) {
          this.statusPassword = false;
        } else {
          this.statusPassword = true;
        }
      }
    },
    format12characters(e) {
      return String(e).substring(0, 12);
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
          }
          break;

        case 'password':
          this.error.password = null;
          if (newValue) {
            this.error.password = true;
          } else {
            this.error.password = false;
          }
          break;
        default:
          break;
      }
    },
    async handleLogin() {
      if (
        validEmail(this.account.mail_address) &&
        validPassword(this.account.password)
      ) {
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

            console.log('cái USER_INFO', USER_INFO);

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
    },
    redirectByAuth(user) {
      this.$router.push('/home');
    },
    goToForgetPass() {
      this.$router.push({ path: '/reset-password-send-email' });
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
