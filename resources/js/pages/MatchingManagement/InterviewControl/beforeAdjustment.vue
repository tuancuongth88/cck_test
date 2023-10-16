<template>
  <modal-common
    :refs="'before-adjustment-modal'"
    :size="'lg'"
    @reset-modal="resetModal"
  >
    <div id="before_adjustment_modal" slot="body">
      <!-- header -->
      <div class="text-center">
        <template v-if="step === 1">
          <h2 class="font-weight-bold">
            {{ detail_data.full_name }} <br>
            {{ detail_data.full_name_ja }}
          </h2>
          <h5>
            {{ detail_data.job_name }} <br>
            <span
              v-if="detail_data.interview_from === 'entry'"
            >{{ $t('ENTRY') }}
            </span>
            <span v-if="detail_data.interview_from === 'offer'">{{
              $t('OFFER')
            }}</span>
          </h5>
        </template>
        <template v-if="step === 2">
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
          <template v-if="typeStatus === 1">
            <div v-for="(item, index) in listCandidate" :key="`hr-${index}`">
              <h2 class="font-weight-bold">
                {{ item.full_name }} <br>
                {{ item.full_name_ja }}
              </h2>
              <!-- <h5>
                {{ detail_data.job_name }} <br>
                {{ $t('ENTRY')}}
              </h5> -->
            </div>
          </template>
          <h5>
            {{ detail_data.job_name }} <br>
            <span
              v-if="detail_data.interview_from === 'entry'"
            >{{ $t('ENTRY') }}
            </span>
            <span v-if="detail_data.interview_from === 'offer'">{{
              $t('OFFER')
            }}</span>
          </h5>
        </template>
      </div>
      <!-- body -->
      <div v-if="isOff" class="content-detail px-4">
        <h6 class="text-center">{{ detail_data.updating_date_ja }}</h6>
        <h3 class="text-center text-red p-4">
          {{ detail_data.status_selection_name }}
        </h3>
        <div v-if="renderOffReason" class="text-center">
          <h6>{{ renderOffReason }}</h6>
        </div>
      </div>

      <div v-if="roleCheck" class="content-detail">
        <template v-if="step === 1">
          <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
            <!-- <h4 class="text-center">一次面接</h4> -->
            <!-- <h4 class="text-center">{{ detail_data.status_selection_name }}</h4> -->
            <h4 class="text-center">{{ current_rount_name }}</h4>
            <b-form-group
              v-slot="{ ariaDescribedby }"
              :label="$t('INTERVIEW_FORMAT')"
            >
              <b-form-radio
                v-model="typeStatus"
                :aria-describedby="ariaDescribedby"
                name="some-radios1"
                :value="1"
                :disabled="
                  listCandidate.length === 0 ||
                    detail_data.status_selection !== 1 ||
                    isOff
                "
                :class="checkErrorStatus ? 'is-invalid-type-check' : ''"
                @change="changeAdjustDate('type_status')"
              >
                <span dusk="radio-type-check-1">
                  {{ $t('GROUP_INTERVIEW') }}</span>
              </b-form-radio>
              <b-form-radio
                v-model="typeStatus"
                :aria-describedby="ariaDescribedby"
                name="some-radios1"
                :value="2"
                :class="is_error_type_status ? 'is-invalid-type-check' : ''"
                :disabled="[3, 4].includes(detail_data.status_selection)"
                @change="changeAdjustDate('type_status')"
              >
                <span dusk="radio-type-check-2">
                  {{ $t('PRIVATE_INTERVIEW') }}</span>
              </b-form-radio>
            </b-form-group>
            <!-- <b-form-group label="面接形式">
              <b-form-radio-group
                v-model="typeStatus"
                name="面接形式"
                stacked
              >
                <template v-for="option in typeStatusOptions">
                  <b-form-radio
                    :key="option.key"
                    :value="option.key"
                    :disabled="(listCandidate.length === 0 || detail_data.status_selection !== 1) && option.key === 1 || isOff"
                    :class="is_error_type_status ? 'is-invalid-type-check' : ''"
                    @change="changeAdjustDate('type_status')"
                  >
                    {{ option.value }}
                  </b-form-radio>
                </template>
              </b-form-radio-group>
            </b-form-group> -->
          </b-form>
          <h6 v-if="typeStatus === 1" class="text-red">
            この人材は他の{{ listCandidate.length }}名の人材
            <span
              v-for="(item, index) in listCandidate"
              :key="`hr-${index}`"
            >{{ item.full_name_ja }},
            </span>
            とエントリーしています。
            集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
          </h6>
          <h6
            v-if="
              [5, 6, 7, 8, 9].includes(detail_data.status_selection) &&
                detail_data.interview_from === 'entry'
            "
            class="text-red"
          >
            <!-- ※ 二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。 -->
            ※ 二次面接以降は個別面接のみとなります。
          </h6>
          <div class="d-lex flex-column align-content-center bg-white">
            <b-table
              id="table-calendar-temp"
              class="bg-white"
              bordered
              :fields="fieldsModal"
              :items="itemsModal"
            >
              <template #cell(control)="data">
                <b-icon v-if="data.item.id > 1" icon="dash-circle" scale="1.25" @click="handleRemoveCandidate(data.item.id)" />
              </template>
              <template #cell(no)="data">
                <span>{{ $t('INTERVIEW_CANDIDATE') }}{{ data.item.id }}</span>
              </template>
              <template #cell(candidate_date)="data">
                <b-form-datepicker
                  v-model="data.item.date"
                  dusk="timeTemp"
                  locale="ja"
                  placeholder=""
                  :date-format-options="{ year: 'numeric', month: 'short', day: '2-digit', weekday: 'short' }"
                  :class="data.item.is_error_date ? ' is-invalid' : ''"
                  :min="minDate"
                  :disabled="isOff"
                  @context="changeAdjustDate('date', data.item)"
                />
              </template>

              <template #cell(starting_time)="data">
                <div class="d-flex">
                  <b-form-select
                    v-model="data.item.start_time"
                    :options="hourOptions"
                    :class="data.item.is_error_start_time ? ' is-invalid' : ''"
                    class="mr-1 ml-2"
                    :disabled="isOff"
                    @change="changeAdjustDate('start_time', data.item)"
                  />
                </div>
              </template>
              <template #cell(expected_time)="data">
                <div class="d-flex align-items-center">
                  <b-form-select
                    v-model="data.item.expected_time"
                    :options="minuteOptions"
                    :class="data.item.is_error_expected_time ? ' is-invalid' : ''"
                    class="mx-2"
                    :disabled="isOff"
                    @change="changeAdjustDate('expected_time', data.item)"
                  />
                  <span>分</span>
                </div>
                <!-- <span v-if="data.item.is_error_expected_time" class="is-invalid">{{ $t('VALIDATE.REQUIRED_SELECT') }}</span> -->
              </template>
            </b-table>
            <b-button class="w-100 plus-button" :disabled="itemsModal.length === 5 || isOff" @click="handleAddCandidate">
              <i class="fas fa-plus-circle" />
            </b-button>
          </div>
          <div class="d-flex justify-content-between">
            <div>
              <b-form-invalid-feedback :state="validate_adjust_date">
                <span style="font-size: 18px">{{
                  $t('VALIDATE.REQUIRED_SELECT')
                }}</span>
              </b-form-invalid-feedback>
            </div>
            <div>
              <span>{{ $t('ALL_TIMES_ARE_JAPAN_TIME') }}</span>
            </div>
          </div>
        </template>

        <template v-if="step === 2">
          <!-- <h4 class="text-center">{{ detail_data.status_selection_name }}</h4> -->
          <h4 class="text-center">{{ current_rount_name }}</h4>
          <b-row class="pt-3">
            <b-col cols="4">{{ $t('INTERVIEW_FORMAT') }} </b-col>
            <b-col v-if="typeStatus === 1" cols="8">
              {{ $t('GROUP_INTERVIEW') }}</b-col>
            <b-col v-if="typeStatus === 2" cols="8">
              {{ $t('PRIVATE_INTERVIEW') }}</b-col>
          </b-row>
          <b-row>
            <b-col
              cols="4"
              class="my-2"
            >{{ $t('INTERVIEW_CANDIDATE_DATE') }}
            </b-col>
            <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
              <div
                v-for="(item, index) in items_modal_confirm"
                :key="`items_modal_confirm-${index}`"
              >
                {{ item }}
              </div>
              <!-- <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div> -->
            </b-col>
          </b-row>
        </template>

        <!-- Button -->
        <div class="d-flex justify-content-center mt-5">
          <template v-if="step === 1">
            <b-button
              id="btn-handle-back"
              variant="secondary"
              class="mx-1"
              :disabled="isOff"
            >
              <i class="fas fa-solid fa-caret-left fs-16" />
              <span class="mx-1" @click="handleBack">{{
                $t('RETURN_TO_PREVIOUS_SCREEN')
              }}</span>
            </b-button>
            <b-button
              id="btn-handle-next-step"
              variant="warning"
              class="text-white mx-1"
              :disabled="isOff"
              @click="handleNextStep($event)"
            >
              {{ $t('GO_CONFIRM') }}</b-button>
          </template>
          <template v-if="step === 2">
            <b-button variant="secondary" class="mx-1">
              <i class="fas fa-solid fa-caret-left fs-16" />
              <span class="mx-1" @click="handleBack">{{
                $t('RETURN_TO_PREVIOUS_SCREEN')
              }}</span>
            </b-button>
            <b-button
              variant="warning"
              class="text-white mx-1"
              @click="handlePostCalender"
            >
              {{ $t('SEND_WITH_THIS_CONTENT') }}
            </b-button>
          </template>
        </div>
      </div>
      <div v-else class="content-detail">
        <template v-if="step === 1">
          <b-form @reset="onReset($event)">
            <!-- <h4 class="text-center">一次面接</h4> -->
            <!-- <h4 class="text-center">{{ detail_data.status_selection_name }}</h4> -->
            <h4 class="text-center">{{ current_rount_name }}</h4>
          </b-form>
        </template>
      </div>
      <template v-if="isOff || !roleCheck">
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
  </modal-common>
</template>

<script>
import EventBus from '@/utils/eventBus';
import ModalCommon from '../../../layout/components/ModalCommon/matching.vue';
import moment from 'moment';
// import 'moment/locale/ja';
import { putSetupCalender } from '@/api/modules/matching.js';
import { MakeToast } from '../../../utils/toastMessage';

export default {
  name: 'BeforeAdjustMent',
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
      typeStatus: 0,
      is_error_type_status: null,
      typeStatusOptions: [
        // { key: 1, value: '集団面接' },
        { key: 1, value: this.$t('INTERVIEW_GROUP') },
        // { key: 2, value: '個人面接' },
        { key: 2, value: this.$t('INTERVIEW_PRIVATE') },
      ],

      validate_adjust_date: true,
      itemsModal: [
        {
          id: 1,
          date: '',
          start_time: '',
          expected_time: '',
          is_error_date: false,
          is_error_start_time: false,
          is_error_expected_time: false,
        },
      ],
      items_modal_confirm: [],
      fieldsModal: [
        {
          key: 'control',
          sortable: false,
          label: '',
          class: 'bg-header',
          thStyle: {
            width: '10%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'no',
          sortable: false,
          label: 'No.',
          class: 'bg-header',
          thStyle: {
            width: '15%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'candidate_date',
          sortable: false,
          label: '候補日',
          class: 'bg-header',
          thStyle: {
            width: '35%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
          tdStyle: 'padding-left: 5px',
        },
        {
          key: 'starting_time',
          sortable: false,
          label: '開始時間',
          class: 'bg-header',
          thStyle: {
            width: '20%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'expected_time',
          sortable: false,
          label: '想定所要時間',
          class: 'bg-header',
          thStyle: {
            width: '20%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],

      clockOptions: [
        {
          value: 'AM',
          text: '午前',
        },
        {
          value: 'PM',
          text: '午後',
        },
      ],
      hourOptions: [
        '8:00',
        '8:30',
        '9:00',
        '9:30',
        '10:00',
        '10:30',
        '11:00',
        '11:30',
        '12:00',
        '12:30',
        '13:00',
        '13:30',
        '14:00',
        '14:30',
        '15:00',
        '15:30',
        '16:00',
        '16:30',
        '17:00',
        '17:30',
        '18:00',
        '18:30',
        '19:00',
        '19:30',
        '20:00',
        '20:30',
        '21:00',
        '21:30',
        '22:00',
      ],
      minuteOptions: ['30', '60', '90'],

      detail_data: {},
      current_rount_name: '',
      step: 1,
    };
  },

  computed: {
    checkErrorStatus() {
      let check = false;
      if (
        this.is_error_type_status &&
        this.listCandidate.length > 0 &&
        this.detail_data.status_selection === 1 &&
        !this.isOff
      ) {
        check = true;
      }
      return check;
    },
    isOff() {
      const status_off = [3, 4];
      if (status_off.includes(this.detail_data.status_selection)) {
        return true;
      }
      return false;
    },

    listCandidate() {
      return this.detail_data.candidateList || [];
    },

    renderOffReason() {
      // let CALENDER_DATA = [];
      // if (this.detail_data.calendar) {
      //   if (!(this.detail_data.calendar.length === 0)) {
      //     const idx = this.detail_data.calendar ? (this.detail_data.calendar.length - 1) : 0;
      //     CALENDER_DATA = this.detail_data.calendar[idx];
      //   }
      // }

      // let REASON = '';
      // if (CALENDER_DATA) {
      //   if (CALENDER_DATA.calendarInterview && (CALENDER_DATA.calendarInterview.length > 0)) {
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

    minDate() {
      const now = new Date();
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      const minDate = new Date(today);
      minDate.setDate(now.getDate());
      return minDate;
    },

    rederCalender() {
      const CALENDER_DATA = [];
      if (this.detail_data.calendar && this.detail_data.calendar.length === 0) {
        return CALENDER_DATA;
      } else {
        if (this.detail_data.calendar) {
          let idx = this.detail_data.calendar.length - 1;
          while (idx >= 0) {
            CALENDER_DATA.push(this.detail_data.calendar[idx]);

            idx--;
          }
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
          this.current_rount_name = props_data.optionSelectRound.round_current_option.round_name_current;
          // this.current_rount_name =
          //   props_data.optionSelectRound.round_next_option.round_name_current;
        }
      },
      deep: true,
    },
  },

  created() {
    EventBus.$on('open-modal-before-adjustment', () => {
      // EventBus.$emit('open-modal', 'document-passing_adjust');
      EventBus.$emit('open-modal', 'before-adjustment-modal');
    });
  },

  methods: {
    resetModal() {
      this.step = 1;
      this.typeStatus = 0;
      this.is_error_type_status = null;

      this.validate_adjust_date = true;
      this.itemsModal = [
        {
          id: 1,
          date: '',
          start_time: '',
          expected_time: '',
          is_error_date: false,
          is_error_start_time: false,
          is_error_expected_time: false,
        },
      ];
      this.items_modal_confirm = [];
    },

    handleRemoveCandidate(id) {
      const NEW_ITEMS_MODAL = [];
      this.itemsModal.map(item => {
        if (item.id !== id) {
          if (item.id > id) {
            NEW_ITEMS_MODAL.push({
              id: item.id - 1,
              date: item.date,
              start_time: item.start_time,
              expected_time: item.expected_time,
              is_error_date: item.is_error_date,
              is_error_start_time: item.is_error_start_time,
              is_error_expected_time: item.is_error_expected_time,
            });
          } else {
            NEW_ITEMS_MODAL.push(item);
          }
        }
      });
      this.itemsModal = NEW_ITEMS_MODAL;
    },

    handleAddCandidate() {
      const len = this.itemsModal.length;
      this.itemsModal.push({
        id: len + 1,
        date: this.itemsModal[len - 1].date,
        start_time: this.itemsModal[len - 1].start_time,
        expected_time: this.itemsModal[len - 1].expected_time,
        is_error_date: false,
        is_error_start_time: false,
        is_error_expected_time: false,
      });
    },

    async handlePostCalender() {
      const CALENDER = [];
      const NEED_CONVERT_TIME = [
        '8:00',
        '8:30',
        '9:00',
        '9:30',
      ];

      this.itemsModal.map((item) => {
        CALENDER.push({
          date: item.date,
          start_time: NEED_CONVERT_TIME.includes(item.start_time) ? `0${item.start_time}` : item.start_time,
          start_time_at: item.start_time_at,
          expected_time: item.expected_time,
        });
      });
      const PARAMS = {
        id: this.detail_data.id,
        calender: {
          interview_type: this.typeStatus,
          times: CALENDER,
        },
      };
      try {
        const res = await putSetupCalender(PARAMS);
        const { code, message } = res.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: this.$t('SENT'),
          });
          EventBus.$emit('close-modal', 'before-adjustment-modal');
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
      let VALIDAT_TIME = null;

      if (this.step === 1) {
        VALIDAT_TIME = this.checkValidateAdjustDate();
        if (!VALIDAT_TIME) {
          return;
        }
        this.convertAdjustDate();
        this.step++;
      }
    },

    handleBack() {
      this.step--;
      if (this.step === 0) {
        EventBus.$emit('close-modal', 'before-adjustment-modal');
        this.step = 1;
      }
    },

    checkValidateAdjustDate() {
      let idx = 0;
      let check = true;
      const len = this.itemsModal.length;
      while (idx < len) {
        if (this.itemsModal[idx].date ||
          this.itemsModal[idx].start_time ||
          this.itemsModal[idx].expected_time ||
          this.itemsModal[idx].id === 1
        ) {
          if (!this.itemsModal[idx].date) {
            this.itemsModal[idx].is_error_date = true;
            check = false;
          } else {
            this.itemsModal[idx].is_error_date = false;
          }
          if (!this.itemsModal[idx].start_time) {
            this.itemsModal[idx].is_error_start_time = true;
            check = false;
          } else {
            this.itemsModal[idx].is_error_start_time = false;
          }
          if (!this.itemsModal[idx].expected_time) {
            this.itemsModal[idx].is_error_expected_time = true;
            check = false;
          } else {
            this.itemsModal[idx].is_error_expected_time = false;
          }
        }

        idx++;
      }

      if (!this.typeStatus) {
        this.is_error_type_status = true;
        check = false;
      } else {
        this.is_error_type_status = false;
      }

      this.validate_adjust_date = check;
      return check;
    },

    changeAdjustDate(type, item = null) {
      if (type === 'type_status') {
        this.is_error_type_status = false;
      }
      if (!item) {
        return;
      }
      if (type === 'date') {
        this.itemsModal[item.id - 1].is_error_date = false;
      }
      if (type === 'start_time') {
        this.itemsModal[item.id - 1].is_error_start_time = false;
      }
      if (type === 'start_time_at') {
        this.itemsModal[item.id - 1].is_error_start_time_at = false;
      }
      if (type === 'expected_time') {
        this.itemsModal[item.id - 1].is_error_expected_time = false;
      }
    },
    convertAdjustDate() {
      this.items_modal_confirm = [];
      this.itemsModal.map(item => {
        const DATE_MOMENT = item.date;
        const START_MOMENT = item.start_time;
        const EXPECTED_MOMENT = item.expected_time;

        const TIME_CONFIRM = this.handleConvertInterviewDateJa(DATE_MOMENT, START_MOMENT, EXPECTED_MOMENT);
        this.items_modal_confirm.push(TIME_CONFIRM);
      });
      // let idx = 0;
      // while (idx < 5) {
      //   const DATE_MOMENT = this.itemsModal[idx].date;
      //   const START_MOMENT = this.itemsModal[idx].start_time;
      //   const EXPECTED_MOMENT = this.itemsModal[idx].expected_time;
      //   const AT_MOMENT = this.itemsModal[idx].start_time_at;

      //   const TIME_CONFIRM = this.handleConvertInterviewDateJa(DATE_MOMENT, START_MOMENT, EXPECTED_MOMENT, AT_MOMENT);
      //   this.items_modal_confirm.push(TIME_CONFIRM);

      //   idx++;
      // }
    },

    handleConvertInterviewDateJa(date, start_time, expected_time) {
      if (date && start_time && expected_time) {
        const DATE = moment(date).locale('ja').format('YYYY年M月D日 (ddd)');

        const START_MOMENT = moment(start_time, 'HH:mm').locale('ja');
        const START_STRING = START_MOMENT.format('H:mm');

        const startTime = moment(start_time, 'HH:mm');
        const END_HOUR = startTime.add(Number(expected_time), 'minutes').locale('ja');
        const ENDE_STRING = END_HOUR.format('H:mm');

        const result = `${DATE} ${START_STRING}~${ENDE_STRING}`;

        return result;
      }
    },

    // handleConvertInterviewDateJa(date, start_time, expected_time, at) {
    //   const DATE = moment(date).locale('ja').format('YYYY年M月D日 (ddd)');

    //   const START_HOUR = `${start_time} ${at}`;
    //   const START_MOMENT = moment(START_HOUR, 'h:mm A').locale('ja');
    //   const minutes_start = START_MOMENT.minutes();
    //   const START_STRING = START_MOMENT.format(minutes_start === 0 ? 'Ah時' : 'Ah時mm分');
    //   // const START_STRING = moment(START_MOMENT).format(START_MOMENT.minutes() === 0 ? 'Ah時' : 'Ah:mm時');

    //   const startTime = moment(START_HOUR, 'h:mm A');
    //   const END_HOUR = startTime.add(Number(expected_time), 'minutes').locale('ja');
    //   const minutes_end = END_HOUR.minutes();
    //   const ENDE_STRING = END_HOUR.format(minutes_end === 0 ? 'Ah時' : 'Ah時mm分');
    //   // const ENDE_STRING = moment(END_HOUR).format(END_HOUR.minutes() === 0 ? 'Ah時' : 'Ah:mm時');

    //   const result = `${DATE} ${START_STRING}~${ENDE_STRING}`;

    //   return result;
    // },
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

.plus-button {
  max-height: 30px;
  border-radius: 5px;
  padding: 0;
}
</style>
