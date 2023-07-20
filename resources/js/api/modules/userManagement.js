import * as RequestApi from '../request';

export function getUserManagement(url) {
  return RequestApi.getOne(url);
}

export function getAllUserManagement(url) {
  return RequestApi.getAll(url);
}

export function postUserManagement(url, data) {
  return RequestApi.postOne(url, data);
}
export function editUserManagement(url, data) {
  return RequestApi.putOne(url, data);
}

export function deleteUserManagement(url) {
  return RequestApi.deleteOne(url);
}

export function deleteAllUserManagement(url, data) {
  return RequestApi.deleteAll(url, data);
}
