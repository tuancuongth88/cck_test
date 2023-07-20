const account_classification_option = [
  { key: 1, value: '申請中', translate: 'Requesting' },
  { key: 2, value: '却下', translate: 'Reject' },
  { key: 3, value: '辞退', translate: 'Decline' },
  { key: 4, value: '他社内定', translate: 'Other company official offer	' },
];

const reasonResultOptions = [
  { text: '他社内定', value: '他社内定' },
  { text: '就業継続（転職断念）', value: '就業継続（転職断念）' },
  { text: '条件不一致（年収）', value: '条件不一致（年収）' },
  { text: '条件不一致（勤務地）', value: '条件不一致（勤務地）' },
  { text: '条件不一致（その他待遇）', value: '条件不一致（その他待遇）' },
  { text: '就業環境', value: '就業環境' },
  { text: '家族の反対', value: '家族の反対' },
  { text: '在留資格不一致', value: '在留資格不一致' },
  { text: '本人との連絡不通 ', value: '本人との連絡不通 ' },
  { text: 'その他', value: 'その他' },
];

const reasonOfferOptions = [
  { key: '他社内定', value: '他社内定' },
  { key: '就業継続（転職断念）', value: '就業継続（転職断念）' },
  { key: '条件不一致（年収）', value: '条件不一致（年収）' },
  { key: '条件不一致（勤務地）', value: '条件不一致（勤務地）' },
  { key: '条件不一致（その他待遇）', value: '条件不一致（その他待遇）' },
  { key: '就業環境', value: '就業環境' },
  { key: '家族の反対', value: '家族の反対' },
  { key: '在留資格不一致', value: '在留資格不一致' },
  { key: '本人との連絡不通 ', value: '本人との連絡不通 ' },
  { key: '条件不一致（年収）', value: '条件不一致（年収）' },
  { key: 'その他', value: 'その他' },
];

export { account_classification_option, reasonResultOptions, reasonOfferOptions };
