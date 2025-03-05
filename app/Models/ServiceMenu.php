<?php

namespace App\Models;

use App\Enums\PageCategoryEnum;
use Illuminate\Database\Eloquent\Model;

class ServiceMenu extends Model
{
    protected $fillable = [
        'category',
        'title',
        'description',
        'color',
        'image',
        'parent_id',
        'path',
        'param',
        'domain_id',
    ];

    protected $casts = [
        'category' => PageCategoryEnum::class,
    ];

    public static function create(array $attributes = [])
    {
        if (empty($attributes['parent_id'])) {
            $attributes['parent_id'] = null;
        }

        return static::query()->create($attributes);
    }

    // Define the relationship with the Domain model
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function parent()
    {
        return $this->belongsTo(ServiceMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ServiceMenu::class, 'parent_id');
    }

    // Accessor for the combined URL
    public function getUrlAttribute()
    {
        if ($this->domain_id == 1) {
            return $this->param ? $this->path.'.'.$this->param : $this->path;
        }

        $domain = $this->domain ? $this->domain->domain : '';
        $param = $this->param ? '?'.$this->param : '';

        return $domain.'/'.$this->path.$param;
    }

    // Mutator to set the domain, path, and param fields from a full URL
    public function setUrlAttribute($value)
    {
        $parsedUrl = parse_url($value);
        $domain = Domain::firstOrCreate(['domain' => $parsedUrl['scheme'].'://'.$parsedUrl['host']]);
        $this->attributes['domain_id'] = $domain->id;
        $this->attributes['path'] = ltrim($parsedUrl['path'], '/');
        $this->attributes['param'] = isset($parsedUrl['query']) ? $parsedUrl['query'] : null;
    }
}
