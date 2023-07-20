export const validateCode = (code) => {
  const reGex = /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/;
  return reGex.test(code);
};

export const validateFullWidthCharacter = (fullWidthNum) => {
  const shiftCharCode = (Δ) => (c) => String.fromCharCode(c.charCodeAt(0) + Δ);
  const halfWidth = fullWidthNum.replace(/[！-～]/g, shiftCharCode(-0xFEE0));
  return halfWidth;
};

export const validateVehicleNumber = (vehicle) => {
  const regex = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|[a-zA-Z]+\d{1,4}$/;
  return regex.test(vehicle);
};

export const validateVehiclePlateTemp = (vehicle) => {
  const regex = /[\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|[a-zA-Z]$/;
  return regex.test(vehicle);
};
