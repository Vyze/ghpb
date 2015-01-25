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

        $project = GhRepoModel::getRepo($owner, $name);
        $contributors = GhContributorModel::getRepoUsers($owner,$name);

        return \View::make('ghpb::pages.project',array('properties'=>$properties, 'project'=>$project,'contributors'=> $contributors));
    }

    public function showSearch() {
        $properties = array();

        $q = \Input::get('query');
        $properties['title']='Search';
        $properties['query'] = $q;
        $properties['show_repo_user'] = true;

        //search result
        $repositories = GhRepoModel::getSearched($q);

        return \View::make('ghpb::pages.search',array('properties'=>$properties,'repositories'=>$repositories));
    }

    public function showUser() {

        if(!$name = array_key_exists('name',$_GET) ? $_GET['name'] : false){
            return "Incorrect username";
        }

        $properties =  GhContributorModel::getUser($name);
        $repositories = GhRepoModel::getUserRepos($name);

        return \View::make('ghpb::pages.user',array('properties'=>$properties,'repositories'=>$repositories));
    }

    /**
     * Creating a record in db for current user, if it doesn't exist
     * @return bool
     */
    private function createGhUser() {

        $cur_user = GitHub::me()->show();

        /**
         * Define cur_user_id constant for the namespace vyze\ghpb
         */
        define('cur_gh_user_id',$cur_user['id']);

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
     * Add/Delete record if like-button
     */
    public function changeLikeStatus() {

        if(!Request::ajax()) return false;

        $properties = array(
            'table' => $_POST['datatype'],
            'id'    => $_POST['id'],
            'name'  => $_POST['name'],
            'liked' => $_POST['liked'],
        );

        if($properties['table'] =='user'){
           $result = GhContributorModel::likeItem($properties);
        }else{
            $result = GhRepoModel::likeItem($properties);
        }

        return \View::make('ghpb::parts.btn_star')->with(array('text'=> $properties['liked'] ? 'Like':'Unlike'));
    }
}
