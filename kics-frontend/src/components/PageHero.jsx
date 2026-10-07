import { memo, useEffect, useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { FiChevronRight, FiHome } from 'react-icons/fi';
import { api } from '../services/api';

const PAGE_KEY_MAP = {
  '/about': 'about',
  '/contact': 'contact',
  '/director-message': 'director-message',
  '/events': 'events',
  '/erp-departments': 'erp-departments',
  '/erp-employees': 'erp-employees',
  '/icosst': 'icosst',
  '/innovation': 'innovation',
  '/jobs': 'jobs',
  '/news': 'news',
  '/publications': 'publications',
  '/research': 'research',
  '/research-areas': 'research-areas',
  '/services': 'services',
  '/staff': 'staff',
  '/workshops': 'workshops',
};

const resolvePageKey = (pathname, overrideKey) => {
  if (overrideKey) return overrideKey;

  const normalized = pathname.replace(/\/+$/, '').toLowerCase();
  if (PAGE_KEY_MAP[normalized]) return PAGE_KEY_MAP[normalized];

  if (normalized.includes('/kics-departments/')) return 'erp-department-detail';
  if (normalized.includes('/jobs/')) return 'job-detail';
  if (normalized.includes('/news/')) return 'news-detail';
  if (normalized.includes('/staff/')) return 'staff-detail';
  if (normalized.includes('/research-areas/')) return 'research-area-detail';

  const firstSegment = normalized.split('/').filter(Boolean)[0];
  if (firstSegment === 'kics-departments') return 'erp-departments';
  if (firstSegment === 'jobs') return 'jobs';
  if (firstSegment === 'news') return 'news';
  if (firstSegment === 'staff') return 'staff';

  return null;
};

const PageHero = memo(function PageHero({ title, subtitle, breadcrumbs = [], backgroundImage, fallbackIcon: FallbackIcon, pageKey }) {
  const location = useLocation();
  const [dbBackgroundImage, setDbBackgroundImage] = useState(null);

  useEffect(() => {
    if (backgroundImage) return;

    const resolvedKey = resolvePageKey(location.pathname, pageKey);
    if (!resolvedKey) return;

    let ignore = false;

    api.get('/api/page-heroes', { cache: false })
      .then((payload) => {
        if (ignore) return;
        const imagePath = payload?.[resolvedKey];
        if (imagePath) {
          const baseUrl = (import.meta.env.VITE_API_BASE_URL || 'https://kics.edu.pk/adminkics/public/api').replace(/\/+$/, '');
          const base = baseUrl.replace(/\/api$/, '');
          const asset = imagePath.startsWith('http') ? imagePath : `${base}/storage/${imagePath.replace(/^\/+/, '')}`;
          setDbBackgroundImage(asset);
        } else {
          setDbBackgroundImage(null);
        }
      })
      .catch(() => {
        if (!ignore) setDbBackgroundImage(null);
      });

    return () => {
      ignore = true;
    };
  }, [backgroundImage, pageKey, location.pathname]);

  const resolvedBackgroundImage = backgroundImage || dbBackgroundImage;

  return (
    <div
      className={`relative pt-20 pb-12 overflow-hidden border-b ${resolvedBackgroundImage ? 'bg-primary-950 border-primary-900' : 'bg-primary-50 border-primary-100'}`}
      style={resolvedBackgroundImage ? {
        backgroundImage: `linear-gradient(90deg, rgba(5, 16, 38, 0.88), rgba(8, 28, 66, 0.72)), url("${resolvedBackgroundImage}")`,
        backgroundSize: '100% 100%',
        backgroundPosition: 'center 56%',
      } : undefined}
    >
      {!backgroundImage && <>
        <div className="absolute top-0 right-0 w-72 h-72 bg-primary-100/50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-56 h-56 bg-primary-100/30 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4 pointer-events-none" />
      </>}

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 text-center">
        {!resolvedBackgroundImage && FallbackIcon && (
          <div className="mb-4 flex justify-center" aria-hidden="true">
            <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-100 text-primary-700">
              <FallbackIcon size={28} />
            </div>
          </div>
        )}
        {/* Breadcrumb */}
        <nav className={`inline-flex items-center gap-1.5 ${backgroundImage ? 'text-white/75' : 'text-slate-500'} text-xs mb-3 flex-wrap justify-center`} aria-label="Breadcrumb">
          <Link to="/" className={`transition-colors flex items-center gap-1 ${resolvedBackgroundImage ? 'hover:text-white' : 'hover:text-primary-600'}`}>
            <FiHome size={11} /> Home
          </Link>
          {breadcrumbs.map((b, i) => (
            <span key={i} className="flex items-center gap-1.5">
              <FiChevronRight size={11} className={resolvedBackgroundImage ? 'text-white/50' : 'text-slate-400'} />
              {b.to ? (
                <Link to={b.to} className={`transition-colors ${resolvedBackgroundImage ? 'hover:text-white' : 'hover:text-primary-600'}`}>{b.label}</Link>
              ) : (
                <span className={`${resolvedBackgroundImage ? 'text-white' : 'text-slate-700'} font-medium`}>{b.label}</span>
              )}
            </span>
          ))}
        </nav>

        <h1 className={`text-2xl sm:text-3xl md:text-4xl font-bold ${resolvedBackgroundImage ? 'text-white' : 'text-slate-900'} mb-3 animate-fadeUp`}>
          {title}
        </h1>
        {subtitle && (
          <p className={`${resolvedBackgroundImage ? 'text-white/85' : 'text-slate-600'} max-w-2xl mx-auto text-sm sm:text-base animate-fadeIn`} style={{ animationDelay: '150ms' }}>
            {subtitle}
          </p>
        )}
      </div>
    </div>
  );
});

export default PageHero;
