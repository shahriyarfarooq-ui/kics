//src>pages>Staff.jsx
import { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { staffMembers as fallbackStaffMembers } from '../data/siteData';
import { staffService } from '../services';
import { getImageLoadingProps } from '../utils/image';
import { FiMail, FiSearch, FiUser, FiAlertCircle, FiArrowRight } from 'react-icons/fi';

// Helper function to map API response to our component structure
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

// Helper to strip HTML tags
const stripHtml = (html) => {
  if (!html) return '';
  const tmp = document.createElement('DIV');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
};

export default function Staff() {
  const [filter, setFilter] = useState('All');
  const [search, setSearch] = useState('');
  const [staff, setStaff] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    const fetchStaff = async () => {
      try {
        setLoading(true);
        setError('');
        
        console.log('Fetching staff from API...');
        const data = await staffService.list();
        console.log('API Response:', data);
        
        if (active) {
          // Check if data is an array
          if (Array.isArray(data) && data.length > 0) {
            const mapped = data.map(mapStaffMember);
            setStaff(mapped);
          } else if (Array.isArray(data) && data.length === 0) {
            setError('No staff members found.');
            setStaff([]);
          } else {
            console.error('Unexpected data format:', data);
            setError('Received unexpected data format from server.');
          }
        }
      } catch (err) {
        console.error('API Error Details:', err);
        if (active) {
          let errorMessage = 'Unable to load staff data. ';
          if (err.status === 404) {
            errorMessage = 'Staff API endpoint not found. Please check the API URL.';
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
  }, []);

  const departments = useMemo(() => {
    const depts = ['All', ...new Set(staff.map((person) => person.dept).filter(Boolean))];
    return depts;
  }, [staff]);

  const visible = staff.filter((person) =>
    (filter === 'All' || person.dept === filter) &&
    (person.name.toLowerCase().includes(search.toLowerCase()) ||
     person.title.toLowerCase().includes(search.toLowerCase()))
  );

  return (
    <div>
      <SEO
        title="Our Staff"
        description="Meet the researchers, engineers, and professionals at KICS UET Lahore - the team driving innovation in AI, cybersecurity and enterprise software."
        breadcrumbs={[{ label: 'About', url: '/about' }, { label: 'Staff', url: '/staff' }]}
      />
      <PageHero
        title="Our Staff"
        subtitle="Meet the dedicated researchers, engineers, and professionals who make KICS a center of excellence."
        breadcrumbs={[{ label: 'About', to: '/about' }, { label: 'Staff' }]}
      />

      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          {loading && (
            <div className="mb-6 text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading staff directory...
            </div>
          )}
          {error && (
            <div className="mb-6 flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              <FiAlertCircle size={16} /> {error}
            </div>
          )}

          <AnimateOnScroll>
            <div className="flex flex-col sm:flex-row gap-4 mb-10">
              <div className="relative flex-1 max-w-sm">
                <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
                <input
                  type="text"
                  placeholder="Search by name or title..."
                  value={search}
                  onChange={e => setSearch(e.target.value)}
                  className="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600"
                />
              </div>
              <div className="flex flex-wrap gap-2">
                {departments.map((department) => (
                  <button key={department} onClick={() => setFilter(department)}
                    className={`px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200 ${
                      filter === department ? 'bg-primary-600 text-white shadow-card' : 'bg-slate-100 text-slate-600 hover:bg-primary-600/10'
                    }`}>
                    {department}
                  </button>
                ))}
              </div>
            </div>
          </AnimateOnScroll>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
            {visible.map((person, index) => (
              <AnimateOnScroll key={person.id || person.name} delay={index * 50}>
                <div className="card p-4 sm:p-5 group text-center h-full flex flex-col">
                  <div className="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary-700 mx-auto mb-3 sm:mb-4 flex items-center justify-center overflow-hidden text-white shadow-md group-hover:scale-110 transition-transform duration-300">
                    {person.image ? (
                      <img 
                        src={person.image} 
                        alt={person.name} 
                        {...getImageLoadingProps({ sizes: '80px' })} 
                        className="w-full h-full object-cover" 
                        onError={(e) => { 
                          e.currentTarget.style.display = 'none'; 
                          e.currentTarget.parentElement.innerHTML = `<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>`;
                        }} 
                      />
                    ) : (
                      <FiUser size={28} />
                    )}
                  </div>
                  <h3 className="font-heading font-bold text-primary-800 text-sm mb-0.5 group-hover:text-cyan-500 transition-colors">{person.name}</h3>
                  <p className="text-cyan-500 text-xs font-semibold">{person.title}</p>
                  <span className="badge-primary mt-2 mb-3 mx-auto">{person.dept}</span>
                  <p className="text-slate-500 text-xs leading-relaxed flex-1">{person.bio}</p>
                  {person.email && (
                    <a href={`mailto:${person.email}`}
                      className="mt-4 inline-flex items-center justify-center gap-1.5 text-primary-800 text-xs font-medium hover:text-cyan-500 transition-colors">
                      <FiMail size={12} /> {person.email}
                    </a>
                  )}
                  {person.id && (
                    <Link to={`/staff/${person.id}`}
                      className="mt-3 inline-flex items-center justify-center gap-1.5 text-primary-800 text-xs font-semibold hover:text-cyan-500 transition-colors">
                      View Profile <FiArrowRight size={12} />
                    </Link>
                  )}
                </div>
              </AnimateOnScroll>
            ))}
          </div>

          {!loading && visible.length === 0 && (
            <div className="text-center py-16 text-slate-400">
              <FiSearch size={44} className="mx-auto mb-3 text-slate-300" />
              <p className="text-lg font-medium">No staff members found</p>
              <p className="text-sm mt-1">Try a different search or filter</p>
            </div>
          )}

          {!loading && visible.length > 0 && (
            <AnimateOnScroll>
              <div className="text-center mt-12 p-6 bg-slate-50 rounded-xl">
                <p className="text-slate-600 text-sm mb-3">
                  Showing {visible.length} of {staff.length} staff members
                </p>
                <p className="text-slate-500 text-xs">
                  For inquiries about specific staff members or departments, contact{' '}
                  <a href="mailto:hr@kics.edu.pk" className="text-primary-700 font-semibold hover:text-cyan-600 underline">
                    hr@kics.edu.pk
                  </a>
                </p>
              </div>
            </AnimateOnScroll>
          )}
        </div>
      </section>
    </div>
  );
}
