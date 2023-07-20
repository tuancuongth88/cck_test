<template>
  <div class="hr-list">
    <div v-if="stateCollaseHrFormSearch" class="hr-list__search">
      <HrFormSearch />
    </div>

    <div class="hr-list__wrap" :class="{ 'pl-0 hr-list-collapse': !stateCollaseHrFormSearch }">
      <div class="btn collapse-bar-btn" @click="toggleHrFormSearch">
        <div :class="{ 'rotate-180deg': stateCollaseHrFormSearch }">
          <img :src="require('@/assets/images/login/chervon-collase-bar.png')" alt="collapse">
        </div>
      </div>

      <div class="hr-list-head">
        <div class="hr-list-head-title">
          <div class="line" />
          <span>人材情報 検索一覧</span>
        </div>

        <div class="hr-list-head-btns">
          <div class="btn btn-light" @click="handleToggleDeleteAllItemModal()">
            <span>チェックしたものを一括削除</span>
            <b-icon icon="trash-fill" style="color: #1D266A;" />
          </div>
        </div>
      </div>

      <div :class="[stateCollaseHrFormSearch ? 'hr-list-table-list-wrap' : 'pl-0 hr-list-collapse' ]">
        <div class="list-table-list__auto-flow-x">
          <div class="hr-list-table-list__table" :class="{ 'hr-list-table-list__table-collapse': !stateCollaseHrFormSearch, }">
            <b-table :fields="hrFields" :items="hrItems" bordered @sort-changed="handleSortTableHrList">
              <template #empty="">
                <span>{{ $t('TABLE_EMPTY_LIST') }}</span>
              </template>

              <template #head(selected)="">
                <b-form-checkbox v-model="stateSelectAllHrItem" @change="onSelectAllCheckboxChange" />
              </template>

              <template #cell(selected)="row">
                <b-form-checkbox v-model="row.item._isSelected" @change="onItemCheckboxChange(row.item, $event)" />
              </template>

              <template #cell(id)="id">
                <div class="w-100 d-flex justify-content-center">
                  <span>{{ id.item.id }}</span>
                </div>
              </template>

              <template #cell(fullName)="fullname">
                <div class="w-100 justify-space-between align-items-start flex-column">
                  <div v-for="(item, index) in fullname.item.fullName" :key="index" class="w-100 justify-space-between flex-column">
                    <div class="text-overflow-ellipsis">{{ item.name_vn }}</div>
                    <div class="text-overflow-ellipsis">{{ item.name_jp }}</div>
                  </div>

                  <img
                    v-if="fullname.item.favorite"
                    :src="require('@/assets/images/login/heart.png')"
                    alt="heart"
                    style=" height: 20px; width: 20px; position: absolute; bottom: 0.5rem; right: 0.5rem; "
                  >
                </div>
              </template>

              <template #cell(age)="age">
                <div class="w-100 d-flex justify-content-center">
                  <span>{{ age.item.age }}</span>
                </div>
              </template>

              <template #cell(japanese_level)="japanese_level">
                <div class="w-100 justify-content-center">
                  <span>{{ japanese_level.item.japanese_level }}</span>
                </div>
              </template>

              <template #cell(status)="status">
                <div class="status-item w-100 justify-center" style="align-items: center">
                  <span v-if="status.item.status === '公開'">{{ '公開' }}</span>
                  <span v-if="status.item.status === '非公開'" style="color: #b1b1b1">{{ '非公開' }}</span>
                  <span v-if="status.item.status === '内定承諾'">{{ '内定承諾' }}</span>
                </div>
              </template>

              <template #cell(detail)="detail">
                <div class="w-100 justify-center" style="align-items: center">
                  <i v-if="detail.item.status === '公開'" class="btn fas fa-eye icon-detail" @click="goToHRDetail(detail)" />
                  <i v-if="detail.item.status === '非公開'" class="btn fas fa-eye icon-detail" @click="goToHRDetail(detail)" />
                  <i v-if="detail.item.status === '内定承諾'" class="btn fas fa-eye icon-detail" style="padding: 0" @click="goToHRDetail(detail)" />
                </div>
              </template>
            </b-table>
          </div>
        </div>
      </div>
    </div>

    <b-modal ref="my-modal" v-model="statusModalConfirmDelAll" hide-header hide-footer title="">
      <div class="modal-body-content">
        <div class="w-100 modal-content-title-del-hr d-flex justify-center align-center">
          <span>本当に削除しますか？</span>
        </div>

        <div class="hr-list-btns">
          <div id="delete-all-item-hr" class="btn" @click="handleToggleDeleteAllItemModal()">
            <span>キャンセル</span>
          </div>

          <div id="import-csv" class="btn accept" @click="handleConfirmDeleteAllHrItem()">
            <span>削除する</span>
          </div>
        </div>
      </div>
    </b-modal>

    <b-modal ref="my-modal" v-model="statusModalImportMultiCSV" hide-header hide-footer title="Using Component Methods">
      <div class="modal-body-content">
        <div class="modal-content-title">
          <span>人材情報をCSVで一括インポートする。</span>
        </div>

        <div class="hr-list-import">
          <div class="hr-list-import__title" for="file-CSV-multi">ファイル</div>

          <div class="btn hr-list-import__btn">
            <label for="upload-img">ファイルを選択</label>
            <input id="upload-img" ref="MultiCSV" type="file" style="display: none" @change="handleImportMultiCSV">
          </div>
        </div>

        <div class="hr-list-btns">
          <div class="btn" @click="handleToggleAddGroupModal()">
            <span>キャンセル</span>
          </div>

          <div id="import" class="btn accept" @click="handleConfirmImportMultiCSV()">
            <span>取り込み</span>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import Cookies from 'js-cookie';
import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';

import { getAllHr } from '@/api/modules/hr';
import { obj2Path } from '@/utils/obj2Path';
import { cleanObj } from '@/utils/handleObj';

const urlAPI = {
  apiGetListHr: '/hr',
};

export default {
  name: 'HrSearchList',
  components: {
    HrFormSearch,
  },
  data() {
    return {
      stateCollaseHrFormSearch: true,
      statusModalConfirmDelAll: false,
      statusModalImportMultiCSV: false,

      reRender: 0,

      hr_item_check_box: null,

      list_data: [
        {
          check_box: 'check',
          id: 65,
          fullName: 'fullName',
          organization_name: 'organization_name',
          age: 'age',
          japanese_level: 'japanese_level',
          status: 'status',
          detail: 'detail',
        },
      ],

      hrListFields: [
        { key: 'check_box', label: '', thClass: 'col1' },
        { key: 'id', label: '#', thClass: 'col2', sortable: true },
        {
          key: 'fullName',
          label: '氏名',
          thClass: 'col3',
          sortable: true,
        },
        {
          key: 'organization_name',
          label: '団体名',
        },
        {
          key: 'age',
          label: '年齢',
          thClass: 'col5',
          sortable: true,
        },
        {
          key: 'japanese_level',
          label: '日本語',
          thClass: 'col6',
          sortable: true,
        },
        {
          key: 'status',
          label: 'ステータス',
          thClass: 'col7',
          sortable: true,
        },
        {
          key: 'detail',
          label: '詳細',
          thClass: 'col8',
          sortable: true,
        },
      ],

      hrFields: [
        {
          key: 'selected',
          sortable: false,
          label: '',
          class: 'choose',
          thClass: 'col-1',
        },
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thClass: 'col-2',
        },
        {
          key: 'fullName',
          sortable: true,
          label: this.$t('HR_REGISTER.FULL_NAME'),
          class: 'fullName',
          thClass: 'col-3',
        },

        {
          key: 'organization_name',
          sortable: true,
          label: this.$t('HR_REGISTER.ORGANIZATION_NAME'),
          class: 'organization_name',
          thClass: 'col-4',
        },
        {
          key: 'age',
          sortable: true,
          label: this.$t('HR_REGISTER.AGE'),
          class: 'age',
          thClass: 'col-5',
        },
        {
          key: 'japanese_level',
          sortable: true,
          label: this.$t('HR_REGISTER.JAPANESE_LEVEL'),
          class: 'japanese_level',
          thClass: 'col-6',
        },

        {
          key: 'status',
          sortable: true,
          label: this.$t('HR_REGISTER.STATUS'),
          class: 'status',
          thClass: 'col-7',
        },
        {
          key: 'detail',
          sortable: false,
          label: this.$t('HR_LIST_FORM.DETAIL'),
          class: 'detail',
          thClass: 'col-8',
        },
      ],

      hrItems: [
        {
          _isSelected: false,
          id: '0000001',
          fullName: [{ name_vn: 'Nguyen Thi Nhi ' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
          favorite: false,
          organization_name:
            'Công ty Cổ phần phát triển... LOD人材開発株式会社',
          age: '21',
          japanese_level: 'N1',
          status: '公開',
        },
        {
          _isSelected: false,
          id: '0000002',
          fullName: [{ name_vn: 'Nguyen Thi Nhi ' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
          favorite: true,
          organization_name:
            'Công ty Cổ phần phát triển... LOD人材開発株式会社',
          age: '22',
          japanese_level: 'N2',
          status: '非公開',
        },
        {
          _isSelected: false,
          id: '0000003',
          fullName: [{ name_vn: 'Nguyen Thi Nhi ' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
          favorite: true,
          organization_name:
            'Công ty Cổ phần phát triển... LOD人材開発株式会社',
          age: '23',
          japanese_level: 'N3',
          status: '内定承諾',
        },

        {
          _isSelected: false,
          id: '0000004',
          fullName: [{ name_vn: 'Nguyen Thi Nhi ' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
          favorite: false,
          organization_name:
            'Công ty Cổ phần phát triển... LOD人材開発株式会社',
          age: '24',
          japanese_level: 'N4',
          status: '公開',
        },
        {
          _isSelected: false,
          id: '0000005',
          fullName: [{ name_vn: 'Nguyen Thi Nhi ' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
          favorite: true,
          organization_name:
            'Công ty Cổ phần phát triển... LOD人材開発株式会社',
          age: '25',
          japanese_level: 'N5',
          status: '公開',
        },
      ],

      selectedItems: [],
      stateSelectAllHrItem: false,

      full_name_vn: '',
      full_name_jp: '',
    };
  },
  computed: {
    query() {
      return Cookies.get('searchParams');
    },
  },
  created() {
    console.log(JSON.parse(this.query));
  },
  methods: {
    async handleGetListHr() {
      let PARAMS = {
        field: '',
        sort_by: '',
        page: '',
        per_page: '',
        hr_org_id: '',
        gender: '',
        age_from: '',
        age_to: '',
        edu_date_from: '',
        edu_date_to: '',
        edu_class: [],
        edu_degree: [],
        work_forms: [],
        work_hour: '',
        japan_levels: [],
        middle_class: [],
        main_job_ids: [],
        search: '',
      };

      PARAMS = cleanObj(PARAMS);

      const URL = `${urlAPI.apiGetListHr}?${obj2Path(PARAMS)}`;

      try {
        const response = await getAllHr(URL, PARAMS);

        console.log(response);
      } catch (error) {
        console.log(error);
      }
    },
    toggleHrFormSearch() {
      this.stateCollaseHrFormSearch = !this.stateCollaseHrFormSearch;
    },
    reloadTable() {
      this.reRender++;
    },
    onSelectAllCheckboxChange(value) {
      this.stateSelectAllHrItem = value;
      this.selectedItems = [...this.hrItems];

      this.selectedItems.forEach((item) => {
        item._isSelected = this.stateSelectAllHrItem;
      });
    },
    onItemCheckboxChange(item, value) {
      if (value) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex((selectedItem) => selectedItem.id === item.id);
        this.selectedItems.splice(index, 1);
      }

      if (this.selectedItems.length === this.hrItems.length) {
        this.stateSelectAllHrItem = true;
      } else {
        this.stateSelectAllHrItem = false;
      }
    },
    async handleSort(ctx) {},
    goToHRDetail(detail_data) {
      const HR_DETAIL_ID = detail_data.item.id;
      this.$router.push({ path: `/hr/detail/${HR_DETAIL_ID}` }, (onAbort) => {});
    },
    handleToggleAddGroupModal() {
      this.statusModalImportMultiCSV = !this.statusModalImportMultiCSV;
    },
    handleConfirmDeleteAllHrItem() {
      this.statusModalConfirmDelAll = false;
    },
    handleToggleDeleteAllItemModal() {
      this.statusModalConfirmDelAll = !this.statusModalConfirmDelAll;
    },
    handleImportMultiCSV() {},
    handleConfirmImportMultiCSV() {
      this.handleToggleAddGroupModal();
    },
    async handleSortTableHrList(ctx) {},
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/components/Modal/ModalStyle.scss';

.hr-list-collapse {
  width: 100%;
}

.fa-eye {
  color: #999999;
}

.hr-list {
  width: 100%;
  height: 100%;
  display: flex;
  min-height: 100vh;
  align-items: stretch;
  justify-content: space-between;
}

.hr-list__search {
  width: 25%;
  min-width: 272px;
}

.hr-list__wrap {
  width: 75%;
  gap: 0.5rem;
  display: flex;
  align-items: stretch;
  flex-direction: column;
  justify-content: flex-start;
  padding: 0rem 0rem 1.5rem 0.75rem;
}

.hr-list-collapse {
  width: 100%;
}

.hr-list-head {
  display: flex;
  align-items: stretch;
  justify-content: space-between;
}

.collapse-bar-btn {
  width: 36px;
  height: 36px;
  display: flex;
  border-radius: 3px;
  background: #ffffff;
  align-items: center;
  padding: 0 !important;
  justify-content: center;
  border: 1px solid #b7b7b7;
  transition: all 0.3s ease;

  & img {
    transform: rotate(180deg);
  }
}

.rotate-180deg {
  transform: rotate(-180deg);
}

.hr-list-head-title {
  gap: 1rem;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;

  & div {
    display: flex;
    align-items: center;
    justify-content: flex-start;
  }

  & span {
    display: flex;
    color: #000000;
    font-size: 24px;
    font-weight: 700;
  }
}

.line {
  width: 4px;
  background: #304cad;
  border-radius: 10px;
  border: 1px solid #304cad;
}

.hr-list-head-btns {
  gap: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.btn-light {
  height: 100%;
  height: 36px;
  gap: 0.25rem;
  display: flex;
  border-radius: 5px;
  background: #ffffff;
  align-items: center;
  justify-content: center;
  border: 1px solid #1d266a;

  & span {
    display: flex;
    color: #1d266a;
    font-size: 15px;
    font-weight: 400;
    line-height: 18px;
    text-align: center;
    align-items: center;
  }
}

.btn-bold {
  height: 100%;
  gap: 0.25rem;
  display: flex;
  border-radius: 5px;
  align-items: center;
  justify-content: center;
  background: #f9be00 !important;

  & span {
    color: #ffffff;
    font-size: 14px;
    font-weight: 400;
    line-height: 17px;
  }
}

.hr-list-table-list-wrap {
  width: 100%;
  height: 100%;
}

.list-table-list__auto-flow-x {
  width: 100%;
  overflow-x: auto;

  &::-webkit-scrollbar {
    width: 10px;
    height: 10px;
  }

  &::-webkit-scrollbar-thumb {
    border-radius: 6px;
  }
}

.hr-list-table-list__table {
  height: 100%;
  width: max-content;
  padding-top: 1.25rem;
}

.hr-list-table-list__table-collapse {
  width: 100%;
}

.text-overflow-ellipsis {
  width: 100%;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.status-item {
  width: 100%;
  height: 29px;
  cursor: default;
  border-radius: 5px;
  background: #ffffff;
  border: 1px solid #666666;
}

::v-deep .hr-list-table-list__table {
  & .b-table {
    & tbody {
      & tr {
        height: 78px;
        min-height: 78px;
        padding: 1.25rem 0;

        & td {
          position: relative;
          vertical-align: middle;

          & > div {
            display: flex;
            justify-content: center;
          }
        }
      }

      & tr:last-child {
        border-bottom: 1px solid #dee2e6;
      }
    }
  }
}

::v-deep .col-1 {
  width: 52px;
}

::v-deep .col-2 {
  width: 38px;
}

::v-deep .col-3 {
  width: 240px;
}

::v-deep .col-4 {
  width: 240px;
}

::v-deep .col-5,
::v-deep .col-6 {
  width: 128px;
  border: 1px solid red;
}

::v-deep .col-7 {
  width: 64px;
  width: 108px;
}

::v-deep .col-8 {
  width: 60px;
}

::v-deep .dropdown-menu {
  left: 0 !important;
  width: 100% !important;
  min-width: 100% !important;
}

::v-deep .btn-secondary {
  background: #f9be00 !important;
  border-color: #f9be00 !important;
}
</style>
