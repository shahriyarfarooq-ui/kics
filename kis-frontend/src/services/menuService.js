import { CACHE_TTL, api, unwrapData } from './api';

const normalizeMenuLink = (link) => ({
  label: link.label,
  to: link.url || link.to || '#',
  orderIndex: link.order_index ?? link.orderIndex ?? 0,
  raw: link,
});

const normalizeMenuSection = (section) => {
  // Check different possible property names
  const links = section.menu_links || section.menuLinks || section.links || [];
  
  // If section has a url, it's a direct link, not a dropdown
  if (section.url || section.to) {
    return {
      label: section.title || section.label,
      to: section.url || section.to || '#',
      orderIndex: section.order_index ?? section.orderIndex ?? 0,
      children: [],
      raw: section,
    };
  }

  // Otherwise it's a section with children
  return {
    label: section.title || section.label,
    to: section.url || section.to,
    orderIndex: section.order_index ?? section.orderIndex ?? 0,
    children: links.map(normalizeMenuLink),
    raw: section,
  };
};

export const menuService = {
  list: async () => {
    try {
      const payload = await api.get('/api/menu-sections', { cacheTtl: CACHE_TTL.LONG });
      const data = unwrapData(payload);
      
      // If data is array, map it
      if (Array.isArray(data)) {
        return data.map(normalizeMenuSection);
      }
      
      // If data is object with sections property
      if (data && data.sections) {
        return data.sections.map(normalizeMenuSection);
      }
      
      // If data is object with data property (nested)
      if (data && data.data && Array.isArray(data.data)) {
        return data.data.map(normalizeMenuSection);
      }
      
      return [];
    } catch (error) {
      console.error('Failed to load menu:', error);
      return [];
    }
  },
};