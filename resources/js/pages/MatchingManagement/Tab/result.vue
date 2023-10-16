<template>
  <div class="w-100">
    <div :class="!sidebarExists ? 'over-flow' : ''">
      <div class="hr-list-table-list__table">
        <b-table
          id="result-table-list"
          :fields="fields"
          :items="dataResult"
          hover
          show-empty
          no-local-sorting
          @sort-changed="handleSortTable"
        >
          <template #empty="">
            <div class="text-center">
              {{ $t('TABLE_EMPTY') }}
            </div>
          </template>
          <template #head(selected)="">
            <b-form-checkbox
              v-model="selectAll"
              @change="onSelectAllCheckboxChange"
            />
          </template>
          <template #head(code)="">
            <label
              class="mb-0"
            >{{ $t('ENTRY_LABEL') }} <br>
              ID</label>
          </template>
          <template #head(status_selection)="">
            <label
              class="mb-0"
            >{{ $t('SELECT_LABEL') }} <br>
              {{ $t('STATUS_LABEL') }}</label>
          </template>
          <template #cell(selected)="row">
            <b-form-checkbox
              :id="`checkbox-result-${row.item.id}`"
              v-model="row.item._isSelected"
              :disabled="
                ![
                  RESULT_STATUS.NOT_PASS,
                  RESULT_STATUS.OFFICAL_OFFER_DECLINE,
                ].includes(row.item.status_selection)
              "
              @change="onItemCheckboxChange(row.item)"
            />
          </template>
          <template #cell(id)="row">
            <span>{{ row.item.hr_id }}</span>
          </template>
          <template #cell(code)="data">
            <span v-if="!data.item.code"> - </span>
            <span v-else>
              {{ data.item.code }}
            </span>
          </template>
          <template #cell(full_name)="row">
            <div class="w-100 justify-space-between flex-column">
              <b-link
                :to="`hr/detail/${row.item.hr_id}`"
                class="w-100 justify-space-between flex-column text-dark"
              >
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${hrFullNameColWidth} - 1.5rem)` }"
                  :title="row.item.full_name"
                >
                  {{ row.item.full_name }}
                </div>
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${hrFullNameColWidth} - 1.5rem)` }"
                  :title="row.item.full_name_ja"
                >
                  {{ row.item.full_name_ja }}
                </div>
              </b-link>
            </div>
            <!-- <img
            v-if="fullname.item.favorite"
            :src="require('@/assets/images/login/favorite-removed.png')"
            alt="favorite-removed"
          > -->
          </template>
          <template #cell(job_title)="row">
            <div class="w-100 justify-space-between flex-column">
              <b-link
                :to="
                  [
                    ROLE.TYPE_SUPER_ADMIN,
                    ROLE.TYPE_COMPANY_ADMIN,
                    ROLE.TYPE_COMPANY,
                  ].includes(permissionCheck)
                    ? `job/detail/${row.item.job_id}`
                    : `job-search/detail/${row.item.job_id}`
                "
                class="w-100 justify-space-between flex-column text-dark"
              >
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${workTitleColWidth} - 1.5rem)` }"
                  :title="row.item.work_title"
                >
                  {{ row.item.job_title }}
                </div>
              </b-link>
            </div>
          </template>

          <template #cell(status_selection)="status_selection">
            <span
              v-if="status_selection.value === RESULT_STATUS.OFFICAL_OFFER"
              class="btn-status btn-pending"
            >
              {{ $t('STATUS.UNOFFICIAL') }}
            </span>
            <span
              v-if="status_selection.value === RESULT_STATUS.NOT_PASS"
              class="btn-status btn-pending"
            >
              {{ $t('STATUS.FAILURE') }}
            </span>
            <span
              v-if="
                status_selection.value === RESULT_STATUS.OFFICAL_OFFER_CONFIRM
              "
              class="btn-status btn-confirm"
            >
              {{ $t('HR_LIST.STATUS_OFFICIAL_OFFER_CONFIRM') }}
            </span>
            <span
              v-if="
                status_selection.value === RESULT_STATUS.OFFICAL_OFFER_DECLINE
              "
              class="btn-status btn-decline"
            >
              {{ $t('STATUS.DECLINE_JOB_OFFER') }}
            </span>
          </template>
          <template #cell(time)="time">
            <span
              v-if="time.value === RESULT_STATUS.EXPIRATION"
              class="btn-status btn-pending"
            >
              {{ $t('EXPIRE') }}
            </span>
            <span v-else-if="!time.value"> - </span>
            <span v-else>
              {{ time.value }}
            </span>
          </template>
          <template #cell(detail)="row">
            <button
              id="btn-go-detail"
              :dusk="'btn-go-detail-' + row.item.id"
              class="btn-go-detail"
              @click="goToDetail(row.item)"
            >
              <i class="fas fa-eye icon-detail" />
            </button>
          </template>
        </b-table>
      </div>
    </div>
    <!-- Pagination -->
    <!-- <div class="w-100 d-flex justify-end align-center">
      <b-pagination
        v-model="queryData.current_page"
        style="padding-top: 20px"
        :total-rows="queryData.total_records"
        :per-page="queryData.per_page"
        aria-controls="result-table-list"
        pills
        :next-class="'next'"
        :prev-class="'prev'"
        @change="($event) => getCurrentPage($event)"
      />
    </div> -->
    <div class="w-100 d-flex justify-end align-center">
      <custom-pagination
        v-if="dataResult && dataResult.length > 0"
        :total-rows="queryData.total_records"
        :current-page="queryData.current_page"
        :per-page="queryData.per_page"
        :key-tab="'tab_3'"
        @pagechanged="onPageChange"
        @changeSize="changeSize"
      />
    </div>
    <!-- result-modal-offical-offer -->
    <modal-common
      :refs="'result-modal-offical-offer'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <h5>
            {{ itemDetail.job_title }} <br>
            {{ itemDetail.code ? $t('ENTRY') : $t('OFFER') }}
          </h5>
        </div>

        <!-- Offical offer -->
        <div class="content-detail px-4 pt-3 pb-4 text-center">
          <h6 class="text-center">
            {{ itemDetail.create_at }}
          </h6>
          <h3 class="text-center font-weight-bold">
            {{ $t('STATUS.UNOFFICIAL') }}
          </h3>
          <div class="text-center">
            {{ '回答期限 :' + timeOfficalOffer }}
          </div>
          <div class="d-flex flex-column align-items-center mt-5">
            <b-button
              v-if="
                [
                  ROLE.TYPE_SUPER_ADMIN,
                  ROLE.TYPE_COMPANY_ADMIN,
                  ROLE.TYPE_COMPANY,
                ].includes(permissionCheck)
              "
              dusk="btn-change-time-expire"
              variant="warning"
              class="w-25 text-white"
              @click="handleModalOfficalOfferEdit"
            >
              {{ $t('BUTTON.EDIT') }}
            </b-button>
            <b-button
              v-if="
                [
                  ROLE.TYPE_SUPER_ADMIN,
                  ROLE.TYPE_HR_MANAGER,
                  ROLE.TYPE_HR,
                ].includes(permissionCheck)
              "
              dusk="btn-confirm"
              variant="primary"
              class="w-25 mt-2"
              @click="handleModalOfficalOfferConfirm"
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_DETAIL') }}
            </b-button>
            <b-button
              v-if="
                [
                  ROLE.TYPE_SUPER_ADMIN,
                  ROLE.TYPE_HR_MANAGER,
                  ROLE.TYPE_HR,
                ].includes(permissionCheck)
              "
              dusk="btn-decline"
              variant="danger"
              class="w-25 mt-2"
              @click="handleModalOfficalOfferDecline"
            >
              {{
                $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_STATUS_DECLINE')
              }}
            </b-button>
          </div>
        </div>
        <!-- Interview infors loops -->
        <div
          v-for="(item, id) in interview_info"
          :key="id"
          class="content-detail px-4 pt-3 pb-4 mt-3 text-center"
        >
          <h3 class="text-center">{{ item.number_ja }}</h3>
          <h6 class="text-center">{{ item.type_name }}</h6>
          <h3 class="text-center font-weight-bold">{{ item.status_ja }}</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>{{ item.timeJa }}</h5>
            <b-link>
              <u> {{ item.url_zoom }} </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              {{ $t('MEETING_ID') }}：{{ item.id_zoom }} <br>
              {{ $t('PASSCODE') }}：{{ item.password_zoom }}
            </p>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-offical-offer-extend reply date -->
    <modal-common
      :refs="'result-modal-offical-offer-extend'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <h5>
            {{ itemDetail.job_title }} <br>
            {{ itemDetail.code ? $t('ENTRY') : $t('OFFER') }}
          </h5>
        </div>
        <div class="content-detail px-4 pt-3 pb-4 text-center">
          <!-- <h3 class="text-center">最終面接</h3> -->
          <h3 class="text-center">{{ elementFinal.number_ja }}</h3>
          <!-- <h6 class="text-center">個別面接</h6> -->
          <h6 class="text-center">{{ elementFinal.type_name }}</h6>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>{{ itemDetail.create_at }}</h5>
            <b-link>
              <u> {{ elementFinal.url_zoom }} </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              {{ $t('MEETING_ID') }}：{{ elementFinal.id_zoom }} <br>
              {{ $t('PASSCODE') }}：{{ elementFinal.password_zoom }}
            </p>
          </div>
          <form>
            <b-form-select
              v-model="optionSelectStatus"
              :options="['内定']"
              value-field="key"
              text-field="value"
              disabled
            />

            <div class="d-flex align-items-center mt-3">
              <label class="mb-0 mx-1" for="textarea">{{ '回答期限' }}</label>
              <span class="mx-1">※7日後以降で設定可能</span>
            </div>
            <!-- <b-form-input
              v-model="time_offer"
              type="date"
              :class="error.time_offer === false ? 'is-invalid' : ''"
              @input="handleChangeForm($event, 'time')"
            /> -->
            <b-form-datepicker
              v-model="time_offer"
              dusk=""
              locale="ja"
              placeholder=""
              :date-format-options="{
                year: 'numeric',
                month: 'short',
                day: '2-digit',
                weekday: 'short',
              }"
              :min="minDateOffer"
              :class="error.time_offer === false ? ' is-invalid' : ''"
              style="width: 418px"
              class="b-calendar-result"
              @input="handleChangeForm($event, 'time')"
            />

            <b-form-invalid-feedback
              id="hire_date_1"
              class="text-left"
              :state="error.time_offer"
            >
              {{ $t('VALIDATE.REQUIRED_TEXT') }}
            </b-form-invalid-feedback>
          </form>

          <b-button
            variant="warning"
            class="text-white mt-5"
            @click="handleExtendReply"
          >
            {{ $t('CONFIRM_BTN') }}
          </b-button>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-offical-offer-confirm -->
    <modal-confirm
      :refs="'result-modal-offical-offer-confirm'"
      :size="'md'"
      :type="'result'"
      @reset-modal="resetModal"
      @handleSubmitModalConfirm="handleSubmitModalConfirm"
    >
      <div slot="body">
        <div class="text-center">
          <h4 class="">{{ $t('TEXT_CONFIRM3_RESULT') }}</h4>
          <h6 class="text-center">
            承諾をすると他の面接等はすべてキャンセルになります。<br>
            他の求人へエントリーもできなくなり、<br>
            企業方のオファーも受け取れなくなります。
          </h6>
        </div>
      </div>
    </modal-confirm>

    <!-- result-modal-offical-offer-decline -->
    <modal-common
      :refs="'result-modal-offical-offer-decline'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="content-modal pt-3">
          <div class="text-center">
            <h4 class="font-weight-medium text-danger">
              {{ $t('TITLE.TAB_4.LABEL_MODAL_REASON') }}
            </h4>
          </div>
          <b-form-group
            v-slot="{ ariaDescribedby }"
            :label="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
            class="mt-4 px-4"
          >
            <b-form-radio-group
              v-model="declineFormData.fixReasonDecline"
              :options="reasonResultOptions"
              :aria-describedby="ariaDescribedby"
              :name="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
              value-field="key"
              text-field="value"
              stacked
              @change="handleChangeReasonDecline"
            />
            <b-form-textarea
              v-if="declineFormData.isShowNote"
              v-model="declineFormData.note"
              class="mt-4"
              :rows="5"
              :class="error.note === false ? 'is-invalid' : ''"
              @input="handleChangeForm($event, 'note')"
            />
            <b-form-invalid-feedback id="note" :state="error.note">
              {{ $t('VALIDATE.REQUIRED_TEXT') }}
            </b-form-invalid-feedback>
          </b-form-group>

          <div class="text-center">
            <b-button
              class="mt-5 mx-1 text-white"
              variant="secondary"
              @click="handleCancelReason"
            >
              {{ $t('MODAL_BUTTON_CANCEL') }}
            </b-button>
            <b-button
              id="btn-result-decline"
              class="mt-5 mx-1 text-white"
              variant="danger"
              @click="handleDeclineResult"
            >
              {{ $t('MODAL_BUTTON_DECLINE') }}
            </b-button>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-offical-offer-decline-detail -->
    <modal-common
      :refs="'result-modal-offical-offer-decline-detail'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <h5>
            {{ itemDetail.job_title }} <br>
            {{ itemDetail.code ? $t('ENTRY') : $t('OFFER') }}
          </h5>
        </div>
        <div class="content-detail px-4 pt-3 pb-4 mt-3 text-center">
          <h6 class="text-center">
            {{ itemDetail.create_at }}
          </h6>
          <h3 class="text-center font-weight-bold text-danger">
            {{ $t('STATUS.DECLINE_JOB_OFFER') }}
          </h3>
          <p class="text-center">
            {{ itemDetail.note }}
          </p>
        </div>
        <!-- Interview infors loops -->
        <div
          v-for="(item, id) in interview_info"
          :key="id"
          class="content-detail px-4 pt-3 pb-4 mt-3 text-center"
        >
          <h3 class="text-center">{{ item.number_ja }}</h3>
          <h6 class="text-center">{{ item.type_name }}</h6>
          <h3 class="text-center font-weight-bold">{{ item.status_ja }}</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>{{ item.timeJa }}</h5>
            <b-link>
              <u> {{ item.url_zoom }} </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              {{ $t('MEETING_ID') }}：{{ item.id_zoom }} <br>
              {{ $t('PASSCODE') }}：{{ item.password_zoom }}
            </p>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-offical-offer-confirm-status = hire date -->
    <modal-common
      :refs="'result-modal-offical-offer-confirm-status'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <h5>
            {{ itemDetail.work_title }} <br>
            {{ itemDetail.code ? $t('ENTRY') : $t('OFFER') }}
          </h5>
        </div>
        <!-- Offical offer confirm -->
        <div class="content-detail px-4 pt-3 pb-4 text-center">
          <h6 class="text-center">{{ itemDetail.update_at }}</h6>
          <h3 class="text-center font-weight-bold text-primary">
            {{ $t('HR_LIST.STATUS_OFFICIAL_OFFER_CONFIRM') }}
          </h3>
          <div class="text-center font-weight-bold">{{ $t('HIRE_DATE') }}</div>
          <template
            v-if="
              [ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR].includes(
                permissionCheck
              )
            "
          >
            <h4 class="text-center">
              {{ itemDetail.hire_date }}
            </h4>
          </template>

          <template
            v-if="
              [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN].includes(
                permissionCheck
              )
            "
          >
            <div class="d-flex flex-column align-items-center mt-2">
              <!-- <b-form-input
                v-if="step === 1"
                v-model="formatHireDate"
                type="date"
                class="w-75"
                :class="error.hire_date === false ? ' is-invalid' : ''"
                @input="handleChangeForm($event, 'hire_date')"
              /> -->
              <b-form-datepicker
                v-if="step === 1"
                v-model="formatHireDate"
                dusk=""
                placeholder=""
                locale="ja"
                :date-format-options="{
                  year: 'numeric',
                  month: 'short',
                  day: '2-digit',
                  weekday: 'short',
                }"
                class="w-75"
                :min="new Date()"
                :class="error.hire_date === false ? ' is-invalid' : ''"
                @input="handleChangeForm($event, 'hire_date')"
              />
              <b-form-invalid-feedback
                id="hire_date_2"
                class="w-75 text-left"
                :state="error.hire_date"
              >
                {{ $t('VALIDATE.REQUIRED_TEXT') }}
              </b-form-invalid-feedback>

              <h4 v-if="step === 2" class="text-center">
                {{ itemDetail.hire_date }}
              </h4>

              <b-button
                v-if="step === 1"
                variant="warning"
                class="w-25 mt-5 text-white"
                @click="handleNextStep"
              >
                {{ $t('BUTTON.SAVE') }}
              </b-button>

              <b-button
                v-if="step === 2"
                variant="warning"
                class="w-25 mt-5 text-white"
                @click="handleBack"
              >
                {{ $t('BUTTON_EDIT') }}
              </b-button>
            </div>
          </template>
        </div>
        <div
          v-for="(item, id) in interview_info"
          :key="id"
          class="content-detail px-4 pt-3 pb-4 mt-3 text-center"
        >
          <h3 class="text-center">{{ item.number_ja }}</h3>
          <h6 class="text-center">{{ item.type_name }}</h6>
          <h3 class="text-center font-weight-bold">{{ item.status_ja }}</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>{{ item.timeJa }}</h5>
            <b-link>
              <u> {{ item.url_zoom }} </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              {{ $t('MEETING_ID') }}：{{ item.id_zoom }} <br>
              {{ $t('PASSCODE') }}： {{ item.password_zoom }}
            </p>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-not-pass -->
    <modal-common
      :refs="'result-modal-not-pass'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <h5>
            {{ itemDetail.job_title }} <br>
            {{ itemDetail.code ? $t('ENTRY') : $t('OFFER') }}
          </h5>
        </div>
        <!-- Interview infors loops -->
        <div
          v-for="(item, id) in interview_info"
          :key="id"
          class="content-detail px-4 pt-3 pb-4 mt-3 text-center"
        >
          <h3 class="text-center">{{ item.number_ja }}</h3>
          <h6 class="text-center">{{ item.type_name }}</h6>
          <h3 class="text-center font-weight-bold">{{ item.status_ja }}</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>{{ item.timeJa }}</h5>
            <b-link>
              <u> {{ item.url_zoom }} </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              {{ $t('MEETING_ID') }}：{{ item.id_zoom }} <br>
              {{ $t('PASSCODE') }}：{{ item.password_zoom }}
            </p>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-offical-offer-expiration: just only for offical offer, not for offical offer confirm -->
    <modal-common
      :refs="'result-modal-offical-offer-expiration'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <h5>
            {{ itemDetail.job_title }} <br>
            {{ itemDetail.code ? $t('ENTRY') : $t('OFFER') }}
          </h5>
        </div>
        <!-- Offical offer -->
        <div class="content-detail px-4 pt-3 pb-4 text-center">
          <!-- <h6 class="text-center">2023年3月27日（月）</h6> -->
          <h6 class="text-center">
            {{ itemDetail.create_at }}
          </h6>
          <h3 class="text-center font-weight-bold">
            {{ $t('STATUS.UNOFFICIAL') }}
          </h3>
          <!-- <div class="text-center font-weight-bold">
            {{ '回答期限 :' + timeOfficalOffer }}
          </div> -->
          <h3 class="text-center font-weight-bold text-danger mt-3">
            {{ $t('EXPIRE') }}
          </h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button disabled class="w-25">
              {{ $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_DETAIL') }}
            </b-button>
            <b-button disabled class="w-25 mt-2">
              {{
                $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_STATUS_DECLINE')
              }}
            </b-button>
          </div>
        </div>
        <!-- Interview infors loops -->
        <div
          v-for="(item, id) in interview_info"
          :key="id"
          class="content-detail px-4 pt-3 pb-4 mt-3 text-center"
        >
          <h3 class="text-center">{{ item.number_ja }}</h3>
          <h6 class="text-center">{{ item.type_name }}</h6>
          <h3 class="text-center font-weight-bold">{{ item.status_ja }}</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>{{ item.timeJa }}</h5>
            <b-link>
              <u> {{ item.url_zoom }} </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              {{ $t('MEETING_ID') }}：{{ item.id_zoom }} <br>
              {{ $t('PASSCODE') }}：{{ item.password_zoom }}
            </p>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- confirm delete -->
    <b-modal
      id="confirm-delete-interview"
      v-model="statusModalConfirmDelAll"
      hide-header
      hide-footer
      static
      title=""
    >
      <div class="modal-body-content">
        <div
          class="w-100 modal-content-title-del-hr d-flex justify-center align-center"
        >
          <span>{{ $t('CONFIRM_DELETE') }}</span>
        </div>
        <div class="hr-list-btns">
          <div
            id="delete-all-item-hr"
            class="btn"
            @click="statusModalConfirmDelAll = false"
          >
            <span> {{ $t('BUTTON.CANCEL') }}</span>
          </div>
          <!-- Cancel -->
          <div
            id="import-csv"
            class="btn accept"
            @click="handleDeleteAllResult"
          >
            <span>{{ $t('BUTTON.DELETE') }}</span>
          </div>
          <!-- delete -->
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import ModalConfirm from '@/layout/components/ModalCommon/confirm.vue';
import ModalCommon from '@/layout/components/ModalCommon/matching.vue';
import { MakeToast } from '@/utils/toastMessage';
import EventBus from '@/utils/eventBus';
import {
  deleteMultipleResult,
  getDetailResult,
  updateResult,
} from '@/api/modules/matching.js';
import { reasonResultOptions } from '@/const/matching.js';
import moment from 'moment';
import ROLE from '@/const/role.js';
import { RESULT_STATUS } from '@/const/matching.js';
import _ from 'lodash';
const HR_NAME_COL_WIDTH = '200px';
const WORK_TITLE_COL_WIDTH = '200px';

export default {
  name: 'Result',
  components: {
    ModalConfirm,
    ModalCommon,
  },
  props: {
    dataResult: {
      type: Array,
      default: () => [],
    },
    pagination: {
      required: true,
      type: Object,
      default: function() {
        return {};
      },
    },
    sidebarExists: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      ROLE: ROLE,
      RESULT_STATUS: RESULT_STATUS,
      itemDetail: '',
      interview_info: [],
      elementFinal: {},
      timeOfficalOffer: '',
      optionSelectStatus: '内定',
      formatHireDate: '',

      declineFormData: {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      queryData: {
        // page: 1,
        // per_page: 20,
        // total_records: 0,
        // search: '',
        // order_type: '',
        // order_column: '',
      },
      fields: [
        {
          key: 'selected',
          sortable: false,
          label: '',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '50px',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'id',
          sortable: true,
          label: this.$t('HEADER_ID'),
          class: 'bg-header',
          thStyle: {
            width: '80px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
        },
        {
          key: 'code',
          sortable: true,
          label: this.$t('HEADER_ID_ENTRY'),
          class: 'bg-header',
          thStyle: {
            width: '114px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'updating_date',
          sortable: true,
          label: this.$t('HEADER_UPDATING_DATE_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: '120px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'full_name',
          sortable: true,
          label: this.$t('HEADER_FULL_NAME_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: HR_NAME_COL_WIDTH,
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'job_title',
          sortable: true,
          label: this.$t('HEADER_JOB_LIST_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: WORK_TITLE_COL_WIDTH,
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'status_selection',
          sortable: true,
          label: this.$t('HEADER_STATUS_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: '120px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'time',
          sortable: true,
          // label: this.$t('HEADER_REQUEST_DATE_ENTRY_MATCHING'),
          label: '期限',
          class: 'bg-header',
          thStyle: {
            width: '120px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'detail',
          label: this.$t('HEADER_DETAIL_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: '104px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
      ],

      reasonResultOptions: reasonResultOptions,
      selectedItems: [],
      selectAll: false,
      step: 1,
      error: {
        hire_date: true,
        note: true,
        time_offer: true,
      },
      time_offer: '',

      statusModalConfirmDelAll: false,
    };
  },

  computed: {
    minDateOffer() {
      const now = moment().format('YYYY-MM-DD');

      let review_date;
      let offer_date;
      if (this.itemDetail) {
        review_date = moment(
          this.itemDetail.create_at,
          'YYYY年MM月DD日'
        ).format('YYYY-MM-DD');
      }
      if (review_date) {
        offer_date = moment(review_date, 'YYYY-MM-DD')
          .add(7, 'days')
          .format('YYYY-MM-DD');
      }

      if (
        offer_date &&
        moment(offer_date, 'YYYY-MM-DD').isAfter(moment(now, 'YYYY-MM-DD'))
      ) {
        return offer_date;
      }
      return now;
    },
    // checkRoleCompany() {
    //   const PROFILE = this.$store.getters.profile;
    //   const current_role_type = PROFILE.type;
    //   const list_company_role = [1, 2, 4];
    //   return list_company_role.includes(current_role_type);
    // },
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
    hrFullNameColWidth() {
      return HR_NAME_COL_WIDTH;
    },
    workTitleColWidth() {
      return WORK_TITLE_COL_WIDTH;
    },
  },

  watch: {
    declineFormData: {
      handler: function(newVal, oldVal) {
        this.error.note = true;
      },
      deep: true,
    },
    pagination: {
      handler: function(props_data) {
        if (props_data) {
          this.queryData = props_data;
        }
      },
      deep: true,
    },
  },

  created() {
    EventBus.$on('handleDeleteAllResult', () => {
      this.handleCheckDelete();
      // this.handleDeleteAllResult();
    });
  },
  destroyed() {
    EventBus.$off('handleDeleteAllResult');
  },

  methods: {
    async handleNextStep(e) {
      e.preventDefault();
      this.checkvalidate();

      if (this.checkvalidate() === true) {
        const params = {
          id: this.itemDetail.id,
          hire_date: this.formatHireDate,
          status: this.RESULT_STATUS.OFFICAL_OFFER_CONFIRM,
        };
        const response = await updateResult(params);

        const { code, message } = response.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: message,
          });
          this.step++;
          this.goToDetail(this.itemDetail);
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } else {
        e.stopPropagation();
      }
    },

    handleBack() {
      this.step--;
      if (this.step === 0) {
        EventBus.$emit(
          'close-modal',
          'result-modal-offical-offer-confirm-status'
        );
        this.step = 1;
      }
    },

    checkvalidate() {
      if (!this.formatHireDate) {
        this.error.hire_date = false;
      } else {
        return true;
      }
    },
    checkvalidateNote() {
      if (!this.declineFormData.note) {
        this.error.note = false;
      } else {
        return true;
      }
    },

    handleChangeReasonDecline() {
      if (this.declineFormData.fixReasonDecline === 'その他') {
        this.declineFormData.isShowNote = true;
      } else {
        this.declineFormData.isShowNote = false;
      }
    },

    async goToDetail(item) {
      try {
        const response = await getDetailResult(item.id);
        const { code, message, data } = response.data;
        if (code === 200) {
          this.itemDetail = data;
          this.formatHireDate = _.get(data, 'hire_date', '');
          if (this.formatHireDate) {
            this.formatHireDate = this.formatHireDate
              .split('年')
              .join('-')
              .split('月')
              .join('-')
              .split('日')
              .join('');
          } else {
            this.formatHireDate = '';
          }

          this.interview_info = data.interview_info.reverse(); // for loop
          this.elementFinal = this.interview_info[0]; // get first element in interview_info for display
          this.timeOfficalOffer = data.time_jp;
          this.time_offer = this.renderDateString(data.time);
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (error) {
        console.log('error', error);
      }
      if (
        [this.RESULT_STATUS.OFFICAL_OFFER].includes(item.status_selection) &&
        item.time !== this.RESULT_STATUS.EXPIRATION
      ) {
        EventBus.$emit('open-modal', 'result-modal-offical-offer');
      }
      if ([this.RESULT_STATUS.NOT_PASS].includes(item.status_selection)) {
        EventBus.$emit('open-modal', 'result-modal-not-pass');
      }
      if (
        [this.RESULT_STATUS.OFFICAL_OFFER_CONFIRM].includes(
          item.status_selection
        )
      ) {
        EventBus.$emit(
          'open-modal',
          'result-modal-offical-offer-confirm-status'
        );
        if (this.formatHireDate === '') {
          this.step = 1;
        } else {
          this.step = 2;
        }
      }
      if (
        [this.RESULT_STATUS.OFFICAL_OFFER].includes(item.status_selection) &&
        item.time === this.RESULT_STATUS.EXPIRATION
      ) {
        EventBus.$emit('open-modal', 'result-modal-offical-offer-expiration');
      }
      if (
        [this.RESULT_STATUS.OFFICAL_OFFER_DECLINE].includes(
          item.status_selection
        )
      ) {
        EventBus.$emit(
          'open-modal',
          'result-modal-offical-offer-decline-detail'
        );
      }
    },

    handleModalOfficalOfferEdit() {
      EventBus.$emit('open-modal', 'result-modal-offical-offer-extend');
    },

    handleModalOfficalOfferConfirm() {
      EventBus.$emit('open-modal', 'result-modal-offical-offer-confirm');
    },

    handleModalOfficalOfferDecline() {
      EventBus.$emit('open-modal', 'result-modal-offical-offer-decline');
    },

    onSelectAllCheckboxChange() {
      const tempArr = this.dataResult.filter(
        (item) =>
          ![
            this.RESULT_STATUS.OFFICAL_OFFER,
            this.RESULT_STATUS.OFFICAL_OFFER_CONFIRM,
          ].includes(item.status_selection)
      );
      if (this.selectAll) {
        this.selectedItems = [...tempArr];
      } else {
        this.selectedItems = [];
      }
      tempArr.forEach((item) => {
        item._isSelected = this.selectAll;
      });
    },

    onItemCheckboxChange(item) {
      const newArray = this.dataResult.filter((element) => {
        return ![
          this.RESULT_STATUS.OFFICAL_OFFER,
          this.RESULT_STATUS.OFFICAL_OFFER_CONFIRM,
        ].includes(element.status_selection);
      });
      if (item._isSelected) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );
        this.selectedItems.splice(index, 1);
      }
      this.selectAll = this.selectedItems.length === newArray.length;
    },

    handleCheckDelete() {
      if (this.selectedItems.length > 0) {
        this.statusModalConfirmDelAll = true;
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },

    async handleDeleteAllResult() {
      this.statusModalConfirmDelAll = false;
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });
      if (ids.length > 0) {
        try {
          const response = await deleteMultipleResult({ ids: ids });
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: '削除しました',
            });
            this.selectAll = false;
            this.$emit('handleGetListResult');
          } else {
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: message,
            });
          }
        } catch (error) {
          console.log('error', error);
        }
      } else {
        MakeToast({
          variant: 'warning',
          title: '危険',
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },

    async handleSubmitModalConfirm() {
      const params = {
        id: this.itemDetail.id,
        // status: 3,
        status: this.RESULT_STATUS.OFFICAL_OFFER_CONFIRM,
      };
      const response = await updateResult(params);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: this.$t('SUCCESS'),
          content: message,
        });
        EventBus.$emit('close-modal', 'result-modal-offical-offer-confirm');
        EventBus.$emit('close-modal', 'result-modal-offical-offer');
        this.$emit('handleGetListResult');
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: message,
        });
      }
    },

    async handleDeclineResult() {
      const formData = {
        id: this.itemDetail.id,
        // status: 4,
        status: this.RESULT_STATUS.OFFICAL_OFFER_DECLINE,
        note: '',
      };
      if (!this.declineFormData.fixReasonDecline) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('WARNING_MESS.NOT_SELECT_DECLINE'),
        });
        return 0;
      } else if (this.declineFormData.fixReasonDecline === 'その他') {
        this.checkvalidateNote();
        if (!this.checkvalidateNote()) {
          return;
        }
        formData.note = this.declineFormData.note;
      } else {
        formData.note = this.declineFormData.fixReasonDecline;
      }

      const response = await updateResult(formData);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: this.$t('SUCCESS'),
          content: message,
        });
        EventBus.$emit('close-modal', 'result-modal-offical-offer-decline');
        EventBus.$emit('close-modal', 'result-modal-offical-offer');
        this.$emit('handleGetListResult');
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: message,
        });
      }
    },

    handleCancelReason() {
      EventBus.$emit('close-modal', 'result-modal-offical-offer-decline');
    },

    resetModal() {
      Object.assign(this.declineFormData, {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      });

      Object.assign(this.error, {
        hire_date: true,
        note: true,
      });

      this.step = 1;
      // this.itemDetail = '';
    },

    handleChangeForm(event, field) {
      const newValue = event;
      switch (field) {
        case 'hire_date':
          this.error.hire_date = null;
          if (newValue) {
            this.error.hire_date = true;
          } else {
            this.error.hire_date = false;
          }
          break;

        case 'note':
          this.error.note = null;
          if (newValue) {
            this.error.note = true;
          } else {
            this.error.note = false;
          }
          break;

        case 'time':
          this.error.time = null;
          if (newValue) {
            this.error.time = true;
          } else {
            this.error.time = false;
          }
          break;
        default:
          break;
      }
    },

    validateTime() {
      if (!this.time_offer) {
        this.error.time_offer = false;
      } else {
        return true;
      }
    },

    minDate() {
      const now = new Date();
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      const minDate = new Date(today);
      minDate.setDate(now.getDate());
      return minDate;
    },

    async handleExtendReply(e) {
      e.preventDefault();
      this.validateTime();
      if (this.validateTime() === true) {
        const formData = {
          id: this.itemDetail.id,
          status: this.itemDetail.status_selection,
          // note: '',
          // hire_date: this.itemDetail.hire_date,
          time: moment(this.time_offer).format('YYYYMMDD'),
        };
        try {
          const response = await updateResult(formData);
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: message,
            });
            EventBus.$emit('close-modal', 'result-modal-offical-offer-extend');
            EventBus.$emit('close-modal', 'result-modal-offical-offer');
            // this.goToDetail(this.itemDetail);
            this.$emit('handleGetListResult');
          } else {
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: message,
            });
          }
        } catch (error) {
          console.log('error', error);
        }
      } else {
        e.stopPropagation();
      }
    },

    renderDateString(param) {
      if (param !== null) {
        const year = param.toString().substr(0, 4);
        const month = param.toString().substr(4, 2);
        const day = param.toString().substr(6, 2);

        const formattedDate = year + '-' + month + '-' + day;
        return formattedDate;
      }
      return '';
    },

    // getCurrentPage(value) {
    //   if (value) {
    //     this.queryData.current_page = parseInt(value);
    //     this.$emit('handleGetListResult');
    //   }
    // },
    handleSortTable(ctx) {
      let customSortBy = ctx?.sortBy;
      if (customSortBy === 'id') {
        customSortBy = 'hr_id';
      }
      const sortQuery = {
        field: customSortBy,
        sort_by: ctx.sortDesc ? 'desc' : 'asc',
      };
      this.$emit('handleGetListResult', sortQuery);
      this.selectAll = false;
      this.selectedItems = [];
    },

    onPageChange(page) {
      if (page) {
        this.queryData.current_page = page;
        this.$emit('handleGetListResult');
        this.selectAll = false;
        this.selectedItems = [];
      }
    },
    changeSize(size) {
      if (size) {
        this.queryData.per_page = size;
        this.queryData.current_page = 1;
        this.$emit('handleGetListResult');
        this.selectAll = false;
        this.selectedItems = [];
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/scss/modules/MatchingManagement/Header.scss';

::v-deep #confirm-delete-interview {
  // Moddal
  .modal-content {
    width: 450px;
  }

  .modal-content-title {
    font-weight: 400;
    font-size: 24px;
    line-height: 29px;
    color: #262626;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
  }

  .modal-content-title-del-hr {
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: $black;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
  }

  .hr-list-btns {
    margin-top: 5.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    font-weight: 400;
    font-size: 20px;
    line-height: 30px;
    text-align: center;
    & span {
      color: #ffffff !important;
    }
    & div:nth-child(1),
    & div:nth-child(2) {
      background: #acacac;
      border-radius: 40px;
      padding: 0.5rem 1.75rem;
    }
  }

  .accept {
    background: #f9be00 !important;
  }
}

.content-detail {
  background-color: #f9f9ff;
  padding: 1.5rem 4rem;
  margin-top: 2rem;
}

.hr-list-table-list__table {
  width: max-content;
  height: 100%;
}
.over-flow {
  width: 100%;
  position: relative;
  padding-left: 0;
  overflow-x: auto;
}

// .b-calendar-result {
//   & .dropdown-menu .show {
//     & .b-calendar .b-form-date-calendar .w-100 {
//       & .b-calendar-inner {
//         width: 400px;
//       }
//     }
//   }
// }
</style>
