<?php

namespace App\Helpers;

use App\Helpers\{Str, Session};

class CSRFProtection {
    public static function token()
    {
        $csrfToken = Str::token();
        Session::set('csrfToken', $csrfToken);

        return $csrfToken;
    }
}
