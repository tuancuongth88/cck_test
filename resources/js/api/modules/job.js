import * as RequestApi from '../request';

export function postOneJob(url, data) {
  return RequestApi.postOne(url, data);
}

export function putOneJob(url, data) {
  return RequestApi.putOne(url, data);
}

export function getOneJob(url, data) {
  return RequestApi.getOne(url, data);
}

export function getListJob(url, data) {
  return RequestApi.getAll(url, data);
}

export function removeJob(url, data) {
  return RequestApi.deleteAll(url, data);
}

export function postEntry(url, data) {
  return RequestApi.postOne(url, data);
}
