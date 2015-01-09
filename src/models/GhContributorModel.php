<?php
/**
 * Created by Vyze
 * Date: 01/05/2015
 */

namespace vyze\ghpb;
use Illuminate\Database\Eloquent\Model;

class GhContributorModel extends Model{

    protected $table = 'ghpb_contributor';
    protected $fillable = array('username','liked');

    public $timestamps = false;

    //validation rules
    public static $rules = array(
        'id' => 'reqired',
        'ghuser_id' => 'reqired',
        'username' => 'reqired',
    );

//    public function save(array $options=array()){
//
////        check if not unique
//
////        $a = $this->exists();
////        dd($a);
////        exit("~~~DEBUG~~~: model save");           //DEBUG<<<
//        parent::save();
//    }
}