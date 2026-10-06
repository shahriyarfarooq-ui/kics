import { useState, useEffect, useCallback, useRef } from 'react';
import { Link } from 'react-router-dom';
import { FiArrowRight, FiAward, FiBookOpen, FiCpu, FiUsers, FiStar, FiChevronLeft, FiChevronRight } from 'react-icons/fi';
import { heroSlides as staticHeroSlides } from '../data/siteData';
import { newsService } from '../services/newsService';
import { buildImageUrl } from '../utils/contentMappers';
import { getImageLoadingProps } from '../utils/image';
import kicsBg from '../assets/images/kics-bg.png';

const stats = [
  { icon: FiAward,    value: '22+',   label: 'Years' },
  { icon: FiBookOpen, value: '500+',  label: 'Publications' },
  { icon: FiCpu,      value: '25+',   label: 'Labs' },
  { icon: FiUsers,    value: '1000+', label: 'Trained' },
];

export default function Hero() {
  // Static text slides - NOT controlled by arrows
  const [textSlides] = useState(staticHeroSlides);
  const [textIndex, setTextIndex] = useState(0);
  
  // News slides - controlled by arrows and dots
  const [newsSlides, setNewsSlides] = useState([]);
  const [newsIndex, setNewsIndex] = useState(0);
  const [animating, setAnimating] = useState(false);
  const [visible, setVisible] = useState(false);
  const [imageLoaded, setImageLoaded] = useState(false);

  // Fetch news slides for the floating image carousel
  useEffect(() => {
    let active = true;
    const fetchNewsSlides = async () => {
      try {
        const newsItems = await newsService.list();
        if (!active) return;

        const newsData = Array.isArray(newsItems)
          ? newsItems
              .filter((item) => item?.image)
              .sort((a, b) => new Date(b.created_at || b.updated_at || b.date || 0) - new Date(a.created_at || a.updated_at || a.date || 0))
              .slice(0, 6)
              .map((item) => ({
                id: item.id,
                image: buildImageUrl(item.image, '/images/hero-2.jpg'),
                title: item.title || 'Latest News',
                excerpt: item.excerpt || item.description || 'Stay updated with the latest from KICS.',
                link: item.id ? `/news/${item.id}` : '/news',
              }))
          : [];

        if (newsData.length > 0) {
          setNewsSlides(newsData);
          setImageLoaded(false);
        } else {
          // Fallback to static images if no news
          setNewsSlides([
            {
              id: 1,
              image: kicsBg,
              title: 'Welcome to KICS',
              excerpt: 'Al-Khwarizmi Institute of Computer Science — advancing knowledge through cutting-edge research.',
              link: '/about',
            },
            {
              id: 2,
              image: 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=400&fit=crop',
              title: 'Research Excellence',
              excerpt: 'Pioneering research in AI, cybersecurity, IoT, and software engineering.',
              link: '/research-areas',
            },
          ]);
        }
      } catch (err) {
        console.error('Failed to fetch news slides:', err);
        // Fallback slides
        setNewsSlides([
          {
            id: 1,
            image: kicsBg,
            title: 'Welcome to KICS',
            excerpt: 'Al-Khwarizmi Institute of Computer Science — advancing knowledge through cutting-edge research.',
            link: '/about',
          },
        ]);
      }
    };

    fetchNewsSlides();
    return () => {
      active = false;
    };
  }, []);

  useEffect(() => {
    const t = setTimeout(() => setVisible(true), 100);
    return () => clearTimeout(t);
  }, []);

  // News navigation - arrows control this
  const goToNews = useCallback((i) => {
    if (animating || i === newsIndex) return;
    setAnimating(true);
    setImageLoaded(false);
    setTimeout(() => { 
      setNewsIndex(i); 
      setAnimating(false); 
    }, 450);
  }, [animating, newsIndex]);

  const nextNews = useCallback(() => {
    if (newsSlides.length === 0) return;
    const next = (newsIndex + 1) % newsSlides.length;
    goToNews(next);
  }, [newsIndex, newsSlides.length, goToNews]);

  const prevNews = useCallback(() => {
    if (newsSlides.length === 0) return;
    const prev = (newsIndex - 1 + newsSlides.length) % newsSlides.length;
    goToNews(prev);
  }, [newsIndex, newsSlides.length, goToNews]);

  // Auto-rotate news slides
  useEffect(() => {
    if (newsSlides.length <= 1) return;
    const id = setInterval(nextNews, 5500);
    return () => clearInterval(id);
  }, [nextNews, newsSlides.length]);

  // Auto-rotate text slides (left side)
  useEffect(() => {
    if (textSlides.length <= 1) return;
    const id = setInterval(() => {
      setTextIndex((prev) => (prev + 1) % textSlides.length);
    }, 5500);
    return () => clearInterval(id);
  }, [textSlides.length]);

  // Keyboard navigation for news
  useEffect(() => {
    const handleKeyDown = (e) => {
      if (newsSlides.length === 0) return;
      if (e.key === 'ArrowLeft') prevNews();
      if (e.key === 'ArrowRight') nextNews();
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [nextNews, prevNews, newsSlides.length]);

  // ── Typewriter effect ──────────────────────────────────────
  const phrases = [
    'Excellence in Research',
    'Innovation in Technology',
    'Leaders in AI & Engineering',
    "Shaping Pakistan's Future",
    'Where Ideas Become Impact',
  ];
  const [typedText, setTypedText] = useState('');
  const [phraseIdx, setPhraseIdx] = useState(0);
  const [isDeleting, setIsDeleting] = useState(false);
  const typeTimerRef = useRef(null);

  useEffect(() => {
    const fullPhrase = phrases[phraseIdx];
    const speed = isDeleting ? 40 : 80;

    typeTimerRef.current = setTimeout(() => {
      if (!isDeleting) {
        setTypedText(fullPhrase.slice(0, typedText.length + 1));
        if (typedText.length + 1 === fullPhrase.length) {
          setTimeout(() => setIsDeleting(true), 1800);
        }
      } else {
        setTypedText(fullPhrase.slice(0, typedText.length - 1));
        if (typedText.length - 1 === 0) {
          setIsDeleting(false);
          setPhraseIdx((i) => (i + 1) % phrases.length);
        }
      }
    }, speed);
    return () => clearTimeout(typeTimerRef.current);
  }, [typedText, isDeleting, phraseIdx]);

  const textSlide = textSlides[textIndex] || staticHeroSlides[0];
  const newsSlide = newsSlides[newsIndex] || newsSlides[0] || {
    image: kicsBg,
    title: 'Welcome to KICS',
    excerpt: 'Al-Khwarizmi Institute of Computer Science — advancing knowledge through cutting-edge research.',
    link: '/about',
  };

  return (
    <section
      id="home"
      className="relative overflow-hidden min-h-[60vh] sm:min-h-[65vh] md:min-h-[70vh] lg:min-h-[75vh] xl:min-h-[80vh]"
      role="region"
      aria-roledescription="carousel"
      aria-label="Hero section"
    >
      {/* ── STATIC BACKGROUND IMAGE ── */}
      {/* ── STATIC BACKGROUND IMAGE ── */}
<div className="absolute inset-0 overflow-hidden">
  <img
    src={kicsBg}
    alt="KICS Background"
    className="absolute inset-0 w-full h-full object-cover object-center"
  />
  {/* Light overlay for text readability */}
  <div className="absolute inset-0 bg-gradient-to-r from-[#0B1833]/70 via-[#0B1833]/50 via-40% to-transparent to-70% z-[1]" />
</div>

      {/* ── Left-to-right gradient overlay ── */}
      <div className="absolute inset-0 bg-gradient-to-r from-[#0B1833]/90 via-[#0B1833]/75 via-40% to-transparent to-70% z-[1]" />
      <div className="absolute inset-0 bg-gradient-to-t from-[#0B1833]/40 via-transparent to-transparent z-[1]" />

      {/* ── Decorative blobs ── */}
      <div className="absolute -top-20 -left-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none z-[2]" />
      <div className="absolute -bottom-16 right-1/3 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none z-[2]" />
      <div className="absolute top-1/3 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none z-[2]" />

      {/* ── Animated particles ── */}
      {[
        { top: '15%', left: '8%',  size: 3, delay: '0s',   dur: '6s'  },
        { top: '70%', left: '5%',  size: 5, delay: '1s',   dur: '8s'  },
        { top: '35%', left: '18%', size: 2, delay: '2s',   dur: '7s'  },
        { top: '80%', left: '22%', size: 4, delay: '0.5s', dur: '9s'  },
        { top: '20%', left: '55%', size: 3, delay: '1.5s', dur: '7.5s'},
        { top: '60%', left: '75%', size: 6, delay: '3s',   dur: '8s'  },
        { top: '10%', left: '88%', size: 2, delay: '2.5s', dur: '6.5s'},
        { top: '50%', left: '92%', size: 4, delay: '1s',   dur: '9.5s'},
        { top: '85%', left: '60%', size: 3, delay: '0.8s', dur: '7s'  },
        { top: '25%', left: '38%', size: 2, delay: '3.5s', dur: '8.5s'},
      ].map((p, i) => (
        <div
          key={i}
          className="absolute rounded-full pointer-events-none opacity-0 z-[2]"
          style={{
            top: p.top, left: p.left,
            width: p.size, height: p.size,
            background: i % 2 === 0 ? 'rgba(245,158,11,0.7)' : 'rgba(147,197,253,0.6)',
            animation: `particle-float ${p.dur} ease-in-out ${p.delay} infinite`,
          }}
        />
      ))}

      {/* ── Floating geometric shapes ── */}
      <div className="absolute top-10 left-[42%] w-14 h-14 border-2 border-blue-400/30 rounded-2xl rotate-12 pointer-events-none z-[2]" />
      <div className="absolute bottom-14 left-[36%] w-8 h-8 bg-amber-400/20 rounded-lg rotate-45 pointer-events-none z-[2]" />
      <div className="absolute top-16 right-[14%] w-5 h-5 bg-amber-400/30 rounded-full pointer-events-none z-[2]" />
      <div className="absolute bottom-20 right-[28%] w-10 h-10 border-2 border-blue-400/30 rounded-full pointer-events-none z-[2]" />
      <div className="absolute top-1/2 left-[30%] w-3 h-3 bg-amber-300/40 rounded-full pointer-events-none z-[2]" />

      {/* ── Main content ── */}
      <div
        className="relative z-[10] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center py-6 sm:py-8 lg:py-10"
        style={{ minHeight: 'inherit' }}
      >
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 xl:gap-12 items-center w-full">

          {/* ══════════════════════════════
              LEFT — Static Text Content (auto-rotates independently)
          ══════════════════════════════ */}
          <div
            className={`text-center lg:text-left transition-all duration-700 ease-out max-w-2xl mx-auto lg:mx-0 ${
              visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
            }`}
          >
            {/* Gradient badge */}
            <div className="inline-flex items-center gap-2 mb-2 sm:mb-3 md:mb-4">
              <span className="inline-flex items-center gap-1.5 sm:gap-2 bg-gradient-to-r from-amber-600 to-amber-500 text-white text-[10px] sm:text-xs font-bold uppercase tracking-wide sm:tracking-widest px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-lg">
                <FiStar size={9} className="sm:w-[10px] sm:h-[10px]" />
                {textSlide.badge}
              </span>
            </div>

            {/* Heading */}
            <h1 className="font-extrabold leading-[1.15] text-white mb-3 sm:mb-4 text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-[3.5rem]">
              {(() => {
                const words = textSlide.title ? textSlide.title.split(' ') : ['Welcome', 'to', 'KICS'];
                const mid = Math.ceil(words.length / 2);
                return (
                  <>
                    {words.slice(0, mid).join(' ')}
                    <br />
                    <span
                      className="bg-clip-text text-transparent"
                      style={{ backgroundImage: 'linear-gradient(135deg, #F59E0B, #D97706)' }}
                    >
                      {words.slice(mid).join(' ')}
                    </span>
                  </>
                );
              })()}
            </h1>

            {/* Subtitle */}
            <p className="text-blue-100 text-xs sm:text-sm md:text-base lg:text-lg leading-relaxed max-w-sm sm:max-w-md lg:max-w-lg mx-auto lg:mx-0 mb-3 sm:mb-4">
              {textSlide.subtitle}
            </p>

            {/* Typewriter tagline */}
            <div className="flex items-center gap-1.5 sm:gap-2 justify-center lg:justify-start mb-4 sm:mb-5">
              <span className="text-amber-400 text-xs sm:text-sm font-semibold">&#9670;</span>
              <span className="text-blue-100 text-xs sm:text-sm font-medium min-w-[140px] sm:min-w-[180px] md:min-w-[220px] inline-block">
                {typedText}
                <span className="inline-block w-0.5 h-3 sm:h-4 bg-amber-400 ml-0.5 align-middle animate-pulse" />
              </span>
            </div>

            {/* CTA Buttons */}
            <div className="flex flex-col sm:flex-row flex-wrap gap-2.5 sm:gap-3 md:gap-4 justify-center lg:justify-start mb-4 sm:mb-5 md:mb-6">
              <Link
                to="/about"
                className="group inline-flex items-center justify-center gap-2 text-white font-bold px-6 sm:px-7 md:px-8 py-3 sm:py-3.5 md:py-4 rounded-full shadow-xl hover:shadow-2xl hover:shadow-amber-500/50 hover:-translate-y-1 hover:scale-105 transition-all duration-300 text-sm sm:text-base"
                style={{ background: 'linear-gradient(135deg, #D97706, #F59E0B)' }}
              >
                Discover KICS
                <FiArrowRight size={15} className="group-hover:translate-x-1 transition-transform duration-200 sm:w-4 sm:h-4" />
              </Link>
              <Link
                to="/research-areas"
                className="inline-flex items-center justify-center gap-2 bg-white/15 hover:bg-white/25 text-white font-bold px-6 sm:px-7 md:px-8 py-3 sm:py-3.5 md:py-4 rounded-full border-2 border-white/40 hover:border-white/80 hover:-translate-y-1 hover:scale-105 transition-all duration-300 text-sm sm:text-base backdrop-blur-md shadow-lg"
              >
                Our Research
              </Link>
            </div>

            {/* Stats row */}
            <div className="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-x-3 gap-y-3 sm:gap-x-4 md:gap-x-6 justify-center lg:justify-start bg-white/5 backdrop-blur-sm rounded-xl sm:rounded-2xl px-3 py-2.5 sm:px-4 sm:py-3 md:px-5 md:py-4 border border-white/10">
              {stats.map(({ icon: Icon, value, label }, i) => (
                <div key={label} className="flex items-center gap-2">
                  {i > 0 && <span className="hidden lg:block w-px h-10 bg-white/20" />}
                  <div className="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-amber-500/20 to-amber-600/20 border border-amber-400/30 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <Icon size={14} className="text-amber-400 sm:w-[15px] sm:h-[15px] md:w-4 md:h-4" />
                  </div>
                  <div>
                    <p className="text-white font-extrabold text-sm sm:text-base md:text-lg leading-none">{value}</p>
                    <p className="text-blue-200 text-[10px] sm:text-xs mt-0.5 sm:mt-1">{label}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* ══════════════════════════════
              RIGHT — News Carousel (controlled by arrows)
          ══════════════════════════════ */}
          {/* ══════════════════════════════
    RIGHT — News Carousel (controlled by arrows)
══════════════════════════════ */}
<div
  className={`flex justify-center lg:justify-end transition-all duration-700 delay-200 ease-out order-first lg:order-last ${
    visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
  }`}
>
  <div className="relative w-full max-w-[320px] sm:max-w-[380px] md:max-w-[420px] lg:max-w-[460px]">

    {/* Rotated decorative frame layers */}
    <div className="absolute inset-0 bg-gradient-to-br from-amber-500/30 to-blue-600/30 rounded-3xl rotate-3 scale-105" />
    <div className="absolute inset-0 bg-white/10 rounded-3xl -rotate-1 scale-[1.02]" />
    <div className="absolute -inset-5 bg-gradient-to-br from-amber-400/20 via-blue-500/15 to-amber-300/10 rounded-[2rem] blur-2xl pointer-events-none" />

    {/* Floating badge — top-left */}
    {/* Floating badge — top-left (DYNAMIC) */}
{(() => {
  const ESTABLISHED_YEAR = 2002;
  const currentYear = new Date().getFullYear();
  const yearsStrong = currentYear - ESTABLISHED_YEAR;
  
  return (
    <div className="absolute -top-3 sm:-top-4 md:-top-6 -left-2 sm:-left-3 md:-left-6 z-20 bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-xl px-2 sm:px-2.5 md:px-3.5 py-1.5 sm:py-2 md:py-2.5 flex items-center gap-1.5 sm:gap-2 md:gap-2.5 border border-amber-200 animate-bounce-gentle hidden sm:flex">
      <div
        className="w-8 sm:w-9 h-8 sm:h-9 rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm"
        style={{ background: 'linear-gradient(135deg, #B45309, #D97706)' }}
      >
        <FiAward size={14} className="text-white sm:w-[15px] sm:h-[15px]" />
      </div>
      <div>
        <p className="text-slate-900 font-extrabold text-[11px] sm:text-xs leading-none">Est. {ESTABLISHED_YEAR}</p>
        <p className="text-primary-600 text-[9px] sm:text-[10px] mt-0.5 font-semibold">{yearsStrong} Years</p>
      </div>
    </div>
  );
})()}

    {/* Floating badge — bottom-right */}
    <div
      className="absolute -bottom-3 sm:-bottom-4 md:-bottom-6 -right-2 sm:-right-3 md:-right-6 z-20 bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-xl px-2 sm:px-2.5 md:px-3.5 py-1.5 sm:py-2 md:py-2.5 flex items-center gap-1.5 sm:gap-2 md:gap-2.5 border border-blue-100 animate-bounce-gentle hidden sm:flex"
      style={{ animationDelay: '1s' }}
    >
      <div className="w-8 sm:w-9 h-8 sm:h-9 rounded-lg sm:rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center shadow-sm">
        <FiBookOpen size={14} className="text-white sm:w-[15px] sm:h-[15px]" />
      </div>
      <div>
        <p className="text-slate-900 font-extrabold text-[11px] sm:text-xs leading-none">500+ Papers</p>
        <p className="text-blue-600 text-[9px] sm:text-[10px] mt-0.5 font-semibold">IEEE · ACM · Elsevier</p>
      </div>
    </div>

    {/* Side pill badge */}
    <div
      className="absolute top-1/2 -right-4 xl:-right-5 -translate-y-1/2 z-20 rounded-lg xl:rounded-xl px-2 xl:px-2.5 py-2.5 xl:py-3 shadow-lg hidden xl:flex flex-col items-center gap-1"
      style={{ background: 'linear-gradient(180deg, #0B1833, #1d4ed8)' }}
    >
      <FiUsers size={12} className="text-amber-300 xl:w-[13px] xl:h-[13px]" />
      <p className="text-white font-extrabold text-xs xl:text-sm leading-none">1000+</p>
      <p className="text-amber-300 text-[8px] xl:text-[9px] font-semibold uppercase tracking-wide" style={{ writingMode: 'vertical-rl' }}>Trained</p>
    </div>

    {/* ════════════════════════════
        NEWS IMAGE WITH TITLE ONLY
    ════════════════════════════ */}
    <Link
      to={newsSlide.link || '/news'}
      className="block relative z-10 rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border-2 sm:border-4 border-white animate-float"
      aria-label={`Read more about ${newsSlide.title}`}
    >
      <img
        src={newsSlide.image || kicsBg}
        alt={newsSlide.title}
        width="600"
        height="400"
        {...getImageLoadingProps({ eager: newsIndex === 0, priority: newsIndex === 0 ? 'high' : 'low', sizes: '(min-width: 1024px) 460px, 100vw' })}
        className={`w-full h-[180px] sm:h-[200px] md:h-[240px] lg:h-[280px] object-cover object-center transition-opacity duration-500 ${
          animating ? 'opacity-0' : 'opacity-100'
        }`}
        onError={e => {
          e.target.src = kicsBg;
        }}
      />
      
      {/* ════════════════════════════
          NEWS TITLE OVERLAY - Bottom 
          (Title ONLY - No Excerpt)
      ════════════════════════════ */}
      <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-[#0B1833]/95 via-[#0B1833]/70 to-transparent p-3 sm:p-4 transition-all duration-500">
        <div className="flex items-start gap-2">
          {/* Yellow accent bar */}
          <span className="w-1 h-8 sm:h-10 bg-amber-400 rounded-full flex-shrink-0 mt-0.5"></span>
          <div className="flex-1 min-w-0">
            {/* News counter */}
            <span className="text-[8px] sm:text-[9px] text-amber-400 font-semibold uppercase tracking-wider">
              {newsIndex + 1} / {newsSlides.length} • Latest News
            </span>
            
            {/* ⭐ NEWS TITLE ONLY - No excerpt */}
            <h3 className="text-white font-bold text-xs sm:text-sm md:text-base leading-tight line-clamp-2 transition-all duration-300">
              {newsSlide.title}
            </h3>
            
            {/* Read more link */}
            <span className="text-[10px] text-amber-400 mt-1 inline-flex items-center gap-1 hover:gap-2 transition-all">
              Read more <FiArrowRight size={10} className="inline" />
            </span>
          </div>
        </div>
      </div>
    </Link>

    {/* News Slide Controls - Arrows and Dots */}
    <div className="flex items-center justify-between gap-2 mt-2 sm:mt-3">
      <button
        onClick={prevNews}
        className="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 flex items-center justify-center transition-all hover:scale-110"
        aria-label="Previous news"
      >
        <FiChevronLeft size={14} className="text-white sm:w-[16px] sm:h-[16px]" />
      </button>

      <div
        className="flex items-center gap-1 sm:gap-1.5"
        role="group"
        aria-label="News navigation"
      >
        {newsSlides.map((_, i) => (
          <button
            key={i}
            onClick={() => goToNews(i)}
            aria-label={`Go to news ${i + 1}`}
            aria-current={i === newsIndex ? 'true' : 'false'}
            className={`transition-all duration-300 rounded-full flex-shrink-0 ${
              i === newsIndex
                ? 'w-4 sm:w-5 h-1 sm:h-1.5 bg-amber-400'
                : 'w-1 sm:w-1.5 h-1 sm:h-1.5 bg-white/40 hover:bg-white/70'
            }`}
          />
        ))}
      </div>

      <button
        onClick={nextNews}
        className="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 flex items-center justify-center transition-all hover:scale-110"
        aria-label="Next news"
      >
        <FiChevronRight size={14} className="text-white sm:w-[16px] sm:h-[16px]" />
      </button>
    </div>
  </div>
</div>

        </div>
      </div>

      {/* Wave bottom divider */}
      <div className="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-[20]">
        <svg className="relative block w-full h-8 sm:h-10" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="#0B1833"></path>
        </svg>
      </div>
    </section>
  );
}