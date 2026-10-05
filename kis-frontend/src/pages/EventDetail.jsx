import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { FiCalendar, FiMapPin, FiClock, FiArrowLeft, FiShare2, FiMail, FiPhone, FiExternalLink, FiUsers } from 'react-icons/fi';
import { FaFacebook, FaTwitter, FaLinkedin, FaWhatsapp } from 'react-icons/fa';
import SEO from '../components/SEO';
import LoadingScreen from '../components/LoadingScreen';
import { eventService } from '../services/eventService';
import { buildImageUrl } from '../utils/contentMappers';
import { formatDate, getTime, getStatusColor, getStatusLabel, getTypeIcon, getTypeLabel } from '../utils/dateUtils';

export default function EventDetail() {
  const { slug } = useParams();
  const [event, setEvent] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [imageLoaded, setImageLoaded] = useState(false);

  useEffect(() => {
    fetchEvent();
  }, [slug]);

  const fetchEvent = async () => {
    try {
      setLoading(true);
      setError('');
      const data = await eventService.get(slug);
      setEvent(data);
    } catch (err) {
      setError('Event not found or unavailable.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const shareUrl = window.location.href;
  const shareTitle = event?.title || 'Check out this event at KICS!';

  const shareLinks = {
    facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
    twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareTitle)}&url=${encodeURIComponent(shareUrl)}`,
    linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`,
    whatsapp: `https://api.whatsapp.com/send?text=${encodeURIComponent(shareTitle + ' ' + shareUrl)}`,
  };

  if (loading) return <LoadingScreen />;

  if (error || !event) {
    return (
      <div className="min-h-[60vh] flex flex-col items-center justify-center px-4">
        <h2 className="text-2xl font-bold text-slate-800 mb-2">Event Not Found</h2>
        <p className="text-slate-500 mb-6">{error || 'The event you\'re looking for does not exist.'}</p>
        <Link to="/events" className="btn-primary inline-flex items-center gap-2">
          <FiArrowLeft size={16} /> Back to Events
        </Link>
      </div>
    );
  }

  const statusColor = getStatusColor(event.event_status);
  const statusLabel = getStatusLabel(event.event_status);
  const typeIcon = getTypeIcon(event.event_type);
  const typeLabel = getTypeLabel(event.event_type);
  
  // Use buildImageUrl for images
  const featuredImage = event.featured_image ? buildImageUrl(event.featured_image) : null;
  const galleryImages = event.gallery_images?.map(img => buildImageUrl(img)) || [];

  return (
    <div>
      <SEO
        title={event.meta_title || event.title}
        description={event.meta_description || event.description?.slice(0, 160)}
        image={featuredImage}
      />

      {/* Hero Section */}
      <section className="relative bg-gradient-to-r from-blue-900 to-blue-700 text-white py-16 overflow-hidden">
        <div className="absolute inset-0 opacity-30">
          {featuredImage && (
            <img
              src={featuredImage}
              alt={event.title}
              className="w-full h-full object-cover"
              onError={(e) => {
                e.target.src = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&h=400&fit=crop';
              }}
            />
          )}
          <div className="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-blue-800/80 to-transparent" />
        </div>
        
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6">
          <Link to="/events" className="inline-flex items-center gap-2 text-white/80 hover:text-white mb-6 transition-colors">
            <FiArrowLeft size={16} /> Back to Events
          </Link>
          
          <div className="flex flex-wrap items-center gap-3 mb-4">
            <span className={`px-3 py-1 rounded-full text-xs font-bold bg-${statusColor === 'blue' ? 'blue' : statusColor === 'green' ? 'green' : statusColor === 'red' ? 'red' : 'gray'}-500 text-white`}>
              {statusLabel}
            </span>
            <span className="px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-sm">
              {typeIcon} {typeLabel}
            </span>
            {event.is_featured && (
              <span className="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white">
                ⭐ Featured
              </span>
            )}
          </div>

          <h1 className="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-4 leading-tight">
            {event.title}
          </h1>
          
          <div className="flex flex-wrap gap-4 text-white/90">
            <span className="flex items-center gap-2">
              <FiCalendar size={16} />
              {formatDate(event.start_date, 'MMMM D, YYYY')}
              {event.end_date && ` - ${formatDate(event.end_date, 'MMMM D, YYYY')}`}
            </span>
            {event.location && (
              <span className="flex items-center gap-2">
                <FiMapPin size={16} />
                {event.location}
              </span>
            )}
          </div>
        </div>
      </section>

      {/* Content Section */}
      <section className="py-12 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {/* Main Content */}
            <div className="lg:col-span-2">
              {/* Description */}
              <div className="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mb-6">
                <h2 className="text-xl font-bold text-slate-800 mb-4">About This Event</h2>
                <div className="prose prose-sm sm:prose-base max-w-none text-slate-600 leading-relaxed whitespace-pre-wrap">
                  {event.description || 'No description available.'}
                </div>
              </div>

              {/* Speakers */}
              {event.speakers && event.speakers.length > 0 && (
                <div className="bg-white rounded-2xl shadow-lg p-6 sm:p-8 mb-6">
                  <h2 className="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <FiUsers size={20} /> Speakers
                  </h2>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {event.speakers.map((speaker, index) => (
                      <div key={index} className="border border-slate-200 rounded-xl p-4">
                        <h4 className="font-bold text-slate-800">{speaker.name || 'Unknown'}</h4>
                        {speaker.title && (
                          <p className="text-sm text-slate-600">{speaker.title}</p>
                        )}
                        {speaker.company && (
                          <p className="text-sm text-slate-500">{speaker.company}</p>
                        )}
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Gallery */}
              {galleryImages.length > 0 && (
                <div className="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                  <h2 className="text-xl font-bold text-slate-800 mb-4">Gallery</h2>
                  <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    {galleryImages.map((image, index) => (
                      <img
                        key={index}
                        src={image}
                        alt={`Gallery ${index + 1}`}
                        className="w-full h-32 object-cover rounded-lg hover:scale-105 transition-transform duration-300"
                        onError={(e) => {
                          e.target.src = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=300&h=200&fit=crop';
                        }}
                      />
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Sidebar */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                <h3 className="font-bold text-slate-800 mb-4">Event Details</h3>
                
                <dl className="space-y-3 text-sm">
                  <div>
                    <dt className="text-slate-500">Date</dt>
                    <dd className="font-semibold text-slate-800">
                      {formatDate(event.start_date, 'MMMM D, YYYY')}
                    </dd>
                  </div>
                  
                  <div>
                    <dt className="text-slate-500">Time</dt>
                    <dd className="font-semibold text-slate-800">
                      {getTime(event.start_date)}
                      {event.end_date && ` - ${getTime(event.end_date)}`}
                    </dd>
                  </div>
                  
                  {event.location && (
                    <div>
                      <dt className="text-slate-500">Location</dt>
                      <dd className="font-semibold text-slate-800">{event.location}</dd>
                      {event.address && (
                        <dd className="text-slate-500 text-xs mt-1">{event.address}</dd>
                      )}
                    </div>
                  )}
                  
                  {event.organizer && (
                    <div>
                      <dt className="text-slate-500">Organizer</dt>
                      <dd className="font-semibold text-slate-800">{event.organizer}</dd>
                    </div>
                  )}
                  
                  {event.contact_email && (
                    <div>
                      <dt className="text-slate-500">Contact</dt>
                      <dd className="font-semibold text-slate-800 flex items-center gap-2">
                        <FiMail size={14} />
                        <a href={`mailto:${event.contact_email}`} className="text-blue-600 hover:underline">
                          {event.contact_email}
                        </a>
                      </dd>
                    </div>
                  )}
                  
                  {event.contact_phone && (
                    <div>
                      <dt className="text-slate-500">Phone</dt>
                      <dd className="font-semibold text-slate-800 flex items-center gap-2">
                        <FiPhone size={14} />
                        <a href={`tel:${event.contact_phone}`} className="text-blue-600 hover:underline">
                          {event.contact_phone}
                        </a>
                      </dd>
                    </div>
                  )}
                </dl>

                {/* Registration Button */}
                {event.registration_link && (
                  <a
                    href={event.registration_link}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="mt-6 w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold px-6 py-3 rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-300"
                  >
                    Register Now <FiExternalLink size={16} />
                  </a>
                )}

                {/* Share Section */}
                <div className="mt-6 pt-6 border-t border-slate-200">
                  <p className="text-sm text-slate-500 mb-3">Share this event</p>
                  <div className="flex gap-2">
                    <a
                      href={shareLinks.facebook}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="w-10 h-10 rounded-full bg-[#1877f2] hover:bg-[#166fe5] text-white flex items-center justify-center transition-colors"
                    >
                      <FaFacebook size={18} />
                    </a>
                    <a
                      href={shareLinks.twitter}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="w-10 h-10 rounded-full bg-[#000] hover:bg-[#1a1a1a] text-white flex items-center justify-center transition-colors"
                    >
                      <FaTwitter size={18} />
                    </a>
                    <a
                      href={shareLinks.linkedin}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="w-10 h-10 rounded-full bg-[#0a66c2] hover:bg-[#0958a9] text-white flex items-center justify-center transition-colors"
                    >
                      <FaLinkedin size={18} />
                    </a>
                    <a
                      href={shareLinks.whatsapp}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="w-10 h-10 rounded-full bg-[#25D366] hover:bg-[#1da851] text-white flex items-center justify-center transition-colors"
                    >
                      <FaWhatsapp size={18} />
                    </a>
                    <button
                      onClick={() => navigator.clipboard.writeText(shareUrl)}
                      className="w-10 h-10 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center transition-colors"
                    >
                      <FiShare2 size={18} />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}