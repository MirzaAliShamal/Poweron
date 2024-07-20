<?php

namespace App\Imports;

use App\Models\Subscriber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubscriberImport implements ToModel, WithHeadingRow
{
    protected $email_list_id;

    public function __construct($email_list_id) {
        $this->email_list_id = $email_list_id;
    }
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new Subscriber([
           'name'           => $row['name'],
           'email'          => $row['email'],
           'email_list_id'  => $this->email_list_id,
        ]);
    }
}
