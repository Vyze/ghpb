<?php
/**
 * Created by Vyze
 * Date: 01/05/2015
 */

namespace vyze\ghpb;
use GrahamCampbell\GitHub\Facades\GitHub;

class GhContributorModel extends \Eloquent{

    protected $table = 'ghpb_contributor';
    protected $fillable = array('username','liked');

    public $timestamps = false;

    //validation rules
    public static $rules = array(
        'id' => 'reqired',
        'ghuser_id' => 'reqired',
        'username' => 'reqired',
    );

    /**
     * Get IDs of liked users
     * @param string $cur_user_id - current user's ID
     * @param string $id
     * @return array list
     */
    public static function getLikedUsers($id = '') {

        $where = array('ghuser_id'=> cur_gh_user_id);
        if($id) $where['id'] = $id;

        $result = self::where($where)->get(array('id'));

        return array_column($result->toArray(),'id');
    }

    /**
     * Get user's data through graham-campbell\github
     * @param string $name
     */
    public static function getUser($name){
        $user =  GitHub::users()->show($name);
        $user['title']= 'User';
        $user['show_repo_user'] = false;

        //check liked
        self::addLikeAttributes($user, true);

        return $user;
    }

    /**
     * Get repository's contributors
     * @param string $owner - repo's owner
     * @param string $name - repo's name
     * @return array
     */
    public static function getRepoUsers($owner, $name) {
        $users = Github::repo()->contributors($owner,$name);

        //check liked
        self::addLikeAttributes($users);

        return $users;

    }

    /**
     * Return the same array, but with additional attributes for every element:
     * attr. 'liked': true if there is such record in database
     * attr. 'id' for repo would switch to repo's owner
     * @param array $source
     * @param bool $one set to TRUE if you operate with only element
     * @return array
     */
    protected static function addLikeAttributes(&$source = array(), $one = false) {

        if (!$source) return;
        $type = 'user';

        if($one){
            $source['type']=$type;
            $likedItems = self::getLikedUsers($source['id']);
            $source['liked'] = in_array($source['id'],$likedItems);

        }else{
            $likedItems =  self::getLikedUsers();

            foreach ($source as &$el) {
                $el['type']=$type;
                $el['liked'] = in_array($el['id'],$likedItems);
            }
        }
    }

    /**
     * Add/remove record if $props['liked'] is TRUE
     * @param array $props
     * @return bool TRUE for success operation
     */
    public static function likeItem($props) {
        if($props['liked']){
            return self::destroy($props['id']);
        }else{
            $record = new GhContributorModel();
            $record->username = $props['name'];
            $record->ghuser_id = cur_gh_user_id;

            return $record->save();
        }
    }
}