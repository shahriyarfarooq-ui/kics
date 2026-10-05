import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FiArrowRight, FiCalendar } from 'react-icons/fi';
import EventCard from './EventCard';
import AnimateOnScroll from './AnimateOnScroll';
import { eventService } from '../services/eventService';

export default function UpcomingEvents() {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchEvents = async () => {
      try {
        const data = await eventService.upcoming(6);
        setEvents(Array.isArray(data) ? data : []);
      } catch (err) {
        console.error('Failed to fetch upcoming events:', err);
      } finally {
        setLoading(false);
      }
    };
    fetchEvents();
  }, []);

  if (loading) {
    return (
      <section className="py-16 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <div className="text-center mb-10">
            <h2 className="text-3xl font-bold text-slate-800">Upcoming Events</h2>
            <p className="text-slate-500 mt-2">Loading events...</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {[1, 2, 3].map(i => (
              <div key={i} className="bg-white rounded-2xl overflow-hidden shadow-lg animate-pulse">
                <div className="h-48 bg-slate-200" />
                <div className="p-5 space-y-3">
                  <div className="h-4 bg-slate-200 rounded w-3/4" />
                  <div className="h-3 bg-slate-200 rounded w-full" />
                  <div className="h-10 bg-slate-200 rounded-xl" />
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  if (events.length === 0) {
    return (
      <section className="py-16 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <AnimateOnScroll>
            <div className="text-center mb-10">
              <h2 className="text-3xl font-bold text-slate-800">Upcoming Events</h2>
              <p className="text-slate-500 mt-2">No upcoming events at the moment. Check back soon!</p>
            </div>
          </AnimateOnScroll>
        </div>
      </section>
    );
  }

  return (
    <section className="py-16 bg-slate-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6">
        <AnimateOnScroll>
          <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
              <span className="text-blue-700 font-bold uppercase tracking-[0.3em] text-xs mb-3 block">
                Don't Miss Out
              </span>
              <h2 className="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
                Upcoming Events
              </h2>
              <div className="w-20 h-1 bg-gradient-to-r from-blue-700 to-blue-800 rounded-full mt-3" />
            </div>
            <Link
              to="/events"
              className="inline-flex items-center gap-2 text-blue-700 font-semibold text-sm hover:text-blue-800 transition-colors group"
            >
              All Events <FiArrowRight className="group-hover:translate-x-1 transition-transform" />
            </Link>
          </div>
        </AnimateOnScroll>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {events.map((event, index) => (
            <AnimateOnScroll key={event.id} delay={index * 100}>
              <EventCard event={event} featured={event.is_featured} />
            </AnimateOnScroll>
          ))}
        </div>
      </div>
    </section>
  );
}