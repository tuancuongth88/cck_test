<template>
  <div class="hr-detail-matching-situation-tab">
    <div class="hr-detail-list-status-process">
      <div class="hr-detail-list-status-process-item border-t border-l border-r ">
        <div class="hr-detail-list-status-process-item--head  border-b">
          <span class="label-entry-applycation">{{ $t('HR_LIST.ENTRY_APPLICATION') }}</span>
        </div>
        <div class="hr-detail-list-status-process--content">
          <div class="hr-detail-list-status">
            <div v-for="(entry_item, index) in dataEntries" :key="`entries-${index}`" class="hr-detail-list-status-item">
              <div>
                <div class="hr-detail-list-status-item__status">
                  <div :class="entry_item.status" class="hr-status-block">
                    <span class="one-line-paragraph status-entry">{{ handleRenderTextByStatus(1, entry_item.origin_status) }}</span>
                  </div>
                </div>

                <div class="hr-detail-list-status-item__candidate-info">
                  <span class="one-line-paragraph entry-title-company" :title="`${entry_item.job_title}/${entry_item.company_name}`">{{ entry_item.job_title }}/{{ entry_item.company_name }}</span>
                </div>
              </div>
              <div>
                <div class="hr-detail-list-status-item__entry-date">
                  <div class="label-entry-date">{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                  <span class="one-line-paragraph entry-date">{{ entry_item.entry_date }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="hr-detail-list-status-process-item border-t border-l border-r ">
        <div class="hr-detail-list-status-process-item--head  border-b">
          <span class="label-offer-received">{{ $t('HR_LIST.OFFER_RECEIVED') }}</span>
        </div>

        <div class="hr-detail-list-status-process--content">
          <div class="hr-detail-list-status">
            <div v-for="(offer_item, index) in dataOffers" :key="`ofer-${index}`" class="hr-detail-list-status-item">
              <div>
                <div class="hr-detail-list-status-item__status">
                  <div :class="offer_item.status" class="hr-status-block">
                    <span class="one-line-paragraph offer-status">{{ handleRenderTextByStatus(2, offer_item.origin_status) }}</span>
                  </div>
                </div>

                <div class="hr-detail-list-status-item__candidate-info">
                  <span class="one-line-paragraph offer-title-company">{{ offer_item.job_title }}/{{ offer_item.company_name }}</span>
                </div>
              </div>

              <div>
                <div class="hr-detail-list-status-item__entry-date">
                  <div class="label-offer-date">{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                  <span class="one-line-paragraph offer-date">{{ offer_item.entry_date }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="hr-detail-list-status-process-item border-t border-l border-r ">
        <div class="hr-detail-list-status-process-item--head  border-b">
          <span class="label-arr-interview">{{ $t('HR_LIST.ARRINTERVIEW') }}</span>
        </div>

        <div class="hr-detail-list-status-process--content">
          <div class="hr-detail-list-status">
            <div v-for="(inertview_item, index) in dataInterviews" :key="`inerview-${index}`" class="hr-detail-list-status-item">
              <div>
                <div class="hr-detail-list-status-item__status">
                  <div :class="inertview_item.status" class="hr-status-block">
                    <span class="one-line-paragraph interview-status">{{ handleRenderTextByStatus(3, inertview_item.origin_status) }}</span>
                  </div>
                </div>

                <div class="hr-detail-list-status-item__candidate-info">
                  <span class="one-line-paragraph interview-title-company">{{ inertview_item.job_title }}/{{ inertview_item.company_name }}</span>
                </div>
              </div>

              <div>
                <div class="hr-detail-list-status-item__entry-date">
                  <div class="label-interview-date">{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                  <span class="one-line-paragraph interview-date">{{ inertview_item.entry_date }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="hr-detail-list-status-process-item border-t border-l border-r border-b">
        <div class="hr-detail-list-status-process-item--head  border-b">
          <span class="label-result">{{ $t('HR_LIST.RESULT') }}</span>
        </div>

        <div class="hr-detail-list-status-process--content">
          <div class="hr-detail-list-status">
            <div v-for="(result_item, index) in dataResults" :key="`result-${index}`" class="hr-detail-list-status-item">
              <div>
                <div class="hr-detail-list-status-item__status">
                  <div :class="result_item.status" class="hr-status-block">
                    <span class="one-line-paragraph result-status">{{ handleRenderTextByStatus(4, result_item.origin_status) }}</span>
                  </div>
                </div>

                <div class="hr-detail-list-status-item__candidate-info">
                  <span class="one-line-paragraph result-title-company">{{ result_item.job_title }}/{{ result_item.company_name }}</span>
                </div>
              </div>

              <div>
                <div class="hr-detail-list-status-item__entry-date">
                  <div class="label-result-date">{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                  <span class="one-line-paragraph result-date">{{ result_item.entry_date }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TabEMatchingSituationDetail',
  props: {
    detailData: {
      type: Object,
      require: true,
      default: function() {
        return {};
      },
    },
  },
  data() {
    return {
      dataEntries: [],
      dataOffers: [],
      dataInterviews: [],
      dataResults: [],
    };
  },
  watch: {
    detailData: {
      handler: function(props_data) {
        if (props_data) {
          this.handleConvertData(props_data);
        }
      },
      deep: true,
    },
  },
  methods: {
    handleConvertData(DATA) {
      if (DATA['entry']) {
        DATA['entry'].forEach((item) => {
          this.dataEntries.push(
            {
              origin_status: item['status'],
              status: this.handleTransformStatus(1, item['status']),
              job_title: item['job_title'],
              company_name: item['company_name'],
              // entry_date: this.handleTransformTimestamp(item['updated_at']),
              entry_date: item['updated_at'],
            }
          );
        });
      }

      if (DATA['offer']) {
        DATA['offer'].forEach((item) => {
          this.dataOffers.push(
            {
              origin_status: item['status'],
              status: this.handleTransformStatus(2, item['status']),
              job_title: item['job_title'],
              company_name: item['company_name'],
              // entry_date: this.handleTransformTimestamp(item['updated_at']),
              entry_date: item['updated_at'],
            }
          );
        });
      }

      if (DATA['interview']) {
        DATA['interview'].forEach((item) => {
          this.dataInterviews.push(
            {
              origin_status: item['status'],
              status: this.handleTransformStatus(3, item['status']),
              job_title: item['job_title'],
              company_name: item['company_name'],
              // entry_date: this.handleTransformTimestamp(item['updated_at']),
              entry_date: item['updated_at'],
            }
          );
        });
      }

      if (DATA['result']) {
        DATA['result'].forEach((item) => {
          this.dataResults.push(
            {
              origin_status: item['status'],
              status: this.handleTransformStatus(4, item['status']),
              job_title: item['job_title'],
              company_name: item['company_name'],
              // entry_date: this.handleTransformTimestamp(item['updated_at']),
              entry_date: item['updated_at'],
            }
          );
        });
      }
    },

    handleTransformTimestamp(string) {
      let result;

      if (string) {
        const YMD = string.split('T')[0];
        const Y = YMD.split('-')[0];
        const M = YMD.split('-')[1];
        const D = YMD.split('-')[2];

        if (Y && M && D) {
          result = `${Y}年${M}月${D}日`;
        }
      }

      return result;
    },

    handleRenderTextByStatus(type, status) {
      if (type === 1) {
        switch (status) {
          case 1:
            return '申請中';
          case 2:
            return '却下';
          case 3:
            return '辞退';
          case 4:
            // return '他社内定';
            return '確認';
          default:
            break;
        }
      }

      if (type === 2) {
        switch (status) {
          case 1:
            return '申請中';
          case 2:
            return '辞退';
          case 3:
            return '承認';
          default:
            break;
        }
      }

      if (type === 3) {
        switch (status) {
          case 1:
            return '書類通過';
          case 2:
            return 'オファー承認';
          case 3:
            return '面接中止';
          case 4:
            return '面接辞退';
          case 5:
            return '一次合格';
          case 6:
            return '二次合格';
          case 7:
            return '三次合格';
          case 8:
            return '四次合格';
          case 9:
            return '五次合格';
          default:
            break;
        }
      }

      if (type === 4) {
        switch (status) {
          case 1:
            return '内定';
          case 2:
            return '不合格';
          case 3:
            return '内定承諾';
          case 4:
            return '内定辞退';
          default:
            break;
        }
      }
    },
    handleTransformStatus(type, status) {
      if (type === 1) {
        switch (status) {
          case 1:
            return 'grey-frame';
          case 2:
            return 'red-frame';
          case 3:
            return 'red-frame';
          case 4:
            // return 'grey-frame';
            return 'blue-frame';
          case 5:
          default:
            break;
        }
      }

      if (type === 2) {
        switch (status) {
          case 1:
            return 'grey-frame';
          case 2:
            return 'red-frame';
          case 3:
            return 'blue-frame';
          default:
            break;
        }
      }

      if (type === 3) {
        switch (status) {
          case 1:
            return 'blue-frame';
          case 2:
            return 'blue-frame';
          case 3:
            return 'red-frame';
          case 4:
            return 'red-frame';
          case 5:
            return 'blue-frame';
          case 6:
            return 'blue-frame';
          case 7:
            return 'blue-frame';
          case 8:
            return 'blue-frame';
          case 9:
            return 'blue-frame';
          default:
            break;
        }
      }

      if (type === 4) {
        switch (status) {
          case 1:
            return 'grey-frame';
          case 2:
            return 'grey-frame';
          case 3:
            return 'blue-frame';
          case 4:
            return 'red-frame';
          default:
            break;
        }
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/pages/Hr/Detail/HRDetail.scss/';
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';

.hr-detail-matching-situation-tab {
	width: 100%;
	display: flex;
  min-height: 91%;
	align-items: stretch;
	justify-content: flex-start;
}

.hr-detail-list-status-process {
  width: 100%;
	display: flex;
	align-items: stretch;
  flex-direction: column;
	justify-content: flex-start;
}

.hr-detail-list-status-process-item {
  width: 100%;
}

.hr-detail-list-status-process-item--head {
  width: 100%;
  height: 47px;
  display: flex;
  padding: 0 0.5rem;
  background: #F7F7F7;
  align-items: center;

  & span {
    font-size: 14px;
    font-weight: 700;
    line-height: 21px;
    color: $titleClassify;
  }
}

.hr-detail-list-status-process--content {
  width: 100%;
  display: flex;
	align-items: stretch;
  flex-direction: column;
  padding: 0.75rem 0.5rem;
	justify-content: flex-start;
}

.hr-detail-list-status {
  width: 100%;
  gap: 0.5rem;
  display: flex;
	align-items: center;
  flex-direction: column;
	justify-content: flex-start;
}

.hr-detail-list-status-item {
  width: 100%;
  gap: 0.5rem;
  display: flex;
	align-items: center;
	justify-content: space-between;

  & > div:nth-child(1) {
    gap: 1.75rem;
    display: flex;
    max-width: 55%;
    align-items: center;
    justify-content: flex-start;
  }

  & > div:nth-child(2) {
    width: 100%;
    max-width: 45%;
    align-items: center;
    justify-content: flex-end;
  }
}

.hr-detail-list-status-item__status {
  // max-width: 25%;
  // min-width: 88px;
  max-width: 35%;
  min-width: 100px;
}

.hr-status-block {
  width: 100%;
  height: 28px;
  display: flex;
  color: #666666;
  cursor: default;
  border-radius: 5px;
  padding: 0 0.25rem;
	align-items: center;
  background: #FFFFFF;
	justify-content: center;
  border: 1px solid #666666;

  & span {
    font-size: 14px;
    font-weight: 400;
    line-height: 21px;
  }
}

.hr-detail-list-status-item__candidate-info {
  display: flex;
  max-width: 75%;
	align-items: center;
	justify-content: space-between;

  & span {
    font-size: 14px;
    font-weight: 400;
    line-height: 21px;
    color: $black !important;
  }
}

.hr-detail-list-status-item__entry-date {
  display: flex;
  color: #767676;
  font-size: 12px;
  font-weight: 400;
  line-height: 18px;
  text-align: right;
	align-items: center;
	justify-content: flex-end;
}

// 1 申請中
.applying { border: 1px solid $applying!important; color: $applying!important;}
// 2 承認済
.approved {border: 1px solid $approved!important; color: $approved!important;}
// 3 辞退
.decline {border: 1px solid $decline!important; color: $decline!important;}
// 4 面接準備中
.preparing {border: 1px solid $preparing!important; color: $preparing!important;}
// 5 一次合格
.first {border: 1px solid $first!important; color: $first!important;}
// 6 内定
.unofficial {border: 1px solid $unofficial!important; color: $unofficial!important;}
// 7 キャンセル
.cancel {border: 1px solid $cancel!important; color: $cancel!important;}
</style>

