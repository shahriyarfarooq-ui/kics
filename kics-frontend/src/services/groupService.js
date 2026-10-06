import { CACHE_TTL, api } from './api';

export const groupService = {
  list: () => api.get('/groups', { unwrap: true, cacheTtl: CACHE_TTL.LONG }),
  get: (code) => api.get(`/groups/${code}`, { unwrap: true, cacheTtl: CACHE_TTL.LONG }),
};
