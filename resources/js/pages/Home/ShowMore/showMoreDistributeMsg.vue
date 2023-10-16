<!-- ShowMoreMsgPage -->
<!-- /show-more-distribution-msg -->
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
            <span>{{ $t('HOME_MANAGEMENT.DISTRIBUTION_MSG') }}</span>
          </div>
        </div>

        <!-- <div>role: {{	role }}</div><br> -->

        <div class="show-more-body">
          <div class="show-more-body-wrap">
            <div class="show-more-table-wrap">
              <div class="show-more-table">
                <!-- All Role -->
                <b-table
                  :items="itemsDistributionMsg"
                  :fields="fieldsDistributionMsg"
                  :bordered="true"
                  :current-page="paginationDistributionMsg.currentPage"
                  :hover="true"
                >
                  <!-- 1 件名 title -->
                  <template #cell(title)="data">
                    <div class="table-cell">
                      <span v-if="!data.item.read_at" class="dot-unread-item" />
                      <b-link
                        class="card-link text-link"
                        @click="handleNavigateToMessageDetail(data.item.id)"
                      >
                        {{ data.item.title }}
                      </b-link>
                    </div>
                  </template>
                  <!-- 2 受信日時 reception time -->
                  <template #cell(reception_time)="data">
                    <div class="w-100 d-flex justify-center align-center">
                      <span>{{ data.item.reception_time }}</span>
                    </div>
                  </template>
                </b-table>

                <!--  -->
              </div>
            </div>
            <!--  -->
            <div v-if="!overlay.show" class="show-more-pagination">
              <div>
                <!-- <b-pagination
                  v-model="paginationDistributionMsg.currentPage"
                  :total-rows="paginationDistributionMsg.totalRows"
                  :per-page="paginationDistributionMsg.perPage"
                  aria-controls="new-msg"
                  pills
                  :next-class="'next'"
                  :prev-class="'prev'"
                  @change="($event) => getCurrentPageDistributionMsg($event)"
                /> -->
                <custom-pagination
                  v-if="itemsDistributionMsg && itemsDistributionMsg.length > 0"
                  :total-rows="paginationDistributionMsg.totalRows"
                  :current-page="paginationDistributionMsg.currentPage"
                  :per-page="paginationDistributionMsg.perPage"
                  @pagechanged="onPageChange"
                  @changeSize="changeSize"
                />
              </div>
            </div>
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
import moment from 'moment';
import { listDistribution } from '@/api/user.js';
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

      ROLE: ROLE,

      // Table 1: NewMessageMatching related vs Others
      readAllDistributionMsg: true,

      paginationDistributionMsg: {
        currentPage: 1,
        totalRows: null,
        perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      },
      fieldsDistributionMsg: [
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
      itemsDistributionMsg: [],
      //
    };
  },

  computed: {},

  created() {
    this.getListDistributionMsg();
  },

  methods: {
    // getCurrentPageDistributionMsg(value) {
    //   if (value) {
    //     this.paginationDistributionMsg.currentPage = parseInt(value);
    //     this.getListDistributionMsg();
    //   }
    // },

    onPageChange(page) {
      this.paginationDistributionMsg.currentPage = page;
      this.getListDistributionMsg();
    },
    changeSize(size) {
      this.paginationDistributionMsg.perPage = size;
      this.paginationDistributionMsg.currentPage = 1;
      this.getListDistributionMsg();
    },

    // Table 2 配信メッセージ distribution msg
    async getListDistributionMsg() {
      const params = {
        page: this.paginationDistributionMsg.currentPage,
        per_page: this.paginationDistributionMsg.perPage,
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
            this.readAllDistributionMsg = false;
          } else {
            this.readAllDistributionMsg = true;
          }

          this.paginationDistributionMsg.totalRows = total_records;
          this.itemsDistributionMsg = []; // !
          result.map((item) => {
            const itemDataParse = JSON.parse(item.data);
            const date = new Date(item.created_at * 1000);
            this.itemsDistributionMsg.push({
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
        console.log(error);
      }
    },

    async handleNavigateToMessageDetail(id) {
      await this.$store.dispatch('message/setMessageID', id);
      this.$router.push({ path: `/home/detail-msg` });
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/modules/Home/showMore.scss';
</style>
