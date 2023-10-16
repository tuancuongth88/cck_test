<template>
  <div class="display-user-management-list">
    <h1 class="border-left-title font-weight-bold">
      {{ $t('TAB_JOB_SEARCH') }}
    </h1>

    <div class="job-search-content">
      <h2 class="job-search-title">{{ $t('SEARCH_FOR_JOB_CONDITION') }}</h2>

      <b-card-group deck>
        <b-card no-body>
          <b-list-group>
            <b-list-group-item class="p-0">
              <div class="d-flex">
                <b-col
                  cols="3"
                  class="d-flex align-items-center bg-gray font-weight-bold"
                >
                  <span>{{ $t('SEARCH_JOB_LIST.OCCUPATION') }}</span>
                </b-col>

                <b-col cols="9" class="d-flex flex-column my-3">
                  <button
                    class="btn btn-plus-modal"
                    dusk="select_job_btn"
                    @click="handleOpenModalOccupation()"
                  >
                    <span>＋ {{ $t('HR_REGISTER.SPECIFY') }}</span>
                  </button>

                  <div
                    v-for="item in occupation"
                    :key="item.id"
                    class="list-item-checked mt-3"
                  >
                    <span
                      class="icon-checked"
                      @click="handleRemoveSelected(item.id)"
                    />
                    <span>{{
                      item.name_ja + ` (${item.childOptions.length})`
                    }}</span>
                  </div>
                </b-col>
              </div>
            </b-list-group-item>

            <b-list-group-item class="p-0">
              <div class="d-flex flex-wrap">
                <b-col
                  cols="3"
                  class="d-flex align-items-center bg-gray font-weight-bold"
                >
                  <span>{{ $t('INCOME') }}</span>
                </b-col>

                <b-col cols="9" class="my-3">
                  <b-row>
                    <b-col cols="5">
                      <b-form-select
                        v-model="income_from"
                        dusk="income_from"
                        :options="income_options"
                        value-field="key"
                        text-field="value"
                      />
                    </b-col>

                    <b-col
                      cols="1"
                      class="p-0 d-flex align-items-center justify-content-center"
                    >
                      <span>{{ $t('JOB_SEARCH.TEN_THOUSAND_YEN') }} 〜</span>
                    </b-col>

                    <b-col cols="5">
                      <b-form-select
                        v-model="income_to"
                        dusk="income_to"
                        :options="income_options"
                        value-field="key"
                        text-field="value"
                      />
                    </b-col>

                    <b-col
                      cols="1"
                      class="p-0 d-flex align-items-center justify-content-center"
                    >
                      <span>{{ $t('JOB_SEARCH.TEN_THOUSAND_YEN') }}</span>
                    </b-col>
                  </b-row>
                </b-col>
              </div>
            </b-list-group-item>

            <b-list-group-item class="p-0">
              <div class="d-flex">
                <b-col
                  cols="3"
                  class="d-flex align-items-center bg-gray font-weight-bold"
                >
                  <span>{{ $t('WORKING_PLACE_2') }}</span>
                </b-col>

                <b-col
                  id="dusk_city_list"
                  cols="9"
                  class="d-flex align-items-center my-3"
                >
                  <multiselect
                    v-model="city_selected"
                    label="name_ja"
                    :multiple="true"
                    track-by="name_ja"
                    :options="city_list"
                    :placeholder="$t('SELECT_PREFECTURE')"
                    :group-values="'options'"
                    :group-label="'timezone'"
                    :selected-label="$t('SELECT')"
                    :select-label="''"
                    :deselect-label="''"
                  >
                    <template slot="option" slot-scope="props">
                      <span v-if="props.option.$isLabel" class="group-label">{{
                        props.option.$groupLabel
                      }}</span>
                      <span v-else class="option-label">{{
                        props.option.name_ja
                      }}</span>
                    </template>

                    <template slot="noResult">
                      <span>{{ $t('ELEMENT_NOT_FOUND') }}</span>
                    </template>
                  </multiselect>
                </b-col>
              </div>
            </b-list-group-item>

            <b-list-group-item class="p-0">
              <div id="dusk_japan_levels" class="d-flex flex-wrap">
                <b-col
                  cols="3"
                  class="d-flex align-items-center bg-gray font-weight-bold"
                >
                  <span>{{ $t('LANGUAGE_REQUIREMENT') }}</span>
                </b-col>

                <b-col cols="9" class="d-flex align-items-center my-3">
                  <b-form-checkbox-group
                    v-model="language_requirement"
                    :options="language_requirement_options"
                    value-field="value"
                    text-field="text"
                  />
                </b-col>
              </div>
            </b-list-group-item>
            <!-- 雇用形態 -->
            <b-list-group-item class="p-0">
              <div class="d-flex">
                <b-col
                  cols="3"
                  class="d-flex align-items-center bg-gray font-weight-bold"
                >
                  <span>{{ $t('JOB_EDIT.FORM_OF_EMPLOYMENT') }}</span>
                </b-col>

                <b-col
                  id="dusk_work_forms"
                  cols="9"
                  class="d-flex align-items-center my-3"
                >
                  <b-form-checkbox-group
                    id="checkbox-group-1"
                    v-model="form_of_employment"
                    :options="form_of_employment_options"
                    value-field="value"
                    text-field="text"
                  />
                </b-col>
              </div>
            </b-list-group-item>

            <b-list-group-item class="p-0">
              <div class="d-flex flex-wrap">
                <b-col
                  cols="3"
                  class="d-flex align-items-center bg-gray font-weight-bold"
                >
                  <span>{{ $t('SPECIAL_POINTS') }}</span>
                </b-col>

                <b-col
                  id="dusk_passion_works"
                  cols="9"
                  class="d-flex align-items-center flex-wrap my-3"
                >
                  <b-form-checkbox-group
                    v-model="passion"
                    :options="passion_options"
                    value-field="value"
                    text-field="text"
                  />
                </b-col>
              </div>
            </b-list-group-item>

            <b-list-group-item class="p-0">
              <div class="d-flex flex-wrap">
                <b-col
                  cols="3"
                  class="d-flex align-items-center bg-gray font-weight-bold"
                >
                  <span>{{ $t('SEARCH_JOB_LIST.FREE_WORD') }}</span>
                </b-col>

                <b-col cols="9" class="my-3">
                  <b-form-input v-model="free_word" dusk="input_search" />
                </b-col>
              </div>
            </b-list-group-item>
          </b-list-group>
        </b-card>
      </b-card-group>

      <div class="search-job-btns d-flex flex-column align-items-center">
        <div
          class="btn btn-search-vs-conditions btn_search--custom"
          dusk="btn_search_with_conditions"
          @click="handleSearch()"
        >
          <span>{{ $t('SEARCH_JOB_LIST.SEARCH_WITH_CONDITIONS') }}</span>
          <img
            :src="require('@/assets/images/login/chervon-right-white.png')"
            alt="collapse"
          >
        </div>

        <div
          class="btn btn-clear-settings"
          dusk="btn_clear_settings"
          @click="handleClearFormData()"
        >
          <span>{{ $t('BUTTON.CLEAR_SETTINGS') }}</span>
        </div>
      </div>
    </div>

    <ModalMultipleSelectOccupation
      :options="modal_occupation_options"
      :show-modal="is_show_modal_occupation"
    />
  </div>
</template>

<script>
import EventBus from '@/utils/eventBus';
import Multiselect from 'vue-multiselect';
import ModalMultipleSelectOccupation from '@/components/Modal/ModalMultipleSelect.vue';

import { getListMainjob } from '@/api/job';
import { incomeOption } from '@/const/job.js';
import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'JobSearch',
  components: {
    Multiselect,
    ModalMultipleSelectOccupation,
  },
  data() {
    return {
      occupation: [],
      is_show_modal_occupation: false,
      modal_occupation_options: [],

      income_from: '',
      income_to: '',
      income_options: incomeOption,

      city_selected: [],
      city_list: [
        {
          timezone: '北海道・東北',
          options: [
            { id: 1, name_en: 'Hokkaido', name_ja: '北海道' },
            { id: 2, name_en: 'Aomori', name_ja: '青森県' },
            { id: 3, name_en: 'Iwate', name_ja: '岩手県' },
            { id: 4, name_en: 'Miyagi', name_ja: '宮城県' },
            { id: 5, name_en: 'Akita', name_ja: '秋田県' },
            { id: 6, name_en: 'Yamagata', name_ja: '山形県' },
            { id: 7, name_en: 'Fukushima', name_ja: '福島県' },
          ],
        },
        {
          timezone: '北陸・甲信越',
          options: [
            { id: 16, name_en: 'Toyama', name_ja: '富山県' },
            { id: 17, name_en: 'Ishikawa', name_ja: '石川県' },
            { id: 18, name_en: 'Fukui', name_ja: '福井県' },
            { id: 15, name_en: 'Niigata', name_ja: '新潟県' },
            { id: 19, name_en: 'Yamanashi', name_ja: '山梨県' },
            { id: 20, name_en: 'Nagano', name_ja: '長野県' },
          ],
        },
        {
          timezone: '関東',
          options: [
            { id: 13, name_en: 'Tokyo', name_ja: '東京都' },
            { id: 14, name_en: 'Kanagawa', name_ja: '神奈川県' },
            { id: 12, name_en: 'Chiba', name_ja: '千葉県' },
            { id: 11, name_en: 'Saitama', name_ja: '埼玉県' },
            { id: 8, name_en: 'Ibaraki', name_ja: '茨城県' },
            { id: 9, name_en: 'Tochigi', name_ja: '栃木県' },
            { id: 10, name_en: 'Gunma', name_ja: '群馬県' },
          ],
        },
        {
          timezone: '東海',
          options: [
            { id: 23, name_en: 'Aichi', name_ja: '愛知県' },
            { id: 22, name_en: 'Shizuoka', name_ja: '静岡県' },
            { id: 21, name_en: 'Gifu', name_ja: '岐阜県' },
            { id: 24, name_en: 'Mie', name_ja: '三重県' },
          ],
        },
        {
          timezone: '関西',
          options: [
            { id: 27, name_en: 'Osaka', name_ja: '大阪府' },
            { id: 26, name_en: 'Kyoto', name_ja: '京都府' },
            { id: 28, name_en: 'Hyogo', name_ja: '兵庫県' },
            { id: 25, name_en: 'Shiga', name_ja: '滋賀県' },
            { id: 29, name_en: 'Nara', name_ja: '奈良県' },
            { id: 30, name_en: 'Wakayama', name_ja: '和歌山県' },
          ],
        },
        {
          timezone: '中国',
          options: [
            { id: 34, name_en: 'Hiroshima', name_ja: '広島県' },
            { id: 33, name_en: 'Okayama', name_ja: '岡山県' },
            { id: 31, name_en: 'Tottori', name_ja: '鳥取県' },
            { id: 32, name_en: 'Shimane', name_ja: '島根県' },
            { id: 35, name_en: 'Yamaguchi', name_ja: '山口県' },
          ],
        },
        {
          timezone: '四国',
          options: [
            { id: 36, name_en: 'Tokushima', name_ja: '徳島県' },
            { id: 37, name_en: 'Kagawa', name_ja: '香川県' },
            { id: 38, name_en: 'Ehime', name_ja: '愛媛県' },
            { id: 39, name_en: 'Kochi', name_ja: '高知県' },
          ],
        },
        {
          timezone: '九州・沖縄',
          options: [
            { id: 40, name_en: 'Fukuoka', name_ja: '福岡県' },
            { id: 43, name_en: 'Kumamoto', name_ja: '熊本県' },
            { id: 41, name_en: 'Saga', name_ja: '佐賀県' },
            { id: 42, name_en: 'Nagasaki', name_ja: '長崎県' },
            { id: 44, name_en: 'Ōita', name_ja: '大分県' },
            { id: 45, name_en: 'Miyazaki', name_ja: '宮崎県' },
            { id: 46, name_en: 'Kagoshima', name_ja: '鹿児島県' },
            { id: 47, name_en: 'Okinawa', name_ja: '沖縄県' },
          ],
        },
      ],

      language_requirement: [],
      language_requirement_options: [
        { value: 1, text: 'N1' },
        { value: 2, text: 'N2' },
        { value: 3, text: 'N3' },
        { value: 4, text: 'N4' },
        { value: 5, text: 'N5' },
        { value: 6, text: '問わない' },
      ],

      form_of_employment: [],
      form_of_employment_options: [
        { value: 1, text: '正社員' },
        { value: 2, text: '契約社員' },
        { value: 3, text: '派遣社員' },
        { value: 4, text: 'その他' },
      ],

      passion: [],
      passion_options: [
        { value: 1, text: '住宅手当有' },
        { value: 2, text: '福利厚生充実' },
        { value: 3, text: '退職金有' },
        { value: 4, text: '年間休日120日以上' },
        { value: 5, text: '寮有' },
        { value: 6, text: '未経験可' },
        { value: 7, text: '外国人管理職登用実績有' },
        { value: 8, text: 'リモート勤務可' },
        { value: 9, text: '引っ越し手当有' },
        { value: 10, text: '週28時間以下OK' },
      ],

      free_word: '',
    };
  },
  created() {
    EventBus.$on('modalSpecifyJobExpShowStatusChanged', (status) => {
      this.is_show_modal_occupation = status;
    });

    EventBus.$on('dataModalMultiple', (data) => {
      const listParentIds = Object.keys(data);
      const listSelected = [];

      listParentIds.length > 0 &&
        listParentIds.map((id) => {
          const parent = this.modal_occupation_options.find(
            (x) => x.id === Number(id)
          );
          listSelected.push({
            ...parent,
            childOptions: data[id],
          });
        });

      this.occupation = listSelected;
    });

    this.handleGetListOccupation();

    // this.handleSyncJobSearchData();
  },
  destroyed() {
    // EventBus.$off('modalSpecifyJobExpShowStatusChanged');
  },
  methods: {
    // handleSyncJobSearchData() {
    //   const syncData = this.$store.getters.job_search_data;

    //   if (syncData) {
    //     this.occupation = syncData['middle_classification_id'];

    //     if (this.occupation) {
    //       this.status_job = true;
    //     }

    //     this.city_id = syncData['city_id'];

    //     if (this.city_id) {
    //       this.status_working_place = true;
    //     }

    //     this.language_requirement = syncData['language_requirements'];

    //     if (this.language_requirement) {
    //       this.status_language_requirement = true;
    //     }

    //     this.form_of_employment = syncData['form_of_employment'];

    //     if (this.form_of_employment) {
    //       this.status_form_of_employment = true;
    //     }

    //     this.passion = syncData['passion_works'];

    //     if (this.passion) {
    //       this.status_passion = true;
    //     }

    //     this.income_from = syncData['income_from'];
    //     this.income_to = syncData['income_to'];

    //     if (this.income_from || this.income_to) {
    //       this.status_income = true;
    //     }

    //     this.free_word = syncData['key_search'];
    //   }
    // },
    async handleGetListOccupation() {
      try {
        const response = await getListMainjob();
        const { code, message } = response['data'];

        if (code === 200) {
          const { data } = response['data'];

          this.modal_occupation_options = data.map((item) => {
            return {
              ...item,
              childOptions: item.job_info,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message,
          });
        }
      } catch (error) {
        MakeToast({
          variant: 'danger',
          title: this.$t('DANGER'),
          content: error.message,
        });
      }
    },
    async handleSearch() {
      const formData = {
        middle_classification_id: this.occupation,
        income_from: this.income_from,
        income_to: this.income_to,
        city_id: this.city_selected,
        language_requirements: this.language_requirement,
        // form_of_employment: this.form_of_employment
        //   ? [this.form_of_employment]
        //   : [],
        form_of_employment: this.form_of_employment,
        passion_works: this.passion,
        key_search: this.free_word,
      };
      await this.$store.dispatch('jobSearch/setJobSearchData', formData);

      this.$router.push({ path: '/job-search/list' });
    },
    handleOpenModalOccupation() {
      EventBus.$emit('showModalSelect', true);
    },
    async handleClearFormData() {
      this.occupation = [];
      this.income_from = '';
      this.income_to = '';
      this.city_selected = [];
      this.language_requirement = [];
      this.form_of_employment = [];
      this.passion = [];
      this.free_word = '';

      EventBus.$emit('handleClearSettingsModal');

      await this.$store.dispatch('jobSearch/setJobSearchData', {});
    },
    async handleRemoveSelected(id) {
      this.occupation = this.occupation.filter(
        (item) => item.id !== Number(id)
      );

      EventBus.$emit('removeSelected', Number(id));

      const formData = {
        middle_classification_id: this.occupation,
        income_from: this.income_from,
        income_to: this.income_to,
        city_id: this.city_selected,
        language_requirements: this.language_requirement,
        form_of_employment: this.form_of_employment
          ? [this.form_of_employment]
          : [],
        passion_works: this.passion,
        key_search: this.free_word,
      };

      await this.$store.dispatch('jobSearch/setJobSearchData', formData);
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss" scoped>
@import '@/scss/_variables.scss';

.btn-plus-modal {
  width: 150px;

  &:hover {
    opacity: 0.6;
    color: #304cad;
  }
}

.job-search-content {
  padding: 20px;
  border: 1px solid #999;
}

.bg-gray {
  background-color: #f8f8f8;
}

.p-0 {
  padding: 0 !important;
}

.badge-required {
  color: red;
  width: 28px;
  height: 13px;
  display: flex;
  font-size: 8px;
  align-items: center;
  border: 1px solid red;
  justify-content: center;
}

.badge-not-required {
  color: #999;
  width: 28px;
  height: 13px;
  display: flex;
  font-size: 8px;
  align-items: center;
  border: 1px solid #999999;
  background-color: #ffffff;
  justify-content: center;
}

textarea.form-control {
  flex: none;
  display: flex;
}

::v-deep .modal-content {
  min-width: 782px;
  min-height: 512px;
  transform: translate(-18%, 25%);
}

::v-deep .modal-body {
  align-items: stretch;
}

.content-modal {
  width: 100%;
  height: 100%;
  gap: 1.25rem;
  display: flex;
  align-items: stretch;
  flex-direction: column;
  justify-content: center;
}

.content-modal-options {
  width: 100%;
  gap: 1.5rem;
  display: flex;
  align-items: stretch;
  justify-content: center;
}

.collapse-parents {
  width: 40%;
  height: 315px;
  overflow-y: auto;
}

.collapse-item {
  display: flex;
  border-top: none;
  padding: 1.076rem;
  position: relative;
  background: #ffffff;
  align-items: center;
  border: 1px solid #c6c6c6;
  border-radius: 0 !important;
  justify-content: flex-start;

  &:first-child {
    border-top: 1px solid #c6c6c6;
    border-radius: 5px 5px 0 0 !important;
  }

  & span {
    color: #333333;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }

  & img:last-child {
    top: 1rem;
    right: 1rem;
    position: absolute;
  }
}

.checked {
  top: 1.1rem;
  height: 17px;
  right: 2.5rem;
  position: absolute;
}

.collapse-item--active {
  color: #333333;
  background: #304cad;

  & span {
    color: #ffffff;
  }
}

.collapse-item--selected {
  color: #333333;
  background: #304cad !important;

  & span {
    color: #ffffff;
  }
}

.content-collapse-options {
  width: 60%;
  height: 315px;
  border-radius: 5px;
  background: #ffffff;
  padding: 0.25rem 0.5rem;
  border: 1px solid #c6c6c6;
}

.head-collapse-parents-title {
  padding: 0.5rem 0.75rem;
  background: #f1f1f1;
  box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);

  & span {
    color: #072470;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }
}

.list-check-box {
  height: 100%;
  padding: 1.25rem 0 0 2.25rem;

  & > div {
    height: 85%;
    overflow-y: auto;
  }
}

.d-none {
  visibility: hidden;
}

.text-blue {
  color: blue;
}

.multiselect-input {
  width: 400px;
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--highlight {
  color: #ffffff;
  background-color: #072470;

  &::after {
    background-color: #072470;
  }
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--selected.multiselect__option--highlight {
  color: #ffffff;
  background-color: #ff6a6a;

  &::after {
    background-color: #ff6a6a;
  }
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--highlight
  > .option-label {
  color: #ffffff;
}

::v-deep
  .multiselect
  > .multiselect__tags
  > .multiselect__tags-wrap
  > .multiselect__tag {
  background-color: #072470 !important;

  & > .multiselect__tag-icon::after {
    color: #ffffff !important;
  }

  & > .multiselect__tag-icon:focus,
  .multiselect__tag-icon:hover {
    background-color: #ff6a6a !important;
  }
}

::v-deep .multiselect__option--group {
  font-size: 16px !important;
  color: #000000 !important;
  background-color: #ffffff !important;
}

::v-deep .option-label {
  font-size: 14px;
  margin-left: 10px;
  color: #333333;
}

.list-item-checked {
  display: flex;
  flex-direction: row;
  align-items: center;

  .icon-checked {
    width: 18px;
    height: 18px;
    cursor: pointer;
    margin-right: 10px;
    display: inline-block;
    border-radius: 0.25rem;
    background-color: #007bff;
    background-position: center;
    background-repeat: no-repeat;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
  }
}
</style>
