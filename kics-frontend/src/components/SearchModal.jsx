import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { FiSearch, FiX, FiArrowRight } from 'react-icons/fi';

// Searchable content database
const searchableContent = [
  // Pages
  { title: 'Home', path: '/', type: 'Page', keywords: 'home main index landing' },
  { title: 'About KICS', path: '/about', type: 'Page', keywords: 'about history vision mission al-khwarizmi institute' },
  { title: "Director's Message", path: '/director-message', type: 'Page', keywords: 'director message leadership' },
  { title: 'ERP Departments', path: '/erp-departments', type: 'Page', keywords: 'erp departments labs staff teams employees' },
  { title: 'Research Areas', path: '/research-areas', type: 'Page', keywords: 'research labs ai machine learning cybersecurity iot' },
  { title: 'Publications', path: '/publications', type: 'Page', keywords: 'publications papers research journals ieee' },
  { title: 'Conferences', path: '/conferences', type: 'Page', keywords: 'conferences icosst events ieee' },
  { title: 'Workshops', path: '/workshops', type: 'Page', keywords: 'workshops training seminars events' },
  { title: 'ICOSST', path: '/icosst', type: 'Page', keywords: 'icosst conference open source systems technologies' },
  { title: 'Jobs at KICS', path: '/jobs', type: 'Page', keywords: 'jobs careers employment openings positions' },
  { title: 'Contact Us', path: '/contact', type: 'Page', keywords: 'contact address phone email location uet lahore' },
  { title: 'Services', path: '/services', type: 'Page', keywords: 'services solutions erp software development' },
  { title: 'News & Media', path: '/news', type: 'Page', keywords: 'news media announcements events updates' },
  { title: 'e-Rozgar Program', path: '/e-rozgaar', type: 'Program', keywords: 'e-rozgar erozgaar training courses freelancing pitb' },

  // Research areas
  { title: 'AI & Machine Learning', path: '/research-areas', type: 'Research', keywords: 'artificial intelligence machine learning deep learning' },
  { title: 'Cybersecurity & Networks', path: '/research-areas', type: 'Research', keywords: 'cybersecurity security networks wireless 5g' },
  { title: 'IoT & Smart Cities', path: '/research-areas', type: 'Research', keywords: 'iot internet of things smart cities sensors' },
  { title: 'Software Engineering', path: '/research-areas', type: 'Research', keywords: 'software engineering development erp applications' },
  { title: 'Data Science', path: '/research-areas', type: 'Research', keywords: 'data science analytics big data business intelligence' },
  { title: 'Embedded Systems & Robotics', path: '/research-areas', type: 'Research', keywords: 'embedded systems robotics automation hardware' },

  // Services
  { title: 'Software Development', path: '/services', type: 'Service', keywords: 'software development web mobile apps custom' },
  { title: 'ERP Solutions', path: '/services', type: 'Service', keywords: 'erp enterprise resource planning campus solution' },
  { title: 'Cybersecurity Services', path: '/services', type: 'Service', keywords: 'cybersecurity penetration testing security audit' },
  { title: 'Professional Training', path: '/services', type: 'Service', keywords: 'training courses workshops certification' },
];

export default function SearchModal({ isOpen, onClose }) {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const [selectedIndex, setSelectedIndex] = useState(0);
  const inputRef = useRef(null);

  // Focus input when modal opens
  useEffect(() => {
    if (isOpen && inputRef.current) {
      inputRef.current.focus();
    }
  }, [isOpen]);

  // Close on ESC key
  useEffect(() => {
    const handleEsc = (e) => {
      if (e.key === 'Escape') onClose();
    };
    window.addEventListener('keydown', handleEsc);
    return () => window.removeEventListener('keydown', handleEsc);
  }, [onClose]);

  // Search logic
  useEffect(() => {
    if (!query.trim()) {
      setResults([]);
      setSelectedIndex(0);
      return;
    }

    const searchTerm = query.toLowerCase();
    const filtered = searchableContent
      .filter(item => {
        const titleMatch = item.title.toLowerCase().includes(searchTerm);
        const keywordMatch = item.keywords.toLowerCase().includes(searchTerm);
        const typeMatch = item.type.toLowerCase().includes(searchTerm);
        return titleMatch || keywordMatch || typeMatch;
      })
      .slice(0, 8); // Limit to 8 results

    setResults(filtered);
    setSelectedIndex(0);
  }, [query]);

  // Keyboard navigation
  useEffect(() => {
    const handleKeyDown = (e) => {
      if (!isOpen || results.length === 0) return;

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        setSelectedIndex(prev => (prev + 1) % results.length);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        setSelectedIndex(prev => (prev - 1 + results.length) % results.length);
      } else if (e.key === 'Enter' && results[selectedIndex]) {
        e.preventDefault();
        handleResultClick(results[selectedIndex].path);
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [isOpen, results, selectedIndex]);

  const handleResultClick = (path) => {
    onClose();
    setQuery('');
  };

  if (!isOpen) return null;

  return (
    <div
      className="fixed inset-0 z-[100] flex items-start justify-center pt-20 px-4"
      onClick={onClose}
    >
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-slate-900/60 backdrop-blur-sm animate-fadeIn"
        style={{ animation: 'fadeIn 0.2s ease-out' }}
      />

      {/* Search Modal */}
      <div
        className="relative w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden animate-slideInUp"
        style={{
          background: 'rgba(255,255,255,0.95)',
          backdropFilter: 'blur(20px)',
          WebkitBackdropFilter: 'blur(20px)',
          border: '1px solid rgba(37,99,235,0.2)',
          animation: 'slideInUp 0.3s cubic-bezier(0.16,1,0.3,1)',
        }}
        onClick={(e) => e.stopPropagation()}
      >
        {/* Search Input */}
        <div className="flex items-center gap-3 px-6 py-5 border-b border-slate-200">
          <FiSearch className="text-primary-600 flex-shrink-0" size={20} />
          <input
            ref={inputRef}
            type="text"
            placeholder="Search pages, research areas, services..."
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            className="flex-1 bg-transparent text-slate-900 placeholder:text-slate-400 outline-none text-base"
          />
          <button
            onClick={onClose}
            className="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
            aria-label="Close search"
          >
            <FiX size={18} />
          </button>
        </div>

        {/* Results */}
        <div className="max-h-96 overflow-y-auto">
          {query && results.length === 0 && (
            <div className="px-6 py-12 text-center">
              <p className="text-slate-500 text-sm">No results found for "{query}"</p>
              <p className="text-slate-400 text-xs mt-2">Try a different search term</p>
            </div>
          )}

          {results.length > 0 && (
            <div className="py-2">
              {results.map((item, index) => (
                <Link
                  key={index}
                  to={item.path}
                  onClick={() => handleResultClick(item.path)}
                  className={`flex items-center justify-between px-6 py-3.5 transition-colors ${
                    index === selectedIndex
                      ? 'bg-primary-50 border-l-2 border-primary-600'
                      : 'hover:bg-slate-50 border-l-2 border-transparent'
                  }`}
                >
                  <div className="flex-1">
                    <p
                      className={`font-semibold text-sm ${
                        index === selectedIndex ? 'text-primary-700' : 'text-slate-900'
                      }`}
                    >
                      {item.title}
                    </p>
                    <p className="text-xs text-slate-500 mt-0.5">
                      <span className="inline-block px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-medium uppercase tracking-wide">
                        {item.type}
                      </span>
                    </p>
                  </div>
                  <FiArrowRight
                    className={`flex-shrink-0 ${
                      index === selectedIndex ? 'text-primary-600' : 'text-slate-400'
                    }`}
                    size={16}
                  />
                </Link>
              ))}
            </div>
          )}

          {!query && (
            <div className="px-6 py-8">
              <p className="text-slate-400 text-xs uppercase tracking-wider font-semibold mb-3">
                Quick Links
              </p>
              <div className="grid grid-cols-2 gap-2">
                {[
                  { label: 'Research Areas', path: '/research-areas' },
                  { label: 'Publications', path: '/publications' },
                  { label: 'Jobs', path: '/jobs' },
                  { label: 'Contact', path: '/contact' },
                ].map((link, i) => (
                  <Link
                    key={i}
                    to={link.path}
                    onClick={() => handleResultClick(link.path)}
                    className="flex items-center justify-between px-4 py-2.5 rounded-lg bg-slate-50 hover:bg-primary-50 text-slate-700 hover:text-primary-700 text-sm font-medium transition-colors"
                  >
                    {link.label}
                    <FiArrowRight size={14} />
                  </Link>
                ))}
              </div>
            </div>
          )}
        </div>

        {/* Footer hint */}
        <div className="px-6 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
          <span>Use ↑↓ to navigate • Enter to select • ESC to close</span>
        </div>
      </div>

      <style>{`
        @keyframes fadeIn {
          from { opacity: 0; }
          to { opacity: 1; }
        }
        @keyframes slideInUp {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
      `}</style>
    </div>
  );
}
