import * as RequestApi from '../request';

export function getListCompany(url, data) {
  return RequestApi.getAll(url, data);
}

export function getOneCompany(url, data) {
  return RequestApi.getOne(url, data);
}
