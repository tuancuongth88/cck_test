import * as RequestApi from '../request';

export function getCompany(url) {
  return RequestApi.getOne(url);
}

export function getAllCompany(url) {
  return RequestApi.getAll(url);
}
// Register = Create
export function postCompany(url, data) {
  return RequestApi.postOne(url, data);
}
export function editCompany(url, data) {
  return RequestApi.putOne(url, data);
}

export function deleteCompany(url) {
  return RequestApi.deleteOne(url);
}

export function deleteAllCompany(url, data) {
  return RequestApi.deleteAll(url, data);
}
