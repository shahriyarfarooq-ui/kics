import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FiSearch, FiCalendar, FiFilter, FiX, FiChevronLeft, FiChevronRight } from 'react-icons/fi';
import PageHero from '../components/PageHero';
import EventCard from '../components/EventCard';
import SEO from '../components/SEO';
import { eventService } from '../services/eventService';
import { getTypeLabel, getStatusColor, getStatusLabel, getTime, formatDate, getCalendarDays } from '../utils/dateUtils';

const EVENT_TYPES = ['all', 'conference', 'workshop', 'seminar', 'summit', 'training', 'social'];

export default function Events() {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [search, setSearch] = useState('');
  const [filterType, setFilterType] = useState('all');
  const [filterStatus, setFilterStatus] = useState('all');
  const [pagination, setPagination] = useState({
    current_page: 1,
    last_page: 1,
    total: 0
  });

  // Calendar state
  const [currentDate, setCurrentDate] = useState(new Date());
  const [selectedDate, setSelectedDate] = useState(null);

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth() + 1;
  const monthName = currentDate.toLocaleString('default', { month: 'long' });

  useEffect(() => {
    fetchEvents();
  }, [filterType, filterStatus, pagination.current_page]);

  const fetchEvents = async () => {
    try {
      setLoading(true);
      setError('');
      
      const params = {
        page: pagination.current_page,
        per_page: 9
      };
      
      if (filterType !== 'all') params.type = filterType;
      if (filterStatus === 'upcoming') params.upcoming = true;
      
      const response = await eventService.list(params);
      
      if (response && Array.isArray(response)) {
        setEvents(response);
      } else if (response && response.data) {
        setEvents(response.data);
        if (response.meta) {
          setPagination({
            current_page: response.meta.current_page || 1,
            last_page: response.meta.last_page || 1,
            total: response.meta.total || 0
          });
        }
      }
    } catch (err) {
      setError('Failed to load events. Please try again.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  // Filter events by search
  const filteredEvents = events.filter(event => {
    if (!search) return true;
    const searchLower = search.toLowerCase();
    return event.title.toLowerCase().includes(searchLower) ||
           (event.description && event.description.toLowerCase().includes(searchLower)) ||
           (event.location && event.location.toLowerCase().includes(searchLower));
  });

  // ─── Calendar Functions ───
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
  };

  const goToNextMonth = () => {
    setCurrentDate(new Date(year, month, 1));
    setSelectedDate(null);
  };

  const goToToday = () => {
    setCurrentDate(new Date());
    setSelectedDate(null);
  };

  const goToMonth = (year, month) => {
    setCurrentDate(new Date(year, month - 1, 1));
    setSelectedDate(null);
  };

  const selectedDateEvents = selectedDate ? getEventsForDate(selectedDate) : [];

  const calendarDays = getCalendarDays(year, month);

  return (
    <div>
      <SEO
        title="Events"
        description="Browse upcoming events, conferences, workshops, and seminars at KICS UET Lahore."
        breadcrumbs={[{ label: 'Home', url: '/' }, { label: 'Events' }]}
      />

      <PageHero
        title="Events"
        subtitle="Stay updated with the latest conferences, workshops, seminars, and training programs at KICS."
        breadcrumbs={[{ label: 'Home', to: '/' }, { label: 'Events' }]}
      />

      <section className="py-12 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          
          {/* ─── CALENDAR SECTION ─── */}
          <div className="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
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

            {/* Month Navigation Quick Links */}
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
                      onClick={() => {
                        if (hasEvent) {
                          setSelectedDate(day.date);
                        }
                      }}
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
                              onClick={(e) => {
                                e.stopPropagation();
                                window.location.href = `/events/${event.slug}`;
                              }}
                              className="text-[8px] sm:text-[9px] text-blue-600 truncate hover:underline cursor-pointer bg-blue-50/80 px-1 py-0.5 rounded"
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
          </div>

          {/* ─── SELECTED DATE EVENTS ─── */}
          {selectedDate && selectedDateEvents.length > 0 && (
            <div className="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
              <div className="bg-gradient-to-r from-blue-600 to-blue-700 px-4 sm:px-6 py-3">
                <h3 className="text-lg font-bold text-white">
                  Events on {formatDate(selectedDate, 'MMMM D, YYYY')}
                </h3>
                <p className="text-blue-100 text-sm">
                  {selectedDateEvents.length} event{selectedDateEvents.length !== 1 ? 's' : ''} found
                </p>
              </div>
              <div className="p-4 sm:p-6 space-y-3">
                {selectedDateEvents.map(event => (
                  <Link
                    key={event.id}
                    to={`/events/${event.slug}`}
                    className="block p-4 border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-md transition-all group"
                  >
                    <div className="flex flex-wrap items-center justify-between gap-2">
                      <div className="flex-1 min-w-0">
                        <div className="flex flex-wrap items-center gap-2 mb-1">
                          <h4 className="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                            {event.title}
                          </h4>
                          <span className={`px-2 py-0.5 rounded-full text-[10px] font-bold bg-${getStatusColor(event.event_status) === 'blue' ? 'blue' : getStatusColor(event.event_status) === 'green' ? 'green' : getStatusColor(event.event_status) === 'red' ? 'red' : 'gray'}-100 text-${getStatusColor(event.event_status) === 'blue' ? 'blue' : getStatusColor(event.event_status) === 'green' ? 'green' : getStatusColor(event.event_status) === 'red' ? 'red' : 'gray'}-700`}>
                            {getStatusLabel(event.event_status)}
                          </span>
                        </div>
                        <div className="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                          <span className="flex items-center gap-1">
                            <FiCalendar size={14} />
                            {getTime(event.start_date)}
                            {event.end_date && ` - ${getTime(event.end_date)}`}
                          </span>
                          {event.location && (
                            <span className="flex items-center gap-1">
                              <FiCalendar size={14} />
                              {event.location}
                            </span>
                          )}
                        </div>
                      </div>
                      <span className="text-sm font-semibold text-blue-600 group-hover:translate-x-1 transition-transform">
                        View Details →
                      </span>
                    </div>
                  </Link>
                ))}
              </div>
            </div>
          )}

          {/* ─── EVENT LIST FILTERS ─── */}
          <div className="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-8">
            <div className="flex flex-wrap items-center gap-4">
              <div className="relative flex-1 min-w-[200px]">
                <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                <input
                  type="text"
                  placeholder="Search events..."
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm"
                />
                {search && (
                  <button
                    onClick={() => setSearch('')}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                  >
                    <FiX size={16} />
                  </button>
                )}
              </div>

              <div className="flex items-center gap-2">
                <FiFilter className="text-slate-400" size={18} />
                <select
                  value={filterType}
                  onChange={(e) => setFilterType(e.target.value)}
                  className="px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm bg-white"
                >
                  {EVENT_TYPES.map(type => (
                    <option key={type} value={type}>
                      {type === 'all' ? 'All Types' : getTypeLabel(type)}
                    </option>
                  ))}
                </select>
              </div>

              <select
                value={filterStatus}
                onChange={(e) => setFilterStatus(e.target.value)}
                className="px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm bg-white"
              >
                <option value="all">All Events</option>
                <option value="upcoming">Upcoming Only</option>
              </select>

              {!loading && (
                <span className="text-sm text-slate-500 ml-auto">
                  {filteredEvents.length} event{filteredEvents.length !== 1 ? 's' : ''} found
                </span>
              )}
            </div>
          </div>

          {/* ─── EVENT LIST ─── */}
          {loading && (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {[1, 2, 3, 4, 5, 6].map(i => (
                <div key={i} className="bg-white rounded-2xl overflow-hidden shadow-lg animate-pulse">
                  <div className="h-48 bg-slate-200" />
                  <div className="p-5 space-y-3">
                    <div className="h-4 bg-slate-200 rounded w-3/4" />
                    <div className="h-3 bg-slate-200 rounded w-full" />
                    <div className="h-3 bg-slate-200 rounded w-2/3" />
                    <div className="h-10 bg-slate-200 rounded-xl" />
                  </div>
                </div>
              ))}
            </div>
          )}

          {error && (
            <div className="bg-red-50 border border-red-200 rounded-2xl p-8 text-center">
              <p className="text-red-600">{error}</p>
              <button
                onClick={fetchEvents}
                className="mt-4 px-6 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors"
              >
                Try Again
              </button>
            </div>
          )}

          {!loading && !error && (
            <>
              {filteredEvents.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  {filteredEvents.map(event => (
                    <EventCard key={event.id} event={event} />
                  ))}
                </div>
              ) : (
                <div className="bg-white rounded-2xl shadow-lg p-12 text-center">
                  <FiCalendar className="text-6xl text-slate-300 mx-auto mb-4" />
                  <h3 className="text-xl font-bold text-slate-800 mb-2">No Events Found</h3>
                  <p className="text-slate-500">
                    {search ? 'Try adjusting your search or filters.' : 'Check back later for upcoming events.'}
                  </p>
                  {search && (
                    <button
                      onClick={() => { setSearch(''); setFilterType('all'); setFilterStatus('all'); }}
                      className="mt-4 px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors"
                    >
                      Clear Filters
                    </button>
                  )}
                </div>
              )}

              {pagination.last_page > 1 && (
                <div className="flex justify-center gap-2 mt-8">
                  <button
                    onClick={() => setPagination(prev => ({ ...prev, current_page: Math.max(1, prev.current_page - 1) }))}
                    disabled={pagination.current_page === 1}
                    className="px-4 py-2 border border-slate-200 rounded-xl hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    Previous
                  </button>
                  <span className="px-4 py-2 text-sm text-slate-600">
                    Page {pagination.current_page} of {pagination.last_page}
                  </span>
                  <button
                    onClick={() => setPagination(prev => ({ ...prev, current_page: Math.min(prev.last_page, prev.current_page + 1) }))}
                    disabled={pagination.current_page === pagination.last_page}
                    className="px-4 py-2 border border-slate-200 rounded-xl hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    Next
                  </button>
                </div>
              )}
            </>
          )}
        </div>
      </section>
    </div>
  );
}