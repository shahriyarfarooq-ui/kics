import React from 'react';
import PageHero from '../components/PageHero';
import SEO from '../components/SEO';

export default function Research() {
  return (
    <>
      <SEO title="Research" description="Research at KICS UET Lahore" />
      <PageHero title="Research" subtitle="Innovative research projects at KICS" />
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <h2 className="text-3xl font-bold text-center mb-8">Research Areas</h2>
          <p className="text-center text-gray-600">Content coming soon...</p>
        </div>
      </section>
    </>
  );
}