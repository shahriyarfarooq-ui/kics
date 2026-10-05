//src>services>staffService.js
// import { CACHE_TTL, api } from './api';

// export const staffService = {
//   list: (params = {}) => api.get('/api/staff', { params, cacheTtl: CACHE_TTL.MEDIUM }),
//   get: (id) => api.get(`/staff/${id}`, { cacheTtl: CACHE_TTL.MEDIUM }),
// };

//src>services>staffService.js
import { CACHE_TTL, api } from './api';

export const staffService = {
  list: (params = {}) => api.get('/staff', { params, cacheTtl: CACHE_TTL.MEDIUM }),
  get: (id) => api.get(`/staff/${id}`, { cacheTtl: CACHE_TTL.MEDIUM }),
};