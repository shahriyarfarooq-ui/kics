import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { FiSearch, FiChevronRight, FiAlertCircle, FiLayers } from 'react-icons/fi';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { erpService } from '../services';
import departmentsBanner from '../assets/images/kics-departments-banner.jpg';
import { buildImageUrl } from '../utils/image';

const departmentMapper = (item) => ({
  id: item.id ?? item.kics_id,
  name: item.name || item.complete_name || 'Unnamed Department',
  code: item.dept_code || item.department_code || 'N/A',
  manager: item.manager || item.project_manager || 'N/A',
  campus: item.campus || 'N/A',
  projects: item.projects_count ?? item.projects?.length ?? 0,
  logo: buildImageUrl(item.logo, ''),
  coverImage: buildImageUrl(item.cover_image || item.logo, ''),
});

export default function ErpDepartments() {
  const [departments, setDepartments] = useState([]);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    const fetchData = async () => {
      try {
        setLoading(true);
        setError('');

        const data = await erpService.listDepartments();
        if (!active) return;

        if (Array.isArray(data)) {
          setDepartments(data.map(departmentMapper));
        } else {
          setError('Unexpected ERP department data format.');
        }
      } catch (err) {
        setError(
          err.status === 404
            ? 'ERP department API not found.'
            : err.message || 'Unable to load ERP departments.'
        );
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchData();
    return () => { active = false; };
  }, []);

  const filtered = departments.filter((department) =>
    department.name.toLowerCase().includes(search.toLowerCase()) ||
    department.code.toLowerCase().includes(search.toLowerCase()) ||
    department.manager.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <SEO
        title="ERP Departments"
        description="Browse ERP departments synced from the KICS API."
        breadcrumbs={[{ label: 'Home', url: '/' }, { label: 'KICS Departments', url: '/kics-departments' }]}
      />

      <PageHero
        title="LABS"
        subtitle="View the departments loaded from the ERP system and inspect their project counts."
        breadcrumbs={[{ label: 'Home', to: '/' }, { label: 'KICS Departments' }]}
        backgroundImage={departmentsBanner}
      />

      <section className="py-16 bg-slate-50">
        <div className="max-w-6xl mx-auto px-4 sm:px-6">
          <div className="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div className="relative w-full sm:max-w-md">
              <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
              <input
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder="Search departments..."
                className="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500"
              />
            </div>
          </div>

          {loading && (
            <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600 shadow-sm">
              Loading ERP departments...
            </div>
          )}

          {error && (
            <div className="rounded-3xl border border-amber-200 bg-amber-50 p-8 text-amber-900 shadow-sm flex items-start gap-3">
              <FiAlertCircle size={24} className="mt-1" />
              <div>
                <p className="font-semibold">Could not load ERP departments</p>
                <p className="text-sm">{error}</p>
              </div>
            </div>
          )}

          {!loading && !error && (
            <div className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
              {filtered.length === 0 ? (
                <div className="col-span-full rounded-3xl border border-slate-200 bg-white p-12 text-center text-slate-500 shadow-sm">
                  No ERP departments found.
                </div>
              ) : (
                filtered.map((department) => (
                  <AnimateOnScroll key={department.id}>
                    <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition-all">
                      <div className="mb-5 flex h-36 items-center justify-center overflow-hidden rounded-2xl bg-slate-50">
                        {department.logo ? (
                          <img src={department.logo} alt={`${department.name} logo`} className="h-full w-full object-contain p-4" onError={(event) => { event.currentTarget.style.display = 'none'; }} />
                        ) : (
                          <FiLayers size={42} className="text-primary-500" aria-hidden="true" />
                        )}
                      </div>
                      <div>
                        <p className="text-xs uppercase tracking-[0.3em] text-primary-600 font-semibold">Lab</p>
                        <h2 className="mt-3 text-xl font-semibold text-slate-900">{department.name}</h2>
                      </div>

                      <div className="mt-6 space-y-3 text-sm text-slate-600">
                        <p><span className="font-semibold text-slate-800">Code:</span> {department.code}</p>
                        <p><span className="font-semibold text-slate-800">Manager:</span> {department.manager}</p>
                        <p><span className="font-semibold text-slate-800">Campus:</span> {department.campus}</p>
                      </div>

                      <div className="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <Link
                          to={`/kics-departments/${department.id}`}
                          state={{ department }}
                          className="inline-flex items-center justify-center gap-2 rounded-full bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 transition-colors"
                        >
                          View Lab <FiChevronRight size={16} />
                        </Link>
                        <p className="text-xs text-slate-400">See projects and staff</p>
                      </div>
                    </div>
                  </AnimateOnScroll>
                ))
              )}
            </div>
          )}
        </div>
      </section>
    </div>
  );
}
