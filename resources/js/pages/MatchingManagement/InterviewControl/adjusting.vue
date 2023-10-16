<template>
  <div>
    <modal-common
      :refs="'adjusting-modal'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div id="adjusting-modal" slot="body">
        <div slot="body">
          <div v-if="step === 1" class="text-center">
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
          </div>

          <div v-if="step === 2" class="text-center">
            <div>
              <h2 class="font-weight-bold">
                {{ detail_data.full_name }} <br>
                {{ detail_data.full_name_ja }}
              </h2>
              <!-- <h5>
                {{ detail_data.job_name }} <br>
                <span v-if="detail_data.interview_from === 'entry'">{{$t('ENTRY')}}</span>
                <span v-if="detail_data.interview_from === 'offer'">{{
                $t('OFFER')
              }}</span>
              </h5> -->
            </div>
            <template v-if="detail_data.type_code === 1">
              <div
                v-for="(item, index) in detail_data.candidateList"
                :key="`hr-${index}`"
              >
                <h2 class="font-weight-bold">
                  {{ item.full_name }} <br>
                  {{ item.full_name_ja }}
                </h2>
                <!-- <h5>
                  {{ detail_data.job_name }} <br>
                  {{
                    $t('ENTRY')
                  }}
                </h5> -->
              </div>
            </template>
            <h5>
              {{ detail_data.job_name }} <br>
              <span v-if="detail_data.interview_from === 'entry'">{{
                $t('ENTRY')
              }}</span>
              <span v-if="detail_data.interview_from === 'offer'">{{
                $t('OFFER')
              }}</span>
            </h5>
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

          <div v-if="roleCheck" class="content-detail px-4">
            <!-- <h4 class="text-center">一次面接</h4> -->
            <!-- <h4 class="text-center">{{ detail_data.status_selection_name }}</h4> -->
            <h4 class="text-center">{{ current_rount_name }}</h4>
            <b-form v-if="step === 1" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  <!-- {{ $t('INTERVIEW_GROUP') }} -->
                  {{ detail_data.type_name }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <!-- <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                /> -->
                <b-form-select
                  v-model="time"
                  :options="carlenderOption"
                  :class="is_error_time ? 'is-invalid' : ''"
                  :disabled="step !== 1 || isOff"
                  @change="changeTime('time')"
                />
                <b-form-invalid-feedback :state="!is_error_time">
                  <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                </b-form-invalid-feedback>
              </b-form-group>
              <!-- <h6 v-if="step === 2" class="text-red"> -->
              <h6 v-if="time && detail_data.type_code === 1" class="text-red">
                この人材は他の{{ detail_data.candidateList.length }}名の人材
                <span
                  v-for="(item, index) in detail_data.candidateList"
                  :key="`hr-${index}`"
                >{{ item.full_name_ja }},
                </span>
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6>
            </b-form>
            <div v-if="step === 2" class="pt-3">
              <b-row>
                <b-col cols="4">{{ $t('INTERVIEW_FORMAT') }} </b-col>
                <b-col v-if="detail_data.type_code === 1" cols="8">
                  {{ $t('GROUP_INTERVIEW') }}</b-col>
                <b-col v-if="detail_data.type_code === 2" cols="8">
                  {{ $t('PRIVATE_INTERVIEW') }}</b-col>
              </b-row>
              <b-row>
                <b-col
                  cols="4"
                  class="my-2"
                >{{ $t('INTERVIEW_CANDIDATE_DATE') }}
                </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <!-- <div>2023年3月1日（水）午後2時〜午後3時</div> -->
                  <div>{{ renderTimeText }}</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1"
                variant="warning"
                class="my-1 text-white w-137"
                :disabled="isOff"
                @click="handleNextStep"
              >
                {{ $t('GO_CONFIRM') }}</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
                :disabled="isOff"
                @click="handleShowDeclineModal"
              >{{
                $t(
                  'STATUS.BTN_STATUS_MATCHING_INTERVIEW.STATUS_INTERVIEW_DECLINE'
                )
              }}</b-button>

              <div v-if="step === 2" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">{{
                    $t('RETURN_TO_PREVIOUS_SCREEN')
                  }}</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleConfirmCalender"
                >{{ $t('SEND_WITH_THIS_CONTENT') }}</b-button>
              </div>
            </div>
          </div>

          <div v-else class="content-detail px-4">
            <!-- <h4 class="text-center">{{ detail_data.status_selection_name }}</h4> -->
            <!-- <h4 class="text-center">{{ roundRender }}</h4> -->
            <h4 class="text-center">{{ current_rount_name }}</h4>
            <div class="pt-4">
              <b-row>
                <b-col cols="4">{{ $t('INTERVIEW_FORMAT') }} </b-col>
                <b-col v-if="detail_data.type_code === 1" cols="8">
                  {{ $t('GROUP_INTERVIEW') }}</b-col>
                <b-col v-if="detail_data.type_code === 2" cols="8">
                  {{ $t('PRIVATE_INTERVIEW') }}</b-col>
              </b-row>
              <b-row class="pt-3">
                <b-col
                  cols="4"
                  class="my-2"
                >{{ $t('INTERVIEW_CANDIDATE_DATE') }}
                </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div
                    v-for="(item, index) in carlenderOptionShift"
                    :key="`calender-${index}`"
                  >
                    {{ item.timeJaAP }}
                  </div>
                </b-col>
              </b-row>
            </div>
          </div>
          <template v-if="isOff">
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
          </template>
        </div>
      </div>
    </modal-common>

    <!-- decline modal -->
    <modal-common
      :refs="'decline-modal'"
      :size="'md'"
      @reset-modal="resetDecilneModal"
    >
      <template slot="body">
        <div class="content-modal pt-3">
          <div class="text-center">
            <h5 class="font-weight-medium text-danger">
              {{ $t('TITLE.TAB_4.LABEL_MODAL_REASON') }}
            </h5>
          </div>
          <b-form-group
            v-slot="{ ariaDescribedby }"
            :label="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
            class="mt-4 px-4 mb-0"
          >
            <b-form-radio-group
              v-model="declineFormData.fixReasonDecline"
              :options="reasonResultOptions"
              :aria-describedby="ariaDescribedby"
              :name="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
              value-field="key"
              text-field="value"
              stacked
              :class="is_error_checked ? 'is-invalid-type-check' : ''"
              @change="changeTime('checked')"
            />
            <b-form-textarea
              v-if="declineFormData.isShowNote"
              v-model="declineFormData.note"
              class="mt-4"
              :rows="5"
              :class="is_error_note ? ' is-invalid' : ''"
              @input="changeTime('note')"
            />
            <b-form-invalid-feedback :state="!validate_decline">
              <span style="font-size: 18px">{{
                $t('VALIDATE.REQUIRED_SELECT')
              }}</span>
            </b-form-invalid-feedback>
          </b-form-group>

          <div class="text-center">
            <b-button
              class="my-2 mt-5 mx-2"
              variant="secondary"
              @click="handleCancelReason"
            >{{ $t('MODAL_BUTTON_CANCEL') }}</b-button>
            <b-button
              class="my-2 mt-5 mx-2 text-white"
              variant="danger"
              @click="handleDecline"
            >{{ $t('MODAL_BUTTON_DECLINE') }}
            </b-button>
          </div>
        </div>
      </template>
    </modal-common>
  </div>
</template>

<script>
import EventBus from '@/utils/eventBus';
import ModalCommon from '../../../layout/components/ModalCommon/matching.vue';
// import moment from 'moment';
import {
  putConfirmCalender,
  putDeclineInterview,
} from '@/api/modules/matching.js';
import { MakeToast } from '../../../utils/toastMessage';
import { reasonResultOptions } from '@/const/matching.js';

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
      time: null,
      is_error_time: false,

      is_error_checked: false,
      is_error_note: false,
      validate_decline: false,

      detail_data: {},
      current_rount_name: '',
      step: 1,

      declineFormData: {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      },
      reasonResultOptions,
    };
  },

  computed: {
    roundRender() {
      if (this.detail_data.optionSelectRound) {
        return this.detail_data.optionSelectRound.round_next_option
          .round_name_current;
      } else {
        return '';
      }
    },
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
      const listRole = [1, 3, 5];

      return listRole.includes(ROLE);
    },

    minDate() {
      const now = new Date();
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      const minDate = new Date(today);
      minDate.setDate(now.getDate() + 1);
      return minDate;
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
      // console.log('CALENDER_DATA==>', CALENDER_DATA);

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

    carlenderOption() {
      const CALENDER_OPTION = [];
      if (
        this.detail_data.calendarTemporary &&
        this.detail_data.calendarTemporary.length > 0
      ) {
        let idx = 0;
        const LIST =
          this.detail_data.calendarTemporary[0].calendar_interview_convert;
        const len = LIST.length;

        while (idx < len) {
          CALENDER_OPTION.push({
            value: idx + 1,
            // text: LIST[idx].timeJaAP,
            text: LIST[idx].timeJa,
          });

          idx++;
        }
      }
      CALENDER_OPTION.push({
        value: 6,
        text: this.$t('INTERVIEW_REJECT_CANDIDATE'),
      });

      return CALENDER_OPTION;
    },

    carlenderOptionShift() {
      if (
        this.detail_data.calendarTemporary &&
        this.detail_data.calendarTemporary.length > 0
      ) {
        return this.detail_data.calendarTemporary[0].calendar_interview_convert;
      }

      return [];
    },

    renderTimeText() {
      let TEXT = '';
      let idx = 0;
      const len = this.carlenderOption.length;

      while (idx < len) {
        if (this.time === this.carlenderOption[idx].value) {
          TEXT = this.carlenderOption[idx].text;
          break;
        }

        idx++;
      }

      return TEXT;
    },

    rederCalender() {
      const CALENDER_DATA = [];
      if (this.detail_data.calendar && this.detail_data.calendar.length === 0) {
        return CALENDER_DATA;
      } else {
        let idx = this.detail_data.calendar
          ? this.detail_data.calendar.length - 2
          : 0;
        while (idx >= 0) {
          CALENDER_DATA.push(this.detail_data.calendar[idx]);

          idx--;
        }
      }

      return CALENDER_DATA;
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
    EventBus.$on('open-modal-adjusting', () => {
      EventBus.$emit('open-modal', 'adjusting-modal');
    });
  },

  methods: {
    resetModal() {
      this.step = 1;
      this.time = null;
      this.is_error_time = false;
    },

    resetDecilneModal() {
      this.declineFormData = {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      };

      this.is_error_checked = false;
      this.is_error_note = false;
      this.validate_decline = false;
    },

    // handleChangeReasonDecline() {
    //   if (this.declineFormData.fixReasonDecline === 'その他') {
    //     this.declineFormData.isShowNote = true;
    //   } else {
    //     this.declineFormData.isShowNote = false;
    //   }
    // },

    handleCancelReason() {
      EventBus.$emit('close-modal', 'decline-modal');
    },

    changeTime(type) {
      switch (type) {
        case 'time':
          if (!this.time) {
            this.is_error_time = true;
          } else {
            this.is_error_time = false;
          }
          break;
        case 'checked':
          if (this.declineFormData.fixReasonDecline === 'その他') {
            this.declineFormData.isShowNote = true;
          } else {
            this.declineFormData.isShowNote = false;
          }
          if (!this.declineFormData.fixReasonDecline) {
            this.is_error_checked = true;
          } else {
            this.is_error_checked = false;
          }
          break;
        case 'note':
          if (
            this.declineFormData.fixReasonDecline === 'その他' &&
            !this.declineFormData.note
          ) {
            this.is_error_note = true;
          } else {
            this.is_error_note = false;
          }
          break;

        default:
          break;
      }
    },

    checkvalidate() {
      if (!this.time) {
        this.is_error_time = true;
      } else {
        this.is_error_time = false;
      }

      return !this.is_error_time;
    },

    checkvalidateDecline() {
      let check = true;
      if (!this.declineFormData.fixReasonDecline) {
        this.is_error_checked = true;
        check = false;
      }
      if (
        this.declineFormData.fixReasonDecline === 'その他' &&
        !this.declineFormData.note
      ) {
        this.is_error_note = true;
        check = false;
      }

      this.validate_decline = !check;

      return check;
    },

    handleShowDeclineModal() {
      EventBus.$emit('open-modal', 'decline-modal');
    },
    async handleDecline() {
      const VALIDATE = this.checkvalidateDecline();
      if (!VALIDATE) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('WARNING_MESS.NOT_SELECT_DECLINE'),
        });
        return;
      }
      const PARAMS = {
        id: this.detail_data.id,
      };
      if (this.declineFormData.fixReasonDecline === 'その他') {
        PARAMS.note = this.declineFormData.note;
      } else {
        PARAMS.note = this.declineFormData.fixReasonDecline;
      }

      try {
        const response = await putDeclineInterview(PARAMS);
        const { code, message } = response.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: '辞退しました。',
          });
          EventBus.$emit('close-modal', 'decline-modal');
          EventBus.$emit('close-modal', 'adjusting-modal');
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

    async handleConfirmCalender() {
      const PARAMS = {
        id: this.detail_data.id,
        time: this.time,
      };
      try {
        const res = await putConfirmCalender(PARAMS);
        const { code, message } = res.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: this.$t('SENT'),
          });
          EventBus.$emit('close-modal', 'adjusting-modal');
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

    handleNextStep(e) {
      e.preventDefault();
      const VALIDATE = this.checkvalidate();
      if (!VALIDATE) {
        return;
      }
      this.step++;
    },

    handleBack() {
      this.step--;
      if (this.step === 0) {
        EventBus.$emit('close-modal', 'adjusting-modal');
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
