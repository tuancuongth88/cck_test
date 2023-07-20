<template>
  <div class="job-detail-for-hr">
    <div class="job-detail-for-hr-container">
      <div class="job-detail-for-hr-form">
        <div class="job-detail-for-hr-form__head">
          <div class="h-100 d-flex justify-start align-stretch">
            <div class="line" />

            <div class="job-detail-for-hr-form__head-title pl-4">
              <span>{{ '求人情報 エントリー' }}</span>
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
                <span style="font-weight: bold">{{ '書類登録' }}</span>
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
                          <span style="font-size: 16px">
                            {{
                              `${item['name']} ${item['furigana']} (ID:${item['code']})`
                            }}
                          </span>
                        </div>
                      </b-col>

                      <b-col>
                        <div
                          class="d-flex flex-column"
                          style="margin-top: 20px"
                        >
                          <div class="d-flex flex-row">
                            <span class="title">志望動機</span> <Require />
                          </div>

                          <div class="d-flex flex-row file-upload-area">
                            <b-button
                              class="default-btn"
                              @click="openFileSelect1(index)"
                            >
                              <span class="text">ファイルを選択</span>
                            </b-button>

                            <input
                              :ref="`fileInput1${index}`"
                              type="file"
                              style="display: none"
                              @change="handleFileSelect1($event, item['id'])"
                            >

                            <span class="description">{{
                              item['file_1']
                            }}</span>
                          </div>

                          <div
                            v-for="(file, fileIndex) in item['file_count']"
                            :key="fileIndex"
                            class="d-flex flex-column"
                            style="margin-top: 10px"
                          >
                            <div class="d-flex flex-row">
                              <span class="title">推薦文</span> <Arbitrarily />
                            </div>

                            <div class="d-flex flex-row">
                              <b-button
                                class="default-btn"
                                @click="openFileSelect2(index)"
                              >
                                <span class="text">ファイルを選択</span>
                              </b-button>

                              <input
                                :ref="`fileInput2${index}`"
                                type="file"
                                style="display: none"
                                @change="handleFileSelect2($event, item['id'])"
                              >

                              <span class="description">{{
                                item['file_2']
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
                            <span>ファイルを追加</span>
                          </div>

                          <div class="d-flex flex-row" style="margin-top: 10px">
                            <span class="title">備考</span> <Arbitrarily />
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
            <div :disable="allow_btn" style="width: 360px">
              <BtnMoveNext
                :min-width="'360px'"
                :text="'人材選択に戻る'"
                :direction="'previous'"
                :bg-color="'#A9A9A9'"
                @click.native="navigateToJobEntry()"
              />
              <BtnMoveNext
                :min-width="'360px'"
                :text="'この内容でエントリーする'"
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
import { MakeToast } from '@/utils/toastMessage';
import { dataDetailHr } from '@/pages/JobSearch/JobDetailForHr/dataJobDetail.js';

import BtnMoveNext from '@/components/BtnMoveNext';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

export default {
  name: 'JobInformationEntry',
  components: {
    Require,
    Arbitrarily,
    BtnMoveNext,
  },
  data() {
    return {
      allow_btn: true,

      formData: dataDetailHr,

      role: 'HrOrganizationOversea',

      vItems: [],
    };
  },
  created() {
    this.getRole();
    this.vItems = this.$route.params.data;
  },
  methods: {
    goBack() {
      this.$router.push({ name: 'JobEntry' });
    },
    navigateToJobEntry() {
      MakeToast({
        variant: 'warning',
        title: this.$t('WARNING'),
        content: 'このページから移動しますか？入力した情報は保存されません。',
      });

      this.$router.push({ name: 'JobEntry' });
    },
    navigateToJobComplete() {
      this.$router.push({ name: 'JobInformationEntryComplete' });
    },
    getRole() {
      this.allow_btn = true;
    },
    handleAddFileInput(id) {
      this.vItems.find((item) => {
        if (item['id'] === id) {
          item['file_count'] += 1;
        }
      });
    },
    openFileSelect1(index) {
      this.$nextTick(() => {
        this.$refs[`fileInput1${index}`][0].click();
      });
    },
    handleFileSelect1(event, id) {
      const selectedFile = event.target.files[0];

      this.vItems.find((item) => {
        if (item['id'] === id) {
          item['file_1'] = `${this.truncateString(
            selectedFile['name']
          )} (${this.bytesToMB(selectedFile['size'])}MB)`;
        }
      });
    },
    openFileSelect2(index) {
      this.$nextTick(() => {
        this.$refs[`fileInput2${index}`][0].click();
      });
    },
    handleFileSelect2(event, id) {
      const selectedFile = event.target.files[0];

      this.vItems.find((item) => {
        if (item['id'] === id) {
          item['file_2'] = `${this.truncateString(
            selectedFile['name']
          )} (${this.bytesToMB(selectedFile['size'])})MB`;
        }
      });
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
