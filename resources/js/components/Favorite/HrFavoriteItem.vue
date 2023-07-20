<template>
  <b-col>
    <div class="favorite-hr-item">
      <div class="hr-avatar">
        <img
          class="avatar"
          :src="require('@/assets/images/icons/male-user.png')"
          alt="male-user"
        >
      </div>
      <div class="hr-name">
        <span class="ht-name-en">Nguyen Thi Nhi</span>
        <span class="ht-name-ja">ｸﾞｴﾝ ﾃｨｰ ﾆｰ</span>
      </div>
      <div class="hr-info">
        <span>女性</span>
        <span class="hr-info-age">22歳</span>
        <span>N1</span>
      </div>
      <button class="btn btn-favorited">
        <b-icon icon="heart-fill" class="rounded" />
        <div class="btn-text">
          <span class="text-title">お気に入りに追加済</span>
          <span class="text-desc">お気に入りにから外す</span>
        </div>
      </button>
      <div class="job-actions mt-5">
        <button class="btn btn-entry mb-3">エントリーする</button>
        <button class="btn btn-detail">詳細を見る</button>
      </div>
    </div>
  </b-col>
</template>

<script>

export default {
  name: 'HrFavoriteItem',

  props: {
    title: {
      type: String,
      default: '',
    },
  },

  computed: {
  },

  methods: {
  },

};
</script>

<style lang="scss" scoped>
	@import "../../scss/_variables.scss";
  .favorite-hr-item {
    display: flex;
    align-items: center;
    flex-direction: column;
    padding: 20px;
    background: #fff;
    border: 1px solid #D2D2D2;
    box-shadow: 0px 4px 4px rgba(162, 162, 162, 0.25);
    border-radius: 10px;
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
