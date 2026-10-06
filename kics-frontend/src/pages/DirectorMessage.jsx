import { useEffect, useMemo, useState } from 'react';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import { Link } from 'react-router-dom';
import { FiMail, FiPhone, FiMapPin, FiUser } from 'react-icons/fi';
import SEO from '../components/SEO';
import { directorService } from '../services';
import { stripHtml } from '../utils/contentMappers';
import directorMessageBanner from '../assets/images/director-message-banner.jpg';

// Import director image
import directorImage from '../assets/images/dir-pic.png';

export default function DirectorMessage() {
  const [message, setMessage] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    const fetchDirectorMessage = async () => {
      try {
        setLoading(true);
        setError('');
        
        console.log('🔍 Fetching director message...');
        const data = await directorService.latest();
        console.log('✅ Director message response:', data);
        
        if (active) {
          if (Array.isArray(data) && data.length > 0) {
            setMessage(data[0]);
          } else if (data && typeof data === 'object' && !Array.isArray(data)) {
            setMessage(data);
          } else {
            setError('No director message found.');
          }
        }
      } catch (err) {
        console.error('❌ Error fetching director message:', err);
        if (active) {
          setError('Unable to load the latest Director message. Showing fallback content.');
        }
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchDirectorMessage();

    return () => { active = false; };
  }, []);

  // Extract message paragraphs from HTML content
  const messageParagraphs = useMemo(() => {
    if (!message) return [];
    
    const text = message.section_message || message.message || '';
    const cleanText = stripHtml(text);
    if (!cleanText) return [];
    
    const paragraphs = cleanText
      .split(/\n{2,}|(?<=\.)\s+(?=[A-Z])/)
      .map((item) => item.trim())
      .filter(Boolean);
    
    console.log('📝 Extracted paragraphs:', paragraphs.length);
    return paragraphs;
  }, [message]);

  // Extract signature
  const signature = useMemo(() => {
    if (!message || messageParagraphs.length === 0) return 'Director KICS';
    
    const text = stripHtml(message.section_message || '');
    if (!text) return 'Director KICS';
    
    const lines = text.split('\n').map(l => l.trim()).filter(Boolean);
    const lastLine = lines[lines.length - 1];
    
    if (lastLine && (lastLine.includes('Director') || lastLine.includes('KICS'))) {
      return lastLine;
    }
    
    return 'Director KICS';
  }, [message, messageParagraphs]);

  // Get quote paragraph
  const quoteParagraph = useMemo(() => {
    if (messageParagraphs.length > 0) {
      const quote = messageParagraphs.find(p => 
        p.includes('strive') || 
        p.includes('vision') || 
        p.includes('mission')
      );
      return quote || messageParagraphs[0];
    }
    return '';
  }, [messageParagraphs]);

  return (
    <div>
      <SEO
        title="Director's Message"
        description="Read the Director's message from Prof. Dr. Shahzad Asif about KICS vision, mission and Pakistan's technological future."
        breadcrumbs={[{ label: 'About', url: '/about' }, { label: "Director's Message", url: '/director-message' }]}
      />
      <PageHero
        title="Director's Message"
        subtitle="A vision for research excellence, innovation, and Pakistan's technological future."
        breadcrumbs={[{ label: 'About', to: '/about' }, { label: "Director's Message" }]}
        backgroundImage={directorMessageBanner}
      />

      <section className="py-20 bg-white">
        <div className="max-w-6xl mx-auto px-4 sm:px-6">
          <div className="grid lg:grid-cols-3 gap-10">
            
            {/* ─── LEFT COLUMN: Director Profile with Professional Image ─── */}
            <AnimateOnScroll animation="reveal-left">
              <div className="card p-6 text-center sticky top-28">
                
                {/* ─── Professional Director Image ─── */}
                <div className="flex justify-center mb-4">
                  <div className="relative">
                    {/* Outer glow ring */}
                    <div className="absolute -inset-6 bg-gradient-to-br from-primary-400/30 via-amber-400/20 to-primary-600/30 rounded-full blur-2xl animate-pulse-slow" />
                    
                    {/* Decorative ring 1 - Outer */}
                    <div className="absolute -inset-3 rounded-full border-2 border-primary-200/50" />
                    
                    {/* Decorative ring 2 - Inner */}
                    <div className="absolute -inset-1 rounded-full border border-primary-300/30" />
                    
                    {/* Main Image Container */}
                    <div className="relative rounded-full overflow-hidden shadow-2xl border-4 border-white w-32 h-32 sm:w-36 sm:h-36">
                      <img
                        src={directorImage}
                        alt="Prof. Dr. Shahzad Asif - Director KICS"
                        className="w-full h-full object-cover object-center scale-105 hover:scale-110 transition-transform duration-700"
                        onError={(e) => {
                          e.target.src = 'https://ui-avatars.com/api/?name=Prof.+Dr.+Shahzad+Asif&size=200&background=1d4ed8&color=fff&font-size=0.5';
                        }}
                      />
                      
                      {/* Subtle gradient overlay at bottom */}
                      <div className="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-primary-900/60 to-transparent" />
                    </div>

                    {/* Small decorative corner elements */}
                    <div className="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full shadow-lg" />
                    <div className="absolute -bottom-1 -left-1 w-3 h-3 bg-gradient-to-br from-primary-400 to-primary-500 rounded-full shadow-lg" />
                  </div>
                </div>

                <h3 className="font-heading font-bold text-primary-800 text-lg">Prof. Dr. Shahzad Asif</h3>
                <p className="text-cyan-500 text-sm font-medium mt-1">Director, KICS</p>
                <p className="text-slate-400 text-xs mt-1">UET Lahore, Pakistan</p>
                
                <div className="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-600 text-left">
                  <p className="flex items-center gap-2">
                    <FiMail size={12} className="text-primary-600 flex-shrink-0" /> 
                    director@kics.edu.pk
                  </p>
                  <p className="flex items-center gap-2">
                    <FiPhone size={12} className="text-primary-600 flex-shrink-0" /> 
                    +92 42 99029450
                  </p>
                  <p className="flex items-center gap-2">
                    <FiMapPin size={12} className="text-primary-600 flex-shrink-0" /> 
                    KICS, UET G.T. Road, Lahore
                  </p>
                </div>

                {/* Read Full Message button in profile card */}
                <div className="mt-4 pt-4 border-t border-slate-100">
                  <Link to="/director-message" className="text-primary-600 text-sm font-semibold hover:text-primary-700 transition-colors">
                    Read Full Message →
                  </Link>
                </div>
              </div>
            </AnimateOnScroll>

            {/* ─── RIGHT COLUMN: Message Content ─── */}
            <AnimateOnScroll animation="reveal-right" className="lg:col-span-2">
              <span className="eyebrow">A Word from our Director</span>
              <h2 className="section-title mb-3">Welcome to Al-Khwarizmi Institute of Computer Science</h2>
              <div className="divider" />

              {loading && (
                <div className="mb-5 text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
                  Loading latest Director message...
                </div>
              )}
              {error && (
                <div className="mb-5 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
                  {error}
                </div>
              )}

              {/* Quote Block */}
              {quoteParagraph && (
                <div className="mt-8 p-6 bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl border-l-4 border-primary-600">
                  {/* <p className="text-slate-700 font-medium italic text-lg">
                    "{quoteParagraph}"
                  </p> */}
                  <p className="text-slate-500 text-sm">{signature}</p>
                  <p className="text-primary-800 font-bold mt-3 text-base text-right">
  — Prof. Dr. Shahzad Asif
</p>
                </div>
              )}

              <div className="mt-8 flex flex-wrap gap-3">
                <Link to="/kics-departments" className="btn-primary">KICS Departments</Link>
                <Link to="/about" className="btn-primary">About KICS</Link>
              </div>
            </AnimateOnScroll>
          </div>
        </div>
      </section>
    </div>
  );
}
