<template>
  <div class="hr-content-tab">
    <div class="hr-content-tab-wrap">
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id=" " class="hr-content-tab-item__title">
          <span>{{ $t('HR_LIST_FORM.TELEPHONE_NUMBER') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div>
            <span>{{ formData.telephone_number }}</span>
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div id=" " class="hr-content-tab-item__title">
          <span>{{ $t('HR_LIST_FORM.MOBILE_PHONE_NUMBER') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div>
            <span>{{ formData.mobile_phone_number }}</span>
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div id=" " class="hr-content-tab-item__title">
          <span>{{ $t('HR_LIST_FORM.MAIL_ADDRESS') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div>
            <span>{{ formData.mail_address }}</span>
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div
          id=" "
          class="hr-content-tab-item__title d-flex"
          style="
            justify-content: flex-start;
            align-items: flex-start;
            padding-top: 1.5rem;
          "
        >
          <span>{{ $t('HR_LIST_FORM.ADDRESS') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="flex-row"
            style="
              gap: 1rem;
              justify-content: flex-start;
              align-items: center;
              padding: 1rem 0;
            "
          >
            <div
              class="w-100 d-flex flex-column justify-space-between align-center"
              style="gap: 1.751rem"
            >
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{ $t('HR_LIST_FORM.CITY') }}</span><!-- City -->
                <div>{{ formData.address_city }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.DISTRICT')
                }}</span><!-- district -->
                <div>{{ formData.address_district }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.NUMBER')
                }}</span><!-- Number -->
                <div>{{ formData.address_number }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.ORTHER')
                }}</span><!-- other -->
                <div>{{ formData.address_other }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r border-b">
        <div
          id=" "
          class="hr-content-tab-item__title d-flex"
          style="
            justify-content: flex-start;
            align-items: flex-start;
            padding-top: 1.5rem;
          "
        >
          <span>{{ $t('HR_LIST_FORM.HOMETOWN_ADDRESS') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="flex-row"
            style="
              gap: 1rem;
              justify-content: flex-start;
              align-items: center;
              padding: 1rem 0;
            "
          >
            <div
              class="w-100 d-flex flex-column justify-space-between align-center"
              style="gap: 1.751rem"
            >
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{ $t('HR_LIST_FORM.CITY') }}</span><!-- City -->
                <div>{{ formData.hometown_city }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.DISTRICT')
                }}</span><!-- district -->
                <div>{{ formData.home_town_district }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.NUMBER')
                }}</span><!-- Number -->
                <div>{{ formData.home_town_number }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.ORTHER')
                }}</span><!-- other -->
                <div>{{ formData.home_town_other }}</div>
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
  name: 'TabDContactDetail',
  props: {
    detailData: {
      type: Object,
      require: true,
      default: function() {
        return {};
      },
    },
  },

  data() {
    return {
      formData: {
        telephone_number: '',
        mobile_phone_number: '',
        mail_address: '',

        address_city: '',
        address_district: '',
        address_number: '',
        address_other: '',

        home_town_district: '',
        home_town_number: '',
        home_town_other: '',
        hometown_city: '',
      },
    };
  },

  watch: {
    detailData: {
      handler: async function(props_data) {
        if (props_data) {
          this.converData(props_data);
        }
      },
      deep: true,
    },
  },

  methods: {
    converData(data) {
      if (!data) {
        return;
      }

      this.formData = {
        telephone_number: data.telephone_number,
        mobile_phone_number: data.mobile_phone_number,
        mail_address: data.mail_address,

        address_city: data.address_city,
        address_district: data.address_district,
        address_number: data.address_number,
        address_other: data.address_other,

        home_town_district: data.home_town_district,
        home_town_number: data.home_town_number,
        home_town_other: data.home_town_other,
        hometown_city: data.hometown_city,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
