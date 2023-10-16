const account_classification_option = [
  { key: 1, value: '自社PF', translate: 'Own platform' },
  { key: 2, value: '送り出し機関', translate: 'Sending agency' },
  { key: 3, value: '派遣会社', translate: 'Dispatch agency' },
  { key: 4, value: '学校', translate: 'School' },
  { key: 5, value: '企業', translate: 'Company' },
];

const country_option = [
  { key: 1, value: 'ベトナム', translate: 'Vietnam' },
  { key: 2, value: 'ミャンマー', translate: 'Myanmar' },
  { key: 3, value: 'フィリピン', translate: 'Philippines' },
  { key: 4, value: 'バングラデシュ', translate: 'Bangladesh' },
  { key: 5, value: 'ネパール', translate: 'Nepal' },
  { key: 6, value: 'カンボジア', translate: 'Cambodia' },
  { key: 7, value: 'タイ', translate: 'Thailand' },
];

const jaLevelOption = [
  { key: 1, value: 'N1' },
  { key: 2, value: 'N2' },
  { key: 3, value: 'N3' },
  { key: 4, value: 'N4' },
  { key: 5, value: 'N5' },
  { key: 6, value: '資格なし' },
];

const finalEductionClassification = [
  { key: 1, value: '卒業', translate: 'graduation' },
  { key: 2, value: '中退', translate: 'dropout' },
  { key: 3, value: '卒業見込み', translate: 'expected to graduate' },
];

const finalEductionDegree = [
  {
    key: 1,
    value: '大学卒業以上',
    translate: 'University graduation or more',
  },
  {
    key: 2,
    value: '大学卒業以外',
    translate: 'Aside from University graduation',
  },
];

const workFormOptions = [
  { key: 1, value: '正社員', translate: 'full time employee' },
  { key: 2, value: '契約社員', translate: 'Contract employee' },
  { key: 3, value: '派遣社員', translate: 'Temporary employee' },
  { key: 4, value: 'その他', translate: 'Others' },
];

const workFormParttimeOptions = [
  {
    key: 1,
    value: '週28時間以下',
    translate: '週28時間以下',
  },
];

const finalEductionYear = [
  { key: 2000, value: '2000' },
  { key: 2001, value: '2001' },
  { key: 2003, value: '2003' },
  { key: 2004, value: '2004' },
  { key: 2005, value: '2005' },
  { key: 2006, value: '2006' },
  { key: 2007, value: '2007' },
  { key: 2008, value: '2008' },
  { key: 2009, value: '2009' },
  { key: 2010, value: '2010' },
];

const finalEductionMonth = [
  // { key: null, text: '', translate: 'please select' },
  { key: '01', value: '1' },
  { key: '02', value: '2' },
  { key: '03', value: '3' },
  { key: '04', value: '4' },
  { key: '05', value: '5' },
  { key: '06', value: '6' },
  { key: '07', value: '7' },
  { key: '08', value: '8' },
  { key: '09', value: '9' },
  { key: '10', value: '10' },
  { key: '11', value: '11' },
  { key: '12', value: '12' },
];

const genderOptions = [
  { key: 1, value: '男性', translate: 'male' },
  { key: 2, value: '女性', translate: 'female' },
];

const renderAge = () => {
  // = 2010 - 1960
  // let year_arr = [];
  // const year_from = 13;
  // const year_to = 63;
  // const number_length = Math.abs(parseInt(year_from - year_to)) + 1; // + 1 Thêm số cuối mảng là year_to
  // year_arr = Array.from({ length: number_length }, (_, i) => year_from + i);
  // return year_arr;

  const age_list = [{ value: null, text: '' }];
  const age_from = 13;
  const age_to = 63;
  for (let i = age_from; i <= age_to; i++) {
    age_list.push(i);
  }
  return age_list;
};

const renderYearsEducationTiming = () => {
  let year_arr = [];
  const year_from = 1960;
  const year_to = 2050;
  const number_length = Math.abs(parseInt(year_from - year_to)) + 1; // + 1 Thêm số cuối mảng là year_to
  year_arr = Array.from({ length: number_length }, (_, i) => {
    return {
      key: year_from + i,
      value: year_from + i,
    };
  });
  return year_arr;
};

export {
  account_classification_option,
  country_option,
  jaLevelOption,
  finalEductionClassification,
  finalEductionDegree,
  workFormOptions,
  workFormParttimeOptions,
  finalEductionYear,
  finalEductionMonth,
  genderOptions,
  renderAge,
  renderYearsEducationTiming
};
