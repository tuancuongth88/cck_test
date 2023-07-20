import CONST_DEGITACHO from '@/const/degitacho';

/**
 * Function get object array in object response of api master manager
 * @param {Object} data
 * @param {Array} listKey
 * @returns Object
 */
export function getListByKey(data, listKey = []) {
  const result = {};

  if (!data) {
    return result;
  }

  // If list listKey equal = 0 -> Return object all key
  if (listKey.length === 0) {
    for (let itemKey = 0; itemKey < data.length; itemKey++) {
      const CURRENT_KEY = data[itemKey][CONST_DEGITACHO.ITEM_KEY];

      result[CURRENT_KEY] = data[itemKey][CONST_DEGITACHO.MASTER_MANAGEMENT_OPTION];
    }

    return result;
  }

  // Else Return object has key in listKey
  for (let itemKey = 0; itemKey < listKey.length; itemKey++) {
    const CURRENT_KEY = listKey[itemKey];

    const findObj = data.find((obj) => obj[CONST_DEGITACHO.ITEM_KEY] === CURRENT_KEY);

    if (findObj) {
      result[CURRENT_KEY] = findObj[CONST_DEGITACHO.MASTER_MANAGEMENT_OPTION];
    }
  }

  return result;
}

