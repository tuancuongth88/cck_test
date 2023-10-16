<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>

    <div class="dev">
      <div class="favorite-wrapper">
        <div
          v-if="[ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER].includes(permissionCheck)"
          class="favorite-job"
        >
          <h2 class="block-title">{{ $t('FAVORITE.JOB') }}</h2>

          <template v-for="(item, index) in listFavouriteJob">
            <JobFavoriteItem
              :key="index"
              :item-detail="item"
              @removeStatusFavouriteJobSuccess="removeStatusFavouriteJobSuccess"
            />
          </template>

          <div class="w-100 d-flex justify-end align-center">
            <!-- <b-pagination
              v-model="paginationFavouriteJob.current_page"
              pills
              :next-class="'next'"
              :prev-class="'prev'"
              style="padding-top: 20px"
              :per-page="paginationFavouriteJob.per_page"
              :total-rows="paginationFavouriteJob.total_records"
              @change="($event) => getCurrentPageFavouriteJob($event)"
            /> -->
            <custom-pagination
              v-if="listFavouriteJob && listFavouriteJob.length > 0"
              :total-rows="paginationFavouriteJob.total_records"
              :current-page="paginationFavouriteJob.current_page"
              :per-page="paginationFavouriteJob.per_page"
              @pagechanged="onPageChangeJob"
              @changeSize="changeSizeJob"
            />
          </div>
        </div>

        <div
          v-if="
            [ROLE.TYPE_COMPANY, ROLE.TYPE_COMPANY_ADMIN].includes(
              permissionCheck
            )
          "
          class="favorite-hr mt-4"
        >
          <h2 class="block-title">{{ $t('FAVORITE.HR') }}</h2>
          <b-row
            class="w-100 d-flex flex-column justify-center align-center mx-0"
          >
            <b-row class="w-100 align-center justify-start px-0 pt-4 list">
              <template v-for="(item, index) in listFavouriteHr">
                <HrFavoriteItem
                  :key="index"
                  :item-detail="item"
                  @removeStatusFavouriteHRSuccess="
                    removeStatusFavouriteHRSuccess
                  "
                />
              </template>
            </b-row>

            <div class="w-100 d-flex justify-end align-center pr-3">
              <!-- <b-pagination
                v-model="paginationFavouriteHr.current_page"
                pills
                :next-class="'next'"
                :prev-class="'prev'"
                style="padding-top: 20px"
                :per-page="paginationFavouriteHr.per_page"
                :total-rows="paginationFavouriteHr.total_records"
                @change="($event) => getCurrentPageFavouriteHr($event)"
              /> -->
              <custom-pagination
                v-if="listFavouriteHr && listFavouriteHr.length > 0"
                :total-rows="paginationFavouriteHr.total_records"
                :current-page="paginationFavouriteHr.current_page"
                :per-page="paginationFavouriteHr.per_page"
                @pagechanged="onPageChangeHr"
                @changeSize="changeSizeHr"
              />
            </div>
          </b-row>
        </div>
      </div>
    </div>
  </b-overlay>
</template>

<script>
import { listFavourite } from '@/api/user.js';
import { MakeToast } from '@/utils/toastMessage';

import ROLE from '@/const/role.js';
import HrFavoriteItem from '@/components/FavoriteItem/HrFavoriteItem.vue';
import JobFavoriteItem from '@/components/FavoriteItem/JobFavoriteItem.vue';
import { PAGINATION_CONSTANT } from '@/const/config.js';

export default {
  name: 'Favorite',
  components: {
    HrFavoriteItem,
    JobFavoriteItem,
  },
  data() {
    return {
      listFavouriteHr: [],

      listFavouriteJob: [],

      ROLE: ROLE,

      overlay: {
        opacity: 0,
        fixed: true,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      paginationFavouriteHr: {
        per_page: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
        current_page: 1,
        total_records: 0,
      },

      paginationFavouriteJob: {
        per_page: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
        current_page: 1,
        total_records: 0,
      },
    };
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },
  created() {
    if (
      [ROLE.TYPE_COMPANY, ROLE.TYPE_COMPANY_ADMIN].includes(
        this.permissionCheck
      )
    ) {
      this.getListFavouriteHr();
    } else if (
      [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER].includes(this.permissionCheck)
    ) {
      this.getListFavouriteJob();
    }
    // else if (this.permissionCheck === ROLE.TYPE_SUPER_ADMIN) {
    //   this.getListFavouriteHr();
    //   this.getListFavouriteJob();
    // }
  },
  methods: {
    // getCurrentPageFavouriteHr(value) {
    //   if (value) {
    //     this.paginationFavouriteHr.current_page = parseInt(value);
    //     this.getListFavouriteHr();
    //   }
    // },
    // getCurrentPageFavouriteJob(value) {
    //   if (value) {
    //     this.paginationFavouriteJob.current_page = parseInt(value);
    //     this.getListFavouriteJob();
    //   }
    // },

    onPageChangeJob(page) {
      this.paginationFavouriteJob.current_page = page;
      this.getListFavouriteJob();
    },
    changeSizeJob(size) {
      this.paginationFavouriteJob.per_page = size;
      this.paginationFavouriteJob.current_page = 1;
      this.getListFavouriteJob();
    },

    onPageChangeHr(page) {
      this.paginationFavouriteHr.current_page = page;
      this.getListFavouriteHr();
    },
    changeSizeHr(size) {
      this.paginationFavouriteHr.per_page = size;
      this.paginationFavouriteHr.current_page = 1;
      this.getListFavouriteHr();
    },
    async getListFavouriteHr() {
      this.overlay.show = true;

      try {
        const params = {
          type: 1,
          page: this.paginationFavouriteHr.current_page,
          per_page: this.paginationFavouriteHr.per_page,
        };

        const response = await listFavourite(params);

        const { code, message, data } = response.data;

        if (code === 200) {
          const { pagination } = data;

          this.paginationFavouriteHr.total_records = pagination.total_records;

          const {
            data: { result },
          } = response.data;

          this.listFavouriteHr = result;
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        console.log(error);
      }

      this.overlay.show = false;
    },
    async getListFavouriteJob() {
      this.overlay.show = true;

      try {
        const params = {
          type: 2,
          page: this.paginationFavouriteJob.current_page,
          per_page: this.paginationFavouriteJob.per_page,
        };

        const response = await listFavourite(params);

        const { code, message, data } = response.data;

        if (code === 200) {
          const { pagination } = data;

          this.paginationFavouriteJob.total_records = pagination.total_records;

          const {
            data: { result },
          } = response.data;

          if (result) {
            result.forEach((item) => {
              item['job_description'] = this.handleBreakLine(
                item['job_description']
              );
            });
          }

          this.listFavouriteJob = result;
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        console.log(error);
      }

      this.overlay.show = false;
    },
    async removeStatusFavouriteHRSuccess() {
      this.getListFavouriteHr();
    },
    async removeStatusFavouriteJobSuccess() {
      this.getListFavouriteJob();
    },
    handleBreakLine(paragraph) {
      let result;

      if (paragraph) {
        result = paragraph.split('\n');
      }

      return result;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/modules/Job/job.scss';

.favorite-job {
  padding: 20px;
  border: 1px solid #999;
}
.favorite-hr {
  padding: 20px;
  background: #f9f9ff;
  border: 1px solid #999;
}

.list {
  gap: 20px;
}
</style>
