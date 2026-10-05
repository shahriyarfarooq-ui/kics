<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'registration_deadline' => 'nullable|date|before:start_date',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'event_type' => 'required|in:conference,workshop,seminar,summit,training,social,other',
            'event_status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'registration_link' => 'nullable|url',
            'speakers' => 'nullable|array',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ];
    }
}