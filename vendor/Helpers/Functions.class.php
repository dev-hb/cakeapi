<?php


class Functions
{

    public static function generateToken(){
        $prefix = "CrDrive-";
        $token = [];
        for($i=0; $i < 20; $i++) $token[] = chr(rand(ord('A'), ord('Z')));
        for($i=0; $i < 20; $i++) $token[] = chr(rand(ord('a'), ord('z')));
        for($i=0; $i < 20; $i++) $token[] = chr(rand(ord('0'), ord('9')));
        shuffle($token);
        $token = implode("", $token);
        return Storage::makeFolder($prefix.$token) ? $prefix.$token : null;
    }

}