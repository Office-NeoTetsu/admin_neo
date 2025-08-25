<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $status;
    public $message;

    /**
     * Konstruktor dengan status, message, dan resource
     */
    public function __construct($resource, $status = true, $message = '')
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Transformasi output ke array JSON API
     */
    public function toArray(Request $request): array
    {
        return [
            'succes' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
