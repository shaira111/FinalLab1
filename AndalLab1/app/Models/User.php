<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    
    class User extends Model{
    // name sa table
        protected $table = 'tbl_users';
    // column sa table
        protected $fillable = [
            'username', 'password', 'gender'
        ];

        public $timestamps = false;
        protected $primaryKey = 'id';
    }