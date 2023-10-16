import store from '../store';
export const pushParamOrQueryToRouter = async(routerName, queries) => {
  await store.dispatch('routerParam/setRouterWithParamQuery', {
    routerName,
    queries,
  });
};
