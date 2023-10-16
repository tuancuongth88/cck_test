<template>
  <div class="job-detail-for-hr">
    <div class="job-detail-for-hr-container">
      <div class="job-detail-for-hr-form">
        <div class="job-detail-for-hr-form__head">
          <div class="h-100 d-flex justify-start align-stretch">
            <div class="line" />

            <div class="job-detail-for-hr-form__head-title pl-4">
              <span>{{ $t('INFORMATION_JOB') }}</span>
            </div>
          </div>

          <div class="job-detail-for-hr-form__head-btn">
            <div class="btn btn-back" @click="goBack()">
              <span>{{ $t('RETURN') }}</span>
            </div>
          </div>
        </div>
        <b-overlay
          :show="overlay.show"
          :blur="overlay.blur"
          :rounded="overlay.rounded"
          :variant="overlay.variant"
          :opacity="overlay.opacity"
          class="w-100"
        >
          <div class="job-detail-for-hr-form-autox">
            <div class="job-detail-for-hr-form-page">
              <div class="form-page-area">
                <div class="form-page-area__head">
                  <span style="font-weight: bold">{{
                    $t('REGIST_DOCUMENT')
                  }}</span>
                </div>

                <div class="form-page-area__content">
                  <div class="form-page-area-content-wrap">
                    <div
                      v-for="(item, index) in vItems"
                      :key="`item-index-${index}`"
                      class="form-item-row"
                    >
                      <b-row
                        class="w-100 m-0"
                        style="border-bottom: 1px solid #c6c6c6"
                      >
                        <b-col style="background-color: #f8f8f8">
                          <div class="name-holder">
                            <span style="font-size: 16px">{{
                              `${item['full_name']} ${item['full_name_ja']} (ID:${item['id']})`
                            }}</span>
                          </div>
                        </b-col>

                        <b-col>
                          <div
                            class="d-flex flex-column"
                            style="margin-top: 20px"
                          >
                            <div class="d-flex flex-row">
                              <span class="title">
                                {{ $t('MOTIVATION') }}
                              </span>
                              <Require />
                            </div>

                            <div class="d-flex flex-row file-upload-area">
                              <b-button
                                :class="
                                  !error[index]['file_motivation']
                                    ? 'default-btn is-invalid'
                                    : 'default-btn'
                                "
                                @click="openFileSelect(1, index)"
                              >
                                <span class="text">{{
                                  $t('HR_REGISTER.SELECT_FILE')
                                }}</span>
                              </b-button>

                              <input
                                :ref="`fileInputMotivation${index}`"
                                type="file"
                                style="display: none"
                                @change="
                                  handleFileSelect(
                                    1,
                                    $event,
                                    item['id'],
                                    '',
                                    index
                                  )
                                "
                              >

                              <span
                                v-if="item['file_motivation']['file']"
                                class="description"
                              >{{
                                item['file_motivation']['file_name']
                              }}</span>
                              <span v-else class="description">{{
                                $t('isMessage')
                              }}</span>
                            </div>

                            <div class="d-flex">
                              <b-form-invalid-feedback
                                :state="error[index]['file_motivation']"
                              >
                                <span>{{
                                  $t('VALIDATE.REQUIRED_SELECT')
                                }}</span>
                              </b-form-invalid-feedback>
                            </div>

                            <div class="d-flex flex-row mt-2">
                              <span class="title">{{
                                $t('RECOMMENDATION')
                              }}</span>
                              <Arbitrarily />
                            </div>

                            <div class="d-flex flex-row file-upload-area">
                              <b-button
                                class="default-btn"
                                @click="openFileSelect(2, index)"
                              >
                                <span class="text">{{
                                  $t('HR_REGISTER.SELECT_FILE')
                                }}</span>
                              </b-button>

                              <input
                                :ref="`fileInputRecommendation${index}`"
                                type="file"
                                style="display: none"
                                @change="
                                  handleFileSelect(
                                    2,
                                    $event,
                                    item['id'],
                                    '',
                                    index
                                  )
                                "
                              >

                              <span
                                v-if="item['file_recommendation']['file']"
                                class="description"
                              >{{
                                item['file_recommendation']['file_name']
                              }}</span>
                              <span v-else class="description">{{
                                $t('isMessage')
                              }}</span>
                            </div>

                            <div
                              v-for="(file, fileIndex) in item['file_others']"
                              :key="fileIndex"
                              class="d-flex flex-column"
                              style="margin-top: 10px"
                            >
                              <div class="d-flex flex-row">
                                <!-- <span class="title">{{
                                  `その他ファイル${fileIndex + 1}`
                                }}</span> -->
                                <span class="title">{{
                                  $t('OTHER_FILES') + (fileIndex + 1)
                                }}</span>
                                <Arbitrarily />
                                <i
                                  class="fas fa-times btn-delete-file-other"
                                  @click="
                                    handleDeleteOtherFile(index, fileIndex)
                                  "
                                />
                              </div>

                              <div class="d-flex flex-row">
                                <b-button
                                  class="default-btn"
                                  @click="openFileSelect(3, index, fileIndex)"
                                >
                                  <span class="text">{{
                                    $t('HR_REGISTER.SELECT_FILE')
                                  }}</span>
                                </b-button>

                                <input
                                  :ref="`fileInputOther${index}${fileIndex}`"
                                  type="file"
                                  style="display: none"
                                  @change="
                                    handleFileSelect(
                                      3,
                                      $event,
                                      item['id'],
                                      fileIndex,
                                      index
                                    )
                                  "
                                >

                                <span v-if="file['file']" class="description">{{
                                  file['file_name']
                                }}</span>
                                <span v-else class="description">{{
                                  $t('isMessage')
                                }}</span>
                              </div>
                            </div>

                            <div
                              class="flex flex-row file-select-btn"
                              @click="handleAddFileInput(item['id'])"
                            >
                              <i
                                class="fas fa-plus-circle"
                                style="color: #a9a9a9"
                              />
                              <span>{{ $t('ADD_FILE') }}</span>
                            </div>

                            <div
                              class="d-flex flex-row"
                              style="margin-top: 10px"
                            >
                              <span class="title">{{
                                $t('HR_LIST_FORM.REMARKS')
                              }}</span>
                              <Arbitrarily />
                            </div>

                            <b-form-textarea
                              id="textarea"
                              v-model="item['remark']"
                              rows="3"
                              max-rows="6"
                              placeholder=""
                              class="mt-3 mb-3"
                            />
                          </div>
                        </b-col>
                      </b-row>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
              class="job-detail-for-hr-form-btn"
              style="margin-top: 2rem; margin-bottom: 2rem"
            >
              <div style="width: 360px">
                <BtnMoveNext
                  :min-width="'360px'"
                  :text="$t('RETURN_TO_PERSONNEL_SELECTION')"
                  :direction="'previous'"
                  :bg-color="'#A9A9A9'"
                  @click.native="navigateToJobEntry()"
                />
                <BtnMoveNext
                  :min-width="'360px'"
                  :text="$t('GO_CONFIRM')"
                  :direction="'next'"
                  @click.native="navigateToJobConfirmation()"
                />
              </div>
            </div>
          </div>
        </b-overlay>
      </div>
    </div>

    <b-modal
      v-model="isShowModalWarning"
      hide-footer
      :no-fade="false"
      no-close-on-backdrop
      centered
      size="lg"
    >
      <template #modal-title>
        <span>このページから移動しますか？入力した情報は保存されません。</span>
      </template>

      <template #default>
        <div class="d-flex justify-content-end" style="gap: 10px">
          <div class="btn btn-cancel" @click="isShowModalWarning = false">
            <span>このページに留まる</span>
          </div>

          <div class="btn btn-confirm" @click="handleConfirmLeavePage()">
            <span>このページから移動する</span>
          </div>
        </div>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { uploadSingleFile } from '@/api/modules/hr';

import BtnMoveNext from '@/components/BtnMoveNext';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
import { LIMIT_FILE_SIZE, FILE_TYPE } from '@/const/config.js';
const FILE_CAN_UPLOAD = [FILE_TYPE.PDF, FILE_TYPE.MP3, FILE_TYPE.MP4];
const urlAPI = {
  urlUploadSingleFile: '/upload',
};

export default {
  name: 'JobInformationEntry',
  components: {
    Require,
    Arbitrarily,
    BtnMoveNext,
  },
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
      vItems: [],

      error: [{ file_motivation: true }],

      isShowModalWarning: false,

      work_id: this.$store.getters.work_id,

      list_selected_hr: this.$store.getters.list_selected_hr,

      is_filled_data: this.$store.getters.is_filled_data,

      list_hr_information: this.$store.getters.list_hr_information,
    };
  },
  created() {
    this.handleFetchDataFromProps();
  },
  methods: {
    async handleConfirmLeavePage() {
      this.isShowModalWarning = false;
      await this.$store.dispatch('job/setIsFilledData', false);
      this.$router.push({ path: '/job-search/select-entry-hr' });
    },
    handleFetchDataFromProps() {
      let propsData;

      if (this.is_filled_data) {
        propsData = this.list_hr_information;

        let idx = 0;
        const len = propsData.length;
        while (idx < len) {
          this.error.push({ file_motivation: true });
          idx++;
        }
      } else {
        propsData = this.list_selected_hr;

        propsData.forEach((item) => {
          item['file_motivation'] = {
            file: null,
            file_name: '',
            file_size: '',
            file_id: null,
          };

          item['file_recommendation'] = {
            file: null,
            file_name: '',
            file_size: '',
            file_id: null,
          };

          item['file_others'] = [];

          item['file_others_result'] = [];

          item['remark'] = '';

          this.error.push({ file_motivation: true });
        });
      }

      this.vItems = [...propsData];
    },
    async goBack() {
      if (this.isFillingData()) {
        this.isShowModalWarning = true;
      } else {
        await this.$store.dispatch('job/setIsFilledData', false);
        this.$router.push({ path: '/job-search/select-entry-hr' });
      }
    },
    async navigateToJobEntry() {
      if (this.isFillingData()) {
        this.isShowModalWarning = true;
      } else {
        await this.$store.dispatch('job/setIsFilledData', false);
        this.$router.push({ path: '/job-search/select-entry-hr' });
      }
    },
    async navigateToJobConfirmation() {
      if (this.handleValidateData()) {
        await this.$store.dispatch('job/setListHrInformation', this.vItems);
        await this.$store.dispatch('job/setIsFilledData', true);

        this.$router.push({ name: 'JobConfirmationEntry' });
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_CHOOSE_FILE'),
        });
      }
    },
    handleAddFileInput(id) {
      const DATA = [...this.vItems];

      DATA.find((item) => {
        if (item['id'] === id) {
          item['file_others'].push({
            file: null,
            file_name: '',
            file_size: '',
            file_id: null,
          });
        }
      });

      this.vItems = DATA;
    },
    openFileSelect(type, index, fileIndex) {
      switch (type) {
        case 1:
          this.$nextTick(() => {
            this.$refs[`fileInputMotivation${index}`][0].click();
          });
          break;
        case 2:
          this.$nextTick(() => {
            this.$refs[`fileInputRecommendation${index}`][0].click();
          });
          break;
        case 3:
          this.$nextTick(() => {
            this.$refs[`fileInputOther${index}${fileIndex}`][0].click();
          });
          break;
        default:
          break;
      }
    },
    async handleFileSelect(type, event, id, fileIndex, errorIndex) {
      const selectedFile = event.target.files[0];
      if (!selectedFile) {
        return;
      }
      if (
        !FILE_CAN_UPLOAD.includes(selectedFile.type) ||
        selectedFile.size > LIMIT_FILE_SIZE.NORMAL_UPLOAD_FILE
      ) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.FILE_UPLOAD_ERORR'),
        });
        return;
      }
      try {
        this.overlay.show = true;
        const tempData = [...this.vItems];

        const item = tempData.find((data) => data['id'] === id);
        switch (type) {
          case 1:
            if (this.validateFileSize(selectedFile['size'])) {
              const response = await this.handleUploadSingleFile(
                selectedFile,
                1
              );

              if (response['status']) {
                item['file_motivation']['file'] = selectedFile;
                item['file_motivation']['file_name'] = `${this.truncateString(
                  selectedFile['name']
                )} (${this.bytesToMB(selectedFile['size'])}MB)`;
                item['file_motivation']['file_id'] = response['id'];

                this.error[errorIndex]['file_motivation'] = true;
              }
            } else {
              MakeToast({
                variant: 'warning',
                title: this.$t('WARNING'),
                content: '最大3MB',
              });
            }
            break;
          case 2:
            if (this.validateFileSize(selectedFile['size'])) {
              const response = await this.handleUploadSingleFile(
                selectedFile,
                2
              );

              if (response['status']) {
                item['file_recommendation']['file'] = selectedFile;
                item['file_recommendation'][
                  'file_name'
                ] = `${this.truncateString(
                  selectedFile['name']
                )} (${this.bytesToMB(selectedFile['size'])}MB)`;
                item['file_recommendation']['file_id'] = response['id'];
              }
            } else {
              MakeToast({
                variant: 'warning',
                title: this.$t('WARNING'),
                content: '最大3MB',
              });
            }
            break;
          case 3:
            if (this.validateFileSize(selectedFile['size'])) {
              const response = await this.handleUploadSingleFile(
                selectedFile,
                3
              );

              if (response['status']) {
                item['file_others'][fileIndex]['file'] = selectedFile;
                item['file_others'][fileIndex][
                  'file_name'
                ] = `${this.truncateString(
                  selectedFile['name']
                )} (${this.bytesToMB(selectedFile['size'])}MB)`;
                item['file_others'][fileIndex]['file_id'] = response['id'];
              }
            } else {
              MakeToast({
                variant: 'warning',
                title: this.$t('WARNING'),
                content: '最大3MB',
              });
            }
            break;
          default:
            break;
        }

        this.vItems = tempData;
        this.overlay.show = false;
      } catch (error) {
        this.overlay.show = false;
      }
    },
    async handleDeleteOtherFile(hr_index, file_index) {
      const TEMP_DATA = [...this.vItems];

      TEMP_DATA[hr_index]['file_others'].splice(file_index, 1);

      this.vItems = TEMP_DATA;

      await this.$store.dispatch('job/setListHrInformation', this.vItems);
    },
    validateFileSize(fileSize) {
      const maxFileSizeMB = 3;
      const fileSizeMB = fileSize / (1024 * 1024);
      return fileSizeMB <= maxFileSizeMB;
    },
    bytesToMB(bytes) {
      const megabytes = bytes / (1024 * 1024);
      return megabytes.toFixed(2);
    },
    truncateString(string) {
      if (string.length > 12) {
        return string.substring(0, 12) + '...';
      } else {
        return string;
      }
    },
    isFillingData() {
      let result = false;

      this.vItems.some((item) => {
        if (
          item['file_motivation']['file'] ||
          item['file_recommendation']['file'] ||
          item['file_others'].length > 0 ||
          item['remark']
        ) {
          result = true;
        }
      });

      return result;
    },
    handleValidateData() {
      let result = true;
      // Remove other file if it empty
      this.vItems = this.vItems.map((x) => {
        return {
          ...x,
          file_others: x.file_others.filter((a) => a.file_name),
        };
      });
      this.vItems.forEach((item, index) => {
        if (!item['file_motivation']['file']) {
          this.error[index]['file_motivation'] = false;
          result = false;
        } else {
          this.error[index]['file_motivation'] = true;
        }
      });

      return result;
    },
    async handleUploadSingleFile(file, type) {
      const result = {
        id: null,
        status: false,
      };

      try {
        const formData = new FormData();

        formData.append('file', file);
        // formData.append('model_file', model_file);
        formData.append('type', type);

        const url = `${urlAPI.urlUploadSingleFile}`;

        const response = await uploadSingleFile(url, formData);

        if (response['code'] === 200) {
          result.id = response['data']['id'];
          result.status = true;
        } else {
          result.id = null;
          result.status = false;

          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: response['message'] || '[ERROR FROM SERVER]',
          });
        }
      } catch (error) {
        result.id = null;
        result.status = false;

        MakeToast({
          variant: 'danger',
          title: this.$t('DANGER'),
          content: error['message'] || '[ERROR FROM SERVER]',
        });
      }

      return result;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/JobSearch/JobDetailForHr/JobDetailForHr.scss';

.btn-cancel {
  font-size: 16px;
  color: #333333;
  font-weight: bold;
  border-radius: 6px;
  background-color: #d8d9da;

  & > span {
    line-height: 30px;
  }

  &:hover {
    opacity: 0.6;
  }
}

.btn-confirm {
  font-size: 16px;
  color: white;
  font-weight: bold !important;
  border-radius: 6px;
  background-color: #304cad !important;

  & > span {
    line-height: 30px !important;
  }

  &:hover {
    opacity: 0.6;
  }
}

.name-holder {
  margin-top: 20px;
}

.title {
  font-weight: 500;
  margin-right: 10px;
}

.default-btn {
  height: 35px;
  color: #000000;
  margin-top: 10px;
  font-weight: 400;
  min-width: 120px;
  margin-right: 10px;
  background-color: #f5f5f5;
  border: 1px solid #8b8b8b;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;

  .text {
    display: flex;
    font-size: 12px;
    justify-content: center;
  }
}

.description {
  font-size: 12px;
  line-height: 58px;
}

.file-select-btn {
  margin-top: 10px;

  &:hover {
    cursor: pointer;
  }
}

.btn-delete-file-other {
  color: red;
  margin-left: 15px;
  line-height: 24px;

  &:hover {
    cursor: pointer;
  }
}
</style>
