// Detail & Edit
// Tab 1
const basic_information_init = {
  fullName: 'Nguyen Thi Nhi',
  full_name_furigana: 'ｸﾞｴﾝ　ﾃｨｰ ﾆｰ',
  gender_id: { id: null, content: '' },
  date_of_birth_year_id: null,
  date_of_birth_month_id: null,
  date_of_birth_day_id: null,
  work_form_id: { id: null, content: '' },
  japanese_level_id: { id: null, content: '' },
  work_form_part_time_input: '',
};
const basic_information_init1 = {
  fullName: 'Nguyen Thi Nhi',
  full_name_furigana: 'ｸﾞｴﾝ　ﾃｨｰ ﾆｰ',
  gender_id: { id: 1, content: '男' },
  date_of_birth_year_id: 1,
  date_of_birth_month_id: 2,
  date_of_birth_day_id: 3,
  work_form_id: { id: 1, content: '正社員' },
  japanese_level_id: { id: 2, content: 'N2' },
  work_form_part_time_input: 'work_form_part_time_input',
};

// Tab 2 学歴・職歴 - education.work history
const textarear = 'ABC株式会社に入社後、約3年間、フィンテック関連システムの開発に従事。クライアントに常駐し、要件定義から設計、テスト、保守・運用まで、一貫して担当してきました。2019年からは新規案件のプロジェクトマネジメント業務にも携わっており、現在は20名規模のアプリ開発プロジェクトのリーダーを務めています。品質担保、納期管理の適切さがクライアントに評価され、同社の他案件のシステム開発も受注見込みとなっています。';
const educationWorkHistoryInit = {
  // 1 卒業年月 Graduation date
  final_education_timing_year_id: 2021,
  final_education_timing_month_id: 7,
  final_education_classification_id: { id: null, content: '卒業' },
  final_education_degree_id: { id: null, content: '学士' },
  major_classification_id: { id: null, content: '言語学' },
  middle_classification_id: { id: null, content: '日本語' },
  // 2 主な職務経歴① - Main job career1
  main_job_career_1_year_from_id: 2021,
  main_job_career_1_month_from_id: 9,
  main_job_career_1_year_to_id: 2023,
  main_job_career_1_month_to_id: 1,
  main_job_career_1_time_now: null, // check box

  main_job_career_1_department_id: { id: null, content: '営業部' },
  main_job_career_1_job_title_id: { id: null, content: '会社員' },
  main_job_career_1_textarea: `${textarear}`,
  // 3 主な職務経歴② - Main job career2
  main_job_career_2_year_from_id: 2021,
  main_job_career_2_month_from_id: 9,
  main_job_career_2_year_to_id: 2023,
  main_job_career_2_month_to_id: 1,
  main_job_career_2_time_now: null, // check box

  main_job_career_2_department_id: { id: null, content: '営業部' },
  main_job_career_2_job_title_id: { id: null, content: '会社員' },
  main_job_career_2_textarea: `${textarear}`,
  // 4 主な職務経歴② - Main job career3
  main_job_career_3_year_from_id: 2021,
  main_job_career_3_month_from_id: 9,
  main_job_career_3_year_to_id: 2023,
  main_job_career_3_month_to_id: 1,
  main_job_career_3_time_now: null, // check box

  main_job_career_3_department_id: { id: null, content: '営業部' },
  main_job_career_3_job_title_id: { id: null, content: '会社員' },
  main_job_career_3_textarea: `${textarear}`,
};

// Tab 3 自己PR・備考 - personal PR.remarks
const personal_pr_remarks_init = {
  personal_pr_special_textarea: '私は日本で働くことが好きです。わからないことは勉強して学ぶことができます。',
  remarks_textarea: '日本長期滞在希望',
};

// Tab 4 Contact
const contact_init = {
  // 1 連絡先電話番号 - Telephone number
  telephone_phone_number_id: 1,
  telephone_phone_number_input: '+84 0987654321',
  // 2 携帯電話番号 - Mobile phone number
  mobile_phone_number_id: 2,
  mobile_phone_number_input: '+84 0312345678',
  // 3 メールアドレス - Mail address
  mail_address_input: 'nguyen.thinhi@gmail.com',
  // 4 現住所 - Address
  address_city_input: 'Hanoi',
  address_district_input: 'Ba Dinh',
  address_number_input: '266 Lieu Giay Doi Can ',
  address_other_input: 'address_other_input',
  // 5 出身地住所 Hometown address
  hometown_address_city_input: 'hometown_address_city_input',
  hometown_address_district_input: 'hometown_address_district_input',
  hometown_address_number_input: 'hometown_address_number_input',
  hometown_address_other_input: 'hometown_address_other_input',
};
export {
  basic_information_init,
  basic_information_init1,
  educationWorkHistoryInit,
  personal_pr_remarks_init,
  contact_init
};
