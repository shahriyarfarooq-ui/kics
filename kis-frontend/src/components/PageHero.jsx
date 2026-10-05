import { memo } from 'react';
import { Link } from 'react-router-dom';
import { FiChevronRight, FiHome } from 'react-icons/fi';

const PageHero = memo(function PageHero({ title, subtitle, breadcrumbs = [], backgroundImage }) {
  return (
    <div
      className={`relative pt-20 pb-12 overflow-hidden border-b ${backgroundImage ? 'bg-primary-950 border-primary-900' : 'bg-primary-50 border-primary-100'}`}
      style={backgroundImage ? {
        backgroundImage: `linear-gradient(90deg, rgba(5, 16, 38, 0.88), rgba(8, 28, 66, 0.72)), url("${backgroundImage}")`,
        backgroundSize: '100% 100%',
        backgroundPosition: 'center 56%',
      } : undefined}
    >
      {!backgroundImage && <>
        <div className="absolute top-0 right-0 w-72 h-72 bg-primary-100/50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-56 h-56 bg-primary-100/30 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4 pointer-events-none" />
      </>}

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 text-center">
        {/* Breadcrumb */}
        <nav className={`inline-flex items-center gap-1.5 ${backgroundImage ? 'text-white/75' : 'text-slate-500'} text-xs mb-3 flex-wrap justify-center`} aria-label="Breadcrumb">
          <Link to="/" className={`transition-colors flex items-center gap-1 ${backgroundImage ? 'hover:text-white' : 'hover:text-primary-600'}`}>
            <FiHome size={11} /> Home
          </Link>
          {breadcrumbs.map((b, i) => (
            <span key={i} className="flex items-center gap-1.5">
              <FiChevronRight size={11} className={backgroundImage ? 'text-white/50' : 'text-slate-400'} />
              {b.to ? (
                <Link to={b.to} className={`transition-colors ${backgroundImage ? 'hover:text-white' : 'hover:text-primary-600'}`}>{b.label}</Link>
              ) : (
                <span className={`${backgroundImage ? 'text-white' : 'text-slate-700'} font-medium`}>{b.label}</span>
              )}
            </span>
          ))}
        </nav>

        <h1 className={`text-2xl sm:text-3xl md:text-4xl font-bold ${backgroundImage ? 'text-white' : 'text-slate-900'} mb-3 animate-fadeUp`}>
          {title}
        </h1>
        {subtitle && (
          <p className={`${backgroundImage ? 'text-white/85' : 'text-slate-600'} max-w-2xl mx-auto text-sm sm:text-base animate-fadeIn`} style={{ animationDelay: '150ms' }}>
            {subtitle}
          </p>
        )}
      </div>
    </div>
  );
});

export default PageHero;
