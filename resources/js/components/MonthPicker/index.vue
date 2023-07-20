<template>
  <div v-click-outside="hide" class="month-picker">
    <i class="fad fa-calendar-day" />
    <input
      :id="inputId"
      ref="input"
      :class="{ 'month-picker__input': true, 'form-control': true, 'validate-warning': validate }"
      type="text"
      readonly
      :value="showDate"
      :disabled="disabled"
      @click="show()"
      @input="updateValue()"
    >

    <div v-if="pickerVisible" class="month-picker__container" :style="{top: ($refs.input.offsetHeight + 5) + 'px'}">
      <div class="month-picker__year">
        <button class="month-picker__year-btn" :disabled="year <= 1" @click="prevYear">
          <i class="fas fa-angle-left" />
        </button>
        <span class="month-picker__show-year">{{ year }}</span>
        <button class="month-picker__year-btn" @click="nextYear">
          <i class="fas fa-angle-right" />
        </button>
      </div>

      <div class="month-picker__monthes">
        <a
          v-for="(m, index) in months"
          :key="'month'+index"
          href=""
          class="month-picker__month"
          :class="{ 'month-picker__month_selected': (index + 1 === currentMonth) }"
          @click.prevent="pickMonth(index)"
        >{{ $t(m) }}</a>
      </div>
    </div>
  </div>
</template>

<script>

// Import const
import constMonthPicker from '@/const/monthPicker';

// Import function helper
import {
  formatMonth,
  getObjectYM,
} from '@/utils/handleDate';

import {
  validateYYYYMM,
} from '@/utils/validate';

export default {
  name: 'MonthPicker',
  /**
   * Directives handle click in-side or out-side
   */
  directives: {
    'click-outside': {
      bind: function(el, binding, vNode) {
        const bubble = binding.modifiers.bubble;
        const handler = (e) => {
          if (bubble || (!el.contains(e.target) && el !== e.target)) {
            binding.value(e);
          }
        };

        el.__vueClickOutside__ = handler;
        document.addEventListener('click', handler);
      },

      unbind: function(el, binding) {
        document.removeEventListener('click', el.__vueClickOutside__);
        el.__vueClickOutside__ = null;
      },
    },
  },
  /**
   * Config prop
   */
  props: {
    inputId: {
      type: String,
      default: '',
    },
    defaultYear: {
      type: Number,
      default: new Date().getFullYear(),
    },
    defaultMonth: {
      type: Number,
      default: new Date().getMonth() + 1,
    },
    months: {
      type: Array,
      default: () => constMonthPicker.LIST_MONTHS,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    validate: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      pickerVisible: false,
      year: this.defaultYear,
      month: this.defaultMonth,

      currentYear: '',
      currentMonth: '',
    };
  },
  computed: {
    /**
       * Handle show data in input
       */
    showDate() {
      if (this.currentYear && this.currentMonth) {
        this.$emit('input', `${this.year}/${formatMonth(this.month)}`);

        const lang = this.$store.getters.language;

        if (lang === 'en') {
          return `${this.$t(this.months[this.month - 1])}/${this.year}`;
        }

        return `${this.year}/${this.$t(this.months[this.month - 1])}`;
      } else {
        return `${this.$t('NO_DATE_SELECTED')}`;
      }
    },

    dataChange() {
      return this.$attrs.value;
    },
  },
  watch: {
    defaultMonth(val) {
      this.month = val;
    },
    defaultYear(val) {
      this.year = val;
    },

    /**
     * Check v-model change
     */
    dataChange() {
      if (validateYYYYMM(this.$attrs.value)) {
        const DATA = getObjectYM(this.$attrs.value);

        this.year = parseInt(DATA.year);
        this.month = parseInt(DATA.month);

        this.currentYear = parseInt(DATA.year);
        this.currentMonth = parseInt(DATA.month);
      } else {
        this.currentYear = '';
        this.currentMonth = '';
      }
    },
  },
  created() {
    /**
       * Handle created component
       */
    if (validateYYYYMM(this.$attrs.value)) {
      const DATA = getObjectYM(this.$attrs.value);

      this.year = parseInt(DATA.year);
      this.month = parseInt(DATA.month);

      this.currentYear = parseInt(DATA.year);
      this.currentMonth = parseInt(DATA.month);
    }
  },
  methods: {
    /**
       * Function handle show popup pick month
       */
    show() {
      this.pickerVisible = !this.pickerVisible;

      if ((this.currentMonth && this.currentYear)) {
        this.year = this.currentYear;
        this.month = this.currentMonth;
      } else {
        this.year = this.defaultYear;
        this.month = this.defaultMonth;
      }
    },

    /**
     * Function handle hide pop-up
     */
    hide() {
      this.pickerVisible = false;
    },

    /**
     * Function handle pick month
     */
    pickMonth(month) {
      this.month = month + 1;
      this.$emit('input', `${this.year}/${formatMonth(this.month)}`);

      this.currentYear = this.year;
      this.currentMonth = this.month;

      this.hide();
    },

    /**
     * Function handel pick year prev
     */
    prevYear() {
      this.year = this.year - 1;

      if (this.year > 1) {
        if (this.currentMonth) {
          this.$emit('input', `${this.year}/${formatMonth(this.month)}`);
        }
      }
    },

    /**
     * Function handel pick year next
     */
    nextYear() {
      this.year = this.year + 1;

      if (this.currentMonth) {
        this.$emit('input', `${this.year}/${formatMonth(this.month)}`);
      }
    },
  },
};
</script>

<style scoped lang="scss">
    @import '../../scss/_variables.scss';

    .month-picker {
        position: relative;

        &__container {
            width: 310px;
            border: 1px solid $silver;
            border-radius: 4px;
            background-color: $white;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
            z-index: 10;
            overflow: hidden;
            position: absolute;
            left: 0;
            padding: 8px;
        }

        &__input {
            background-color: $white;
            width: 100%;
            border-radius: .25rem;
            padding: 6px 12px;
            cursor: pointer;
            text-indent: 25px;

            &:disabled {
                color: #6c757d;
                cursor: default;
                background: $athens-gray;
            }
        }

        &__monthes {
            display: flex;
            flex-wrap: wrap;

            a {
                &:hover {
                    text-decoration: none;
                }
            }
        }

        &__year {
            display: flex;
            padding: 10px;
            background-color: $abbey;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        &__year-btn {
            flex: 0 0 40px;
            height: 33px;
            line-height: 33px;
            background-color: transparent;
            border: none;
            border-radius: .25rem;
            background-color: $sorbus;

            i {
                font-size: 20px;
                color: $white;
            }

            &:hover {
                opacity: .8;
            }

            &:disabled {
                opacity: .8;
            }
        }

        &__show-year {
            flex: 0 0 calc(100% - 80px);
            text-align: center;
            padding-top: 7px;
            font-size: 1.1rem;
            font-weight: bold;
            color: $white;
        }

        &__month {
            box-sizing: border-box;
            flex: 0 0 calc(100% / 3);
            text-align: center;
            padding: 10px 0;
            color: $shark;
            border-radius: 5px;
            font-weight: 500;

            &_selected {
                background-color: $sorbus !important;
                color: $white;
            }

            &:nth-child(3n+3) {
                border-right: 0;
            }

            &:hover {
                background-color: #f3f3f3;
            }
        }
    }

    .month-picker > i {
        position: absolute;
        left: 0;
        padding: 10px;
        pointer-events: none;
        font-size: 20px;
        color: $sorbus;
    }
</style>
