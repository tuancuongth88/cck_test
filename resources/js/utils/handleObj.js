/**
 * Function clean Object if object has value  is [undefinded, ''] -> delete key
 * @param {Object} obj
 * @returns
 */
export function cleanObj(obj) {
  if (!obj) {
    return {};
  }

  for (const propName in obj) {
    if (obj[propName] === undefined || obj[propName] === '') {
      delete obj[propName];
    }
  }

  return obj;
}

/**
 * Function change key object undefined or '' to -> null
 * @param {*} obj
 * @returns object
 */
export function changeSpace2Null(obj) {
  if (!obj) {
    return {};
  }

  for (const propName in obj) {
    if (obj[propName] === undefined || obj[propName] === '') {
      obj[propName] = null;
    }
  }

  return obj;
}
