// api.js - A utility module for making API requests with caching and error handling.
const DEFAULT_BASE_URL = 'https://kics.edu.pk/adminkics/public/api';

//for local development, you can use the following base URL instead:
// const DEFAULT_BASE_URL = 'http://localhost/kics/adminkics/public/api';

const DEFAULT_CACHE_TTL = 5 * 60 * 1000;

export const CACHE_TTL = {
  SHORT: 60 * 1000,
  MEDIUM: DEFAULT_CACHE_TTL,
  LONG: 30 * 60 * 1000,
};

export const API_BASE_URL = (
  import.meta.env.VITE_API_BASE_URL || DEFAULT_BASE_URL
).replace(/\/+$/, '');

export class ApiError extends Error {
  constructor(message, { status = 0, data = null, url = '' } = {}) {
    super(message);
    this.name = 'ApiError';
    this.status = status;
    this.data = data;
    this.url = url;
  }
}

const isAbsoluteUrl = (url) => /^https?:\/\//i.test(url);
const responseCache = new Map();

const shouldUseCache = (method, cache) => method === 'GET' && cache !== false;

const buildCacheKey = (url, options) => {
  const { headers = {}, unwrap = false } = options;
  return JSON.stringify({
    url,
    unwrap,
    headers,
  });
};

export const clearApiCache = () => responseCache.clear();

export const deleteApiCache = (predicate) => {
  if (typeof predicate !== 'function') return;

  Array.from(responseCache.keys()).forEach((key) => {
    if (predicate(key)) responseCache.delete(key);
  });
};

export const buildApiUrl = (endpoint, params = {}) => {
  const baseUrl = API_BASE_URL.replace(/\/+$/, '');
  const normalizedEndpoint = String(endpoint).replace(/^\/+/, '');
  const endpointPath = /^api(?:\/|$)/i.test(normalizedEndpoint) && /\/api$/i.test(baseUrl)
    ? normalizedEndpoint.replace(/^api\/?/i, '')
    : normalizedEndpoint;

  const path = isAbsoluteUrl(endpoint)
    ? endpoint
    : `${baseUrl}/${endpointPath}`;

  const url = new URL(path);

  Object.entries(params)
    .filter(([, value]) => value !== undefined && value !== null && value !== '')
    .forEach(([key, value]) => {
      if (Array.isArray(value)) {
        value.forEach((item) => url.searchParams.append(key, item));
      } else {
        url.searchParams.set(key, value);
      }
    });

  return url.toString();
};

const parseResponse = async (response) => {
  const contentType = response.headers.get('content-type') || '';

  if (response.status === 204) {
    return null;
  }

  if (contentType.includes('application/json')) {
    return response.json();
  }

  return response.text();
};

export const unwrapData = (payload) => {
  if (payload && typeof payload === 'object' && 'data' in payload) {
    return payload.data;
  }

  return payload;
};

export const request = async (endpoint, options = {}) => {
  const {
    params,
    body,
    cache = true,
    cacheTtl = DEFAULT_CACHE_TTL,
    forceRefresh = false,
    headers,
    unwrap = false,
    ...fetchOptions
  } = options;

  const url = buildApiUrl(endpoint, params);
  const hasBody = body !== undefined && body !== null;
  const isFormData = typeof FormData !== 'undefined' && body instanceof FormData;
  const method = (fetchOptions.method || (hasBody ? 'POST' : 'GET')).toUpperCase();
  const cacheEnabled = shouldUseCache(method, cache);
  const cacheKey = cacheEnabled ? buildCacheKey(url, { headers, unwrap }) : null;

  if (cacheEnabled && !forceRefresh) {
    const cached = responseCache.get(cacheKey);

    if (cached && cached.expiresAt > Date.now()) {
      return cached.promise || cached.data;
    }

    if (cached) responseCache.delete(cacheKey);
  }

  if (!cacheEnabled && method !== 'GET') {
    clearApiCache();
  }

  const requestPromise = fetch(url, {
    method,
    ...fetchOptions,
    headers: {
      Accept: 'application/json',
      ...(hasBody && !isFormData ? { 'Content-Type': 'application/json' } : {}),
      ...headers,
    },
    body: hasBody && !isFormData ? JSON.stringify(body) : body,
  })
    .then(async (response) => {
      const data = await parseResponse(response);

      if (!response.ok) {
        throw new ApiError(
          data?.message || `Request failed with status ${response.status}`,
          { status: response.status, data, url }
        );
      }

      return unwrap ? unwrapData(data) : data;
    });

  if (cacheEnabled) {
    responseCache.set(cacheKey, {
      expiresAt: Date.now() + cacheTtl,
      promise: requestPromise,
    });
  }

  try {
    const data = await requestPromise;

    if (cacheEnabled) {
      responseCache.set(cacheKey, {
        expiresAt: Date.now() + cacheTtl,
        data,
      });
    }

    return data;
  } catch (error) {
    if (cacheEnabled) responseCache.delete(cacheKey);
    throw error;
  }
};

export const api = {
  get: (endpoint, options = {}) => request(endpoint, { ...options, method: 'GET' }),
  post: (endpoint, body, options = {}) => request(endpoint, { ...options, method: 'POST', body }),
  put: (endpoint, body, options = {}) => request(endpoint, { ...options, method: 'PUT', body }),
  patch: (endpoint, body, options = {}) => request(endpoint, { ...options, method: 'PATCH', body }),
  delete: (endpoint, options = {}) => request(endpoint, { ...options, method: 'DELETE' }),
};

export const createAsyncState = () => ({
  data: null,
  error: null,
  loading: false,
});
