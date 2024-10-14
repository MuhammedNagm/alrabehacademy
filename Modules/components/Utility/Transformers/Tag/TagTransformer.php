<?php

namespace Modules\Components\Utility\Transformers\Tag;

use Modules\Foundation\Transformers\BaseTransformer;
use Modules\Components\Utility\Models\Tag\Tag;

class TagTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('utility.models.tag.resource_url');

        parent::__construct();
    }

    /**
     * @param Tag $tag
     * @return array
     * @throws \Throwable
     */
    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'name' => str_limit($tag->name, 50),
            'slug' => $tag->slug,
            'status' => formatStatusAsLabels($tag->status),
            'module' => $tag->module ?? '-',
            'created_at' => format_date($tag->created_at),
            'updated_at' => format_date($tag->updated_at),
            'action' => $this->actions($tag)
        ];
    }
}
