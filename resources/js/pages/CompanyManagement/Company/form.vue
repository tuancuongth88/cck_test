<!-- Company detail/edit -->
<!-- /company/detail/${id} -->
<!-- /company/edit/${id} -->

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

    <div v-if="role_admin || role_company_management" class="company-edit">
      <div class="company-edit-wrap">
        <!-- 1 Change Status -->
        <div class="company-change-status">
          <div class="company-change-status-wrap">
            <div class="company-change-status-infor">
              <!-- 1 Name, Name jp -->
              <div class="w-100 d-flex flex-column justify-center align-center">
                <div class="infor-name">
                  <span>{{ formData.company_name }}</span>
                </div>
                <div class="infor-name">
                  <span>{{ formData.company_name_jp }}</span>
                </div>
              </div>
              <!-- 2 ID company  -->
              <div
                id="id-company"
                class="w-100 d-flex justify-center align-center"
              >
                <span>(ID : {{ id_company }})</span>
              </div>
            </div>

            <div class="company-change-status-options">
              <div v-if="type_form === 'detail' || checkCompanyRole">
                <div :id="status_color" class="status-cell">
                  <span>{{ showStatusCompany(formData.status) }}</span>
                </div>
              </div>

              <div v-if="type_form === 'edit' && !checkCompanyRole">
                <div v-if="optionsStatusConpany.length <= 0">
                  <div :id="status_color" class="status-cell">
                    <span>{{ showStatusCompany(formData.status) }}</span>
                  </div>
                </div>
                <b-form-select
                  v-model="statusSelect"
                  :options="companyStatusOptions"
                  value-field="value"
                  text-field="text"
                  class="mb-3 dropdown-custom"
                  style="width: 100%"
                  dusk="change_status"
                  :disabled="companyStatusOptions.length === 1"
                  @change="changeStatusCompany"
                />
              </div>
            </div>
          </div>
        </div>
        <!-- 2 Tab Form -->
        <div class="company-edit-content">
          <div class="company-edit-content__head">
            <div class="list-company-head__title">
              <div class="line" />
              <div v-if="type_form === 'detail'">
                <span id="title-company-detail">{{
                  $t('TITLE.COMPANY_DETAIL')
                }}</span>
              </div>
              <div v-if="type_form === 'edit'">
                <span id="title-company-detail">{{
                  $t('TITLE.COMPANY_DETAIL')
                }}</span>
              </div>
            </div>
            <!-- 一覧に戻る Back to list /  編集 Edit -->
            <div v-if="type_form === 'detail'" class="list-company-head__btns">
              <b-button
                id="btn-back-to-list "
                dusk="btn-back"
                class="btn_back--custom"
                @click="backCompanyList"
              >
                {{ $t('BUTTON.BACK_TO_LIST') }}
              </b-button>
              <b-button
                id="btn-edit "
                dusk="btn-edit"
                class="btn_save--custom"
                @click="goToEditCompany"
              >
                {{ $t('BUTTON.EDIT') }}
              </b-button>
            </div>
            <div v-if="type_form === 'edit'" class="list-company-head__btns">
              <b-button
                id="btn-cancel "
                dusk="btn-cancel"
                class="btn_back--custom"
                @click="cancelEditCompany"
              >
                {{ $t('BUTTON.CANCEL') }}
              </b-button>
              <b-button
                id="btn-save"
                dusk="btn-save"
                class="btn_save--custom"
                @click="handleUpdateCompany"
              >
                {{ $t('BUTTON.SAVE') }}
              </b-button>
            </div>
          </div>

          <!-- <b-form-input v-model="formData.full_name_furigana" /> -->
          <div class="company-edit-content__tabs">
            <div class="company-edit-content-tabs-wrap">
              <div class="company-tab-content-child">
                <b-tabs v-model="tabIndex" fill>
                  <b-tab
                    :title="$t('TAB.BASIC_INFORMATION')"
                    :title-link-class="linkClass(0)"
                  >
                    <CompanyBasicInfo
                      id="company-basic-info"
                      :form-data-company="formData"
                      :display-area-code="display_area_code"
                      :error-company="error"
                      @change-status="changeStatus"
                      @pust-area-code="pustAreaCode"
                      @select-render-text="selectRenderText"
                      @handle-change-form="handleChangeForm"
                      @check-email="checkEmail"
                      @handle-change-form-option="handleChangeFormOption"
                    />
                  </b-tab>
                  <!--  -->
                  <!-- 詳細情報 detail information -->
                  <b-tab
                    :title="$t('TAB.DETAIL_INFORMATION')"
                    :title-link-class="linkClass(1)"
                  >
                    <CompanyDetailInfo
                      id="company-detail-info"
                      :form-data-company="formData"
                      :error-company="error"
                      @change-status="changeStatus"
                      @pust-area-code="pustAreaCode"
                      @select-render-text="selectRenderText"
                      @handle-change-form="handleChangeForm"
                      @check-email="checkEmail"
                      @handle-change-form-option="handleChangeFormOption"
                    />
                  </b-tab>
                  <!--  -->
                </b-tabs>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal updateAllData -->
      <!-- @reset-modal="resetModal"
        @handleSubmitModalConfirm="updateAllData" -->
      <b-modal
        ref="'confirm-update-status-company'"
        v-model="modalConfirmUpdateStatus"
        hide-header
        hide-footer
        title=""
      >
        <div class="modal-body-content">
          <div
            class="w-100 d-flex justify-center align-center modal-content-title-del-hr"
            style="padding-top: 30px"
          >
            <span>{{ $t('COMPANY.REALLY') }}{{ statusConpanyLabel
            }}{{ $t('COMPANY.DO_YOU_WANT_TO_CHANGE_TO') }}</span>
          </div>

          <div class="hr-list-btns">
            <div
              id="delete-all-item-hr"
              class="btn"
              @click="closeModalUpdateCompany"
            >
              <span>{{ $t('CANCEL') }}</span>
            </div>

            <div id="import-csv" class="btn accept" @click="updateAllData">
              <span>{{ $t('OK') }}</span>
            </div>
          </div>
        </div>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { validEmail } from '@/utils/validate';
import CompanyBasicInfo from './CompanyBasicInfo';
import CompanyDetailInfo from './CompanyDetailInfo';
import { detailCompany } from '@/api/company.js';
import { updateCompany } from '@/api/company.js';
import { updateStatus } from '@/api/user.js';
import { getListMainjob } from '@/api/job';

export default {
  name: 'CompanyDetail',
  components: {
    CompanyBasicInfo,
    CompanyDetailInfo,
    // ModalConfirm,
  },

  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
      },

      // type_form: 'detail',
      type_form: 'edit',
      tabIndex: 0,
      // ID
      id_company: this.$route.params.id,
      path_company: '',

      role_company_management: true,
      role_admin: true,

      hasChangeStatus: false,
      modalConfirmUpdateStatus: false,

      major_classification_options: [],
      middle_classification_options: [
        {
          key: null,
          type: null,
          value: this.$t('VALIDATE.REQUIRED_SELECT'),
          disabled: true,
          selected: true,
        },
      ],
      formData: {
        company_name: '',
        company_name_jp: '',
        // 3
        major_classification_id: null,
        major_classification_text: '',
        middle_classification_id: null,
        middle_classification_text: '',
        // 4
        post_code: '',
        prefectures: '',
        municipality: '',
        number: '',
        other: '',
        // 5
        telephone_number_id: null,
        telephone_number: '',
        telephone_number_detail: '',
        telephone_number_put_api: '',
        //
        mail_address: '',
        url: '',
        //
        job_title: '',
        full_name: '',
        full_name_furigana: '',
        //
        representative_contact_id: null,
        representative_contact: '',
        representative_contact_detail: '',
        representative_contact_put_api: '',

        assignee_contact_id: null,
        assignee_contact: '',
        assignee_contact_detail: '',
        assignee_contact_put_api: '',
        // / 2 企業情報 Company information
        establishment_year: '',
        startup_year: '',
        capital: '',
        proceeds: '',
        number_of_staffs: '',

        number_of_operations: '',
        number_of_shops: '',
        number_of_factories: '',
        fiscal_year: '',

        status: null,
      },

      display_area_code: {
        telephone_number: '',
        representative_contact: '',
        assignee_contact: '',
      },

      error: {
        company_name: true,
        company_name_jp: true,
        major_classification_id: true,
        middle_classification_id: true,
        post_code: true,
        prefectures: true,
        municipality: true,
        number: true,
        // other: true,
        telephone_number_id: true,
        telephone_number: true,
        mail_address: true,
        url: true,
        job_title: true,
        full_name: true,
        full_name_furigana: true,
        // representative_contact_id: true,
        // representative_contact: true,
        assignee_contact_id: true,
        assignee_contact: true,
      },
      optionsStatusConpany: [
        {
          value: 1,
          label: this.$t('HR_STATUS.EXAMINATION_PENDING'),
          translate: 'examination pending',
        },
        {
          value: 2,
          label: this.$t('HR_STATUS.CONFIRM'),
          translatel: 'confirm',
        },
        { value: 3, label: this.$t('HR_STATUS.REJECT'), translatel: 'decline' },
        {
          value: 4,
          label: this.$t('HR_STATUS.STOP_USING'),
          translatebel: 'reject',
        },
      ],
      statusSelect: null,
      idStatusSelect: null,
      idStatusCompany: null,
      status_color: '',
      statusConpanyLabelBefore: '',
      statusConpanyLabel: '',
      itemStatusCompanyselectedBefore: '',
      itemStatusCompanyselected: '',

      error_toast_message: '',
      //
    };
  },

  computed: {
    checkCompanyRole() {
      const PROFILE = this.$store.getters.profile;
      return PROFILE.type === 4;
    },
    companyStatusOptions() {
      let OPTIONS = [];
      switch (this.idStatusSelect) {
        case 1:
          OPTIONS = [
            {
              value: 1,
              text: this.$t('HR_STATUS.EXAMINATION_PENDING'),
              // translate: 'examination pending',
            },
            {
              value: 2,
              text: this.$t('HR_STATUS.CONFIRM'),
              // translatel: 'confirm',
            },
            {
              value: 3,
              text: this.$t('HR_STATUS.REJECT'),
              // translatel: 'decline',
            },
          ];
          break;

        case 2:
          OPTIONS = [
            {
              value: 2,
              text: this.$t('HR_STATUS.CONFIRM'),
              // translatel: 'confirm',
            },
            {
              value: 4,
              text: this.$t('HR_STATUS.STOP_USING'),
              // translatebel: 'reject',
            },
          ];
          break;
        case 3:
          OPTIONS = [
            {
              value: 3,
              text: this.$t('HR_STATUS.REJECT'),
              // translatel: 'decline',
            },
          ];
          break;
        case 4:
          OPTIONS = [
            {
              value: 4,
              text: this.$t('HR_STATUS.STOP_USING'),
              // translatebel: 'reject',
            },
          ];
          break;

        default:
          break;
      }

      return OPTIONS;
    },
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
    // currChange() {
    //   return this.queryData.page;
    // },
  },

  created() {
    this.getPathUrl();
    this.getCompanyDetail();
    this.getListMainjob();
  },

  methods: {
    getPathUrl() {
      const path = this.$route.path;
      if (path.includes('detail')) {
        this.path_company = path;
        this.type_form = 'detail';
      }
      if (path.includes('edit')) {
        this.path_company = path;
        this.type_form = 'edit';
      }
    },
    changeStatus() {
      if (this.type_form === 'detail') {
        this.type_form = 'edit';
      } else if (this.type_form === 'edit') {
        this.type_form = 'detail';
      }
    },
    resetModal() {
      this.typeStatus = 0;
      this.isDisplaySecond = false;
      this.isDisplaySecondOffical = false;
      this.optionSelectStatus = '';
      this.optionSelectSecondStatus = '';
    },
    linkClass(idx) {
      if (this.tabIndex === idx) {
        return ['bg-primary', 'text-light'];
      } else {
        return ['bg-light', 'text-info'];
      }
    },
    //
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
    //
    pustAreaCode(type_select, type_option) {
      switch (type_select) {
        case 'telephone_number':
          if (type_option) {
            this.display_area_code.telephone_number = String(type_option);
            this.formData.telephone_number_put_api = String(type_option);
          } else {
            // Xóa các input
            this.display_area_code.telephone_number = '';
            this.formData.telephone_number = '';
            this.formData.telephone_number_put_api = '';
            // Tắt validate
            this.error.telephone_number = true;
          }
          break;
        case 'representative_contact':
          if (type_option) {
            this.display_area_code.representative_contact = String(type_option);
            this.formData.representative_contact_put_api = String(type_option);
          } else {
            this.display_area_code.representative_contact = '';
            this.formData.representative_contact = '';
            this.formData.representative_contact_put_api = '';
            this.error.representative_contact = true;
          }
          break;
        case 'assignee_contact':
          if (type_option) {
            this.display_area_code.assignee_contact = String(type_option);
            this.formData.assignee_contact_put_api = String(type_option);
          } else {
            this.display_area_code.assignee_contact = '';
            this.formData.assignee_contact = '';
            this.formData.assignee_contact_put_api = '';
            this.error.assignee_contact = true;
          }
          break;
        default:
          break;
      }
    },
    selectRenderText(type_select, id_select, option_select) {
      switch (type_select) {
        // 1 major classification
        case 'major_classification_id':
          if (id_select) {
            let text = '';
            option_select.map((item) => {
              if (item.id === id_select) {
                text = item.name_ja;
              }
            });
            this.formData.major_classification_text = text;
          }
          break;
        // 2 middle classification
        case 'middle_classification_id':
          if (id_select) {
            let text = '';
            option_select.map((item) => {
              if (item.id === id_select) {
                text = item.name_ja;
              }
            });
            this.formData.middle_classification_text = text;
          }
          break;
        //
        default:
          break;
      }
    },
    phonePutApi(areaCode, string_phone, type) {
      // this.$emit('phone-put-api', areaCode, string_phone);
      if (areaCode || string_phone) {
        const phone_convert = `${areaCode} ${string_phone}`;
        return phone_convert;
      }
      if (type === 'representative_contact') {
        if (string_phone === '') {
          const phone_convert = ``;
          return phone_convert;
        }
      }
    },

    handleChangeForm(event, field) {
      const newValue = event;
      switch (field) {
        case 'company_name':
          this.error.company_name = true;
          if (newValue) {
            this.error.company_name = true;
          } else {
            this.error.company_name = false;
          }
          break;
        // 2
        case 'company_name_jp':
          this.error.company_name_jp = true;
          if (newValue) {
            this.error.company_name_jp = true;
          } else {
            this.error.company_name_jp = false;
          }
          break;
        // 3.1
        case 'major_classification_id':
          this.error.major_classification_id = true;
          if (newValue) {
            this.error.major_classification_id = true;
          } else {
            this.error.major_classification_id = false;
          }
          break;
        // 3.2
        case 'middle_classification_id':
          this.error.middle_classification_id = true;
          if (newValue) {
            if (this.findItemCount > 0) {
              this.error.middle_classification_id = true;
            }
          } else {
            if (this.findItemCount > 0) {
              this.error.middle_classification_id = false;
            }
          }
          break;
        // 4.1
        case 'post_code':
          this.error.post_code = true;
          if (newValue) {
            this.error.post_code = true;
          } else {
            this.error.post_code = false;
          }
          break;
        // 4.2
        case 'prefectures':
          this.error.prefectures = true;
          if (newValue) {
            this.error.prefectures = true;
          } else {
            this.error.prefectures = false;
          }
          break;
        // 4.3
        case 'municipality':
          this.error.municipality = true;
          if (newValue) {
            this.error.municipality = true;
          } else {
            this.error.municipality = false;
          }
          break;
        // 4.4
        case 'number':
          this.error.number = true;
          if (newValue) {
            this.error.number = true;
          } else {
            this.error.number = false;
          }
          break;
          // 4.5 Ko bắt buộc

        // 5 input
        case 'telephone_number':
          this.error.telephone_number = true;
          if (newValue) {
            this.error.telephone_number = true;
            this.formData.telephone_number_detail = this.phonePutApi(
              this.display_area_code.telephone_number,
              this.formData.telephone_number
            );
          } else {
            this.error.telephone_number = false;
          }
          break;
        // 6
        case 'mail_address':
          this.error.mail_address = true;
          if (newValue) {
            this.error.mail_address = true;
          } else {
            this.error.mail_address = false;
          }
          break;
        // 7
        case 'url':
          this.error.url = true;
          if (newValue) {
            this.error.url = true;
          } else {
            this.error.url = false;
          }
          break;
        // 8.1
        case 'job_title':
          this.error.job_title = true;
          if (newValue) {
            this.error.job_title = true;
          } else {
            this.error.job_title = false;
          }
          break;
        // 8.2
        case 'full_name':
          this.error.full_name = true;
          if (newValue) {
            this.error.full_name = true;
          } else {
            this.error.full_name = false;
          }
          break;
        // 8.3
        case 'full_name_furigana':
          this.error.full_name_furigana = true;
          if (newValue) {
            this.error.full_name_furigana = true;
          } else {
            this.error.full_name_furigana = false;
          }
          break;

        // 9 input
        case 'representative_contact':
          this.error.representative_contact = true;
          if (newValue) {
            // this.error.representative_contact = true;
            this.formData.representative_contact_detail = this.phonePutApi(
              this.display_area_code.representative_contact,
              this.formData.representative_contact
            );
          } else {
            // this.error.representative_contact = false;
            this.display_area_code.representative_contact = '';
          }
          break;
        // 10
        case 'assignee_contact_id':
          this.error.assignee_contact_id = true;
          if (newValue) {
            this.error.assignee_contact_id = true;
          } else {
            this.error.assignee_contact_id = false;
          }
          break;
        // 10 input
        case 'assignee_contact':
          this.error.assignee_contact = true;
          if (newValue) {
            this.error.assignee_contact = true;
            this.formData.representative_contact_detail = this.phonePutApi(
              this.display_area_code.assignee_contact,
              this.formData.assignee_contact
            );
          } else {
            this.error.assignee_contact = false;
          }
          break;
        // ...
        default:
          break;
      }
    },

    checkValidate() {
      if (this.formData.company_name === '') {
        this.error.company_name = false;
      }
      if (this.formData.company_name_jp === '') {
        this.error.company_name_jp = false;
      }
      if (this.formData.major_classification_id === null) {
        this.error.major_classification_id = false;
        this.error.middle_classification_id = true;
      } else if (this.formData.major_classification_id !== null) {
        if (this.formData.middle_classification_id === null) {
          this.error.middle_classification_id = false;
        } else {
          this.error.middle_classification_id = true;
        }
      }

      if (this.formData.post_code === '') {
        this.error.post_code = false;
      }
      if (this.formData.prefectures === '') {
        this.error.prefectures = false;
      }
      if (this.formData.municipality === '') {
        this.error.municipality = false;
      }
      if (this.formData.number === '') {
        this.error.number = false;
      }
      // Not require
      // if (this.formData.other === '') {
      //   this.error.other = false;
      // }

      if (this.display_area_code.telephone_number === '') {
        // option khác
        this.error.telephone_number_id = false;
        this.error.telephone_number = true;
      } else if (this.display_area_code.telephone_number !== '') {
        if (!this.formData.telephone_number) {
          this.error.telephone_number = false;
        } else {
          this.error.telephone_number = true;
        }
      }

      if (this.formData.mail_address === '') {
        this.error.mail_address = false;
      }
      if (this.formData.url === '') {
        this.error.url = false;
      }

      if (this.formData.job_title === '') {
        this.error.job_title = false;
      }
      if (this.formData.full_name === '') {
        this.error.full_name = false;
      }
      if (this.formData.full_name_furigana === '') {
        this.error.full_name_furigana = false;
      }
      // 9
      // if (this.display_area_code.representative_contact === '') { // option khác
      //   this.error.representative_contact_id = false;
      //   this.error.representative_contact = true;
      // } else if (this.display_area_code.representative_contact !== '') {
      //   if (!this.formData.representative_contact) {
      //     this.error.representative_contact = false;
      //   } else {
      //     this.error.representative_contact = true;
      //   }
      // }
      // 10
      if (this.display_area_code.assignee_contact === '') {
        // option khác
        this.error.assignee_contact_id = false;
        this.error.assignee_contact = true;
      } else if (this.display_area_code.assignee_contact !== '') {
        if (!this.formData.assignee_contact) {
          this.error.assignee_contact = false;
        } else {
          this.error.assignee_contact = true;
        }
      }
      // True && all
      if (
        this.formData.company_name !== '' &&
        this.formData.company_name_jp !== '' &&
        this.formData.major_classification_id !== null &&
        this.formData.middle_classification_id !== null &&
        this.formData.post_code !== '' &&
        this.formData.prefectures !== '' &&
        this.formData.municipality !== '' &&
        this.formData.number !== '' &&
        // this.formData.other !== '' &&
        this.display_area_code.telephone_number && // option khác
        this.formData.telephone_number &&
        this.formData.mail_address &&
        this.formData.url !== '' &&
        this.formData.job_title !== '' &&
        this.formData.full_name !== '' &&
        // this.display_area_code.representative_contact && // option khác
        // this.formData.representative_contact &&

        this.display_area_code.assignee_contact && // option khác
        this.formData.assignee_contact
      ) {
        return true;
      }
    },

    checkEmail() {
      if (validEmail(this.formData.mail_address)) {
        return true;
      } else {
        return false;
      }
    },

    handleChangeFormOption(event, type_dropdown) {
      switch (type_dropdown) {
        // 5 option
        case 'telephone_number_id':
          this.error.telephone_number_id = true;
          if (event !== '') {
            this.error.telephone_number_id = true;
          } else {
            this.error.telephone_number_id = false;
          }
          break;
        // 9
        // case 'representative_contact_id':
        //   this.error.representative_contact_id = true;
        //   if (event !== '') {
        //     this.error.representative_contact_id = true;
        //   } else {
        //     this.error.representative_contact_id = false;
        //   }
        //   break;
        // 10
        case 'assignee_contact_id':
          this.error.assignee_contact_id = true;
          if (event !== '') {
            this.error.assignee_contact_id = true;
          } else {
            this.error.assignee_contact_id = false;
          }
          break;
        default:
          break;
      }
    },
    backCompanyList() {
      const PROFILE = this.$store.getters.profile;
      if (PROFILE.type === 4) {
        this.$router.push({ name: 'Home' }, (onAbort) => {});
      } else {
        this.$router.push({ path: `/company/list` }, (onAbort) => {});
      }
    },
    goToEditCompany() {
      this.$router.push({ path: `/company/edit/${this.id_company}` });
    },
    cancelEditCompany() {
      this.$router.push({ path: `/company/detail/${this.id_company}` });
    },
    showStatusConpanyLabelInit() {
      if (this.formData) {
        this.idStatusCompany = this.formData.status;
        this.statusConpanyLabel = '';
        this.optionsStatusConpany.map((item) => {
          if (this.formData.status === item.value) {
            this.statusConpanyLabelBefore = item.label;
            this.itemStatusCompanyselectedBefore = item.value;
            this.statusConpanyLabel = item.label;
          }
        });
      }
    },

    // handelChangeStatusCompany(item) {
    //   console.log('item khi chọn selectbox====>', item);
    //   if (item) {
    //     this.statusConpanyLabel = item.label;
    //     this.itemStatusCompanyselected = item.value;
    //     this.idStatusCompany = item.value; // ! Gửi lên
    //     this.hasChangeStatus = true;
    //   }
    // },

    changeStatusCompany() {
      const findSelected = this.companyStatusOptions.find(
        (item) => item.value === this.statusSelect
      );
      if (findSelected) {
        this.statusConpanyLabel = findSelected.text;
      }
      if (this.statusSelect === this.idStatusSelect) {
        this.hasChangeStatus = false;
      } else {
        this.hasChangeStatus = true;
      }
    },

    changeUIStatusCompany(IditemSelect) {
      // 1 status company = 1
      if (Number(this.formData.status) === 1) {
        this.optionsStatusConpany = [
          {
            value: 1,
            label: this.$t('HR_STATUS.EXAMINATION_PENDING'),
            translate: 'examination pending',
          },
          {
            value: 2,
            label: this.$t('HR_STATUS.CONFIRM'),
            translatel: 'confirm',
          },
          {
            value: 3,
            label: this.$t('HR_STATUS.REJECT'),
            translatel: 'decline',
          },
        ];
        if (IditemSelect) {
          // UI: Chọn đổi status 2 -> còn 3
          // if (IditemSelect === 2) {
          //   this.optionsStatusConpany = [{ value: 3, label: this.$t('HR_STATUS.REJECT'), translatel: 'decline' }];
          // }
          // // UI: Chọn 3 đổi status 3 -> option rỗng
          // if (IditemSelect === 3) {
          //   this.optionsStatusConpany = [];
          // }
        }
      }
      // 2 status company = 2 còn 4
      if (Number(this.formData.status) === 2) {
        this.optionsStatusConpany = [
          {
            value: 2,
            label: this.$t('HR_STATUS.CONFIRM'),
            translatel: 'confirm',
          },
          {
            value: 4,
            label: this.$t('HR_STATUS.STOP_USING'),
            translatebel: 'reject',
          },
        ];
        if (IditemSelect) {
          // UI: Chọn đổi status 4 -> option rỗng
          // if (IditemSelect === 4) {
          //   this.optionsStatusConpany = [];
          // }
        }
      }
      // 3 status company = 3
      if (Number(this.formData.status) === 3) {
        this.optionsStatusConpany = [
          {
            value: 3,
            label: this.$t('HR_STATUS.REJECT'),
            translatel: 'decline',
          },
        ];
      }

      if (Number(this.formData.status) === 4) {
        this.optionsStatusConpany = [
          {
            value: 4,
            label: this.$t('HR_STATUS.STOP_USING'),
            translatebel: 'reject',
          },
        ];
      }
      //
    },

    openModalUpdateCompany() {
      // this.$bus.emit('open-modal', 'confirm-update-status-company');
      this.modalConfirmUpdateStatus = true;
    },
    closeModalUpdateCompany() {
      // this.$bus.emit('close-modal', 'confirm-update-status-company');
      this.modalConfirmUpdateStatus = false;
    },

    async getListMainjob() {
      try {
        const response = await getListMainjob();
        const { data, code } = response.data;

        if (code === 200) {
          data.map((item) => {
            if (this.formData.major_classification_id === item.id) {
              this.formData.major_classification_text = item.name_ja;
            }
          });
          this.major_classification_options = []; // Reset arr
          this.major_classification_options = data;

          const id_major = this.formData.major_classification_id;
          let middle_option = [];
          for (let i = 0; i < this.major_classification_options.length; i++) {
            if (this.major_classification_options[i].id === id_major) {
              middle_option = this.major_classification_options[i].job_info;
            }
          }
          this.middle_classification_options = [];
          this.middle_classification_options = middle_option;

          middle_option.map((item) => {
            if (this.formData.middle_classification_id === item.id) {
              this.formData.middle_classification_text = item.name_ja;
            }
          });
        }
      } catch (error) {
        console.log(error);
      }
    },

    async updateStatusCompany() {
      this.closeModalUpdateCompany();
      const PARAMS = {
        company_id: this.id_company,
        // status: this.idStatusCompany, // Nhận ID status mới theo select
        status: this.statusSelect,
      };

      try {
        this.overlay.show = true;
        const response = await updateStatus(PARAMS);
        const { code, message } = response.data;
        if (code === 200) {
          this.overlay.show = false;
          await this.getCompanyDetail(); // Lấy lại số status mới nhất
          this.changeUIStatusCompany(this.itemStatusCompanyselected);
          // MakeToast({
          //   variant: 'success',
          //   title: 'SUCCESS',
          //   content: message || '',
          // });
        } else {
          this.overlay.show = false;
          this.statusConpanyLabel = this.statusConpanyLabelBefore;
          this.itemStatusCompanyselected = this.itemStatusCompanyselectedBefore;
          // MakeToast({
          //   variant: 'danger',
          //   title: this.$t('DANGER'),
          //   content: message || '',
          // });
          this.error_toast_message = message;
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },
    // GET Detail
    async getCompanyDetail() {
      try {
        this.overlay.show = true;
        const response = await detailCompany(this.id_company);
        const { data, code, message } = response.data;
        if (code === 200) {
          this.overlay.show = false;
          //
          this.convertDataCompanyDetail(data);
          this.showStatusConpanyLabelInit();
          this.changeUIStatusCompany(data.status);
          this.statusSelect = data.status;
          this.idStatusSelect = data.status;
          this.itemStatusCompanyselected = data.status;
          //
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    convertAreaCode(strPhone) {
      if (strPhone) {
        const patternHasCode = /\+81|\+84/;
        if (patternHasCode.test(strPhone)) {
          const patternCode = /\+81|\+84/g;
          const matches = strPhone.match(patternCode);
          return matches[0];
        } else {
          return '';
        }
      }
    },
    convertPhone(strPhone) {
      const patternHasCode = /\+81|\+84/;
      if (strPhone) {
        if (patternHasCode.test(strPhone)) {
          const patternPhone = /(\+81|\+84|\s)/g;
          const newString = strPhone.replace(patternPhone, '');
          return newString;
        } else {
          return strPhone;
        }
      }
    },
    convertDataCompanyDetail(dataCompany) {
      this.formData.company_name = dataCompany.company_name;
      this.formData.company_name_jp = dataCompany.company_name_jp;
      // 3
      this.formData.major_classification_id = dataCompany.major_classification;
      this.formData.major_classification_text = this.selectRenderText(
        'major_classification_id',
        dataCompany.major_classification,
        this.major_classification_options
      );

      this.formData.middle_classification_id =
        dataCompany.middle_classification;
      this.formData.middle_classification_text = this.selectRenderText(
        'middle_classification_id',
        dataCompany.middle_classification,
        this.middle_classification_options
      );
      // 4
      this.formData.post_code = dataCompany.post_code;
      this.formData.prefectures = dataCompany.prefectures;
      this.formData.municipality = dataCompany.municipality;
      this.formData.number = dataCompany.number;
      this.formData.other = dataCompany.other;
      // 5
      this.display_area_code.telephone_number = this.convertAreaCode(
        dataCompany.telephone_number
      );
      this.formData.telephone_number = this.convertPhone(
        dataCompany.telephone_number
      );
      //
      this.formData.mail_address = dataCompany.mail_address;
      this.formData.url = dataCompany.url;
      //
      this.formData.job_title = dataCompany.job_title;
      this.formData.full_name = dataCompany.full_name;
      this.formData.full_name_furigana = dataCompany.full_name_furigana;
      //
      this.display_area_code.representative_contact = this.convertAreaCode(
        dataCompany.representative_contact
      );
      this.formData.representative_contact = this.convertPhone(
        dataCompany.representative_contact
      );
      this.display_area_code.assignee_contact = this.convertAreaCode(
        dataCompany.assignee_contact
      );
      this.formData.assignee_contact = this.convertPhone(
        dataCompany.assignee_contact
      );
      // 2 企業情報 Company information
      this.formData.establishment_year = dataCompany.establishment_year;
      this.formData.startup_year = dataCompany.startup_year;
      this.formData.capital = dataCompany.capital;
      this.formData.proceeds = dataCompany.proceeds;
      this.formData.number_of_staffs = dataCompany.number_of_staffs;
      this.formData.number_of_operations = dataCompany.number_of_operations;
      this.formData.number_of_shops = dataCompany.number_of_shops;
      this.formData.number_of_factories = dataCompany.number_of_factory;
      this.formData.fiscal_year = dataCompany.fiscal;

      this.formData.status = dataCompany.status;
    },

    async handleUpdateCompany() {
      if (this.hasChangeStatus) {
        this.openModalUpdateCompany();
      } else {
        this.updateCompany();
      }
    },
    async updateCompany() {
      this.checkValidate();
      this.checkEmail();
      const resCheckValidate = this.checkValidate();
      const resCheckEmail = this.checkEmail();
      if (resCheckValidate) {
        if (!resCheckEmail) {
          this.hasChangeStatus = false;
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: this.$t('PLEASE_ENTER_THE_CORRECT_EMAIL_ADDRESS_FORMAT'),
          });
        }
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: this.$t('HR_REGISTER.PLEASE_ENTER_ALL_REQUIRED_INFORMATION'),
        });
      }
      console.log('đi vào đây đúng không', this.formData);

      if (resCheckValidate && resCheckEmail) {
        const BODY = {
          company_name: this.formData.company_name,
          company_name_jp: this.formData.company_name_jp,
          major_classification: this.formData.major_classification_id,
          middle_classification: this.formData.middle_classification_id,
          post_code: this.formData.post_code,
          prefectures: this.formData.prefectures,
          municipality: this.formData.municipality,
          number: this.formData.number,
          other: this.formData.other,
          telephone_number: this.phonePutApi(
            this.display_area_code.telephone_number,
            this.formData.telephone_number
          ),
          mail_address: this.formData.mail_address,
          url: this.formData.url,
          job_title: this.formData.job_title,
          full_name: this.formData.full_name,
          full_name_furigana: this.formData.full_name_furigana,
          representative_contact: this.formData.representative_contact
            ? this.phonePutApi(
              this.display_area_code.representative_contact,
              this.formData.representative_contact,
              'representative_contact'
            )
            : '',
          assignee_contact: this.phonePutApi(
            this.display_area_code.assignee_contact,
            this.formData.assignee_contact
          ),
          // 2 企業情報 Company information
          establishment_year: this.formData.establishment_year,
          startup_year: this.formData.startup_year,
          capital: this.formData.capital,
          proceeds: this.formData.proceeds,
          number_of_staffs: this.formData.number_of_staffs,
          number_of_operations: this.formData.number_of_operations,
          number_of_shops: this.formData.number_of_shops,
          number_of_factory: this.formData.number_of_factories,
          fiscal: this.formData.fiscal_year,
          // status: this.idStatusCompany, // Cũng thay dc status
          is_create: 1,
        };

        try {
          this.overlay.show = true;
          const response = await updateCompany(this.id_company, BODY);
          const { code, message } = response.data;
          if (code === 200) {
            this.overlay.show = false;
            // this.updateStatusCompany(); //
            this.closeModalUpdateCompany();
            if (this.error_toast_message) {
              MakeToast({
                variant: 'danger',
                title: this.$t('DANGER'),
                content: this.error_toast_message,
              });
            } else {
              MakeToast({
                variant: 'success',
                title: this.$t('SUCCESS'),
                content: message || this.$t('HR_ORG_COMPANY_UPDATE_SUCCESS'),
              });
            }
            this.$router.push({ path: `/company/detail/${this.id_company}` });
            //
          } else {
            this.overlay.show = false;
            MakeToast({
              variant: 'danger',
              title: this.$t('DANGER'),
              content: message || 'アップデートに失敗しました',
            });
            MakeToast({
              variant: 'danger',
              title: this.$t('DANGER'),
              content: this.error_toast_message,
            });
          }
          this.error_toast_message = '';
        } catch (error) {
          this.overlay.show = false;
          console.log(error);
        }
      }
    },
    async updateAllData() {
      await this.updateStatusCompany();
      this.updateCompany();
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/components/Modal/ModalStyle.scss';

.company-edit {
  // border: 1px solid blue;
  width: 100%;
  min-height: 100vh;
  padding: 0 0rem 3.75rem 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: stretch;
}

::v-deep .company-edit span {
  word-break: break-all;
}
.company-edit-wrap {
  // border: 1px solid red;
  width: 100%;
  height: 100%;
  flex: 1;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1.8rem;
}
.company-change-status {
  // border: 1px solid blue;
  max-width: 276px;
  width: 100%;
  //
  min-height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1.75rem;
}
.company-change-status-wrap {
  border: 1px solid $borderBasicInfor;
  border-radius: 3px;
  width: 100%;
  min-height: 368px;
  padding: 2.5rem 1rem 4.5rem 1rem;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
  gap: 8rem;
}
.company-change-status-infor {
  // border: 1px solid;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 1.5rem;
  color: $titleClassify;
  font-size: 16px;
  font-weight: 400;
}
.infor-name {
  color: $titleClassify;
  font-size: 16px;
  font-weight: 400;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}
.company-change-status-options {
  // border: 1px solid;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1rem;
}
.status-cell {
  border: 1px solid $applying;
  color: $applying;
  border-radius: 10px;
  min-width: 152px;
  height: 38px;
  padding: 0 2.25rem;
  font-size: 16px;
  font-weight: 400;
  display: flex;
  justify-content: center;
  align-items: center;
}
// 2
.company-edit-content {
  // border: 1px solid;
  width: 100%;
  display: flex;
  flex-direction: column;

  justify-content: flex-start;
  align-items: stretch;
  gap: 1rem;
}
.company-edit-content__head {
  // border: 1px solid;
  width: 100%;
  height: 36px;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  gap: 1rem;
}
.list-company-head__title {
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1rem;
  font-size: 24px;
  font-weight: 700;
}
.list-company-head__btns {
  // border: 1px solid red;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.25rem;
  font-size: 16px;
  font-weight: 400;
  color: $white;
}
.line {
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
}

.company-edit-content__tabs {
  // border: 1px solid;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1rem;
}
.company-edit-content-tabs-wrap {
  // border: 1px solid;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1rem;
}
.company-edit-content-tabs-wrap {
  // border: 1px solid;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1rem;
}
.company-tab-content-child {
  width: 100%;
}
.modal-comfirm-update {
  width: 100%;
  padding: 8.5rem 5rem 5.5rem 5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  gap: 6.625rem;
}
.modal-comfirm-update__title {
  font-size: 24px;
  font-weight: 400;
  text-align: center;
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

::v-deep .dropdown-custom {
  // border: 1px solid red !important;
  min-width: 152px;
  & > .dropdown-menu {
    transform: translate(0px, 38px) !important;
    min-width: 152px !important;
  }
}
</style>
