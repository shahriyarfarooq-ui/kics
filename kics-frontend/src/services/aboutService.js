// kics-frontend/src/services/aboutService.js - A service module for interacting with the "about" API endpoints.
import { CACHE_TTL, api } from './api';

export const aboutService = {
  // Remove /api from the path since base URL already includes /api
  list: () => api.get('/about', { cacheTtl: CACHE_TTL.LONG }),
  latest: () => api.get('/about/latest', { cacheTtl: CACHE_TTL.LONG }),
  get: (id) => api.get(`/about/${id}`, { cacheTtl: CACHE_TTL.LONG }),
};