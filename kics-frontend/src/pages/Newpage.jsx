import React from 'react';
import PageHero from '../components/PageHero';
import SEO from '../components/SEO';

export default function NewPage() {
  return (
    <>
      <SEO title="New Page" description="Description of new page" />
      <PageHero title="New Page" subtitle="Subtitle here" />
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6">
          <h2 className="text-3xl font-bold text-center mb-8">Content Here</h2>
          <p className="text-center text-gray-600">Your page content...</p>
        </div>
      </section>
    </>
  );
}