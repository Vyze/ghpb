<?php
namespace vyze\ghpb;

use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Config\Repository;

class GhpbController extends \Controller {

    /**
     * The config instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var string current user's ID
     */
    protected $cur_user_id = '';

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = \View::make($this->layout);
        }
    }

    /**
     * Install a new GhpbController instance.
     */
    public function __construct() {
        $this->createGhUser();
    }

    public function showIndexPage() {
        return \View::make('ghpb::layout.default');
    }

    public function showProject() {
        $properties = array();

        $def_owner = Config::get('ghpb::default_ghowner');
        $def_name = Config::get('ghpb::default_ghproject');
//        $def_owner = 'Yiisoft';
//        $def_name = 'yii';

        $owner = array_key_exists('owner',$_GET)? $_GET['owner']: '';
        $name = array_key_exists('name',$_GET)? $_GET['name']: '';

        //check with default values
        if(! ( $owner && $name ) ){
            $owner = $def_owner;
            $name = $def_name;
        }

        //Set page header
        if( strtolower($owner)!=strtolower($def_owner) || strtolower($name)!=strtolower($def_name) ){
            $properties['title']='Project';
        }

        $project = GitHub::repo()->show($owner, $name);
        $project['github_repo']='https://github.com/'.$project['full_name'];
        $contributors = Github::repo()->contributors($owner,$name);

        //check liked
        $this->addLikeAttributes($project,'repo',true);
        $this->addLikeAttributes($contributors,'user');

//        dd($project);
        return \View::make('ghpb::pages.project',array('properties'=>$properties, 'project'=>$project,'contributors'=> $contributors));
    }

    public function showSearch() {
        $properties = array();

        $q = \Input::get('query');
        $properties['title']='Search';
        $properties['query'] = $q;
        $properties['show_repo_user'] = true;
        $q_fomated = str_replace(' ','+',trim($q));
        //search result
        $list =  GitHub::repo()->find($q_fomated);
        $repositories = $list['repositories'];

        //check likes
        $this->addLikeAttributes($repositories,'repo');

        return \View::make('ghpb::pages.search',array('properties'=>$properties,'repositories'=>$repositories));
    }

    public function showUser() {


        if(!$name = $_GET['name']){
            return "Incorrect username";
        }

        $properties =  GitHub::users()->show($name);
        $properties['title']= 'User';
        $properties['show_repo_user'] = false;

        $repositories = GitHub::users()->repositories($name);

        //check likes
        $this->addLikeAttributes($properties,'user',true);
        $this->addLikeAttributes($repositories,'repo');
//        dd($properties);//DEBUG
        return \View::make('ghpb::pages.user',array('properties'=>$properties,'repositories'=>$repositories));
    }

    /**
     * Creating a record in db for current user, if it doesn't exist
     * @return bool
     */
    private function createGhUser() {

        $cur_user = GitHub::me()->show();
        $this->cur_user_id= $cur_user['id'];
        if ($cur_user && !GhUserModel::find($cur_user['id'],array('id'))){

            $ghpb_user = new GhUserModel;
            $ghpb_user->id = $cur_user['id'];
            $ghpb_user->username = $cur_user['login'];
            if(array_key_exists('name',$cur_user)){
                $ghpb_user->name = $cur_user['name'];
            }
            return $ghpb_user->save();
        }
    }

    /**
     * Return the same array, but with additional attributes for every element:
     * attr. 'datatype' = $type
     * attr. 'liked': true if there is such record in database
     * attr. 'id' for repo would switch to repo's owner
     * @param array $source
     * @param bool $only_one set to TRUE if you operate with only element
     * @param string type set source type if empty (user,repo etc.)
     * @return array
     */
    private function addLikeAttributes(&$source = array(), $type, $only_one = false) {

        if(!$source) return;

        if($type=='user'){
            if($only_one){
                $source['type']=$type;
                $likedItems =  $this->getLikedUsers($source['id']);
//                dd($likedItems);
                $source['liked'] = in_array($source['id'],$likedItems);

            }else{
                $likedItems =  $this->getLikedUsers();

                foreach ($source as &$el) {
                    $el['type']=$type;
                    $el['liked'] = in_array($el['id'],$likedItems);
                }
            }
        }else{
            if($only_one){
                $source['type']= $type;
                $source['id']= $source['owner']['login'];
                $likedItems =  $this->getLikedRepos($source['id'], $source['name']);
                $source['liked'] = in_array($source['full_name'],$likedItems);

            }else{
                $likedItems =  $this->getLikedRepos();

                foreach ($source as &$el) {

                    if(array_key_exists('full_name',$el)){
                        $el['id']= $el['owner']['login'];
                        $key = $el['full_name'];
                    }else{
                        $el['id']= $el['owner'];
                        $key = $el['owner'].'/'.$el['name'];
                    }

                    $el['type'] = $type;
                    $el['liked'] = in_array($key,$likedItems);
                }
            }
        }

    }

    /**
     * Get IDs of liked users
     * @param string $id
     * @return array list
     */
    protected function getLikedUsers($id = '') {

        $where = array('ghuser_id'=> $this->cur_user_id);
        if($id) $where['id'] = $id;

        $result = GhContributorModel::where($where)->get(array('id'));

        return array_column($result->toArray(),'id');
    }

    /**
     * Get full names of liked repositories
     * @param string $owner
     * @param string $name
     * @return array list
     */
    protected function getLikedRepos($owner = '', $name = '') {

        $where = array('ghuser_id'=> $this->cur_user_id);
        if($owner && $name){
            $where['owner'] = $owner;
            $where['name'] = $name;
        }

        $result = GhRepoModel::where($where)->get();

        return array_column($result->toArray(),'fullname');
    }

    /**
     * Add/Delete record if like-button
     */
    public function changeLikeStatus() {

        if(!Request::ajax()) return false;

        $table = $_POST['datatype'];
        $id    = $_POST['id'];
        $name  = $_POST['name'];
        $liked = $_POST['liked'];

        if($table =='user'){
            if($liked){
                GhContributorModel::destroy($id);
                return \View::make('ghpb::parts.btn_star')->with(array('text'=>'Like'));

            }else{
                if(GhContributorModel::find($id,['id'])) return false;

                $record = new GhContributorModel;
                $record->id = $id;
                $record->ghuser_id = $this->cur_user_id;
                $record->username = $name;

                $record->save();
                return \View::make('ghpb::parts.btn_star')->with(array('text'=>'Unlike'));
            }
        }else{

            if($liked){
                GhRepoModel::destroy($id);
                return \View::make('ghpb::parts.btn_star')->with(array('text'=>'Like'));

            }else{
                if(GhRepoModel::firstByAttributes(array('owner'=>$id,'name'=>$name))) return false;

                $record = new GhRepoModel;
                $record->owner = $id;
                $record->name = $name;
                $record->ghuser_id = $this->cur_user_id;

                $record->save();
                return \View::make('ghpb::parts.btn_star')->with(array('text'=>'Unlike'));
            }
        }
    }
}
