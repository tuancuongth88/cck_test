<!-- ShowMoreMsgPage -->
<!-- /show-more-on-going-job -->
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
      <div class="show-more-container pb-2">
        <!-- 進行中の案件 On-going Job -->
        <div class="show-more-head">
          <div class="line" />
          <div class="show-more-head__title">
            <span>{{ $t('HOME_MANAGEMENT.ON_GOING_JOB') }}</span>
          </div>
        </div>

        <!-- <div>role: {{	role }}</div><br> -->
        <div class="d-flex w-100 mt-2">
          <b-col cols="12">
            <div>
              <div class="show-more-table-wrap">
                <div class="show-more-table">
                  <!-- All Role -->
                  <b-table
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
                <!--  -->
                </div>
              </div>
              <!--  -->
              <custom-pagination
                v-if="table_on_going_job_items && table_on_going_job_items.length > 0"
                :total-rows="table_on_going_job_pagination.totalRows"
                :current-page="table_on_going_job_pagination.currentPage"
                :per-page="table_on_going_job_pagination.perPage"
                @pagechanged="onPageChange"
                @changeSize="changeSize"
              />
            </div>
          </b-col>
        </div>

      </div>
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import BtnBack from '@/components/Button/BtnBack.vue';
import { listGoingJob } from '@/api/user.js';

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
      currentTabNewMsg: 'matching-related', // default
      readAllMsgOnGoingJob: true,
      table_on_going_job_pagination: {
        currentPage: 1,
        totalRows: null,
        perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      },

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
      //
    };
  },

  computed: {},

  created() {
    this.getListOnGoingJob();
  },

  methods: {
    // getCurrentPageOnGoingJob(value) {
    //   if (value) {
    //     this.table_on_going_job_pagination.currentPage = parseInt(value);
    //     this.getListOnGoingJob();
    //   }
    // },
    onPageChange(page) {
      this.table_on_going_job_pagination.currentPage = page;
      this.getListOnGoingJob();
    },
    changeSize(size) {
      this.table_on_going_job_pagination.perPage = size;
      this.table_on_going_job_pagination.currentPage = 1;
      this.getListOnGoingJob();
    },
    async getListOnGoingJob() {
      this.table_on_going_job_items = [];
      const params = {
        page: this.table_on_going_job_pagination.currentPage,
        per_page: this.table_on_going_job_pagination.perPage,
      };
      try {
        this.overlay.show = true;
        const response = await listGoingJob(params);
        const { code, message } = response.data;

        if (code === 200) {
          // const array = [];
          const {
            data: { result, pagination },
          } = response.data;

          this.table_on_going_job_pagination.totalRows =
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
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/modules/Home/showMore.scss';
</style>
