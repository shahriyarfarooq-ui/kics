import { lazy, Suspense } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import SEO from './components/SEO';
import ScrollToTopBtn, { ScrollReset } from './components/ScrollToTop';
import LoadingScreen from './components/LoadingScreen';
import ScrollProgress from './components/ScrollProgress';
import ErrorBoundary from './components/ErrorBoundary';
import Chatbot from './components/Chatbot';
import Research from './pages/Research';
import Innovation from './pages/Innovation';
import NewPage from './pages/NewPage';
import Events from './pages/Events';
import EventDetail from './pages/EventDetail';
import EventCalendar from './pages/EventCalendar';

const TechnologyCenters = lazy(() => import('./pages/TechnologyCenters'));
const ResearchProjects = lazy(() => import('./pages/ResearchProjects'));
const Seminars = lazy(() => import('./pages/Seminars'));

const Home          = lazy(() => import('./pages/Home'));
const About         = lazy(() => import('./pages/About'));
const DirectorMessage = lazy(() => import('./pages/DirectorMessage'));
const Staff         = lazy(() => import('./pages/Staff'));
const StaffDetail   = lazy(() => import('./pages/StaffDetail'));
const ResearchAreas = lazy(() => import('./pages/ResearchAreas'));
const ResearchAreaDetail = lazy(() => import('./pages/ResearchAreaDetail'));
const Publications  = lazy(() => import('./pages/Publications'));
const Conferences   = lazy(() => import('./pages/Conferences'));
const Workshops     = lazy(() => import('./pages/Workshops'));
const ICOSST        = lazy(() => import('./pages/ICOSST'));
const Jobs          = lazy(() => import('./pages/Jobs'));
const JobDetail     = lazy(() => import('./pages/JobDetail'));
const Contact       = lazy(() => import('./pages/Contact'));
const Services      = lazy(() => import('./pages/Services'));
const News          = lazy(() => import('./pages/News'));
const NewsDetail    = lazy(() => import('./pages/NewsDetail'));
const ErpDepartments = lazy(() => import('./pages/ErpDepartments'));
const ErpDepartmentDetail = lazy(() => import('./pages/ErpDepartmentDetail'));
const ErpEmployees  = lazy(() => import('./pages/ErpEmployees'));
const EventList     = lazy(() => import('./pages/EventList'));
const KicsNewsletter = lazy(() => import('./pages/KicsNewsletter'));

function PageSpinner() {
  return (
    <div className="min-h-[60vh] flex items-center justify-center">
      <div className="w-10 h-10 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin" />
    </div>
  );
}

function NotFound() {
  return (
    <>
      <SEO
        title="Page Not Found"
        description="The requested KICS page could not be found."
        noIndex
      />
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <div className="text-center px-4">
          <p className="text-7xl font-bold text-slate-200 mb-4">404</p>
          <h1 className="text-2xl font-bold text-slate-800 mb-3">Page Not Found</h1>
          <p className="text-slate-500 mb-6">The page you are looking for does not exist.</p>
          <a href="/" className="btn-primary inline-flex">Go Home</a>
        </div>
      </div>
    </>
  );
}

export default function App() {
  return (
    <ErrorBoundary>
      <BrowserRouter>
        <LoadingScreen />
        <ScrollProgress />
        <ScrollReset />
        <div className="flex flex-col min-h-screen overflow-x-hidden">
          <Navbar />
          <main className="flex-1 pt-[68px] lg:pt-[100px]">
            <Suspense fallback={<PageSpinner />}>
              <Routes>
                <Route path="/"                  element={<Home />} />
                <Route path="/about"             element={<About />} />
                <Route path="/director-message"  element={<DirectorMessage />} />
                <Route path="/staff"             element={<Navigate to="/kics-employees" replace />} />
                {/* Legacy /StaffPage path (case-insensitive links) */}
                <Route path="/StaffPage"         element={<Navigate to="/kics-departments" replace />} />
                <Route path="/staff/:id"          element={<StaffDetail />} />
                <Route path="/research-areas"    element={<ResearchAreas />} />
                <Route path="/research-areas/:code" element={<ResearchAreaDetail />} />
                <Route path="/publications"      element={<Publications />} />
                <Route path="/conferences"       element={<Conferences />} />
                <Route path="/workshops"         element={<Workshops />} />
                <Route path="/icosst"            element={<ICOSST />} />
                <Route path="/jobs"              element={<Jobs />} />
                <Route path="/jobs/:id"           element={<JobDetail />} />
                <Route path="/contact"           element={<Contact />} />
                <Route path="/services"          element={<Services />} />
                <Route path="/news"              element={<News />} />
                <Route path="/news/:id"           element={<NewsDetail />} />
                <Route path="/kics-departments"    element={<ErpDepartments />} />
                <Route path="/kics-departments/:id" element={<ErpDepartmentDetail />} />
                <Route path="/kics-employees"     element={<ErpEmployees />} />
                <Route path="/technology-centers" element={<TechnologyCenters />} />
                <Route path="/research-projects" element={<ResearchProjects />} />
                <Route path="/seminars"          element={<Seminars />} />
                <Route path="*"                  element={<NotFound />} />
                <Route path="/research" element={<Research />} />
                <Route path="/innovation" element={<Innovation />} />
                <Route path="/new-page" element={<NewPage />} />
                <Route path="/event-list" element={<EventList />} />
                <Route path="/kics-newsletter" element={<KicsNewsletter />} />
                <Route path="/events" element={<Events />} />
<Route path="/events/:slug" element={<EventDetail />} />
<Route path="/events/calendar" element={<EventCalendar />} />
              </Routes>
            </Suspense>
          </main>
          <Footer />
          <ScrollToTopBtn />
          <Chatbot />
        </div>
      </BrowserRouter>
    </ErrorBoundary>
  );
}
