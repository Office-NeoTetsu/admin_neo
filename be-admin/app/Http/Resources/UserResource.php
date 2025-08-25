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
    public function __construct($status = true, $message = '', $resource)
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
            'success' => $this->status,
            'message' => $this->message,
            'data' => [
                'id'         => $this->id,
                'name'       => $this->name,
                'email'      => $this->email,
                'created_at' => $this->created_at,
            ],
        ];
    }
}
