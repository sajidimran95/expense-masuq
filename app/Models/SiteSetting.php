<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Throwable;

#[Fillable([
    'company_name',
    'logo',
    'favicon',
    'meta_title',
    'meta_description',
])]
class SiteSetting extends Model
{
    public static function current(): self
    {
        $defaults = [
            'company_name' => 'Expense Management',
            'logo' => 'images/expense-logo.svg',
            'favicon' => 'images/expense-logo.svg',
            'meta_title' => 'Expense Management',
            'meta_description' => 'দৈনিক ও মাসিক খরচ, রিপোর্ট এবং অনুমোদন সহজে ম্যানেজ করার সফটওয়্যার।',
        ];

        try {
            return static::query()->firstOrCreate([], $defaults);
        } catch (Throwable) {
            return new static($defaults);
        }
    }

    public function logoUrl(): string
    {
        return asset($this->logo ?: 'images/expense-logo.svg');
    }

    public function faviconUrl(): string
    {
        return asset($this->favicon ?: $this->logo ?: 'images/expense-logo.svg');
    }

    public function title(): string
    {
        return $this->meta_title ?: $this->company_name;
    }

    public function description(): string
    {
        return $this->meta_description ?: 'দৈনিক ও মাসিক খরচ, রিপোর্ট এবং অনুমোদন সহজে ম্যানেজ করার সফটওয়্যার।';
    }
}
