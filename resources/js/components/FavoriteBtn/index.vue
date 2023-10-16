<!-- Favorite -->
<template>
  <div
    class="btn favorite-btn"
    :style="`height: ${height}; width: ${width};font-size: ${fontSize}`"
  >
    <img
      :src="require('@/assets/images/login/heart.png')"
      alt="heart"
      style="height: 20px; width: 20px"
    >
    <div>
      <span class="added-to-favorites">{{ $t('ADDED_TO_FAVORITES') }}</span>
      <span class="removed-to-favorites">{{
        $t('REMOVE_FROM_FAVORITES')
      }}</span>
    </div>
  </div>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import SearchWithConditions from '../../layout/components/search/SearchWithConditions.vue';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'FavoriteBtn',
  components: {
    // SearchWithConditions,
  },

  props: {
    text: {
      type: String,
      default: '',
    },
    height: {
      type: String,
      default: '',
    },
    width: {
      type: String,
      default: '',
    },
    fontSize: {
      type: String,
      default: '',
    },
  },

  data() {
    return {};
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
    // currChange() {
    //   return this.queryData.page;
    // },
  },

  created() {
    // this.getListAllData();
  },

  methods: {},
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
.favorite-btn {
  border: 1px solid #cdcdcd;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  background: #ffffff;
  min-width: 152px;
  width: 100%;
  height: 38px;

  display: flex;
  justify-content: center;
  align-items: center;
  gap: 4px;
  & > div {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
}
.added-to-favorites {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  color: #b9b9b9;
}
.removed-to-favorites {
  font-style: normal;
  font-weight: 500;
  font-size: 10px;
  line-height: 10.5px;
  color: #cccccc;
}
</style>
