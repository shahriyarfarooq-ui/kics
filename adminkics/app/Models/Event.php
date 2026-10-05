<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'registration_deadline',
        'location',
        'address',
        'event_type',
        'event_status',
        'featured_image',
        'gallery_images',
        'is_featured',
        'is_visible',
        'registration_link',
        'speakers',
        'organizer',
        'contact_email',
        'contact_phone',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'gallery_images' => 'array',
        'speakers' => 'array',
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())
            ->where('event_status', '!=', 'cancelled');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return [
            'upcoming' => 'blue',
            'ongoing' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
        ][$this->event_status] ?? 'gray';
    }

    public function getStatusLabelAttribute()
    {
        return [
            'upcoming' => 'Upcoming',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ][$this->event_status] ?? 'Unknown';
    }

    public function getTypeLabelAttribute()
    {
        return [
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'summit' => 'Summit',
            'training' => 'Training',
            'social' => 'Social',
            'other' => 'Other',
        ][$this->event_type] ?? 'Other';
    }

    public function getTypeIconAttribute()
    {
        return [
            'conference' => '📚',
            'workshop' => '🔧',
            'seminar' => '🎤',
            'summit' => '🏔️',
            'training' => '💡',
            'social' => '🎉',
            'other' => '📌',
        ][$this->event_type] ?? '📌';
    }
}