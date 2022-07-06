<?php

namespace App\Repositories\{{RNT}};

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class {{RNT}}Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var {{RNT}} 模型
     */
    public ${{RNT}};

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct({{RNT}} ${{RNT}})
    {
        $this->${{RNT}} = ${{RNT}};
    }
}
