import { CACHE_TTL, api } from './api';

export const careerService = {
  list: (params = {}) => api.get('/api/careers', { params, cacheTtl: CACHE_TTL.SHORT }),
  get: (id) => api.get(`/api/careers/${id}`, { cacheTtl: CACHE_TTL.SHORT }),
};
