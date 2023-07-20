/**
 * Function validate email
 * @param {*} email
 * @returns Boolean
 */
export function validEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

/**
 * Function validate password
 * @param {*} password
 * @returns Boolean
 */
export function validPassword(password) {
  const re = /^\S{8,16}$/;
  return re.test(password);
}

/**
 * Function validate has Empty or White Space
 * @param {*} string
 * @returns Boolean
 */
export function validEmptyOrWhiteSpace(string) {
  const re = /^\s*$/;
  return re.test(string);
}

/**
 * Function validate number > 0
 * @param {*} number
 * @returns Boolean
 */
export function validateNumberMoreThanZero(number) {
  const re = /^[1-9][0-9]*$/;
  return re.test(number);
}

/**
 * Function validate is number
 * @param {*} number
 * @returns Boolean
 */
export function validateNumber(number) {
  const re = /^\d+$/;
  return re.test(number);
}

/**
 * Function validate date -> YYYY/MM/DD
 * @param {*} value
 * @returns Boolean
 */
export function validateYYYYMMDD(value) {
  const re = /([12]\d{3}[./-](0[1-9]|1[0-2])[./-](0[1-9]|[12]\d|3[01]))/;
  return re.test(value);
}

/**
 * Function validate -> YYYY/MM
 * @param {*} value
 * @returns Boolean
 */
export function validateYYYYMM(value) {
  const re = /([12]\d{3}[./-](0[1-9]|1[0-2]))/;
  return re.test(value);
}

export function validateTwoIdDiff(oldValue, newValue) {
  if (oldValue !== newValue) {
    return true;
  }

  return false;
}
// Function validate is A-z 1-9
export function validPassAz19(password) {
  const re = /^(?=.*[A-Za-z])(?=.*[1-9]).+$/;
  return re.test(password);
}
// Chỉ được nhập và chứa ít nhất 1 chữ Hoa 1 chữ thường và 1 số
export function validPassOnlyRequireAz09(password) {
  const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]+$/;
  return pattern.test(password);
}
