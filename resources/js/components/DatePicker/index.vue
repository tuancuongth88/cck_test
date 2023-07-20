<template>
  <div>
    <b-form-datepicker
      v-model="date"
      :locale="locale"
      :class="{ 'date_picker': true, 'validate-warning': validate }"
      :disabled="disabled"
      :min="min"
      :max="max"
      :readonly="readonly"
      :placeholder="$t('NO_DATE_SELECTED')"
      :label-no-date-selected="$t('NO_DATE_SELECTED')"
      :label-help="$t('DATE_PICKER_LABEL_HELP')"
      :calendar-width="`290px`"
      :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
    >
      // Icon input
      <template #button-content>
        <i class="fad fa-calendar-day" />
      </template>

      // Month
      <template #nav-prev-month>
        <i class="fas fa-angle-left" />
      </template>

      <template #nav-next-month>
        <i class="fas fa-angle-right" />
      </template>

      // Year
      <template #nav-prev-year>
        <i class="fad fa-angle-double-left" />
      </template>

      <template #nav-next-year>
        <i class="fad fa-angle-double-right" />
      </template>
    </b-form-datepicker>
  </div>
</template>

<script>
export default {
  name: 'DatePicker',
  props: {
    disabled: {
      type: Boolean,
      default: false,
    },
    readonly: {
      type: Boolean,
      default: false,
    },
    min: {
      type: String,
      default: '',
    },
    max: {
      type: String,
      default: '',
    },
    validate: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      date: '',
    };
  },
  computed: {
    locale() {
      return this.$store.getters.language;
    },
    dataChange() {
      return this.$attrs.value;
    },
  },
  watch: {
    date() {
      this.$emit('input', this.date);
    },
    dataChange() {
      this.date = this.$attrs.value;
    },
  },
  created() {
    this.date = this.$attrs.value;
  },
};
</script>
