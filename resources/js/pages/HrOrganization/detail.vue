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
        <p style="margin-top: 10px">{{ $t("PLEASE_WAIT") }}</p>
      </div>
    </template>

    <div v-else class="display-user-management-list page-detail">
      <b-row class="mb-4">
        <b-col cols="3">
          <b-card class="text-center p-4">
            <b-card-text class="font-weight-bold">
              {{ itemDetail.corporate_name_en }}
            </b-card-text>
            <b-card-text class="font-weight-bold">
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
              <b-link
                :href="itemDetail.file.file_path"
                target="_blank"
              >
                {{ itemDetail.file && itemDetail.file.file_name }}
              </b-link>
            </div>
            <span class="btn-status btn-pending">
              {{ $t(itemDetail.status_text) }}
            </span>
          </b-card>
        </b-col>

        <b-col cols="9">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <b-col class="border-left-title font-weight-bold">
              {{ $t('TITLE.ORGANIZATION_DETAIL') }}
            </b-col>
            <div>
              <b-button variant="secondary mx-1" @click="handleBackList">
                {{ $t('BUTTON.BACK_TO_LIST') }}
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('HR_REGISTER.CORPORATE_NAME') }}</b-col>
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('COMPANY.CORPORATE_NAME_FURIGANA') }}</b-col>
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME') }}</b-col>
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >
                    {{
                      $t('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME_FURIGANA')
                    }}
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('COMPANY.ADDRESS') }}</b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center font-weight-bold p-0"
                      >{{ $t('COMPANY.POST_CODE') }}
                      </b-col>
                      <b-col
                        cols="9"
                        class="d-flex align-items-center p-0"
                      >102-0093
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center font-weight-bold p-0"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center font-weight-bold p-0"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center font-weight-bold p-0"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center font-weight-bold p-0"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
                  >{{ $t('COMPANY.MAIL_ADDRESS') }}</b-col>
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
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
                    class="d-flex align-items-center bg-gray font-weight-bold"
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
      </b-row>
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
      // console.log('id==>', id);
      await getDetailHrOrganization(id).then((response) => {
        const { data } = response;
        if (data.code === 200) {
          this.itemDetail = data.data;
          // console.log('this.itemDetail===>', this.itemDetail);

          this.itemDetail.account_classification = data.data.account_classification;
          this.itemDetail.account_classification_name = this.converTextClassifocation(data.data.account_classification);
          this.itemDetail.assignee_contact = data.data.assignee_contact;
          this.itemDetail.certificate_file_id = data.data.certificate_file_id;
          this.itemDetail.corporate_name_en = data.data.corporate_name_en;
          this.itemDetail.corporate_name_ja = data.data.corporate_name_ja;

          this.itemDetail.country = data.data.country;
          this.itemDetail.country_name = this.converTextCountry(data.data.country);

          // this.itemDetail.created_at = data.data.created_at;
          // this.itemDetail.deleted_at = data.data.deleted_at;
          this.itemDetail.file.id = data.data.file.id;
          this.itemDetail.file.file_name = data.data.file.file_name;
          this.itemDetail.file.file_path = process.env.MIX_LARAVEL_TEST_URL + data.data.file.file_path;
          // this.itemDetail.file.file_path = data.data.file.file_path;
          this.itemDetail.license_no = data.data.license_no;
          this.itemDetail.mail_address = data.data.mail_address;
          this.itemDetail.municipality = data.data.municipality;
          this.itemDetail.number = data.data.number;
          this.itemDetail.other = data.data.other;
          this.itemDetail.post_code = data.data.post_code;
          this.itemDetail.prefectures = data.data.prefectures;
          this.itemDetail.representative_contact = data.data.representative_contact;
          this.itemDetail.representative_full_name = data.data.representative_full_name;
          this.itemDetail.representative_full_name_furigana = data.data.representative_full_name_furigana;

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
      HR_ORG.country_option.map(option => {
        if (option.key === country_id) {
          COUNTRY = option.value;
          return;
        }
      });
      return COUNTRY;
    },

    converTextClassifocation(classifocation_id) {
      let CLASSIFICATION = '';
      HR_ORG.account_classification_option.map(option => {
        if (option.key.toString() === classifocation_id) {
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
      this.$router.push({ path: `/hr-organization/list` });
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

.d-flex-none {
  display: unset;
}

.card-body {
  padding: 0;
}
</style>
