const routePreloaders = [
  { match: (path) => path === '/', load: () => import('../pages/Home') },
  { match: (path) => path.startsWith('/about'), load: () => import('../pages/About') },
  { match: (path) => path.startsWith('/director-message'), load: () => import('../pages/DirectorMessage') },
  { match: (path) => path === '/staff' || path.startsWith('/staff/'), load: () => import('../pages/ErpDepartments') },
  { match: (path) => path === '/research-areas', load: () => import('../pages/ResearchAreas') },
  { match: (path) => path.startsWith('/publications'), load: () => import('../pages/Publications') },
  { match: (path) => path.startsWith('/conferences'), load: () => import('../pages/Conferences') },
  { match: (path) => path.startsWith('/workshops'), load: () => import('../pages/Workshops') },
  { match: (path) => path.startsWith('/icosst'), load: () => import('../pages/ICOSST') },
  { match: (path) => path === '/jobs' || path.startsWith('/jobs/'), load: () => import('../pages/Jobs') },
  { match: (path) => path.startsWith('/contact'), load: () => import('../pages/Contact') },
  { match: (path) => path.startsWith('/services'), load: () => import('../pages/Services') },
  { match: (path) => path === '/news' || path.startsWith('/news/'), load: () => import('../pages/News') },

];

const preloadedRoutes = new Set();

export const preloadRoute = (path) => {
  if (!path || path.startsWith('http') || preloadedRoutes.has(path)) return;

  const preloader = routePreloaders.find(({ match }) => match(path));
  if (!preloader) return;

  preloadedRoutes.add(path);
  preloader.load().catch(() => preloadedRoutes.delete(path));
};
