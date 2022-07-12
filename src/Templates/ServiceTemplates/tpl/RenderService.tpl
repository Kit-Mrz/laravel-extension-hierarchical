<?php

namespace App\Services\{{NAMESPACE_PATH}}\{{RNT}};

use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY_NAME}};
use App\Repositories\{{REPOSITORY_NAME}}\{{REPOSITORY}};
use App\Supports\Cores\ServiceRenderContract;

class {{RNT}}RenderService implements ServiceRenderContract
{
    /**
     * @desc
     * @param {{REPOSITORY_NAME}} $o
     * @return array
     */
    public function handle(object $o) : array
    {
        $row = $o->toArray();
        //
        $item = {{REPOSITORY}}::handleOutput($row);

        return $item;
    }

}
