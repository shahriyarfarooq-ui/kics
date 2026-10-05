import { Link } from 'react-router-dom';
import { FiCalendar, FiMapPin, FiClock, FiArrowRight } from 'react-icons/fi';
import { formatDate, getTime, getStatusColor, getStatusLabel, getTypeIcon, getTypeLabel } from '../utils/dateUtils';
import { buildImageUrl } from '../utils/contentMappers';

const EventCard = ({ event, featured = false }) => {
  const { 
    id, 
    title, 
    slug, 
    description, 
    start_date, 
    end_date, 
    location, 
    event_type, 
    event_status, 
    featured_image,
    registration_link
  } = event;

  const statusColor = getStatusColor(event_status);
  const statusLabel = getStatusLabel(event_status);
  const typeIcon = getTypeIcon(event_type);
  const typeLabel = getTypeLabel(event_type);
  
  // Use buildImageUrl to get the correct image path
  const imageUrl = featured_image ? buildImageUrl(featured_image) : null;

  return (
    <div className={`group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border ${featured ? 'border-amber-400' : 'border-slate-200'} hover:border-blue-300 h-full flex flex-col`}>
      
      {/* Image Section */}
      <div className="relative w-full h-48 sm:h-56 overflow-hidden bg-gradient-to-br from-blue-600 to-blue-800">
        {imageUrl ? (
          <img
            src={imageUrl}
            alt={title}
            className="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110"
            onError={(e) => {
              e.target.src = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop';
            }}
          />
        ) : (
          <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800">
            <span className="text-6xl">{typeIcon}</span>
          </div>
        )}
        
        {/* Status Badge */}
        <div className="absolute top-3 left-3">
          <span className={`px-3 py-1 rounded-full text-xs font-bold shadow-lg bg-${statusColor === 'blue' ? 'blue' : statusColor === 'green' ? 'green' : statusColor === 'red' ? 'red' : 'gray'}-500 text-white`}>
            {statusLabel}
          </span>
        </div>
        
        {/* Type Badge */}
        <div className="absolute top-3 right-3">
          <span className="px-3 py-1 rounded-full text-xs font-bold shadow-lg bg-white/90 text-slate-700 backdrop-blur-sm">
            {typeIcon} {typeLabel}
          </span>
        </div>

        {/* Featured Badge */}
        {featured && (
          <div className="absolute top-12 left-3">
            <span className="px-3 py-1 rounded-full text-xs font-bold shadow-lg bg-amber-500 text-white">
              ⭐ Featured
            </span>
          </div>
        )}
      </div>

      {/* Content Section */}
      <div className="p-5 flex flex-col flex-1">
        {/* Date & Time */}
        <div className="flex items-center gap-4 text-xs text-slate-500 mb-2">
          <span className="flex items-center gap-1">
            <FiCalendar size={12} />
            {formatDate(start_date, 'MMM D, YYYY')}
          </span>
          {end_date && (
            <span className="flex items-center gap-1">
              <FiClock size={12} />
              {getTime(start_date)} - {getTime(end_date)}
            </span>
          )}
        </div>

        {/* Title */}
        <h3 className="text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
          {title}
        </h3>

        {/* Description */}
        <p className="text-slate-600 text-sm leading-relaxed line-clamp-2 flex-1 mb-3">
          {description || 'No description available.'}
        </p>

        {/* Location */}
        {location && (
          <div className="flex items-center gap-1 text-xs text-slate-500 mb-3">
            <FiMapPin size={12} />
            <span className="line-clamp-1">{location}</span>
          </div>
        )}

        {/* Actions */}
        <div className="flex items-center gap-3 mt-auto pt-3 border-t border-slate-100">
          <Link
            to={`/events/${slug}`}
            className="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold text-sm px-4 py-2 rounded-xl transition-all duration-300 hover:scale-[1.02] hover:shadow-lg"
          >
            View Details
            <FiArrowRight size={14} className="group-hover:translate-x-1 transition-transform" />
          </Link>
          
          {registration_link && (
            <a
              href={registration_link}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center justify-center px-4 py-2 rounded-xl border-2 border-blue-600 text-blue-600 font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all duration-300"
            >
              Register
            </a>
          )}
        </div>
      </div>
    </div>
  );
};

export default EventCard;