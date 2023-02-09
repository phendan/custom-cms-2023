<?php

namespace App\Traits;

use App\Helpers\Str;

trait Fillable {
    public function fill(array $data = []) {
        foreach ($data as $field => $value) {
            $this->{Str::toCamelCase($field)} = $value;
        }
    }
}
