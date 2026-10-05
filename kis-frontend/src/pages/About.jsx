// kics-frontend/src/pages/About.jsx
import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { aboutService, visionService } from '../services';
import { stripHtml } from '../utils/contentMappers';
import { getImageLoadingProps } from '../utils/image';
import staffBanner from '../assets/images/staff-banner.jpg';

const timeline = [
  { year: '2002', event: 'KICS established at UET Lahore under HEC funding.' },
  { year: '2005', event: 'First research lab operational; initial publications in IEEE journals.' },
  { year: '2008', event: 'Collaboration signed with Huawei for telecom research.' },
  { year: '2012', event: 'Technology Incubation Center (TIC) launched to support startups.' },
  { year: '2015', event: 'ICOSST conference series inaugurated, gaining international recognition.' },
  { year: '2018', event: 'Expanded to 20+ specialized labs across five technology domains.' },
  { year: '2021', event: 'ICOSST 2021 hosted in hybrid format during the global pandemic.' },
  { year: '2024', event: 'Major AI Summit held; new Director Prof. Dr. Hafiz Shahzad Asif appointed.' },
  { year: '2026', event: 'KICS incubated startup wins $20K at NVC; MoU with MNS-UET Multan.' },
];

export default function About() {
  const [about, setAbout] = useState(null);
  const [vision, setVision] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    let active = true;

    Promise.allSettled([aboutService.latest(), visionService.latest()])
      .then(([aboutResult, visionResult]) => {
        if (!active) return;
        if (aboutResult.status === 'fulfilled') setAbout(aboutResult.value);
        if (visionResult.status === 'fulfilled') setVision(visionResult.value);
        if (aboutResult.status === 'rejected' && visionResult.status === 'rejected') {
          setError('Unable to load the latest About and Vision content. Showing fallback content.');
        }
        setLoading(false);
      });

    return () => { active = false; };
  }, []);

  const storyOne = stripHtml(about?.section_1);
  const storyTwo = stripHtml(about?.section_2);
  const visionText = stripHtml(vision?.section_vision);

  return (
    <div>
      <SEO
        title="About KICS"
        description="Learn about KICS — the Al-Khwarizmi Institute of Computer Science at UET Lahore, established in 2002. Our history, vision, mission and milestones."
        breadcrumbs={[{ label: 'About KICS', url: '/about' }]}
      />
      <PageHero
        title="About KICS"
        subtitle="Advancing knowledge through applied research, innovation and collaboration since 2002."
        breadcrumbs={[{ label: 'About KICS' }]}
        backgroundImage={staffBanner}
      />

      {/* Intro */}
      <section className="py-12 sm:py-16 lg:py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          {loading && (
            <div className="mb-6 text-sm text-primary-700 bg-primary-50 border border-primary-100 rounded-lg px-4 py-3">
              Loading latest institute profile...
            </div>
          )}
          {error && (
            <div className="mb-6 text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3">
              {error}
            </div>
          )}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-14 items-center">
            <AnimateOnScroll animation="reveal-left">
              <div className="text-center lg:text-left">
                <span className="eyebrow">Our Story</span>
                <h2 className="section-title mb-4">Al-Khwarizmi Institute of Computer Science</h2>
                <div className="divider mx-auto lg:mx-0" />
              </div>
              {storyOne ? (
                <p className="text-slate-600 leading-relaxed mb-4">{storyOne}</p>
              ) : (
              <p className="text-slate-600 leading-relaxed mb-4">
                The Al-Khwarizmi Institute of Computer Science (KICS) is a research and development
                institution established in August 2002 at the University of Engineering &amp; Technology (UET)
                Lahore. It was created to conduct high-quality applied research in collaboration with
                industries and academic institutions — nationally and internationally.
              </p>
              )}
              {storyTwo ? (
                <p className="text-slate-600 leading-relaxed mb-4">{storyTwo}</p>
              ) : (
              <p className="text-slate-600 leading-relaxed mb-4">
                KICS takes its name from Muhammad ibn Musa Al-Khwarizmi (780–850 AD), the legendary Muslim
                mathematician whose work established the foundations of algebra and introduced the concept
                of algorithms — a term derived directly from his name.
              </p>
              )}
              <p className="text-slate-600 leading-relaxed">
                Today, with over 25 specialized labs and centers spanning AI, cybersecurity, IoT, enterprise
                software, power systems, and industrial automation, KICS stands as one of Pakistan's leading
                centers for applied computer science research.
              </p>
            </AnimateOnScroll>

            <AnimateOnScroll animation="reveal-right">
              <div className="relative rounded-2xl overflow-hidden shadow-card-hover">
                <img src={staffBanner}
                  alt="KICS Institute"
                  {...getImageLoadingProps({ sizes: '(min-width: 1024px) 50vw, 100vw' })}
                  className="w-full h-80 object-cover object-center"
                  onError={e => { e.target.src='https://placehold.co/600x320/4a1209/fae3de?text=KICS+UET+Lahore'; }} />
                <div className="absolute bottom-5 left-5 right-5 bg-gradient-to-t from-primary-900/90 via-primary-900/60 to-transparent pt-20 -mb-5 pb-5">
                  <p className="text-white font-heading font-bold text-lg">KICS, UET Lahore</p>
                  <p className="text-white/70 text-sm">Est. 2002 — G.T. Road, Lahore</p>
                </div>
              </div>
            </AnimateOnScroll>
          </div>
        </div>
      </section>

      {/* Namesake */}
      <section className="py-12 sm:py-16 bg-primary-900 bg-dot-pattern" id="vision">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 lg:gap-8">
            <AnimateOnScroll>
              <div className="bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl p-5 sm:p-6 lg:p-8 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <h3 className="text-white font-bold text-xl sm:text-2xl mb-3 sm:mb-4">Our Namesake</h3>
                <p className="text-white/90 text-sm sm:text-base leading-relaxed">
                  Muhammad ibn Musa Al-Khwarizmi (780–850 AD) was a Muslim scholar who revolutionized
                  mathematics. He developed foundational concepts in algebra and trigonometry, and
                  the term "algorithm" itself derives from his name. KICS honors this legacy of
                  computational thinking and innovation.
                </p>
              </div>
            </AnimateOnScroll>
            <AnimateOnScroll delay={100}>
              <div className="bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl p-5 sm:p-6 lg:p-8 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <h3 className="text-white font-bold text-xl sm:text-2xl mb-3 sm:mb-4">Our Mission</h3>
                <p className="text-white/90 text-sm sm:text-base leading-relaxed">
                  To conduct result-oriented research and development activities using ICT for national socio-economic
                  advancement. We bridge the gap between academia and industry while fostering innovation in Computer
                  Sciences and Information Technology to advance science and technology throughout Pakistan.
                </p>
              </div>
            </AnimateOnScroll>
            <AnimateOnScroll delay={200} className="md:col-span-2">
              <div className="bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl p-5 sm:p-6 lg:p-8 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <h3 className="text-white font-bold text-xl sm:text-2xl mb-3 sm:mb-4">Our Vision</h3>
                {visionText ? (
                  <p className="text-white/90 text-sm sm:text-base leading-relaxed">{visionText}</p>
                ) : (
                  <p className="text-white/90 text-sm sm:text-base leading-relaxed">
                    To drive Pakistan toward a knowledge-based economy by developing world-class research in algorithms,
                    software engineering, and digital control systems. KICS aims to be recognized as a premier research
                    and advanced technology organization, serving as a center of excellence for cutting-edge research,
                    technology innovation, and industrial collaboration.
                  </p>
                )}
              </div>
            </AnimateOnScroll>
          </div>
        </div>
      </section>

      {/* Timeline */}
      <section className="py-12 sm:py-16 lg:py-20 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          <AnimateOnScroll>
            <div className="text-center mb-12">
              <span className="eyebrow">Our Journey</span>
              <h2 className="section-title">KICS Milestones</h2>
              <div className="divider-center mt-3" />
            </div>
          </AnimateOnScroll>
          <div className="relative">
            <div className="absolute left-6 sm:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-primary-600 via-cyan-500 to-primary-600 opacity-30" />
            {timeline.map((item, i) => (
              <AnimateOnScroll key={i} animation={i % 2 === 0 ? 'reveal-left' : 'reveal-right'} delay={i * 60}>
                <div className={`relative flex items-start gap-6 mb-8 ${i % 2 === 0 ? 'sm:flex-row' : 'sm:flex-row-reverse'}`}>
                  <div className={`flex-1 ${i % 2 === 0 ? 'sm:text-right sm:pr-8' : 'sm:pl-8'} pl-12 sm:pl-0`}>
                    <div className="card p-4 inline-block text-left">
                      <span className="text-cyan-500 font-bold text-sm">{item.year}</span>
                      <p className="text-slate-600 text-sm mt-1">{item.event}</p>
                    </div>
                  </div>
                  <div className="absolute left-4 sm:left-1/2 sm:-translate-x-1/2 w-4 h-4 rounded-full bg-cyan-500 border-2 border-white shadow-lg shadow-cyan-500/50 flex-shrink-0 mt-1" />
                  <div className="hidden sm:block flex-1" />
                </div>
              </AnimateOnScroll>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-14 bg-slate-50">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 text-center">
          <AnimateOnScroll animation="reveal-scale">
            <h2 className="section-title mb-4">Explore More</h2>
            <p className="text-slate-500 mb-8">Learn about our research, team, and opportunities.</p>
            <div className="flex flex-wrap justify-center gap-4">
              <Link to="/kics-departments" className="btn-primary">ERP Departments</Link>
              <Link to="/director-message" className="btn-primary">Director's Message</Link>
              <Link to="/research-areas" className="btn-primary">Research Labs</Link>
            </div>
          </AnimateOnScroll>
        </div>
      </section>
    </div>
  );
}
