<?php

namespace App\Imports;

use App\Models\Contacts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Throwable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class ContactsImport implements ToModel, 
    WithHeadingRow, 
    SkipsOnError,
    WithValidation,
    SkipsOnFailure
{
    use Importable,SkipsErrors,SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contacts([
            'name'=>$row['name'],
            'email'=>$row['email'],
            'phone'=>$row['phone'],
            'address'=>$row['address']
        ]);
    }

    // public function onError(Throwable $error){

    // }

    public function rules(): array
    {
        return [
            '*.email' => ['email', 'unique:contacts,email']
        ];
    }
    // public function onFailure(Failure ...$failure)
    // {
    // }
}