<?php
/**
 * Created by Vyze
 * Date: 01/05/2015
 */

namespace vyze\ghpb;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class GhUserModel extends Model{

    protected $table = 'ghpb_user';
    protected $fillable = array('username','name');

    public $timestamps = false;

//    //validation rules
//    public static $rules = array(
//    );
}