// State Status Modal Select Jobs To Offer
const status_select_jobs_to_offer = false;
// Common
// const provincesr_option = [
//   { value: null, text: '選択してください', translate: 'please select' }, // Không xác định
// ];

// Static
const gender_option = [
  { value: { id: null, content: '' }, text: '選択してください', translate: 'please select',
  }, // Không xác định
  { value: { id: 1, content: '男' }, text: '男', translate: 'male' },
  { value: { id: 2, content: '女' }, text: '女', translate: 'female' },
];

const gender_option_search = [
  { value: { id: 1, content: '男性' }, text: '男性', translate: 'male' },
  { value: { id: 2, content: '女性' }, text: '女性', translate: 'female' },
];

const renderAge = () => {
  // = 2010 - 1960
  let year_arr = [];
  const year_from = 13;
  const year_to = 63;
  const number_length = Math.abs(parseInt(year_from - year_to)) + 1; // + 1 Thêm số cuối mảng là year_to
  year_arr = Array.from({ length: number_length }, (_, i) => year_from + i);
  return year_arr;
};

const renderYears = () => {
  let year_arr = [];
  const year_from = 1900;
  const year_to = 2050;
  const number_length = Math.abs(parseInt(year_from - year_to)) + 1; // + 1 Thêm số cuối mảng là year_to
  year_arr = Array.from({ length: number_length }, (_, i) => year_from + i);
  return year_arr;
};

const renderYearsEducationTiming = () => {
  let year_arr = [];
  const year_from = 1960;
  const year_to = 2050;
  const number_length = Math.abs(parseInt(year_from - year_to)) + 1; // + 1 Thêm số cuối mảng là year_to
  year_arr = Array.from({ length: number_length }, (_, i) => year_from + i);
  return year_arr;
};

const renderMonth = () => {
  let month_arr = [];
  const month_from = 1;
  const month_to = 12;
  const number_length = Math.abs(parseInt(month_from - month_to)) + 1; // + 1 Thêm số cuối mảng là month_to
  month_arr = Array.from({ length: number_length }, (_, i) => month_from + i);
  return month_arr;
};

const renderDay = () => {
  let day_arr = [];
  const day_from = 2010;
  const day_to = 1960;
  const number_length = Math.abs(parseInt(day_from - day_to)) + 1; // + 1 Thêm số cuối mảng là day_to
  day_arr = Array.from({ length: number_length }, (_, i) => day_from + i);
  return day_arr;
};

const renderNumAge = () => {
  let day_arr = [];
  const day_from = 1;
  const day_to = 31;
  const number_length = Math.abs(parseInt(day_from - day_to)) + 1; // + 1 Thêm số cuối mảng là day_to
  day_arr = Array.from({ length: number_length }, (_, i) => day_from + i);
  return day_arr;
};

const work_form_option = [
  {
    value: { id: null, content: '' },
    text: '選択してください',
    translate: 'please select',
  },
  {
    value: { id: 1, content: '正社員' },
    text: '正社員',
    translate: 'full-time employee',
  },
  {
    value: { id: 2, content: '契約社員' },
    text: '契約社員',
    translate: 'contract employee',
  },
  {
    value: { id: 3, content: '派遣社員' },
    text: '派遣社員',
    translate: 'Temporary staff',
  },
  { value: { id: 4, content: 'その他' }, text: 'その他', translate: 'others' },
];

const language_requirement_options = [
  { value: { id: null, content: '' }, text: '選択してください', translate: 'please select' },
  { value: { id: 1, content: 'N1' }, text: 'N1', translate: 'N1' },
  { value: { id: 2, content: 'N2' }, text: 'N2', translate: 'N2' },
  { value: { id: 3, content: 'N3' }, text: 'N3', translate: 'N3' },
  { value: { id: 4, content: 'N4' }, text: 'N4', translate: 'N4' },
  { value: { id: 5, content: 'N5' }, text: 'N5', translate: 'N5' },
  { value: { id: 6, content: '資格なし' }, text: '資格なし', translate: 'no qualification' },
];

const final_education_classification_options = [
  { value: { id: null, content: '' }, text: '選択してください', translate: 'please select' },
  { value: { id: 1, content: '卒業' }, text: '卒業', translate: 'graduation' },
  { value: { id: 2, content: '中退' }, text: '中退', translate: 'dropout' },
  { value: { id: 3, content: '卒業見込み' }, text: '卒業見込み', translate: 'expected to graduate' },
];

const final_education_degree_options = [
  { value: { id: null, content: '' }, text: '選択してください', translate: 'please select' },
  { value: { id: 1, content: '博士' }, text: '博士', translate: 'Doctor' },
  { value: { id: 2, content: '修士' }, text: '修士', translate: 'master s degree' },
  { value: { id: 3, content: '学士' }, text: '学士', translate: 'expected to graduate' },
  { value: { id: 3, content: '短期大学卒業' }, text: '短期大学卒業', translate: 'graduated from junior college' },
  { value: { id: 3, content: '専門学校卒業' }, text: '専門学校卒業', translate: 'graduated from vocational school' },
  { value: { id: 3, content: '高校卒業' }, text: '高校卒業', translate: 'high school graduation' },
];

const phone_number_options_common = [
  { value: { id: null, content: '' }, text: '選択してください', translate: '' },
  { value: { id: 1, content: '+84' }, text: '+84', translate: '+84' },
  { value: { id: 2, content: '+81' }, text: '+81', translate: '+81' },
];

// API
const provincesr_option = [
  { value: { id: 1, content: '男' }, text: '北海道' },
  { value: { id: 2, content: '男' }, text: '青森県' },
  { value: { id: 3, content: '男' }, text: '岩手県' },
  { value: { id: 4, content: '男' }, text: '宮城県' },
  { value: { id: 5, content: '男' }, text: '秋田県' },
  { value: { id: 6, content: '男' }, text: '山形県' },
  { value: { id: 7, content: '男' }, text: '福島県' },
  { value: { id: 8, content: '男' }, text: '茨城県' },
  { value: { id: 9, content: '男' }, text: '栃木県' },
  { value: { id: 10, content: '男' }, text: '群馬県' },
  { value: { id: 11, content: '男' }, text: '埼玉県' },
  { value: { id: 12, content: '男' }, text: '千葉県' },
  { value: { id: 13, content: '男' }, text: '東京都' },
  { value: { id: 14, content: '男' }, text: '神奈川県' },
  { value: { id: 15, content: '男' }, text: '新潟県' },
  { value: { id: 16, content: '男' }, text: '富山県' },
  { value: { id: 17, content: '男' }, text: '石川県' },
  { value: { id: 18, content: '男' }, text: '福井県' },
  { value: { id: 19, content: '男' }, text: '山梨県' },
  { value: { id: 20, content: '男' }, text: '長野県' },
  { value: { id: 21, content: '男' }, text: '岐阜県' },
  { value: { id: 22, content: '男' }, text: '静岡県' },
  { value: { id: 23, content: '男' }, text: '愛知県' },
  { value: { id: 24, content: '男' }, text: '三重県' },
  { value: { id: 25, content: '男' }, text: '滋賀県' },
  { value: { id: 27, content: '男' }, text: '京都府' },
  { value: { id: 28, content: '男' }, text: '大阪府' },
  { value: { id: 29, content: '男' }, text: '兵庫県' },
  { value: { id: 30, content: '男' }, text: '奈良県' },
  { value: { id: 31, content: '男' }, text: '和歌山県' },
  { value: { id: 32, content: '男' }, text: '鳥取県' },
  { value: { id: 33, content: '男' }, text: '島根県' },
  { value: { id: 34, content: '男' }, text: '岡山県' },
  { value: { id: 35, content: '男' }, text: '広島県' },
  { value: { id: 36, content: '男' }, text: '山口県' },
  { value: { id: 37, content: '男' }, text: '徳島県' },
  { value: { id: 38, content: '男' }, text: '香川県' },
  { value: { id: 39, content: '男' }, text: '愛媛県' },
  { value: { id: 40, content: '男' }, text: '高知県' },
  { value: { id: 41, content: '男' }, text: '福岡県' },
  { value: { id: 42, content: '男' }, text: '佐賀県' },
  { value: { id: 43, content: '男' }, text: '長崎県' },
  { value: { id: 44, content: '男' }, text: '熊本県' },
  { value: { id: 45, content: '男' }, text: '大分県' },
  { value: { id: 46, content: '男' }, text: '宮崎県' },
  { value: { id: 47, content: '男' }, text: '鹿児島県' },
  { value: { id: 48, content: '男' }, text: '沖縄県' },
];
const deparment_option_api = [
  { value: { id: null, content: '' }, text: '選択してください', translate: 'please select' },
  { value: { id: 1, content: 'Deparment 1' }, text: 'Deparment 1', translate: 'Selection department (major classification)' },
  { value: { id: 2, content: 'Deparment 2' }, text: 'Deparment 2', translate: 'Selection department (middle classification)' },
  { value: { id: 3, content: 'Deparment 3' }, text: 'Deparment 3', translate: 'Selection department (middle classification)' },
  { value: { id: 4, content: 'Deparment 4' }, text: 'Deparment 4', translate: 'Selection department (middle classification)' },
  { value: { id: 5, content: 'Deparment 5' }, text: 'Deparment 5', translate: 'Selection department (middle classification)' },
  { value: { id: 6, content: 'Deparment 6' }, text: 'Deparment 6', translate: 'Selection department (middle classification)' },
];

const job_title_agriculture_forestry_fishery_api = [
  { value: 1, text: '耕種農業 ', translate: 'Crop farming' },
  { value: 1, text: '畜産農業', translate: 'Livestock farming' },
];

const job_title_manufacturingy_api = [
  { value: 1, text: '土木工事業', translate: 'Civil engineering business' },
  {
    value: 1,
    text: '舗装工事業',
    translate: 'Pavement construction business',
  },
];

const major_classification_options_api = [
  { value: { id: null, content: '' }, text: '選択してください', translate: 'please select' },
  {
    value: { id: 1, content: '農業、林業、漁業' }, text: '農業、林業、漁業 ',
    translate: 'Agriculture, Forestry, Fishery',
    middle: [
      { value: 1, text: '卒業', translate: 'graduation' },
      { value: 2, text: '育林業', translate: 'Forestry expansion' },
      { value: 3, text: '素材生産業', translate: 'Material production industry' },
      { value: 4, text: '海面漁業（海での漁師）', translate: 'Sea fishery(fishermen at sea)' },
      { value: 5, text: '内水面漁業（淡水での漁師）', translate: 'Inland fishery(Fishermen at ' },
    ],
  },
  {
    value: { id: 2, content: '建設業' },
    text: '建設業 ',
    translate: 'Construction industry',
    middle: [
      { value: 1, text: '卒業2', translate: 'graduation' },
      { value: 2, text: '育林業2', translate: 'Forestry expansion' },
      { value: 3, text: '素材生産業2', translate: 'Material production industry' },
      { value: 4, text: '海面漁業（海での漁師）2', translate: 'Sea fishery(fishermen at sea)' },
      { value: 5, text: '内水面漁業（淡水での漁師）2', translate: 'Inland fishery(Fishermen at ' },
    ],
  },
  {
    value: { id: 3, content: '製造業' },
    text: '製造業',
    translate: 'Manufacturing industry',
    middle: [
      { value: 1, text: '卒業3', translate: 'graduation' },
      { value: 2, text: '育林業3', translate: 'Forestry expansion' },
      { value: 3, text: '素材生産業3', translate: 'Material production industry' },
      { value: 4, text: '海面漁業（海での漁師）3', translate: 'Sea fishery(fishermen at sea)' },
      { value: 5, text: '内水面漁業（淡水での漁師）3', translate: 'Inland fishery(Fishermen at ' },
    ],
  },
];
// Test tạm
const middle_classification_options_api = [
  { value: { id: null, content: '' }, text: '選択してください', translate: 'please select' },
  { value: { id: 1, content: '畜産農業' }, text: '畜産農業', translate: ' Livestock farming' },
  { value: { id: 2, content: '育林業' }, text: '育林業', translate: ' Forestry expansion' },
  { value: { id: 3, content: '素材生産業' }, text: '素材生産業 ', translate: 'Material production industry' },
  { value: { id: 4, content: 'その他林業' }, text: 'その他林業 ', translate: 'Other forestry business' },
  { value: { id: 5, content: '海面漁業（海での漁師）' }, text: '海面漁業（海での漁師）', translate: 'Sea fishery(fishermen at sea)' },
  { value: { id: 6, content: '内水面漁業（淡水での漁師）' }, text: '内水面漁業（淡水での漁師）', translate: 'Inland fishery(Fishermen at' },
];

// Validate
const format2characters = (e) => {
  return String(e).substring(0, 2);
};
const format20characters = (e) => {
  console.log('format20characters', e);
  return String(e).substring(0, 20);
};
const format50characters = (e) => {
  return String(e).substring(0, 50);
};
const format2000characters = (e) => {
  return String(e).substring(0, 2000);
};

export {
  status_select_jobs_to_offer,
  deparment_option_api,
  job_title_agriculture_forestry_fishery_api,
  job_title_manufacturingy_api,
  provincesr_option,
  gender_option,
  gender_option_search,
  renderAge,
  renderYears,
  renderYearsEducationTiming,
  renderMonth,
  renderDay,
  renderNumAge,
  work_form_option,
  language_requirement_options,
  final_education_classification_options,
  final_education_degree_options,
  phone_number_options_common,
  major_classification_options_api,
  middle_classification_options_api,
  format2characters,
  format20characters,
  format50characters,
  format2000characters
};
