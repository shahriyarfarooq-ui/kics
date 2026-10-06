import { useEffect, useMemo, useState } from 'react';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { publicationService } from '../services';
import { mapPublication, truncateText } from '../utils/contentMappers';
import { FiAlertCircle, FiAward, FiBook, FiCpu, FiDollarSign, FiExternalLink, FiFileText, FiGitBranch, FiGlobe, FiSearch } from 'react-icons/fi';

const fallbackPublications = [
  {
    id: 'fallback-ai',
    title: 'Artificial Intelligence and Machine Learning Research at KICS',
    author: 'KICS Researchers',
    journal: 'KICS Research Archive',
    publication_year: '2026',
    category: 'Artificial Intelligence',
    publication_abstract: 'Representative KICS publication record used while the live publications API is unavailable.',
  },
  {
    id: 'fallback-cybersecurity',
    title: 'Cybersecurity, Networks, and Applied Systems Research',
    author: 'KICS Research Groups',
    journal: 'KICS Research Archive',
    publication_year: '2025',
    category: 'Cybersecurity',
    publication_abstract: 'Representative KICS publication record used while the live publications API is unavailable.',
  },
  {
    id: 'fallback-iot',
    title: 'IoT and Embedded Systems Innovation',
    author: 'KICS Labs',
    journal: 'KICS Research Archive',
    publication_year: '2024',
    category: 'IoT',
    publication_abstract: 'Representative KICS publication record used while the live publications API is unavailable.',
  },
];

const accessLinks = [
  { name: 'IEEE Xplore', desc: 'Browse KICS papers published in IEEE journals and conference proceedings.', url: 'https://ieeexplore.ieee.org', Icon: FiBook },
  { name: 'Google Scholar', desc: 'Find all KICS publications and citation metrics on Google Scholar.', url: 'https://scholar.google.com', Icon: FiGlobe },
  { name: 'KICS Official Portal', desc: 'Download full publication list directly from the KICS website.', url: 'https://kics.edu.pk/web/research-technology/publications/', Icon: FiFileText },
  { name: 'ResearchGate', desc: 'Connect with KICS researchers and access their full profiles.', url: 'https://researchgate.net', Icon: FiGitBranch },
];

const pageSize = 8;

export default function Publications() {
  const [publications, setPublications] = useState(() => fallbackPublications.map(mapPublication));
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('All');
  const [year, setYear] = useState('All');
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    publicationService.list()
      .then((data) => {
        const mapped = Array.isArray(data) ? data.map(mapPublication) : [];
        if (active && mapped.length > 0) setPublications(mapped);
      })
      .catch(() => {
        if (active) setError('Unable to load current publications. Showing fallback records.');
      })
      .finally(() => {
        if (active) setLoading(false);
      });

    return () => { active = false; };
  }, []);

  useEffect(() => {
    setPage(1);
  }, [query, category, year]);

  const categories = useMemo(() => (
    ['All', ...new Set(publications.map((item) => item.category).filter(Boolean))]
  ), [publications]);

  const years = useMemo(() => (
    ['All', ...new Set(publications.map((item) => String(item.year)).filter(Boolean))].sort((a, b) => {
      if (a === 'All') return -1;
      if (b === 'All') return 1;
      return Number(b) - Number(a);
    })
  ), [publications]);

  const journals = useMemo(() => (
    [...new Set(publications.map((item) => item.journal).filter(Boolean))].slice(0, 8)
  ), [publications]);

  const filtered = useMemo(() => {
    const needle = query.trim().toLowerCase();

    return publications.filter((item) => {
      const matchesCategory = category === 'All' || item.category === category;
      const matchesYear = year === 'All' || String(item.year) === year;
      const searchable = [item.title, item.author, item.journal, item.category, item.abstract, item.group, item.person]
        .filter(Boolean)
        .join(' ')
        .toLowerCase();

      return matchesCategory && matchesYear && (!needle || searchable.includes(needle));
    });
  }, [publications, query, category, year]);

  const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
  const visible = filtered.slice((page - 1) * pageSize, page * pageSize);

  const pubStats = [
    { value: `${publications.length}+`, label: 'Total Publications', Icon: FiFileText },
    { value: `${journals.length}+`, label: 'Publication Venues', Icon: FiAward },
    { value: `${categories.length - 1}+`, label: 'Research Categories', Icon: FiCpu },
    { value: `${years.length - 1}+`, label: 'Publication Years', Icon: FiDollarSign },
  ];

  return (
    <div>
      <SEO
        title="Publications"
        description="Browse research publications by KICS UET Lahore in IEEE, ACM, Elsevier and Springer - covering AI, cybersecurity, IoT, and software engineering."
        breadcrumbs={[{ label: 'Research', url: '/research-areas' }, { label: 'Publications', url: '/publications' }]}
      />
      <PageHero
        title="Publications"
        subtitle="KICS researchers publish cutting-edge work in top-tier international journals and conferences."
        breadcrumbs={[{ label: 'Research' }, { label: 'Publications' }]}
      />

      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          {loading && (
            <div className="mb-6 text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading publications...
            </div>
          )}
          {error && (
            <div className="mb-6 flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              <FiAlertCircle size={16} /> {error}
            </div>
          )}

          <AnimateOnScroll>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-12 sm:mb-14">
              {pubStats.map((stat, index) => (
                <div key={stat.label} className="card p-5 sm:p-6 text-center group" style={{ animationDelay: `${index * 80}ms` }}>
                  <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-primary-50 group-hover:bg-primary-600 flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                    <stat.Icon size={20} className="text-primary-600 group-hover:text-white transition-colors duration-300 sm:w-[22px] sm:h-[22px]" />
                  </div>
                  <p className="text-2xl sm:text-3xl font-bold text-slate-900">{stat.value}</p>
                  <p className="text-slate-500 text-xs sm:text-sm mt-1">{stat.label}</p>
                </div>
              ))}
            </div>
          </AnimateOnScroll>

          <AnimateOnScroll>
            <div className="mb-8 grid grid-cols-1 lg:grid-cols-[1fr_auto_auto] gap-3">
              <label className="relative block">
                <FiSearch size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                <input
                  value={query}
                  onChange={(event) => setQuery(event.target.value)}
                  className="w-full rounded-lg border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100"
                  placeholder="Search by title, author, journal, or abstract"
                />
              </label>
              <select
                value={category}
                onChange={(event) => setCategory(event.target.value)}
                className="rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100"
              >
                {categories.map((item) => <option key={item} value={item}>{item}</option>)}
              </select>
              <select
                value={year}
                onChange={(event) => setYear(event.target.value)}
                className="rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100"
              >
                {years.map((item) => <option key={item} value={item}>{item}</option>)}
              </select>
            </div>
          </AnimateOnScroll>

          <div className="space-y-4 mb-12">
            {visible.map((publication, index) => (
              <AnimateOnScroll key={publication.id || `${publication.title}-${index}`} delay={index * 40}>
                <article className="card p-5 sm:p-6">
                  <div className="flex flex-col sm:flex-row sm:items-start gap-4">
                    <div className="w-11 h-11 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                      <FiFileText size={20} className="text-primary-700" />
                    </div>
                    <div className="flex-1 min-w-0">
                      <div className="flex flex-wrap gap-2 mb-2">
                        <span className="badge-primary">{publication.category}</span>
                        {publication.year && <span className="text-xs font-semibold text-cyan-600">{publication.year}</span>}
                      </div>
                      <h3 className="font-heading font-bold text-primary-900 text-base sm:text-lg leading-snug">{publication.title}</h3>
                      <p className="text-sm text-slate-500 mt-1">{publication.author}</p>
                      <p className="text-sm text-slate-600 mt-2">
                        {publication.journal}{publication.volume ? `, ${publication.volume}` : ''}
                      </p>
                      {publication.abstract && (
                        <p className="text-sm text-slate-500 mt-3 leading-relaxed">{truncateText(publication.abstract, 220)}</p>
                      )}
                    </div>
                  </div>
                </article>
              </AnimateOnScroll>
            ))}

            {visible.length === 0 && (
              <div className="text-center py-14 text-slate-400">
                <FiSearch size={40} className="mx-auto mb-3 text-slate-300" />
                <p className="text-lg font-medium">No publications found</p>
                <p className="text-sm mt-1">Try changing the search or filters.</p>
              </div>
            )}
          </div>

          {filtered.length > pageSize && (
            <div className="flex flex-wrap items-center justify-center gap-2 mb-14">
              <button
                onClick={() => setPage((value) => Math.max(1, value - 1))}
                disabled={page === 1}
                className="px-4 py-2 rounded-lg text-sm font-semibold bg-slate-100 text-slate-600 disabled:opacity-40 hover:bg-primary-600/10"
              >
                Previous
              </button>
              <span className="px-4 py-2 text-sm text-slate-500">Page {page} of {totalPages}</span>
              <button
                onClick={() => setPage((value) => Math.min(totalPages, value + 1))}
                disabled={page === totalPages}
                className="px-4 py-2 rounded-lg text-sm font-semibold bg-slate-100 text-slate-600 disabled:opacity-40 hover:bg-primary-600/10"
              >
                Next
              </button>
            </div>
          )}

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <AnimateOnScroll animation="reveal-left">
              <h3 className="section-title text-xl sm:text-2xl mb-4 sm:mb-6">Publication Venues</h3>
              <p className="text-slate-600 text-sm leading-relaxed mb-5">
                KICS researchers regularly publish in leading international venues. Current records include:
              </p>
              <ul className="space-y-2">
                {journals.map((journal) => (
                  <li key={journal} className="flex items-center gap-2 text-slate-600 text-sm py-2 border-b border-slate-100 last:border-0">
                    <span className="text-cyan-500">-</span> {journal}
                  </li>
                ))}
              </ul>
            </AnimateOnScroll>
            <AnimateOnScroll animation="reveal-right">
              <h3 className="section-title text-xl sm:text-2xl mb-4 sm:mb-6">Access Our Research</h3>
              <div className="space-y-4">
                {accessLinks.map((item) => (
                  <a key={item.name} href={item.url} target="_blank" rel="noreferrer"
                    className="card p-4 flex items-start gap-4 group block">
                    <div className="w-10 h-10 rounded-lg bg-primary-50 group-hover:bg-primary-600 flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                      <item.Icon size={18} className="text-primary-600 group-hover:text-white transition-colors duration-300" />
                    </div>
                    <div className="flex-1">
                      <h4 className="font-semibold text-primary-800 group-hover:text-cyan-500 transition-colors flex items-center gap-1.5">
                        {item.name} <FiExternalLink size={12} className="opacity-0 group-hover:opacity-100 transition-opacity" />
                      </h4>
                      <p className="text-slate-500 text-xs mt-0.5">{item.desc}</p>
                    </div>
                  </a>
                ))}
              </div>
            </AnimateOnScroll>
          </div>
        </div>
      </section>
    </div>
  );
}
