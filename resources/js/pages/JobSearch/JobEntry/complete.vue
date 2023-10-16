<template>
  <div class="job-detail-for-hr">
    <div class="job-detail-for-hr-container">
      <div class="job-detail-for-hr-form">
        <div class="job-detail-for-hr-form__head">
          <div class="h-100 d-flex justify-start align-stretch">
            <div class="line" />

            <div class="job-detail-for-hr-form__head-title pl-4">
              <span>{{ $t('INFORMATION_JOB') }}</span>
            </div>
          </div>

          <div class="job-detail-for-hr-form__head-btn">
            <div class="btn btn-back" @click="handleNavigateToJobList()">
              <span>{{ $t('RETURN') }}</span>
            </div>
          </div>
        </div>

        <div class="job-detail-for-hr-form-autox">
          <div class="job-detail-for-hr-form-page">
            <div class="form-page-area">
              <div class="form-page-area__content">
                <div class="form-page-area-content-wrap notification">
                  <span>{{ $t('ITEM_SENT') }}</span>
                  <span>{{ $t('WATING_FROM_COMPANY') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'JobEntryComplete',
  data() {
    return {};
  },
  methods: {
    async handleNavigateToJobList() {
      await this.$store.dispatch('job/setListHrInformation', []);
      await this.$store.dispatch('job/setIsFilledData', false);
      this.$router.push({ path: '/job-search/list' });
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
