import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { useEffect, useState } from 'react';
import { eventService } from '../services/eventService';
import { formatDate } from '../utils/dateUtils';
import { FiExternalLink, FiCalendar, FiMapPin, FiArrowRight } from 'react-icons/fi';
import { Link } from 'react-router-dom';
import { getImageLoadingProps } from '../utils/image';

export default function Conferences() {
  const [conferences, setConferences] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let active = true;
    eventService.list({ type: 'conference', per_page: 100 })
      .then((events) => {
        if (active) setConferences(Array.isArray(events) ? events : []);
      })
      .catch((error) => console.error('Failed to load conferences:', error))
      .finally(() => { if (active) setLoading(false); });

    return () => { active = false; };
  }, []);

  return (
    <div>
      <SEO
        title="Conferences"
        description="KICS organizes ICOSST and other IEEE conferences on open-source systems, AI, cybersecurity, and more. Past and upcoming events at UET Lahore."
        breadcrumbs={[{ label: 'Events' }, { label: 'Conferences', url: '/conferences' }]}
      />
      <PageHero
        title="Conferences"
        subtitle="KICS hosts and co-organizes international conferences to advance open science and technology."
        breadcrumbs={[{ label: 'Events' }, { label: 'Conferences' }]}
        pageKey="conferences"
      />

      {/* ICOSST feature */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <AnimateOnScroll>
            <div className="bg-primary-900 rounded-2xl overflow-hidden shadow-xl">
              <div className="grid grid-cols-1 lg:grid-cols-2">
                <div className="p-6 sm:p-8 lg:p-12">
                  <span className="badge mb-3 sm:mb-4 inline-block">Flagship Conference</span>
                  <h2 className="text-2xl sm:text-3xl font-heading font-bold text-primary-50 mb-3 sm:mb-4 leading-tight">
                    International Conference on Open Source Systems &amp; Technologies
                  </h2>
                  <p className="text-primary-100 text-sm sm:text-base leading-relaxed mb-5 sm:mb-6">
                    ICOSST is KICS's premier annual international conference, bringing together researchers,
                    practitioners, and policymakers to share advances in open-source systems,
                    software, AI, cybersecurity, cloud computing, and more. Co-organized with the
                    IEEE Computer &amp; Communication Societies Lahore Section (R10).
                  </p>
                  <div className="flex flex-wrap gap-3">
                    <Link to="/icosst" className="btn-primary">Explore ICOSST</Link>
                    <a href="http://icosst.kics.edu.pk" target="_blank" rel="noreferrer"
                      className="btn-outline flex items-center gap-2">Official Site <FiExternalLink size={14} /></a>
                  </div>
                </div>
                <div className="relative h-56 lg:h-auto">
                  <img src="https://kics.edu.pk/web/wp-content/uploads/2024/12/banner-1.jpg"
                    alt="ICOSST"
                    {...getImageLoadingProps({ sizes: '(min-width: 1024px) 50vw, 100vw' })}
                    className="w-full h-full object-cover object-center"
                    onError={e => { e.target.src='https://placehold.co/600x400/4a1209/fae3de?text=ICOSST'; }} />
                </div>
              </div>
            </div>
          </AnimateOnScroll>

          {/* Conference list */}
          <div className="mt-14">
            <AnimateOnScroll>
              <h3 className="section-title mb-8">All Conferences</h3>
            </AnimateOnScroll>
            <div className="space-y-5">
              {loading && <p className="text-slate-500">Loading conferences...</p>}
              {!loading && conferences.length === 0 && <p className="text-slate-500">No conferences are currently available.</p>}
              {conferences.map((conf, i) => (
                <AnimateOnScroll key={i} delay={i * 60}>
                  <div className="card p-6 flex flex-col sm:flex-row sm:items-center gap-5 group">
                    <div className="w-14 h-14 rounded-xl bg-primary-700 flex items-center justify-center text-white font-heading font-bold text-xs text-center leading-tight flex-shrink-0 group-hover:shadow-lg group-hover:shadow-cyan-500/30 transition-shadow">
                      {conf.event_type || 'Conference'}
                    </div>
                    <div className="flex-1">
                      <h4 className="font-heading font-bold text-primary-800 group-hover:text-cyan-500 transition-colors">{conf.title}</h4>
                      <div className="flex flex-wrap gap-4 mt-2 text-sm text-slate-500">
                        <span className="flex items-center gap-1.5"><FiCalendar size={13} /> {formatDate(conf.start_date, 'MMMM D, YYYY')}</span>
                        <span className="flex items-center gap-1.5"><FiMapPin size={13} /> {conf.location}</span>
                      </div>
                    </div>
                    <div className="flex items-center gap-3">
                      <Link to={`/events/${conf.slug}`} className="btn-primary inline-flex items-center gap-2">
                        View Details <FiArrowRight size={14} />
                      </Link>
                      <span className={`badge ${['upcoming', 'ongoing'].includes(conf.event_status) ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500'}`}>
                        {conf.event_status === 'upcoming' ? 'Upcoming' : conf.event_status === 'ongoing' ? 'Ongoing' : 'Concluded'}
                      </span>
                      {conf.registration_link && (
                        <a href={conf.registration_link} target="_blank" rel="noreferrer"
                          className="w-9 h-9 rounded-lg bg-primary-600 text-white flex items-center justify-center hover:bg-cyan-500 transition-colors">
                          <FiExternalLink size={14} />
                        </a>
                      )}
                    </div>
                  </div>
                </AnimateOnScroll>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Submit CTA */}
      <section className="py-14 bg-slate-50">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 text-center">
          <AnimateOnScroll animation="reveal-scale">
            <span className="eyebrow">Participate</span>
            <h2 className="section-title mb-4">Submit Your Research</h2>
            <p className="text-slate-500 mb-8">
              ICOSST welcomes original research papers on open-source systems, AI applications,
              cybersecurity, cloud, networks, and related technology domains.
            </p>
            <a href="http://icosst.kics.edu.pk" target="_blank" rel="noreferrer" className="btn-primary">
              Visit Conference Portal
            </a>
          </AnimateOnScroll>
        </div>
      </section>
    </div>
  );
}
