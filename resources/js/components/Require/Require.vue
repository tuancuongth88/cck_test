<template>
  <div class="require">
    <span>{{ $t('REQUIRED') }}</span>
  </div>
</template>
<script>

export default {
  name: 'Require',
};
</script>

<style lang="scss" scoped>
.require {
  // display: flex;
  // min-width: 45px;
  // padding: 2px 4px;
  // user-select: none;
  // border-radius: 2px;
  // width: fit-content;
  // background: #FFFFFF;
  // align-items: center;
  // justify-content: center;
  // border: 0.5px solid #FF0000;
  color: red;
  width: 28px;
  height: 13px;
  display: flex;
  font-size: 8px;
  align-items: center;
  border: 1px solid red;
  justify-content: center;

  & span {
    color: #FF0000;
    font-weight: bold;
    font-size: 8px;
    text-align: center;
  }
}
</style>
