<!-- CompanyBasicInfo -->
<template>
  <div id="company-detail-info-page" class="hr-registration-page-autox mt-1">
    <div class="hr-registration-page__form">
      <!-- <div>formData: {{ formData }}</div><br> -->
      <!-- <div>type_form: {{ type_form }}</div><br> -->
      <!-- <div>middle_classification_options: {{ middle_classification_options }}</div><br> -->
      <!-- <button style="position: fixed; background: gray; opacity: 0.1;" class="btn" @click="changeStatus">Change</button> -->

      <!-- 2.1 設立年度 establishment_year -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.ESTABLISHMENT_YEAR') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.establishment_year"
            aria-describedby="establishment_year"
            max-lenght="50"
            dusk="'establishment_year'"
            :name="'establishment_year'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.establishment_year === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'establishment_year')"
          />
          <b-form-invalid-feedback
            id="establishment_year"
            :state="error.establishment_year"
          >
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.ESTABLISHMENT_YEAR') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.establishment_year }}</span>
          </div>
        </div>
      </div>

      <!-- 2.2 創業年度 startup_year -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.STARTUP_YEAR') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.startup_year"
            aria-describedby="startup_year"
            max-lenght="50"
            dusk="'startup_year'"
            :name="'startup_year'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.startup_year === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'startup_year')"
          />
          <b-form-invalid-feedback
            id="startup_year"
            :state="error.startup_year"
          >
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.STARTUP_YEAR') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.startup_year }}</span>
          </div>
        </div>
      </div>

      <!-- 2.3 資本金等 capital -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.CAPITAL') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.capital"
            aria-describedby="capital"
            max-lenght="50"
            dusk="'capital'"
            :name="'capital'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.capital === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'capital')"
          />
          <b-form-invalid-feedback id="capital" :state="error.capital">
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.CAPITAL') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.capital }}</span>
          </div>
        </div>
      </div>

      <!-- 2.4 売上金 proceeds -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.PROCEEDS') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.proceeds"
            aria-describedby="proceeds"
            max-lenght="50"
            dusk="'proceeds'"
            :name="'proceeds'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.proceeds === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'proceeds')"
          />
          <b-form-invalid-feedback id="proceeds" :state="error.proceeds">
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.PROCEEDS') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.proceeds }}</span>
          </div>
        </div>
      </div>

      <!-- 2.5 従業員数 number_of_staffs -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_STAFFS') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.number_of_staffs"
            aria-describedby="number_of_staffs"
            max-lenght="50"
            dusk="'number_of_staffs'"
            :name="'number_of_staffs'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.number_of_staffs === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'number_of_staffs')"
          />
          <b-form-invalid-feedback
            id="number_of_staffs"
            :state="error.number_of_staffs"
          >
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_STAFFS') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.number_of_staffs }}</span>
          </div>
        </div>
      </div>

      <!-- 2.6 従業員数 number_of_operations -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_OPERATIONS') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.number_of_operations"
            aria-describedby="number_of_operations"
            max-lenght="50"
            dusk="'number_of_operations'"
            :name="'number_of_operations'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.number_of_operations === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'number_of_operations')"
          />
          <b-form-invalid-feedback
            id="number_of_operations"
            :state="error.number_of_operations"
          >
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_OPERATIONS') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.number_of_operations }}</span>
          </div>
        </div>
      </div>

      <!-- 2.7 店舗数 number_of_shops -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_SHOPS') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.number_of_shops"
            aria-describedby="number_of_shops"
            max-lenght="50"
            dusk="'number_of_shops'"
            :name="'number_of_shops'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.number_of_shops === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'number_of_shops')"
          />
          <b-form-invalid-feedback
            id="number_of_shops"
            :state="error.number_of_shops"
          >
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_SHOPS') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.number_of_shops }}</span>
          </div>
        </div>
      </div>

      <!-- 2.8 工場数 number_of_factories -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_FACTORIES') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.number_of_factories"
            aria-describedby="number_of_factories"
            max-lenght="50"
            dusk="'number_of_factories'"
            :name="'number_of_factories'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.number_of_factories === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'number_of_factories')"
          />
          <b-form-invalid-feedback
            id="number_of_factories"
            :state="error.number_of_factories"
          >
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.NUMBER_OF_FACTORIES') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.number_of_factories }}</span>
          </div>
        </div>
      </div>

      <!-- 2.10 決算月 fiscal_year -->
      <div
        v-if="type_form === 'edit'"
        class="row-item border-t border-l border-r border-b"
      >
        <div class="form-title d-flex justify-content-between">
          <span>{{ $t('JOB_DETAIL.FISCAL_YEAR') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <b-form-input
            v-model="formData.fiscal_year"
            aria-describedby="fiscal_year"
            max-lenght="50"
            dusk="'fiscal_year'"
            :name="'fiscal_year'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.fiscal_year === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'fiscal_year')"
          />
          <b-form-invalid-feedback id="fiscal_year" :state="error.fiscal_year">
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div
        v-if="type_form === 'detail'"
        class="row-item border-t border-l border-r border-b"
      >
        <div
          class="form-title"
          :class="{ 'bg-type-detail': type_form === 'detail' }"
        >
          <span>{{ $t('JOB_DETAIL.FISCAL_YEAR') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.fiscal_year }}</span>
          </div>
        </div>
      </div>

      <!--  -->
      <!-- hr-registration-page__form -->
    </div>
  </div>
</template>

<script>
// import { obj2Path } from '@/utils/obj2Path';
// import SearchWithConditions from '../../layout/components/search/SearchWithConditions.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'CompanyBasicInfo',
  components: {
    // Require,
    Arbitrarily,
  },
  props: {
    formDataCompany: {
      type: Object,
      required: true,
    },
    errorCompany: {
      type: Object,
      required: true,
    },
    // typeForm: {
    //   type: String,
    //   default: 'detail',
    // },
  },

  data() {
    return {
      status_agree_with_terms_conditions: false,
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0.2,
        blur: '1rem',
        rounded: 'sm',
      },
      //  type_form: 'detail',
      type_form: 'edit',
      //
      formData: this.formDataCompany,
      // formData: CompanyData1,
      error: this.errorCompany,

      //
    };
  },

  computed: {},

  created() {
    this.getPathUrl();
  },

  methods: {
    getPathUrl() {
      const path = this.$route.path;
      if (path.includes('detail')) {
        this.path_company = path;
        this.type_form = 'detail';
      }
      if (path.includes('edit')) {
        this.path_company = path;
        this.type_form = 'edit';
      }
    },
    changeStatus() {
      if (this.type_form === 'detail') {
        this.type_form = 'edit';
      } else if (this.type_form === 'edit') {
        this.type_form = 'detail';
      }
    },
    format7characters(e) {
      return String(e).substring(0, 7);
    },
    format50characters(e) {
      return String(e).substring(0, 50);
    },

    handleChangeForm(event, field) {
      this.$emit('handle-change-form', event, field);
    },

    checkValidate() {
      this.$emit('check-validate');
    },

    handleChangeFormOption(event, type_dropdown) {
      this.$emit('handle-change-form-option', event, type_dropdown);
    },
    // Lấy danh sách các ngành nghề

    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/RegisterHrOrigin/RegisterHrOrigin.scss';
</style>
