import Cookies from 'js-cookie';

/**
 * Function get Token in Cookies
 * @returns Token
 */
export function getToken() {
  const TOKEN = Cookies.get('token');

  if (TOKEN) {
    return TOKEN;
  }

  return '';
}

export function getUserInfo(){
  const USERINFO = Cookies.get('user_info');
  if (USERINFO){
    return JSON.parse(USERINFO);
  }
  return null;
}

/**
 * Function get Exp Token in Cookies
 * @returns Exp Token
 */
export function getExpToken() {
  const EXP = Cookies.get('exp_token');

  if (EXP) {
    return EXP;
  }

  return '';
}

/**
 * Function change token to Object
 * @param {String} token
 * @returns Parse Token
 */
export function parseToken(token) {
  var base64Url = token.split('.')[1];
  var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
  var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
  }).join(''));

  return JSON.parse(jsonPayload);
}
