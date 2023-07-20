<!-- Confirm -->
<template>
  <div class="btn btn-back" :style="`height: ${height}; font-size: ${fontSize}`">
    <span>{{ text }}</span>
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
  name: 'BtnConfirm',
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
    fontSize: {
      type: String,
      default: '',
    },
  },

  data() {
    return {

    };
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

  methods: {

  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
.btn-back {
  border: 1px solid #F9BE00;
  border-radius: 50px;
  background: #F9BE00;
  color: $white;
  height: 30px;
  padding: 0 1.75rem;
  font-size: 16px;
  font-weight: 400;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>

