<template>
  <div>
    <b-table :fields="fieldsInterview" :items="itemsInterview">
      <template #head(selected)="">
        <b-form-checkbox
          v-model="selectAll"
          @change="onSelectAllCheckboxChange"
        />
      </template>
      <template #cell(selected)="row">
        <b-form-checkbox
          v-model="row.item._isSelected"
          @change="onItemCheckboxChange(row.item)"
        />
      </template>
      <template #cell(full_name)="fullname">
        <div
          class="w-100 justify-space-between flex-column"
          style="align-items: flex-start"
        >
          <div
            v-for="(item, index) in fullname.item.full_name"
            :key="index"
            class="w-100 justify-space-between flex-column"
          >
            <div class="text-overflow-ellipsis">
              {{ item.name_vn }}
            </div>
            <div class="text-overflow-ellipsis">
              {{ item.name_jp }}
            </div>
          </div>
        </div>
      </template>
      <template #cell(selection_status)="selection_status">
        <span
          v-if="
            selection_status.value === 'オファー承認' ||
              selection_status.value === '書類通過' ||
              selection_status.value === '一次合格' ||
              selection_status.value === '二次合格' ||
              selection_status.value === '三次合格' ||
              selection_status.value === '四次合格' ||
              selection_status.value === '五次合格'
          "
          class="btn-status btn-confirm"
        >
          {{ selection_status.value }}
        </span>
        <span
          v-if="
            selection_status.value === '面接中止' ||
              selection_status.value === '面接辞退'
          "
          class="btn-status btn-decline"
        >
          {{ selection_status.value }}
        </span>
      </template>
      <template
        #cell(interview_adjustment_status)="interview_adjustment_status"
      >
        <span class="btn-status btn-pending">
          {{ interview_adjustment_status.value }}
        </span>
      </template>
      <template #cell(detail)="row">
        <button class="btn-go-detail" @click="goToDetail(row.item)">
          <i class="fas fa-eye icon-detail" />
        </button>
      </template>
    </b-table>
    <!-- offer confirmation + adjust -->
    <modal-common
      :refs="'offer-confirmation_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <!-- header -->
        <div class="text-center">
          <template v-if="step === 1 || step === 2">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《オファー》
            </h5>
          </template>
        </div>
        <!-- body -->
        <div class="content-detail px-5">
          <template v-if="step === 1">
            <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
              <h4 class="text-center">一次面接</h4>
              <b-form-group label="面接形式">
                <b-form-radio-group
                  v-model="typeStatus"
                  name="面接形式"
                  stacked
                >
                  <template v-for="option in typeStatusOptions">
                    <b-form-radio
                      :key="option.key"
                      :value="option.key"
                      :disabled="option.key === 1"
                    >
                      {{ option.value }}
                    </b-form-radio>
                  </template>
                </b-form-radio-group>
              </b-form-group>
            </b-form>
            <h6 v-if="typeStatus === 1" class="text-red">
              この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
              とエントリーしています。
              集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
            </h6>
            <b-table
              class="bg-white"
              bordered
              :fields="fieldsModal"
              :items="itemsModal"
            >
              <template #cell(candidate_date)="">
                <b-form-input type="date" />
              </template>

              <template #cell(starting_time)="">
                <div class="d-flex">
                  <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                  <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                </div>
              </template>
              <template #cell(expected_time)="">
                <div class="d-flex align-items-center">
                  <b-form-select :options="minuteOptions" class="mx-2" />
                  <span>分</span>
                </div>
              </template>
            </b-table>
          </template>

          <template v-if="step === 2">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">個人面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>

          <!-- Button -->
          <div class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >確認画面へ</b-button>
            </template>
            <template v-if="step === 2">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button variant="warning" class="text-white mx-1" @click="'';">
                この内容で送信する
              </b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- document passing + adjust -->
    <modal-common
      :refs="'document-passing_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <!-- header -->
        <div class="text-center">
          <template v-if="step === 1">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </template>
          <template v-if="step === 2">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </template>
        </div>
        <!-- body -->
        <div class="content-detail px-5">
          <template v-if="step === 1">
            <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
              <h4 class="text-center">一次面接</h4>
              <b-form-group label="面接形式">
                <b-form-radio-group
                  v-model="typeStatus"
                  name="面接形式"
                  stacked
                >
                  <template v-for="option in typeStatusOptions">
                    <b-form-radio :key="option.key" :value="option.key">
                      {{ option.value }}
                    </b-form-radio>
                  </template>
                </b-form-radio-group>
              </b-form-group>
            </b-form>
            <h6 v-if="typeStatus === 1" class="text-red">
              この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
              とエントリーしています。
              集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
            </h6>
            <b-table
              class="bg-white"
              bordered
              :fields="fieldsModal"
              :items="itemsModal"
            >
              <template #cell(candidate_date)="">
                <b-form-input type="date" />
              </template>

              <template #cell(starting_time)="">
                <div class="d-flex">
                  <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                  <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                </div>
              </template>
              <template #cell(expected_time)="">
                <div class="d-flex align-items-center">
                  <b-form-select :options="minuteOptions" class="mx-2" />
                  <span>分</span>
                </div>
              </template>
            </b-table>
          </template>

          <template v-if="step === 2">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">集団面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>

          <!-- Button -->
          <div class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >確認画面へ</b-button>
            </template>
            <template v-if="step === 2">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button variant="warning" class="text-white mx-1" @click="'';">
                この内容で送信する
              </b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- first passing + adjust -->
    <modal-common
      :refs="'first-passing_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <template v-if="step === 1 || step === 2">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </template>
          <template v-if="step === 3">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </template>
        </div>

        <div class="content-detail px-5">
          <!-- step 1 step 2 content -->
          <template v-if="step === 1 || step === 2">
            <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
              <h4 class="text-center">二次合格</h4>
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                とエントリーしています。
                集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
              </h6>
              <!-- table -->
              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </b-form>
          </template>
          <!-- step 3 content -->
          <template v-if="step === 3">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">集団面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>

          <!-- Button -->
          <div class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >この内容で送信する</b-button>
            </template>
            <template v-if="step === 2">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >
                確認画面へ
              </b-button>
            </template>
            <template v-if="step === 3">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="'';"
              >この内容で送信する</b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- second passing + adjust -->
    <modal-common
      :refs="'second-passing_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <template v-if="step === 1 || step === 2">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </template>
          <template v-if="step === 3">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </template>
        </div>

        <div class="content-detail px-5">
          <template v-if="step === 1 || step === 2">
            <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
              <h4 class="text-center">三次合格</h4>
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                とエントリーしています。
                集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
              </h6>
              <!-- table -->
              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </b-form>
          </template>
          <!-- step 3 content -->
          <template v-if="step === 3">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">集団面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>

          <!-- Button -->
          <div class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >この内容で送信する</b-button>
            </template>
            <template v-if="step === 2">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >確認画面へ</b-button>
            </template>
            <template v-if="step === 3">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="'';"
              >この内容で送信する</b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- third passing + adjust -->
    <modal-common
      :refs="'third-passing_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div v-if="step === 1 || step === 2" class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>
        </div>

        <div v-if="step === 3" class="text-center">
          <div>
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>
          <div>
            <h2 class="font-weight-bold">
              Nguyen Trang <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>
          <div>
            <h2 class="font-weight-bold">
              Phan Nam <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>
          <div>
            <h2 class="font-weight-bold">
              Pham Minh <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>
        </div>

        <div class="content-detail px-5">
          <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
            <h4 class="text-center">四次合格</h4>
            <b-form-group v-if="step === 1 || step === 2" label="面接形式">
              <b-form-radio-group
                :options="['集団面接', '個人面接']"
                name="面接形式"
                stacked
              />
            </b-form-group>
            <h6 v-if="step === 2" class="text-red">
              この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
              とエントリーしています。
              集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
            </h6>
            <!-- table -->
            <b-table
              v-if="step === 2 || step === 1"
              class="bg-white"
              bordered
              :fields="fieldsModal"
              :items="itemsModal"
            >
              <template #cell(candidate_date)="">
                <b-form-input type="date" />
              </template>

              <template #cell(starting_time)="">
                <div class="d-flex">
                  <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                  <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                </div>
              </template>
              <template #cell(expected_time)="">
                <div class="d-flex align-items-center">
                  <b-form-select :options="minuteOptions" class="mx-2" />
                  <span>分</span>
                </div>
              </template>
            </b-table>

            <!-- step 3 content -->
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月5日（月）午前11時〜午後12時</div>
                  <div>2023年3月5日（月）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後2時〜午後3時</div>
                  <div>2023年3月7日（水）午前10時〜午前11時</div>
                </b-col>
              </b-row>
            </div>

            <!-- Button -->
            <div v-if="step === 1" class="d-flex justify-content-center mt-5">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >この内容で送信する</b-button>
            </div>
            <div v-if="step === 2" class="d-flex justify-content-center mt-5">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >確認画面へ</b-button>
            </div>
            <div v-if="step === 3" class="d-flex justify-content-center mt-5">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="'';"
              >この内容で送信する</b-button>
            </div>
          </b-form>
        </div>
      </div>
    </modal-common>
    <!-- fourth passing + adjust -->
    <modal-common
      :refs="'fourth-passing_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <template v-if="step === 1 || step === 2">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </template>
          <template v-if="step === 3">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </template>
        </div>

        <div class="content-detail px-5">
          <template v-if="step === 1 || step === 2">
            <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
              <h4 class="text-center">五次合格</h4>
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                とエントリーしています。
                集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
              </h6>
              <!-- table -->
              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </b-form>
          </template>
          <!-- step 3 content -->
          <template v-if="step === 3">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">集団面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>

          <!-- Button -->
          <div v-if="step === 1" class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >この内容で送信する</b-button>
            </template>
            <template v-if="step === 2">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >確認画面へ</b-button>
            </template>
            <template v-if="step === 3">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="'';"
              >この内容で送信する</b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- fifth passing + adjust -->
    <modal-common
      :refs="'fifth-passing_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <template v-if="step === 1 || step === 2">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </template>
          <template v-if="step === 3">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </template>
        </div>

        <div class="content-detail px-5">
          <template v-if="step === 1 || step === 2">
            <b-form @submit="handleNextStep($event)" @reset="onReset($event)">
              <h4 class="text-center">最終面接</h4>
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                とエントリーしています。
                集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
              </h6>
              <!-- table -->
              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </b-form>
          </template>
          <!-- step 3 content -->
          <template v-if="step === 3">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">集団面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>

          <!-- Button -->
          <div v-if="step === 1" class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >この内容で送信する</b-button>
            </template>
            <template v-if="step === 2">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="handleNextStep($event)"
              >確認画面へ</b-button>
            </template>
            <template v-if="step === 3">
              <b-button variant="secondary" class="mx-1">
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1" @click="handleBack">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="'';"
              >この内容で送信する</b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- cancel + adjust -->
    <modal-common
      :refs="'cancel_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center text-red p-5">面接中止</h3>
        </div>
        <!--  -->
        <div class="content-detail px-5">
          <b-form>
            <h4 class="text-center">一次面接</h4>
            <b-form-group label="面接形式">
              <b-form-radio-group v-model="typeStatus" name="面接形式" stacked>
                <template v-for="option in typeStatusOptions">
                  <b-form-radio :key="option.key" :value="option.key" disabled>
                    {{ option.value }}
                  </b-form-radio>
                </template>
              </b-form-radio-group>
            </b-form-group>
          </b-form>
          <!-- <h6 v-if="typeStatus === 1" class="text-red">
              この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
              とエントリーしています。
              集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
            </h6> -->
          <b-table
            class="bg-white"
            bordered
            :fields="fieldsModal"
            :items="itemsModal"
          >
            <template #cell(candidate_date)="">
              <b-form-input type="date" disabled />
            </template>

            <template #cell(starting_time)="">
              <div class="d-flex">
                <b-form-select
                  :options="clockOptions"
                  disabled
                  class="ml-1 mr-2"
                />
                <b-form-select
                  :options="hourOptions"
                  disabled
                  class="mr-1 ml-2"
                />
              </div>
            </template>
            <template #cell(expected_time)="">
              <div class="d-flex align-items-center">
                <b-form-select :options="minuteOptions" disabled class="mx-2" />
                <span>分</span>
              </div>
            </template>
          </b-table>

          <!-- Button -->
          <div class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1" disabled>
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="secondary"
                class="text-white mx-1"
                disabled
              >確認画面へ</b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- reject + adjust -->
    <modal-common
      :refs="'reject_adjust'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center text-red p-5">面接辞退</h3>
        </div>
        <!--  -->
        <div class="content-detail px-5">
          <b-form>
            <h4 class="text-center">一次面接</h4>
            <b-form-group label="面接形式">
              <b-form-radio-group v-model="typeStatus" name="面接形式" stacked>
                <template v-for="option in typeStatusOptions">
                  <b-form-radio :key="option.key" :value="option.key" disabled>
                    {{ option.value }}
                  </b-form-radio>
                </template>
              </b-form-radio-group>
            </b-form-group>
          </b-form>
          <!-- <h6 v-if="typeStatus === 1" class="text-red">
              この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
              とエントリーしています。
              集団面接を選択した場合、他のエントリー者にも同じ面接候補日が自動で送信されます。
            </h6> -->
          <b-table
            class="bg-white"
            bordered
            :fields="fieldsModal"
            :items="itemsModal"
          >
            <template #cell(candidate_date)="">
              <b-form-input type="date" disabled />
            </template>

            <template #cell(starting_time)="">
              <div class="d-flex">
                <b-form-select
                  :options="clockOptions"
                  disabled
                  class="ml-1 mr-2"
                />
                <b-form-select
                  :options="hourOptions"
                  disabled
                  class="mr-1 ml-2"
                />
              </div>
            </template>
            <template #cell(expected_time)="">
              <div class="d-flex align-items-center">
                <b-form-select :options="minuteOptions" disabled class="mx-2" />
                <span>分</span>
              </div>
            </template>
          </b-table>

          <!-- Button -->
          <div class="d-flex justify-content-center mt-5">
            <template v-if="step === 1">
              <b-button variant="secondary" class="mx-1" disabled>
                <i class="fas fa-solid fa-caret-left fs-16" />
                <span class="mx-1">前の画面に戻る</span>
              </b-button>
              <b-button
                variant="secondary"
                class="text-white mx-1"
                disabled
              >確認画面へ</b-button>
            </template>
          </div>
        </div>
      </div>
    </modal-common>

    <!-- offer confirmation + adjusting -->
    <modal-common
      :refs="'offer-confirmation_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <div
            v-if="step === 1 || step === 2 || step === 3"
            class="text-center"
          >
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《オファー》
            </h5>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">一次面接</h4>
            <b-form v-if="step === 1 || step === 2" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ '個人面接' }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
              <!-- <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6> -->
            </b-form>
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">個人面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月1日（水）午後2時〜午後3時</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1 || step === 2"
                variant="warning"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
              >面接辞退</b-button>

              <div v-if="step === 3" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >この内容で送信する</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- document passing + adjusting -->
    <modal-common
      :refs="'document-passing_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <div v-if="step === 1 || step === 2" class="text-center">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>

          <div v-if="step === 3" class="text-center">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">一次面接</h4>
            <b-form v-if="step === 1 || step === 2" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6>
            </b-form>
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月1日（水）午後2時〜午後3時</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1 || step === 2"
                variant="warning"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
              >面接辞退</b-button>

              <div v-if="step === 3" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >この内容で送信する</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- first passing + adjusting -->
    <modal-common
      :refs="'first-passing_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <div v-if="step === 1 || step === 2" class="text-center">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《オファー》
            </h5>
          </div>

          <div v-if="step === 3" class="text-center">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">二次面接</h4>
            <b-form v-if="step === 1 || step === 2" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6>
            </b-form>
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月1日（水）午後2時〜午後3時</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1 || step === 2"
                variant="warning"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
              >面接辞退</b-button>

              <div v-if="step === 3" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >この内容で送信する</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- second passing + adjusting -->
    <modal-common
      :refs="'second-passing_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <div v-if="step === 1 || step === 2" class="text-center">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>

          <div v-if="step === 3" class="text-center">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">三次面接</h4>
            <b-form v-if="step === 1 || step === 2" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6>
            </b-form>
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月1日（水）午後2時〜午後3時</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1 || step === 2"
                variant="warning"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
              >面接辞退</b-button>

              <div v-if="step === 3" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >この内容で送信する</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- third passing + adjusting -->
    <modal-common
      :refs="'third-passing_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <div v-if="step === 1 || step === 2" class="text-center">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《オファー》
            </h5>
          </div>

          <div v-if="step === 3" class="text-center">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《オファー》
              </h5>
            </div>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">四次面接</h4>
            <b-form v-if="step === 1 || step === 2" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6>
            </b-form>
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月1日（水）午後2時〜午後3時</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1 || step === 2"
                variant="warning"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
              >面接辞退</b-button>

              <div v-if="step === 3" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >この内容で送信する</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- fourth passing + adjusting -->
    <modal-common
      :refs="'fourth-passing_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <div v-if="step === 1 || step === 2" class="text-center">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>

          <div v-if="step === 3" class="text-center">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">四次面接</h4>
            <b-form v-if="step === 1 || step === 2" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6>
            </b-form>
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月1日（水）午後2時〜午後3時</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1 || step === 2"
                variant="warning"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
              >面接辞退</b-button>

              <div v-if="step === 3" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >この内容で送信する</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- fifth passing + adjusting -->
    <modal-common
      :refs="'fifth-passing_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <div v-if="step === 1 || step === 2" class="text-center">
            <h2 class="font-weight-bold">
              Nguyen Thi Nhi <br>
              ｸﾞｴﾝ ﾃｨ ﾆｰ
            </h2>
            <h5>
              電気設計エンジニア <br>
              《エントリー》
            </h5>
          </div>

          <div v-if="step === 3" class="text-center">
            <div>
              <h2 class="font-weight-bold">
                Nguyen Thi Nhi <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Nguyen Trang <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Phan Nam <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
            <div>
              <h2 class="font-weight-bold">
                Pham Minh <br>
                ｸﾞｴﾝ ﾃｨ ﾆｰ
              </h2>
              <h5>
                電気設計エンジニア <br>
                《エントリー》
              </h5>
            </div>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">最終面接</h4>
            <b-form v-if="step === 1 || step === 2" ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
              <h6 v-if="step === 2" class="text-red">
                この人材は他の3名の人材 Nguyen Trang、Phan Nam、Pham Minh
                と集団面接の設定がされています。面接候補日を設定した場合、残りのエントリー者にも同じ日程が自動で設定されます。
              </h6>
            </b-form>
            <div v-if="step === 3">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月1日（水）午後2時〜午後3時</div>
                </b-col>
              </b-row>
            </div>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                v-if="step === 1 || step === 2"
                variant="warning"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                v-if="step === 1"
                variant="danger"
                class="text-white my-1 w-137"
              >面接辞退</b-button>

              <div v-if="step === 3" class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >この内容で送信する</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- cancel + adjusting -->
    <modal-common
      :refs="'cancel_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <h3 class="font-weight-bold text-center">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h3>
          <div class="text-center mt-4">
            電気設計エンジニア
            <br>
            《エントリー》
          </div>
          <div class="content-detail">
            <h3 class="text-center text-red p-5">面接中止</h3>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">一次面接</h4>
            <b-form ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  disabled
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
            </b-form>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                disabled
                variant="secondary"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                disabled
                variant="secondary"
                class="text-white my-1 w-137"
              >面接辞退</b-button>
            </div>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- reject + adjusting -->
    <modal-common
      :refs="'reject_adjusting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div slot="body">
          <h3 class="font-weight-bold text-center">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h3>
          <div class="text-center mt-4">
            電気設計エンジニア
            <br>
            《エントリー》
          </div>
          <div class="content-detail">
            <h3 class="text-center text-red p-5">面接辞退</h3>
          </div>

          <div class="content-detail px-4">
            <h4 class="text-center">一次面接</h4>
            <b-form ref="form">
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_FORMAT')"
                label-for="input-1"
              >
                <span class="ml-4">
                  {{ $t('INTERVIEW_GROUP') }}
                </span>
              </b-form-group>
              <b-form-group
                id="input-group-1"
                :label="$t('INTERVIEW_CANDIDATE_DATE')"
                label-for="input-1"
                class="mt-5"
              >
                <b-form-select
                  disabled
                  :options="[
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月1日（水）午後2時〜午後3時',
                    '2023年3月2日（木）午後1時〜午後2時',
                    '2023年3月2日（木）午後2時〜午後3時',
                    '2023年3月2日（木）午後4時〜午後5時',
                    'いずれの日時も不可、再調整',
                  ]"
                />
              </b-form-group>
            </b-form>
            <!-- Button -->
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                disabled
                variant="secondary"
                class="my-1 text-white w-137"
                @click="handleNextStep"
              >
                確認画面へ</b-button>
              <b-button
                disabled
                variant="secondary"
                class="text-white my-1 w-137"
              >面接辞退</b-button>
            </div>
          </div>
        </div>
      </div>
    </modal-common>

    <!-- offer confirmation + URL setting -->
    <modal-common :refs="'offer-confirmation_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《オファー》
        </div>
        <div class="content-detail px-5">
          <h3 class="text-center">一次面接</h3>
          <h6 class="text-center">個人面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button variant="warning" class="w-25 text-white" @click="'';">
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- document passing + URL setting -->
    <modal-common :refs="'document-passing_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail px-5">
          <h3 class="text-center">一次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button variant="warning" class="w-25 text-white" @click="'';">
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- first passing + URL setting -->
    <modal-common :refs="'first-passing_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center">二次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button variant="warning" class="w-25 text-white" @click="'';">
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- second passing + URL setting -->
    <modal-common :refs="'second-passing_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center">三次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button variant="warning" class="w-25 text-white" @click="'';">
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">一次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- third passing + URL setting -->
    <modal-common :refs="'third-passing_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center">四次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button variant="warning" class="w-25 text-white" @click="'';">
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">二次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">一次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- fourth passing + URL setting -->
    <modal-common :refs="'fourth-passing_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center">五次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button variant="warning" class="w-25 text-white" @click="'';">
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">三次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">二次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">一次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- fifth passing + URL setting -->
    <modal-common :refs="'fifth-passing_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center">最終面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button variant="warning" class="w-25 text-white" @click="'';">
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">四次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">三次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">二次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">一次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- cancel + URL setting -->
    <modal-common :refs="'cancel_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center text-red p-5">面接中止</h3>
        </div>
        <div class="content-detail">
          <h3 class="text-center">最終面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
                disabled
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
                disabled
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
                disabled
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button
              variant="secondary"
              disabled
              class="w-25 text-white"
              @click="'';"
            >
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">二次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">一次面接</h3>
          <h6 class="text-center">集団面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- reject + URL setting -->
    <modal-common :refs="'reject_URL-setting'" :size="'md'">
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center text-red p-5">面接辞退</h3>
        </div>
        <div class="content-detail">
          <h3 class="text-center">最終面接</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5 class="mb-5">2023年4月6日（木）13:30〜14:30</h5>
          </div>
          <form ref="form">
            <b-form-group
              label="オンライン面接URL"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
                disabled
              />
            </b-form-group>
            <b-form-group
              label="ミーティングID"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
                disabled
              />
            </b-form-group>
            <b-form-group
              label="パスコード"
              label-for="name-input"
              invalid-feedback="Name is required"
              :state="nameState"
            >
              <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
                disabled
              />
            </b-form-group>
          </form>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button
              variant="secondary"
              disabled
              class="w-25 text-white"
              @click="'';"
            >
              {{ $t('BUTTON.SAVE') }}
            </b-button>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">二次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>

        <div class="content-detail">
          <h3 class="text-center">一次面接</h3>
          <h6 class="text-center">集団面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年3月24日（金）15:00〜16:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/12345678987 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：12345678987 <br>
              パスコード：yhA0oq19JH
            </p>
          </div>
        </div>
      </div>
    </modal-common>

    <!-- offer confirmation + adjusted-->
    <modal-common
      :refs="'offer-confirmation_adjusted'"
      :size="step === 4 || step === 5 ? 'lg' : 'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《オファー》
          </h5>
          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>

        <div class="content-detail">
          <h3 v-if="step === 1 || step === 2 || step === 3" class="text-center">
            一次面接
          </h3>
          <h3 v-if="step === 4 || step === 5" class="text-center">二次合格</h3>
          <template v-if="step === 1 || step === 2 || step === 3">
            <h6 class="text-center">個人面接</h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年4月6日（木）11:00〜12:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-select
                v-model="optionSelectStatus"
                :options="['合格', '不合格', '内定']"
                @change="handleSelectStatus"
              />
              <b-form-select
                v-if="isDisplaySecond"
                v-model="optionSelectSecondStatus"
                class="mt-3"
                :options="['二次合格へ', '最終面接へ']"
              />

              <template v-if="isDisplaySecondOffical">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1">
                    {{ '回答期限' }}
                  </label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <b-form-input v-model="optionSelectSecondStatus" type="date" />
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">結果</b-col>
                <b-col cols="8" class="d-flex flex-wrap align-items-center">
                  {{ optionSelectStatus }}
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ optionSelectStatus === '合格' ? '' : '回答期限' }}</b-col>
                <b-col cols="8" class="d-flex flex-wrap align-items-center">
                  {{ optionSelectSecondStatus }}
                </b-col>
              </b-row>
            </template>
          </template>
          <template v-if="step === 4">
            <b-form-group label="面接形式">
              <b-form-radio-group
                :options="['集団面接', '個人面接']"
                name="面接形式"
                stacked
              />
            </b-form-group>

            <h6 class="text-red">
              ※
              二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。
            </h6>

            <b-table
              class="bg-white"
              bordered
              :fields="fieldsModal"
              :items="itemsModal"
            >
              <template #cell(candidate_date)="">
                <b-form-input type="date" />
              </template>

              <template #cell(starting_time)="">
                <div class="d-flex">
                  <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                  <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                </div>
              </template>
              <template #cell(expected_time)="">
                <div class="d-flex align-items-center">
                  <b-form-select :options="minuteOptions" class="mx-2" />
                  <span>分</span>
                </div>
              </template>
            </b-table>
          </template>

          <template v-if="step === 5">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">集団面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                @click="handleStopInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ '確定' }}
            </b-button>

            <template v-if="step === 3">
              <div
                v-if="optionSelectStatus === '合格'"
                class="d-flex justify-content-center mt-5"
              >
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="'';">閉じる</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span> 日程調整へ進む</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
              <div
                v-if="optionSelectStatus === '内定'"
                class="d-flex justify-content-center mt-5"
              >
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  編集
                </b-button>
              </div>
            </template>
            <template v-if="step === 4">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
            <template v-if="step === 5">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- document passing + adjusted-->
    <modal-common
      :refs="'document-passing_adjusted'"
      :size="step === 4 || step === 5 ? 'lg' : 'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>

          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>

        <!--  -->
        <div class="content-detail">
          <h3 v-if="step === 1 || step === 2 || step === 3" class="text-center">
            一次面接
          </h3>
          <h3 v-if="step === 4 || step === 5" class="text-center">二次合格</h3>
          <template v-if="step === 1 || step === 2 || step === 3">
            <h6 class="text-center">個別面接</h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年4月6日（木）11:00〜12:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-select
                v-model="optionSelectStatus"
                :options="['合格', '不合格', '内定']"
                @change="handleSelectStatus"
              />

              <b-form-select
                v-if="isDisplaySecond"
                v-model="optionSelectSecondStatus"
                class="mt-3"
                :options="['二次合格へ', ' 最終面接へ']"
                @change="'';"
              />

              <template v-if="isDisplaySecondOffical">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1">{{ '回答期限' }}</label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <b-form-input v-model="optionSelectSecondStatus" type="date" />
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">結果</b-col>
                <b-col cols="8" class="d-flex flex-wrap align-items-center">
                  {{ optionSelectStatus }}
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ optionSelectStatus === '合格' ? '' : '回答期限' }}
                </b-col>
                <b-col cols="8" class="d-flex flex-wrap align-items-center">
                  {{ optionSelectSecondStatus }}
                </b-col>
              </b-row>
            </template>
          </template>
          <template v-if="step === 4">
            <b-form-group label="面接形式">
              <b-form-radio-group
                :options="['集団面接', '個人面接']"
                name="面接形式"
                stacked
              />
            </b-form-group>

            <h6 class="text-red">
              ※
              二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。
            </h6>

            <b-table
              class="bg-white"
              bordered
              :fields="fieldsModal"
              :items="itemsModal"
            >
              <template #cell(candidate_date)="">
                <b-form-input type="date" />
              </template>

              <template #cell(starting_time)="">
                <div class="d-flex">
                  <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                  <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                </div>
              </template>
              <template #cell(expected_time)="">
                <div class="d-flex align-items-center">
                  <b-form-select :options="minuteOptions" class="mx-2" />
                  <span>分</span>
                </div>
              </template>
            </b-table>
          </template>

          <template v-if="step === 5">
            <b-row>
              <b-col cols="4">面接形式 </b-col>
              <b-col cols="8">集団面接</b-col>
            </b-row>
            <b-row>
              <b-col cols="4" class="my-2">面接候補日 </b-col>
              <b-col cols="8" class="d-flex flex-wrap align-items-center my-2">
                <div>2023年3月5日（月）午前11時〜午後12時</div>
                <div>2023年3月5日（月）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後1時〜午後2時</div>
                <div>2023年3月6日（火）午後2時〜午後3時</div>
                <div>2023年3月7日（水）午前10時〜午前11時</div>
              </b-col>
            </b-row>
          </template>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                @click="handleStopInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ '確定' }}
            </b-button>

            <template v-if="step === 3">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="'';">閉じる</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span> 日程調整へ進む</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
            </template>
            <template v-if="step === 4">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
            <template v-if="step === 5">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- first passing + adjusted-->
    <modal-common
      :refs="'first-passing_adjusted'"
      :size="step === 4 || step === 5 ? 'lg' : 'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>

          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>

        <div class="content-detail">
          <template v-if="step === 1 || step === 2 || step === 3">
            <h3 class="text-center">二次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年4月6日（木）11:00〜12:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-select
                v-model="optionSelectStatus"
                :options="['合格', '不合格', '内定']"
                @change="handleSelectStatus"
              />

              <b-form-select
                v-if="isDisplaySecond"
                v-model="optionSelectSecondStatus"
                class="mt-3"
                :options="['三次面接へ', ' 最終面接へ']"
              />

              <template v-if="isDisplaySecondOffical">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1" for="textarea">{{
                    '回答期限'
                  }}</label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <b-form-input type="date" />
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">結果</b-col>
                <b-col cols="8">{{ optionSelectStatus }}</b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ optionSelectStatus === '合格' ? '' : '回答期限' }}
                </b-col>
                <b-col cols="8"> {{ optionSelectSecondStatus }}</b-col>
              </b-row>
            </template>
          </template>

          <template v-if="step === 4 || step === 5">
            <h3 class="text-center">三次面接</h3>
            <template v-if="step === 4">
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>

              <h6 class="text-red">
                ※
                二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。
              </h6>

              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </template>

            <template v-if="step === 5">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月5日（月）午前11時〜午後12時</div>
                  <div>2023年3月5日（月）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後2時〜午後3時</div>
                  <div>2023年3月7日（水）午前10時〜午前11時</div>
                </b-col>
              </b-row>
            </template>
          </template>

          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                @click="handleStopInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ '確定' }}
            </b-button>

            <template v-if="step === 3">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="'';">閉じる</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span> 日程調整へ進む</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
            </template>
            <template v-if="step === 4">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
            <template v-if="step === 5">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
          </div>
        </div>
        <!--  -->
        <template v-if="step === 1">
          <div class="content-detail">
            <h3 class="text-center">一次面接</h3>
            <h6 class="text-center">集団面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年3月24日（金）15:00〜16:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/12345678987 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：12345678987 <br>
                パスコード：yhA0oq19JH
              </p>
            </div>
          </div>
        </template>
      </div>
    </modal-common>
    <!-- second passing + adjusted -->
    <modal-common
      :refs="'second-passing_adjusted'"
      :size="step === 4 || step === 5 ? 'lg' : 'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>
          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>
        <div class="content-detail">
          <template v-if="step === 1 || step === 2 || step === 3">
            <h3 class="text-center">三次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年4月6日（木）11:00〜12:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-select
                v-model="optionSelectStatus"
                :options="['合格', '不合格', '内定']"
                @change="handleSelectStatus"
              />

              <b-form-select
                v-if="isDisplaySecond"
                v-model="optionSelectSecondStatus"
                class="mt-3"
                :options="['四次面接へ', ' 最終面接へ']"
              />

              <template v-if="isDisplaySecondOffical">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1" for="textarea">{{
                    '回答期限'
                  }}</label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <b-form-input type="date" />
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">結果</b-col>
                <b-col cols="8">{{ optionSelectStatus }}</b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ optionSelectStatus === '合格' ? '' : '回答期限' }}
                </b-col>
                <b-col cols="8"> {{ optionSelectSecondStatus }}</b-col>
              </b-row>
            </template>
          </template>
          <template v-if="step === 4 || step === 5">
            <h3 class="text-center">三次面接</h3>
            <template v-if="step === 4">
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>

              <h6 class="text-red">
                ※
                二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。
              </h6>

              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </template>

            <template v-if="step === 5">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月5日（月）午前11時〜午後12時</div>
                  <div>2023年3月5日（月）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後2時〜午後3時</div>
                  <div>2023年3月7日（水）午前10時〜午前11時</div>
                </b-col>
              </b-row>
            </template>
          </template>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                @click="handleStopInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ '確定' }}
            </b-button>

            <template v-if="step === 3">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="'';">閉じる</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span> 日程調整へ進む</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
            </template>
            <template v-if="step === 4">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
            <template v-if="step === 5">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
          </div>
        </div>

        <template v-if="step === 1">
          <div class="content-detail">
            <h3 class="text-center">二次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">一次面接</h3>
            <h6 class="text-center">集団面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年3月24日（金）15:00〜16:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/12345678987 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：12345678987 <br>
                パスコード：yhA0oq19JH
              </p>
            </div>
          </div>
        </template>
      </div>
    </modal-common>
    <!-- third passing + adjusted -->
    <modal-common
      :refs="'third-passing_adjusted'"
      :size="step === 4 || step === 5 ? 'lg' : 'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>
          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>
        <div class="content-detail">
          <template v-if="step === 1 || step === 2 || step === 3">
            <h3 class="text-center">四次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年4月6日（木）11:00〜12:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-select
                v-model="optionSelectStatus"
                :options="['合格', '不合格', '内定']"
                @change="handleSelectStatus"
              />

              <b-form-select
                v-if="isDisplaySecond"
                v-model="optionSelectSecondStatus"
                class="mt-3"
                :options="['四次面接へ', ' 最終面接へ']"
              />

              <template v-if="isDisplaySecondOffical">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1" for="textarea">{{
                    '回答期限'
                  }}</label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <b-form-input type="date" />
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">結果</b-col>
                <b-col cols="8">{{ optionSelectStatus }}</b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ optionSelectStatus === '合格' ? '' : '回答期限' }}
                </b-col>
                <b-col cols="8"> {{ optionSelectSecondStatus }}</b-col>
              </b-row>
            </template>
          </template>
          <template v-if="step === 4 || step === 5">
            <h3 class="text-center">四次面接</h3>
            <template v-if="step === 4">
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>

              <h6 class="text-red">
                ※
                二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。
              </h6>

              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </template>

            <template v-if="step === 5">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月5日（月）午前11時〜午後12時</div>
                  <div>2023年3月5日（月）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後2時〜午後3時</div>
                  <div>2023年3月7日（水）午前10時〜午前11時</div>
                </b-col>
              </b-row>
            </template>
          </template>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                @click="handleStopInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ '確定' }}
            </b-button>

            <template v-if="step === 3">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="'';">閉じる</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span> 日程調整へ進む</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
            </template>
            <template v-if="step === 4">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
            <template v-if="step === 5">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
          </div>
        </div>

        <template v-if="step === 1">
          <div class="content-detail">
            <h3 class="text-center">三次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">二次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">一次面接</h3>
            <h6 class="text-center">集団面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年3月24日（金）15:00〜16:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/12345678987 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：12345678987 <br>
                パスコード：yhA0oq19JH
              </p>
            </div>
          </div>
        </template>
      </div>
    </modal-common>
    <!-- fourth passing + adjusted-->
    <modal-common
      :refs="'fourth-passing_adjusted'"
      :size="step === 4 || step === 5 ? 'lg' : 'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>
          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>
        <div class="content-detail">
          <template v-if="step === 1 || step === 2 || step === 3">
            <h3 class="text-center">五次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年4月6日（木）11:00〜12:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-select
                v-model="optionSelectStatus"
                :options="['合格', '不合格', '内定']"
                @change="handleSelectStatus"
              />

              <b-form-select
                v-if="isDisplaySecond"
                v-model="optionSelectSecondStatus"
                class="mt-3"
                :options="['五次面接へ', ' 最終面接へ']"
              />

              <template v-if="isDisplaySecondOffical">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1" for="textarea">{{
                    '回答期限'
                  }}</label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <b-form-input type="date" />
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">結果</b-col>
                <b-col cols="8">{{ optionSelectStatus }}</b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ optionSelectStatus === '合格' ? '' : '回答期限' }}
                </b-col>
                <b-col cols="8"> {{ optionSelectSecondStatus }}</b-col>
              </b-row>
            </template>
          </template>
          <template v-if="step === 4 || step === 5">
            <h3 class="text-center">五次面接</h3>
            <template v-if="step === 4">
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>

              <h6 class="text-red">
                ※
                二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。
              </h6>

              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </template>

            <template v-if="step === 5">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月5日（月）午前11時〜午後12時</div>
                  <div>2023年3月5日（月）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後2時〜午後3時</div>
                  <div>2023年3月7日（水）午前10時〜午前11時</div>
                </b-col>
              </b-row>
            </template>
          </template>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                @click="handleStopInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ '確定' }}
            </b-button>

            <template v-if="step === 3">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="'';">閉じる</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span> 日程調整へ進む</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
            </template>
            <template v-if="step === 4">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
            <template v-if="step === 5">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
          </div>
        </div>

        <template v-if="step === 1">
          <div class="content-detail">
            <h3 class="text-center">四次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">三次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">二次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">一次面接</h3>
            <h6 class="text-center">集団面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年3月24日（金）15:00〜16:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/12345678987 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：12345678987 <br>
                パスコード：yhA0oq19JH
              </p>
            </div>
          </div>
        </template>
      </div>
    </modal-common>
    <!-- fifth passing + adjusted -->
    <modal-common
      :refs="'fifth-passing_adjusted'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>
          <h5 v-if="step === 3">以下の内容で送信しました。 <br></h5>
        </div>
        <div class="content-detail">
          <template v-if="step === 1 || step === 2 || step === 3">
            <h3 class="text-center">最終面接</h3>
            <h6 class="text-center">個別面接</h6>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年4月6日（木）11:00〜12:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
            <template v-if="step === 2">
              <b-form-select
                v-model="optionSelectStatus"
                :options="['合格', '不合格', '内定']"
                @change="handleSelectStatus"
              />

              <b-form-select
                v-if="isDisplaySecond"
                v-model="optionSelectSecondStatus"
                class="mt-3"
                :options="['最終面接へ']"
              />

              <template v-if="isDisplaySecondOffical">
                <div class="d-flex align-items-center mt-3">
                  <label class="mb-0 mx-1" for="textarea">{{
                    '回答期限'
                  }}</label>
                  <span class="mx-1">※7日後以降で設定可能</span>
                </div>
                <b-form-input type="date" />
              </template>
            </template>
            <template v-if="step === 3">
              <b-row>
                <b-col cols="4">結果</b-col>
                <b-col cols="8">{{ optionSelectStatus }}</b-col>
              </b-row>
              <b-row>
                <b-col cols="4">
                  {{ optionSelectStatus === '合格' ? '' : '回答期限' }}
                </b-col>
                <b-col cols="8"> {{ optionSelectSecondStatus }}</b-col>
              </b-row>
            </template>
          </template>
          <template v-if="step === 4 || step === 5">
            <h3 class="text-center">最終面接</h3>
            <template v-if="step === 4">
              <b-form-group label="面接形式">
                <b-form-radio-group
                  :options="['集団面接', '個人面接']"
                  name="面接形式"
                  stacked
                />
              </b-form-group>

              <h6 class="text-red">
                ※
                二次面接以降は個別面接のみとなります。集団面接を希望する場合はコンシェルジュに問い合わせてください。
              </h6>

              <b-table
                class="bg-white"
                bordered
                :fields="fieldsModal"
                :items="itemsModal"
              >
                <template #cell(candidate_date)="">
                  <b-form-input type="date" />
                </template>

                <template #cell(starting_time)="">
                  <div class="d-flex">
                    <b-form-select :options="clockOptions" class="ml-1 mr-2" />
                    <b-form-select :options="hourOptions" class="mr-1 ml-2" />
                  </div>
                </template>
                <template #cell(expected_time)="">
                  <div class="d-flex align-items-center">
                    <b-form-select :options="minuteOptions" class="mx-2" />
                    <span>分</span>
                  </div>
                </template>
              </b-table>
            </template>

            <template v-if="step === 5">
              <b-row>
                <b-col cols="4">面接形式 </b-col>
                <b-col cols="8">集団面接</b-col>
              </b-row>
              <b-row>
                <b-col cols="4" class="my-2">面接候補日 </b-col>
                <b-col
                  cols="8"
                  class="d-flex flex-wrap align-items-center my-2"
                >
                  <div>2023年3月5日（月）午前11時〜午後12時</div>
                  <div>2023年3月5日（月）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後1時〜午後2時</div>
                  <div>2023年3月6日（火）午後2時〜午後3時</div>
                  <div>2023年3月7日（水）午前10時〜午前11時</div>
                </b-col>
              </b-row>
            </template>
          </template>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <template v-if="step === 1">
              <b-button
                variant="warning"
                class="text-white my-1 w-137"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
              </b-button>

              <b-button
                variant="danger"
                class="my-1 text-white w-137"
                @click="handleStopInterview"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL')
                }}
              </b-button>
            </template>
            <b-button
              v-if="step === 2"
              variant="warning"
              class="text-white my-1 w-137"
              @click="handleNextStep"
            >
              {{ '確定' }}
            </b-button>

            <template v-if="step === 3">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <span class="mx-1" @click="'';">閉じる</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span> 日程調整へ進む</span>

                  <i class="fas fa-solid fa-caret-right fs-16" />
                </b-button>
              </div>
            </template>
            <template v-if="step === 4">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="handleNextStep"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
            <template v-if="step === 5">
              <div class="d-flex justify-content-center mt-5">
                <b-button variant="secondary" class="mx-1">
                  <i class="fas fa-solid fa-caret-left fs-16" />
                  <span class="mx-1" @click="handleBack">前の画面に戻る</span>
                </b-button>
                <b-button
                  variant="warning"
                  class="text-white mx-1"
                  @click="'';"
                >
                  <span>この内容で送信する</span>
                </b-button>
              </div>
            </template>
          </div>
        </div>

        <template v-if="step === 1">
          <div class="content-detail">
            <h3 class="text-center">五次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">四次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">三次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">二次面接</h3>
            <h6 class="text-center">個別面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h4>2023年3月30日（木）11:00〜12:00</h4>
              <b-link>
                <u> https://us02web.zoom.us/j/987654321 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：987654321 <br>
                パスコード：Lr20JAs47
              </p>
            </div>
          </div>
          <div class="content-detail">
            <h3 class="text-center">一次面接</h3>
            <h6 class="text-center">集団面接</h6>
            <h3 class="text-center font-weight-bold">合格</h3>
            <div class="d-flex flex-column align-items-center mt-4">
              <h5>2023年3月24日（金）15:00〜16:00</h5>
              <b-link>
                <u> https://us02web.zoom.us/j/12345678987 </u>
              </b-link>
            </div>
            <div class="d-flex flex-column align-items-center mt-4">
              <p>
                ミーティングID：12345678987 <br>
                パスコード：yhA0oq19JH
              </p>
            </div>
          </div>
        </template>
      </div>
    </modal-common>
    <!-- cancel + adjusted -->
    <modal-common
      :refs="'cancel_adjusted'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center text-red p-5">面接中止</h3>
        </div>
        <div class="content-detail">
          <h3 class="text-center">一次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button
              variant="secondary"
              class="text-white my-1 w-137"
              disabled
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
            </b-button>

            <b-button
              variant="secondary"
              class="my-1 text-white w-137"
              disabled
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL') }}
            </b-button>
          </div>
        </div>
      </div>
    </modal-common>
    <!-- reject + adjusted -->
    <modal-common
      :refs="'reject_adjusted'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <h3 class="font-weight-bold text-center">
          Nguyen Thi Nhi <br>
          ｸﾞｴﾝ ﾃｨ ﾆｰ
        </h3>
        <div class="text-center mt-4">
          電気設計エンジニア
          <br>
          《エントリー》
        </div>
        <div class="content-detail">
          <h3 class="text-center text-red p-5">面接辞退</h3>
        </div>
        <div class="content-detail">
          <h3 class="text-center">一次合格</h3>
          <h6 class="text-center">個別面接</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
          <!-- button -->
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button
              variant="secondary"
              class="text-white my-1 w-137"
              disabled
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_COMPLETE') }}
            </b-button>

            <b-button
              variant="secondary"
              class="my-1 text-white w-137"
              disabled
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_INTERVIEW.BTN_INTERVIEW_CANCEL') }}
            </b-button>
          </div>
        </div>
      </div>
    </modal-common>

    <!-- Confirm again -->
    <modal-common :refs="'adjusted-stop'" :size="'md'">
      <div slot="body" class="p-4">
        <h4 class="text-center text-red">本当に面接を中止しますか？</h4>
        <form ref="form">
          <div class="d-flex align-items-center">
            <label class="mb-0" for="textarea">{{ $t('TEXT_CONFIRM2') }}</label>
            <b-badge class="badge-not-required mx-2" variant="secondary">
              任意
            </b-badge>
          </div>
          <b-form-textarea
            id="textarea"
            placeholder=""
            rows="6"
            max-rows="28"
            max-lengh="2000"
          />
        </form>
        <div class="d-flex align-items-center mt-5 justify-content-center">
          <b-button variant="secondary" class="mx-1">
            {{ $t('MODAL_BUTTON_CANCEL') }}
          </b-button>
          <b-button variant="danger" class="text-white mx-1">
            {{ $t('MODAL_BUTTON_CANCEL_INTERVIEW') }}
          </b-button>
        </div>
      </div>
    </modal-common>
  </div>
</template>

<script>
import ModalCommon from '../../../layout/components/ModalCommon/matching.vue';
import {
  fieldsInterview,
  itemsInterview,
  itemsModal,
  fieldsModal,
  typeStatusOptions,
} from './data.js';
// import { getAllUserManagement, deleteUserManagement, deleteAllUserManagement } from '@/api/modules/userManagement';
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';

export default {
  name: 'Entry',
  components: {
    ModalCommon,
  },
  data() {
    return {
      typeStatus: 0,
      typeStatusOptions: typeStatusOptions,
      tabIndex: 0,
      itemDetail: '',
      nameState: '',
      name: '',

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
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

      reRender: 0,
      // table
      fieldsInterview: fieldsInterview,
      itemsInterview: itemsInterview,
      itemsModal: itemsModal,
      fieldsModal: fieldsModal,

      clockOptions: ['AM', 'PM'],
      hourOptions: [
        '12:00',
        '12:30',
        '1:00',
        '1:30',
        '2:00',
        '2:30',
        '3:00',
        '3:30',
        '4:00',
        '4:30',
        '5:00',
        '5:40',
        '6:00',
        '6:30',
        '7:00',
        '7:30',
        '8:00',
        '8:30',
        '9:00',
        '9:30',
        '10:00',
        '10:30',
        '11:00',
        '11:30',
      ],
      minuteOptions: ['30', '60', '90'],
      selectedItems: [],
      selectAll: false,
      step: 1,
      optionSelectStatus: '',
      isDisplaySecond: false,
      isDisplaySecondOffical: '',
    };
  },

  computed: {
    listUser() {
      return this.$store.getters.listUser;
    },
  },

  watch: {},

  created() {
    // this.getListAllData();
  },

  methods: {
    resetModal() {
      this.step = 1;
      this.typeStatus = 0;
      this.isDisplaySecond = false;
      this.isDisplaySecondOffical = false;
      this.optionSelectStatus = '';
      this.optionSelectSecondStatus = '';
    },
    goToDetail(item) {
      // adjust|adjusting|URL-setting|adjusted
      this.itemDetail = item;
      if (
        item.selection_status === 'オファー承認' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.step = 1;
        this.$bus.emit('open-modal', 'offer-confirmation_adjust');
      } else if (
        item.selection_status === 'オファー承認' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'offer-confirmation_adjusting');
      } else if (
        item.selection_status === 'オファー承認' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'offer-confirmation_URL-setting');
      } else if (
        item.selection_status === 'オファー承認' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'offer-confirmation_adjusted');
      } else if (
        item.selection_status === '書類通過' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'document-passing_adjust');
      } else if (
        item.selection_status === '書類通過' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'document-passing_adjusting');
      } else if (
        item.selection_status === '書類通過' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'document-passing_URL-setting');
      } else if (
        item.selection_status === '書類通過' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'document-passing_adjusted');
      } else if (
        item.selection_status === '一次合格' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'first-passing_adjust');
      } else if (
        item.selection_status === '一次合格' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'first-passing_adjusting');
      } else if (
        item.selection_status === '一次合格' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'first-passing_URL-setting');
      } else if (
        item.selection_status === '一次合格' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'first-passing_adjusted');
      } else if (
        item.selection_status === '二次合格' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'second-passing_adjust');
      } else if (
        item.selection_status === '二次合格' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'second-passing_adjusting');
      } else if (
        item.selection_status === '二次合格' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'second-passing_URL-setting');
      } else if (
        item.selection_status === '二次合格' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'second-passing_adjusted');
      } else if (
        item.selection_status === '三次合格' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'third-passing_adjust');
      } else if (
        item.selection_status === '三次合格' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'third-passing_adjusting');
      } else if (
        item.selection_status === '三次合格' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'third-passing_URL-setting');
      } else if (
        item.selection_status === '三次合格' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'third-passing_adjusted');
      } else if (
        item.selection_status === '四次合格' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'fourth-passing_adjust');
      } else if (
        item.selection_status === '四次合格' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'fourth-passing_adjusting');
      } else if (
        item.selection_status === '四次合格' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'fourth-passing_URL-setting');
      } else if (
        item.selection_status === '四次合格' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'fourth-passing_adjusted');
      } else if (
        item.selection_status === '五次合格' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'fifth-passing_adjust');
      } else if (
        item.selection_status === '五次合格' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'fifth-passing_adjusting');
      } else if (
        item.selection_status === '五次合格' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'fifth-passing_URL-setting');
      } else if (
        item.selection_status === '五次合格' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'fifth-passing_adjusted');
      } else if (
        item.selection_status === '面接中止' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'cancel_adjust');
      } else if (
        item.selection_status === '面接中止' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'cancel_adjusting');
      } else if (
        item.selection_status === '面接中止' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'cancel_URL-setting');
      } else if (
        item.selection_status === '面接中止' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'cancel_adjusted');
      } else if (
        item.selection_status === '面接辞退' &&
        item.interview_adjustment_status === '調整前'
      ) {
        this.$bus.emit('open-modal', 'reject_adjust');
      } else if (
        item.selection_status === '面接辞退' &&
        item.interview_adjustment_status === '調整中'
      ) {
        this.$bus.emit('open-modal', 'reject_adjusting');
      } else if (
        item.selection_status === '面接辞退' &&
        item.interview_adjustment_status === 'URL設定中'
      ) {
        this.$bus.emit('open-modal', 'reject_URL-setting');
      } else if (
        item.selection_status === '面接辞退' &&
        item.interview_adjustment_status === '調整済'
      ) {
        this.$bus.emit('open-modal', 'reject_adjusted');
      }
    },

    handleSelectStatus() {
      if (this.optionSelectStatus === '合格') {
        this.isDisplaySecond = true;
      } else {
        this.isDisplaySecond = false;
      }
      if (this.optionSelectStatus === '内定') {
        this.isDisplaySecondOffical = true;
      } else {
        this.isDisplaySecondOffical = false;
      }
    },

    handleNextStep(e) {
      e.preventDefault();
      this.checkvalidate();
      console.log('handleNextStep', this.formData);
      this.step++;
      if (this.checkvalidate() === true) {
        console.log('Vào đây');
      } else {
        e.stopPropagation();
      }
    },

    handleStopInterview() {
      this.$bus.emit('open-modal', 'adjusted-stop');
    },

    handleBack() {
      this.step--;
      if (this.step === 0) {
        this.$bus.emit('close-modal');
        this.step++;
      }
    },

    checkvalidate() {},

    onSelectAllCheckboxChange() {
      if (this.selectAll) {
        this.selectedItems = [...this.items];
      } else {
        this.selectedItems = [];
      }
      this.items.forEach((item) => {
        item._isSelected = this.selectAll;
      });
    },

    onItemCheckboxChange(item) {
      if (item._isSelected) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );
        this.selectedItems.splice(index, 1);
      }
      this.selectAll = this.selectedItems.length === this.items.length;
    },

    handleDeleteAll() {
      const mangIds = this.selectedItems.map((item, id) => {
        return item.id;
      });
      console.log('handleDeleteAll', mangIds);
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/scss/modules/MatchingManagement/Header.scss';

.content-detail {
  background-color: #f9f9ff;
  padding: 1.5rem 4rem;
  margin-top: 2rem;
}

.w-137 {
  min-width: 137px;
}

.bg-white {
  background-color: #fff;
}

.btn-status {
  min-width: 100px;
}

.text-red {
  color: red;
}
</style>
