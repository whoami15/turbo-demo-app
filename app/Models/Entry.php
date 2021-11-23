<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Entry extends Model
{
    use HasFactory;

    public static $entryableShouldAutoCreate = true;

    public static function withoutEntryableAutoCreation(callable $scope)
    {
        try {
            static::$entryableShouldAutoCreate = false;

            return $scope();
        } finally {
            static::$entryableShouldAutoCreate = true;
        }
    }

    public function setEntryableAttribute($model)
    {
        $this->entryable()->associate($model);
    }

    public function entryable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'parent_entry_id');
    }

    public function entryableResourceName()
    {
        return (string) Str::of($this->entryable->entryableResource())
            ->snake()
            ->explode('_')
            ->map(fn ($word) => ucfirst($word))
            ->join(' ');
    }

    public function entryableIndexRoute()
    {
        return $this->entryable->entryableIndexRoute();
    }

    public function entryableShowRoute()
    {
        return $this->entryable->entryableShowRoute();
    }

    public function getTitleAttribute()
    {
        return $this->entryable->entryableTitle();
    }

    public function entryableHasComments()
    {
        return $this->entryable->canHaveComments();
    }

    public function entryableTeam()
    {
        return $this->entryable->entryableTeam();
    }

    public function belongsToTeam(User $user)
    {
        return $user->belongsToTeam($this->entryableTeam());
    }
}
