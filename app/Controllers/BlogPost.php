<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;

class BlogPost extends BaseController
{
    use ResponseTrait;

    public function __construct(){
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
            header('Access-Control-Allow-Headers: token, Content-Type');
            header('Access-Control-Max-Age: 1728000');
            header('Content-Length: 0');
            header('Content-Type: text/plain');
            die();
        }

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header('Content-Type: application/json');
    }

    /**
     *
     * Get all BlogPosts
     *
     * @return mixed
     * returns array of BlogPosts
     */
	public function index()
	{
        $blogPosts = model('App\Models\BlogPost');

        return $this->respond($blogPosts->findAll(), 200);
	}

    /**
     *
     * Create a new BlogPost
     *
     * @return Response|mixed
     * returns new created BlogPost on success
     */
	public function create(){
        $validation =  \Config\Services::validation();

        $validation->setRules([
            "status" => ["rules" => "permit_empty|in_list[Draft,Published]"],
            "title" => ["rules" => "required|min_length[3]|max_length[60]"],
            "description" => ["rules" => "required|min_length[20]"]
        ]);

        if(!$validation->withRequest($this->request)->run()){
            return $this->fail($validation->getErrors(), 422);
        }

        $blogPost = new \App\Models\BlogPost();

        $blogPostData = [
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description')
        ];

        if($this->request->getVar("status")){
            $blogPostData['status'] = $this->request->getVar('status');
        }

        try{
            if($blogPost->save($blogPostData)){
                $insertedPost = $blogPost->find($blogPost->getInsertID());
                return $this->respond($insertedPost);
            }
        }catch(\ReflectionException $e){
            return $this->failServerError();
        }

        return $this->failServerError();
    }

    /**
     *
     * Get single BlogPost
     *
     * @param $postId
     * @return mixed
     * returns single BlogPost on success
     */
    public function getSingle($postId){
	    $blogPost = model('App\Models\BlogPost')->find($postId);

	    if(empty($blogPost)){
	        return $this->failNotFound();
        }

        return $this->respond($blogPost);
    }


    /**
     *
     * Updates data of single BlogPost
     *
     * @param $postId
     * @return mixed
     * returns updated BlogPost on success
     */
    public function postSingle($postId){
        $validation =  \Config\Services::validation();

        $validation->setRules([
            "status" => ["rules" => "permit_empty|in_list[Draft,Published]"],
            "title" => ["rules" => "permit_empty|min_length[3]|max_length[60]"],
            "description" => ["rules" => "permit_empty|min_length[20]"]
        ]);


        if(!$validation->withRequest($this->request)->run()){
            return $this->fail($validation->getErrors(), 422);
        }

        $blogPostModel = model('App\Models\BlogPost');
        $blogPost = $blogPostModel->find($postId);

        if(empty($blogPost)){
            return $this->failNotFound();
        }

        $replaceData = [];

        if($this->request->getVar('status') !== null){
            $replaceData['status'] = $this->request->getVar('status');
        }
        if($this->request->getVar('title') !== null){
            $replaceData['title'] = $this->request->getVar('title');
        }
        if($this->request->getVar('description') !== null){
            $replaceData['description'] = $this->request->getVar('description');
        }

        $blogPost = array_replace_recursive($blogPost, $replaceData);

        $blogPostModel->save($blogPost);

        return $this->respond($blogPost);
    }


    /**
     *
     * Delete single BlogPost
     *
     * @param $postId
     * @return Response|mixed
     * returns HTTP 204 on success
     */
    public function deleteSingle($postId){
	    /** @var \App\Models\BlogPost $blogPost */
        $blogPost = model('App\Models\BlogPost')->find($postId);

        if(empty($blogPost)){
            return $this->failNotFound();
        }

        if(model('App\Models\BlogPost')->delete($postId) !== false){
            return $this->respond(null, 204 );
        }

        return $this->failServerError();
    }
}
