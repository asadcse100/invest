<?php

namespace App\Models;

use App\Enums\PageStatus;
use App\Filters\Filterable;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Filterable;

    protected $fillable = [
        'name',
        'slug',
        'menu_name',
        'menu_link',
        'title',
        'subtitle',
        'seo',
        'content',
        'lang',
        'status',
        'public',
        'params',
        'trash',
        'pid'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'seo' => 'array',
        'params' => 'array',
    ];

    public function translatedPage()
    {
        return $this->hasMany(Page::class, 'pid')->where('pid', '<>', 0);
    }

    public function mainPage()
    {
        return $this->hasOne(Page::class, 'id', 'pid');
    }

    public function scopeActive($query)
    {
        return $query->where('status', PageStatus::ACTIVE);
    }

    public function scopeMain($query)
    {
        return $query->where('pid', '=', 0);
    }

    public function getAccessAttribute()
    {
        if ($this->pid > 0) {
            return ($this->mainPage->public == 1) ? 'public' : 'login';
        }
        return ($this->public == 1) ? 'public' : 'login';
    }

    public function getLinkAttribute()
    {
        if (isset($this->pid) && $this->pid > 0) {
            return (!empty($this->mainPage->menu_link)) ? $this->mainPage->menu_link : route('show.page', $this->mainPage->slug);    
        }
        return (!empty($this->menu_link)) ? $this->menu_link : route('show.page', $this->slug);
    }

    public function getTextAttribute()
    {
        return (!empty($this->menu_name)) ? $this->menu_name : $this->name;
    }
}
