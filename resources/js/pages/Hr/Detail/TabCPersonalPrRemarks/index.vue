<template>
  <div class="hr-content-tab hr-persional-remarks">
    <div class="hr-content-tab-wrap">
      <div
        class="hr-content-tab-item border-t border-l border-r"
        style="min-height: 240px"
      >
        <div
          class="hr-content-tab-item__title d-flex justify-content-start align-items-start"
        >
          <span class="label-persional-pr-special-note">{{ $t('HR_LIST_FORM.PERSONAL_PR_SPECIAL_NOTES') }}</span>
        </div>

        <div
          class="hr-content-tab__data d-flex justify-content-start align-items-start py-2"
        >
          <div>
            <span class="text-left contenr-persional-pr-special-note">{{
              personalPrSpecialNotes
            }}</span>
          </div>

        </div>
      </div>

      <div
        class="hr-content-tab-item border-t border-l border-r border-b"
        style="min-height: 240px"
      >
        <div
          class="hr-content-tab-item__title d-flex justify-content-start align-items-start"
        >
          <span class="label-remaks">{{ $t('HR_LIST_FORM.REMARKS') }}</span>
        </div>

        <div
          class="hr-content-tab__data d-flex justify-content-start align-items-start py-2"
        >
          <div>
            <span class="text-left content-remaks">{{
              remarks
            }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'TabCPersonalPrRemarksDetail',
  props: {
    personalPrSpecialNotes: {
      type: String,
      require: true,
      default: function() {
        return '';
      },
    },
    remarks: {
      type: String,
      require: true,
      default: function() {
        return '';
      },
    },
  },
  data() {
    return {
      formDetail: {},
    };
  },
  watch: {
    // detailData: {
    //   handler: async function(props_data) {
    //     if (props_data) {
    //       this.formDetail = props_data;
    //     }
    //   },
    //   deep: true,
    // },
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
