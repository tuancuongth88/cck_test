<template>
  <div class="job-detail-for-hr">
    <div class="job-detail-for-hr-container">
      <div class="job-detail-for-hr-form">
        <div class="job-detail-for-hr-form__head">
          <div class="h-100 d-flex justify-start align-stretch">
            <div class="line" />

            <div class="job-detail-for-hr-form__head-title pl-4">
              <span>{{ $t('JOB_ENTRY.TITLE') }}</span>
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
              <div class="form-page-area__head">
                <span style="font-weight: bold">{{
                  $t('JOB_ENTRY.ENTRY_HR')
                }}</span>
              </div>

              <div class="form-page-area__content">
                <div class="form-page-area-content-wrap">
                  <div class="form-item-row">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_ENTRY.FULL_NAME') }}</span>
                      </div>
                    </div>

                    <div class="form-item-row__inputs" style="padding: 1rem">
                      <div
                        class="entry-hr-select"
                        style="align-items: flex-start !important"
                      >
                        <div @click="handleToggleModalSelectHR()">
                          <ExtendBtn />
                        </div>

                        <div class="entry-hr-selected-list">
                          <div
                            v-for="(item, index) in listHrSelected"
                            :key="index"
                            class="entry-hr-selected-item"
                          >
                            <span
                              class="btn-effect"
                              @click="removeThisHr(item['id'])"
                            >
                              <i class="fas fa-times" />
                            </span>

                            <div class="entry-hr-selected-item__name">
                              <span style="font-size: 16px">
                                {{
                                  `${item['name']} ${item['furigana']} (ID:${item['code']})`
                                }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            class="job-detail-for-hr-form-btn"
            style="margin-top: 2rem; margin-bottom: 2rem"
          >
            <div
              :disable="allow_btn"
              style="width: 360px"
              @click="navigateToInformationEntry"
            >
              <BtnMoveNext
                :min-width="'360px'"
                :text="'エントリーに進む'"
                :direction="'next'"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal
      ref="modal-select-hr-to-entry"
      v-model="statusModalSelectHR"
      hide-footer
      dialog-class
      :size="'lg'"
      title="人材を選ぶ"
      :no-fade="false"
      no-close-on-backdrop
      aria-describedby="select-hr-to-entry"
    >
      <div class="modal-select-hr">
        <div class="modal-select-hr-container">
          <div class="modal-select-hr-search">
            <HrFormSearch />
          </div>

          <div class="modal-select-hr-list">
            <div class="modal-select-hr-wrap">
              <div class="select-hr-head">
                <span>人材一覧</span>
              </div>

              <div class="select-hr-list">
                <div class="select-hr-list-wrap">
                  <div
                    v-for="(item, index) in vItems"
                    :key="`item-${index}`"
                    class="select-hr-item"
                  >
                    <b-form-checkbox
                      v-model="item['is_check']"
                      size="lg"
                      :disabled="item['is_disabled']"
                      @change="handleChangeCheckboxValue(item, $event)"
                    >
                      <span style="font-size: 16px">
                        {{
                          `${item['name']} ${item['furigana']} (ID:${item['code']})`
                        }}
                      </span>
                    </b-form-checkbox>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-select-hr-btn">
              <div class="btn btn-confirm" @click="handleTurnOffModal()">
                <span>決定</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-modal>

    <b-modal
      v-model="isShowModalWarning"
      hide-footer
      :no-fade="false"
      no-close-on-backdrop
      centered
      size="lg"
    >
      <template #modal-title>
        <span>このページから移動しますか？選択した内容はすべて破棄されます。</span>
      </template>

      <template #default>
        <div class="d-flex justify-content-end" style="gap: 10px">
          <div class="btn btn-cancel" @click="isShowModalWarning = false">
            <span>キャンセル</span>
          </div>

          <div class="btn btn-confirm" @click="navigateToInformationEntry()">
            <span>OK</span>
          </div>
        </div>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { dataDetailHr } from '@/pages/JobSearch/JobDetailForHr/dataJobDetail.js';

import ExtendBtn from '@/components/ExtendBtn';
import BtnMoveNext from '@/components/BtnMoveNext';
import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';

export default {
  name: 'JobEntry',
  components: {
    HrFormSearch,
    ExtendBtn,
    BtnMoveNext,
  },
  data() {
    return {
      allow_btn: true,

      formData: dataDetailHr,

      role: 'HrOrganizationOversea',

      statusModalSelectHR: false,

      isShowModalWarning: false,

      vItems: [
        {
          id: 1,
          is_check: false,
          is_disabled: false,
          name: 'Trần Văn An',
          furigana: 'ｼﾞﾖｳ ﾀﾞｲｽｹ',
          code: '000001',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 2,
          is_check: false,
          is_disabled: false,
          name: 'Nguyễn Thị Hương',
          furigana: 'ﾀﾞｲ ﾌﾞﾝ ﾀﾝ',
          code: '000002',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 3,
          is_check: false,
          is_disabled: false,
          name: 'Lê Minh Tâm',
          furigana: 'ｼﾞﾖｳ ﾎﾞｳ',
          code: '000003',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 4,
          is_check: false,
          is_disabled: false,
          name: 'Phạm Thị Thu Hà',
          furigana: 'ﾕｳ ﾀｹｼ',
          code: '000004',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 5,
          is_check: false,
          is_disabled: false,
          name: 'Nguyễn Văn Tài',
          furigana: 'ﾔﾏｼﾛ ﾀﾂﾖｼ',
          code: '000005',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 6,
          is_check: false,
          is_disabled: false,
          name: 'Ngô Thị Thu',
          furigana: 'ｺﾞｳ ﾒｲｺ',
          code: '000006',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 7,
          is_check: false,
          is_disabled: false,
          name: 'Bùi Văn Thắng',
          furigana: 'ﾊﾝﾀﾞ ﾀﾂﾖｼ',
          code: '000007',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 8,
          is_check: false,
          is_disabled: false,
          name: 'Đỗ Thị Hương',
          furigana: 'ﾏﾂﾔﾏ ｶﾅｴ',
          code: '000008',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 9,
          is_check: false,
          is_disabled: false,
          name: 'Vũ Văn Thành',
          furigana: 'ﾊﾞｸ ﾀﾂﾖｼ',
          code: '000009',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 10,
          is_check: false,
          is_disabled: false,
          name: 'Lê Minh Tú',
          furigana: 'ﾏﾂﾔﾏ ｾｲｺ',
          code: '000010',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 11,
          is_check: false,
          is_disabled: false,
          name: 'Nguyễn Văn Hải',
          furigana: 'ﾀﾆﾍﾞ ﾕﾐ',
          code: '000011',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 12,
          is_check: false,
          is_disabled: false,
          name: 'Phan Thị Hồng',
          furigana: 'ｶﾜｶﾐ ﾏｻﾐ',
          code: '000012',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 13,
          is_check: false,
          is_disabled: false,
          name: 'Lê Thị Hà',
          furigana: 'ﾀﾆﾍﾞ ﾏｻﾐ',
          code: '000013',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 14,
          is_check: false,
          is_disabled: false,
          name: 'Trần Thị Thúy',
          furigana: 'ﾀﾆﾍﾞ ﾏｻﾐ',
          code: '000014',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 15,
          is_check: false,
          is_disabled: false,
          name: 'Nguyễn Văn Thịnh',
          furigana: 'ﾔﾏﾀﾞ ﾀﾂﾖｼ',
          code: '000015',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 16,
          is_check: false,
          is_disabled: false,
          name: 'Trần Thị Thanh',
          furigana: 'ﾀﾆﾍﾞ ﾏｻﾐ',
          code: '000016',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 17,
          is_check: false,
          is_disabled: false,
          name: 'Nguyễn Thị Phương',
          furigana: 'ﾖｼﾉ ﾀﾞｲｽｹ',
          code: '000017',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 18,
          is_check: false,
          is_disabled: false,
          name: 'Phạm Văn Đức',
          furigana: 'ﾀｶﾉ ﾀﾂﾖｼ',
          code: '000018',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 19,
          is_check: false,
          is_disabled: false,
          name: 'Lê Thị Kim',
          furigana: 'ﾀﾆﾍﾞ ﾏｻﾐ',
          code: '000019',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
        {
          id: 20,
          is_check: false,
          is_disabled: false,
          name: 'Nguyễn Văn Hùng',
          furigana: 'ｶﾜﾉ ﾀﾂﾖｼ',
          code: '000020',
          remark: '',
          file_1: '選択されていません',
          file_2: '選択されていません',
          file_count: 1,
        },
      ],

      listHrSelected: [],
    };
  },
  watch: {
    listHrSelected() {
      if (this.listHrSelected.length >= 10) {
        this.vItems.forEach((item) => {
          if (!item['is_check']) {
            item['is_disabled'] = true;
          }
        });
      } else {
        this.handleResetDisabledStatus();
      }
    },
  },
  created() {
    this.getRole();
  },
  methods: {
    goBack() {
      if (this.listHrSelected.length > 0) {
        this.isShowModalWarning = true;
      } else {
        this.$router.push({ name: 'JobSearchList' });
      }
    },
    navigateToInformationEntry() {
      if (this.listHrSelected.length > 0) {
        this.$router.push({
          name: 'JobInformationEntry',
          params: { data: this.listHrSelected },
        });
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: `エントリーする人材を選択してください (エントリーに進む ボタン下部)`,
        });
      }
    },
    getRole() {
      this.allow_btn = true;
    },
    removeThisHr(hr_id) {
      this.listHrSelected = this.listHrSelected.filter(
        (item) => item['id'] !== hr_id
      );

      this.vItems.find((item) => {
        if (item['id'] === hr_id) {
          item['is_check'] = false;
        }
      });
    },
    handleToggleModalSelectHR() {
      this.statusModalSelectHR = !this.statusModalSelectHR;
    },
    handleChangeCheckboxValue(item, event) {
      if (this.listHrSelected.length) {
        const isExisted = this.listHrSelected.find(
          (item_id) => item_id === item['id']
        );

        if (isExisted) {
          const index = this.listHrSelected.findIndex(
            (item_index) => item_index === item['id']
          );

          this.listHrSelected.splice(index, 1);
        } else {
          if (event) {
            this.listHrSelected.push(item);
          }
        }
      } else {
        if (event) {
          this.listHrSelected.push(item);
        }
      }
    },
    handleResetDisabledStatus() {
      this.vItems.forEach((item) => {
        item['is_disabled'] = false;
      });
    },
    handleTurnOffModal() {
      this.statusModalSelectHR = false;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/pages/JobSearch/JobDetailForHr/JobDetailForHr.scss';

.btn-cancel {
  background-color: red;
  border-radius: 6px;
  color: white;
}

.btn-confirm {
  background-color: #007bff;
  border-radius: 6px;
  color: white;
}

.entry-hr-select {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
}

.entry-hr-selected-list {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  padding: 0 0.5rem;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;

  .btn-effect {
    color: red;
  }
}

.entry-hr-selected-item {
  gap: 1rem;
  width: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
}

.entry-hr-selected-item__name {
  width: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
}

.select-hr-item {
  display: flex;
  margin-bottom: 10px;
  flex-direction: column;
}

.modal-select-hr {
  width: 100%;
  display: flex;
  min-height: 500px;
  align-items: stretch;
  justify-content: flex-start;
}

.modal-select-hr-container {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;
}

.modal-select-hr-search {
  width: 100%;
  display: flex;
  max-width: 40%;
  border-radius: 5px;
  background: #ffffff;
  align-items: stretch;
  justify-content: flex-start;
}

.modal-select-hr-list {
  width: 100%;
  gap: 2.1rem;
  display: flex;
  max-width: 60%;
  align-items: center;
  flex-direction: column;
  padding: 0 0 6.25rem 0;
  justify-content: flex-start;
}

.modal-select-hr-wrap {
  flex: 1;
  width: 100%;
  display: flex;
  gap: 1.375rem;
  border-radius: 5px;
  background: #ffffff;
  align-items: center;
  flex-direction: column;
  border: 1px solid #c6c6c6;
  justify-content: flex-start;
  padding: 0.5rem 0.75rem 1.625rem 0.75rem;
}

.select-hr-head {
  width: 100%;
  height: 34px;
  display: flex;
  padding: 20px;
  background: #f1f1f1;
  align-items: center;
  justify-content: flex-start;
  box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);

  & > div {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  & > span {
    color: #304cad;
    font-size: 18px;
    font-weight: bold;
  }
}

.select-hr-list {
  flex: 1;
  width: 100%;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;
}

.select-hr-list-wrap {
  padding-left: 10px;
}

.btn-confirm {
  height: 45px;
  display: flex;
  min-width: 130px;
  padding: 0 2.8rem;
  border-radius: 6px;
  background: #304cad;
  align-items: center;
  justify-content: center;
  border: 1px solid #c6c6c6;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);

  & span {
    color: $white;
    font-size: 16px;
    font-weight: bold;
  }
}
</style>
