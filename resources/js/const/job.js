const incomeOption = [
  { key: null, value: '指定なし' },
  { key: '200', value: '200' },
  { key: '250', value: '250' },
  { key: '300', value: '300' },
  { key: '350', value: '350' },
  { key: '400', value: '400' },
  { key: '450', value: '450' },
  { key: '500', value: '500' },
  { key: '550', value: '550' },
  { key: '600', value: '600' },
  { key: '650', value: '650' },
  { key: '700', value: '700' },
  { key: '750', value: '750' },
  { key: '800', value: '800' },
  { key: '850', value: '850' },
  { key: '900', value: '900' },
  { key: '950', value: '950' },
  { key: '1000', value: '1000' },
];

const jaLevelOption = [
  { key: 1, value: 'N1' },
  { key: 2, value: 'N2' },
  { key: 3, value: 'N3' },
  { key: 4, value: 'N4' },
  { key: 5, value: 'N5' },
  { key: 6, value: '問わない' },
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
  { value: null, text: '選択してください', disabled: true },
  { value: 1, text: 'ベトナム', disabled: false },
  { value: 2, text: 'ミャンマー', disabled: false },
  { value: 3, text: 'フィリピン', disabled: false },
  { value: 4, text: 'バングラデシュ', disabled: false },
  { value: 5, text: 'ネパール', disabled: false },
  { value: 6, text: 'カンボジア', disabled: false },
  { value: 7, text: 'タイ', disabled: false },
  { value: 8, text: '問わない', disabled: false },
];

const STATUS_JOB = {
  RECRUITING: 1,
  PAUSE: 2,
  OUT_OF_RECRUITMENT: 3,
};

export {
  incomeOption,
  jaLevelOption,
  formEmployeeOption,
  passionOption,
  countryOptions,
  STATUS_JOB
};
