<template>
  <modal-common
    :refs="'URL-setting-modal'"
    :size="'md'"
    @reset-modal="resetModal"
  >
    <div id="URL-setting-modal" slot="body">
      <h3 class="font-weight-bold text-center">
        {{ detail_data.full_name }} <br>
        {{ detail_data.full_name_ja }}
      </h3>
      <div class="text-center mt-4">
        {{ detail_data.job_name }} <br>
        <span v-if="detail_data.interview_from === 'entry'">{{
          $t('ENTRY')
        }}</span>
        <span v-if="detail_data.interview_from === 'offer'">{{
          $t('OFFER')
        }}</span>
      </div>

      <div v-if="isOff" class="content-detail px-4">
        <h6 class="text-center">{{ detail_data.updating_date_ja }}</h6>
        <h3 class="text-center text-red p-4">
          {{ detail_data.status_selection_name }}
        </h3>
        <div v-if="renderOffReason" class="text-center">
          <h6>{{ renderOffReason }}</h6>
        </div>
      </div>

      <div class="content-detail px-5">
        <!-- <h3 class="text-center">一次面接</h3> -->
        <!-- <h3 class="text-center">{{ detail_data.status_selection_name }}</h3> -->
        <h3 class="text-center">{{ current_rount_name }}</h3>
        <!-- <h6 class="text-center">個別面接</h6> -->
        <h6 v-if="detail_data.type_code === 1" class="text-center">
          {{ $t('GROUP_INTERVIEW') }}
        </h6>
        <h6 v-if="detail_data.type_code === 2" class="text-center">
          {{ $t('PRIVATE_INTERVIEW') }}
        </h6>
        <div class="d-flex flex-column align-items-center mt-4">
          <!-- <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5> -->
          <h5 class="mb-5">{{ rederCalenderNow }}</h5>
        </div>
        <form v-if="roleCheck" ref="form">
          <b-form-group label="オンライン面接URL" label-for="url-zoom">
            <b-form-input
              v-model="form_data.url_zoom"
              dusk="url_zoom_text"
              :class="[
                form_data_error.url_zoom_error ? 'is-invalid' : '',
                form_data_error.url_zoom_error_length ? 'is-invalid' : '',
              ]"
              :disabled="isOff"
              @input="handleChange('url_zoom')"
            />
            <span v-if="form_data_error.url_zoom_error" class="error-text">
              <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
            </span>
            <span
              v-if="form_data_error.url_zoom_error_length"
              class="error-text"
            >
              <span>半角英数字のみ、最大50字</span>
            </span>
          </b-form-group>
          <b-form-group label="ミーティングID" label-for="id-zoom">
            <b-form-input
              v-model="form_data.id_zoom"
              dusk="id_zoom_text"
              :class="[
                form_data_error.id_zoom_error ? 'is-invalid' : '',
                form_data_error.id_zoom_error_length ? 'is-invalid' : '',
              ]"
              :disabled="isOff"
              @input="handleChange('id_zoom')"
            />
            <span v-if="form_data_error.id_zoom_error" class="error-text">
              <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
            </span>
            <span
              v-if="form_data_error.id_zoom_error_length"
              class="error-text"
            >
              <span>半角英数字のみ、最大50字</span>
            </span>
          </b-form-group>
          <b-form-group label="パスコード" label-for="name-input">
            <b-form-input
              v-model="form_data.password_zoom"
              dusk="password_zoom_text"
              :class="[
                form_data_error.password_zoom_error ? 'is-invalid' : '',
                form_data_error.password_zoom_error_length ? 'is-invalid' : '',
              ]"
              :disabled="isOff"
              @input="handleChange('password_zoom')"
            />
            <span v-if="form_data_error.password_zoom_error" class="error-text">
              <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
            </span>
            <span
              v-if="form_data_error.password_zoom_error_length"
              class="error-text"
            >
              <span>半角英数字のみ、最大50字</span>
            </span>
          </b-form-group>
        </form>
        <div
          v-if="roleCheck"
          class="d-flex flex-column align-items-center mt-4"
        >
          <b-button
            variant="warning"
            class="w-25 text-white"
            :disabled="isOff"
            @click="handleSetupZoom"
          >
            {{ $t('BUTTON.SAVE') }}
          </b-button>
        </div>
      </div>

      <div
        v-for="(item, index) in rederCalender"
        :key="`detail-calender-${index}`"
        class="content-detail"
      >
        <h3 class="text-center">{{ item.number_ja }}</h3>
        <h6 v-if="item.type_code === 1" class="text-center">
          {{ $t('GROUP_INTERVIEW') }}
        </h6>
        <h6 v-if="item.type_code === 2" class="text-center">
          {{ $t('PRIVATE_INTERVIEW') }}
        </h6>
        <h3 class="text-center" style="font-weight: bold">
          {{ item.status_ja }}
        </h3>
        <div class="d-flex flex-column align-items-center mt-4">
          <h5>{{ item.calendarInterview[0].timeJa }}</h5>
          <b-link>
            <u> {{ item.url_zoom }} </u>
          </b-link>
        </div>
        <div class="d-flex flex-column align-items-center mt-4">
          <p>
            {{ $t('MEETING_ID') }}：{{ item.id_zoom }} <br>
            {{ $t('PASSCODE') }}：{{ item.password_zoom }}
          </p>
        </div>
      </div>
    </div>
  </modal-common>
</template>

<script>
import EventBus from '@/utils/eventBus';
import ModalCommon from '../../../layout/components/ModalCommon/matching.vue';
// import moment from 'moment';
import { putSetupZoom } from '@/api/modules/matching.js';
import { MakeToast } from '../../../utils/toastMessage';

export default {
  name: 'Adjusting',
  components: {
    ModalCommon,
  },

  props: {
    detailData: {
      type: Object,
      default: function() {
        return {};
      },
      required: true,
    },
  },

  data() {
    return {
      detail_data: {},
      current_rount_name: '',
      form_data: {
        url_zoom: '',
        id_zoom: '',
        password_zoom: '',
      },
      form_data_error: {
        url_zoom_error: false,
        url_zoom_error_length: false,
        id_zoom_error: false,
        id_zoom_error_length: false,
        password_zoom_error: false,
        password_zoom_error_length: false,
      },
    };
  },

  computed: {
    isOff() {
      const status_off = [3, 4];
      if (status_off.includes(this.detail_data.status_selection)) {
        return true;
      }
      return false;
    },

    roleCheck() {
      const PROFILE = this.$store.getters.profile;
      const ROLE = PROFILE.type;
      const listRole = [1, 3];

      return listRole.includes(ROLE);
    },

    renderOffReason() {
      // let CALENDER_DATA = [];
      // if (this.detail_data.calendar) {
      //   if (!(this.detail_data.calendar.length === 0)) {
      //     const idx = this.detail_data.calendar
      //       ? this.detail_data.calendar.length - 1
      //       : 0;
      //     CALENDER_DATA = this.detail_data.calendar[idx];
      //   }
      // }

      // let REASON = '';
      // if (CALENDER_DATA) {
      //   if (
      //     CALENDER_DATA.calendarInterview &&
      //     CALENDER_DATA.calendarInterview.length > 0
      //   ) {
      //     REASON = CALENDER_DATA.remark;
      //   }
      // }

      return this.detail_data.note;
    },

    rederCalender() {
      const CALENDER_DATA = [];
      if (this.detail_data.calendar && this.detail_data.calendar.length === 0) {
        return CALENDER_DATA;
      } else {
        if (this.detail_data.calendar) {
          let idx = this.detail_data.calendar.length - 2;
          while (idx >= 0) {
            CALENDER_DATA.push(this.detail_data.calendar[idx]);

            idx--;
          }
        }
      }

      return CALENDER_DATA;
    },

    rederCalenderNow() {
      let CALENDER_DATA = [];
      if (this.detail_data.calendar) {
        if (!(this.detail_data.calendar.length === 0)) {
          const idx = this.detail_data.calendar
            ? this.detail_data.calendar.length - 1
            : 0;
          CALENDER_DATA = this.detail_data.calendar[idx];
        }
      }

      let CALENDER = '';
      if (CALENDER_DATA) {
        if (
          CALENDER_DATA.calendarInterview &&
          CALENDER_DATA.calendarInterview.length > 0
        ) {
          CALENDER = CALENDER_DATA.calendarInterview[0].timeJa;
        }
      }

      return CALENDER;
    },
  },

  watch: {
    detailData: {
      handler: function(props_data) {
        if (props_data) {
          this.detail_data = props_data;
          this.current_rount_name =
            props_data.optionSelectRound.round_current_option.round_name_current;
        }
      },
      deep: true,
    },
  },

  created() {
    EventBus.$on('open-modal-url-setting', () => {
      EventBus.$emit('open-modal', 'URL-setting-modal');
    });
  },

  methods: {
    resetModal() {
      this.form_data = {
        url_zoom: '',
        id_zoom: '',
        password_zoom: '',
      };
      this.form_data_error = {
        url_zoom_error: false,
        url_zoom_error_length: false,
        id_zoom_error: false,
        id_zoom_error_length: false,
        password_zoom_error: false,
        password_zoom_error_length: false,
      };
    },

    handleChange(type) {
      switch (type) {
        case 'url_zoom':
          if (!this.form_data.url_zoom) {
            this.form_data_error.url_zoom_error = true;
          } else {
            this.form_data_error.url_zoom_error = false;
          }
          if (this.form_data.url_zoom.length > 50) {
            this.form_data_error.url_zoom_error_length = true;
          } else {
            this.form_data_error.url_zoom_error_length = false;
          }
          break;
        case 'id_zoom':
          if (!this.form_data.id_zoom) {
            this.form_data_error.id_zoom_error = true;
          } else {
            this.form_data_error.id_zoom_error = false;
          }
          if (this.form_data.id_zoom.length > 50) {
            this.form_data_error.id_zoom_error_length = true;
          } else {
            this.form_data_error.id_zoom_error_length = false;
          }

          break;
        case 'password_zoom':
          if (!this.form_data.password_zoom) {
            this.form_data_error.password_zoom_error = true;
          } else {
            this.form_data_error.password_zoom_error = false;
          }
          if (this.form_data.password_zoom.length > 50) {
            this.form_data_error.password_zoom_error_length = true;
          } else {
            this.form_data_error.password_zoom_error_length = false;
          }

          break;

        default:
          break;
      }
    },

    checkvalidate() {
      let check = true;
      if (!this.form_data.url_zoom) {
        this.form_data_error.url_zoom_error = true;
        check = false;
      }
      if (this.form_data.url_zoom.length > 50) {
        this.form_data_error.url_zoom_error_length = true;
        check = false;
      }
      if (!this.form_data.id_zoom) {
        this.form_data_error.id_zoom_error = true;
        check = false;
      }
      if (this.form_data.id_zoom.length > 50) {
        this.form_data_error.id_zoom_error_length = true;
        check = false;
      }
      if (!this.form_data.password_zoom) {
        this.form_data_error.password_zoom_error = true;
        check = false;
      }
      if (this.form_data.password_zoom.length > 50) {
        this.form_data_error.password_zoom_error_length = true;
        check = false;
      }
      return check;
    },

    async handleSetupZoom() {
      const VALIDATE = this.checkvalidate();
      if (!VALIDATE) {
        return;
      }
      const PARAMS = this.form_data;
      PARAMS.id = this.detail_data.id;

      // console.log('PARAM: ', PARAMS);

      try {
        const response = await putSetupZoom(PARAMS);
        const { code, message } = response.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: this.$t('SENT'),
          });
          EventBus.$emit('close-modal', 'URL-setting-modal');
          this.$emit('reloadList');
        } else {
          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/scss/modules/MatchingManagement/Header.scss';

::v-deep .is-invalid-type-check {
  label {
    color: #dc3545;
  }
  label::before {
    border-color: #dc3545;
  }
}
.content-detail {
  background-color: #f9f9ff;
  padding: 1rem 2rem;
  margin-top: 2rem;
}

.w-137 {
  min-width: 137px;
}

.bg-white {
  background-color: #fff;
}

.btn-status {
  min-width: 100px;
}

.text-red {
  color: red;
}

.error-text {
  color: #dc3545;
}
</style>
