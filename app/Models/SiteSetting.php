<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Throwable;

#[Fillable([
    'company_name',
    'logo',
    'favicon',
    'front_background',
    'meta_title',
    'meta_description',
    'front_badge_text',
    'front_title',
    'front_description',
])]
class SiteSetting extends Model
{
    public static function current(): self
    {
        $defaults = [
            'company_name' => 'Expense Management',
            'logo' => 'images/expense-logo.svg',
            'favicon' => 'images/expense-logo.svg',
            'front_background' => null,
            'meta_title' => 'Expense Management',
            'meta_description' => 'দৈনিক ও মাসিক খরচ, রিপোর্ট এবং অনুমোদন সহজে ম্যানেজ করার সফটওয়্যার।',
            'front_badge_text' => 'দৈনিক ও মাসিক খরচ হিসাব সফটওয়্যার',
            'front_title' => 'প্রতিদিনের খরচ ও মাসিক বাজেট সহজে হিসাব করুন।',
            'front_description' => 'দৈনিক খরচ, মাসিক বাজেট, রসিদ, অনুমোদন এবং ক্যাটাগরি রিপোর্ট এক জায়গা থেকে সুন্দরভাবে ম্যানেজ করুন।',
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

    public function frontBackgroundUrl(): string
    {
        return $this->front_background
            ? asset($this->front_background)
            : 'https://images.unsplash.com/photo-1554224154-22dec7ec8818?auto=format&fit=crop&w=1920&q=85';
    }

    public function frontBadgeText(): string
    {
        return $this->front_badge_text ?: 'দৈনিক ও মাসিক খরচ হিসাব সফটওয়্যার';
    }

    public function frontTitle(): string
    {
        return $this->front_title ?: 'প্রতিদিনের খরচ ও মাসিক বাজেট সহজে হিসাব করুন।';
    }

    public function frontDescription(): string
    {
        return $this->front_description ?: 'দৈনিক খরচ, মাসিক বাজেট, রসিদ, অনুমোদন এবং ক্যাটাগরি রিপোর্ট এক জায়গা থেকে সুন্দরভাবে ম্যানেজ করুন।';
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
