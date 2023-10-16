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
            <div class="btn btn-back" @click="navigateToJobInformation()">
              <span>{{ $t('RETURN') }}</span>
            </div>
          </div>
        </div>

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
                            <span class="title">{{ $t('MOTIVATION') }}</span>
                          </div>

                          <div class="d-flex flex-row file-upload-area">
                            <span
                              v-if="item['file_motivation']['file']"
                              class="description"
                            >{{ item['file_motivation']['file_name'] }}</span>
                            <span v-else class="description">{{
                              $t('isMessage')
                            }}</span>
                          </div>

                          <div class="d-flex flex-row mt-2">
                            <span class="title">{{
                              $t('RECOMMENDATION')
                            }}</span>
                          </div>

                          <div class="d-flex flex-row file-upload-area">
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
                            </div>

                            <div class="d-flex flex-row">
                              <span v-if="file['file']" class="description">{{
                                file['file_name']
                              }}</span>
                              <span v-else class="description">{{
                                $t('isMessage')
                              }}</span>
                            </div>
                          </div>

                          <div class="d-flex flex-row" style="margin-top: 10px">
                            <span class="title">{{ $t('REMARKS') }}</span>
                          </div>

                          <div class="d-flex flex-row">
                            <span class="description">{{
                              item['remark']
                            }}</span>
                          </div>
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
                :text="$t('RETURN_TO_DOCUMENT_REGISTRATION')"
                :direction="'previous'"
                :bg-color="'#A9A9A9'"
                @click.native="navigateToJobInformation()"
              />
              <BtnMoveNext
                :min-width="'360px'"
                :text="$t('ENTER_WITH_THIS_CONTENT')"
                :direction="'next'"
                @click.native="navigateToJobComplete()"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { postEntry } from '@/api/modules/job';
import { MakeToast } from '@/utils/toastMessage';

import BtnMoveNext from '@/components/BtnMoveNext';

const urlAPI = {
  apiPostEntry: '/entry',
};

export default {
  name: 'JobConfirmationEntry',
  components: {
    BtnMoveNext,
  },
  data() {
    return {
      vItems: [],

      work_id: this.$store.getters.work_id,

      list_hr_information: this.$store.getters.list_hr_information,
    };
  },
  created() {
    this.handleFetchDataFromProps();
  },
  methods: {
    handleFetchDataFromProps() {
      const propsData = [...this.list_hr_information];

      propsData.forEach((item) => {
        const result = [];

        if (item['file_others'].length > 0) {
          item['file_others'].forEach((file) => {
            result.push(file['file_id']);
          });
        }

        item['file_others_result'] = result;
      });

      this.vItems = [...propsData];
    },
    navigateToJobInformation() {
      this.$router.push({
        name: 'JobInformationEntry',
      });
    },
    async navigateToJobComplete() {
      try {
        const url = `${urlAPI.apiPostEntry}`;

        const data = [];

        this.vItems.forEach((item) => {
          data.push({
            work_id: this.work_id,
            hr_id: item['id'],
            remarks: item['remark'],
            motivation_file_id: item['file_motivation']['file_id'],
            recommendation_file_id:
              item['file_recommendation'] &&
              item['file_recommendation']['file_id']
                ? item['file_recommendation']['file_id']
                : '',
            other_files: item['file_others_result'] || [],
          });
        });

        const formData = new FormData();

        data.forEach((item, index) => {
          Object.keys(item).forEach((key) => {
            const value = item[key];
            const fieldName = `items[${index}][${key}]`;

            if (Array.isArray(value)) {
              value.forEach((item, idx) => {
                formData.append(`${fieldName}[${idx}]`, item);
              });
            } else {
              formData.append(fieldName, value);
            }
          });
        });

        const response = await postEntry(url, formData);

        if (response['code'] === 200) {
          this.$router.push({ name: 'JobEntryComplete' });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: response['message'] || '[ERROR FROM SERVER]',
          });
        }
      } catch (error) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: error['message'] || '[ERROR FROM SERVER]',
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/JobSearch/JobDetailForHr/JobDetailForHr.scss';

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
</style>
