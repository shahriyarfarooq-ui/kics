import { CACHE_TTL, api } from './api';

export const newsService = {
  list: (params = {}) => api.get('/news', { params, cacheTtl: CACHE_TTL.SHORT }),
  get: (id) => api.get(`/news/${id}`, { cacheTtl: CACHE_TTL.MEDIUM }),
};
