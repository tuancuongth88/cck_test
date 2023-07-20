<template>
  <div class="dev">
    <div class="favorite-wrapper">
      <div class="favorite-job">
        <h2 class="block-title">お気に入りの求人</h2>
        <JobFavoriteItem />
        <JobFavoriteItem />
        <JobFavoriteItem />
      </div>
      <div class="favorite-hr">
        <h2 class="block-title">
          お気に入りの人材
        </h2>
        <b-row>
          <HrFavoriteItem />
          <HrFavoriteItem />
          <HrFavoriteItem />
        </b-row>
      </div>
    </div>
  </div>
</template>

<script>
import JobFavoriteItem from '../../components/Favorite/JobFavoriteItem.vue';
import HrFavoriteItem from '../../components/Favorite/HrFavoriteItem.vue';
export default {
  name: 'Favorite',
  components: {
    JobFavoriteItem,
    HrFavoriteItem,
  },
  data() {
    return {};
  },
  computed: {
    language() {
      return this.$store.getters.language;
    },
  },
  methods: {
  },
};
</script>

<style lang="scss" scoped>
  .favorite-job {
    border: 1px solid #999;
    padding: 20px;
  }
  .favorite-hr {
    background: #F9F9FF;
    border: 1px solid #999;
    padding: 20px;
  }
</style>
