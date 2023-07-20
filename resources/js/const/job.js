const incomeOption = [
  { key: null, value: '指定なし' }, // Không xác định
  { key: '2,000,000', value: '￥2,000,000' },
  { key: '2,500,000', value: '￥2,500,000' },
  { key: '3,000,000', value: '￥3,000,000' },
  { key: '3,500,000', value: '￥3,500,000' },
  { key: '4,000,000', value: '￥4,000,000' },
  { key: '4,500,000', value: '￥4,500,000' },
  { key: '5,000,000', value: '￥5,000,000' },
  { key: '5,500,000', value: '￥5,500,000' },
  { key: '6,000,000', value: '￥6,000,000' },
  { key: '6,500,000', value: '￥6,500,000' },
  { key: '7,000,000', value: '￥7,000,000' },
  { key: '7,500,000', value: '￥7,500,000' },
  { key: '8,000,000', value: '￥8,000,000' },
  { key: '8,500,000', value: '￥8,500,000' },
  { key: '9,000,000', value: '￥9,000,000' },
  { key: '9,500,000', value: '￥9,500,000' },
  { key: '10,000,000', value: '￥10,000,000' },
];

const jaLevelOption = [
  { key: 1, value: 'N1' },
  { key: 2, value: 'N2' },
  { key: 3, value: 'N3' },
  { key: 4, value: 'N4' },
  { key: 5, value: 'N5' },
  { key: 6, value: 'なし' },
];

const formEmployeeOption = [
  { key: 1, value: '正社員' },
  { key: 2, value: '契約社員' },
  { key: 3, value: '派遣社員' },
  { key: 4, value: 'その他' },
];

const passionOption = [
  { key: 1, value: '住宅手当有' },
  { key: 2, value: '福利厚生充実' },
  { key: 3, value: '退職金有' },
  { key: 4, value: '年間休日120日以上' },
  { key: 5, value: '寮有' },
  { key: 6, value: '未経験可' },
  { key: 7, value: '外国人管理職登用実績有' },
  { key: 8, value: 'リモート勤務可' },
  { key: 9, value: '引っ越し手当有' },
  { key: 10, value: '週28時間以下OK' },
];

const countryOptions = [
  { key: 1, value: 'ベトナム', transtate: 'Vietnam' },
  { key: 2, value: 'ミャンマー', transtate: 'Myanmar' },
  { key: 3, value: 'フィリピン', transtate: 'Philippines' },
  { key: 4, value: 'バングラデシュ', transtate: 'Bangladesh' },
  { key: 5, value: 'ネパール', transtate: 'Nepal' },
  { key: 6, value: 'カンボジア', transtate: 'Cambodia' },
  { key: 7, value: 'タイ', transtate: 'Thailand' },
];

export {
  incomeOption,
  jaLevelOption,
  formEmployeeOption,
  passionOption,
  countryOptions
};
