import { validateYYYYMMDD } from './validate';

/**
 * Function get date now
 * @returns String | Date now 'YYYY/MM/DD"
 */
export function getDateNow() {
  const DATE = new Date();

  const YEAR_NOW = DATE.getFullYear();
  const MONTH_NOW = formatMonth(DATE.getMonth() + 1);
  const DATE_NOW = formatDate(DATE.getDate());

  return joinYMD(YEAR_NOW, MONTH_NOW, DATE_NOW);
}

export function getYMNow() {
  const DATE = new Date();

  const YEAR_NOW = DATE.getFullYear();
  const MONTH_NOW = formatMonth(DATE.getMonth() + 1);

  return `${YEAR_NOW}/${MONTH_NOW}`;
}

export function isCheckYMNow(date) {
  const DATE_NOW = `${getYMNow()}/01`;
  const DATE = `${date}/01`;

  if (!(validateYYYYMMDD(DATE_NOW) && validateYYYYMMDD(DATE))) {
    return false;
  }

  if ((new Date(DATE) >= new Date(DATE_NOW))) {
    return true;
  }

  return false;
}

export function isCheckYMDNow(date) {
  const DATE_NOW = formatYMD(`${getDateNow()}`);
  const DATE = formatYMD(`${date}`);

  if (!(validateYYYYMMDD(DATE_NOW) && validateYYYYMMDD(DATE))) {
    return false;
  }

  if ((new Date(DATE) >= new Date(DATE_NOW))) {
    return true;
  }

  return false;
}

/**
 * Function format date 2 digit
 * @param {Number} d
 * @returns String | 01, 10
 */
export function formatDate(d) {
  if (!d) {
    return '';
  }

  return d < 10 ? '0' + d : d;
}

/**
 * Function format month 2 digit
 * @param {Number} m
 * @returns String | 01, 10
 */
export function formatMonth(m) {
  if (!m) {
    return '';
  }

  return m < 10 ? '0' + m : m;
}

/**
 * Function join Year, Month, Date
 * @param {Number} y
 * @param {Number} m
 * @param {Number} d
 * @returns String | 2000/10/13
 */
export function joinYMD(y, m, d) {
  if (!y && !m && !d) {
    return '';
  }

  return `${y}/${m}/${d}`;
}

/**
 * Function change format Year-Month-Date -> Year/Month/Date
 * @param {Date} value
 * @returns String | 2000/10/13
 */
export function formatYMD(value) {
  if (!value) {
    return '';
  }

  return value.replace(/(\d{4})-(\d{1,2})-(\d{1,2})/, function(match, y, m, d) {
    return `${y}/${m}/${d}`;
  });
}

/**
 * Function change format Year/Month/Date-> Year-Month-Date
 * @param {*} value
 * @returns String | 2000-10-13
 */
export function formatBindingYMD(value) {
  if (!value) {
    return '';
  }

  return value.replace(/(\d{4})[/](\d{1,2})[/](\d{1,2})/, function(match, y, m, d) {
    return `${y}-${m}-${d}`;
  });
}

/**
 * Function change format Year/Month-> Year-Month
 * @param {*} value
 * @returns String | 2000-10
 */
export function formatBindingYM(value) {
  if (!value) {
    return '';
  }

  return value.replace(/(\d{4})[/](\d{1,2})/, function(match, y, m) {
    return `${y}-${m}`;
  });
}

/**
 * Function change 2000/10 -> to Object
 * @param {String} value
 * @returns Object | { year: 2000, month: 10 }
 */
export function getObjectYM(value) {
  if (!value) {
    return '';
  }

  const SPLIT = value.split('/');

  if (SPLIT.length === 2) {
    return {
      year: SPLIT[0] || '',
      month: SPLIT[1] || '',
    };
  }

  return {
    year: '',
    month: '',
  };
}

export function isCheckDateDiff(dateCurrent, dateNew) {
  if (!validateYYYYMMDD(dateNew)) {
    return false;
  }

  if (!dateCurrent && validateYYYYMMDD(dateNew)) {
    return true;
  }

  dateCurrent = formatYMD(dateCurrent);
  dateNew = formatYMD(dateNew);

  if (dateCurrent !== dateNew) {
    return true;
  }

  return false;
}
