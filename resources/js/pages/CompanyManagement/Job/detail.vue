<template>
  <div class="display-user-management-list">
    <b-row class="mb-2">
      <b-col
        cols="12"
        class="d-flex justify-content-between align-items-center"
      >
        <b-col class="border-left-title font-weight-bold">{{
          $t('JOB_DETAIL.JOB_DETAIL')
        }}</b-col>
        <div>
          <b-button variant="outline-dark mx-1" @click="handleBackList">{{
            $t('BUTTON.BACK_TO_LIST')
          }}</b-button>
          <b-button
            variant="warning"
            class="text-white mx-1"
            @click="handleGoToEdit"
          >{{ $t('EDIT') }}</b-button>
        </div>
      </b-col>
    </b-row>
    <b-row class="mb-4">
      <b-col cols="12">
        <b-card-group deck>
          <b-card :header="$t('TITLE.JOB_INFORMATION')">
            <b-list-group>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.TITLE') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >電気設計エンジニア
                  </b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.COMPANY_NAME') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >シティコンピュータ株式会社</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.APPLICATION_PERIOD') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >2023年3月1日 〜 2023年3月31日</b-col>
                </div>
              </b-list-group-item>
              <!-- Row 3+ 職種 occupation -->
              <b-list-group-item class="p-0">
                <div class="d-flex flex-wrap">
                  <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">
                    {{ $t('JOB_EDIT.OCCUPATION') }}
                  </b-col>
                  <b-col cols="9" class="w-100 d-flex flex-column justify-start align-center my-2">
                    <!-- 大分類 -->
                    <div class="form-item-row__inputs d-flex justify-center align-center mt-2">
                      <span>大分類</span>
                      <div class=" d-flex justify-start align-center">
                        <span>運送業・郵便業</span>
                      </div>
                    </div>
                    <!-- 中分類 -->
                    <div class="form-item-row__inputs d-flex justify-center align-center mt-2">
                      <span>中分類</span>
                      <div class=" d-flex justify-start align-center">
                        <span>貨物運送業</span>
                      </div>
                    </div>

                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.JOB_DESCRIPTION') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >....</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.APPLICATION_CONDITION') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >応募条件はこちらです。
                  </b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.APPLICATION_REQUIREMENT') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >応募要件はこちらです。
                  </b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.COUNTRY') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >ベトナム</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.REQUIRED_LANGUAGE_CONDITION') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >N1 N2 N3</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.PREFERRED_SKILL') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >中国語日常会話以上 <br>
                    その他言語</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.FORM_OF_EMPLOYMENT') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >正規雇用</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.WORKING_TIME') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >9:00〜17:00</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.VACATION') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    完全週休2日制（休日は土日祝日） 年間有給休暇14日〜20日
                    年間休日日数121日
                    年末年始休日（12/29〜1/3）、夏季休暇（6月〜10月の間で2日）等
                    入社初年度の有給休暇は入社時期に異なる（3〜12日）
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.EXPECTED_INCOME') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >600万円</b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.WORKING_PLACE') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >愛知県</b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.WORKING_PLACE_DETAIL') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    住所：愛知県岡崎市 勤務地最寄駅：名鉄本線 東岡崎駅 から
                    車25分
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.TREATMENT_WELFARE') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    通勤手当、健康保険、厚生年金保険、雇用保険、労災保険
                    ＜各手当・制度補足＞ 通勤手当：実費支給
                    社会保険：社会保険完備 ＜教育制度・資格補助補足＞
                    外部研修、通信教育・eラーニング、語学研修等への費用補助等
                    ＜その他補足＞ その他福利厚生・教育支援制度あり
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.COMPANY_PR_APPEAL') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    .....
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.BONUS_PAY_RISE') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    有
                  </b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.OVER_TIME') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    有
                  </b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.TRANSFER') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    有
                  </b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.PASSIVE_SMOKING') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    有
                  </b-col>
                </div>
              </b-list-group-item>

              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.PASSION') }}</b-col>
                  <b-col
                    cols="9"
                    class="d-flex flex-wrap align-items-center my-2"
                  >
                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_HOUSING_ALLOWANCE') }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_WELFARE_ENHANCEMENT') }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_SEVERANCE_PAY') }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{
                        $t('JOB_DETAIL.PASSION_CHILD_OR_MORE_ANNUAL_HOLIDAYS')
                      }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_RESIDENCE') }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_NO_EXPERIENCE_OK') }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_HIRING') }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_REMOTE_WORK_OK') }}
                    </b-form-checkbox>

                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ $t('JOB_DETAIL.PASSION_CHILD_MOVING_ALLOWANCE') }}
                    </b-form-checkbox>
                    <b-form-checkbox
                      name="check-button"
                      button
                      :button-variant="'info'"
                      class="secondary m-1 passion-checkbox"
                      size="sm"
                    >
                      {{ '週28時間以下OK' }}
                    </b-form-checkbox>
                  </b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.INTERVIEW_FOLLOW') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    一次面接→二次面接→三次面接→内定
                  </b-col>
                </div>
              </b-list-group-item>
            </b-list-group>
          </b-card>
        </b-card-group>
        <b-card-group deck class="mt-4">
          <b-card :header="$t('TITLE.COMPANY_INFORMATION')">
            <b-list-group>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.ESTABLISHMENT_YEAR') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >2010年</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.STARTUP_YEAR') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >2009年</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.CAPITAL') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >1千万円</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.PROCEEDS') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >815億円</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.NUMBER_OF_STAFFS') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >7,218名</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.NUMBER_OF_OPERATIONS') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >20</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.NUMBER_OF_SHOPS') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >50</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.NUMBER_OF_FACTORIES') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >50</b-col>
                </div>
              </b-list-group-item>
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('JOB_DETAIL.FISCAL_YEAR') }}
                  </b-col>
                  <b-col
                    cols="9"
                    class="d-flex align-items-center my-2"
                  >12月</b-col>
                </div>
              </b-list-group-item>
            </b-list-group>
          </b-card>
        </b-card-group>
      </b-col>
    </b-row>
  </div>
</template>

<script>
// import {
//   getAllUserManagement,
//   deleteUserManagement,
//   deleteAllUserManagement,
// } from '@/api/modules/userManagement';
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'JobDetail',
  components: {},
  data() {
    return {
      statusJob: '',
      noSort: true,
      checkbox: false,
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
        // Update selectAll based on items selected status
        const allSelected = newVal.every((item) => item.selected);
        if (allSelected !== this.selectAll) {
          this.selectAll = allSelected;
        }
      },
      deep: true,
    },
  },

  // mounted(){
  //   this.statusJob = this.$route.params.status;
  // },

  created() {
    this.statusJob = this.$route.query.status;
    this.getListAllData();
  },

  methods: {
    selectAllChanged() {
      // Update all items to match selectAll
      this.items.forEach((item) => {
        item.selected = this.selectAll;
      });
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

    goToDetail(id, status) {
      console.log('id', id);
      this.$router.push({ path: `/hr/detail/${id}` }, (onAbort) => {});
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

    ConfirmClose() {
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

    async DeleteAll() {
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

    handleGoToEdit() {
      this.$router.push({
        path: `/job/edit/${this.$route.params.id}`,
        query: { status: this.statusJob },
      });
    },
    handleBackList() {
      this.$router.push({ path: `/job/list` });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';

.border-left-title {
  border-left: 4px solid #314cad;
  height: 36px;
  line-height: 36px;
}

.bg-gray {
  background-color: #f8f8f8;
}

.p-0 {
  padding: 0 !important;
}

.form-item-row__inputs {
	display: flex;
	// padding: 0.75rem 1.25rem;
	align-items:  center;
	justify-content: center;
  & > span {
  //   border: 1px solid red;
    min-width: 140px;
    width: 20%;
  }

	& > div {
    width: 77%;
		height: 100%;
		display: flex;
		font-size: 14px;
		font-weight: 400;
		line-height: 21px;
		align-items: center;
		justify-content: flex-start;
	}
}
</style>
