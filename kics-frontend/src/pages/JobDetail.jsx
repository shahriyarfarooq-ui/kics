import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { sampleJobs } from '../data/siteData';
import { careerService } from '../services';
import { mapCareer } from '../utils/contentMappers';
import { FiAlertCircle, FiArrowLeft, FiBriefcase, FiClock, FiMapPin } from 'react-icons/fi';

export default function JobDetail() {
  const { id } = useParams();
  const [job, setJob] = useState(() => sampleJobs.map(mapCareer).find((item) => String(item.id) === String(id)) || null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    careerService.get(id)
      .then((data) => {
        if (active) setJob(mapCareer(data));
      })
      .catch(() => {
        if (active) setError('Unable to load this job detail right now.');
      })
      .finally(() => {
        if (active) setLoading(false);
      });

    return () => { active = false; };
  }, [id]);

  const title = job?.title || 'Job Detail';
  const applyHref = `mailto:hr@kics.edu.pk?subject=${encodeURIComponent(`Job Application - ${title}`)}`;

  return (
    <div>
      <SEO
        title={title}
        description={`Career opportunity at KICS: ${title}`}
        path={`/jobs/${id}`}
        breadcrumbs={[{ label: 'Jobs', url: '/jobs' }, { label: title, url: `/jobs/${id}` }]}
      />
      <PageHero
        title={title}
        subtitle="Review the position details and apply to join KICS."
        breadcrumbs={[{ label: 'Jobs' }, { label: title }]}
      />

      <section className="py-16 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          <Link to="/jobs" className="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 hover:text-cyan-600 mb-6">
            <FiArrowLeft size={16} /> Back to jobs
          </Link>

          {loading && (
            <div className="mb-6 text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading job detail...
            </div>
          )}
          {error && (
            <div className="mb-6 flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              <FiAlertCircle size={16} /> {error}
            </div>
          )}

          {job ? (
            <AnimateOnScroll>
              <article className="card p-6 sm:p-8">
                <div className="flex flex-col sm:flex-row sm:items-start gap-4 mb-6">
                  <div className="w-12 h-12 rounded-xl bg-primary-700 flex items-center justify-center flex-shrink-0">
                    <FiBriefcase size={20} className="text-white" />
                  </div>
                  <div>
                    <h2 className="font-heading font-bold text-2xl text-primary-900 mb-3">{job.title}</h2>
                    <div className="flex flex-wrap gap-3 text-sm text-slate-500">
                      <span className="badge-primary">{job.dept}</span>
                      <span className="flex items-center gap-1"><FiClock size={14} /> {job.deadline}</span>
                      <span className="flex items-center gap-1"><FiMapPin size={14} /> {job.location}</span>
                    </div>
                  </div>
                </div>

                <p className="text-slate-700 leading-relaxed whitespace-pre-line">{job.description}</p>

                <div className="mt-8">
                  <h3 className="font-heading font-bold text-primary-900 mb-3">Requirements</h3>
                  <ul className="space-y-2">
                    {job.requirements.map((requirement, index) => (
                      <li key={index} className="flex items-start gap-2 text-slate-600 text-sm">
                        <span className="text-cyan-500 mt-0.5 flex-shrink-0">-</span>
                        {requirement}
                      </li>
                    ))}
                  </ul>
                </div>

                <a href={applyHref} className="btn-primary mt-8 inline-flex">
                  Apply Now
                </a>
              </article>
            </AnimateOnScroll>
          ) : (
            !loading && (
              <div className="text-center py-16 text-slate-400">
                <FiBriefcase size={40} className="mx-auto mb-3 text-slate-300" />
                <p className="text-lg font-medium">Job not found</p>
                <p className="text-sm mt-1">Please return to the jobs page and choose an available opening.</p>
              </div>
            )
          )}
        </div>
      </section>
    </div>
  );
}
