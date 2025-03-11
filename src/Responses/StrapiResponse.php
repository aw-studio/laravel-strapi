<?php

namespace AwStudio\LaravelStrapi\Responses;

use Illuminate\Support\Collection;

class StrapiResponse
{
    public Collection|array $data; // Can be a Collection (CollectionType) or an array (SingleType)

    public array $meta;

    public function __construct(array $response)
    {
        // If 'data' is an associative array, it's a SingleType
        if (isset($response['data']) && is_array($response['data']) && ! array_is_list($response['data'])) {
            $this->data = $response['data']; // Store as an array (not a Collection)
        } else {
            $this->data = collect($response['data'] ?? []); // Store as Collection (CollectionType)
        }

        $this->meta = $response['meta'] ?? [];
    }

    /**
     * Get the first item (for CollectionTypes).
     */
    public function first(): ?array
    {
        return is_array($this->data) ? $this->data : $this->data->first();
    }

    /**
     * Convert the response to an array.
     */
    public function toArray(): array
    {
        return [
            'data' => is_array($this->data) ? $this->data : $this->data->toArray(),
            'meta' => $this->meta,
        ];
    }
}
