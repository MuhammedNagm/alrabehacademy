<?php

namespace Modules\Components\Utility\Classes\Rating;


use Illuminate\Database\Eloquent\Model;
use Modules\Components\Utility\Models\Rating\Rating as RatingModel;

class RatingManager
{

    protected $instance, $author;

    /**
     * RatingManager constructor.
     * @param $instance
     * @param $author
     */
    public function __construct($instance, $author)
    {
        $this->instance = $instance;
        $this->author = $author;
    }

    /**
     * @param $data
     * @return RatingModel|Model
     */
    public function createRating($data)
    {
        $data = array_merge([
            'reviewrateable_id' => $this->instance->id,
            'reviewrateable_type' => get_class($this->instance),
            'author_id' => $this->author->id,
            'author_type' => get_class($this->author),
        ], $data);

        return RatingModel::create($data);
    }

    /**
     * @param RatingModel $rating
     * @param $data
     * @return bool
     */
    public function updateRating(RatingModel $rating, $data)
    {
        return $rating->update($data);
    }

    public function handleModelRating($data)
    {
        $rating = $this->instance->ratings()->where([
            'author_id' => $this->author->id,
            'author_type' => get_class($this->author)
        ])->first();

        if ($rating) {
            $this->updateRating($rating, $data);
        } else {
            $this->createRating($data);
        }
    }

    /**
     * @param RatingModel $rating
     * @return bool|null
     * @throws \Exception
     */
    public function deleteRating(RatingModel $rating)
    {
        return $rating->delete();
    }

}
