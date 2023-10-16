<template>
  <div>
    <div class="dev">
      <h1 class="title">Dev</h1>

      <div class="container-fluid">

        <div class="item">
          <h4>Multi language</h4>

          <div class="container change-lang">
            <b-row>
              <b-col>
                <b-button :class="{ 'active-lang': language === constLang.ENGLISH }" @click="handleChangeLang(constLang.ENGLISH)">English</b-button>
              </b-col>

              <b-col>
                <b-button :class="{ 'active-lang': language === constLang.JAPANESE }" @click="handleChangeLang(constLang.JAPANESE)">Japanese</b-button>
              </b-col>
            </b-row>
          </div>
        </div>

        <div class="item">
          <h4>Month Picker</h4>

          <div class="container display-month">
            <span><strong>Data: </strong> {{ month }} </span>

            <MonthPicker v-model="month" />

            <b-button style="margin-top: 10px;" @click="month = ''">Clear Year Month</b-button>
          </div>
        </div>

        <div class="item">
          <h4>Date Picker</h4>

          <div class="container">
            <span class="date-picker-value"><strong>Value: </strong>{{ date }}</span>

            <DatePicker v-model="date" />
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { MakeToast } from '../../utils/toastMessage';
import MonthPicker from '../../components/MonthPicker/index.vue';
import DatePicker from '../../components/DatePicker/index.vue';
import constLang from '../../const/language';

export default {
  name: 'Dev',
  components: {
    MonthPicker,
    DatePicker,
  },
  data() {
    return {
      month: '',
      date: '',
      constLang: constLang,
    };
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
  },
  methods: {
    handleChangeLang(lang) {
      this.$store.dispatch('app/setLanguage', lang)
        .then(() => {
          this.$i18n.locale = lang;
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: 'Change language successfully',
          });
        })
        .catch(() => {
          MakeToast({
            variant: 'danger',
            title: 'Danger',
            content: 'Language change failed',
          });
        });
    },
  },
};
</script>

<style lang="scss" scoped>
    @import '../../scss/_variables.scss';

    .dev {
        height: 100vh;
        .title {
            text-align: center;
        }

        .item {
            text-align: left;
            margin-bottom: 10px;

            h4 {
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .change-lang {
                text-align: center;
                button {
                    min-width: 200px;
                    margin-top: 5px;
                    margin-bottom: 5px;
                    border: none;
                }

                .active-lang {
                    background-color: $sorbus;
                }
            }

            .display-month {
                strong {
                    margin-top: 20px;
                    margin-bottom: 20px;
                }
            }

        }
    }
</style>
