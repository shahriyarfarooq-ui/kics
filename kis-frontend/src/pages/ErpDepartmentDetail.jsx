import { useEffect, useState } from 'react';
import { useParams, useLocation, Link } from 'react-router-dom';
import { FiArrowLeft, FiAlertCircle, FiUsers, FiFolder } from 'react-icons/fi';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { erpService } from '../services';

const mapProject = (project) => ({
  id: project.id ?? project.kics_id,
  name: project.name || 'Unnamed Project',
  manager: project.project_manager || 'Unknown',
  coordinator: project.project_coordinator || 'Unknown',
  state: project.project_states || 'Unknown',
  type: project.project_type || 'Unknown',
});

export default function ErpDepartmentDetail() {
  const { id } = useParams();
  const location = useLocation();
  const [projects, setProjects] = useState([]);
  const [employees, setEmployees] = useState([]);
  const [department, setDepartment] = useState(location.state?.department || null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [viewMode, setViewMode] = useState('projects'); // 'projects' or 'staff'

  const normalizeDepartmentValue = (value = '') =>
    String(value)
      .toLowerCase()
      .replace(/lab(?:oratory)?/g, '')
      .replace(/[^a-z0-9]/g, '')
      .trim();

  const departmentMatchesEmployee = (employee) => {
    if (!department || !employee?.department) return false;
    const normalizedEmployee = normalizeDepartmentValue(employee.department);
    const matchValues = [department.name, department.code, department.campus]
      .filter(Boolean)
      .map(normalizeDepartmentValue);

    return matchValues.some((value) => value && normalizedEmployee.includes(value));
  };

  const departmentStaff = employees.filter(departmentMatchesEmployee);

  useEffect(() => {
    let active = true;

    const fetchResources = async () => {
      try {
        setLoading(true);
        setError('');

        const [projectData, employeeData] = await Promise.all([
          erpService.listDepartmentProjects(id),
          erpService.listEmployees(),
        ]);

        if (!active) return;

        if (Array.isArray(projectData)) {
          setProjects(projectData.map(mapProject));
        } else {
          throw new Error('Unexpected ERP project data format.');
        }

        if (Array.isArray(employeeData)) {
          setEmployees(employeeData);
        } else {
          throw new Error('Unexpected staff data format.');
        }

        if (!department && location.state?.department) {
          setDepartment(location.state.department);
        }

        if (!department) {
          setDepartment({ id, name: `Department ${id}` });
        }
      } catch (err) {
        setError(
          err.status === 404
            ? 'ERP department projects or staff API not found.'
            : err.message || 'Unable to load ERP department data.'
        );
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchResources();
    return () => { active = false; };
  }, [id, location.state, department]);

  return (
    <div>
      <SEO
        title={department ? `${department.name} Projects` : 'ERP Department Projects'}
        description="View projects for the selected ERP department."
        breadcrumbs={[{ label: 'Home', url: '/' }, { label: 'ERP Departments', url: '/kics-departments' }, { label: department?.name || 'Department' }]}
      />

      <PageHero
        title={department ? `${department.name} Projects` : 'ERP Department Projects'}
        subtitle="Explore the projects associated with the selected ERP department."
        breadcrumbs={[{ label: 'Home', to: '/' }, { label: 'ERP Departments', to: '/kics-departments' }, { label: department?.name || 'Department' }]}
      />

      <section className="py-16 bg-slate-50">
        <div className="max-w-6xl mx-auto px-4 sm:px-6">
          <div className="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <Link
              to="/kics-departments"
              className="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-primary-300 hover:text-primary-700"
            >
              <FiArrowLeft size={16} /> Back to Departments
            </Link>
            
            {/* Toggle Button */}
            <div className="flex items-center gap-2 bg-white rounded-full border border-slate-200 p-1 shadow-sm">
              <button
                onClick={() => setViewMode('projects')}
                className={`flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-all ${
                  viewMode === 'projects'
                    ? 'bg-primary-600 text-white shadow-md'
                    : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50'
                }`}
              >
                <FiFolder size={16} />
                Projects ({projects.length})
              </button>
              <button
                onClick={() => setViewMode('staff')}
                className={`flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-all ${
                  viewMode === 'staff'
                    ? 'bg-primary-600 text-white shadow-md'
                    : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50'
                }`}
              >
                <FiUsers size={16} />
                Staff ({departmentStaff.length})
              </button>
            </div>
          </div>

          {loading && (
            <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600 shadow-sm">
              Loading department data...
            </div>
          )}

          {error && (
            <div className="rounded-3xl border border-amber-200 bg-amber-50 p-8 text-amber-900 shadow-sm flex items-start gap-3">
              <FiAlertCircle size={24} className="mt-1" />
              <div>
                <p className="font-semibold">Could not load ERP data</p>
                <p className="text-sm">{error}</p>
              </div>
            </div>
          )}

          {!loading && !error && (
            <>
              {/* Projects View */}
              {viewMode === 'projects' && (
                <>
                  {projects.length === 0 ? (
                    <div className="rounded-3xl border border-slate-200 bg-white p-16 text-center text-slate-500 shadow-sm">
                      No projects found for this ERP department.
                    </div>
                  ) : (
                    <div className="grid gap-4 sm:grid-cols-2">
                      {projects.map((project) => (
                        <AnimateOnScroll key={project.id}>
                          <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                            <p className="text-xs uppercase tracking-[0.2em] text-primary-600 font-semibold mb-3">Project</p>
                            <h2 className="text-xl font-semibold text-slate-900 mb-3">{project.name}</h2>
                            <div className="space-y-2 text-sm text-slate-600">
                              <p><span className="font-semibold text-slate-800">State:</span> {project.state}</p>
                              <p><span className="font-semibold text-slate-800">Type:</span> {project.type}</p>
                              <p><span className="font-semibold text-slate-800">Manager:</span> {project.manager}</p>
                              <p><span className="font-semibold text-slate-800">Coordinator:</span> {project.coordinator}</p>
                            </div>
                          </div>
                        </AnimateOnScroll>
                      ))}
                    </div>
                  )}
                </>
              )}

              {/* Staff View */}
              {viewMode === 'staff' && (
                <>
                  {departmentStaff.length === 0 ? (
                    <div className="rounded-3xl border border-slate-200 bg-white p-16 text-center text-slate-500 shadow-sm">
                      No staff members were found for this lab.
                    </div>
                  ) : (
                    <div className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                      {departmentStaff.map((employee, index) => (
                        <AnimateOnScroll key={employee.id || index}>
                          <div className="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                            <p className="text-xs uppercase tracking-[0.2em] text-primary-600 font-semibold mb-3">Staff</p>
                            <h3 className="text-xl font-semibold text-slate-900 mb-3">{employee.complete_name || employee.name || 'Unnamed Staff'}</h3>
                            <div className="space-y-2 text-sm text-slate-600">
                              <p><span className="font-semibold text-slate-800">Designation:</span> {employee.job_title || employee.title || 'N/A'}</p>
                              <p><span className="font-semibold text-slate-800">Department:</span> {employee.department || 'N/A'}</p>
                              {employee.work_email || employee.email ? (
                                <p><span className="font-semibold text-slate-800">Email:</span> {employee.work_email || employee.email}</p>
                              ) : null}
                            </div>
                          </div>
                        </AnimateOnScroll>
                      ))}
                    </div>
                  )}
                </>
              )}
            </>
          )}
        </div>
      </section>
    </div>
  );
}