<template>
  <div class="arbitrarily">
    <span>{{ $t('ARBITRARY') }}</span>
  </div>
</template>
<script>
export default {
  name: 'Arbitrarily',
};
</script>

<style lang="scss" scoped>
.arbitrarily {
  // display: flex;
  // min-width: 45px;
  // padding: 2px 4px;
  // user-select: none;
  // border-radius: 2px;
  // width: fit-content;
  // background: #ffffff;
  // align-items: center;
  // justify-content: center;
  // border: 0.5px solid #999999;
  color: #999 !important;
  width: 28px;
  height: 13px;
  display: flex;
  font-size: 8px !important;
  align-items: center;
  border: 1px solid #999;
  background-color: #fff;
  justify-content: center;

  & span {
    color: #999999;
    font-size: 8px;
    font-weight: bold;
    text-align: center;
  }
}
</style>
