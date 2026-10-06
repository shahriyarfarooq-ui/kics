import { CACHE_TTL, api } from './api';

export const erpService = {
  listDepartments: () => api.get('/erp/departments', { cacheTtl: CACHE_TTL.MEDIUM }),
  listDepartmentProjects: (id) => api.get(`/erp/departments/${id}/projects`, { cacheTtl: CACHE_TTL.MEDIUM }),
  listProjects: (params = {}) => api.get('/erp/projects', { params, cacheTtl: CACHE_TTL.MEDIUM }),
  listEmployees: () => api.get('/erp/employees', { cacheTtl: CACHE_TTL.MEDIUM }),
};
