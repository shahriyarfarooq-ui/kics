import { useState, useEffect } from "react";
import { FiX, FiExternalLink } from "react-icons/fi";
import { announcementPopupService } from '../services/announcementPopupService';

const AnnouncementPopup = () => {
  const [popup, setPopup] = useState(null);
  const [isVisible, setIsVisible] = useState(false);
  const [isClosing, setIsClosing] = useState(false);

  useEffect(() => {
    let active = true;
    announcementPopupService.getActive()
      .then((data) => {
        if (!active || !data?.is_active || !data?.image_url) return;
        setPopup(data);
        setIsVisible(true);
      })
      .catch((error) => console.error('Failed to load homepage popup:', error));

    return () => { active = false; };
  }, []);

  // Auto-close after 10 seconds
  useEffect(() => {
    if (isVisible) {
      const timer = setTimeout(() => {
        handleClose();
      }, 10000);
      return () => clearTimeout(timer);
    }
  }, [isVisible]);

  const handleClose = () => {
    setIsClosing(true);
    setTimeout(() => {
      setIsVisible(false);
      setIsClosing(false);
    }, 400);
  };

  if (!isVisible) return null;

  if (!popup) return null;

  const externalLink = /^https?:\/\//i.test(popup.link_url || '');
  const PopupLink = popup.link_url ? 'a' : 'div';
  const popupLinkProps = popup.link_url
    ? { href: popup.link_url, ...(externalLink ? { target: '_blank', rel: 'noopener noreferrer' } : {}) }
    : {};

  return (
    <>
      {/* Backdrop overlay */}
      <div
        className={`fixed inset-0 z-[100] bg-black/70 backdrop-blur-sm transition-opacity duration-400 ${
          isClosing ? "opacity-0" : "opacity-100"
        }`}
        onClick={handleClose}
      />

      {/* Popup Container */}
      <div
        className={`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[101] w-[92%] max-w-5xl max-h-[90vh] transition-all duration-400 ${
          isClosing ? "opacity-0 scale-95" : "opacity-100 scale-100"
        }`}
      >
        <div className="bg-transparent rounded-2xl overflow-hidden relative">
          
          {/* Close Button */}
          <button
            onClick={handleClose}
            className="absolute top-3 right-3 z-20 w-9 h-9 rounded-full bg-black/50 hover:bg-black/70 backdrop-blur-sm flex items-center justify-center text-white hover:text-white transition-all duration-300 hover:scale-110"
            aria-label="Close announcement"
          >
            <FiX size={18} />
          </button>

          {/* Clickable Image - Full width, responsive */}
          <PopupLink {...popupLinkProps} className="block w-full">
            <div className="relative w-full bg-transparent flex justify-center">
              <img
                src={popup.image_url}
                alt={popup.title || 'KICS Announcement'}
                className="block w-auto h-auto max-w-full max-h-[90vh] object-contain"
              />
              
              {/* Subtle gradient overlay at bottom */}
              <div className="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-black/40 via-black/10 to-transparent pointer-events-none" />
              
              {/* Badge - Top Left */}
              <div className="absolute top-4 left-4 bg-amber-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg pointer-events-none">
                {popup.title || 'Announcement'}
              </div>

              {/* Click indicator - Bottom Right */}
              <div className="absolute bottom-4 right-4 bg-black/40 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full flex items-center gap-1.5 pointer-events-none">
                {popup.link_url && <><FiExternalLink size={12} /> Click to read more</>}
              </div>
            </div>
          </PopupLink>

          {/* Auto-close indicator - Bottom */}
          <div className="absolute bottom-0 left-0 right-0 px-4 py-2 bg-gradient-to-t from-black/60 to-transparent pointer-events-none">
            <div className="flex items-center gap-3 max-w-xs mx-auto">
              <div className="flex-1 h-1 bg-white/30 rounded-full overflow-hidden">
                <div
                  className="h-full bg-gradient-to-r from-amber-400 to-blue-400 rounded-full transition-all duration-[10000ms] ease-linear"
                  style={{ width: isClosing ? "0%" : "100%" }}
                />
              </div>
              <span className="text-[10px] text-white/70 whitespace-nowrap">
                Auto-closes in 10s
              </span>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AnnouncementPopup;
