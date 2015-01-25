<?php
/**
 * Created by Vyze
 * Date: 01/05/2015
 */

namespace vyze\ghpb;
use GrahamCampbell\GitHub\Facades\GitHub;

class GhRepoModel extends \Eloquent {

    protected $table = 'ghpb_repo';
    protected $fillable = array('name', 'owner');
    protected $appends = array('fullname');

    public $timestamps = false;

    public function getFullnameAttribute() {
        return $this->attributes['owner'] . '/' . $this->attributes['name'];
    }

    /**
     * Get full names of liked repositories
     * @param string $cur_user_id - current user's ID
     * @param string $owner
     * @param string $name
     * @return array list
     */
    public static function getLikedRepos($owner = '', $name = '') {

        $where = array('ghuser_id' => cur_gh_user_id);
        if ($owner && $name) {
            $where['owner'] = $owner;
            $where['name'] = $name;
        }

        $result = GhRepoModel::where($where)->get();

        return array_column($result->toArray(), 'fullname');
    }

    /**
     * Get repository's data through graham-campbell\github
     * @param string $owner
     * @param string $name
     */
    public static function getRepo($owner, $name) {
        $repo = GitHub::repo()->show($owner, $name);
        $repo['github_repo'] = 'https://github.com/' . $repo['full_name'];

        //check liked
        self::addLikeAttributes($repo, true);

        return $repo;

    }

    /**
     * Get user's repositories through graham-campbell\github
     * @param string $user
     * @return array
     */
    public static function getUserRepos($user) {
        $repositories = GitHub::users()->repositories($user);

        //check liked
        self::addLikeAttributes($repositories);

        return $repositories;

    }

    /**
     * Search repositories through graham-campbell\github
     * @param string $query
     */
    public static function getSearched($query='') {

        $q_fomated = str_replace(' ','+',trim($query));

        //search result
        $list =  GitHub::repo()->find($q_fomated);
        $repositories = $list['repositories'];
        //check likes
        self::addLikeAttributes($repositories);

        return $repositories;
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
        $type = 'repo';

        if ($one) {
            $source['type'] = $type;
            $source['id'] = $source['owner']['login'];
            $likedItems = self::getLikedRepos($source['id'], $source['name']);
            $source['liked'] = in_array($source['full_name'], $likedItems);

        } else {
            $likedItems = self::getLikedRepos();

            foreach ($source as &$el) {

                if (array_key_exists('full_name', $el)) {
                    $el['id'] = $el['owner']['login'];
                    $key = $el['full_name'];
                } else {
                    $el['id'] = $el['owner'];
                    $key = $el['owner'] . '/' . $el['name'];
                }

                $el['type'] = $type;
                $el['liked'] = in_array($key, $likedItems);
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
            $record = new GhRepoModel;
            $record->owner = $props['id'];
            $record->name = $props['name'];
            $record->ghuser_id = cur_gh_user_id;

            return $record->save();
        }
    }
}