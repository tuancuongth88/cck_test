<!-- ShowMoreMsgPage -->
<!-- /show-more/:type -->
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
        <p style="margin-top: 10px">
          {{ $t('PLEASE_WAIT') }}
        </p>
      </div>
    </template>
    <!-- 	 -->
    <div class="show-more">
      <div class="show-more-container">
        <!-- 新着メッセージ new msg -->
        <div class="show-more-head">
          <div class="line" />
          <div class="show-more-head__title">
            <span>{{ $t('TITLE.NEW_MSG') }}</span>
          </div>
        </div>

        <!-- <div>role: {{	role }}</div><br> -->
        <!-- <div>currentTabNewMsg: {{	currentTabNewMsg }}</div><br> -->

        <div class="show-more-body">
          <div class="show-more-body-wrap">
            <div class="show-more-table-wrap">
              <div class="show-more-table">
                <!-- Role 1 - admin -->
                <b-table
                  v-if="[ROLE.TYPE_SUPER_ADMIN].includes(permissionCheck)"
                  id="new-msg-maching"
                  :items="itemsNewMsg"
                  :fields="fieldsNewMsg"
                  :bordered="true"
                  hover
                  :current-page="paginationNewMsgMatchingRelated.currentPage"
                >
                  <!-- 1 -->
                  <template #cell(title)="data">
                    <div class="table-cell">
                      <span v-if="!data.item.read_at" class="dot-unread-item" />
                      <span v-if="data.item.read_at" class="dot-hidden" />
                      <b-link
                        class="card-link text-link"
                        :dusk="`detail-msg`"
                        @click="handleNavigateToMessageDetail(data.item.id)"
                      >
                        <span>{{ data.item.title }}</span>
                      </b-link>
                    </div>
                  </template>
                  <!-- 2 -->
                  <template #cell(reception_time)="data">
                    <div class="w-100 h-100 d-flex justify-center align-center">
                      <span>{{ data.item.reception_time }}</span>
                    </div>
                  </template>
                </b-table>

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
                      v-if="!readAllMsgMatchingRelated"
                      class="unread-all-dot"
                    />
                  </div>

                  <div class="unread-all-tab">
                    <span>{{ $t('TITLE.OTHERS') }}</span>
                    <span v-if="!readAllOtherMsg" class="unread-all-dot" />
                  </div>
                </div>

                <!-- Role === 2345 -->
                <b-tabs
                  v-if="
                    [
                      ROLE.TYPE_COMPANY_ADMIN,
                      ROLE.TYPE_HR_MANAGER,
                      ROLE.TYPE_COMPANY,
                      ROLE.TYPE_HR,
                    ].includes(permissionCheck)
                  "
                  class="w-100 tab-active"
                >
                  <!-- 1 Tab Matching Related -->
                  <b-tab
                    class="w-100"
                    :active="currentTabNewMsg === 'matching-related'"
                    @click="handChageCurrentTab('matching-related')"
                  >
                    <template #title>
                      <div class="w-100">
                        {{ $t('TAB.MATCHING_RELATION') }}
                        <!-- <span v-if="!readAllMsgMatchingRelated" class="dot" /> -->
                      </div>
                    </template>
                    <!--   :current-page="
                        paginationNewMsgMatchingRelated.currentPage
                      "-->
                    <b-table
                      id="new-msg"
                      :items="itemsNewMsg"
                      :fields="fieldsNewMsg"
                      :bordered="true"
                      :hover="true"
                      show-empty
                    >
                      <template #empty="">
                        <div class="text-center">
                          {{ $t('TABLE_EMPTY') }}
                        </div>
                      </template>
                      <!-- 1 -->
                      <template #cell(title)="data">
                        <div class="table-cell">
                          <span
                            v-if="!data.item.read_at"
                            class="dot-unread-item"
                          />
                          <span v-if="data.item.read_at" class="dot-hidden" />
                          <b-link
                            class="card-link text-link"
                            @click="handleNavigateToMessageDetail(data.item.id)"
                          >
                            {{ data.item.title }}
                          </b-link>
                        </div>
                      </template>
                      <!-- 2 -->
                      <template #cell(reception_time)="data">
                        <div
                          class="w-100 h-100 d-flex justify-center align-center"
                        >
                          <span>{{ data.item.reception_time }}</span>
                        </div>
                      </template>
                    </b-table>
                  </b-tab>
                  <!-- 2 Others -->
                  <b-tab
                    class="w-100"
                    :active="currentTabNewMsg === 'others'"
                    @click="handChageCurrentTab('others')"
                  >
                    <template #title>
                      <div class="w-100">
                        {{ $t('TITLE.OTHERS') }}
                        <!-- <span v-if="!readAllOtherMsg" class="dot" /> -->
                      </div>
                    </template>
                    <!--    :current-page="
                        paginationNewMsgOther.currentPage
                      " -->
                    <b-table
                      id="new-msg-other-show-more"
                      :items="itemsOtherMsg"
                      :fields="fieldsOtherMsg"
                      :bordered="true"
                      show-empty
                    >
                      <template #empty="">
                        <div class="text-center">
                          {{ $t('TABLE_EMPTY') }}
                        </div>
                      </template>
                      <!-- 1 -->
                      <template #cell(title)="data">
                        <div class="table-cell">
                          <span
                            v-if="!data.item.read_at"
                            class="dot-unread-item"
                          />
                          <span v-if="data.item.read_at" class="dot-hidden" />
                          <b-link
                            class="card-link text-link"
                            @click="handleNavigateToMessageDetail(data.item.id)"
                          >
                            <span>{{ data.item.title }}</span>
                          </b-link>
                        </div>
                      </template>
                      <!-- 2 -->
                      <template #cell(reception_time)="data">
                        <div
                          class="w-100 h-100 d-flex justify-center align-center"
                        >
                          <span>{{ data.item.reception_time }}</span>
                        </div>
                      </template>
                    </b-table>
                  </b-tab>
                </b-tabs>
              </div>
            </div>

            <!-- <div v-if="!overlay.show" class="show-more-pagination"> -->
            <!-- Tab 1 -->
            <custom-pagination
              v-if="
                currentTabNewMsg === 'matching-related' &&
                  itemsNewMsg &&
                  itemsNewMsg.length > 0
              "
              :total-rows="paginationNewMsgMatchingRelated.totalRows"
              :current-page="paginationNewMsgMatchingRelated.currentPage"
              :per-page="paginationNewMsgMatchingRelated.perPage"
              :key-tab="currentTabNewMsg"
              @pagechanged="onPageChange"
              @changeSize="changeSize"
            />
            <!-- Tab 2: Others-->
            <custom-pagination
              v-if="
                currentTabNewMsg === 'others' &&
                  itemsOtherMsg &&
                  itemsOtherMsg.length > 0
              "
              :total-rows="paginationNewMsgOther.totalRows"
              :current-page="paginationNewMsgOther.currentPage"
              :per-page="paginationNewMsgOther.perPage"
              :key-tab="currentTabNewMsg"
              @pagechanged="onPageChangeOther"
              @changeSize="changeSizeOther"
            />
          </div>
        </div>
      </div>
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import BtnBack from '@/components/Button/BtnBack.vue';
import { listNoti, listDistribution } from '@/api/user.js';
import moment from 'moment';
import ROLE from '@/const/role.js';
import { PAGINATION_CONSTANT } from '@/const/config.js';

export default {
  name: 'ShowMoreMsgPage',
  components: {
    // SearchWithConditions,
  },

  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
      },

      role: '',
      ROLE: ROLE,

      // Table 1: NewMessageMatching related vs Others
      currentTabNewMsg: 'matching-related', // default
      readAllMsgMatchingRelated: true,

      paginationNewMsgMatchingRelated: {
        currentPage: 1,
        totalRows: 0,
        perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      },

      fieldsNewMsg: [
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
          },
        },
      ],
      itemsNewMsg: [
        // {
        //   id: 1,
        //   status: 'interview-zoom',
        //   title: '面接Zoom URL発行',
        //   reception_time: '2023/02/01 13:21',
        // },
      ],

      // Table 1: Tab thers
      readAllOtherMsg: true,

      // Others
      paginationNewMsgOther: {
        currentPage: 1,
        totalRows: 0, // null
        perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      },
      fieldsOtherMsg: [
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
      itemsOtherMsg: [],
      //
    };
  },

  computed: {
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },

  created() {
    if (this.permissionCheck === ROLE.TYPE_SUPER_ADMIN) {
      this.getListNotiMatchingRelated();
    } else {
      this.getListNotiMatchingRelated();
      this.getListOthersMsg();
    }

    this.getCurrenTab();
  },

  methods: {
    getCurrenTab() {
      this.currentTabNewMsg = 'matching-related';
      if (parseInt(this.$route.params.id) === null) {
        this.currentTabNewMsg = 'matching-related';
      }
      if (parseInt(this.$route.params.id) === 1) {
        this.currentTabNewMsg = 'matching-related';
      }
      if (parseInt(this.$route.params.id) === 2) {
        this.currentTabNewMsg = 'others';
      }
    },

    handChageCurrentTab(type_tab) {
      // 'matching-related' 'others'
      // if (!type_tab) {
      //   this.currentTabNewMsg = 'matching-related';
      // } else {
      //   this.currentTabNewMsg = type_tab;
      // }
      this.currentTabNewMsg = type_tab;
    },

    // Table 1 Tab 1 マッチング関連  Matching relation
    async getListNotiMatchingRelated() {
      this.itemsNewMsg = []; // !
      const params = {
        page: this.paginationNewMsgMatchingRelated.currentPage,
        per_page: this.paginationNewMsgMatchingRelated.perPage,
      };
      try {
        this.overlay.show = true;
        const response = await listNoti(params);
        const { code, message } = response['data'];
        const result = response['data']['data']['result'];
        const total_records =
          response['data']['data']['pagination']['total_records'];

        if (code === 200) {
          const array = [];
          result.forEach((item) => {
            array.push(item['read_at']);
          });
          if (array.includes(null)) {
            this.readAllMsgMatchingRelated = false;
          } else {
            this.readAllMsgMatchingRelated = true;
          }
          this.paginationNewMsgMatchingRelated.totalRows = total_records;

          result.map((item) => {
            const itemDataParse = JSON.parse(item.data);
            const date = new Date(item.created_at * 1000);
            this.itemsNewMsg.push({
              id: item.id,
              read_at: item.read_at ? item.read_at : '',
              title: itemDataParse.subject ? itemDataParse.subject : '',
              reception_time: moment(date).format('YYYY/MM/DD HH:mm'),
            });
          });
          this.overlay.show = false;
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },

    // Table 1 Tab 2 その他 Others
    async getListOthersMsg() {
      this.itemsOtherMsg = []; // !
      const params = {
        page: this.paginationNewMsgOther.currentPage,
        per_page: this.paginationNewMsgOther.perPage,
      };
      try {
        this.overlay.show = true;
        const response = await listDistribution(params);
        const { code, message } = response['data'];
        const result = response['data']['data']['result'];
        const total_records =
          response['data']['data']['pagination']['total_records'];

        if (code === 200) {
          const array = [];
          result.forEach((item) => {
            array.push(item['read_at']);
          });
          if (array.includes(null)) {
            this.readAllOtherMsg = false;
          } else {
            this.readAllOtherMsg = true;
          }

          this.paginationNewMsgOther.totalRows = total_records;
          result.map((item) => {
            const itemDataParse = JSON.parse(item.data);
            const date = new Date(item.created_at * 1000);
            this.itemsOtherMsg.push({
              id: item.id,
              read_at: item.read_at ? item.read_at : '',
              title: itemDataParse.title ? itemDataParse.title : '',
              reception_time: moment(date).format('YYYY/MM/DD HH:mm'),
            });
          });
          this.overlay.show = false;
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        this.overlay.show = false;
        console.error(error);
      }
    },

    async handleNavigateToMessageDetail(id) {
      await this.$store.dispatch('message/setMessageID', id);
      this.$router.push({ path: `/home/detail-msg` });
    },
    //
    onPageChange(page) {
      this.paginationNewMsgMatchingRelated.currentPage = page;
      this.getListNotiMatchingRelated();
    },
    changeSize(size) {
      this.paginationNewMsgMatchingRelated.perPage = size;
      this.paginationNewMsgMatchingRelated.currentPage = 1;
      this.getListNotiMatchingRelated();
    },
    // paginationNewMsgMatchingRelated
    onPageChangeOther(page) {
      this.paginationNewMsgOther.currentPage = page;
      this.getListOthersMsg();
    },
    changeSizeOther(size) {
      this.paginationNewMsgOther.perPage = size;
      this.paginationNewMsgOther.currentPage = 1;
      this.getListOthersMsg();
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/modules/Home/showMore.scss';
</style>
