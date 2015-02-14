<?php
namespace TypiCMS\Modules\Menus\Http\Controllers;

use TypiCMS\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Menus\Repositories\MenuInterface as Repository;

class ApiController extends BaseApiController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
