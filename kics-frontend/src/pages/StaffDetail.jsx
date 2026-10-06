//src>pages>StaffDetail.jsx
import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { staffService } from '../services';
import { getImageLoadingProps } from '../utils/image';
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
  bio: item.bio ? stripHtml(item.bio) : 'No biography available.',
  email: item.email || null,
  image: item.image || null,
  researchInterest: item.research_interest ? stripHtml(item.research_interest) : null,
});

export default function StaffDetail() {
  const { id } = useParams();
  const [person, setPerson] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    const fetchStaff = async () => {
      try {
        setLoading(true);
        setError('');
        
        console.log(`Fetching staff member ${id}...`);
        const data = await staffService.get(id);
        console.log('API Response:', data);
        
        if (active && data) {
          const mapped = mapStaffMember(data);
          setPerson(mapped);
        } else if (active) {
          setError('Staff member not found.');
        }
      } catch (err) {
        console.error('API Error Details:', err);
        if (active) {
          let errorMessage = 'Unable to load this staff profile. ';
          if (err.status === 404) {
            errorMessage = 'Staff member not found.';
          } else if (err.status === 500) {
            errorMessage = 'Server error. Please try again later.';
          } else if (err.message) {
            errorMessage += `(${err.message})`;
          }
          setError(errorMessage);
        }
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchStaff();

    return () => { active = false; };
  }, [id]);

  const title = person?.name || 'Staff Profile';
  const bio = person?.bio || '';
  const researchInterest = person?.researchInterest || '';

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

      <section className="py-16 bg-white">
        <div className="max-w-5xl mx-auto px-4 sm:px-6">
          <Link to="/staff" className="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 hover:text-cyan-600 mb-8">
            <FiArrowLeft size={14} /> Back to Staff
          </Link>

          {loading && (
            <div className="text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading staff profile...
            </div>
          )}

          {error && (
            <div className="text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              {error}
            </div>
          )}

          {person && (
            <AnimateOnScroll>
              <div className="card p-6 sm:p-8">
                <div className="grid md:grid-cols-[220px_1fr] gap-8">
                  <div className="text-center">
                    <div className="w-36 h-36 rounded-full bg-primary-700 mx-auto flex items-center justify-center overflow-hidden text-white shadow-lg">
                      {person.image ? (
                        <img 
                          src={person.image} 
                          alt={person.name} 
                          {...getImageLoadingProps({ eager: true, sizes: '144px' })} 
                          className="w-full h-full object-cover" 
                          onError={(e) => { 
                            e.currentTarget.style.display = 'none'; 
                            e.currentTarget.parentElement.innerHTML = `<svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>`;
                          }} 
                        />
                      ) : (
                        <FiUser size={54} />
                      )}
                    </div>
                    <span className="badge-primary mt-5 inline-flex">{person.dept}</span>
                  </div>
                  <div>
                    <h1 className="font-heading font-bold text-2xl sm:text-3xl text-primary-900">{person.name}</h1>
                    <p className="text-cyan-600 font-semibold mt-1">{person.title}</p>
                    {person.email && (
                      <a href={`mailto:${person.email}`} className="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-cyan-600 mt-4">
                        <FiMail size={14} /> {person.email}
                      </a>
                    )}
                    <div className="prose prose-slate max-w-none mt-6 text-slate-600">
                      <h2>Biography</h2>
                      <p>{bio || 'Biography will be updated soon.'}</p>
                      {researchInterest && (
                        <>
                          <h2>Research Interests</h2>
                          <p>{researchInterest}</p>
                        </>
                      )}
                    </div>
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