import { useState, useEffect, useRef } from 'react';

export default function useCounter(target, duration = 2000, startOnVisible = true) {
  const [count, setCount] = useState(0);
  const ref = useRef(null);
  const started = useRef(false);
  const rafId = useRef(null); // FIX: Track animation frame ID

  useEffect(() => {
    const el = ref.current;
    if (!el || !startOnVisible) return;

    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting && !started.current) {
        started.current = true;
        const start = performance.now();

        const step = (now) => {
          const progress = Math.min((now - start) / duration, 1);
          const eased = 1 - Math.pow(1 - progress, 3);
          setCount(Math.floor(eased * target));

          if (progress < 1) {
            rafId.current = requestAnimationFrame(step); // FIX: Save ID
          }
        };

        rafId.current = requestAnimationFrame(step);
        observer.unobserve(el);
      }
    }, { threshold: 0.3 });

    observer.observe(el);

    return () => {
      observer.disconnect();
      // FIX: Critical - cancel animation frame on unmount
      if (rafId.current) {
        cancelAnimationFrame(rafId.current);
      }
    };
  }, [target, duration, startOnVisible]);

  return { count, ref };
}
