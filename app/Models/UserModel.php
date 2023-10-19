<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email_id',
        'mobile_No',
        'fullName',
        'userName',
        'password',
        'resetToken'
    ];
}