import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { FiCalendar, FiArrowLeft, FiShare2, FiTag } from 'react-icons/fi';
import { FaFacebook, FaTwitter, FaLinkedin, FaWhatsapp } from 'react-icons/fa';
import PageHero from '../components/PageHero';
import SEO from '../components/SEO';
import { newsService } from '../services';
import { buildImageUrl } from '../utils/contentMappers';

export default function NewsDetail() {
  const { id } = useParams();
  const [news, setNews] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [imageLoaded, setImageLoaded] = useState(false);

  useEffect(() => {
    setLoading(true);
    setError('');
    setImageLoaded(false);

    const fetchNews = async () => {
      try {
        const data = await newsService.get(id);
        if (data) {
          // Ensure image has full URL
          if (data.image) {
            data.image = buildImageUrl(data.image);
          }
          setNews(data);
        } else {
          setError('News article not found.');
        }
      } catch (err) {
        setError('Unable to load the news article. Please try again.');
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    fetchNews();
  }, [id]);

  // Share functionality
  const shareUrl = window.location.href;
  const shareTitle = news?.title || 'Check out this news from KICS!';

  const shareLinks = {
    facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
    twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareTitle)}&url=${encodeURIComponent(shareUrl)}`,
    linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`,
    whatsapp: `https://api.whatsapp.com/send?text=${encodeURIComponent(shareTitle + ' ' + shareUrl)}`,
  };

  // ── LOADING STATE ──
  if (loading) {
    return (
      <div>
        <SEO title="Loading..." description="Loading news article..." noIndex />
        <PageHero title="Loading..." subtitle="Please wait..." />
        
        <section className="pt-4 pb-16 bg-white">
          <div className="max-w-4xl mx-auto px-4 sm:px-6">
            <div className="animate-pulse">
              <div className="w-full h-64 sm:h-80 md:h-96 bg-slate-200 rounded-2xl mb-6" />
              <div className="h-8 bg-slate-200 rounded-lg w-3/4 mb-3" />
              <div className="h-8 bg-slate-200 rounded-lg w-1/2 mb-4" />
              <div className="flex items-center gap-4 mb-6">
                <div className="h-4 bg-slate-200 rounded w-24" />
                <div className="h-4 bg-slate-200 rounded w-32" />
              </div>
              <div className="space-y-3">
                <div className="h-4 bg-slate-200 rounded w-full" />
                <div className="h-4 bg-slate-200 rounded w-full" />
                <div className="h-4 bg-slate-200 rounded w-3/4" />
                <div className="h-4 bg-slate-200 rounded w-full" />
                <div className="h-4 bg-slate-200 rounded w-5/6" />
              </div>
            </div>
          </div>
        </section>
      </div>
    );
  }

  // ── ERROR STATE ──
  if (error || !news) {
    return (
      <div>
        <SEO title="News Not Found" description="The requested news article could not be found." noIndex />
        <PageHero title="News Not Found" subtitle="We couldn't find the article you're looking for." />
        
        <section className="pt-4 pb-16 bg-white">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <div className="bg-red-50 border border-red-200 rounded-2xl p-8">
              <p className="text-red-600 text-lg font-semibold mb-4">
                {error || 'News article not found.'}
              </p>
              <Link to="/news" className="btn-primary inline-flex items-center gap-2">
                <FiArrowLeft size={16} /> Back to News
              </Link>
            </div>
          </div>
        </section>
      </div>
    );
  }

  // ── CONTENT STATE ──
  return (
    <div>
      <SEO
        title={news.title}
        description={news.excerpt || news.description?.slice(0, 160)}
        image={news.image || null}
        breadcrumbs={[{ label: 'News', url: '/news' }, { label: news.title }]}
      />

      <PageHero
        title="News & Events"
        subtitle="Stay updated with the latest happenings at KICS"
        breadcrumbs={[{ label: 'Home', to: '/' }, { label: 'News', to: '/news' }, { label: news.title }]}
      />

      <section className="pt-4 pb-16 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          
          {/* Back Button */}
          <Link
            to="/news"
            className="inline-flex items-center gap-2 text-slate-500 hover:text-primary-600 transition-colors mb-6 group"
          >
            <FiArrowLeft size={16} className="group-hover:-translate-x-1 transition-transform" />
            Back to News
          </Link>

          {/* ── Featured Image ── */}
          {news.image && (
            <div className="relative w-full h-64 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg mb-6 bg-slate-100">
              <img
                src={news.image}
                alt={news.title}
                className={`w-full h-full object-cover transition-opacity duration-300 ${
                  imageLoaded ? 'opacity-100' : 'opacity-0'
                }`}
                onLoad={() => setImageLoaded(true)}
                onError={(e) => {
                  e.target.src = 'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=800&h=450&fit=crop';
                  setImageLoaded(true);
                }}
              />
              {!imageLoaded && (
                <div className="absolute inset-0 bg-gradient-to-r from-slate-200 via-slate-100 to-slate-200 animate-shimmer" />
              )}
            </div>
          )}

          {/* ── Title ── */}
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 leading-tight">
            {news.title}
          </h1>

          {/* ── Meta Info ── */}
          <div className="flex flex-wrap items-center gap-4 text-sm text-slate-500 border-b border-slate-200 pb-4 mb-6">
            <span className="flex items-center gap-1.5">
              <FiCalendar size={14} />
              {news.date || 'Date not specified'}
            </span>
            {news.category && (
              <span className="flex items-center gap-1.5 bg-primary-50 text-primary-700 px-3 py-1 rounded-full">
                <FiTag size={14} />
                {news.category}
              </span>
            )}
          </div>

          {/* ── Content ── */}
          <div className="prose prose-slate max-w-none prose-headings:text-slate-900 prose-p:text-slate-600 prose-p:leading-relaxed prose-p:mb-4 prose-strong:text-slate-800">
            {news.description ? (
              <div dangerouslySetInnerHTML={{ __html: news.description }} />
            ) : (
              <p className="text-slate-500 italic">No content available for this article.</p>
            )}
          </div>

          {/* ── Share Section ── */}
          <div className="mt-10 pt-6 border-t border-slate-200">
            <div className="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
              <span className="text-sm font-semibold text-slate-700 flex items-center gap-2">
                <FiShare2 size={16} />
                Share this article:
              </span>
              <div className="flex flex-wrap gap-2">
                <a
                  href={shareLinks.facebook}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 rounded-full bg-[#1877f2] hover:bg-[#166fe5] text-white flex items-center justify-center transition-all hover:scale-110 shadow-md"
                  aria-label="Share on Facebook"
                >
                  <FaFacebook size={18} />
                </a>
                <a
                  href={shareLinks.twitter}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 rounded-full bg-[#000] hover:bg-[#1a1a1a] text-white flex items-center justify-center transition-all hover:scale-110 shadow-md"
                  aria-label="Share on Twitter"
                >
                  <FaTwitter size={18} />
                </a>
                <a
                  href={shareLinks.linkedin}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 rounded-full bg-[#0a66c2] hover:bg-[#0958a9] text-white flex items-center justify-center transition-all hover:scale-110 shadow-md"
                  aria-label="Share on LinkedIn"
                >
                  <FaLinkedin size={18} />
                </a>
                <a
                  href={shareLinks.whatsapp}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 rounded-full bg-[#25D366] hover:bg-[#1da851] text-white flex items-center justify-center transition-all hover:scale-110 shadow-md"
                  aria-label="Share on WhatsApp"
                >
                  <FaWhatsapp size={18} />
                </a>
                <button
                  onClick={() => {
                    navigator.clipboard.writeText(shareUrl);
                    alert('Link copied to clipboard!');
                  }}
                  className="w-10 h-10 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center transition-all hover:scale-110 shadow-md"
                  aria-label="Copy link"
                >
                  <FiShare2 size={16} />
                </button>
              </div>
            </div>
          </div>

          {/* ── Back Navigation ── */}
          <div className="mt-8 pt-6 border-t border-slate-200">
            <Link
              to="/news"
              className="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold transition-colors group"
            >
              <FiArrowLeft size={16} className="group-hover:-translate-x-1 transition-transform" />
              All News
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
}