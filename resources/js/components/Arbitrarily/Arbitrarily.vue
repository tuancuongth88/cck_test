<template>
  <div class="arbitrarily">
    <span>任意</span>
  </div>
</template>
<script>

export default {
  name: 'Arbitrarily',
};
</script>

<style lang="scss" scoped>
.arbitrarily {
  display: flex;
  min-width: 45px;
  padding: 2px 4px;
  user-select: none;
  border-radius: 2px;
  width: fit-content;
  background: #FFFFFF;
  align-items: center;
  justify-content: center;
  border: 0.5px solid #999999;

  & span {
    color: #999999;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
  }
}
</style>
