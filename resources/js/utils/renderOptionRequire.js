const renderOptionRequire = (option) => {
  let arr = [];
  if (option) {
    arr = [...option];
    arr.unshift(
      { key: null, value: '選択してください', disabled: true } // 選択してください
    );
  }
  return arr;
};

export {
  renderOptionRequire
};
// this.$t('VALIDATE.REQUIRED_SELECT')
