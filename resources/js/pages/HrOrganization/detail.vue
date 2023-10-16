<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template v-if="overlay.show" #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>

    <div
      v-show="!overlay.show"
      class="display-user-management-list page-detail"
    >
      <div
        class="mb-4 d-flex justify-space-between align-start"
        style="gap: 1.8rem"
      >
        <b-col cols="3" class="px-0">
          <b-card class="text-center p-4">
            <b-card-text>
              {{ itemDetail.corporate_name_en }}
            </b-card-text>
            <b-card-text>
              {{ itemDetail.corporate_name_ja }}
            </b-card-text>
            <b-card-text> (ID: {{ itemDetail.id }})</b-card-text>
            <b-card-text>
              {{ itemDetail.account_classification_name }}
            </b-card-text>
            <div class="detail-file mb-3 d-flex flex-column">
              <span>
                {{ $t('HR_REGISTER.CERTIFICATE') }}
              </span>
              <b-link :href="itemDetail.file.file_path" target="_blank">
                {{ itemDetail.file && itemDetail.file.file_name }}
              </b-link>
            </div>
            <span :id="status_color" class="btn-status btn-pending">
              {{ showStatusCompany(itemDetail.status) }}
            </span>
          </b-card>
        </b-col>

        <b-col class="px-0">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <b-col
              class="border-left-title font-weight-bold hr-org-detail-title"
            >
              {{ $t('TITLE.ORGANIZATION_DETAIL') }}
            </b-col>
            <div>
              <!--  variant="secondary mx-1"
                class="back-to-hr-org-list" -->
              <b-button
                class="btn_back--custom mx-1"
                dusk="btn-back"
                @click="handleBackList"
              >
                {{ $t('BUTTON.BACK_TO_LIST') }}
              </b-button>
              <b-button
                class="btn_save--custom mx-1"
                dusk="btn-edit"
                @click="handleGoToEdit"
              >{{ $t('EDIT') }}</b-button>
            </div>
          </div>
          <b-card-group class="d-flex-none p-0">
            <b-list-group>
              <!-- Corporate name -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{ $t('HR_REGISTER.LABEL.CORPORATE_NAME') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.corporate_name_en }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Corporate name (furigana) -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{ $t('HR_REGISTER.LABEL.CORPORATE_NAME_JAPAN') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.corporate_name_ja }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- License No.  -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >
                    {{ $t('HR_REGISTER.LICENSE_NO') }}
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.license_no }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Account classification  -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >
                    {{ $t('HR_REGISTER.ACCOUNT_CLASSIFICATION') }}
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.account_classification_name }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Country  -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{ $t('HR_REGISTER.COUNTRY') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.country_name }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Representative fullname -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{
                    $t('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME')
                  }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.representative_full_name }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Representative full name(furigana) -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >
                    {{ $t('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME') }}
                    <br>
                    {{ '(フリガナ)' }}
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.representative_full_name_furigana }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Representative contact -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{ $t('COMPANY.REPRESENTATIVE_CONTACT') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.representative_contact }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Assignee contact -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{ $t('COMPANY.ASSIGNEE_CONTACT') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.assignee_contact }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Address -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{ $t('COMPANY.ADDRESS') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      >{{ $t('COMPANY.POST_CODE') }}
                      </b-col>
                      <b-col cols="9" class="d-flex align-items-center p-0">
                        {{ itemDetail.post_code }}
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      >{{ $t('COMPANY.CITY') }}
                      </b-col>
                      <b-col cols="9" class="d-flex align-items-center p-0">
                        {{ itemDetail.prefectures }}
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      >{{ $t('COMPANY.DISTINCT') }}
                      </b-col>
                      <b-col cols="9" class="d-flex align-items-center p-0">
                        {{ itemDetail.municipality }}
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      >
                        {{ $t('COMPANY.NUMBER') }}
                      </b-col>
                      <b-col cols="9" class="d-flex align-items-center p-0">
                        {{ itemDetail.number }}
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      >
                        {{ $t('COMPANY.OTHERS') }}
                      </b-col>
                      <b-col cols="9" class="d-flex align-items-center p-0">
                        {{ itemDetail.other }}
                      </b-col>
                    </div>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Mail address -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex flex-column align-items-start bg-gray"
                  >
                    <span>{{ $t('COMPANY.MAIL_ADDRESS') }}</span>
                    <span>（ログインID）</span>
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.mail_address }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- URL -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >{{ $t('URL') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.url }}
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- certificate -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  >
                    {{ $t('HR_REGISTER.CERTIFICATE') }}
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    {{ itemDetail.file && itemDetail.file.file_name }}
                  </b-col>
                </div>
              </b-list-group-item>
            </b-list-group>
          </b-card-group>
        </b-col>
      </div>
    </div>
  </b-overlay>
</template>

<script>
// import { getAllUserManagement, deleteUserManagement, deleteAllUserManagement } from '@/api/modules/userManagement';
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import { getDetailHrOrganization } from '@/api/hrOrganization.js';
import * as HR_ORG from '@/const/hrOrganization.js';
// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'HrOrganizationDetail',
  components: {},
  data() {
    return {
      HR_ORG,
      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },
      reRender: 0,
      status_color: '',

      itemDetail: {
        account_classification: '',
        account_classification_name: '',
        assignee_contact: '',
        certificate_file_id: 0,
        corporate_name_en: '',
        corporate_name_ja: '',
        country: 0,
        country_name: '',
        // created_at: "",
        // deleted_at: null,
        file: {
          id: 0,
          file_name: '',
          file_path: '',
        },
        license_no: '',
        mail_address: '',
        municipality: '',
        number: '',
        other: '',
        post_code: '',
        prefectures: '',
        representative_contact: '',
        representative_full_name: '',
        representative_full_name_furigana: '',
        status: 0,
        status_text: '',
        updated_at: '',
        url: '',
        user_id: 0,
      },
    };
  },

  created() {
    const id = this.$route.params.id;
    this.getDetail(id);
  },

  methods: {
    async getDetail(id) {
      this.overlay.show = true;
      await getDetailHrOrganization(id).then((response) => {
        const { data } = response;
        if (data.code === 200) {
          this.itemDetail = data.data;

          this.itemDetail.account_classification =
            data.data.account_classification;
          this.itemDetail.account_classification_name =
            this.converTextClassifocation(data.data.account_classification);
          this.itemDetail.assignee_contact = data.data.assignee_contact;
          this.itemDetail.certificate_file_id = data.data.certificate_file_id;
          this.itemDetail.corporate_name_en = data.data.corporate_name_en;
          this.itemDetail.corporate_name_ja = data.data.corporate_name_ja;

          this.itemDetail.country = data.data.country;
          this.itemDetail.country_name = this.converTextCountry(
            data.data.country
          );

          // this.itemDetail.created_at = data.data.created_at;
          // this.itemDetail.deleted_at = data.data.deleted_at;
          this.itemDetail.file.id = data.data.file.id;
          this.itemDetail.file.file_name = data.data.file.file_name;
          this.itemDetail.file.file_path =
            process.env.MIX_LARAVEL_TEST_URL + data.data.file.file_path;
          // this.itemDetail.file.file_path = data.data.file.file_path;
          this.itemDetail.license_no = data.data.license_no;
          this.itemDetail.mail_address = data.data.mail_address;
          this.itemDetail.municipality = data.data.municipality;
          this.itemDetail.number = data.data.number;
          this.itemDetail.other = data.data.other;
          this.itemDetail.post_code = data.data.post_code;
          this.itemDetail.prefectures = data.data.prefectures;
          this.itemDetail.representative_contact =
            data.data.representative_contact;
          this.itemDetail.representative_full_name =
            data.data.representative_full_name;
          this.itemDetail.representative_full_name_furigana =
            data.data.representative_full_name_furigana;

          this.itemDetail.status = data.data.status;
          this.itemDetail.status_text = this.converTextStatus(data.data.status);

          this.itemDetail.updated_at = data.data.updated_at;
          this.itemDetail.url = data.data.url;
          this.itemDetail.user_id = data.data.user_id;
        }
        // this.$store.dispatch('loading/setLoading', false);
      });
      this.overlay.show = false;
    },

    converTextCountry(country_id) {
      let COUNTRY = '';
      HR_ORG.country_option.map((option) => {
        if (option.key === country_id) {
          COUNTRY = option.value;
          return;
        }
      });
      return COUNTRY;
    },

    converTextClassifocation(classifocation_id) {
      let CLASSIFICATION = '';
      HR_ORG.account_classification_option.map((option) => {
        if (option.key.toString() === classifocation_id.toString()) {
          CLASSIFICATION = option.value;
          return;
        }
      });
      return CLASSIFICATION;
    },
    converTextStatus(status_id) {
      let STATUS = '';
      switch (status_id) {
        case 1:
          STATUS = 'HR_STATUS.EXAMINATION_PENDING';
          break;

        case 2:
          STATUS = 'HR_STATUS.CONFIRM';
          break;

        case 3:
          STATUS = 'HR_STATUS.REJECT';
          break;

        case 4:
          STATUS = 'HR_STATUS.STOP_USING';
          break;

        default:
          break;
      }

      return STATUS;
    },

    handleGoToEdit() {
      this.$router.push({
        path: `/hr-organization/edit/${this.$route.params.id}`,
      });
    },
    handleBackList() {
      const PROFILE = this.$store.getters.profile;
      if (PROFILE.type !== 5) {
        this.$router.push({ path: `/hr-organization/list` });
      }
    },
    showStatusCompany(statusCompany) {
      if (statusCompany === 1) {
        this.status_color = `status-1-pending`;
        return this.$t('HR_STATUS.EXAMINATION_PENDING');
      }
      if (statusCompany === 2) {
        this.status_color = `status-2-confirm`;
        return this.$t('HR_STATUS.CONFIRM');
      }
      if (statusCompany === 3) {
        this.status_color = `status-34-reject-stop`;
        return this.$t('HR_STATUS.REJECT');
      }
      if (statusCompany === 4) {
        this.status_color = `status-34-reject-stop`;
        return this.$t('HR_STATUS.STOP_USING');
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
::v-deep .display-user-management-list span {
  word-break: break-all;
}
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

.d-flex-none {
  display: unset;
}

.card-body {
  padding: 0;
}

#status-1-pending {
  border: 1px solid #333333 !important;
  color: #333333 !important;
}

#status-2-confirm {
  border: 1px solid #4340ff !important;
  color: #4340ff !important;
}

#status-34-reject-stop {
  border: 1px solid #ff6060 !important;
  color: #ff6060 !important;
}
</style>
