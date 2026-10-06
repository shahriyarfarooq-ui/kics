import { useState } from 'react';
import PageHero from '../components/PageHero';
import AnimateOnScroll from '../components/AnimateOnScroll';
import SEO from '../components/SEO';
import { contactInfo } from '../data/siteData';
import { contactService } from '../services';
import { FiPhone, FiMail, FiMapPin, FiSend, FiPrinter } from 'react-icons/fi';
import { FaFacebookF, FaTwitter, FaLinkedinIn, FaInstagram, FaYoutube } from 'react-icons/fa';

export default function Contact() {
  const [form, setForm] = useState({ name: '', email: '', phone: '', subject: '', message: '' });
  const [sent, setSent] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (event) => {
    event.preventDefault();
    setSubmitting(true);
    setError('');
    setSent(false);

    try {
      await contactService.send(form);
      setSent(true);
      setForm({ name: '', email: '', phone: '', subject: '', message: '' });
      setTimeout(() => setSent(false), 5000);
    } catch (requestError) {
      const validationErrors = requestError?.data?.errors;
      const firstValidationError = validationErrors
        ? Object.values(validationErrors).flat().find(Boolean)
        : '';

      setError(firstValidationError || requestError?.message || 'Unable to send your message right now. Please try again.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div>
      <SEO
        title="Contact Us"
        description="Contact KICS UET Lahore for research collaboration, technology services, professional training, or general inquiries. Phone, email and address."
        breadcrumbs={[{ label: 'Contact', url: '/contact' }]}
      />
      <PageHero
        title="Contact Us"
        subtitle="Reach out to our team for research collaboration, services, training, or general inquiries."
        breadcrumbs={[{ label: 'Contact' }]}
      />

      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <div className="grid lg:grid-cols-5 gap-10">
            <AnimateOnScroll animation="reveal-left" className="lg:col-span-2">
              <div className="bg-[#0B1833] rounded-2xl p-7 h-full bg-dot-pattern">
                <span className="text-primary-300 font-semibold uppercase tracking-[0.2em] text-xs mb-3 block">Get in Touch</span>
                <h2 className="text-2xl font-heading font-bold mb-5 text-primary-50">Contact Information</h2>
                <div className="space-y-5 mb-8">
                  <a href={`tel:${contactInfo.phone}`} className="flex items-start gap-4 group">
                    <div className="w-10 h-10 rounded-xl bg-primary-700 group-hover:bg-primary-500 flex items-center justify-center flex-shrink-0 transition-colors text-primary-100">
                      <FiPhone size={16} />
                    </div>
                    <div>
                      <p className="text-primary-300 text-xs mb-0.5">Phone</p>
                      <p className="text-primary-50 text-sm font-medium">{contactInfo.phone}</p>
                    </div>
                  </a>
                  <a href={`mailto:${contactInfo.email}`} className="flex items-start gap-4 group">
                    <div className="w-10 h-10 rounded-xl bg-primary-700 group-hover:bg-primary-500 flex items-center justify-center flex-shrink-0 transition-colors text-primary-100">
                      <FiMail size={16} />
                    </div>
                    <div>
                      <p className="text-primary-300 text-xs mb-0.5">Email</p>
                      <p className="text-primary-50 text-sm font-medium">{contactInfo.email}</p>
                    </div>
                  </a>
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 rounded-xl bg-primary-700 flex items-center justify-center flex-shrink-0 text-primary-100">
                      <FiPrinter size={16} />
                    </div>
                    <div>
                      <p className="text-primary-300 text-xs mb-0.5">Fax</p>
                      <p className="text-primary-50 text-sm font-medium">{contactInfo.fax}</p>
                    </div>
                  </div>
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 rounded-xl bg-primary-700 flex items-center justify-center flex-shrink-0 text-primary-100">
                      <FiMapPin size={16} />
                    </div>
                    <div>
                      <p className="text-primary-300 text-xs mb-0.5">Address</p>
                      <p className="text-primary-50 text-sm leading-relaxed">{contactInfo.address}</p>
                    </div>
                  </div>
                </div>

                <div className="border-t border-primary-700 pt-6 mb-6">
                  <p className="text-primary-300 text-xs mb-3 uppercase tracking-wider">Department Contacts</p>
                  <div className="space-y-3">
                    {contactInfo.contacts.map((contact) => (
                      <div key={contact.name} className="bg-primary-800 rounded-lg p-3">
                        <p className="text-primary-400 text-xs font-semibold">{contact.role}</p>
                        <p className="text-primary-50 text-sm font-medium">{contact.name}</p>
                        <p className="text-primary-200 text-xs">{contact.title}</p>
                        <p className="text-primary-200 text-xs mt-0.5 flex items-center gap-1">
                          <FiPhone size={10} /> {contact.phone}{contact.cell ? ` | ${contact.cell}` : ''}
                        </p>
                      </div>
                    ))}
                  </div>
                </div>

                <div className="flex gap-3">
                  {[
                    { Icon: FaFacebookF, href: contactInfo.socials.facebook, color: 'hover:bg-blue-600' },
                    { Icon: FaTwitter, href: contactInfo.socials.twitter, color: 'hover:bg-blue-600' },
                    { Icon: FaLinkedinIn, href: contactInfo.socials.linkedin, color: 'hover:bg-blue-600' },
                    { Icon: FaInstagram, href: contactInfo.socials.instagram, color: 'hover:bg-blue-600' },
                    { Icon: FaYoutube, href: contactInfo.socials.youtube, color: 'hover:bg-blue-600' },
                  ].map(({ Icon, href, color }, index) => (
                    <a
                      key={index}
                      href={href}
                      target="_blank"
                      rel="noreferrer"
                      className={`w-9 h-9 rounded-full bg-primary-700 ${color} text-primary-100 flex items-center justify-center transition-all duration-200`}
                    >
                      <Icon size={14} />
                    </a>
                  ))}
                </div>
              </div>
            </AnimateOnScroll>

            <AnimateOnScroll animation="reveal-right" className="lg:col-span-3">
              <span className="eyebrow">Send a Message</span>
              <h2 className="section-title mb-6">We'd Love to Hear From You</h2>

              {sent && (
                <div className="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                  Thank you! Your message has been sent. We'll get back to you soon.
                </div>
              )}

              {error && (
                <div className="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm">
                  {error}
                </div>
              )}

              <form onSubmit={handleSubmit} className="space-y-5">
                <div className="grid sm:grid-cols-2 gap-5">
                  {[
                    { id: 'name', label: 'Full Name', type: 'text', placeholder: 'Your full name' },
                    { id: 'email', label: 'Email Address', type: 'email', placeholder: 'your@email.com' },
                  ].map((field) => (
                    <div key={field.id}>
                      <label htmlFor={field.id} className="block text-sm font-semibold text-slate-700 mb-1.5">{field.label}</label>
                      <input
                        id={field.id}
                        type={field.type}
                        placeholder={field.placeholder}
                        required
                        value={form[field.id]}
                        onChange={(event) => setForm({ ...form, [field.id]: event.target.value })}
                        className="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 transition-colors"
                      />
                    </div>
                  ))}
                </div>
                <div>
                  <label htmlFor="phone" className="block text-sm font-semibold text-slate-700 mb-1.5">Phone Number</label>
                  <input
                    id="phone"
                    type="tel"
                    placeholder="+92 42 99029450"
                    value={form.phone}
                    onChange={(event) => setForm({ ...form, phone: event.target.value })}
                    className="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 transition-colors"
                  />
                </div>
                <div>
                  <label htmlFor="subject" className="block text-sm font-semibold text-slate-700 mb-1.5">Subject</label>
                  <input
                    id="subject"
                    type="text"
                    placeholder="How can we help?"
                    required
                    value={form.subject}
                    onChange={(event) => setForm({ ...form, subject: event.target.value })}
                    className="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 transition-colors"
                  />
                </div>
                <div>
                  <label htmlFor="message" className="block text-sm font-semibold text-slate-700 mb-1.5">Message</label>
                  <textarea
                    id="message"
                    rows={5}
                    placeholder="Tell us about your inquiry..."
                    required
                    value={form.message}
                    onChange={(event) => setForm({ ...form, message: event.target.value })}
                    className="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 transition-colors resize-none"
                  />
                </div>
                <button
                  type="submit"
                  disabled={submitting}
                  className="btn-primary w-full sm:w-auto justify-center disabled:opacity-70 disabled:cursor-not-allowed"
                >
                  <FiSend size={15} /> {submitting ? 'Sending...' : 'Send Message'}
                </button>
              </form>
            </AnimateOnScroll>
          </div>
        </div>
      </section>

      <section className="bg-slate-100">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 py-3">
          <AnimateOnScroll>
            <div className="rounded-2xl overflow-hidden shadow-card">
              <iframe
                title="KICS Location"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3400.7!2d74.3080!3d31.4804!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391904c526d4f5a5%3A0xef3b22b4e2f7cc13!2sUniversity%20of%20Engineering%20and%20Technology%2C%20Lahore!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
                width="100%"
                height="300"
                style={{ border: 0 }}
                allowFullScreen
                loading="lazy"
              />
            </div>
          </AnimateOnScroll>
        </div>
      </section>
    </div>
  );
}
