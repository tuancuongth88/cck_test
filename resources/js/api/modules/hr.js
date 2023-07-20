import * as RequestApi from '../request';

export function postHr(url, data) {
  return RequestApi.postOne(url, data);
}

export function getOneHr(url, data) {
  return RequestApi.getOne(url, data);
}

export function postOneHr(url, data) {
  return RequestApi.postOne(url, data);
}

export function getHr(url) {
  return RequestApi.getOne(url);
}

export function getAllHr(url) {
  return RequestApi.getAll(url);
}

export function editHr(url, data) {
  return RequestApi.putOne(url, data);
}

export function deleteHr(url) {
  return RequestApi.deleteOne(url);
}

export function deleteAllHr(url, data) {
  return RequestApi.deleteAll(url, data);
}

export function updateHrInformation(url, form_data) {
  return RequestApi.putOne(url, form_data);
}

export function uploadSingleFile(url, form_data) {
  return RequestApi.postOne(url, form_data);
}

export function getClassificationListDepartment(url, data) {
  return RequestApi.getAll(url, data);
}

export function getClassificationListOccupation(url, data) {
  return RequestApi.getAll(url, data);
}
