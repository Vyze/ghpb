<?php
/**
 * Created by Vyze
 * Date: 01/05/2015
 */

namespace vyze\ghpb;
use Illuminate\Database\Eloquent\Model;

class GhRepoModel extends Model{

    protected $table = 'ghpb_repo';
    protected $fillable = array('name','owner');
    protected $appends = array('fullname');

    public $timestamps = false;

//    //validation rules
//    public static $rules = array(
//    );

    public function getFullnameAttribute() {
        return $this->attributes['owner'].'/'.$this->attributes['name'];
    }
}