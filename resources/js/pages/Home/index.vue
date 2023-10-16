<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
    :style="'min-height: 100vh; height: 100%'"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>

    <div class="display-user-management-list">
      <!-- Table 1 msg matching related / Other -->
      <b-row class="mb-4">
        <b-col cols="12">
          <b-card class="d-flex flex-column">
            <h4 class="card-title">
              {{ $t('TITLE.NEW_MSG') }}
            </h4>
            <!-- DOT Read all -->
            <div
              :class="
                [ROLE.TYPE_SUPER_ADMIN].includes(permissionCheck)
                  ? 'unread-all-head'
                  : 'd-none'
              "
            >
              <div class="unread-all-tab">
                <span>{{ $t('TAB.MATCHING_RELATION') }}</span>
                <span
                  v-if="!is_read_all_new_msg_matching_related"
                  class="unread-all-dot"
                />
              </div>
            </div>

            <!-- Admin 1 Tab -->
            <div v-if="[ROLE.TYPE_SUPER_ADMIN].includes(permissionCheck)">
              <b-tabs class="tab-active">
                <b-tab id="read-all-msg" active>
                  <template #title>
                    <span>{{ $t('TAB.MATCHING_RELATION') }}</span>
                  </template>

                  <b-table
                    id="new-msg"
                    :items="table_new_msg_matching_related_items"
                    :fields="table_new_msg_matching_related_fields"
                    bordered
                    hover
                    :current-page="
                      table_new_msg_matching_related_pagination.current_page
                    "
                  >
                    <template #cell(title)="data">
                      <div class="table-cell">
                        <span
                          v-if="!data.item.read_at"
                          class="dot-unread-item"
                        />
                        <span v-if="data.item.read_at" class="dot-hidden" />
                        <b-link
                          @click="handleNavigateToMessageDetail(data.item.id)"
                        >
                          <span class="card-link">{{ data.item.title }}</span>
                        </b-link>
                      </div>
                    </template>

                    <template #cell(reception_time)="data">
                      <div
                        class="w-100 h-100 d-flex justify-center align-center"
                      >
                        <span>{{ data.item.reception_time }}</span>
                      </div>
                    </template>
                  </b-table>

                  <b-link
                    v-if="
                      table_new_msg_matching_related_pagination.total_rows > 5
                    "
                    id="show-more-msg"
                    dusk="show-more-msg"
                    style="color: #5141a4"
                    class="d-flex justify-content-end mt-1"
                    @click="showMore('new-msg', 'matching-relation')"
                  >
                    <span>{{ $t('TITLE.SHOW_MORE_MESSAGES') }}</span>
                  </b-link>
                </b-tab>
              </b-tabs>
            </div>
            <!-- None Admin Have Tab other -->
            <!-- DOT Read all -->
            <div
              :class="
                [
                  ROLE.TYPE_COMPANY_ADMIN,
                  ROLE.TYPE_HR_MANAGER,
                  ROLE.TYPE_COMPANY,
                  ROLE.TYPE_HR,
                ].includes(permissionCheck)
                  ? 'unread-all-head'
                  : 'd-none'
              "
            >
              <div class="unread-all-tab">
                <span>{{ $t('TAB.MATCHING_RELATION') }}</span>
                <span
                  v-if="!is_read_all_new_msg_matching_related"
                  class="unread-all-dot"
                />
              </div>
              <div class="unread-all-tab">
                <span>{{ $t('TITLE.OTHERS') }}</span>
                <span
                  v-if="!is_read_all_distribution_msg"
                  class="unread-all-dot"
                />
              </div>
            </div>

            <b-tabs
              v-if="
                [
                  ROLE.TYPE_COMPANY_ADMIN,
                  ROLE.TYPE_HR_MANAGER,
                  ROLE.TYPE_COMPANY,
                  ROLE.TYPE_HR,
                ].includes(permissionCheck)
              "
              class="tab-active"
            >
              <!-- Tab msg matching related -->
              <b-tab active>
                <template #title>
                  <span>{{ $t('TAB.MATCHING_RELATION') }}</span>
                </template>

                <b-table
                  id="new-msg"
                  :items="table_new_msg_matching_related_items"
                  :fields="table_new_msg_matching_related_fields"
                  hover
                  bordered
                  :current-page="
                    table_new_msg_matching_related_pagination.current_page
                  "
                >
                  <template #cell(title)="data">
                    <div class="table-cell">
                      <span v-if="!data.item.read_at" class="dot-unread-item" />
                      <span v-if="data.item.read_at" class="dot-hidden" />
                      <b-link
                        class="card-link text-link"
                        @click="handleNavigateToMessageDetail(data.item.id)"
                      >
                        <span>{{ data.item.title }}</span>
                      </b-link>
                    </div>
                  </template>

                  <template #cell(reception_time)="data">
                    <div class="w-100 h-100 d-flex justify-center align-center">
                      <span>{{ data.item.reception_time }}</span>
                    </div>
                  </template>
                </b-table>

                <b-link
                  v-if="
                    table_new_msg_matching_related_pagination.total_rows > 5
                  "
                  style="color: #5141a4"
                  class="d-flex justify-content-end mt-1"
                  @click="showMore('new-msg', 'matching-relation')"
                >
                  <span>{{ $t('TITLE.SHOW_MORE_MESSAGES') }}</span>
                </b-link>
              </b-tab>

              <!-- Tab その他  Other (Not admin)  -->
              <b-tab>
                <template #title>
                  <span>{{ $t('TITLE.OTHERS') }}</span>
                  <!-- <span v-if="!is_read_all_distribution_msg" class="dot" /> -->
                </template>
                <b-table
                  id="new-msg-other"
                  :items="table_distribution_msg_items"
                  :fields="table_distribution_msg_fields"
                  hover
                  bordered
                >
                  <template #cell(title)="data">
                    <div class="table-cell">
                      <span v-if="!data.item.read_at" class="dot-unread-item" />
                      <span v-if="data.item.read_at" class="dot-hidden" />

                      <b-link
                        class="card-link text-link"
                        @click="handleNavigateToMessageDetail(data.item.id)"
                      >
                        <span>{{ data.item.title }}</span>
                      </b-link>
                    </div>
                  </template>

                  <template #cell(reception_time)="data">
                    <div class="w-100 h-100 d-flex justify-center align-center">
                      <span>{{ data.item.reception_time }}</span>
                    </div>
                  </template>
                </b-table>

                <b-link
                  v-if="table_distribution_msg_pagination.total_rows > 5"
                  style="color: #5141a4"
                  class="d-flex justify-content-end mt-1"
                  @click="showMore('new-msg', 'others')"
                >
                  <span>{{ $t('TITLE.SHOW_MORE_MESSAGES') }}</span>
                </b-link>
              </b-tab>
            </b-tabs>
          </b-card>
        </b-col>
      </b-row>

      <!-- Table 2: distribution msg (Only Role 1 - Admin) -->
      <b-row
        v-if="[ROLE.TYPE_SUPER_ADMIN].includes(permissionCheck)"
        class="mb-4"
      >
        <b-col cols="12">
          <b-card>
            <div class="d-flex justify-content-between mb-1">
              <h4 class="card-title mb-0">
                {{ $t('HOME_MANAGEMENT.DISTRIBUTION_MSG') }}
              </h4>

              <b-button
                class="btn_save--custom"
                dusk="create_msg"
                @click="handleNavigateToDistributionScreen()"
              >
                <span>{{ $t('HOME_MANAGEMENT.CREATE_MESSAGE') }}</span>
              </b-button>
            </div>

            <!-- DOT Read all -->
            <div
              :class="
                [ROLE.TYPE_SUPER_ADMIN].includes(permissionCheck)
                  ? 'unread-all-head'
                  : 'd-none'
              "
            >
              <div class="unread-all-tab">
                <span>{{ $t('TITLE.MSG') }}</span>
                <span
                  v-if="!is_read_all_distribution_msg"
                  class="unread-all-dot"
                />
              </div>
            </div>

            <b-tabs class="tab-active">
              <b-tab id="read-all-msg" active>
                <template #title>
                  <span>{{ $t('TITLE.MSG') }}</span>
                  <!-- <span v-if="!is_read_all_distribution_msg" class="dot" /> -->
                </template>

                <b-table
                  id="distribution-msg-table"
                  :items="table_distribution_msg_items"
                  :fields="table_distribution_msg_fields"
                  :hover="true"
                  bordered
                  :current-page="table_distribution_msg_pagination.current_page"
                >
                  <template #cell(title)="data">
                    <div class="table-cell">
                      <span v-if="!data.item.read_at" class="dot-unread-item" />
                      <span v-if="data.item.read_at" class="dot-hidden" />
                      <b-link
                        class="card-link text-link"
                        @click="handleNavigateToMessageDetail(data.item.id)"
                      >
                        <span>{{ data.item.title }}</span>
                      </b-link>
                    </div>
                  </template>

                  <template #cell(reception_time)="data">
                    <div class="w-100 h-100 d-flex justify-center align-center">
                      <span>{{ data.item.reception_time }}</span>
                    </div>
                  </template>
                </b-table>

                <b-link
                  v-if="table_distribution_msg_pagination.total_rows > 5"
                  dusk="show-more-msg"
                  style="color: #5141a4"
                  class="d-flex justify-content-end mt-1"
                  @click="showMore('distribution-msg')"
                >
                  <span>{{ $t('TITLE.SHOW_MORE_MESSAGES') }}</span>
                </b-link>
              </b-tab>
            </b-tabs>
          </b-card>
        </b-col>
      </b-row>

      <!-- Table 3 進行中の案件 On-going Job -->
      <b-row id="dusk_on_going_job" class="mb-4">
        <b-col cols="12">
          <b-card>
            <h4 class="card-title">
              {{ $t('HOME_MANAGEMENT.ON_GOING_JOB') }}
            </h4>
            <b-table
              id="on-going-job-table"
              :items="table_on_going_job_items"
              :fields="table_on_going_job_fields"
              :hover="true"
              bordered
              responsive
              class="table-scroll"
              :current-page="table_on_going_job_pagination.current_page"
            >
              <template #empty="">
                <div class="w-100 d-flex justify-center align-center">
                  <span>{{ $t('NOT_DATA') }}</span>
                </div>
              </template>

              <!-- 1 日時 Date -->
              <template #cell(date)="row">
                <div
                  class="d-flex justify-center align-center"
                  :style="`width: calc( ${on_going_job_width_col.date}px - 1.5rem )`"
                >
                  <span>{{ row.item.date }}</span>
                </div>
              </template>

              <!-- 2 種類 Occupation -->
              <!-- <template #cell(occupation)="row">
                <div class="w-100 justify-space-between flex-column">
                  <div class="w-100 justify-space-between flex-column">
                    <div class="text-overflow-ellipsis">
                      <span>{{ row.item.occupation_ja }}</span>
                    </div>
                  </div>
                </div>
              </template> -->

              <!-- 3 氏名 Name -->
              <template #cell(name)="row">
                <div class="w-100 justify-flex-start flex-column">
                  <b-link
                    :to="`hr/detail/${row.item.id_hrs}`"
                    class="w-100 justify-space-between flex-column text-dark"
                  >
                    <div
                      :title="row.item.name"
                      class="one-line-paragraph"
                      :style="`width: calc( ${on_going_job_width_col.name}px - 1.5rem )`"
                    >
                      {{ row.item.name }}
                    </div>
                    <div
                      :title="row.item.name_ja"
                      class="one-line-paragraph"
                      :style="`width: calc( ${on_going_job_width_col.name}px - 1.5rem )`"
                    >
                      {{ row.item.name_ja }}
                    </div>
                  </b-link>
                </div>
              </template>
              <!-- 4 求人名 job name -->
              <template #cell(job_name)="row">
                <div
                  class="d-flex justify-space-between flex-column w-fit-content"
                >
                  <b-link
                    :to="`job/detail/${row.item.id_job}`"
                    class="d-flex justify-flex-start align-center text-dark"
                    :style="`width: calc( ${on_going_job_width_col.job_name}px - 1.5rem )`"
                  >
                    <div class="one-line-paragraph" :title="row.item.job_name">
                      {{ row.item.job_name }}
                    </div>
                  </b-link>
                </div>
              </template>
              <!-- 5 企業名 company name -->
              <template #cell(company_name)="row">
                <div
                  :title="row.item.company_name"
                  class="one-line-paragraph"
                  :style="`width: calc( ${on_going_job_width_col.company_name}px - 1.5rem )`"
                >
                  {{ row.item.company_name }}
                </div>
              </template>
              <!-- 6 詳細 Detail -->
              <template #cell(detail)="detail">
                <button
                  class="btn-go-detail"
                  :style="`width: calc( ${on_going_job_width_col.detail}px - 1.5rem )`"
                  @click="handleNavigateToDetailScreen(detail.item.to_link)"
                >
                  <i class="fas fa-eye icon-detail" />
                </button>
              </template>
            </b-table>

            <b-link
              v-if="table_on_going_job_pagination.total_rows > 5"
              id="view-more-ongoing-projects"
              style="color: #5141a4"
              class="d-flex justify-content-end mt-1"
              dusk="show_more_on_going_job"
              @click="showMore('on-going-job')"
            >
              <span>{{ $t('TITLE.SHOW_MORE_MESSAGES_ON_GOING_JOB') }}</span>
            </b-link>
          </b-card>
        </b-col>
      </b-row>
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import {
  listNoti,
  listDistribution,
  listGoingJob,
  getUnread,
} from '@/api/user.js';

import moment from 'moment';
import ROLE from '@/const/role.js';

export default {
  name: 'Home',
  data() {
    return {
      ROLE: ROLE,

      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      // -------------------- TABLE 1 ---------------------
      is_read_all_new_msg_matching_related: true,

      table_new_msg_matching_related_fields: [
        {
          key: 'title',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.TITLE'),
          class: 'title',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
        {
          key: 'reception_time',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.RECEPTION_TIME'),
          class: 'reception_time',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
      ],

      table_new_msg_matching_related_items: [],

      table_new_msg_matching_related_pagination: {
        current_page: 1,
        total_rows: null,
        per_page: 5,
      },

      // -------------------- TABLE 2 ---------------------
      is_read_all_distribution_msg: true,

      table_distribution_msg_fields: [
        {
          key: 'title',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.TITLE'),
          class: 'title',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
        {
          key: 'reception_time',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.RECEPTION_TIME'),
          class: 'reception_time',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
      ],

      table_distribution_msg_items: [],

      table_distribution_msg_pagination: {
        current_page: 1,
        total_rows: null,
        per_page: 5,
      },

      // -------------------- TABLE 3 ---------------------
      on_going_job_width_col: {
        date: '152',
        name: '208',
        job_name: '316',
        company_name: '288',
        detail: '76',
      },
      table_on_going_job_fields: [
        {
          key: 'date',
          sortable: false,
          label: '日時',
          class: 'date',
          thStyle: {
            width: '152px',
            // width: '18%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
        // {
        //   key: 'occupation',
        //   sortable: false,
        //   label: '種類',
        //   class: 'occupation',
        //   thStyle: {
        //     width: '10%',
        //     textAlign: 'center',
        //     backgroundColor: '#F0ECFF',
        //     color: '#69609C',
        //     fontSize: '14px',
        //   },
        // },
        {
          key: 'name',
          sortable: false,
          label: '氏名',
          class: 'name',
          thStyle: {
            // width: '20%',
            width: '208px',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
        {
          key: 'job_name',
          sortable: false,
          label: '求人名  ',
          class: 'job_name',
          thStyle: {
            // width: '20%',
            width: '316px',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
        {
          key: 'company_name',
          sortable: false,
          label: '企業名',
          class: 'company_name',
          thStyle: {
            // width: '25%',
            width: '288px',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
        },
        {
          key: 'detail',
          sortable: false,
          label: this.$t('BUTTON.DETAIL'),
          class: 'detail',
          thStyle: {
            // width: '7%',
            width: '76px',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
            fontSize: '14px',
          },
          tdClass: 'text-center',
        },
      ],
      table_on_going_job_items: [],

      table_on_going_job_pagination: {
        current_page: 1,
        total_rows: null,
        per_page: 5,
      },
    };
  },
  computed: {
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },
  created() {
    this.handleInitialComponentData();
  },
  methods: {
    async handleInitialComponentData() {
      this.overlay.show = true;

      await this.handleGetListNotification();
      await this.handleGetListDistributionMSG();
      await this.handleGetListGoingJob();

      this.overlay.show = false;
    },
    async handleGetListNotification() {
      const params = {
        page: this.table_new_msg_matching_related_pagination.current_page,
        per_page: this.table_new_msg_matching_related_pagination.per_page,
      };

      try {
        const response = await listNoti(params);

        const { code, message } = response['data'];

        if (code === 200) {
          const result = response['data']['data']['result'];
          const total_records =
            response['data']['data']['pagination']['total_records'] || 0;
          const responseUnreadNum = await getUnread();

          const codeUnreadNum = responseUnreadNum['data']['code'];

          if (codeUnreadNum === 200) {
            const mesNotificationNum =
              responseUnreadNum['data']['data']['mesNotification'];

            if (mesNotificationNum > 0) {
              this.is_read_all_new_msg_matching_related = false;
            } else {
              this.is_read_all_new_msg_matching_related = true;
            }
          }

          this.table_new_msg_matching_related_pagination.total_rows =
            total_records;

          this.table_new_msg_matching_related_items = [];

          result.map((item) => {
            const itemDataParse = JSON.parse(item.data);

            const date = new Date(item.created_at * 1000);
            this.table_new_msg_matching_related_items.push({
              id: item.id,
              read_at: item.read_at ? item.read_at : '',
              title: itemDataParse.subject ? itemDataParse.subject : '',
              reception_time: moment(date).format('YYYY/MM/DD HH:mm'),
            });
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message || '',
          });
        }
      } catch (error) {
        console.log(error);

        MakeToast({
          variant: 'danger',
          title: this.$t('DANGER'),
          content: error['message'] || '',
        });
      }
    },
    async handleGetListDistributionMSG() {
      const params = {
        page: this.table_distribution_msg_pagination.current_page,
        per_page: this.table_distribution_msg_pagination.per_page,
      };

      try {
        const response = await listDistribution(params);

        const { code, message } = response['data'];

        if (code === 200) {
          const result = response['data']['data']['result'];
          const total_records =
            response['data']['data']['pagination']['total_records'];
          const responseUnreadNum = await getUnread();
          const codeUnreadNum = responseUnreadNum['data']['code'];

          if (codeUnreadNum === 200) {
            const mesOtherListNum =
              responseUnreadNum['data']['data']['mesOtherList'];

            if (mesOtherListNum > 0) {
              this.is_read_all_distribution_msg = false;
            } else {
              this.is_read_all_distribution_msg = true;
            }
          }

          this.table_distribution_msg_pagination.total_rows = total_records;

          this.table_distribution_msg_items = [];

          result.map((item) => {
            const itemDataParse = JSON.parse(item.data);

            const date = new Date(item.created_at * 1000);

            this.table_distribution_msg_items.push({
              id: item.id,
              read_at: item.read_at ? item.read_at : '',
              title: itemDataParse.title ? itemDataParse.title : '',
              reception_time: moment(date).format('YYYY/MM/DD HH:mm'),
            });
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message || '',
          });
        }
      } catch (error) {
        console.log(error);

        MakeToast({
          variant: 'danger',
          title: this.$t('DANGER'),
          content: error['message'] || '',
        });
      }
    },
    async handleGetListGoingJob() {
      const params = {
        page: this.table_on_going_job_pagination.current_page,
        per_page: this.table_on_going_job_pagination.per_page,
      };

      try {
        const response = await listGoingJob(params);

        const { code, message } = response.data;

        if (code === 200) {
          const {
            data: { result, pagination },
          } = response.data;

          this.table_on_going_job_pagination.total_rows =
            pagination.total_records;

          this.table_on_going_job_items = result.map((item) => {
            return {
              date: item.dateJa,
              occupation: item.occupation,
              occupation_ja: item.occupation_ja,
              name: item.full_name,
              name_ja: item.full_name_ja,
              job_name: item.job_name,
              company_name: item.company_name,
              id_hrs: item.id_hrs,
              id_job: item.id_job,
              to_link: item.to_link,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message || '',
          });
        }
      } catch (error) {
        console.log(error);

        MakeToast({
          variant: 'danger',
          title: this.$t('DANGER'),
          content: error['message'] || '',
        });
      }
    },
    showMore(type_table, type_tab) {
      if (type_table === 'new-msg') {
        if (type_tab === 'matching-relation') {
          this.$router.push({ path: `/home/show-more-msg/${1}` });
        }

        if (type_tab === 'others') {
          this.$router.push({ path: `/home/show-more-msg/${2}` });
        }
      }

      if (type_table === 'distribution-msg') {
        this.$router.push({ path: `/home/show-more-distribution-msg` });
      }

      if (type_table === 'on-going-job') {
        this.$router.push({ path: `/on-going-job` });
      }
    },
    handleNavigateToDistributionScreen() {
      this.$router.push({ path: `/home/distribute` }, (onAbort) => {});
    },
    async handleNavigateToMessageDetail(id) {
      await this.$store.dispatch('message/setMessageID', id);
      this.$router.push({ path: `/home/detail-msg` });
    },
    handleNavigateToDetailScreen(to_link) {
      if (to_link === 'entry') {
        this.$store.dispatch('app/saveTabIndex', 0);
        this.$router.push({ path: `/matching-management` });
      } else if (to_link === 'offer') {
        this.$store.dispatch('app/saveTabIndex', 1);
        this.$router.push({ path: `/matching-management` });
      } else if (to_link === 'interview') {
        this.$store.dispatch('app/saveTabIndex', 2);
        this.$router.push({ path: `/matching-management` });
      } else if (to_link === 'result') {
        this.$store.dispatch('app/saveTabIndex', 3);
        this.$router.push({ path: `/matching-management` });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Job/job.scss';

.nav-item {
  border: 1px solid #ccc;
}

.card-link {
  color: #000;
}

.show-more {
  color: #69609c !important;
}

.distribute-msg-frame {
  display: flex;
  margin-top: 1rem;
  padding: 4rem 8rem;
  background: #ffffff;
  border: 1px solid #999;
  flex-direction: column;
}

.table-cell {
  // border: 1px solid green;
  width: 100%;
  height: 40px;
  display: flex;
  position: relative;
  align-items: center;
  justify-content: flex-start;
}

.dot-unread-all {
  width: 8px;
  height: 8px;
  top: -1.2rem;
  left: -0.3rem;
  border-radius: 50%;
  position: relative;
  background: #ff0000;
  border: 1px solid #ff0000;
}

.dot-unread-item {
  border: 1px solid #ff0000;
  background: #ff0000;
  border-radius: 50%;
  width: 8px;
  height: 8px;
  position: relative;
  top: -1.2rem;
  left: -0.3rem;
}

.dot-hidden {
  border: 1px solid #ff0000;
  background: #ff0000;
  border-radius: 50%;
  width: 8px;
  height: 8px;
  position: relative;
  top: -1.2rem;
  left: -0.3rem;

  visibility: hidden !important;
}
.unread-all-head {
  // border: 1px solid #000;
  width: 100%;
  display: flex;
  justify-content: flex;
  align-items: center;
}
.unread-all-tab {
  position: relative;
  padding: 0 1rem;
  height: 0;
  & span:nth-child(1) {
    visibility: hidden;
  }
}
::v-deep .nav-link {
  border-top: 4px solid $white !important;
}
.unread-all-dot {
  background: #ff0000;
  border: 1px solid #ff0000;
  border-radius: 50%;
  width: 8px;
  height: 8px;
  position: absolute;
  top: 0.25rem;
  right: 0.15rem;
  z-index: 1;
  transform: translateY(4px);
}

::v-deep .text-link {
  // border: 1px solid #ff0000;
  color: $titleClassify;
  &:hover {
    text-decoration: underline;
    color: $titleClassify;
  }
}
.w-fit-content {
  width: fit-content !important;
  color: $titleClassify;
  &:hover {
    color: $titleClassify;
  }
}

:v-deep .table {
  color: $titleClassify;
}
.table-scroll {
  overflow-y: auto;
  max-height: 500px;
}

.card-title {
  font-size: 24px;
  font-weight: 700;
  line-height: 36px;
}
</style>
