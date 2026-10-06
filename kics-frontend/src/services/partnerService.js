import { CACHE_TTL, api } from './api';

export const partnerService = {
  list: (params = {}) => api.get('/api/partners', { params, unwrap: true, cacheTtl: CACHE_TTL.LONG }),
  get: (id) => api.get(`/api/partners/${id}`, { unwrap: true, cacheTtl: CACHE_TTL.LONG }),
};
