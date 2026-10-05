export const DEFAULT_IMAGE_FALLBACK = 'https://placehold.co/600x400/4a1209/fae3de?text=KICS';

const absoluteUrlPattern = /^(?:[a-z][a-z\d+\-.]*:)?\/\//i;

const encodeUrl = (url) => {
  try {
    return encodeURI(url);
  } catch {
    return url;
  }
};

// ✅ DIRECT FIX - Use the correct storage URL
const STORAGE_BASE_URL = 'https://kics.edu.pk/web/adminkics/public/storage';

export const buildImageUrl = (value, fallback = DEFAULT_IMAGE_FALLBACK) => {
  if (value === null || value === undefined) return fallback;

  const image = String(value).trim();
  if (!image) return fallback;

  if (
    absoluteUrlPattern.test(image) ||
    image.startsWith('data:') ||
    image.startsWith('blob:')
  ) {
    return encodeUrl(image);
  }

  const normalized = image
    .replace(/\\/g, '/')
    .replace(/^public\//i, '')
    .replace(/^storage\/app\/public\//i, '')
    .replace(/^app\/public\//i, '');

  // Remove leading slashes
  const cleanPath = normalized.replace(/^\/+/, '');

  // Return full URL
  return encodeUrl(`${STORAGE_BASE_URL}/${cleanPath}`);
};

export const getImageLoadingProps = ({
  eager = false,
  priority = eager ? 'high' : 'auto',
  sizes = '100vw',
} = {}) => ({
  loading: eager ? 'eager' : 'lazy',
  decoding: eager ? 'sync' : 'async',
  fetchpriority: priority,
  sizes,
});