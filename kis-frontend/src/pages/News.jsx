import { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { newsItems as fallbackNewsItems } from '../data/siteData';
import { newsService } from '../services';
import { mapNewsItem } from '../utils/contentMappers';
import { getImageLoadingProps } from '../utils/image';
import { FiCalendar, FiArrowRight, FiAlertCircle } from 'react-icons/fi';

const newsletters = [
  { issue: 'Issue 12', date: 'Q1 2026', title: 'KICS Times - Spring 2026', desc: 'AquaTech wins NVC, new director appointment, and AI Summit highlights.' },
  { issue: 'Issue 11', date: 'Q4 2025', title: 'KICS Times - Winter 2025', desc: 'ICOSST 2025 recap, new lab launches, and industry partnerships.' },
  { issue: 'Issue 10', date: 'Q3 2025', title: 'KICS Times - Fall 2025', desc: 'Research publications, Summer Tech program outcomes, and more.' },
];

export default function News() {
  const [filter, setFilter] = useState('All');
  const [items, setItems] = useState(() => fallbackNewsItems.map(mapNewsItem));
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    newsService.list()
      .then((data) => {
        const mapped = Array.isArray(data) ? data.map(mapNewsItem) : [];
        if (active && mapped.length > 0) setItems(mapped);
      })
      .catch(() => {
        if (active) setError('Unable to load latest news. Showing fallback news.');
      })
      .finally(() => {
        if (active) setLoading(false);
      });

    return () => { active = false; };
  }, []);

  const categories = useMemo(() => (
    ['All', ...new Set(items.map((item) => item.category).filter(Boolean))]
  ), [items]);

  const visible = filter === 'All' ? items : items.filter((item) => item.category === filter);

  return (
    <div>
      <SEO
        title="News & Events"
        description="Latest news, achievements, partnerships, and events from KICS UET Lahore. Research breakthroughs, appointments, and upcoming conferences."
        breadcrumbs={[{ label: 'News & Events', url: '/news' }]}
      />
      <PageHero
        title="News & Events"
        subtitle="Stay up to date with the latest achievements, partnerships, and events at KICS."
        breadcrumbs={[{ label: "What's New" }, { label: 'News & Events' }]}
      />

      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          {loading && (
            <div className="mb-6 text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading latest news...
            </div>
          )}
          {error && (
            <div className="mb-6 flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              <FiAlertCircle size={16} /> {error}
            </div>
          )}

          <AnimateOnScroll>
            <div className="flex flex-wrap gap-2 mb-10">
              {categories.map((category) => (
                <button key={category} onClick={() => setFilter(category)}
                  className={`px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200 ${
                    filter === category ? 'bg-primary-600 text-white shadow-card' : 'bg-slate-100 text-slate-600 hover:bg-primary-600/10'
                  }`}>
                  {category}
                </button>
              ))}
            </div>
          </AnimateOnScroll>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            {visible.map((item, index) => (
              <AnimateOnScroll key={item.id || item.title} delay={index * 60}>
                <article className="card group flex flex-col h-full">
                  <div className="relative h-44 sm:h-48 overflow-hidden">
                    <img
                      src={item.image}
                      alt={item.title}
                      {...getImageLoadingProps({ sizes: '(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw' })}
                      className="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110"
                      onError={e => { e.target.src='https://placehold.co/400x200/4a1209/fae3de?text=KICS+News'; }} />
                    <span className="absolute top-3 left-3 badge">{item.category}</span>
                  </div>
                  <div className="p-4 sm:p-5 flex flex-col flex-1">
                    <span className="text-[10px] sm:text-xs text-cyan-500 font-semibold flex items-center gap-1.5 mb-2">
                      <FiCalendar size={10} /> {item.date || 'Latest'}
                    </span>
                    <h3 className="font-heading font-bold text-primary-800 text-sm sm:text-base mb-2 group-hover:text-cyan-500 transition-colors leading-snug">{item.title}</h3>
                    <p className="text-slate-500 text-sm leading-relaxed flex-1">{item.excerpt}</p>
                    {item.id ? (
                      <Link to={`/news/${item.id}`} className="mt-4 inline-flex items-center gap-1.5 text-primary-800 text-sm font-semibold hover:text-cyan-500 transition-colors group/link">
                        Read More <FiArrowRight size={13} className="transition-transform group-hover/link:translate-x-1" />
                      </Link>
                    ) : (
                      <span className="mt-4 inline-flex items-center gap-1.5 text-slate-400 text-sm font-semibold">
                        Read More <FiArrowRight size={13} />
                      </span>
                    )}
                  </div>
                </article>
              </AnimateOnScroll>
            ))}
          </div>

          {visible.length === 0 && (
            <div className="text-center py-16 text-slate-400">
              <p className="text-lg font-medium">No news found</p>
              <p className="text-sm mt-1">Try a different category.</p>
            </div>
          )}
        </div>
      </section>

      <section className="py-16 bg-slate-50" id="newsletter">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <AnimateOnScroll>
            <div className="text-center mb-10">
              <span className="eyebrow">KICS Times</span>
              <h2 className="section-title">Newsletter</h2>
              <div className="divider-center mt-3" />
              <p className="text-slate-500 text-sm mt-4 max-w-xl mx-auto">
                The KICS Times newsletter keeps you informed about research breakthroughs, events,
                achievements, and opportunities at KICS.
              </p>
            </div>
          </AnimateOnScroll>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
            {newsletters.map((newsletter, index) => (
              <AnimateOnScroll key={newsletter.issue} delay={index * 80}>
                <div className="card p-5 group">
                  <div className="flex items-center justify-between mb-3">
                    <span className="badge-primary">{newsletter.issue}</span>
                    <span className="text-xs text-slate-400">{newsletter.date}</span>
                  </div>
                  <h4 className="font-heading font-bold text-primary-800 mb-2 group-hover:text-cyan-500 transition-colors">{newsletter.title}</h4>
                  <p className="text-slate-500 text-sm">{newsletter.desc}</p>
                  <button className="mt-4 text-primary-800 text-sm font-semibold hover:text-cyan-500 transition-colors flex items-center gap-1.5">
                    Download PDF
                  </button>
                </div>
              </AnimateOnScroll>
            ))}
          </div>
        </div>
      </section>

      <section className="py-16 bg-primary-900 bg-dot-pattern" id="media">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 text-center">
          <AnimateOnScroll animation="reveal-scale">
            <span className="text-primary-300 font-semibold uppercase tracking-[0.2em] text-xs mb-3 block">Media</span>
            <h2 className="section-title-white mb-4">Photo &amp; Video Gallery</h2>
            <div className="divider-center" />
            <p className="text-primary-100 mb-8">
              Browse photos and videos from KICS events, conferences, workshops, and research activities.
            </p>
            <a href="https://facebook.com/kics.official" target="_blank" rel="noreferrer" className="btn-primary">
              Visit Our Facebook Page
            </a>
          </AnimateOnScroll>
        </div>
      </section>
    </div>
  );
}
