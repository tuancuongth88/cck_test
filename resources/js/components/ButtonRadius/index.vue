<!-- Button Border Radius -->
<template>
  <div class="button-radius">
    {{ text }}
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
  name: 'ButtonRadius',
  components: {
    // SearchWithConditions,
  },

  props: {
    text: {
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
.button-radius {
  border: 1px solid;
  border-radius: 50px;
  background: #ACACAC;
  height: 32px;
  width: 100px;
  padding: 0 1rem;
}

</style>

