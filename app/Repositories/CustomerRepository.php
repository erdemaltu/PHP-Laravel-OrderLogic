<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function find($id)
    {
        return Customer::find($id);
    }
}
