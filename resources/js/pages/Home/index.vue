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
        <p style="margin-top: 10px">
          {{ $t('PLEASE_WAIT') }}
        </p>
      </div>
    </template>
    <!--  -->
    <div class="display-user-management-list">
      <!-- <b-row class="mb-4">
      <b-col cols="12" class="d-flex justify-content-end">
        <b-button variant="outline-dark" @click="goToDistribute">
          {{ $t('BUTTON.DISTRIBUTE') }}
          <i class="fa fa-light fa-pencil" />
        </b-button>
      </b-col>
    </b-row> -->

      <!--  Table 1 -->
      <b-row class="mb-4">
        <b-col cols="12">
          <b-card :title="$t('TITLE.NEW_MSG')">
            <b-tabs class="tab-active">
              <!-- 1 -->
              <b-tab>
                <template #title>
                  {{ $t('TAB.MATCHING_RELATION') }}
                  <span v-if="!readAll" class="dot" />
                  <!-- <span class="dot" /> -->
                </template>
                <!--  -->
                <b-table :items="itemsNewMsg" :fields="fields1" :bordered="true">
                  <!-- 1 -->
                  <template #cell(title)="data">
                    <div class="table-cell">
                      <!-- {{ data.item }} -->
                      <span v-if="!data.item.read_at" class="dot-unread" />
                      <b-link class="card-link" @click="detailMsg(data.item.id)">
                        {{ data.item.title }}
                      </b-link>
                    </div>
                  </template>
                  <!-- 2 -->
                  <template #cell(reception_time)="data">
                    <div class="w-100 h-100 d-flex justify-center align-center">
                      <span>{{ data.item.reception_time }}</span>
                    </div>
                  </template>

                </b-table>

                <b-link
                  class="d-flex justify-content-end"
                >{{ $t('TITLE.SHOW_MORE_MESSAGES') }}
                </b-link>
              </b-tab>
              <!-- 2 -->
              <b-tab>
                <template #title>
                  {{ $t('TITLE.OTHERS') }} <span class="dot" />
                </template>
                <b-table
                  :items="itemsNewMsg"
                  :fields="fields1"
                  :bordered="true"
                />
                <b-link
                  class="d-flex justify-content-end"
                >{{ $t('TITLE.SHOW_MORE_MESSAGES') }}
                </b-link>
              </b-tab>
            <!--  -->
            </b-tabs>
          </b-card>
        </b-col>
      </b-row>
      <!-- Table 2 -->
      <b-row class="mb-4">
        <b-col cols="12">
          <b-card>
            <div class="d-flex justify-content-between">
              <h4 class="card-title">
                {{ $t('HOME_MANAGEMENT.DISTRIBUTION_MSG') }}
              </h4>
              <b-button
                variant="warning"
                class="text-white"
                @click="goToDistribute"
              >
                {{ $t('HOME_MANAGEMENT.CREATE_MESSAGE') }}
              </b-button>
            </div>

            <b-tabs class="tab-active">
              <b-tab :title="$t('TITLE.MSG')">
                <b-table :items="items2" :fields="fields2" :bordered="true">
                  <template #cell(title)="row">
                    <b-link :to="'/home/detail'" class="card-link">{{
                      row.item.title
                    }}</b-link>
                  </template>
                </b-table>
                <b-link
                  class="d-flex justify-content-end"
                >{{ $t('TITLE.SHOW_MORE_MESSAGES') }}
                </b-link>
              </b-tab>
            </b-tabs>
          </b-card>
        </b-col>
      </b-row>
      <!-- Table 3 -->
      <b-row class="mb-4">
        <b-col cols="12">
          <b-card :title="$t('HOME_MANAGEMENT.ON_GOING_JOB')">
            <b-table :items="items3" :fields="fields3" :bordered="true">
              <template #cell(detail)="detail">
                <button
                  class="btn-go-detail"
                  @click="goToDetail(detail.item.id, detail.item.status)"
                >
                  <i class="fas fa-eye icon-detail" />
                </button>
              </template>
            </b-table>
            <b-link
              class="d-flex justify-content-end"
            >{{ $t('TITLE.SHOW_MORE_MESSAGES') }}
            </b-link>
          </b-card>
        </b-col>
      </b-row>
    </div>
  </b-overlay>

</template>

<script>
// import {
//   getAllUserManagement,
//   deleteUserManagement,
//   deleteAllUserManagement,
// } from '@/api/modules/userManagement';
// import { obj2Path } from '@/utils/obj2Path';
import { MakeToast } from '@/utils/toastMessage';
import { listNoti } from '@/api/user.js';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/destroyMany',
// };
export default {
  name: 'Home',
  data() {
    return {
      noSort: true,
      checkbox: false,
      listId: [],
      closeMess: true,
      showModal: false,

      readAll: true,

      message: {
        isShowMessage: false,
        isMessage: '',
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
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

      fields1: [
        {
          key: 'title',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.TITLE'),
          class: 'title',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
        {
          key: 'reception_time',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.RECEPTION_TIME'),
          class: 'reception_time',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
      ],
      fields2: [
        {
          key: 'title',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.TITLE'),
          class: 'title',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
        {
          key: 'reception_time',
          sortable: false,
          label: this.$t('HOME_MANAGEMENT.RECEPTION_TIME'),
          class: 'reception_time',
          thStyle: {
            width: '70%',
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
      ],
      fields3: [
        {
          key: 'date',
          sortable: false,
          label: '日時',
          class: 'date',
          thStyle: {
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
        {
          key: 'occupation',
          sortable: false,
          label: '種類',
          class: 'occupation',
          thStyle: {
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
        {
          key: 'name',
          sortable: false,
          label: '氏名',
          class: 'name',
          thStyle: {
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
        {
          key: 'job_name',
          sortable: false,
          label: '求人名  ',
          class: 'job_name',
          thStyle: {
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
        {
          key: 'company_name',
          sortable: false,
          label: '企業名',
          class: 'company_name',
          thStyle: {
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
        },
        {
          key: 'detail',
          sortable: false,
          label: this.$t('BUTTON.DETAIL'),
          class: 'detail',
          thStyle: {
            textAlign: 'center',
            backgroundColor: '#F0ECFF',
            color: '#69609C',
          },
          tdClass: 'text-center',
        },
      ],

      reRender: 0,
      itemsNewMsg: [
        // {
        //   id: 1,
        //   status: 'interview-zoom',
        //   title: '面接Zoom URL発行',
        //   reception_time: '2023/02/01 13:21',
        // },
      ],
      items2: [
        {
          id: 1,
          title: 'システム停止のお知らせ ',
          reception_time: '2023/02/01　13:21',
        },
        {
          id: 2,
          title: 'ABC人材団体',
          reception_time: '2023/02/02　15:30',
        },
      ],
      items3: [
        {
          date: '2023年3月2日（木） ',
          occupation: 'エントリー',
          name: 'Nguyen Thi Anh　ｸﾞｴﾝ ﾁｰ ｱﾝ',
          job_name: '電気設計/制御設計',
          company_name: 'シティコンピュータ株式会社',
          detail: '詳細',
        },
        {
          date: '2023年3月2日（木） ',
          occupation: 'エントリー',
          name: 'Nguyen Thi Anh　ｸﾞｴﾝ ﾁｰ ｱﾝ',
          job_name: '電気制御エンジニア',
          company_name: 'シティコンピュータ株式会社',
          detail: '詳細',
        },
        {
          date: '2023年3月1日（木） ',
          occupation: 'オファー',
          name: 'Nguyen Thi Anh　ｸﾞｴﾝ ﾁｰ ｱﾝ',
          job_name: 'ITエンジニア BE',
          company_name: 'シティコンピュータ株式会社',
          detail: '詳細',
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
  },

  created() {
    this.getListNoti();
  },

  methods: {
    // Table 1:
    async getListNoti() {
      try {
        this.overlay.show = true;
        const response = await listNoti();
        const { code, message } = response.data;
        const { result } = response.data.data;
        if (code === 200) {
          const array = [];
          result.forEach(item => {
            array.push(item['read_at']);
          });
          if (array.includes(null)) {
            this.readAll = false;
          } else {
            this.readAll = true;
          }

          result.map((item) => {
            const itemDataParse = JSON.parse(item.data);
            this.itemsNewMsg.push({
              id: item.id,
              read_at: item.read_at ? item.read_at : '',
              title: itemDataParse.subject ? itemDataParse.subject : '',
              reception_time: itemDataParse.date ? itemDataParse.date : '',
            });
          });
          this.overlay.show = false;
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },

    fillterListUser($event) {
      this.overlay.show = true;
      this.getListAllData($event);
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

    goToDetailScreen(id) {
      this.$router.push(
        { path: `/usermanagement/detail/${id}` },
        (onAbort) => {}
      );
    },

    goToEditScreen(id) {
      this.$router.push(
        { path: `/usermanagement/edit/${id}` },
        (onAbort) => {}
      );
    },

    goToDistribute() {
      // this.$router.push({ path: `/distribute-msg-create` }, (onAbort) => {});
      this.$router.push({ path: `/home/distribute` }, (onAbort) => {});
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

    handleClickTab1() {
      console.log('handleClickTab1');
    },

    handleClickTab2() {
      console.log('handleClickTab2');
    },
    detailMsg(id){
      // console.log('id: ', id);
      this.$router.push({ path: `/home/detail-msg/${id}` });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Job/job.scss';

.nav-item {
  border: 1px solid #ccc;
}

.card-link {
  color: #000;
}

.show-more {
  color: #69609c !important;
}

.distribute-msg-frame {
  background: #FFFFFF;
  border: 1px solid #999;
  margin-top: 1rem;
  padding: 4rem 8rem;
  display: flex;
  flex-direction: column;
}

.table-cell {
  // border: 1px solid red;
  width: 100%;
  height: 40px;
	// padding: 1.2rem 0.5rem ;
	display: flex;
	justify-content: flex-start;
	align-items: center;
  position: relative;
}

.dot-unread {
  border: 1px solid #FF0000;
  background: #FF0000;
  border-radius: 50%;
  width: 8px;
  height: 8px;
  position: relative;
  top: -1.2rem;
  left: -0.3rem;

}

// .dot {
//   display: inline-block;
//   position: absolute;
//   width: 8px;
//   height: 8px;
//   border-radius: 50%;
//   background-color: red;
//   margin-left: 6px;
//   margin-top: -6px;
// }
</style>
