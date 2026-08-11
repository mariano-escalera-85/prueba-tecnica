<?php

namespace App\Http\Integrations\Contracts;

interface ValidatesResponse
{
    /**
     * Get the validation rules that apply to the response data.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function responseRules(): array;
}
