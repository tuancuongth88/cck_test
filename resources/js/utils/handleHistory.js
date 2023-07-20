import { getDateNow } from './handleDate';

const VALUE = 'value';
const APPLICATION_START_DATE = 'application_start_date';

// Case
const HISTORY_VEHICLE_ID = 'history_vehicle_id';

/**
 * Function get history nearest with date now
 * @param {Array} history
 * @returns String
 */
export function getHistoryNearest(history, type = '') {
  if (!history) {
    return '';
  }

  const DATE_NOW = new Date(getDateNow());

  history = history.filter((item) => {
    const ITEM_DATE = new Date(item[APPLICATION_START_DATE]);

    return (ITEM_DATE > DATE_NOW);
  });

  history.sort((a, b) => {
    return (new Date(a[APPLICATION_START_DATE]) - new Date(b[APPLICATION_START_DATE]));
  });

  if (history.length > 0) {
    switch (type) {
      case HISTORY_VEHICLE_ID: {
        return `${history[0][APPLICATION_START_DATE]} : ${getAfterPain(history[0][VALUE])}`;
      }

      default: {
        return `${history[0][APPLICATION_START_DATE]} : ${history[0][VALUE]}`;
      }
    }
  }

  return ``;
}

export function getIdVehicle(text) {
  const regex = /[^,]*/;

  const result = text.match(regex);

  if (result.length > 0) {
    return text.match(regex)[0];
  } else {
    return '';
  }
}

export function getAfterPain(text) {
  const regex = /[^,]*$/;

  const result = text.match(regex);

  if (result.length > 0) {
    return text.match(regex)[0];
  } else {
    return '';
  }
}

export function getObjById(id, array) {
  id = parseInt(id);

  const obj = array.find((item) => item.id === id);

  return obj.option_value;
}

