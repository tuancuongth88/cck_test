<template>
  <div class="favorite-job-item">
    <div class="favorite-head">
      <div class="favorite-head-left">
        <h3 class="job-name">{{ itemDetail.title }}</h3>
      </div>

      <div class="favorite-head-right">
        <span
          :class="'job-date'"
        >{{ renderDateJobTo(itemDetail.application_period_to)
        }}{{ $t('JOB_SEARCH.POSTED_UNTIL') }}</span>

        <button
          id="btn-remove-status-favourite-job"
          class="btn btn-favorited"
          @click="removeStatusFavouriteJob(itemDetail.id)"
        >
          <b-icon icon="heart-fill" class="rounded" />

          <div class="btn-text">
            <span class="text-title">{{ $t('ADDED_TO_FAVORITES') }}</span>
            <span class="text-desc">{{ $t('REMOVE_FROM_FAVORITES') }}</span>
          </div>
        </button>
      </div>
    </div>

    <div class="favorite-content">
      <div class="job-info w-75">
        <div class="job-info-item">
          <div class="job-info-left">
            <div class="job-icon">
              <img
                class="icon"
                :src="require('@/assets/images/icons/briefcase.png')"
                alt="briefcase"
              >
            </div>

            <span>{{ $t('JOB_DETAIL.JOB_DESCRIPTION') }}</span>
          </div>

          <div class="job-info-right d-flex flex-column">
            <h4
              v-for="(line, lineIndex) in itemDetail.job_description"
              :key="lineIndex"
              class="d-flex mb-0"
            >
              {{ line }}<br>
            </h4>
          </div>
        </div>

        <div class="job-info-item">
          <div class="job-info-left">
            <div class="job-icon">
              <img
                class="icon"
                :src="require('@/assets/images/icons/location.png')"
                alt="location"
              >
            </div>

            <span>{{ $t('WORKING_PLACE_2') }}</span>
          </div>

          <div class="job-info-right">
            <h4 class="mb-0">{{ renderCityName(itemDetail.city_id) }}</h4>
          </div>
        </div>

        <div class="job-info-item">
          <div class="job-info-left">
            <div class="job-icon">
              <img
                class="icon"
                :src="require('@/assets/images/icons/pouch.png')"
                alt="pouch"
              >
            </div>

            <span>{{ $t('JOB_DETAIL.EXPECTED_INCOME') }}</span>
          </div>

          <div class="job-info-right">
            <h4 class="mb-0">
              {{ itemDetail.expected_income }}
              {{ $t('JOB_SEARCH.TEN_THOUSAND_YEN') }}
            </h4>
          </div>
        </div>
      </div>

      <div class="job-actions w-25">
        <b-button
          id="btn-check-dead-line-recruit"
          class="btn btn-app btn-entry mb-3"
          :disabled="checkDeadlineRecruit(itemDetail.application_period_to)"
          @click="handleMoveHrEntry()"
        >
          <span>{{ $t('ENTER') }}</span>
        </b-button>

        <b-button
          id="btn-handle-clear-all-setting"
          class="btn btn-app btn-detail"
          @click="goToDetailJob(itemDetail.id)"
        >
          <span>{{ $t('JOB_SEARCH.SEE_DETAIL') }}</span>
        </b-button>
      </div>
    </div>
  </div>
</template>

<script>
import { removeFavourite } from '@/api/user.js';
import { MakeToast } from '@/utils/toastMessage';
import ROLE from '@/const/role.js';

import moment from 'moment';

export default {
  name: 'JobFavoriteItem',
  props: {
    title: {
      type: String,
      default: '',
    },
    itemDetail: {
      type: Object,
      default: function() {
        return {};
      },
    },
  },
  data() {
    return {
      city_list: [
        { id: 1, name_en: 'Hokkaido', name_ja: '北海道' },
        { id: 2, name_en: 'Aomori', name_ja: '青森県' },
        { id: 3, name_en: 'Iwate', name_ja: '岩手県' },
        { id: 4, name_en: 'Miyagi', name_ja: '宮城県' },
        { id: 5, name_en: 'Akita', name_ja: '秋田県' },
        { id: 6, name_en: 'Yamagata', name_ja: '山形県' },
        { id: 7, name_en: 'Fukushima', name_ja: '福島県' },
        { id: 16, name_en: 'Toyama', name_ja: '富山県' },
        { id: 17, name_en: 'Ishikawa', name_ja: '石川県' },
        { id: 18, name_en: 'Fukui', name_ja: '福井県' },
        { id: 15, name_en: 'Niigata', name_ja: '新潟県' },
        { id: 19, name_en: 'Yamanashi', name_ja: '山梨県' },
        { id: 20, name_en: 'Nagano', name_ja: '長野県' },
        { id: 13, name_en: 'Tokyo', name_ja: '東京都' },
        { id: 14, name_en: 'Kanagawa', name_ja: '神奈川県' },
        { id: 12, name_en: 'Chiba', name_ja: '千葉県' },
        { id: 11, name_en: 'Saitama', name_ja: '埼玉県' },
        { id: 8, name_en: 'Ibaraki', name_ja: '茨城県' },
        { id: 9, name_en: 'Tochigi', name_ja: '栃木県' },
        { id: 10, name_en: 'Gunma', name_ja: '群馬県' },
        { id: 23, name_en: 'Aichi', name_ja: '愛知県' },
        { id: 22, name_en: 'Shizuoka', name_ja: '静岡県' },
        { id: 21, name_en: 'Gifu', name_ja: '岐阜県' },
        { id: 24, name_en: 'Mie', name_ja: '三重県' },
        { id: 27, name_en: 'Osaka', name_ja: '大阪府' },
        { id: 26, name_en: 'Kyoto', name_ja: '京都府' },
        { id: 28, name_en: 'Hyogo', name_ja: '兵庫県' },
        { id: 25, name_en: 'Shiga', name_ja: '滋賀県' },
        { id: 29, name_en: 'Nara', name_ja: '奈良県' },
        { id: 30, name_en: 'Wakayama', name_ja: '和歌山県' },
        { id: 34, name_en: 'Hiroshima', name_ja: '広島県' },
        { id: 33, name_en: 'Okayama', name_ja: '岡山県' },
        { id: 31, name_en: 'Tottori', name_ja: '鳥取県' },
        { id: 32, name_en: 'Shimane', name_ja: '島根県' },
        { id: 35, name_en: 'Yamaguchi', name_ja: '山口県' },
        { id: 36, name_en: 'Tokushima', name_ja: '徳島県' },
        { id: 37, name_en: 'Kagawa', name_ja: '香川県' },
        { id: 38, name_en: 'Ehime', name_ja: '愛媛県' },
        { id: 39, name_en: 'Kochi', name_ja: '高知県' },
        { id: 40, name_en: 'Fukuoka', name_ja: '福岡県' },
        { id: 43, name_en: 'Kumamoto', name_ja: '熊本県' },
        { id: 41, name_en: 'Saga', name_ja: '佐賀県' },
        { id: 42, name_en: 'Nagasaki', name_ja: '長崎県' },
        { id: 44, name_en: 'Ōita', name_ja: '大分県' },
        { id: 45, name_en: 'Miyazaki', name_ja: '宮崎県' },
        { id: 46, name_en: 'Kagoshima', name_ja: '鹿児島県' },
        { id: 47, name_en: 'Okinawa', name_ja: '沖縄県' },
      ],
      ROLE: ROLE,
    };
  },

  computed: {
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },
  methods: {
    async removeStatusFavouriteJob(id) {
      const query = `relation_id=${id}&type=2`;
      try {
        const response = await removeFavourite(query);
        const { code, message } = response.data;
        if (code === 200) {
          this.$emit('removeStatusFavouriteJobSuccess');
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    goToDetailJob(id) {
      if ([ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER].includes(this.permissionCheck)) {
        this.$router.push(`/job-search/detail/${id}`);
      } else {
        this.$router.push(`/job/detail/${id}`);
      }
    },
    renderDateJobTo(dateJobDue) {
      return moment(dateJobDue).format('YYYY年MM月DD日');
    },
    checkDeadlineRecruit(dateJobDueTo) {
      const dateJobDue = moment(dateJobDueTo).format('YYYY年MM月DD日');

      const dateCurrent = moment().format('YYYY年MM月DD日');

      if (dateCurrent > dateJobDue) {
        return true;
      } else {
        return false;
      }
    },
    renderCityName(city_id) {
      let result = '';

      if (city_id) {
        this.city_list.find((item) => {
          if (item['id'] === city_id) {
            result = item['name_ja'];
          }
        });
      }

      return result;
    },
    async handleMoveHrEntry() {
      await this.$store.dispatch('job/setWorkID', this.itemDetail.id);
      this.$router.push('/job-search/select-entry-hr');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
.favorite-job-item {
  margin-bottom: 20px;
  background: #fff;
  border: 1px solid #999;
  border-radius: 5px;
  .favorite-head {
    display: flex;
    justify-content: space-between;
    padding: 15px 20px;
    background-color: $grayBg;
    border-radius: 5px 5px 0px 0px;
    border-bottom: 1px solid #999;
    .favorite-head-left {
      span {
        font-style: normal;
        font-weight: 400;
        font-size: 12px;
      }
      .job-name {
        font-size: 20px;
        color: $blueLight;
        font-weight: 700;
        margin-bottom: 0;
      }
    }
    .favorite-head-right {
      display: flex;
      flex-direction: column;
      align-items: center;
      .job-date {
        font-size: 13px;
        color: #323232;
        margin-bottom: 5px;
      }
    }
  }
  .favorite-content {
    display: flex;
    justify-content: space-between;
    .job-info {
      padding: 20px 30px;
      .job-info-item {
        display: flex;
        align-items: center;
        &:not(:last-child) {
          margin-bottom: 20px;
        }
        .job-info-left {
          display: flex;
          align-items: center;
          flex-direction: column;
          margin-right: 20px;
          .job-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            background-color: #f5f5f5;
            border-radius: 50%;
          }
          span {
            font-weight: 400;
            font-size: 8px;
            color: #444;
          }
        }
        .job-info-right {
          h4 {
            font-weight: 400;
            font-size: 14px;
            color: #444;
          }
        }
      }
    }
    .job-actions {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 260px;
      background-color: #f8f8f8;
      border-left: 1px solid #999;
      border-radius: 0px 0px 5px 0px;
    }
  }

  .w-75 {
    max-width: 75%;
  }
}
</style>
