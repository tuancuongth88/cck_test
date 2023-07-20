/**
 * Function format money 123456 -> 123,456
 * @param {*} number
 * @returns
 */
export function formatMoney(number) {
  if (!number) {
    return '';
  }

  number = number + '';
  return number.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
