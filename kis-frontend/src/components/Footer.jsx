import { Link } from 'react-router-dom';
import { FiPhone, FiMail, FiMapPin, FiArrowUp, FiSend } from 'react-icons/fi';
import {
  FaFacebookF,
  FaTwitter,
  FaLinkedinIn,
  FaInstagram,
  FaYoutube,
} from 'react-icons/fa';
import { getImageLoadingProps } from '../utils/image';

const footerCols = [
  {
    heading: 'Quick Links',
    links: [
      { label: 'About Us', to: '/about' },
      { label: "Director's Message", to: '/director-message' },
      { label: 'KICS Departments', to: '/kics-departments' },
      { label: 'Jobs at KICS', to: '/jobs' },
    ],
  },
  {
    heading: 'Research',
    links: [
      { label: 'Research Areas', to: '/research-areas' },
      { label: 'Publications', to: '/publications' },
      { label: 'Collaborations', to: '/research-areas#collaborations' },
    ],
  },
  {
    heading: 'Programs',
    links: [
      { label: 'Conferences', to: '/conferences' },
      { label: 'Workshops', to: '/workshops' },
      // { label: 'e-Rozgar Program', to: '/e-rozgaar' },
      { label: 'News & Updates', to: '/news' },
    ],
  },
];

export default function Footer() {
  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  return (
    <>
      <footer className="relative overflow-hidden">
        {/* Animated Glowing Top Border */}
        <div className="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-primary-600 via-cyan-400 to-primary-600 bg-[length:200%_100%] animate-gradient-x" />

        {/* Dark Navy Background - Solid color for consistency */}
        <div className="absolute inset-0 bg-[#0f1e3d]" />

        {/* Subtle animated background particles */}
        <div className="absolute inset-0 opacity-30">
          <div className="absolute top-20 left-[10%] w-2 h-2 bg-cyan-400 rounded-full animate-pulse" />
          <div className="absolute top-40 right-[15%] w-1.5 h-1.5 bg-primary-400 rounded-full animate-pulse animation-delay-2000" />
          <div className="absolute bottom-32 left-[25%] w-1 h-1 bg-cyan-300 rounded-full animate-pulse animation-delay-4000" />
          <div className="absolute bottom-20 right-[30%] w-2 h-2 bg-primary-300 rounded-full animate-pulse" />
        </div>

        {/* Main Content */}
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 pt-16 pb-8">
          {/* 4-Column Grid */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-8 lg:gap-10 mb-12">

            {/* Column 1: About / Logo */}
            <div className="lg:col-span-1">
              <Link to="/" className="inline-flex items-center gap-3 mb-6 group">
                <div className="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center group-hover:bg-white/20 group-hover:scale-110 transition-all duration-300">
                  <img
                    src="https://kics.edu.pk/web/wp-content/uploads/2018/02/unnamed.png"
                    alt="KICS"
                    {...getImageLoadingProps({ sizes: '36px' })}
                    className="w-9 h-9 object-contain"
                    onError={(e) => (e.target.style.display = 'none')}
                  />
                </div>
                <div>
                  <p className="font-bold text-lg leading-none uppercase text-white tracking-wide">KICS</p>
                  <p className="text-cyan-300 text-xs mt-1 font-semibold">
                    UET Lahore
                  </p>
                </div>
              </Link>

              <p className="text-gray-300 text-sm leading-relaxed mb-6">
                Al-Khwarizmi Institute of Computer Science — Advancing research and innovation since 2002.
              </p>

              {/* Contact Info with Icons */}
              <div className="space-y-3">
                <a
                  href="tel:+924299029450"
                  className="flex items-center gap-3 text-gray-400 hover:text-cyan-400 text-sm transition-all duration-300 group"
                >
                  <div className="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-cyan-500/20 transition-colors">
                    <FiPhone size={14} />
                  </div>
                  <span>+92 42 99029450</span>
                </a>
                <a
                  href="mailto:info@kics.edu.pk"
                  className="flex items-center gap-3 text-gray-400 hover:text-cyan-400 text-sm transition-all duration-300 group"
                >
                  <div className="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-cyan-500/20 transition-colors">
                    <FiMail size={14} />
                  </div>
                  <span>info@kics.edu.pk</span>
                </a>
                <div className="flex items-start gap-3 text-gray-400 text-sm">
                  <div className="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center mt-0.5">
                    <FiMapPin size={14} />
                  </div>
                  <span className="leading-relaxed">G.T. Road, Lahore<br />Punjab, Pakistan</span>
                </div>
              </div>
            </div>

            {/* Columns 2-4: Link Groups */}
            {footerCols.map((col) => (
              <div key={col.heading} className="lg:pl-4">
                <h4 className="text-white font-bold text-base mb-6 tracking-wide">{col.heading}</h4>
                <ul className="space-y-3">
                  {col.links.map((link) => (
                    <li key={link.label}>
                      <Link
                        to={link.to}
                        className="text-gray-400 hover:text-cyan-400 text-sm transition-all duration-300 inline-block hover:translate-x-1 relative group"
                      >
                        <span className="relative">
                          {link.label}
                          <span className="absolute -bottom-1 left-0 w-0 h-[2px] bg-cyan-400 transition-all duration-300 group-hover:w-full" />
                        </span>
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>

          {/* Social Media + Newsletter Section */}
          <div className="border-t border-white/10 pt-10 mb-8">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

              {/* Social Icons - Floating Effect */}
              <div className="text-center lg:text-left">
                <h4 className="text-white font-bold text-sm mb-5 tracking-wide">Connect With Us</h4>
                <div className="flex gap-3 justify-center lg:justify-start">
                  {[
                    { Icon: FaFacebookF, href: 'https://facebook.com/kics.official', color: 'hover:bg-blue-600' },
                    { Icon: FaTwitter, href: 'https://twitter.com/KICSUETLAHORE', color: 'hover:bg-sky-500' },
                    { Icon: FaLinkedinIn, href: 'https://linkedin.com/company/kics', color: 'hover:bg-blue-700' },
                    { Icon: FaInstagram, href: 'https://instagram.com', color: 'hover:bg-gradient-to-br hover:from-purple-600 hover:to-pink-500' },
                    { Icon: FaYoutube, href: 'https://youtube.com', color: 'hover:bg-red-600' },
                  ].map(({ Icon, href, color }, i) => (
                    <a
                      key={i}
                      href={href}
                      target="_blank"
                      rel="noreferrer"
                      className={`w-11 h-11 rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 hover:border-transparent text-gray-300 hover:text-white flex items-center justify-center transition-all duration-300 hover:-translate-y-2 hover:scale-110 hover:shadow-lg hover:shadow-cyan-500/50 ${color}`}
                    >
                      <Icon size={18} />
                    </a>
                  ))}
                </div>
              </div>

              {/* Newsletter / CTA */}
              <div className="text-center lg:text-right">
                <div className="inline-block bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 hover:shadow-lg hover:shadow-cyan-500/20">
                  <p className="text-white font-semibold text-sm mb-2">Stay Updated</p>
                  <p className="text-gray-400 text-xs mb-4">Get latest news and events from KICS</p>
                  <Link
                    to="/news"
                    className="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-cyan-600 text-white font-semibold px-5 py-2.5 rounded-lg hover:scale-105 transition-all duration-300 text-sm hover:shadow-lg hover:shadow-cyan-500/50"
                  >
                    <FiSend size={14} />
                    Latest News
                  </Link>
                </div>
              </div>
            </div>
          </div>

          {/* Bottom Bar - Copyright */}
          <div className="border-t border-white/10 pt-8">
            <div className="flex flex-col md:flex-row justify-between items-center gap-4">
              <div className="text-center md:text-left">
                <p className="text-gray-400 text-xs">
                  © {new Date().getFullYear()} KICS — Al-Khwarizmi Institute of Computer Science, UET Lahore
                </p>
                <p className="text-gray-500 text-xs mt-1">
                  <em>Seeking Truth, Pursuing Innovation</em>
                </p>
              </div>

              {/* Scroll to Top Button */}
              <button
                onClick={scrollToTop}
                className="group flex items-center gap-2 text-gray-400 hover:text-cyan-400 transition-all duration-300"
                aria-label="Scroll to top"
              >
                <span className="text-xs font-semibold uppercase hidden sm:inline">Back to Top</span>
                <div className="w-10 h-10 rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-cyan-500/20 hover:border-cyan-500/50 flex items-center justify-center transition-all duration-300 group-hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/30">
                  <FiArrowUp size={18} className="group-hover:animate-bounce" />
                </div>
              </button>
            </div>
          </div>
        </div>
      </footer>

    </>
  );
}
