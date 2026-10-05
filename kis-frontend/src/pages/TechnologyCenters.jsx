import { memo } from 'react';
import { FiCpu, FiZap, FiGlobe, FiDatabase, FiArrowRight } from 'react-icons/fi';
import AnimateOnScroll from '../components/AnimateOnScroll';

// Import logos directly
import tickLogo from '../assets/images/technology-center-logos/Gemini_Generated_Image_pnp74xpnp74xpnp7.png';
import ceradLogo from '../assets/images/technology-center-logos/Gemini_Generated_Image_vs9mcmvs9mcmvs9m.png';
import huaweiLogo from '../assets/images/technology-center-logos/Gemini_Generated_Image_eb68vseb68vseb68.png';
import cleLogo from '../assets/images/technology-center-logos/Gemini_Generated_Image_hctythctythctyth.png';

const technologyCenters = [
  {
    id: 1,
    name: 'Technology Incubation & Commercialization Centre',
    shortName: 'TICK',
    description: 'Fostering innovation and entrepreneurship by incubating startups and commercializing cutting-edge technologies developed at KICS.',
    logo: tickLogo,
    icon: FiCpu,
    
    textColor: 'text-blue-600',
    achievements: ['50+ Startups Incubated', '20+ Commercialized Products', '10+ Patents Filed'],
  },
  {
    id: 2,
    name: 'Huawei UET Joint Telecom IT Centre',
    shortName: 'HUTIC',
    description: 'A strategic partnership between Huawei and UET Lahore focused on telecommunications research, 5G/6G technologies, and IT innovation.',
    logo: huaweiLogo,
    icon: FiGlobe,
   
    textColor: 'text-red-600',
    achievements: ['5G Research Lab', '30+ Joint Projects', '100+ Trained Professionals'],
  },
  {
    id: 3,
    name: 'Center for Language Engineering',
    shortName: 'CLE',
    description: 'Advancing natural language processing, computational linguistics, and AI-powered language technologies for Urdu and regional languages.',
    logo: cleLogo,
    icon: FiDatabase,
   
    textColor: 'text-purple-600',
    achievements: ['Urdu NLP Tools', '20+ Research Papers', 'Language Databases'],
  },
  {
    id: 4,
    name: 'Center of Energy Research And Development',
    shortName: 'CERAD',
    description: 'Pioneering research in renewable energy, energy efficiency, smart grids, and sustainable power solutions for Pakistan\'s energy challenges.',
    logo: ceradLogo,
    icon: FiZap,
   
    textColor: 'text-green-600',
    achievements: ['Solar Research', '10+ Industry Projects', 'Smart Grid Solutions'],
  },
];

const TechnologyCenters = memo(() => {
  return (
    <section className="relative py-16 sm:py-20 bg-gradient-to-b from-white via-blue-50/20 to-white overflow-hidden">
      {/* Decorative background */}
      <div className="absolute inset-0 opacity-5">
        <div className="absolute top-20 left-10 w-64 h-64 bg-blue-500 rounded-full blur-3xl" />
        <div className="absolute bottom-20 right-10 w-80 h-80 bg-purple-500 rounded-full blur-3xl" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6">
        {/* Header */}
        <AnimateOnScroll>
          <div className="text-center mb-12 sm:mb-16">
            <span className="inline-block text-blue-700 font-bold uppercase tracking-[0.3em] text-xs mb-3">
              Technology Centers
            </span>
            <h2 className="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 mb-4 leading-tight">
              Centers of Excellence
            </h2>
            <div className="w-20 h-1 bg-gradient-to-r from-blue-700 to-blue-800 rounded-full mx-auto" />
            <p className="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto mt-6 leading-relaxed">
              KICS houses specialized technology centers driving innovation across telecommunications, 
              language engineering, energy, and entrepreneurship.
            </p>
          </div>
        </AnimateOnScroll>

        {/* Cards Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 mx-auto">
          {technologyCenters.map((center, index) => (
            <AnimateOnScroll key={center.id} delay={index * 100}>
              <div className="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 border border-slate-200 hover:border-slate-300">
                
                {/* ═══════════════════════════════════════════════
                    FULL-WIDTH LOGO - NO PADDING
                    Image goes edge to edge of the card
                ═══════════════════════════════════════════════ */}
                <div className={`relative w-full aspect-square bg-gradient-to-br ${center.color} overflow-hidden`}>
                  {/* Subtle pattern overlay */}
                  <div className="absolute inset-0 bg-black/5" />
                  
                  {/* Full-width logo - NO padding */}
                  <div className="absolute inset-0 flex items-center justify-center">
                    <img
                      src={center.logo}
                      alt={center.shortName}
                      className="w-full h-full object-contain drop-shadow-xl"
                      onError={(e) => {
                        e.target.style.display = 'none';
                        const parent = e.target.parentElement;
                        const Icon = center.icon;
                        if (parent) {
                          parent.innerHTML = `<svg class="w-24 h-24 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`;
                        }
                      }}
                    />
                  </div>
                  
                  {/* Short Name Badge on Image - Top Right */}
                  <div className="absolute top-3 sm:top-4 right-3 sm:right-4 bg-white/90 backdrop-blur-sm px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full shadow-lg z-10">
                    <span className={`text-[10px] sm:text-xs font-bold ${center.textColor}`}>
                      {center.shortName}
                    </span>
                  </div>
                </div>

                {/* ═══════════════════════════════════════════════
                    CONTENT
                ═══════════════════════════════════════════════ */}
                <div className="p-4 sm:p-6">
                  <h3 className="text-base sm:text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-700 transition-colors leading-tight line-clamp-2">
                    {center.name}
                  </h3>
                  
                  <p className="text-slate-600 text-xs sm:text-sm leading-relaxed mb-3 sm:mb-4 line-clamp-3">
                    {center.description}
                  </p>

                  {/* Achievements */}
                  <div className="flex flex-wrap gap-1.5 sm:gap-2">
                    {center.achievements.map((achievement, i) => (
                      <span
                        key={i}
                        className="text-[10px] sm:text-xs font-medium bg-slate-100 text-slate-700 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full border border-slate-200"
                      >
                        {achievement}
                      </span>
                    ))}
                  </div>
                </div>

                {/* ═══════════════════════════════════════════════
                    ACTIONS
                ═══════════════════════════════════════════════ */}
                <div className="px-4 sm:px-6 pb-4 sm:pb-6 pt-0">
                  <button
                    className={`w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r ${center.color} text-white font-semibold text-xs sm:text-sm px-4 py-2 sm:py-2.5 rounded-xl transition-all duration-300 hover:scale-[1.02] hover:shadow-lg`}
                  >
                    Learn More
                    <FiArrowRight size={14} className="sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform" />
                  </button>
                </div>
              </div>
            </AnimateOnScroll>
          ))}
        </div>
      </div>
    </section>
  );
});

TechnologyCenters.displayName = 'TechnologyCenters';

export default TechnologyCenters;