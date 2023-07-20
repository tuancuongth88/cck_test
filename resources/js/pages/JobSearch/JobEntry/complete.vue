<template>
  <div class="job-detail-for-hr">
    <div class="job-detail-for-hr-container">
      <div class="job-detail-for-hr-form">
        <div class="job-detail-for-hr-form__head">
          <div class="h-100 d-flex justify-start align-stretch">
            <div class="line" />

            <div class="job-detail-for-hr-form__head-title pl-4">
              <span>{{ '求人情報 エントリー' }}</span>
            </div>
          </div>

          <div class="job-detail-for-hr-form__head-btn">
            <div class="btn btn-back" @click="goBack()">
              <span>{{ $t('RETURN') }}</span>
            </div>
          </div>
        </div>

        <div class="job-detail-for-hr-form-autox">
          <div class="job-detail-for-hr-form-page">
            <div class="form-page-area">
              <div class="form-page-area__content">
                <div class="form-page-area-content-wrap notification">
                  <span>エントリーを送信しました。</span>
                  <span>企業からの連絡をお待ちください。</span>
                </div>
              </div>
            </div>
          </div>

          <div class="footer" style="margin-top: 2rem; margin-bottom: 2rem">
            <span>Entry has been submitted.</span>
            <span>Please wait for contact from the company</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'JobInformationEntryComplete',
  data() {
    return {};
  },
  methods: {
    goBack() {
      this.$router.push({ name: 'JobInformationEntry' });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/JobSearch/JobDetailForHr/JobDetailForHr.scss';

.notification {
  height: 500px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.footer {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>
