<?php declare(strict_types=1);

use App\Controllers\GifsController;

return
    [
        ['/', [GifsController::class, 'index']],
    ];
