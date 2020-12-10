<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DataRepository
{

    public static function SaveOrEdit($key,$req,$tablename) {
        if (array_key_exists($key,$req)) {
            if (is_null($req[$key])) {
                unset($req[$key]);
                $req['created_date'] = Carbon::now();
                //$req['created_by'] = Auth::user()->id;
                $id = DB::table($tablename)->insertGetId($req, $key);
            } else {
                $id = $req[$key];
                $req['update_date'] = Carbon::now();
                DB::table($tablename)->where($key,$id)->update($req);
            }
        } else {
            $req['created_date'] = Carbon::now();
            $id = DB::table($tablename)->insertGetId($req, $key);
        }
        return $id;
    }

    public static function CreateUser($key,$req) {

        if (!(is_null($req[$key]))) {
            $arr[$key] = $req[$key];
            $arr['email'] = $req['email'];
        } else {
            $arr['username'] = substr(str_replace(' ', '', $req['full_name']),0,6).Carbon::parse($req['date_of_birth'])->format('dy');
            $arr['password'] = bcrypt(Carbon::parse($req['date_of_birth'])->format('dmY'));
            $arr['email'] = $req['email'];
        }

        return $arr;
    }


    
}