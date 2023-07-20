export const deleteCookie = (name) => {
  document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};
export const setCookie = (cname, cvalue, exdays) => {
  const d = new Date();

  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);

  const expires = 'expires=' + d.toUTCString();

  document.cookie = `${cname}=${cvalue};${expires};path=/`;
};

export const getCookie = (cname) => {
  const name = cname + '=';
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');

  for (let c of ca) {
    while (c.charAt(0) === ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }

  return '';
};

export const downloadFormUrl = (url) => {
  const element = document.createElement('a');
  element.setAttribute('href', url);

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
};

export const jsonParseData = (data) => {
  // payload
  let jsonData = null;
  try {
    // Parse a JSON
    jsonData = JSON.parse(data);
  } catch (e) {
    // You can read e for more info
    // Let's assume the error is that we already have parsed the payload
    // So just return that
    jsonData = data;
  }
  return jsonData;
};
