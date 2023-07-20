<template>
  <div class="display-user-management-list">
    <h1 class="border-left-title font-weight-bold">求人検索</h1>

    <div class="job-search-content">
      <h2 class="job-search-title">求人情報を詳細条件から探す</h2>

      <b-form @submit="onSubmit($event)" @reset="onReset($event)">
        <b-card-group deck>
          <b-card no-body>
            <b-list-group>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    {{ $t('WORKING_PLACE_2') }}
                  </b-col>

                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <button class="btn btn-plus-modal" @click="handleOpenModal">
                      <span>＋ 指定する</span>
                    </button>

                    <span class="file-name ml-4">特に指定しない</span>
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex flex-wrap">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    <span>{{ $t('INCOME') }}</span>
                  </b-col>

                  <b-col cols="9" class="my-2">
                    <b-row>
                      <b-col cols="5">
                        <b-form-select />
                      </b-col>

                      <b-col cols="1" class="p-0 d-flex align-items-center justify-content-center">
                        <span>〜</span>
                      </b-col>

                      <b-col cols="5">
                        <b-form-select />
                      </b-col>
                    </b-row>
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    <span>{{ $t('WORKING_PLACE_2') }}</span>
                  </b-col>

                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <multiselect
                      v-model="citySelected"
                      label="name_ja"
                      :multiple="true"
                      track-by="name_ja"
                      :options="cityList"
                      placeholder="都道府県を選ぶ"
                      :group-values="'options'"
                      :group-label="'timezone'"
                      :selected-label="'選択済み'"
                      :select-label="'選択するにはEnterキーを押してください'"
                      :deselect-label="'削除するにはEnterキーを押してください'"
                    >
                      <template slot="option" slot-scope="props">
                        <span v-if="props.option.$isLabel" class="group-label">{{ props.option.$groupLabel }}</span>
                        <span v-else class="option-label">{{ props.option.name_ja }}</span>
                      </template>

                      <template slot="noResult">
                        <span>「要素が見つかりません。検索クエリを変更してください。」となります。</span>
                      </template>
                    </multiselect>
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex flex-wrap">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    <span>{{ $t('LANGUAGE_REQUIREMENT') }}</span>
                  </b-col>

                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <b-form-checkbox class="mr-4" name="my-checkbox" value="1">N1</b-form-checkbox>
                    <b-form-checkbox class="mr-4" name="my-checkbox" value="2">N2</b-form-checkbox>
                    <b-form-checkbox class="mr-4" name="my-checkbox" value="3">N3</b-form-checkbox>
                    <b-form-checkbox class="mr-4" name="my-checkbox" value="4">N4</b-form-checkbox>
                    <b-form-checkbox class="mr-4" name="my-checkbox" value="5">N5</b-form-checkbox>
                    <b-form-checkbox class="mr-4" name="my-checkbox" value="5">問わない</b-form-checkbox>
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex flex-wrap">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    <span>{{ $t('JOB_EDIT.FORM_OF_EMPLOYMENT') }}</span>
                  </b-col>

                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <b-form-radio class="mr-4" name="my-radio" value="1">正社員</b-form-radio>
                    <b-form-radio class="mr-4" name="my-radio" value="2">契約社員</b-form-radio>
                    <b-form-radio class="mr-4" name="my-radio" value="3">派遣社員</b-form-radio>
                    <b-form-radio class="mr-4" name="my-radio" value="3">その他</b-form-radio>
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex flex-wrap">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    <span>こだわりポイント</span>
                  </b-col>

                  <b-col cols="9" class="d-flex align-items-center flex-wrap my-2">
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="1">住宅手当有</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="2">福利厚生充実</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="3">退職金有</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="4">年間休日120日以上</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="5">寮有</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="6">未経験可</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="7">外国人管理職登用実績有</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="6">リモート勤務可</b-form-checkbox>
                    <b-form-checkbox class="mr-4 mb-2" name="my-checkbox" value="6">引っ越し手当有</b-form-checkbox>
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex flex-wrap">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    <span>{{ $t('SEARCH_JOB_LIST.FREE_WORD') }}</span>
                  </b-col>

                  <b-col cols="9" class="my-2">
                    <b-form-input v-model="formData.freeWord" aria-label="" @input="handleChangeForm($event, 'freeWord')" />

                    <b-form-invalid-feedback :state="error.freeWord">
                      <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
            </b-list-group>
          </b-card>
        </b-card-group>

        <div class="search-job-btns d-flex flex-column align-items-center">
          <div class="btn btn-search-vs-conditions" @click="handleSearch">
            <span>{{ $t('SEARCH_JOB_LIST.SEARCH_WITH_CONDITIONS') }}</span>
            <img :src="require('@/assets/images/login/chervon-right-white.png')" alt="collapse">
          </div>

          <div class="btn btn-clear-settings" @click="handleClearSettingsFormData">
            <span>{{ $t('BUTTON.CLEAR_SETTINGS') }}</span>
          </div>
        </div>
      </b-form>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect';

// import {
//   getAllUserManagement,
//   deleteUserManagement,
//   deleteAllUserManagement,
// } from '@/api/modules/userManagement';
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import { validEmptyOrWhiteSpace } from '@/utils/validate';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };

export default {
  name: 'JobSearch',
  components: { Multiselect },
  data() {
    return {
      options: null,

      noSort: true,

      checkbox: false,
      checked1: false,
      checked2: false,
      checked3: false,
      checked4: false,
      checked5: false,
      checked6: false,
      checked7: false,
      checked8: false,

      listId: [],

      closeMess: true,

      showModal: false,

      selectAll: false,

      message: {
        isShowMessage: false,
        isMessage: '',
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      queryData: {
        page: 1,
        per_page: 20,
        total_records: 0,
        search: '',
        order_type: '',
        order_column: '',
      },

      formData: {
        occupation: '',
        company_name: '',
        application_period: '',
        application_period_start: '',
        application_period_end: '',
        job_description: '',
        application_condition: '',
        application_requirement: '',
        country: '',
        language_requirement: '',
        other_skill: '',
        preferred_skill: '',
        form_of_employment: '',
        working_time: '',
        vacation: '',
        expected_income: '',
        working_place: '',
        working_place_detail: '',
        treatment_welfare: '',
        company_pr_appeal: '',
        bonus_pay: '',
        overtime: '',
        transfer: '',
        passive_smoking: '',
        passion: '',
        interview_follow: '',
        freeWord: '',
      },

      error: {
        occupation: null,
        company_name: null,
        application_period: null,
        application_period_start: null,
        application_period_end: null,
        job_description: null,
        application_condition: null,
        application_requirement: null,
        country: null,
        language_requirement: null,
        other_skill: null,
        preferred_skill: null,
        form_of_employment: null,
        working_time: null,
        vacation: null,
        expected_income: null,
        working_place: null,
        working_place_detail: null,
        treatment_welfare: null,
        company_pr_appeal: null,
        bonus_pay: null,
        overtime: null,
        transfer: null,
        passive_smoking: null,
        passion: null,
        interview_follow: null,
      },

      reRender: 0,

      fields: [
        { key: 'choose', sortable: false, label: '', class: 'choose' },
        { key: 'id', sortable: true, label: 'ID', class: 'id' },
        { key: 'hr_name', sortable: true, label: 'HR NAME', class: 'hr_name' },
        {
          key: 'company_name',
          sortable: true,
          label: 'COMPANY NAME',
          class: 'company_name',
        },
        { key: 'status', sortable: false, label: 'STATUS', class: 'status' },
        {
          key: 'updating_date',
          sortable: false,
          label: 'UPDATE DATE',
          class: 'updating_date',
        },
        { key: 'detail', sortable: false, label: 'DETAIL', class: 'detail' },
      ],

      items: [
        {
          id: '0000001',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集中',
          selected: false,
          detail: '募集中',
        },
        {
          id: '0000002',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '一時停止中',
          selected: false,
          detail: '一時停止中',
        },
        {
          id: '0000003',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000004',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000005',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000006',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000007',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
      ],

      occupationOption: [
        {
          id: 1,
          title: '北海道・東北',
          translate: 'Agriculture/Forestry/Fisheries',
        },
        { id: 2, title: '関東', translate: 'Construction industry' },
        { id: 3, title: '北陸・甲信越', translate: 'manufacturing industry' },
        {
          id: 4,
          title: '東海',
          translate: 'Electricity, gas, water industry',
        },
        {
          id: 5,
          title: '関西',
          translate: 'Information and communication industry',
        },
        {
          id: 6,
          title: '中国',
          translate: 'Transportation and postal services',
        },
        { id: 7, title: '四国', translate: 'Wholesale/Retail' },
        { id: 8, title: '四九州・沖縄国', translate: 'Wholesale/Retail' },
      ],

      selectedItemIndex: -1,

      activedItemIndex: [],

      saveConditionPopup: [],

      jobItem: {},

      allChildSelected: {
        'position_-1': false,
      },

      selectedOptions: {
        'position_-1': [],
      },

      childOption: [],

      optionChildDetail: [
        {
          agriculture_forestry_fisheries_options: [
            {
              text: '農業・林業・漁業 1',
              key: 1,
              value: '住宅手当有',
              translate: ' Housing allowance',
            },
            {
              text: '農業・林業・漁業 2',
              key: 2,
              value: '福利厚生充実',
              translate: 'Welfare enhancement',
            },
            {
              text: '農業・林業・漁業 3',
              key: 3,
              value: '福利厚生充実',
              translate: 'Welfare enhancement',
            },
            {
              text: '農業・林業・漁業 4',
              key: 4,
              value: '福利厚生充実',
              translate: 'Welfare enhancement',
            },
          ],
        },
        {
          construction_industry_options: [
            {
              text: '建設業 1',
              key: 1,
              value: '建設業 1',
              translate: ' Housing allowance',
            },
            {
              text: '建設業 2',
              key: 2,
              value: '建設業 2',
              translate: 'Welfare enhancement',
            },
            {
              text: '建設業 3',
              key: 3,
              value: '建設業 3',
              translate: 'Welfare enhancement',
            },
            {
              text: '建設業 4',
              key: 4,
              value: '建設業 4',
              translate: 'Welfare enhancement',
            },
            {
              text: '建設業 5',
              key: 5,
              value: '建設業 5',
              translate: 'Welfare enhancement',
            },
          ],
        },
        {
          manufacturing_industry__options: [
            {
              text: '製造業 1',
              key: 1,
              value: '製造業 3',
              translate: ' manufacturing industry',
            },
            {
              text: '製造業 2',
              key: 2,
              value: '製造業 3',
              translate: ' manufacturing industry',
            },
            {
              text: '製造業 3',
              key: 3,
              value: '製造業 3',
              translate: ' manufacturing industry',
            },
            {
              text: '製造業 4',
              key: 4,
              value: '製造業 3',
              translate: ' manufacturing industry',
            },
          ],
        },
        {
          electricity_gas_water_industry__options: [
            {
              text: '電気・ガス・水道業 1',
              key: 1,
              value: '住宅手当有 1',
              translate: ' Housing allowance',
            },
            {
              text: '電気・ガス・水道業 2',
              key: 2,
              value: '住宅手当有 2',
              translate: ' Housing allowance',
            },
            {
              text: '電気・ガス・水道業 3',
              key: 3,
              value: '住宅手当有 3',
              translate: ' Housing allowance',
            },
            {
              text: '電気・ガス・水道業 4',
              key: 4,
              value: '住宅手当有 4',
              translate: ' Housing allowance',
            },
          ],
        },
        {
          information_and_communication_industry_options: [
            {
              text: '情報通信業 1',
              key: 1,
              value: '情報通信業 1',
              translate: ' Housing allowance',
            },
            {
              text: '情報通信業 2',
              key: 2,
              value: '情報通信業 2',
              translate: ' Housing allowance',
            },
            {
              text: '情報通信業 3',
              key: 3,
              value: '情報通信業 3',
              translate: ' Housing allowance',
            },
            {
              text: '情報通信業 4',
              key: 4,
              value: '情報通信業 4',
              translate: ' Housing allowance',
            },
          ],
        },
        {
          transportation_and_postal_services_options: [
            {
              text: '運輸業・郵便業 1',
              key: 1,
              value: '運輸業・郵便業 1',
              translate: ' Housing allowance',
            },
            {
              text: '運輸業・郵便業 2',
              key: 2,
              value: '運輸業・郵便業 2',
              translate: 'Welfare enhancement',
            },
          ],
        },
        {
          wholesale_retail_ptions_options: [
            {
              text: '卸売業・小売業 1',
              key: 1,
              value: '卸売業・小売業 1',
              translate: ' Housing allowance',
            },
            {
              text: '卸売業・小売業 2',
              key: 2,
              value: '卸売業・小売業 2',
              translate: 'Welfare enhancement',
            },
          ],
        },
      ],

      // cityOptions: [
      //   {
      //     id: 1,
      //     name_en: 'Hokkaido',
      //     name_ja: '北海道',
      //   },
      //   {
      //     id: 2,
      //     name_en: 'Aomori',
      //     name_ja: '青森県',
      //   },
      //   {
      //     id: 3,
      //     name_en: 'Iwate',
      //     name_ja: '岩手県',
      //   },
      //   {
      //     id: 4,
      //     name_en: 'Miyagi',
      //     name_ja: '宮城県',
      //   },
      //   {
      //     id: 5,
      //     name_en: 'Akita',
      //     name_ja: '秋田県',
      //   },
      //   {
      //     id: 6,
      //     name_en: 'Yamagata',
      //     name_ja: '山形県',
      //   },
      //   {
      //     id: 7,
      //     name_en: 'Fukushima',
      //     name_ja: '福島県',
      //   },
      //   {
      //     id: 8,
      //     name_en: 'Ibaraki',
      //     name_ja: '茨城県',
      //   },
      //   {
      //     id: 9,
      //     name_en: 'Tochigi',
      //     name_ja: '栃木県',
      //   },
      //   {
      //     id: 10,
      //     name_en: 'Gunma',
      //     name_ja: '群馬県',
      //   },
      //   {
      //     id: 11,
      //     name_en: 'Saitama',
      //     name_ja: '埼玉県',
      //   },
      //   {
      //     id: 12,
      //     name_en: 'Chiba',
      //     name_ja: '千葉県',
      //   },
      //   {
      //     id: 13,
      //     name_en: 'Tokyo',
      //     name_ja: '東京都',
      //   },
      //   {
      //     id: 14,
      //     name_en: 'Kanagawa',
      //     name_ja: '神奈川県',
      //   },
      //   {
      //     id: 15,
      //     name_en: 'Niigata',
      //     name_ja: '新潟県',
      //   },
      //   {
      //     id: 16,
      //     name_en: 'Toyama',
      //     name_ja: '富山県',
      //   },
      //   {
      //     id: 17,
      //     name_en: 'Ishikawa',
      //     name_ja: '石川県',
      //   },
      //   {
      //     id: 18,
      //     name_en: 'Fukui',
      //     name_ja: '福井県',
      //   },
      //   {
      //     id: 19,
      //     name_en: 'Yamanashi',
      //     name_ja: '山梨県',
      //   },
      //   {
      //     id: 20,
      //     name_en: 'Nagano',
      //     name_ja: '長野県',
      //   },
      //   {
      //     id: 21,
      //     name_en: 'Gifu',
      //     name_ja: '岐阜県',
      //   },
      //   {
      //     id: 22,
      //     name_en: 'Shizuoka',
      //     name_ja: '静岡県',
      //   },
      //   {
      //     id: 23,
      //     name_en: 'Aichi',
      //     name_ja: '愛知県',
      //   },
      //   {
      //     id: 24,
      //     name_en: 'Mie',
      //     name_ja: '三重県',
      //   },
      //   {
      //     id: 25,
      //     name_en: 'Shiga',
      //     name_ja: '滋賀県',
      //   },
      //   {
      //     id: 26,
      //     name_en: 'Kyoto',
      //     name_ja: '京都府',
      //   },
      //   {
      //     id: 27,
      //     name_en: 'Osaka',
      //     name_ja: '大阪府',
      //   },
      //   {
      //     id: 28,
      //     name_en: 'Hyogo',
      //     name_ja: '兵庫県',
      //   },
      //   {
      //     id: 29,
      //     name_en: 'Nara',
      //     name_ja: '奈良県',
      //   },
      //   {
      //     id: 30,
      //     name_en: 'Wakayama',
      //     name_ja: '和歌山県',
      //   },
      //   {
      //     id: 31,
      //     name_en: 'Tottori',
      //     name_ja: '鳥取県',
      //   },
      //   {
      //     id: 32,
      //     name_en: 'Shimane',
      //     name_ja: '島根県',
      //   },
      //   {
      //     id: 33,
      //     name_en: 'Okayama',
      //     name_ja: '岡山県',
      //   },
      //   {
      //     id: 34,
      //     name_en: 'Hiroshima',
      //     name_ja: '広島県',
      //   },
      //   {
      //     id: 35,
      //     name_en: 'Yamaguchi',
      //     name_ja: '山口県',
      //   },
      //   {
      //     id: 36,
      //     name_en: 'Tokushima',
      //     name_ja: '徳島県',
      //   },
      //   {
      //     id: 37,
      //     name_en: 'Kagawa',
      //     name_ja: '香川県',
      //   },
      //   {
      //     id: 38,
      //     name_en: 'Ehime',
      //     name_ja: '愛媛県',
      //   },
      //   {
      //     id: 39,
      //     name_en: 'Kochi',
      //     name_ja: '高知県',
      //   },
      //   {
      //     id: 40,
      //     name_en: 'Fukuoka',
      //     name_ja: '福岡県',
      //   },
      //   {
      //     id: 41,
      //     name_en: 'Saga',
      //     name_ja: '佐賀県',
      //   },
      //   {
      //     id: 42,
      //     name_en: 'Nagasaki',
      //     name_ja: '長崎県',
      //   },
      //   {
      //     id: 43,
      //     name_en: 'Kumamoto',
      //     name_ja: '熊本県',
      //   },
      //   {
      //     id: 44,
      //     name_en: 'Ōita',
      //     name_ja: '大分県',
      //   },
      //   {
      //     id: 45,
      //     name_en: 'Miyazaki',
      //     name_ja: '宮崎県',
      //   },
      //   {
      //     id: 46,
      //     name_en: 'Kagoshima',
      //     name_ja: '鹿児島県',
      //   },
      //   {
      //     id: 47,
      //     name_en: 'Okinawa',
      //     name_ja: '沖縄県',
      //   },
      // ],

      citySelected: [],
      cityList: [
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
    };
  },
  computed: {
    listUser() {
      return this.$store.getters.listUser;
    },
    currChange() {
      return this.queryData.page;
    },
  },
  watch: {
    currChange() {
      this.getListAllData();
    },
    items: {
      handler(newVal, oldVal) {
        const allSelected = newVal.every((item) => item.selected);
        if (allSelected !== this.selectAll) {
          this.selectAll = allSelected;
        }
      },
      deep: true,
    },
  },
  created() {
    this.getListAllData();
  },
  methods: {
    addTag(newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000),
      };
      this.options.push(tag);
      this.value.push(tag);
    },
    async getListAllData(e) {
      // const Search = {
      //   search: this.queryData.search,
      //   order_column: this.queryData.order_column,
      //   order_type: this.queryData.order_type,
      //   page: this.queryData.page,
      //   per_page: this.queryData.per_page,
      // };
      // await getAllUserManagement(`${urlAPI.urlGetLisUser}?${obj2Path(Search)}`)
      //   .then((res) => {
      //     if (res.data.data) {
      //       const listUser = res.data.data;
      //       this.queryData.total_records = res.data.total;
      //       this.$store.dispatch('userManagement/saveListUser', listUser);
      //     }
      //   })
      //   .catch((error) => {
      //     MakeToast({
      //       variant: 'warning',
      //       title: this.$t('DANGER'),
      //       content: error.message,
      //     });
      //     this.overlay.show = false;
      //   });
    },
    fillterListUser($event) {
      this.overlay.show = true;
      this.getListAllData($event);
    },
    selectAllItem() {
      console.log('selectAllItem');
    },
    selectItem(id) {
      if (this.listId.includes(id) && this.listId.length > 0) {
        this.listId.splice(this.listId.indexOf(id), 1);
      } else {
        this.listId.push(id);
      }
    },
    showDelete() {
      if (this.listId.length > 0) {
        this.showModal = true;
      } else {
        this.closeMess = true;
        this.message.isShowMessage = true;
        this.checkbox = true;
        this.getListAllData();
      }
    },
    createNewUser() {
      this.$router.push('/usermanagement/create');
    },
    goToEditScreen(id) {
      this.$router.push(
        { path: `/usermanagement/edit/${id}` },
        (onAbort) => {}
      );
    },
    async confirmationForm(id, name) {
      this.$bvModal
        .msgBoxConfirm(
          this.$t('MODAL_MESSAGE_CONFIRM_DELETE', { name: name }),
          {
            title: this.$t('MODAL_CONFIRM_DELETE'),
            okVariant: 'danger',
            okTitle: this.$t('MODAL_BUTTON_DELETE'),
            cancelVariant: 'secondary ',
            cancelTitle: this.$t('MODAL_BUTTON_CANCEL'),
            centered: true,
            size: 'lg',
          }
        )
        .then((value) => {
          this.confirmStatus = value;
          if (this.confirmStatus === true) {
            // deleteUserManagement(`${urlAPI.urlGetLisUser}/${id}`).then(() => {
            //   MakeToast({
            //     variant: 'success',
            //     title: this.$t('SUCCESS'),
            //     content: this.$t('CONTENT_SUSS'),
            //   });
            //   this.reRender++;
            //   this.getListAllData();
            // });
          }
          this.getListAllData();
        });
    },
    confirmClose() {
      this.checkbox = false;
      this.closeMess = false;
      this.getListAllData();
    },
    sortingChanged(ctx) {
      this.queryData.order_column =
        ctx.sortBy === 'role[0].name' ? 'role[0].name' : ctx.sortBy;
      this.queryData.order_column = ctx.sortBy === 'name' ? 'name' : ctx.sortBy;
      this.queryData.order_column =
        ctx.sortBy === 'email' ? 'email' : ctx.sortBy;
      this.queryData.order_type = ctx.sortDesc === true ? 'ASC' : 'DESC';
      this.getListAllData();
    },
    async deleteAll() {
      // await deleteAllUserManagement(urlAPI.urlDeleAll, {
      //   id: this.listId,
      // }).then(() => {
      //   MakeToast({
      //     variant: 'success',
      //     title: this.$t('SUCCESS'),
      //     content: this.$t('CONTENT_SUSS'),
      //   });
      //   this.listId.length = 0;
      //   this.reRender++;
      //   this.showModal = false;
      //   this.getListAllData();
      // });
    },
    handleCreate() {
      if (this.handleValidate(this.formData)) {
        console.log('xong');
      }
    },
    handleValidate(formData) {
      for (const x in formData) {
        if (!validEmptyOrWhiteSpace(formData[x])) {
          return true;
        }
      }

      // MakeToast({
      //   variant: 'warning',
      //   title: this.$t('WARNING'),
      //   content: 'Trường 1 cần required',
      // });

      return false;
    },
    checkvalidate() {
      if (this.formData.occupation === '') {
        this.error.occupation = false;
      }
      if (this.formData.company_name === '') {
        this.error.company_name = false;
      }

      if (this.formData.job_description === '') {
        this.error.job_description = false;
      }

      if (this.formData.application_condition === '') {
        this.error.application_condition = false;
      }

      if (this.formData.working_time === '') {
        this.error.working_time = false;
      }

      if (this.formData.expected_income === '') {
        this.error.expected_income = false;
      }

      if (this.formData.application_requirement === '') {
        this.error.application_requirement = false;
      }

      if (this.formData.preferred_skill === '') {
        this.error.preferred_skill = false;
      }

      if (this.formData.other_skill === '') {
        this.error.other_skill = false;
      }

      if (this.formData.vacation === '') {
        this.error.vacation = false;
      }

      if (this.formData.working_place_detail === '') {
        this.error.working_place_detail = false;
      }

      if (this.formData.treatment_welfare === '') {
        this.error.treatment_welfare = false;
      }

      if (this.formData.company_pr_appeal === '') {
        this.error.company_pr_appeal = false;
      }

      if (this.formData.country === '') {
        this.error.country = false;
      }

      if (this.formData.working_place === '') {
        this.error.working_place = false;
      }

      if (this.formData.interview_follow === '') {
        this.error.interview_follow = false;
      }

      // if (!this.formData.email || !this.checkEmail(this.formData.email)) {
      //   this.error.email = false;
      // }
      // if (!this.formData.password || this.formData.password.length < 8) {
      //   this.error.password = false;
      // }
      // if (this.formData.role_id === '' && this.roleId === this.headQuarter) {
      //   this.error.role_id = false;
      // }
      // if (
      //   this.formData.department_id === '' &&
      //   this.roleId === this.headQuarter &&
      //   this.formData.role_id === this.department
      // ) {
      //   this.error.department_id = false;
      // }
      // if (
      //   this.formData.department_id === '' &&
      //   this.formData.role_id === this.headQuarter
      // ) {
      //   this.error.department_id = null;
      // }
      // if (this.roleId !== this.headQuarter) {
      //   delete this.error['role_id'];
      //   delete this.error['department_id'];
      // }
      // if (this.formData.role_id === this.headQuarter && !this.formData.department_id) {
      //   delete this.error['department_id'];
      // }
      // if (Object.keys(this.error).every(k => this.error[k] === false)) {
      //   return false;
      // }
      // if (
      //   Object.keys(this.error).every(k => this.error[k] === null) &&
      //   this.roleId === this.headQuarter
      // ) {
      //   return false;
      // }
      // if (Object.keys(this.error).every(k => this.error[k] === true)) {
      //   return true;
      // }
    },
    onSubmit(e) {
      e.preventDefault();
      this.checkvalidate();
      if (this.checkvalidate() === true) {
        console.log('Vào đây');
      } else {
        e.stopPropagation();
      }
    },
    handleChangeForm(event, field) {
      const newValue = event;
      switch (field) {
        case 'occupation':
          this.error.occupation = null;
          if (newValue) {
            this.error.occupation = true;
          } else {
            this.error.occupation = false;
          }
          break;

        case 'company_name':
          this.error.company_name = null;
          if (newValue) {
            this.error.company_name = true;
          } else {
            this.error.company_name = false;
          }
          break;

        case 'job_description':
          this.error.job_description = null;
          if (newValue) {
            this.error.job_description = true;
          } else {
            this.error.job_description = false;
          }
          break;

        case 'application_condition':
          this.error.application_condition = null;
          if (newValue) {
            this.error.application_condition = true;
          } else {
            this.error.application_condition = false;
          }
          break;

        case 'application_requirement':
          this.error.application_requirement = null;
          if (newValue) {
            this.error.application_requirement = true;
          } else {
            this.error.application_requirement = false;
          }
          break;

        case 'other_skill':
          this.error.other_skill = null;
          if (newValue) {
            this.error.other_skill = true;
          } else {
            this.error.other_skill = false;
          }
          break;

        case 'preferred_skill':
          this.error.preferred_skill = null;
          if (newValue) {
            this.error.preferred_skill = true;
          } else {
            this.error.preferred_skill = false;
          }
          break;

        case 'vacation':
          this.error.vacation = null;
          if (newValue) {
            this.error.vacation = true;
          } else {
            this.error.vacation = false;
          }
          break;

        case 'working_place_detail':
          this.error.working_place_detail = null;
          if (newValue) {
            this.error.working_place_detail = true;
          } else {
            this.error.working_place_detail = false;
          }
          break;

        case 'treatment_welfare':
          this.error.treatment_welfare = null;
          if (newValue) {
            this.error.treatment_welfare = true;
          } else {
            this.error.treatment_welfare = false;
          }
          break;

        case 'company_pr_appeal':
          this.error.company_pr_appeal = null;
          if (newValue) {
            this.error.company_pr_appeal = true;
          } else {
            this.error.company_pr_appeal = false;
          }
          break;

        case 'expected_income':
          this.error.expected_income = null;
          if (newValue) {
            this.error.expected_income = true;
          } else {
            this.error.expected_income = false;
          }
          break;

        case 'working_time':
          this.error.working_time = null;
          if (newValue) {
            this.error.working_time = true;
          } else {
            this.error.working_time = false;
          }
          break;

        case 'working_place':
          this.error.working_place = null;
          if (newValue) {
            this.error.working_place = true;
          } else {
            this.error.working_place = false;
          }
          break;

        case 'country':
          this.error.country = null;
          if (newValue) {
            this.error.country = true;
          } else {
            this.error.country = false;
          }
          break;

        case 'interview_follow':
          this.error.interview_follow = null;
          if (newValue) {
            this.error.interview_follow = true;
          } else {
            this.error.interview_follow = false;
          }
          break;

        default:
          break;
      }
    },
    handleClearSettingsFormData() {
      Object.assign(this.formData, {
        occupation: '',
        income_from: '',
        income_to: '',
        free_word: '',
        working_place: '',
        language_requirement: [],
        form_employee: [],
        passion: [],
      });
    },
    handleClearSettingsModal() {
      this.allChildSelected = {};
      this.selectedOptions = {};
      this.saveConditionPopup = [];
      // Object.assign(this.allChildSelected, {
      //   'position_-1': '',
      // });
      // Object.assign(this.selectedOptions, {
      //   'position_-1': [],
      // });
    },
    changeChildCheckbox() {
      const isAllSelect =
        this.selectedOptions[`position_${this.selectedItemIndex}`].length ===
        this.childOption.length;
      this.allChildSelected[`position_${this.selectedItemIndex}`] = isAllSelect;
    },
    async handleSelectOccupation(index, item) {
      this.jobItem = item;
      this.selectedItemIndex = index;
      for (const key in this.selectedOptions) {
        // lấy ra key của những thằng nào khác không
        if (this.selectedOptions[key].length > 0) {
          const index = Number(key.charAt(9));
          if (!this.activedItemIndex.includes(index)) {
            this.activedItemIndex.push(index);
          }
        } else {
          const index = Number(key.charAt(9));
          // console.log('lấy ra được những key có là 0==>', index);
          this.activedItemIndex = this.activedItemIndex.filter(
            (item) => item !== index
          );
        }
      }
      if (!this.allChildSelected[`position_${this.selectedItemIndex}`]) {
        this.$set(
          this.allChildSelected,
          `position_${this.selectedItemIndex}`,
          false
        );
      }
      if (!this.selectedOptions[`position_${this.selectedItemIndex}`]) {
        this.$set(
          this.selectedOptions,
          `position_${this.selectedItemIndex}`,
          []
        );
      }
      switch (index) {
        case 0:
          this.childOption = this.optionChildDetail[index][
            'agriculture_forestry_fisheries_options'
          ].map((item) => {
            return item.text;
          });
          break;
        case 1:
          this.childOption = this.optionChildDetail[index][
            'construction_industry_options'
          ].map((item) => {
            return item.text;
          });
          break;
        case 2:
          this.childOption = this.optionChildDetail[index][
            'manufacturing_industry__options'
          ].map((item) => {
            return item.text;
          });
          break;
        case 3:
          this.childOption = this.optionChildDetail[index][
            'electricity_gas_water_industry__options'
          ].map((item) => {
            return item.text;
          });
          break;
        case 4:
          this.childOption = this.optionChildDetail[index][
            'information_and_communication_industry_options'
          ].map((item) => {
            return item.text;
          });
          break;
        case 5:
          this.childOption = this.optionChildDetail[index][
            'transportation_and_postal_services_options'
          ].map((item) => {
            return item.text;
          });
          break;
        case 6:
          this.childOption = this.optionChildDetail[index][
            'wholesale_retail_ptions_options'
          ].map((item) => {
            return item.text;
          });
          break;
        default:
          break;
      }
    },
    async handleSelectAllChild(checked) {
      if (checked) {
        this.$set(this.selectedOptions, `position_${this.selectedItemIndex}`, [
          ...this.childOption,
        ]);
      } else {
        this.$set(
          this.selectedOptions,
          `position_${this.selectedItemIndex}`,
          []
        );
      }
    },
    handleReflectContent(jobItem) {
      this.showModal = false;
      const existItem = this.saveConditionPopup.find(
        (item) => item?.id === jobItem.id
      );
      const jobItemWithChild = {
        ...jobItem,
        conditions: [
          ...this.selectedOptions[`position_${this.selectedItemIndex}`],
        ],
      };
      // lọc object
      // console.log('giờ nó lại lài gì', this.selectedOptions);
      for (const key in this.selectedOptions) {
        // lấy ra key của những thằng nào khác không
        if (this.selectedOptions[key].length > 0) {
          const index = Number(key.charAt(9));
          if (!this.activedItemIndex.includes(index)) {
            this.activedItemIndex.push(index);
          }
        } else {
          const index = Number(key.charAt(9));
          // console.log('lấy ra được những key có là 0==>', index);
          this.activedItemIndex = this.activedItemIndex.filter(
            (item) => item !== index
          );
        }
      }

      if (existItem) {
        this.saveConditionPopup = this.saveConditionPopup.map((item) =>
          item.id === existItem.id ? jobItemWithChild : item
        );
      } else {
        this.saveConditionPopup.push(jobItemWithChild);
      }
      console.log('lúc đầu', this.saveConditionPopup);
      // Kiểm tra this.saveConditionPopup
      this.saveConditionPopup = this.saveConditionPopup.filter((item) => {
        return item.conditions.length > 0;
      });
      console.log('lúc sau', this.saveConditionPopup);
      console.log('XXXX', this.activedItemIndex);
      // if (jobItemWithChild.conditions.length === 0) {
      //   console.log('đi vào đây');
      //   return;
      // }
    },
    handleBack() {
      this.$router.push('/job/list');
    },
    handleSearch() {
      console.log('formData lúc này', this.formData);
      this.$router.push('/job-search/list');
    },
    handleOpenModal() {
      this.showModal = true;
    },
    handleCloseModal() {
      this.showModal = false;
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss" scoped>

@import '@/scss/_variables.scss';

.job-search-content {
  border: 1px solid #999;
  padding: 20px;
}

.bg-gray {
  background-color: #f8f8f8;
}

.p-0 {
  padding: 0 !important;
}

.badge-required {
  color: red;
  border: 1px solid red;
  width: 28px;
  font-size: 8px;
  height: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.badge-not-required {
  color: #999;
  border: 1px solid #999;
  width: 28px;
  font-size: 8px;
  height: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
}

textarea.form-control {
  display: flex;
  flex: none;
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
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: stretch;
  gap: 1.25rem;
}

.content-modal-options {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1.5rem;
}

.collapse-parents {
  width: 40%;
  height: 315px;
  overflow-y: auto;
}

.collapse-item {
  background: #ffffff;
  border: 1px solid #c6c6c6;
  border-top: none;
  border-radius: 0 !important;
  padding: 1.076rem;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  position: relative;
  &:first-child {
    border-radius: 5px 5px 0 0 !important;
    border-top: 1px solid #c6c6c6;
  }
  & span {
    font-weight: 700;
    font-size: 14px;
    line-height: 17px;
    color: #333333;
  }

  & img:last-child {
    position: absolute;
    top: 1rem;
    right: 1rem;
  }
}

.checked {
  height: 17px;
  position: absolute;
  top: 1.1rem;
  right: 2.5rem;
}

.collapse-item--active {
  background: #304cad;
  color: #333333;

  & span {
    color: #ffffff;
  }
}

.collapse-item--selected {
  background: #304cad !important;
  color: #333333;

  & span {
    color: #ffffff;
  }
}

.content-collapse-options {
  background: #ffffff;
  border: 1px solid #c6c6c6;
  border-radius: 5px;
  width: 60%;
  padding: 0.25rem 0.5rem;
  height: 315px;
}

.head-collapse-parents-title {
  padding: 0.5rem 0.75rem;
  background: #f1f1f1;
  box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);

  & span {
    font-weight: 700;
    font-size: 14px;
    line-height: 17px;
    color: #072470;
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

::v-deep .multiselect > .multiselect__content-wrapper > .multiselect__content > .multiselect__element > .multiselect__option--highlight {
  color: #FFFFFF;
  background-color: #072470;

  &::after {
    background-color: #072470;
  }
}

::v-deep .multiselect > .multiselect__content-wrapper > .multiselect__content > .multiselect__element > .multiselect__option--selected.multiselect__option--highlight {
  color: #FFFFFF;
  background-color: #ff6a6a;

  &::after {
    background-color: #ff6a6a;
  }
}

::v-deep .multiselect > .multiselect__content-wrapper > .multiselect__content > .multiselect__element > .multiselect__option--highlight > .option-label {
  color: #FFFFFF;
}

::v-deep .multiselect > .multiselect__tags > .multiselect__tags-wrap > .multiselect__tag {
  background-color: #072470 !important;

  & > .multiselect__tag-icon::after {
    color: #FFFFFF !important;
  }

  & > .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
    background-color: #ff6a6a !important;
  }
}

::v-deep .multiselect__option--group {
  font-size: 16px !important;
  color: #000000 !important;
  background-color: #FFFFFF !important;
}

::v-deep .option-label {
  font-size: 14px;
  margin-left: 10px;
  color: #333333;
}
</style>
