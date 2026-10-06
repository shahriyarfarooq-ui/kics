//src/pages/ResearchAreaDetail.jsx
import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { groupService } from '../services';
import { buildImageUrl, stripHtml } from '../utils/contentMappers';
import {
  FiArrowLeft,
  FiBookOpen,
  FiUsers,
  FiLayers,
  FiFileText
} from 'react-icons/fi';

export default function ResearchAreaDetail() {
  const { code } = useParams();
  const [group, setGroup] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [activeTab, setActiveTab] = useState('staff');

  useEffect(() => {
    let active = true;

    groupService.get(code)
      .then((data) => {
        if (active) setGroup(data);
      })
      .catch(() => {
        if (active) setError('Unable to load this research group.');
      })
      .finally(() => {
        if (active) setLoading(false);
      });

    return () => { active = false; };
  }, [code]);

  const title = group?.group_name || group?.name || code || 'Research Group';
  const description = stripHtml(group?.group_description || group?.group_briefdescription);
  const staff = Array.isArray(group?.staff) ? group.staff : [];
  const publications = Array.isArray(group?.publications) ? group.publications : [];
  const projects = Array.isArray(group?.projects) ? group.projects : [];
  const image = buildImageUrl(group?.group_banner || group?.img_path, '');

  return (
    <div>
      <SEO
        title={title}
        description={description || 'KICS research group detail.'}
        image={image}
        path={`/research-areas/${code}`}
        breadcrumbs={[{ label: 'Research Areas', url: '/research-areas' }, { label: title, url: `/research-areas/${code}` }]}
      />
      <PageHero
        title={title}
        //subtitle={description || 'Specialized research lab and center at KICS.'}
        breadcrumbs={[{ label: 'Research Areas', to: '/research-areas' }, { label: 'Detail' }]}
      />

      <section className="py-16 bg-white">
        <div className="max-w-6xl mx-auto px-4 sm:px-6">
          <Link to="/research-areas" className="inline-flex items-center gap-2 text-sm font-semibold text-primary-700 hover:text-cyan-600 mb-8">
            <FiArrowLeft size={14} /> Back to Research Areas
          </Link>

          {loading && (
            <div className="text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading research group...
            </div>
          )}

          {error && (
            <div className="text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              {error}
            </div>
          )}

          {group && (
            <AnimateOnScroll>
              <div className="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100">

                {/* Banner */}
                {image && (
                  <img
                    src={image}
                    alt={title}
                    className="w-full h-72 object-cover"
                    onError={(e) => {
                      e.currentTarget.style.display = 'none';
                    }}
                  />
                )}

                {/* Header */}
                <div className="p-8 border-b">
                  <span className="inline-flex px-3 py-1 rounded-full bg-cyan-100 text-cyan-700 text-sm font-medium">
                    {group.code}
                  </span>

                  <h1 className="text-3xl font-bold text-primary-900 mt-4">
                    {title}
                  </h1>

                  <p className="mt-4 text-slate-600">
                    {description}
                  </p>

                  <div className="grid grid-cols-3 gap-4 mt-8">
                    <div className="bg-slate-50 rounded-xl p-4 text-center">
                      <div className="text-2xl font-bold text-cyan-600">
                        {staff.length}
                      </div>
                      <div className="text-sm text-slate-500">
                        Staff
                      </div>
                    </div>

                    <div className="bg-slate-50 rounded-xl p-4 text-center">
                      <div className="text-2xl font-bold text-cyan-600">
                        {projects.length}
                      </div>
                      <div className="text-sm text-slate-500">
                        Projects
                      </div>
                    </div>

                    <div className="bg-slate-50 rounded-xl p-4 text-center">
                      <div className="text-2xl font-bold text-cyan-600">
                        {publications.length}
                      </div>
                      <div className="text-sm text-slate-500">
                        Publications
                      </div>
                    </div>
                  </div>
                </div>

                {/* Tabs */}
                <div className="border-b bg-slate-50">
                  <div className="flex flex-wrap">

                    <button
                      onClick={() => setActiveTab('overview')}
                      className={`px-6 py-4 font-medium transition ${
                        activeTab === 'overview'
                          ? 'border-b-2 border-cyan-600 text-cyan-600 bg-white'
                          : 'text-slate-500'
                      }`}
                    >
                      Overview
                    </button>

                    <button
                      onClick={() => setActiveTab('staff')}
                      className={`px-6 py-4 font-medium transition ${
                        activeTab === 'staff'
                          ? 'border-b-2 border-cyan-600 text-cyan-600 bg-white'
                          : 'text-slate-500'
                      }`}
                    >
                      Staff ({staff.length})
                    </button>

                    <button
                      onClick={() => setActiveTab('projects')}
                      className={`px-6 py-4 font-medium transition ${
                        activeTab === 'projects'
                          ? 'border-b-2 border-cyan-600 text-cyan-600 bg-white'
                          : 'text-slate-500'
                      }`}
                    >
                      Projects ({projects.length})
                    </button>

                    <button
                      onClick={() => setActiveTab('publications')}
                      className={`px-6 py-4 font-medium transition ${
                        activeTab === 'publications'
                          ? 'border-b-2 border-cyan-600 text-cyan-600 bg-white'
                          : 'text-slate-500'
                      }`}
                    >
                      Publications ({publications.length})
                    </button>

                  </div>
                </div>

                {/* Tab Content */}
                <div className="p-8">

                  {/* OVERVIEW */}
                  {activeTab === 'overview' && (
                    <div>
                      <h2 className="text-2xl font-bold mb-6">
                        About this Research Group
                      </h2>

                      <div
                        className="prose max-w-none"
                        dangerouslySetInnerHTML={{
                          __html: group.group_description
                        }}
                      />
                    </div>
                  )}

                  {/* STAFF */}
                  {activeTab === 'staff' && (
                    <div>
                      <h2 className="text-2xl font-bold mb-6">
                        Research Staff
                      </h2>

                      {staff.length === 0 ? (
                        <p>No staff found.</p>
                      ) : (
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                          {staff.map((person) => (
                            <div
                              key={person.people_id}
                              className="bg-white border rounded-xl p-5 text-center hover:shadow-lg transition"
                            >
                              <img
                                src={buildImageUrl(person.image_name, 'people')}
                                alt={`${person.fname} ${person.lname}`}
                                className="w-28 h-28 rounded-full object-cover mx-auto mb-4"
                                onError={(e) => {
                                  e.target.src =
                                    'https://via.placeholder.com/150';
                                }}
                              />

                              <h3 className="font-semibold text-lg">
                                {person.fname} {person.lname}
                              </h3>

                              <p className="text-sm text-slate-500">
                                {person.designation?.designation_name ||
                                  'Research Staff'}
                              </p>

                              {person.email && (
                                <p className="text-xs text-cyan-600 mt-2">
                                  {person.email}
                                </p>
                              )}
                            </div>
                          ))}
                        </div>
                      )}
                    </div>
                  )}

                  {/* PROJECTS */}
                  {activeTab === 'projects' && (
                    <div>
                      <h2 className="text-2xl font-bold mb-6">
                        Research Projects
                      </h2>

                      <div className="space-y-6">
                        {projects.map((project) => (
                          <div
                            key={project.projectlist_id}
                            className="border rounded-xl p-6 hover:shadow-md transition"
                          >
                            <div className="flex justify-between items-start flex-wrap gap-3">
                              <h3 className="font-semibold text-xl">
                                {project.projectlist_Name}
                              </h3>

                              <span
                                className={`px-3 py-1 rounded-full text-xs ${
                                  project.is_completed
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-blue-100 text-blue-700'
                                }`}
                              >
                                {project.is_completed
                                  ? 'Completed'
                                  : 'Ongoing'}
                              </span>
                            </div>

                            <div
                              className="mt-4 prose max-w-none"
                              dangerouslySetInnerHTML={{
                                __html: project.projectlist_description
                              }}
                            />
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* PUBLICATIONS */}
                  {activeTab === 'publications' && (
                    <div>
                      <h2 className="text-2xl font-bold mb-6">
                        Publications
                      </h2>

                      <div className="space-y-4">
                        {publications.map((pub) => (
                          <div
                            key={pub.publication_id}
                            className="border rounded-xl p-5 hover:shadow-md transition"
                          >
                            <h3 className="font-semibold text-lg">
                              {pub.publication_title}
                            </h3>

                            <p className="text-slate-600 mt-2">
                              {pub.author}
                            </p>

                            <p className="text-sm text-cyan-600 mt-1">
                              {pub.publication_year}
                            </p>

                            {pub.journal && (
                              <p className="text-sm text-slate-500 mt-2">
                                {pub.journal}
                              </p>
                            )}
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                </div>
              </div>
            </AnimateOnScroll>
          )}
        </div>
      </section>
    </div>
  );
}