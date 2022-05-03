<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Request as RequestModel;

class RequestFactory extends Factory
{
    protected $model = RequestModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name"=>"Teko Puyol",
            "email"=>"teko.puyol@gmail.com",
            "password"=>"password",
        ];
    }
}
