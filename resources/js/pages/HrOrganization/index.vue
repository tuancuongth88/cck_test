<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t("PLEASE_WAIT") }}</p>
      </div>
    </template>

    <div class="display-user-management-list">
      <b-row class="mb-2">
        <b-col
          cols="12"
          class="d-flex justify-content-between align-items-center"
        >
          <b-col class="border-left-title font-weight-bold">{{
            $t('TITLE.ORGANIZATION_LIST')
          }}</b-col>
        </b-col>
      </b-row>
      <b-row class="mb-4">
        <b-col cols="12">
          <b-table :fields="fields" :items="items" :hover="true">
            <template #cell(organization_name)="data">
              <span class="d-block">{{ data.item.organization_name_en }}</span>
              <span class="d-block">{{ data.item.organization_name_ja }}</span>
            </template>
            <template #cell(status)="status">
              <span
                v-if="status.value === '審査待ち'"
                class="btn-status btn-pending"
              >
                {{ $t('STATUS.EXAMINATION_PENDING') }}
              </span>
              <span v-if="status.value === '承認'" class="btn-status btn-confirm">
                {{ $t('STATUS.CONFIRM') }}
              </span>
              <span v-if="status.value === '却下'" class="btn-status btn-decline">
                {{ $t('STATUS.DECLINE') }}
              </span>
              <span
                v-if="status.value === '利用停止'"
                class="btn-status btn-decline"
              >
                {{ $t('STATUS.DISCONTINUANCE_OF_USE') }}
              </span>
            </template>
            <template #cell(detail)="detail">
              <button
                class="btn-go-detail"
                @click="goToDetail(detail.item.id, detail.value)"
              >
                <i class="fas fa-eye icon-detail" />
              </button>
            </template>
          </b-table>
        </b-col>
      </b-row>
    </div>
  </b-overlay>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import ModalCommon from '@/components/Modal/index.vue';
import { getAllHrOrganization } from '@/api/hrOrganization.js';
import moment from 'moment';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'HrOrganizationList',
  components: {},

  data() {
    return {
      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },
      fields: [
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thStyle: { textAlign: 'center' },
        },
        {
          key: 'organization_name',
          sortable: true,
          // label: 'ORGANIZATION',
          label: '団体名',
          class: 'organization_name',
          thStyle: { textAlign: 'center' },
        },
        {
          key: 'classification',
          sortable: true,
          // label: 'Classification',
          label: '区分',
          class: 'classification',
          thStyle: { textAlign: 'center' },
        },
        {
          key: 'updating_date',
          sortable: true,
          // label: 'UPDATING DATE',
          label: '更新日',
          class: 'updating_date',
          thStyle: { textAlign: 'center' },
        },
        {
          key: 'status',
          sortable: true,
          // label: 'STATUS',
          label: 'ステータス',
          class: 'status',
          thStyle: { textAlign: 'center' },
          tdClass: 'text-center',
        },
        {
          key: 'detail',
          sortable: false,
          // label: 'DETAIL',
          label: '詳細',
          class: 'detail',
          thStyle: { textAlign: 'center' },
          tdClass: 'text-center',
        },
      ],
      items: [
        // {
        //   _isSelected: false,
        //   id: '0000001',
        //   organization_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
        //   organization_name_ja: 'LOD人材開発株式会社',
        //   classification: '運輸・通信業',
        //   updating_date: '20230301',
        //   status: '審査待ち',
        // },
        // {
        //   _isSelected: false,
        //   id: '0000002',
        //   organization_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
        //   organization_name_ja: 'LOD人材開発株式会社',
        //   classification: '運輸・通信業',
        //   updating_date: '20230301',
        //   status: '承認',
        // },
        // {
        //   _isSelected: false,
        //   id: '0000003',
        //   organization_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
        //   organization_name_ja: 'LOD人材開発株式会社',
        //   classification: '運輸・通信業',
        //   updating_date: '20230301',
        //   status: '却下',
        // },
        // {
        //   _isSelected: false,
        //   id: '0000004',
        //   organization_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
        //   organization_name_ja: 'LOD人材開発株式会社',
        //   classification: '運輸・通信業',
        //   updating_date: '20230301',
        //   status: '利用停止',
        // },
      ],
    };
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
    // currChange() {
    //   return this.queryData.page;
    // },
  },

  created() {
    this.getListAllData();
  },

  methods: {
    async getListAllData() {
      this.overlay.show = true;
      const PARAM = {
        per_page: -1,
      };
      // this.$store.dispatch('loading/setLoading', true);
      await getAllHrOrganization(PARAM).then((response) => {
        // console.log('response==>', response);
        const { data } = response;
        if (data.code === 200) {
          this.items = data.data.result.map((item) => {
            return {
              _isSelected: false,
              id: item.id,
              organization_name: item.organization_name_en,
              organization_name_ja: 'LOD人材開発株式会社',
              classification: item.account_classification,
              updating_date: moment.unix(item.updated_at).format('YYYYMMDD'),
              status: this.genderStatus(item.status),
            };
          });

          // this.$store.dispatch('app/saveListUSer', listUser);
          // this.pagination.total_records = response.data.total;
          // // response.data.pagination.total_records;
          // this.pagination.current_page = response.data.current_page;
          // this.pagination.isDisable = false;
          // listUser.forEach((element) => {
          //   element.roles.name = this.convertRoles(element.roles.name);
          // });
        }
        // this.$store.dispatch('loading/setLoading', false);
      });
      this.overlay.show = false;
      // .catch((error) => {
      //   this.closeLoading();
      //   MakeToast({
      //     variant: 'warning',
      //     title: this.$t('LANGUAGES.TEXT_TOAST_TITLE_WARNING'),
      //     content: error.message,
      //   });
      // });
    },

    genderStatus(id) {
      switch (id) {
        case 1:
          return '審査待ち';
        case 2:
          return '承認';
        case 3:
          return '却下';
        case 4:
          return '利用停止';
        default:
          break;
      }
    },

    goToDetail(id) {
      this.$router.push(
        { path: `/hr-organization/detail/${id}` },
        (onAbort) => {}
      );
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.line {
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
}

.btn-light {
  background: #ffffff;
  border: 1px solid #1d266a;
  border-radius: 5px;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.25rem;
  & span {
    font-weight: 400;
    font-size: 15px;
    line-height: 18px;
    display: flex;
    align-items: center;
    text-align: center;
    color: #1d266a;
  }
}
.btn-bold {
  background: #f9be00 !important;
  border-radius: 5px;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.25rem;
  & span {
    font-weight: 400;
    font-size: 14px;
    line-height: 17px;
    color: #ffffff;
  }
}

// Ghi đè
::v-deep .col-1 {
  width: 40px;
}
::v-deep .col-2 {
  width: 112px;
}
::v-deep .col-3,
::v-deep .col-4 {
  // border: 1px solid red;
  width: 208px;
}

::v-deep .col-5,
::v-deep .col-6 {
  // border: 1px solid red;
  width: 84px;
}

::v-deep .col-7 {
  // border: 1px solid red;
  width: 96px;
}

::v-deep .col-8 {
  border: 1px solid red;
  width: 59px;
}

// ::v-deep  .col8, ::v-deep  .col9 {
//     border: 1px solid green;
//     width: 172px;
//     width: 150x;
//     max-width: 212px;
// }

// .paggination {}
// Ghi đè option
::v-deep .dropdown-menu {
  left: 0 !important;
  min-width: 100% !important;
  width: 100% !important;
}
// Ghi đè button
::v-deep .btn-secondary {
  background: #f9be00 !important;
  border-color: #f9be00 !important;
}
// Moddal
::v-deep .modal-content {
  width: 652px;
  transform: translateX(-14.5%);
  padding: 4.5rem 5rem;
}
// .modal-body-content {
//   border: 1px solid;
// }

.modal-content-title {
  font-weight: 400;
  font-size: 24px;
  line-height: 29px;
  color: #262626;
}

#import {
  background: #f9be00 !important;
}

.border-left-title {
  border-left: 4px solid #314cad;
  height: 36px;
  line-height: 36px;
}
</style>
