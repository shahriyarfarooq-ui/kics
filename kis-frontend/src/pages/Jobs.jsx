import { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { sampleJobs, departments } from '../data/siteData';
import { careerService } from '../services';
import { mapCareer } from '../utils/contentMappers';
import jobsBanner from '../assets/images/jobs-banner.png';
import { FiAlertCircle, FiBriefcase, FiClock, FiChevronDown, FiChevronUp, FiMapPin, FiSearch, FiCpu, FiCode, FiBookOpen } from 'react-icons/fi';

function JobCard({ job, index }) {
  const [open, setOpen] = useState(false);
  const applyHref = `mailto:${job.applyEmail}?subject=${encodeURIComponent(`Job Application - ${job.title}`)}`;

  return (
    <AnimateOnScroll delay={index * 60}>
      <div className="card overflow-hidden">
        <button className="w-full flex items-start sm:items-center gap-4 p-6 text-left group" onClick={() => setOpen(!open)}>
          <div className="w-12 h-12 rounded-xl bg-primary-700 flex items-center justify-center flex-shrink-0">
            <FiBriefcase size={20} className="text-white" />
          </div>
          <div className="flex-1 min-w-0">
            <h3 className="font-heading font-bold text-primary-800 group-hover:text-cyan-500 transition-colors">{job.title}</h3>
            <div className="flex flex-wrap gap-3 mt-1.5 text-xs text-slate-500">
              <span className="badge-primary">{job.dept}</span>
              <span className="flex items-center gap-1"><FiClock size={10} /> {job.type}</span>
              {job.location && <span className="flex items-center gap-1"><FiMapPin size={10} /> {job.location}</span>}
              <span className="text-cyan-500 font-semibold">{job.deadline}</span>
            </div>
          </div>
          <div className="flex-shrink-0 ml-2 text-slate-400">
            {open ? <FiChevronUp size={18} /> : <FiChevronDown size={18} />}
          </div>
        </button>
        {open && (
          <div className="px-6 pb-6 border-t border-slate-100 pt-5 animate-fadeIn">
            <p className="text-slate-600 text-sm leading-relaxed mb-4 whitespace-pre-line">{job.description}</p>
            <h4 className="font-semibold text-primary-800 text-sm mb-2">Requirements:</h4>
            <ul className="space-y-1">
              {job.requirements.map((req, i) => (
                <li key={i} className="flex items-start gap-2 text-slate-500 text-sm">
                  <span className="text-cyan-500 mt-0.5 flex-shrink-0">-</span> {req}
                </li>
              ))}
            </ul>
            <div className="mt-5 flex flex-wrap gap-3">
              <a href={applyHref} className="btn-primary inline-flex">Apply Now</a>
              {job.id && (
                <Link to={`/jobs/${job.id}`} className="btn-outline inline-flex">
                  View Details
                </Link>
              )}
            </div>
          </div>
        )}
      </div>
    </AnimateOnScroll>
  );
}

export default function Jobs() {
  const [dept, setDept] = useState('All');
  const [jobs, setJobs] = useState(() => sampleJobs.map(mapCareer));
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    careerService.list()
      .then((data) => {
        const mapped = Array.isArray(data) ? data.map(mapCareer) : [];
        if (active && mapped.length > 0) setJobs(mapped);
      })
      .catch(() => {
        if (active) setError('Unable to load current openings. Showing fallback positions.');
      })
      .finally(() => {
        if (active) setLoading(false);
      });

    return () => { active = false; };
  }, []);

  const dynamicDepartments = useMemo(() => (
    ['All', ...new Set(jobs.map((job) => job.dept).filter(Boolean))]
  ), [jobs]);
  const filterDepartments = dynamicDepartments.length > 1 ? dynamicDepartments : departments;
  const visible = dept === 'All' ? jobs : jobs.filter((job) => job.dept === dept);

  return (
    <div>
      <SEO
        title="Jobs at KICS"
        description="Explore career opportunities at KICS - research, engineering, and academic roles at UET Lahore's leading computer science research institute."
        breadcrumbs={[{ label: 'Jobs', url: '/jobs' }]}
      />
      <PageHero
        title="Jobs at KICS"
        subtitle="Join our team of researchers, engineers, and educators building Pakistan's technological future."
        breadcrumbs={[{ label: 'Jobs' }]}
        backgroundImage={jobsBanner}
      />

      <section className="py-16 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          {loading && (
            <div className="mb-6 text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading current openings...
            </div>
          )}
          {error && (
            <div className="mb-6 flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              <FiAlertCircle size={16} /> {error}
            </div>
          )}

          <AnimateOnScroll>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 mb-10 sm:mb-12">
              {[
                { Icon: FiCpu, title: 'Research Roles', desc: 'Research Associates, Post-docs, and Lab Managers.' },
                { Icon: FiCode, title: 'Engineering Roles', desc: 'Software, Network, and Embedded Systems Engineers.' },
                { Icon: FiBookOpen, title: 'Academic Roles', desc: 'Instructors and trainers for professional development.' },
              ].map((item) => (
                <div key={item.title} className="card p-4 sm:p-5 text-center group">
                  <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-primary-50 group-hover:bg-primary-600 flex items-center justify-center mx-auto mb-2 sm:mb-3 transition-colors duration-300">
                    <item.Icon size={20} className="text-primary-600 group-hover:text-white transition-colors duration-300 sm:w-[22px] sm:h-[22px]" />
                  </div>
                  <h4 className="font-bold text-slate-800 text-xs sm:text-sm mb-1 sm:mb-1.5">{item.title}</h4>
                  <p className="text-slate-500 text-[11px] sm:text-xs">{item.desc}</p>
                </div>
              ))}
            </div>
          </AnimateOnScroll>

          <AnimateOnScroll>
            <div className="flex flex-wrap gap-2 mb-8">
              {filterDepartments.slice(0, 10).map((department) => (
                <button key={department} onClick={() => setDept(department)}
                  className={`px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 ${
                    dept === department ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-primary-600/10'
                  }`}>
                  {department}
                </button>
              ))}
            </div>
          </AnimateOnScroll>

          <div className="space-y-4">
            {visible.map((job, i) => <JobCard key={job.id || job.title} job={job} index={i} />)}
            {visible.length === 0 && (
              <div className="text-center py-12 text-slate-400">
                <FiSearch size={40} className="mx-auto mb-3 text-slate-300" />
                <p>No positions found for this department.</p>
              </div>
            )}
          </div>

          <AnimateOnScroll>
            <div className="mt-10 p-6 bg-primary-50 rounded-xl border border-primary-200">
              <h3 className="text-primary-900 font-bold text-lg mb-3">How to Apply</h3>
              <p className="text-slate-700 text-sm mb-4">
                Interested candidates should send their CV, cover letter, and relevant documents to{' '}
                <a href="mailto:hr@kics.edu.pk" className="text-primary-700 font-semibold hover:text-cyan-600 underline">
                  hr@kics.edu.pk
                </a>
              </p>
              <p className="text-slate-600 text-sm">
                Please mention the position title in the email subject line. Only shortlisted candidates will be contacted for interviews.
              </p>
            </div>
          </AnimateOnScroll>
        </div>
      </section>
    </div>
  );
}
