import { Helmet } from 'react-helmet-async';
import { buildImageUrl } from '../utils/image';

const BASE_URL = (import.meta.env.VITE_SITE_URL || 'https://kics.edu.pk').replace(/\/+$/, '');
const DEFAULT_IMAGE = 'https://kics.edu.pk/web/wp-content/uploads/2018/02/unnamed.png';
const SITE_NAME = 'KICS UET Lahore';
const DEFAULT_DESCRIPTION = 'KICS is a leading applied computer science research institution at UET Lahore, advancing AI, cybersecurity, IoT and enterprise software.';

const stripHtml = (value = '') =>
  String(value || '')
    .replace(/<[^>]*>/g, ' ')
    .replace(/\s+/g, ' ')
    .trim();

const limitText = (value, maxLength) => {
  const text = stripHtml(value);
  if (text.length <= maxLength) return text;
  return `${text.slice(0, maxLength - 3).trim()}...`;
};

const absoluteUrlPattern = /^(?:[a-z][a-z\d+\-.]*:)?\/\//i;

const toAbsoluteUrl = (value = '') => {
  const path = String(value || '').trim();
  if (!path) return BASE_URL;
  if (absoluteUrlPattern.test(path)) return path;

  try {
    return new URL(path, `${BASE_URL}/`).toString();
  } catch {
    return BASE_URL;
  }
};

const orgSchema = {
  '@context': 'https://schema.org',
  '@type': 'EducationalOrganization',
  name: 'Al-Khwarizmi Institute of Computer Science',
  alternateName: ['KICS', 'KICS UET'],
  url: BASE_URL,
  logo: {
    '@type': 'ImageObject',
    url: DEFAULT_IMAGE,
    width: 200,
    height: 200,
  },
  description: DEFAULT_DESCRIPTION,
  foundingDate: '2002',
  address: {
    '@type': 'PostalAddress',
    streetAddress: 'G.T. Road',
    addressLocality: 'Lahore',
    addressRegion: 'Punjab',
    postalCode: '54890',
    addressCountry: 'PK',
  },
  geo: {
    '@type': 'GeoCoordinates',
    latitude: 31.5805,
    longitude: 74.3575,
  },
  telephone: '+92-42-99029450',
  email: 'info@kics.edu.pk',
  sameAs: [
    'https://facebook.com/kics.official',
    'https://twitter.com/KICSUETLAHORE',
    'https://linkedin.com/company/kics',
  ],
  parentOrganization: {
    '@type': 'EducationalOrganization',
    name: 'University of Engineering and Technology, Lahore',
    url: 'https://uet.edu.pk',
  },
};

const websiteSchema = {
  '@context': 'https://schema.org',
  '@type': 'WebSite',
  name: SITE_NAME,
  url: BASE_URL,
  potentialAction: {
    '@type': 'SearchAction',
    target: {
      '@type': 'EntryPoint',
      urlTemplate: `${BASE_URL}/search?q={search_term_string}`,
    },
    'query-input': 'required name=search_term_string',
  },
};

export default function SEO({
  title,
  description = DEFAULT_DESCRIPTION,
  image = DEFAULT_IMAGE,
  type = 'website',
  breadcrumbs = [],
  path = '',
  canonical = '',
  noIndex = false,
  publishedTime = '',
  modifiedTime = '',
  author = SITE_NAME,
}) {
  const cleanTitle = limitText(title || 'Al-Khwarizmi Institute of Computer Science', 70);
  const fullTitle = cleanTitle === SITE_NAME ? SITE_NAME : `${cleanTitle} | ${SITE_NAME}`;
  const metaDescription = limitText(description || DEFAULT_DESCRIPTION, 160);
  const currentPath = path || (typeof window !== 'undefined' ? window.location.pathname : '/');
  const currentUrl = toAbsoluteUrl(canonical || currentPath || '/');
  const ogImage = toAbsoluteUrl(buildImageUrl(image, DEFAULT_IMAGE));
  const schemaType = type === 'article' ? 'Article' : 'WebPage';

  const pageSchema = {
    '@context': 'https://schema.org',
    '@type': schemaType,
    name: cleanTitle,
    headline: cleanTitle,
    description: metaDescription,
    url: currentUrl,
    image: ogImage,
    publisher: orgSchema,
    ...(publishedTime ? { datePublished: publishedTime } : {}),
    ...(modifiedTime ? { dateModified: modifiedTime } : {}),
    ...(author ? { author: { '@type': 'Organization', name: author } } : {}),
  };

  const breadcrumbSchema = breadcrumbs.length
    ? {
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        itemListElement: [
          { '@type': 'ListItem', position: 1, name: 'Home', item: BASE_URL },
          ...breadcrumbs.map((breadcrumb, index) => ({
            '@type': 'ListItem',
            position: index + 2,
            name: breadcrumb.label,
            ...(breadcrumb.url ? { item: toAbsoluteUrl(breadcrumb.url) } : {}),
          })),
        ],
      }
    : null;

  return (
    <Helmet>
      <title>{fullTitle}</title>
      <meta name="description" content={metaDescription} />
      <meta name="keywords" content="KICS, UET Lahore, computer science, AI, machine learning, cybersecurity, IoT, research, Pakistan, engineering, technology" />
      <meta name="author" content={author} />
      <meta name="robots" content={noIndex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'} />
      <meta httpEquiv="Content-Language" content="en" />
      <meta name="theme-color" content="#1e40af" />
      <link rel="canonical" href={currentUrl} />

      <meta property="og:type" content={type} />
      <meta property="og:url" content={currentUrl} />
      <meta property="og:title" content={fullTitle} />
      <meta property="og:description" content={metaDescription} />
      <meta property="og:image" content={ogImage} />
      <meta property="og:image:width" content="1200" />
      <meta property="og:image:height" content="630" />
      <meta property="og:site_name" content={SITE_NAME} />
      <meta property="og:locale" content="en_US" />
      {publishedTime && <meta property="article:published_time" content={publishedTime} />}
      {modifiedTime && <meta property="article:modified_time" content={modifiedTime} />}
      {author && <meta property="article:author" content={author} />}

      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:url" content={currentUrl} />
      <meta name="twitter:title" content={fullTitle} />
      <meta name="twitter:description" content={metaDescription} />
      <meta name="twitter:image" content={ogImage} />
      <meta name="twitter:site" content="@KICSUETLAHORE" />
      <meta name="twitter:creator" content="@KICSUETLAHORE" />

      <script type="application/ld+json">{JSON.stringify(orgSchema)}</script>
      <script type="application/ld+json">{JSON.stringify(websiteSchema)}</script>
      <script type="application/ld+json">{JSON.stringify(pageSchema)}</script>
      {breadcrumbSchema && (
        <script type="application/ld+json">{JSON.stringify(breadcrumbSchema)}</script>
      )}
    </Helmet>
  );
}
