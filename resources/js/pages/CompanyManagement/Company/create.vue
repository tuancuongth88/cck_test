<template>
  <div class="display-user-management-list">
    <b-row class="mb-2">
      <b-col cols="3" />
      <b-col cols="9" class="d-flex justify-content-between align-items-center">
        <b-col class="border-left-title font-weight-bold">{{
          '新規登録/New registration'
        }}</b-col>
        <div>
          <b-button
            variant="outline-dark mx-1"
          >{{ '一覧に戻る/Back to list' }}
          </b-button>
          <b-button
            type="submit"
            variant="warning"
            class="text-white mx-1"
            @click="onSubmit($event)"
          >{{ '保存/save' }}</b-button>
        </div>
      </b-col>
    </b-row>
    <b-row class="mb-4">
      <b-col cols="12">
        <b-form @submit="onSubmit($event)" @reset="onReset($event)">
          <b-card-group deck>
            <b-card header="求人情報">
              <b-list-group>
                <!-- Row 1 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >Occupation(title)
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="my-2">
                      <b-form-input
                        v-model="formData.occupation"
                        :class="error.occupation === false ? ' is-invalid' : ''"
                        @input="handleChangeForm($event, 'occupation')"
                      />
                      <b-form-invalid-feedback :state="error.occupation">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 2 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >企業名 company name
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-select>
                        <option value="1">Lựa chọn 1</option>
                        <option value="2">Lựa chọn 2</option>
                        <option value="3">Lựa chọn 3</option>
                      </b-select>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 3 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >募集期間Application period
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-col cols="5" class="p-0">
                        <b-form-input type="date" />
                      </b-col>
                      <b-col
                        cols="2"
                        class="p-0 d-flex align-items-center justify-content-center"
                      >
                        〜
                      </b-col>
                      <b-col cols="5" class="p-0">
                        <b-form-input type="date" />
                      </b-col>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 4 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >仕事内容Job description
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 5 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >応募条件/Application condition
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >応募要件/Application requirement
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >国/Country
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-input
                        aria-label=""
                        placeholder="選択してください"
                      />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >言語条件/Language requirement
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-checkbox
                        class="mr-4"
                        name="my-checkbox"
                        value="1"
                      >N1</b-form-checkbox>
                      <b-form-checkbox
                        class="mr-4"
                        name="my-checkbox"
                        value="2"
                      >N2</b-form-checkbox>
                      <b-form-checkbox
                        class="mr-4"
                        name="my-checkbox"
                        value="3"
                      >N3</b-form-checkbox>
                      <b-form-checkbox
                        class="mr-4"
                        name="my-checkbox"
                        value="4"
                      >N4</b-form-checkbox>
                      <b-form-checkbox
                        class="mr-4"
                        name="my-checkbox"
                        value="5"
                      >N5</b-form-checkbox>
                      <b-form-checkbox
                        class="mr-4"
                        name="my-checkbox"
                        value="5"
                      >問わない</b-form-checkbox>
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >その他必須スキル/Other skill
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >歓迎スキル/Preferred Skill
                      <b-badge
                        class="badge-required mx-2"
                        variant="secondary"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >雇用形態/form of employment
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-radio
                        class="mr-4"
                        name="my-radio"
                        value="1"
                      >正社員</b-form-radio>
                      <b-form-radio
                        class="mr-4"
                        name="my-radio"
                        value="2"
                      >契約社員</b-form-radio>
                      <b-form-radio
                        class="mr-4"
                        name="my-radio"
                        value="3"
                      >派遣社員</b-form-radio>
                      <b-form-radio
                        class="mr-4"
                        name="my-radio"
                        value="3"
                      >その他</b-form-radio>
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >勤務時間/Working time
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-col cols="5" class="p-0">
                        <b-form-select />
                      </b-col>
                      <b-col
                        cols="2"
                        class="p-0 d-flex align-items-center justify-content-center"
                      >
                        〜
                      </b-col>
                      <b-col cols="5" class="p-0">
                        <b-form-select />
                      </b-col>
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >休日・休暇/Vacation
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >想定年収/expected income
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-input />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >勤務地（都道府県）/working palace
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-select />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >勤務地 詳細/Working palace detail
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >待遇・福利厚生/ Treatment.Welfare
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >会社PR・魅力/Company PR.Appeal
                      <b-badge
                        class="badge-required mx-2"
                        variant="secondary"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-textarea />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >賞与・昇給の有無/Bonus.Pay rise
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="A"
                      >Option A</b-form-radio>
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="B"
                      >Option B</b-form-radio>
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >時間外労働の有無/Over time
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="A"
                      >Option A</b-form-radio>
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="B"
                      >Option B</b-form-radio>
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >転勤の有無/Transfer
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="A"
                      >Option A</b-form-radio>
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="B"
                      >Option B</b-form-radio>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!--  -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >受動喫煙の有無/Passive smoking</b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="A"
                      >Option A</b-form-radio>
                      <b-form-radio
                        class="mr-4"
                        name="some-radios"
                        value="B"
                      >Option B</b-form-radio>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!--  -->
                <b-list-group-item class="p-0">
                  <div class="d-flex w-100 justify-content-between">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >特徴/Passion</b-col>
                    <b-col
                      cols="9"
                      class="d-flex flex-wrap align-items-center my-2"
                    >
                      <b-badge
                        variant="primary mx-2 my-1"
                      >住宅手当有/Housing allowance</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >福利厚生充実/Welfare enhancement</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >退職金有/Severance pay</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >住宅手当有/Housing allowance</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >年間休日120日以上/120 or more annual holidays</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >寮有/Residence</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >未経験可/No experience ok</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >外国人管理職登用実績有</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >リモート勤務可/Remote work ok</b-badge>
                      <b-badge
                        variant="primary mx-2 my-1"
                      >引っ越し手当有/Moving allowance</b-badge>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!--  -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >面接フロー/Interview follow</b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-form-select
                        name="some-radios"
                        value="A"
                      >Option A</b-form-select>
                    </b-col>
                  </div>
                </b-list-group-item>
              </b-list-group>
            </b-card>
          </b-card-group>

          <b-card-group deck>
            <b-card header="企業情報/establishment year">
              <b-list-group>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >設立年度/capital</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="2010年" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >創業年度/proceeds</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="815億円" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >資本金等/number of staffs</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="1千万円" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >売上金/number of operatios</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="815億円" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >従業員数/number of shops</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="7,218名" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >事務所数/number of factories</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="20" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >店舗数</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="50" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >工場数/fiscal year</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="50" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >決算月</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="12月" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
              </b-list-group>
            </b-card>
          </b-card-group>
        </b-form>
      </b-col>
    </b-row>
  </div>
</template>

<script>
// import { getAllUserManagement, deleteUserManagement, deleteAllUserManagement } from '@/api/modules/userManagement';
import { MakeToast } from '../../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import { validEmptyOrWhiteSpace } from '@/utils/validate';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'CompanyCreate',
  components: {},
  data() {
    return {
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

      formData: {
        occupation: '',
      },

      error: {
        occupation: null,
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

  created() {
    this.getListAllData();
  },

  methods: {
    validateState(ref) {
      if (
        this.veeFields[ref] &&
        (this.veeFields[ref].dirty || this.veeFields[ref].validated)
      ) {
        return !this.veeErrors.has(ref);
      }
      return null;
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
            // deleteUserManagement(`${urlAPI.urlGetLisUser}/${id}`)
            //   .then(() => {
            //     MakeToast({
            //       variant: 'success',
            //       title: this.$t('SUCCESS'),
            //       content: this.$t('CONTENT_SUSS'),
            //     });
            //     this.reRender++;
            //     this.getListAllData();
            //   });
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
      // await deleteAllUserManagement(urlAPI.urlDeleAll, { id: this.listId })
      //   .then(() => {
      //     MakeToast({
      //       variant: 'success',
      //       title: this.$t('SUCCESS'),
      //       content: this.$t('CONTENT_SUSS'),
      //     });
      //     this.listId.length = 0;
      //     this.reRender++;
      //     this.showModal = false;
      //     this.getListAllData();
      //   });
    },

    handleCreate() {
      if (this.handleValidate(this.formData)) {
        console.log('xong');
      }
    },

    handleValidate(formData) {
      console.log('formData', formData);
      if (!validEmptyOrWhiteSpace(formData.occupation)) {
        return true;
      }

      MakeToast({
        variant: 'warning',
        title: this.$t('WARNING'),
        content: 'Trường 1 cần required',
      });

      return false;
    },

    checkvalidate() {
      console.log('đi vào checkvalidate');
      if (this.formData.occupation === '') {
        this.error.occupation = false;
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
      console.log('onSubmit');
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
      // const listValue = [1, 2, 3];
      // console.log('newvalue', newValue);

      switch (field) {
        // case 'role_id':
        //   if (newValue) {
        //     this.error.role_id = true;
        //     this.branchList = [];
        //     if (newValue === 2) {
        //       const departmentByRole = this.companyBranch.filter(
        //         x => x.role_id === newValue
        //       );
        //       departmentByRole.length > 0 &&
        //         departmentByRole.map(item => {
        //           departmentList.push({ value: item.id, text: item.name });
        //         });
        //       this.branchList = departmentList;
        //       this.author = false;
        //       this.error.department_id = null;
        //       this.form.department_id = '';
        //     } else {
        //       this.branchList = [];
        //       this.author = true;
        //       this.form.department_id = '';
        //       this.error.department_id = null;
        //     }
        //   } else {
        //     this.error.role_id = false;
        //     this.author = true;
        //     this.form.department_id = '';
        //   }
        //   break;
        case 'occupation':
          this.error.occupation = null;
          if (newValue) {
            this.error.occupation = true;
          } else {
            this.error.occupation = false;
          }
          break;
        // case 'name':
        //   if (newValue.length > 0) {
        //     this.error.name = true;
        //   } else {
        //     this.error.name = false;
        //   }
        //   break;
        // case 'email':
        //   if (newValue.length === 0 || !this.checkEmail(newValue)) {
        //     this.error.email = false;
        //   } else {
        //     this.error.email = true;
        //   }
        //   break;
        // case 'password':
        //   if (newValue.length > 7 && newValue.length < 17) {
        //     this.error.password = true;
        //   } else {
        //     this.error.password = false;
        //   }
        //   break;
        default:
          break;
      }
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
</style>
