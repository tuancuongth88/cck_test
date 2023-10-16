<template>
  <div class="select-language">
    <b-img :class="{ 'active-lang': language === constLang.JAPANESE }" :src="imageSrcJp" alt="Responsive image" @click="handleChangeLang(constLang.JAPANESE)" />
    <b-img :class="{ 'active-lang': language === constLang.ENGLISH }" :src="imageSrcEn" alt="Responsive image" @click="handleChangeLang(constLang.ENGLISH)" />
  </div>
</template>

<script>

import { MakeToast } from '../../utils/toastMessage';
import constLang from '../../const/language';

export default {
  name: 'MenuRight',
  data() {
    return {
      constLang: constLang,
      imageNameJp: 'japan.jpg',
      imageNameEn: 'united-kingdom.jpg',
    };
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
    imageSrcJp() {
      return require(`@/assets/images/icons/${this.imageNameJp}`);
    },
    imageSrcEn() {
      return require(`@/assets/images/icons/${this.imageNameEn}`);
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
    @import "../../scss/_variables.scss";
    .select-language {
        img {
          width: 34px;
          cursor: pointer;
          &.active-lang {
					box-shadow: 0px 0px 0px 2px red;
          }
        }
    }
</style>
