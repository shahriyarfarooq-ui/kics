import { useEffect, useRef } from 'react';

/**
 * Wraps children and applies a scroll-reveal CSS class when the element enters the viewport.
 * @param {string} animation - 'reveal' | 'reveal-left' | 'reveal-right' | 'reveal-scale'
 * @param {number} delay - CSS transition delay in ms
 */
export default function AnimateOnScroll({ children, animation = 'reveal', delay = 0, className = '' }) {
  const ref = useRef(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setTimeout(() => el.classList.add('visible'), delay);
          observer.unobserve(el);
        }
      },
      { threshold: 0.1 }
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, [delay]);

  return (
    <div ref={ref} className={`${animation} ${className}`}>
      {children}
    </div>
  );
}
