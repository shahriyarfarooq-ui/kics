import { api } from './api';

export const announcementPopupService = {
  getActive: () => api.get('/api/announcement-popup', { cache: false }),
};
