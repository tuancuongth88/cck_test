<template>
  <div class="require">
    <span>必須</span>
  </div>
</template>
<script>

export default {
  name: 'Require',
};
</script>

<style lang="scss" scoped>
.require {
  display: flex;
  min-width: 45px;
  padding: 2px 4px;
  user-select: none;
  border-radius: 2px;
  width: fit-content;
  background: #FFFFFF;
  align-items: center;
  justify-content: center;
  border: 0.5px solid #FF0000;

  & span {
    color: #FF0000;
    font-weight: bold;
    font-size: 12px;
    text-align: center;
  }
}
</style>
