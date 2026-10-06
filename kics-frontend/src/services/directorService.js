// kics-frontend/src/services/directorService.js - A service module for interacting with the "director message" API endpoints.
import { CACHE_TTL, api } from './api';

export const directorService = {
  // Remove /api from the path since base URL already includes /api
  list: () => api.get('/director_message', { cacheTtl: CACHE_TTL.LONG }),
  latest: () => api.get('/director_message/latest', { cacheTtl: CACHE_TTL.LONG }),
  get: (id) => api.get(`/director_message/${id}`, { cacheTtl: CACHE_TTL.LONG }),
};