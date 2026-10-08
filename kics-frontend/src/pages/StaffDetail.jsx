//src>pages>StaffDetail.jsx
import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { staffService } from '../services';
import { getImageLoadingProps } from '../utils/image';
import { buildImageUrl } from '../utils/image';
import { FiArrowLeft, FiMail, FiUser } from 'react-icons/fi';

// Helper to strip HTML tags
const stripHtml = (html) => {
  if (!html) return '';
  const tmp = document.createElement('DIV');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
};

// Helper to map API response
const mapStaffMember = (item) => ({
  id: item.id,
  name: item.name || 'Unknown',
  title: item.designation || 'Staff Member',
  dept: item.department || 'Other',
  bio: item.bio ? stripHtml(item.bio) : '',
  email: item.email || null,
  image: buildImageUrl(item.image_path || item.image, ''),
  researchInterest: item.research_interest ? stripHtml(item.research_interest) : null,
  aboutMe: item.about_me ? stripHtml(item.about_me) : null,
  education: item.education ? stripHtml(item.education) : null,
  achievements: item.achievements ? stripHtml(item.achievements) : null,
  certifications: item.certifications ? stripHtml(item.certifications) : null,
  publications: item.publications ? stripHtml(item.publications) : null,
  workExperience: item.work_experience ? stripHtml(item.work_experience) : null,
  projects: item.projects ? stripHtml(item.projects) : null,
  socialLinks: item.social_links || {},
});

export default function StaffDetail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [person, setPerson] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    const fetchStaff = async () => {
      try {
        setLoading(true);
        setError('');

        const data = await staffService.get(id);

        if (active && data) {
          const mapped = mapStaffMember(data);
          setPerson(mapped);
        } else if (active) {
          setError('Staff member not found.');
        }
      } catch (err) {
        if (!active) return;

        if (err.status === 404) {
          setError('This staff profile is not public or is no longer available.');
          return;
        }

        let errorMessage = 'Unable to load this staff profile.';
        if (err.status === 500) {
          errorMessage = 'Server error. Please try again later.';
        } else if (err.message) {
          errorMessage = err.message;
        }
        setError(errorMessage);
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchStaff();

    return () => { active = false; };
  }, [id, navigate]);

  const title = person?.name || 'Staff Profile';
  const bio = person?.bio || '';
  const researchInterest = person?.researchInterest || '';
  const profileSections = person ? [
    ['About Me', person.aboutMe],
    ['Education', person.education],
    ['Achievements', person.achievements],
    ['Certifications', person.certifications],
    ['Publications', person.publications],
    ['Work Experience', person.workExperience],
    ['Projects', person.projects],
  ].filter(([, value]) => value) : [];

  const renderTextBlock = (label, value) => {
    if (!value) return null;
    const items = value
      .split(/\n|\r\n|\u2022|;/)
      .map((item) => item.replace(/^[-*•\s]+/, '').trim())
      .filter(Boolean);

    return (
      <section key={label} className="border-b border-slate-200 pb-5 last:border-b-0 last:pb-0">
        <h2 className="text-base font-semibold text-slate-800 mb-2">{label}</h2>
        {items.length > 1 ? (
          <ul className="space-y-2 text-sm text-slate-600">
            {items.map((item, idx) => (
              <li key={`${label}-${idx}`} className="flex gap-2">
                <span className="mt-1.5 h-1.5 w-1.5 rounded-full bg-primary-600 flex-shrink-0" />
                <span>{item}</span>
              </li>
            ))}
          </ul>
        ) : (
          <p className="text-sm text-slate-600 whitespace-pre-line">{items[0] || value}</p>
        )}
      </section>
    );
  };

  return (
    <div>
      <SEO
        title={title}
        description={bio || 'KICS staff profile.'}
        image={person?.image}
        type="profile"
        path={`/staff/${id}`}
        author={person?.name || 'KICS UET Lahore'}
        breadcrumbs={[{ label: 'Staff', url: '/staff' }, { label: title, url: `/staff/${id}` }]}
      />
      <PageHero
        title={title}
        subtitle={person?.title || 'KICS Staff Profile'}
        breadcrumbs={[{ label: 'Staff', to: '/staff' }, { label: 'Profile' }]}
      />

      <section className="py-12 bg-slate-50">
        <div className="max-w-5xl mx-auto px-4 sm:px-6">
          <Link to="/staff" className="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 hover:text-cyan-600 mb-6">
            <FiArrowLeft size={14} /> Back to Staff
          </Link>

          {loading && (
            <div className="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm">
              Loading staff profile...
            </div>
          )}

          {error && (
            <div className="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
              <p className="text-lg font-semibold text-slate-800">Profile not available</p>
              <p className="mt-2 text-sm text-slate-600">{error}</p>
              <Link
                to="/staff"
                className="mt-5 inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700"
              >
                Back to Staff Directory
              </Link>
            </div>
          )}

          {person && (
            <AnimateOnScroll>
              <div className="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div className="bg-gradient-to-r from-primary-700 to-cyan-600 px-6 py-8 text-white">
                  <div className="flex flex-col sm:flex-row sm:items-center gap-5">
                    <div className="h-24 w-24 rounded-full border-4 border-white/80 bg-white/10 flex items-center justify-center overflow-hidden shadow-md">
                      {person.image ? (
                        <img
                          src={person.image}
                          alt={person.name}
                          {...getImageLoadingProps({ eager: true, sizes: '144px' })}
                          className="h-full w-full object-cover"
                          onError={(e) => {
                            e.currentTarget.style.display = 'none';
                            e.currentTarget.parentElement.innerHTML = '<svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>';
                          }}
                        />
                      ) : (
                        <FiUser size={38} />
                      )}
                    </div>

                    <div>
                      <p className="text-sm uppercase tracking-[0.2em] text-cyan-100">Staff Profile</p>
                      <h1 className="mt-2 text-2xl sm:text-3xl font-bold">{person.name}</h1>
                      <p className="mt-1 text-cyan-100">{person.title}</p>
                      {person.dept && <p className="mt-2 inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-medium">{person.dept}</p>}
                    </div>
                  </div>
                </div>

                <div className="p-6 sm:p-8">
                  {person.email && (
                    <div className="mb-6 flex items-center gap-2 text-sm text-slate-600">
                      <FiMail size={15} className="text-primary-700" />
                      <a href={`mailto:${person.email}`} className="font-medium text-primary-700 hover:text-primary-600">
                        {person.email}
                      </a>
                    </div>
                  )}

                  <div className="space-y-6">
                    {bio && (
                      <section className="border-b border-slate-200 pb-5">
                        <h2 className="text-base font-semibold text-slate-800 mb-2">Biography</h2>
                        <p className="text-sm text-slate-600 whitespace-pre-line">{bio}</p>
                      </section>
                    )}

                    {researchInterest && (
                      <section className="border-b border-slate-200 pb-5">
                        <h2 className="text-base font-semibold text-slate-800 mb-2">Research Interests</h2>
                        <p className="text-sm text-slate-600 whitespace-pre-line">{researchInterest}</p>
                      </section>
                    )}

                    {profileSections.map(([heading, value]) => renderTextBlock(heading, value))}

                    {Object.entries(person.socialLinks).some(([, value]) => value) && (
                      <section>
                        <h2 className="text-base font-semibold text-slate-800 mb-3">Links</h2>
                        <div className="flex flex-wrap gap-3">
                          {Object.entries(person.socialLinks).filter(([, value]) => value).map(([label, href]) => (
                            <a
                              key={label}
                              href={href}
                              target="_blank"
                              rel="noopener noreferrer"
                              className="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 hover:border-primary-300 hover:text-primary-700"
                            >
                              {label}
                            </a>
                          ))}
                        </div>
                      </section>
                    )}
                  </div>
                </div>
              </div>
            </AnimateOnScroll>
          )}
        </div>
      </section>
    </div>
  );
}