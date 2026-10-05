import { CACHE_TTL, api } from './api';

export const visionService = {
  list: () => api.get('/api/vision', { cacheTtl: CACHE_TTL.LONG }),
  latest: () => api.get('/api/vision/latest', { cacheTtl: CACHE_TTL.LONG }),
  get: (id) => api.get(`/api/vision/${id}`, { cacheTtl: CACHE_TTL.LONG }),
};
