import { CACHE_TTL, api } from './api';

export const publicationService = {
  list: (params = {}) => api.get('/api/publications', { params, cacheTtl: CACHE_TTL.MEDIUM }),
  get: (id) => api.get(`/api/publications/${id}`, { cacheTtl: CACHE_TTL.MEDIUM }),
};
