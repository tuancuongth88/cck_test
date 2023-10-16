<template>
  <div class="d-flex pagination-container justify-end align-center mt-2">
    <div class="record-info mr-3">
      <!-- {{ `${totalRows}件の${startRecord}〜${endRecord}を表示` }} -->
      {{
        `${totalRows}${$t('DISPLAY_LABEL')}${startRecord}〜${endRecord}${$t(
          'RESULT'
        )}`
      }}
    </div>
    <div class="select-more-per-page">
      <b-form-select
        v-model="selectedSize"
        class="select-size-input"
        dusk="selected_record"
        :options="optionsSizes"
        @change="changeSize"
      />
    </div>
    <div class="ml-3">
      <b-pagination
        v-model="pageNumber"
        :total-rows="totalRows"
        :per-page="perPage"
        pills
        class="w-100"
        :next-class="'next'"
        :prev-class="'prev'"
        @change="($event) => changePagination($event)"
      />
    </div>
  </div>
</template>

<script>
import {
  PAGINATION_CONSTANT,
  PAGINATION_SELECTED_RECORDS,
} from '@/const/config.js';

export default {
  name: 'CustomPagination',
  props: {
    keyTab: {
      required: false,
      type: String,
      default: 'pagination-data',
    },
    currentPage: {
      required: true,
      type: Number,
    },
    totalRows: {
      required: true,
      type: Number,
    },
    perPage: {
      required: true,
      type: Number,
    },
  },
  data() {
    return {
      pageNumber: 1,
      selectedSize: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      startRecord: 0,
      endRecord: 0,
    };
  },
  computed: {
    optionsSizes() {
      return PAGINATION_SELECTED_RECORDS;
    },
  },
  watch: {
    currentPage(value) {
      this.pageNumber = value;
      this.updateRecordInfo();
    },
    perPage(value) {
      this.selectedSize = value;
      this.updateRecordInfo();
    },
    keyTab(value) {
      if (value) {
        this.pageNumber = this.currentPage;
        this.selectedSize = this.perPage;
        this.updateRecordInfo();
      }
    },
  },
  created() {
    this.pageNumber = this.currentPage;
    this.selectedSize = this.perPage;
    this.updateRecordInfo();
  },
  methods: {
    updateRecordInfo() {
      this.startRecord =
        this.totalRows !== 0
          ? this.selectedSize * (this.currentPage - 1) + 1
          : 0;
      this.endRecord =
        this.totalRows < this.selectedSize * this.currentPage
          ? this.totalRows
          : this.selectedSize * this.currentPage;
    },
    changePagination(page) {
      this.$emit('pagechanged', Number(page));
      this.updateRecordInfo();
    },
    changeSize() {
      this.$emit('changeSize', this.selectedSize);
      this.updateRecordInfo();
    },
  },
};
</script>

<style lang="scss" scoped>
.pagination-container {
  .select-size-input {
    height: 29px;
    padding: 1px 24px;
  }
  & ul {
    margin-top: auto;
    margin-bottom: auto;
  }
  .record-info {
    color: #000;
  }
}
</style>
