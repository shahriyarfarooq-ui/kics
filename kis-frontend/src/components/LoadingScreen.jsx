import { useState, useEffect } from 'react';
import { getImageLoadingProps } from '../utils/image';
import kicsLogo from '../assets/images/logos/kics-logo.png';

export default function LoadingScreen() {
  const [isLoading, setIsLoading] = useState(true);
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    // Simulate loading progress
    const interval = setInterval(() => {
      setProgress(prev => {
        if (prev >= 100) {
          clearInterval(interval);
          setTimeout(() => setIsLoading(false), 300);
          return 100;
        }
        return prev + Math.random() * 15;
      });
    }, 150);

    return () => clearInterval(interval);
  }, []);

  if (!isLoading) return null;

  return (
    <div className="fixed inset-0 z-[100] bg-primary-900 flex items-center justify-center">
      {/* Background pattern */}
      <div className="absolute inset-0 bg-dot-pattern opacity-20" />

      {/* Animated circles */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl animate-pulse-glow" />
        <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl animate-float" />
      </div>

      {/* Content */}
      <div className="relative text-center px-6">
        {/* Logo */}
        <div className="mb-8 animate-scaleIn">
          <div className="w-24 h-24 mx-auto rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center mb-4 animate-pulse-glow">
            <img
              src={kicsLogo}
              alt="KICS"
              {...getImageLoadingProps({ eager: true, sizes: '80px' })}
              className="w-20 h-20 object-contain"
              onError={(e) => {
                e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2M4OTcyYSIvPjx0ZXh0IHg9IjUwIiB5PSI1NSIgZm9udC1zaXplPSIzMiIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtZmFtaWx5PSJBcmlhbCI+SzwvdGV4dD48L3N2Zz4=';
              }}
            />
          </div>
          <h2 className="text-3xl font-heading font-bold text-white mb-2 animate-slideInUp">
            KICS
          </h2>
          <p className="text-white/60 text-sm animate-slideInUp" style={{ animationDelay: '0.1s' }}>
            Al-Khwarizmi Institute of Computer Science
          </p>
        </div>

        {/* Progress bar */}
        <div className="w-72 mx-auto animate-slideInUp" style={{ animationDelay: '0.2s' }}>
          <div className="h-1 bg-white/10 rounded-full overflow-hidden mb-2">
            <div
              className="h-full bg-gradient-to-r from-cyan-500 via-cyan-400 to-blue-500 transition-all duration-300 ease-out"
              style={{ width: `${Math.min(progress, 100)}%` }}
            />
          </div>
          <p className="text-white/40 text-xs">
            Loading... {Math.round(Math.min(progress, 100))}%
          </p>
        </div>

        {/* Loading dots */}
        <div className="flex items-center justify-center gap-1.5 mt-6">
          {[0, 1, 2].map((i) => (
            <div
              key={i}
              className="w-2 h-2 rounded-full bg-cyan-400 animate-bounce-gentle"
              style={{ animationDelay: `${i * 0.15}s` }}
            />
          ))}
        </div>
      </div>
    </div>
  );
}
