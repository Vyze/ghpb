<?php
/**
 * Created by Vyze
 * Date: 01/05/2015
 */

namespace vyze\ghpb;

class GhUserModel extends \Eloquent{

    protected $table = 'ghpb_user';
    protected $fillable = array('username','name');

    public $timestamps = false;

}