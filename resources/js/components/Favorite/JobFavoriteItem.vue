<template>
  <div class="favorite-job-item">
    <div class="favorite-head">
      <div class="favorite-head-left">
        <h3 class="job-name">日ベトナム通訳</h3>
        <span>【新宿】働きやすいインターナショナルな環境</span>
      </div>
      <div class="favorite-head-right">
        <span class="job-date">2023年4月30日まで掲載</span>
        <button class="btn btn-favorited">
          <b-icon icon="heart-fill" class="rounded" />
          <div class="btn-text">
            <span class="text-title">お気に入りに追加済</span>
            <span class="text-desc">お気に入りにから外す</span>
          </div>
        </button>
      </div>
    </div>
    <div class="favorite-content">
      <div class="job-info">
        <div class="job-info-item">
          <div class="job-info-left">
            <div class="job-icon">
              <img
                class="icon"
                :src="require('@/assets/images/icons/briefcase.png')"
                alt="briefcase"
              >
            </div>
            <span>仕事内容</span>
          </div>
          <div class="job-info-right">
            <h4 class="mb-0">日本語とベトナム語の通訳・翻訳のお仕事。未経験可能・要N2以上。東京の通いやすい立地のオフィスで待…</h4>
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
            <span>勤務地</span>
          </div>
          <div class="job-info-right">
            <h4 class="mb-0">東京新宿区／JR山手線線「新宿駅」より徒歩5分</h4>
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
            <span>給与</span>
          </div>
          <div class="job-info-right">
            <h4 class="mb-0">月収30万円〜</h4>
          </div>
        </div>
      </div>
      <div class="job-actions">
        <button class="btn btn-app btn-entry mb-3">エントリーする</button>
        <button class="btn btn-app btn-detail">詳細を見る</button>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'JobFavoriteItem',

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
  @import '../../scss/_variables.scss';
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
              background-color: #F5F5F5;
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
        background-color: #F8F8F8;
        border-left: 1px solid #999;
        border-radius: 0px 0px 5px 0px;
      }
    }
  }
</style>
