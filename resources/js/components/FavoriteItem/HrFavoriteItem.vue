<template>
  <!-- <b-col cols="4" class="mb-4 py-0"> mx-2 -->
  <div class="favorite-hr-item">
    <div class="hr-avatar">
      <img
        class="avatar"
        :src="require('@/assets/images/icons/male-user.png')"
        alt="male-user"
      >
    </div>

    <div class="w-100 hr-name">
      <span
        :title="itemDetail.full_name"
        class="w-100 ht-name-en one-line-paragraph"
      >{{ itemDetail.full_name }}</span>
      <span
        :title="itemDetail.full_name_ja"
        class="w-100 ht-name-ja one-line-paragraph"
      >{{ itemDetail.full_name_ja }}</span>
    </div>

    <div class="hr-info">
      <span>{{ itemDetail.gender === 1 ? '男性' : '女性' }}</span>
      <span
        class="hr-info-age"
      >{{ renderAge(itemDetail.date_of_birth) }}{{ $t('AGE') }}</span>
      <!-- <span>{{ itemDetail?.language_requirement.name }}</span> -->
      <span>{{ renderLanguageName(itemDetail.language_requirement.id) }}</span>
    </div>
    <button
      id="btn-remove-status-favourite-hr"
      class="btn btn-favorited"
      @click="removeStatusFavouriteHR(itemDetail.id)"
    >
      <b-icon icon="heart-fill" class="rounded" />
      <div class="btn-text">
        <span class="text-title">{{ $t('ADDED_TO_FAVORITES') }}</span>
        <span class="text-desc">{{ $t('REMOVE_FROM_FAVORITES') }}</span>
      </div>
    </button>
    <div class="job-actions mt-5">
      <b-button
        :class="['btn btn-entry mb-3']"
        :disabled="itemDetail.status === 3 || itemDetail.status === 2"
        @click="handleMoveSelectJobToOffer(itemDetail.id)"
      >
        オファーする
      </b-button>
      <button class="btn btn-detail" @click="goToDetailHR(itemDetail.id)">
        詳細を見る
      </button>
    </div>
  </div>
  <!-- </b-col> -->
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { removeFavourite } from '@/api/user.js';
import { jaLevelOption } from '@/const/hrOrganization.js';
import moment from 'moment';

export default {
  name: 'HrFavoriteItem',

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
      jaLevelOption: jaLevelOption,
    };
  },
  computed: {
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },

  methods: {
    async removeStatusFavouriteHR(id) {
      const query = `relation_id=${id}&type=1`;
      try {
        const response = await removeFavourite(query);
        const { code, message } = response.data;
        if (code === 200) {
          this.$emit('removeStatusFavouriteHRSuccess');
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

    renderAge(dateString) {
      const yearBorn = moment(dateString).year();
      const yearCurrent = moment().year();

      return yearCurrent - yearBorn;
    },

    goToDetailHR(id) {
      this.$router.push(`/hr/detail/${id}`);
    },

    async handleMoveSelectJobToOffer(id) {
      this.$router.push(`/hr/detail/${id}`);
      this.$store.dispatch('app/setRedirectToModalSelectJobsToOffer', true);
    },

    renderLanguageName(key) {
      const findItem = this.jaLevelOption.find((item) => item.key === key);
      if (findItem) {
        return findItem.value;
      } else {
        return '';
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.favorite-hr-item {
  display: flex;
  align-items: center;
  flex-direction: column;
  padding: 14px 20px;
  background: #fff;
  border: 1px solid #d2d2d2;
  box-shadow: 0px 4px 4px rgba(162, 162, 162, 0.25);
  border-radius: 10px;
  width: 342px;
  .hr-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 15px;
  }
  .hr-name {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    .ht-name-en {
      font-size: 24px;
      font-weight: 700px;
      color: #000;
      line-height: 1;
    }
    .ht-name-ja {
      font-weight: 400;
      font-size: 20px;
      color: #000;
    }
  }
  .hr-info {
    font-size: 16px;
    font-weight: 400;
    color: #000;
    margin: 25px 0px;
    .hr-info-age {
      margin: 0px 37px;
    }
  }
  .job-actions {
    display: flex;
    flex-direction: column;
  }
}
</style>
