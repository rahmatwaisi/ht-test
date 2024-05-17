<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomLengthAwarePagination extends JsonResource
{
    /**
     * @var string a name for items in the paginator, default is "items"
     */
    private string $items_name;

    /**
     * @var string|null a name of resource class for given items
     */
    private ?string $resource_class;

    /**
     * @var array|null some extra meta data
     */
    private ?array $meta;

    public function __construct($resource, string $items_name = 'result', string $resource_class = null)
    {
        $this->items_name = $items_name;
        $this->resource_class = $resource_class;
        $this->meta = null;
        parent::__construct($resource);
    }

    /**
     * Creates an instance of json resource with default items name
     * However, you can use the constructor to create with custom names
     * @param $resource
     * @param string|null $resource_class
     * @return static
     */
    public static function build($resource, string $resource_class = null): self
    {
        return new self($resource, 'result', $resource_class);
    }

    /**
     * Sets the metadata of the resource
     * @param array $data
     * @return $this
     */
    public function withMetaData(array $data): self
    {
        $this->meta = $data;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        if (!empty($this->resource_class)) {
            $resource_class = $this->resource_class;
            if (class_exists($resource_class))
                /** @var JsonResource $resource_class */
                $items = $resource_class::collection($this->resource->items());
            else
                $items = $this->resource->items();
        } else {
            $items = $this->resource->items();
        }

        return [
            "$this->items_name" => $items,
            "meta" => $this->meta,
            "current_page" => $this->resource->currentPage(),
            "last_page" => $this->resource->lastPage(),
            "per_page" => $this->resource->perPage(),
            "total" => $this->resource->total(),
            "next_page_url" => $this->resource->nextPageUrl(),
            "prev_page_url" => $this->resource->previousPageUrl(),
        ];
    }
}
