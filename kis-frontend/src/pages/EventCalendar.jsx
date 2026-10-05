import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { FiChevronLeft, FiChevronRight, FiCalendar, FiMapPin, FiClock, FiX, FiArrowRight } from 'react-icons/fi';
import { eventService } from '../services/eventService';
import { formatDate, getTime, getStatusColor, getStatusLabel, getCalendarDays } from '../utils/dateUtils';
import { buildImageUrl } from '../utils/image';

export default function EventCalendar() {
  const navigate = useNavigate();
  const [currentDate, setCurrentDate] = useState(new Date());
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedDate, setSelectedDate] = useState(null);
  const [showPopup, setShowPopup] = useState(false);

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth() + 1;
  const monthName = currentDate.toLocaleString('default', { month: 'long' });

  useEffect(() => {
    fetchEvents();
  }, [year, month]);

  const fetchEvents = async () => {
    try {
      setLoading(true);
      const data = await eventService.calendar(year, month);
      setEvents(Array.isArray(data) ? data : []);
    } catch (err) {
      console.error('Failed to fetch calendar events:', err);
      setEvents([]);
    } finally {
      setLoading(false);
    }
  };

  const getEventsForDate = (date) => {
    const dateStr = date.toDateString();
    return events.filter(event => {
      const eventDate = new Date(event.start_date);
      return eventDate.toDateString() === dateStr;
    });
  };

  const hasEvents = (date) => {
    return getEventsForDate(date).length > 0;
  };

  const goToPrevMonth = () => {
    setCurrentDate(new Date(year, month - 2, 1));
    setSelectedDate(null);
    setShowPopup(false);
  };

  const goToNextMonth = () => {
    setCurrentDate(new Date(year, month, 1));
    setSelectedDate(null);
    setShowPopup(false);
  };

  const goToToday = () => {
    setCurrentDate(new Date());
    setSelectedDate(null);
    setShowPopup(false);
  };

  const goToMonth = (year, month) => {
    setCurrentDate(new Date(year, month - 1, 1));
    setSelectedDate(null);
    setShowPopup(false);
  };

  const handleDateClick = (date) => {
    const dayEvents = getEventsForDate(date);
    if (dayEvents.length > 0) {
      setSelectedDate(date);
      setShowPopup(true);
    }
  };

  const handleEventClick = (slug) => {
    navigate(`/events/${slug}`);
  };

  const closePopup = () => {
    setShowPopup(false);
    setTimeout(() => setSelectedDate(null), 300);
  };

  const selectedDateEvents = selectedDate ? getEventsForDate(selectedDate) : [];
  const calendarDays = getCalendarDays(year, month);

  // ─── LOADING STATE ───
  if (loading) {
    return (
      <div className="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div className="animate-pulse">
          <div className="h-16 bg-slate-200" />
          <div className="p-4">
            <div className="grid grid-cols-7 gap-1">
              {[...Array(42)].map((_, i) => (
                <div key={i} className="h-16 bg-slate-100 rounded-lg" />
              ))}
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="bg-white rounded-2xl shadow-lg overflow-hidden relative">
      {/* Calendar Header */}
      <div className="bg-gradient-to-r from-blue-600 to-blue-700 px-4 sm:px-6 py-4">
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="flex items-center gap-3">
            <button
              onClick={goToPrevMonth}
              className="p-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white transition-colors"
            >
              <FiChevronLeft size={18} />
            </button>
            <h2 className="text-lg sm:text-xl font-bold text-white min-w-[140px] text-center">
              {monthName} {year}
            </h2>
            <button
              onClick={goToNextMonth}
              className="p-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white transition-colors"
            >
              <FiChevronRight size={18} />
            </button>
          </div>
          <button
            onClick={goToToday}
            className="px-4 py-1.5 bg-white text-blue-700 rounded-lg hover:bg-blue-50 transition-colors text-sm font-semibold"
          >
            Today
          </button>
        </div>
      </div>

      {/* Month Navigation */}
      <div className="flex flex-wrap gap-1 px-4 py-3 border-b border-slate-200">
        {['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'].map((m, i) => {
          const monthNum = i + 1;
          const isActive = monthNum === month;
          return (
            <button
              key={i}
              onClick={() => goToMonth(year, monthNum)}
              className={`px-3 py-1 rounded-lg text-xs font-semibold transition-all ${
                isActive
                  ? 'bg-blue-600 text-white shadow-md'
                  : 'text-slate-600 hover:bg-slate-100'
              }`}
            >
              {m}
            </button>
          );
        })}
      </div>

      {/* Calendar Grid */}
      <div className="p-4">
        <div className="grid grid-cols-7 gap-1">
          {['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day => (
            <div key={day} className="py-2 text-center text-xs font-semibold text-slate-600">
              {day}
            </div>
          ))}
        </div>

        <div className="grid grid-cols-7 gap-1 mt-1">
          {calendarDays.map((day, index) => {
            const dayEvents = getEventsForDate(day.date);
            const isToday = day.date.toDateString() === new Date().toDateString();
            const isSelected = selectedDate && day.date.toDateString() === selectedDate.toDateString();
            const hasEvent = dayEvents.length > 0;

            return (
              <div
                key={index}
                onClick={() => handleDateClick(day.date)}
                className={`
                  min-h-[60px] sm:min-h-[70px] p-1.5 rounded-lg cursor-pointer transition-all
                  ${!day.isCurrentMonth ? 'bg-slate-50/50 text-slate-400' : 'hover:bg-blue-50'}
                  ${isToday ? 'bg-blue-50 border-2 border-blue-500' : ''}
                  ${isSelected ? 'ring-2 ring-blue-500 ring-inset' : ''}
                  ${hasEvent ? 'cursor-pointer' : 'cursor-default'}
                `}
              >
                <div className="flex items-start justify-between">
                  <span className={`
                    text-sm font-semibold
                    ${!day.isCurrentMonth ? 'text-slate-400' : 'text-slate-700'}
                    ${isToday ? 'text-blue-600' : ''}
                  `}>
                    {day.date.getDate()}
                  </span>
                  {hasEvent && (
                    <span className="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 mt-1" />
                  )}
                </div>

                {day.isCurrentMonth && dayEvents.length > 0 && (
                  <div className="mt-1 space-y-0.5">
                    {dayEvents.slice(0, 1).map(event => (
                      <div
                        key={event.id}
                        className="text-[8px] sm:text-[9px] text-blue-600 truncate bg-blue-50/80 px-1 py-0.5 rounded"
                      >
                        {event.title.length > 12 ? event.title.substring(0, 12) + '…' : event.title}
                      </div>
                    ))}
                    {dayEvents.length > 1 && (
                      <span className="text-[8px] sm:text-[9px] text-slate-400 px-1">
                        +{dayEvents.length - 1} more
                      </span>
                    )}
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </div>

      {/* ─── EVENT POPUP ─── */}
      {showPopup && selectedDate && selectedDateEvents.length > 0 && (
        <>
          {/* Backdrop */}
          <div
            className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm transition-opacity duration-300"
            onClick={closePopup}
          />
          
          {/* Popup */}
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
            <div className="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden pointer-events-auto animate-scaleIn">
              {/* Popup Header */}
              <div className="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div>
                  <h3 className="text-lg font-bold text-white">
                    {formatDate(selectedDate, 'EEEE, MMMM D, YYYY')}
                  </h3>
                  <p className="text-blue-100 text-sm">
                    {selectedDateEvents.length} event{selectedDateEvents.length !== 1 ? 's' : ''}
                  </p>
                </div>
                <button
                  onClick={closePopup}
                  className="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition-colors"
                >
                  <FiX size={18} />
                </button>
              </div>

              {/* Popup Body - Scrollable */}
              <div className="p-4 sm:p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                {selectedDateEvents.length === 0 ? (
                  <p className="text-slate-500 text-center py-8">No events on this day.</p>
                ) : (
                  <div className="space-y-4">
                    {selectedDateEvents.map((event) => {
                      const imageUrl = buildImageUrl(event.featured_image);
                      return (
                        <div
                          key={event.id}
                          onClick={() => handleEventClick(event.slug)}
                          className="group block border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-md transition-all cursor-pointer overflow-hidden"
                        >
                          <div className="flex flex-col sm:flex-row">
                            {/* Event Image */}
                            <div className="w-full sm:w-2/5 h-48 sm:h-auto bg-slate-100 overflow-hidden flex-shrink-0">
                              {imageUrl ? (
                                <img
                                  src={imageUrl}
                                  alt={event.title}
                                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                  onError={(e) => {
                                    e.target.style.display = 'none';
                                    e.target.parentElement.style.background = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
                                  }}
                                />
                              ) : (
                                <div className="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                  <FiCalendar className="text-white/50 text-5xl" />
                                </div>
                              )}
                            </div>
                            
                            {/* Event Details */}
                            <div className="flex-1 p-4 sm:p-5">
                              <div className="flex flex-wrap items-center gap-2 mb-2">
                                <h4 className="text-base sm:text-lg font-bold text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-2">
                                  {event.title}
                                </h4>
                                <span className={`px-2 py-0.5 rounded-full text-[10px] font-bold bg-${getStatusColor(event.event_status) === 'blue' ? 'blue' : getStatusColor(event.event_status) === 'green' ? 'green' : getStatusColor(event.event_status) === 'red' ? 'red' : 'gray'}-100 text-${getStatusColor(event.event_status) === 'blue' ? 'blue' : getStatusColor(event.event_status) === 'green' ? 'green' : getStatusColor(event.event_status) === 'red' ? 'red' : 'gray'}-700`}>
                                  {getStatusLabel(event.event_status)}
                                </span>
                                {event.is_featured && (
                                  <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                    ⭐ Featured
                                  </span>
                                )}
                              </div>
                              
                              <div className="flex flex-wrap items-center gap-3 text-sm text-slate-500 mb-3">
                                <span className="flex items-center gap-1">
                                  <FiClock size={14} />
                                  {getTime(event.start_date)}
                                  {event.end_date && ` - ${getTime(event.end_date)}`}
                                </span>
                                {event.location && (
                                  <span className="flex items-center gap-1">
                                    <FiMapPin size={14} />
                                    {event.location}
                                  </span>
                                )}
                              </div>

                              {event.description && (
                                <p className="text-sm text-slate-600 line-clamp-2 mb-3">
                                  {event.description}
                                </p>
                              )}

                              <div className="flex items-center gap-2 text-sm font-semibold text-blue-600 group-hover:translate-x-1 transition-transform">
                                View Details <FiArrowRight size={14} />
                              </div>
                            </div>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                )}
              </div>
            </div>
          </div>
        </>
      )}

      {/* ─── CSS ANIMATION ─── */}
      <style>{`
        @keyframes scaleIn {
          from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
          }
          to {
            opacity: 1;
            transform: scale(1) translateY(0);
          }
        }
        .animate-scaleIn {
          animation: scaleIn 0.25s ease-out forwards;
        }
      `}</style>
    </div>
  );
}