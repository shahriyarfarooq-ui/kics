<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class People extends Model
{
    use HasFactory;

    protected $table = 'people';
    protected $primaryKey = 'people_id';
    public $timestamps = false;

    protected $fillable = [
        'url_name', 'user_id', 'designation_id', 'group_id', 'post_id',
        'title', 'fname', 'lname', 'url', 'email', 'off_no', 'ext', 'cell_no',
        'fax_no', 'image_name', 'seqno', 'biography', 'research_interest',
        'about_me', 'education', 'achievements', 'certifications', 'publications',
        'work_experience', 'projects', 'profile_photo_path', 'linkedin_url',
        'github_url', 'website_url', 'profile_visible', 'profile_edit_locked',
        'profile_updated_at', 'status', 'is_core_team'
    ];

    // Add casts for boolean fields
    protected $casts = [
        'is_core_team' => 'boolean',
        'profile_visible' => 'boolean',
        'profile_edit_locked' => 'boolean',
    ];

    // Relationships
//    ================= Relationships ================= */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'group_id');
    }

    public function post()
    {
        return $this->belongsTo(KicPost::class, 'post_id', 'post_id');
    }

    /**
     * Many-to-many relationship with labs (groups)
     * Through the staff_lab pivot table
     */
    public function labs()
    {
        return $this->belongsToMany(
            Group::class,           // Related model
            'staff_lab',           // Pivot table name
            'staff_id',            // Foreign key on pivot table (references people)
            'group_id',            // Related key on pivot table (references groups)
            'people_id',           // Local key on people table
            'group_id'             // Related key on groups table
        )->withPivot('role');      // Include pivot columns
    }

    /**
     * Boot method for auto-generating URL name
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($person) {
            // Auto-generate URL name if empty or name changed
            if (empty($person->url_name) || 
                $person->isDirty(['title', 'fname', 'lname'])) {
                
                // Create base slug from name parts
                $nameParts = array_filter([
                    $person->title,
                    $person->fname,
                    $person->lname
                ]);
                
                if (!empty($nameParts)) {
                    $baseSlug = Str::slug(trim(implode(' ', $nameParts)), '-');
                    
                    // Ensure uniqueness
                    $originalSlug = $baseSlug;
                    $counter = 1;
                    
                    // Check if slug already exists (excluding current record)
                    while (static::where('url_name', $baseSlug)
                           ->where('people_id', '!=', $person->people_id)
                           ->exists()) {
                        $baseSlug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                    
                    $person->url_name = $baseSlug;
                }
            }
        });
    }

    /**
     * Accessor for full name
     */
    public function getFullNameAttribute()
    {
        return trim(implode(' ', array_filter([
            $this->title,
            $this->fname,
            $this->lname
        ])));
    }

    /**
     * Scope for active people
     */
    public function scopeActive($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope for core team members
     */
    public function scopeCoreTeam($query)
    {
        return $query->where('is_core_team', true);
    }

    /**
     * Scope for ordering by sequence number
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('seqno')->orderBy('lname');
    }
}
