import { useEffect, useMemo, useState } from 'react';
import { FiSearch, FiMail, FiAlertCircle, FiUser, FiBriefcase } from 'react-icons/fi';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { erpService } from '../services';
import employeesBanner from '../assets/images/kics-employees-banner.jpg';

const mapEmployee = (employee) => ({
  id: employee.id,
  name: employee.complete_name || employee.name || 'Unnamed Staff',
  title: employee.job_title || 'Staff Member',
  department: employee.department || 'General',
  email: employee.work_email || employee.email || '',
  bio: employee.bio || '',
  image: employee.image || null,
});

const stripHtml = (html = '') => {
  if (typeof document === 'undefined') return html;
  const tmp = document.createElement('DIV');
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || '';
};

// Seniority ranking order for job titles
const DESIGNATION_ORDER = [
  'director', 'vice chancellor', 'head', 'professor',
  'principal investigator', 'co-pi', 'consultant research', 'consultant', 'technical manager',
  'principal manager', 'sr. manager research', 'sr. manager', 'sr. manager procurement', 'sr. manager training', 'sr. project manager', 'team lead',
  'manager research', 'manager', 'accounts manager', 'business development manager', 'program manager',
  'assistant manager research', 'assistant manager', 'assistant manager program', 'assistant manager hr', 'assistant manager accounts', 'associate professor',
  'sr. research officer', 'sr. network administrator', 'sr. software engineer', 'sr. sqa engineer', 'sr. design engineer', 'sr. admin & accounts officer', 'sr. admin officer', 'senior hr officer',
  'research officer', 'network engineer', 'network administrator', 'software engineer', 'sqa engineer', 'software developer', 'design engineer', 'accounts officer', 'admin officer', 'audit officer', 'hr officer', 'officer', 'graphic designer',
  'trainer', 'senior trainer', 'junior trainer', 'instructor', 'lab engineer', 'research assistant', 'accounts assistant', 'admin assistant', 'hr assistant', 'program assistant', 'office assistant',
  'technician', 'lab technician', 'lab assistant', 'network assistant', 'sqa assistant', 'caretaker', 'driver', 'security guard', 'security officer', 'electrician', 'naib qasid', 'sweeper', 'gardner',
  'intern', 'trainee engineer', 'trainee', 'ms student', 'phd student', 'intern 1', 'intern l'
];

const getDesignationRank = (title = '') => {
  const t = title.toLowerCase();
  for (let i = 0; i < DESIGNATION_ORDER.length; i++) {
    if (t.includes(DESIGNATION_ORDER[i])) {
      return i;
    }
  }
  return 999; // Default rank for unspecified titles
};

const ROLE_THEMES = {
  director: {
    bg: 'from-amber-500/10 via-amber-500/5 to-transparent hover:border-amber-400/40 hover:shadow-amber-500/10',
    border: 'border-amber-200/50',
    text: 'text-amber-800',
    badge: 'bg-amber-100/80 text-amber-900 border-amber-200/30'
  },
  manager: {
    bg: 'from-blue-500/10 via-blue-500/5 to-transparent hover:border-blue-400/40 hover:shadow-blue-500/10',
    border: 'border-blue-200/50',
    text: 'text-blue-800',
    badge: 'bg-blue-100/80 text-blue-900 border-blue-200/30'
  },
  engineer: {
    bg: 'from-indigo-500/10 via-indigo-500/5 to-transparent hover:border-indigo-400/40 hover:shadow-indigo-500/10',
    border: 'border-indigo-200/50',
    text: 'text-indigo-800',
    badge: 'bg-indigo-100/80 text-indigo-900 border-indigo-200/30'
  },
  officer: {
    bg: 'from-teal-500/10 via-teal-500/5 to-transparent hover:border-teal-400/40 hover:shadow-teal-500/10',
    border: 'border-teal-200/50',
    text: 'text-teal-800',
    badge: 'bg-teal-100/80 text-teal-900 border-teal-200/30'
  },
  default: {
    bg: 'from-slate-50 to-slate-100/50 hover:border-slate-300 hover:shadow-slate-500/5',
    border: 'border-slate-200/70',
    text: 'text-slate-700',
    badge: 'bg-slate-100 text-slate-800 border-slate-200/50'
  }
};

const getRoleTheme = (title = '') => {
  const t = title.toLowerCase();
  if (t.includes('director') || t.includes('vice chancellor') || t.includes('head') || t.includes('professor') || t.includes('principal investigator') || t.includes('co-pi')) {
    return ROLE_THEMES.director;
  }
  if (t.includes('manager') || t.includes('lead') || t.includes('coordinator')) {
    return ROLE_THEMES.manager;
  }
  if (t.includes('engineer') || t.includes('developer') || t.includes('administrator') || t.includes('expert') || t.includes('analyst')) {
    return ROLE_THEMES.engineer;
  }
  if (t.includes('officer') || t.includes('assistant') || t.includes('trainer') || t.includes('instructor') || t.includes('researcher')) {
    return ROLE_THEMES.officer;
  }
  return ROLE_THEMES.default;
};

export default function ErpEmployees() {
  const [employees, setEmployees] = useState([]);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    const fetchData = async () => {
      try {
        setLoading(true);
        setError('');

        const data = await erpService.listEmployees();
        if (!active) return;

        if (Array.isArray(data)) {
          setEmployees(data.map(mapEmployee));
        } else {
          setError('Unexpected staff data format.');
        }
      } catch (err) {
        setError(
          err.status === 404
            ? 'Staff API endpoint not found.'
            : err.message || 'Unable to load staff directory.'
        );
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchData();
    return () => { active = false; };
  }, []);

  // Filter and Sort employees by designation rank, then alphabetically by name
  const filteredAndSorted = useMemo(() => {
    const filterText = search.toLowerCase();
    return employees
      .filter((employee) =>
        employee.name.toLowerCase().includes(filterText) ||
        employee.title.toLowerCase().includes(filterText) ||
        employee.department.toLowerCase().includes(filterText)
      )
      .sort((a, b) => {
        const rankA = getDesignationRank(a.title);
        const rankB = getDesignationRank(b.title);
        if (rankA !== rankB) return rankA - rankB;
        return a.name.localeCompare(b.name);
      });
  }, [employees, search]);

  return (
    <div>
      <SEO
        title="Staff Directory"
        description="Browse current staff members and research lab assignments at KICS UET Lahore."
        breadcrumbs={[{ label: 'Home', url: '/' }, { label: 'Staff', url: '/kics-employees' }]}
      />

      <PageHero
        title="Staff"
        subtitle="Meet the active researchers, developers, managers, and support team members at KICS."
        breadcrumbs={[{ label: 'Home', to: '/' }, { label: 'Staff' }]}
        backgroundImage={employeesBanner}
      />

      <section className="py-16 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <div className="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div className="relative max-w-md w-full">
              <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
              <input
                type="text"
                placeholder="Search staff by name, designation, or department..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-sm"
              />
            </div>
            {!loading && !error && (
              <div className="text-sm text-slate-500">
                Showing {filteredAndSorted.length} active staff members
              </div>
            )}
          </div>

          {loading && (
            <div className="rounded-3xl border border-slate-200 bg-white p-12 text-center text-slate-600 shadow-sm animate-pulse">
              Loading staff directory...
            </div>
          )}

          {error && (
            <div className="rounded-3xl border border-amber-200 bg-amber-50 p-8 text-amber-900 shadow-sm flex items-start gap-3">
              <FiAlertCircle size={24} className="mt-1 flex-shrink-0" />
              <div>
                <p className="font-semibold">Could not load Staff Directory</p>
                <p className="text-sm">{error}</p>
              </div>
            </div>
          )}

          {!loading && !error && (
            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
              {filteredAndSorted.length === 0 ? (
                <div className="col-span-full rounded-3xl border border-slate-200 bg-white p-16 text-center text-slate-500 shadow-sm">
                  No staff members matched your search.
                </div>
              ) : (
                filteredAndSorted.map((employee, index) => {
                  const theme = getRoleTheme(employee.title);
                  return (
                    <AnimateOnScroll key={employee.id || index} delay={index % 4 * 50}>
                      <div className={`h-full rounded-2xl border bg-gradient-to-br ${theme.bg} ${theme.border} p-6 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1`}>
                        <div>
                          <div className="flex items-center gap-4 mb-4">
                            {/* Avatar container */}
                            <div className="h-16 w-16 rounded-full bg-white border border-slate-100 overflow-hidden flex items-center justify-center text-slate-400 flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-300">
                              {employee.image ? (
                                <img src={employee.image} alt={employee.name} className="h-full w-full object-cover" />
                              ) : (
                                <div className="h-full w-full flex items-center justify-center bg-slate-100 text-primary-700 font-bold text-xl">
                                  {employee.name.charAt(0)}
                                </div>
                              )}
                            </div>
                            <div>
                              <h3 className="text-base font-bold text-slate-900 group-hover:text-primary-600 transition-colors leading-snug">
                                {employee.name}
                              </h3>
                              <p className={`text-xs font-semibold mt-1 px-2.5 py-0.5 rounded-full border inline-block ${theme.badge}`}>
                                {employee.title}
                              </p>
                            </div>
                          </div>

                          {employee.bio && (
                            <p className="mb-4 text-xs text-slate-600 leading-relaxed">
                              {stripHtml(employee.bio).slice(0, 120)}
                              {employee.bio.length > 120 ? '...' : ''}
                            </p>
                          )}

                          <div className="mt-2 pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <div className="flex items-center gap-2 text-xs text-slate-600">
                              <FiBriefcase className="text-slate-400 flex-shrink-0" size={14} />
                              <span className="truncate" title={employee.department}>
                                {employee.department}
                              </span>
                            </div>
                          </div>
                        </div>

                        {employee.email && (
                          <div className="mt-4 pt-2">
                            <a
                              href={`mailto:${employee.email}`}
                              className="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition-all shadow-sm"
                            >
                              <FiMail size={14} /> Email
                            </a>
                          </div>
                        )}
                      </div>
                    </AnimateOnScroll>
                  );
                })
              )}
            </div>
          )}
        </div>
      </section>
    </div>
  );
}
