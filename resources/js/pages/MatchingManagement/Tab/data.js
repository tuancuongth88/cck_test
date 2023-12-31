const fieldsInterview = [
  {
    key: 'selected',
    sortable: false,
    label: '',
    class: 'bg-header',
    thStyle: {
      backgroundColor: '#1D266A',
      width: '50px',
      color: '#fff',
      textAlign: 'center',
    },
    thClass: 'text-center',
    tdClass: 'text-center',
  },
  {
    key: 'id',
    sortable: true,
    label: 'ID',
    class: 'bg-header',
    thStyle: {
      backgroundColor: '#1D266A',
      width: '120px',
      color: '#fff',
      textAlign: 'center',
    },
    thClass: 'text-center',
  },
  {
    key: 'entry_id',
    sortable: true,
    label: 'エントリーID',
    class: 'bg-header',
    thStyle: {
      width: '120px',
      backgroundColor: '#1D266A',
      color: '#fff',
      textAlign: 'center',
    },
  },
  {
    key: 'interview_date',
    sortable: true,
    // label: this.$t('HEADER_REQUEST_DATE_ENTRY_MATCHING'),
    label: '面接日',
    class: 'bg-header',
    thStyle: {
      backgroundColor: '#1D266A',
      width: '120px',
      color: '#fff',
      textAlign: 'center',
    },
  },
  {
    key: 'full_name',
    sortable: true,
    // label: this.$t('HEADER_FULL_NAME_ENTRY_MATCHING'),
    label: '氏名',
    class: 'bg-header',
    thStyle: {
      backgroundColor: '#1D266A',
      width: '180px',
      color: '#fff',
      textAlign: 'center',
    },
  },
  {
    key: 'job_name',
    sortable: true,
    // label: this.$t('HEADER_JOB_LIST_ENTRY_MATCHING'),
    label: '求人名',
    class: 'bg-header',
    thStyle: {
      backgroundColor: '#1D266A',
      width: '180px',
      color: '#fff',
      textAlign: 'center',
    },
  },
  {
    key: 'selection_status',
    sortable: true,
    label: '選考 ｽﾃｰﾀｽ',
    class: 'bg-header',
    thStyle: {
      backgroundColor: '#1D266A',
      width: '140px',
      color: '#fff',
      textAlign: 'center',
    },
  },
  {
    key: 'interview_adjustment_status',
    sortable: true,
    label: '面接調整ｽﾃｰﾀｽ',
    class: 'bg-header',
    thStyle: {
      backgroundColor: '#1D266A',
      width: '140px',
      color: '#fff',
      textAlign: 'center',
    },
  },
  // This one needs a custom template, so we define the key and the label
  {
    key: 'detail',
    label: '詳細',
    class: 'bg-header',
    thStyle: {
      width: '59px',
      backgroundColor: '#1D266A',
      color: '#fff',
      textAlign: 'center',
    },
    tdClass: 'text-center',
  },
];

const itemsInterview = [
  {
    _isSelected: false,
    id: '0000001',
    entry_id: 'E00000019',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: 'オファー承認',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000002',
    entry_id: 'E00000019',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: 'オファー承認',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000003',
    entry_id: 'E00000019',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: 'オファー承認',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000004',
    entry_id: 'E00000019',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: 'オファー承認',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000005',
    entry_id: 'E00000018',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '書類通過',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000006',
    entry_id: 'E00000018',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '書類通過',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000007',
    entry_id: 'E00000018',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '書類通過',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000008',
    entry_id: 'E00000018',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '書類通過',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000009',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '一次合格',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000010',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '一次合格',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000011',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '一次合格',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000012',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '一次合格',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000013',
    entry_id: 'E00000016',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '二次合格',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000014',
    entry_id: 'E00000016',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '二次合格',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000015',
    entry_id: 'E00000015',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '二次合格',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000016',
    entry_id: 'E00000014',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '二次合格',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000017',
    entry_id: 'E00000013',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '三次合格',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000018',
    entry_id: 'E00000012',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '三次合格',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000019',
    entry_id: 'E00000011',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '三次合格',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000020',
    entry_id: 'E00000011',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '三次合格',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000021',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '四次合格',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000022',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '四次合格',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000023',
    entry_id: 'E00000016',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '四次合格',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000024',
    entry_id: 'E00000016',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '四次合格',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000025',
    entry_id: 'E00000015',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '五次合格',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000026',
    entry_id: 'E00000014',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '五次合格',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000027',
    entry_id: 'E00000013',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '五次合格',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000028',
    entry_id: 'E00000012',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '五次合格',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000029',
    entry_id: 'E00000011',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接中止',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000030',
    entry_id: 'E00000011',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接中止',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000031',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接中止',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000032',
    entry_id: 'E00000017',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接中止',
    interview_adjustment_status: '調整済',
  },
  {
    _isSelected: false,
    id: '0000033',
    entry_id: 'E00000016',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接辞退',
    interview_adjustment_status: '調整前',
  },
  {
    _isSelected: false,
    id: '0000034',
    entry_id: 'E00000016',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接辞退',
    interview_adjustment_status: '調整中',
  },
  {
    _isSelected: false,
    id: '0000035',
    entry_id: 'E00000015',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接辞退',
    interview_adjustment_status: 'URL設定中',
  },
  {
    _isSelected: false,
    id: '0000036',
    entry_id: 'E00000014',
    interview_date: '20230301',
    full_name: [{ name_vn: 'Nguyen Thi Nhi' }, { name_jp: 'ｸﾞｴﾝ ﾃｨ ﾆｰ' }],
    job_name: '電気設計エンジニア',
    selection_status: '面接辞退',
    interview_adjustment_status: '調整済',
  },
];

const fieldsModal = [
  {
    key: 'no',
    sortable: false,
    label: 'No.',
    class: 'bg-header',
    thStyle: {
      width: '15%',
      color: '#fff',
      textAlign: 'center',
    },
    thClass: 'text-center',
    tdClass: 'text-center',
  },
  {
    key: 'candidate_date',
    sortable: false,
    label: '候補日',
    class: 'bg-header',
    thStyle: {
      width: '30%',
      color: '#fff',
      textAlign: 'center',
    },
    thClass: 'text-center',
    tdClass: 'text-center',
  },
  {
    key: 'starting_time',
    sortable: false,
    label: '開始時間',
    class: 'bg-header',
    thStyle: {
      width: '35%',
      color: '#fff',
      textAlign: 'center',
    },
    thClass: 'text-center',
    tdClass: 'text-center',
  },
  {
    key: 'expected_time',
    sortable: false,
    label: '想定所要時間',
    class: 'bg-header',
    thStyle: {
      width: '20%',
      color: '#fff',
      textAlign: 'center',
    },
    thClass: 'text-center',
    tdClass: 'text-center',
  },
];

const itemsModal = [
  {
    no: '候補1',
    candidate_date: '',
    starting_time: '',
    expected_time: '',
  },
  {
    no: '候補2',
    candidate_date: '',
    starting_time: '',
    expected_time: '',
  },
  {
    no: '候補3',
    candidate_date: '',
    starting_time: '',
    expected_time: '',
  },
  {
    no: '候補4',
    candidate_date: '',
    starting_time: '',
    expected_time: '',
  },
  {
    no: '候補5',
    candidate_date: '',
    starting_time: '',
    expected_time: '',
  },
];

const typeStatusOptions = [
  { key: 1, value: '集団面接' },
  { key: 2, value: '個人面接' },
];
export { fieldsInterview, itemsInterview, itemsModal, fieldsModal, typeStatusOptions };
