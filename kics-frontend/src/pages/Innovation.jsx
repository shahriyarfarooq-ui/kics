import React from 'react';
import PageHero from '../components/PageHero';
import SEO from '../components/SEO';

export default function Innovation() {
  return (
    <>
      <SEO title="Innovation" description="Innovation at KICS UET Lahore" />
      <PageHero title="Innovation" subtitle="Driving innovation through technology" />
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <h2 className="text-3xl font-bold text-center mb-8">Innovation Center</h2>
          <p className="text-center text-gray-600">Content coming soon...</p>
        </div>
      </section>
    </>
  );
}