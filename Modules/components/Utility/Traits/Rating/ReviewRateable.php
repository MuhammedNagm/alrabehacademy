<?php

namespace Modules\Components\Utility\Traits\Rating;

use Modules\Components\Utility\Models\Rating\Rating;
use Illuminate\Database\Eloquent\Model;

trait ReviewRateable
{
    public static function bootReviewRateable()
    {
        static::deleted(function (Model $deletedModel) {
            if (schemaHasTable('utility_ratings')) {
                $deletedModel->ratings()->delete();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'reviewrateable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function author()
    {
        return $this->morphTo('author');
    }

    /**
     * @param null $round
     * @return \Illuminate\Support\Collection
     */
    public function averageRating($round = null)
    {
        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(rating), ' . $round . ') as averageReviewRateable')
                ->pluck('averageReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(rating) as averageReviewRateable')
            ->pluck('averageReviewRateable');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function countRating()
    {
        return $this->ratings()
            ->selectRaw('count(rating) as countReviewRateable')
            ->pluck('countReviewRateable');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function sumRating()
    {
        return $this->ratings()
            ->selectRaw('SUM(rating) as sumReviewRateable')
            ->pluck('sumReviewRateable');
    }

    /**
     * @param int $max
     * @return float|int
     */
    public function ratingPercent($max = 5)
    {
        $ratings = $this->ratings();
        $quantity = $ratings->count();
        $total = $ratings->selectRaw('SUM(rating) as total')->pluck('total');
        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }
}
