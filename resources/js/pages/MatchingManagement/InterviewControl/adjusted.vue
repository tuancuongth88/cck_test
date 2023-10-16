<template>
  <div>
    <modal-common
      :refs="'adjusted-modal'"
      :size="step === 4 || step === 5 ? 'lg' : 'md'"
      @reset-modal="resetModal"
    >
      <div id="adjusted-modal" slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ detail_data.full_name }} <br>
            {{ detail_data.full_name_ja }}
          </h2>
          <h5>
            {{ detail_data.job_name }} <br>
            <span v-if="detail_data.interview_from === 'entry'">{{
              $t('ENTRY')
            }}</span>
            <span v-if="detail_data.interview_from === 'offer'">{{
              $t('OFFER')
            }}</span>
          </h5>

          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>

        <!--  -->
        <div v-if="isOff" class="content-detail px-4">
          <h6 class="text-center">{{ detail_data.updating_date_ja }}</h6>
          <h3 class="text-center text-red p-4">
            {{ detail_data.status_selection_name }}
          </h3>
          <div v-if="renderOffReason" class="text-center">
            <h6>{{ renderOffReason }}</h6>
          </div>
        </div>

        <div class="content-detail">
          <h3 v-if="step === 1 || step === 2 || step === 3" class="text-center">
            <!-- {{ detail_data.status_selection_name }} -->
            {{ current_rount_name }}
          </h3>
          <h3 v-if="step === 4 || step === 5" class="text-center">二次合格</h3>
          <template v-if="step === 1 || step === 2 || step === 3">
            <!-- <h6 class="text-center">個別面接</h6> -->
            <h6 v-if="detail_data.type_code === 1" class="text-center">
              {{ $t('GROUP_INTERVIEW') }}
            </h6>
            <h6 v-if="detail_data.type_code === 2" class="text-center">
              {{ $t('PRIVATE_INTERVIEW') }}
            </h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>{{ rederCalenderTime }}</h5>
              <b-link>
                <u> {{ rederCalenderNow.url_zoom }} </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                {{ $t('MEETING_ID') }}：{{ rederCalenderNow.id_zoom }} <br>
                {{ $t('PASSCODE') }}：{{ rederCalenderNow.password_zoom }}
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-group>
                <b-form-select
                  v-model="select_status"
                  :options="renderStatusOption"
                  :class="error_select_status ? 'is-invalid' : ''"
                  :disabled="isOff"
                  @change="handleChange('select_status')"
                />
                <b-form-invalid-feedback :state="!error_select_status">
                  <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                </b-form-invalid-feedback>
              </b-form-group>

              <b-form-group>
                <b-form-select
                  v-if="select_status === 1"
                  id="action_select_pass"
                  v-model="action"
                  class="mt-3"
                  :options="actionOption"
                  :class="error_action ? 'is-invalid' : ''"
                  @change="handleChange('action')"
                />
                <b-form-invalid-feedback :state="!error_action">
                  <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                </b-form-invalid-feedback>
              </b-form-group>

              <template v-if="select_status === 3">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1">{{ '回答期限' }}</label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <!-- <b-form-input
                  v-model="date_offer"
                  type="date"
                  :class="error_date_offer ? 'is-invalid' : ''"
                  @change="handleChange('date_offer')"
                /> -->
                <b-form-datepicker
                  id="date_offer"
                  v-model="date_offer"
                  locale="ja"
                  placeholder=""
                  :date-format-options="{
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit',
                    weekday: 'short',
                  }"
                  :class="error_date_offer ? ' is-invalid' : ''"
                  :min="minDate"
                  @input="handleChange('date_offer')"
                />
                <b-form-invalid-feedback :state="!error_date_offer">
                  <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                </b-form-invalid-feedback>
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">{{ $t('TAB.RESULT') }}</b-col>
                <b-col cols="8" class="d-flex flex-wrap align-items-center">
                  {{ renderStatus }}
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ select_status === 3 ? '回答期限' : '' }}
                </b-col>
                <b-col cols="8" class="d-flex flex-wrap align-items-center">
                  <span v-if="select_status === 1">{{ renderAction }}</span>
                  <span v-if="select_status === 3">{{ renderDateOffer }}</span>
                </b-col>
              </b-row>
            </template>
          </template>
          <!-- button -->
          <div
            v-if="roleCheck"
            class="d-flex flex-column align-items-center mt-4"
          >
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                :disabled="isOff"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                :disabled="isOff"
                @click="handleShowModalCancelInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ $t('CONFIRM_BTN') }}
            </b-button>

            <template v-if="step === 3">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="handleCancelReview">{{
                    $t('CLOSE_BTN')
                  }}</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleConfirmInterviewCompanyReview"
                >
                  <span>{{ $t('SEND_WITH_THIS_CONTENT') }}</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
            </template>
          </div>
        </div>

        <!-- History -->
        <template v-if="step === 1">
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
            <h3 class="text-center font-weight-bold">{{ $t('PASSED') }}</h3>
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
        </template>
      </div>
    </modal-common>

    <!-- Cancel modal -->
    <modal-common
      :refs="'cancel-modal'"
      :size="'md'"
      @reset-modal="resetModalCancel"
    >
      <div slot="body">
        <div class="text-center">
          <h5 class="text-danger">
            {{ $t('INTERVIEW_CANCEL_MODAL_TITLE') }}
          </h5>
        </div>
        <div class="content-detail p-4">
          <!-- <h4 class="text-center mb-4">{{ detail_data.status_selection_name }}</h4> -->
          <h4 class="text-center mb-4">{{ current_rount_name }}</h4>
          <form ref="form">
            <div class="d-flex align-items-center">
              <label class="mb-0 mr-2" for="textarea">{{
                $t('INTERVIEW_CANCEL_MODAL_LABEL')
              }}</label>
              <!-- <b-badge class="badge-not-required mx-2" variant="secondary">{{
                $t('ARBITRARY')
              }}</b-badge> -->
              <Arbitrarily />
            </div>
            <b-form-textarea
              id="textarea"
              v-model="note"
              placeholder=""
              rows="6"
              max-rows="28"
              max-lengh="2000"
            />
          </form>

          <div class="d-flex align-items-center mt-5 justify-content-center">
            <b-button
              variant="secondary"
              class="mx-1"
              @click="handleCloseCancel"
            >
              {{ $t('MODAL_BUTTON_CANCEL') }}
            </b-button>
            <b-button
              variant="danger"
              class="text-white mx-1"
              @click="handleStopInterview"
            >
              {{ $t('MODAL_BUTTON_CANCEL_INTERVIEW') }}</b-button>
          </div>
        </div>
      </div>
    </modal-common>
  </div>
</template>

<script>
import EventBus from '@/utils/eventBus';
import ModalCommon from '../../../layout/components/ModalCommon/matching.vue';
import moment from 'moment';
import {
  putCancelInterview,
  putConfirmReview,
} from '@/api/modules/matching.js';
import { MakeToast } from '../../../utils/toastMessage';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

export default {
  name: 'Adjusting',
  components: {
    ModalCommon,
    Arbitrarily,
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
      step: 1,
      detail_data: {},
      current_rount_name: '',

      note: '',
      note_error: false,

      select_status: null,
      error_select_status: false,
      action: null,
      error_action: false,
      date_offer: '',
      error_date_offer: false,

      status_option: [
        {
          value: 1,
          text: '合格',
        },
        {
          value: 2,
          text: '不合格',
        },
        {
          value: 3,
          text: '内定',
        },
      ],
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

    roleCheck() {
      const PROFILE = this.$store.getters.profile;
      const ROLE = PROFILE.type;
      const listRole = [1, 2, 4];

      return listRole.includes(ROLE);
    },

    renderStatusOption() {
      let LIST_OPTION = this.status_option;
      if (
        this.detail_data.optionSelectRound.round_current_option.round_number ===
        5
      ) {
        LIST_OPTION = [
          {
            value: 2,
            text: '不合格',
          },
          {
            value: 3,
            text: '内定',
          },
        ];
      }

      return LIST_OPTION;
    },

    minDate() {
      const now = new Date();
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      const minDate = new Date(today);
      // minDate.setDate(now.getDate() + 1);
      minDate.setDate(now.getDate() + 7);
      return minDate;
    },

    // maxDate() {
    //   const now = new Date();
    //   const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    //   const macDate = new Date(today);
    //   macDate.setDate(now.getDate() + 7);
    //   return macDate;
    // },

    actionOption() {
      const LIST_OPTION = [];
      const LIST = this.detail_data.optionSelectRound.round_option_inteview;
      LIST.map((item) => {
        LIST_OPTION.push({
          value: item.round_number,
          text: item.round_text_ja,
        });
      });

      return LIST_OPTION;
    },

    renderStatus() {
      const result = this.status_option.filter(
        (item) => item.value === this.select_status
      );
      return result[0].text;
    },

    renderAction() {
      const result = this.actionOption.filter(
        (item) => item.value === this.action
      );
      return result[0].text;
    },
    renderDateOffer() {
      if (!this.date_offer) {
        return '';
      }

      const DATE_STRING = moment(this.date_offer).format('YYYY年M月D日');
      const DATE = new Date(this.date_offer);
      const day = DATE.getDay();
      let WEEK_DAY = '';
      switch (day) {
        case 0:
          WEEK_DAY = '日'; // Sun
          break;
        case 1:
          WEEK_DAY = '月'; // Mon
          break;
        case 2:
          WEEK_DAY = '火'; // Tue
          break;
        case 3:
          WEEK_DAY = '水'; // Wed
          break;
        case 4:
          WEEK_DAY = '木'; // Thu
          break;
        case 5:
          WEEK_DAY = '金'; // Fri
          break;
        case 6:
          WEEK_DAY = '土'; // Sat
          break;

        default:
          break;
      }
      return `${DATE_STRING} (${WEEK_DAY})`;
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
          const idx = this.detail_data.calendar.length - 1;
          CALENDER_DATA = this.detail_data.calendar[idx];
        }
      }

      return CALENDER_DATA;
    },

    rederCalenderTime() {
      let CALENDER_DATA = [];
      if (this.detail_data.calendar) {
        if (!(this.detail_data.calendar.length === 0)) {
          const idx = this.detail_data.calendar.length - 1;
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
    EventBus.$on('open-modal-adjusted', () => {
      EventBus.$emit('open-modal', 'adjusted-modal');
    });
  },

  methods: {
    resetModal() {
      this.step = 1;
      this.select_status = null;
      this.action = null;
      this.date_offer = '';
    },

    resetModalCancel() {
      this.note = '';
      this.note_error = false;
    },

    handleShowModalCancelInterview() {
      EventBus.$emit('open-modal', 'cancel-modal');
    },

    handleCloseCancel() {
      EventBus.$emit('close-modal', 'cancel-modal');
    },

    async handleStopInterview() {
      const PARAMS = {
        id: this.detail_data.id,
        // note: this.note,
      };
      if (this.note) {
        PARAMS.note = this.note;
      }
      console.log('hello PARAMS ====>', PARAMS);
      try {
        const response = await putCancelInterview(PARAMS);
        const { code, message } = response.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: '中止しました。',
          });
          EventBus.$emit('close-modal', 'cancel-modal');
          EventBus.$emit('close-modal', 'adjusted-modal');
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
    handleCancelReview() {
      EventBus.$emit('close-modal', 'adjusted-modal');
    },

    handleChange(type) {
      switch (type) {
        case 'select_status':
          if (!this.select_status) {
            this.error_select_status = true;
            this.error_action = false;
            this.error_date_offer = false;
            this.action = null;
            this.date_offer = '';
          } else if (this.select_status === 1) {
            this.error_select_status = false;
            this.error_date_offer = false;
            this.date_offer = '';
          } else if (this.select_status === 3) {
            this.error_select_status = false;
            this.error_action = false;
            this.action = null;
          } else {
            this.error_select_status = false;
            this.error_action = false;
            this.error_date_offer = false;
            this.action = null;
            this.date_offer = '';
          }
          break;
        case 'action':
          if (!this.action) {
            this.error_action = true;
          } else {
            this.error_action = false;
          }
          break;
        case 'date_offer':
          if (!this.date_offer) {
            this.error_date_offer = true;
          } else {
            this.error_date_offer = false;
          }
          break;
        case 'note':
          if (!this.note) {
            this.note_error = true;
          } else {
            this.note_error = false;
          }
          break;

        default:
          break;
      }
    },

    checkvalidate() {
      let check = true;
      if (!this.select_status) {
        check = false;
        this.error_select_status = true;
      }
      if (this.select_status === 1 && !this.action) {
        check = false;
        this.error_action = true;
      }
      if (this.select_status === 3 && !this.date_offer) {
        check = false;
        this.error_date_offer = true;
      }

      return check;
    },

    async handleConfirmInterviewCompanyReview() {
      const PARAMS = {
        id: this.detail_data.id,
        status: this.select_status,
        date_offer: this.date_offer,
        action: this.action,
      };
      try {
        const response = await putConfirmReview(PARAMS);
        const { code, message } = response.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: this.$t('SENT'),
          });
          EventBus.$emit('close-modal', 'adjusted-modal');
          this.$emit('reloadList');
          this.$emit('reloadResult');
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

    handleNextStep(e) {
      e.preventDefault();
      if (this.step === 2) {
        const VALIDATE = this.checkvalidate();
        if (!VALIDATE) {
          return;
        }
      }
      this.step++;
    },

    handleBack() {
      this.step--;
      if (this.step === 0) {
        EventBus.$emit('close-modal', 'adjusted-modal');
        this.step = 1;
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
</style>
