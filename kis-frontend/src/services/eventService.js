import { api, unwrapData } from './api';
import { buildImageUrl } from '../utils/contentMappers';

export const eventService = {
  // Get all events with pagination and filters
  list: async (params = {}) => {
    const payload = await api.get('/api/events', { params });
    const data = unwrapData(payload);
    
    // Process images for each event
    if (Array.isArray(data)) {
      return data.map(event => ({
        ...event,
        featured_image: buildImageUrl(event.featured_image),
        gallery_images: event.gallery_images?.map(img => buildImageUrl(img)) || []
      }));
    }
    return data;
  },

  // Get single event by slug
  get: async (slug) => {
    const payload = await api.get(`/api/events/${slug}`);
    const data = unwrapData(payload);
    
    // Process images
    if (data) {
      data.featured_image = buildImageUrl(data.featured_image);
      data.gallery_images = data.gallery_images?.map(img => buildImageUrl(img)) || [];
    }
    return data;
  },

  // Get upcoming events (homepage)
  upcoming: async (limit = 6) => {
    const payload = await api.get('/api/events/upcoming', { params: { limit } });
    const data = unwrapData(payload);
    
    if (Array.isArray(data)) {
      return data.map(event => ({
        ...event,
        featured_image: buildImageUrl(event.featured_image),
        gallery_images: event.gallery_images?.map(img => buildImageUrl(img)) || []
      }));
    }
    return data;
  },

  // Get events for calendar view
  calendar: async (year, month) => {
    const payload = await api.get('/api/events/calendar', { params: { year, month } });
    const data = unwrapData(payload);
    
    if (Array.isArray(data)) {
      return data.map(event => ({
        ...event,
        featured_image: buildImageUrl(event.featured_image),
        gallery_images: event.gallery_images?.map(img => buildImageUrl(img)) || []
      }));
    }
    return data;
  },

  // Get featured events
  featured: async () => {
    const payload = await api.get('/api/events', { params: { featured: true, per_page: 4 } });
    const data = unwrapData(payload);
    
    if (Array.isArray(data)) {
      return data.map(event => ({
        ...event,
        featured_image: buildImageUrl(event.featured_image),
        gallery_images: event.gallery_images?.map(img => buildImageUrl(img)) || []
      }));
    }
    return data;
  }
};