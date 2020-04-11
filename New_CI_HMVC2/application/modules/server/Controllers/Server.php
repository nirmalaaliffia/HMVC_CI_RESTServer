<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require(APPPATH . 'libraries/Format.php');
require(APPPATH . 'libraries/REST_Controller.php');

// use namespace
use Restserver\Libraries\REST_Controller;


class Server extends REST_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Server/Mahasiswa_model','mahasiswa');
		$this->load->database();
    }

	public function index_get()
	{
		$id=$this->get('id');
        if($id == null){
            $mahasiswa = $this->mahasiswa->getMahasiswa();
        }else{
            $mahasiswa = $this->mahasiswa->getMahasiswa($id);
        }
       
        if($mahasiswa){
            $this->response([
                'status' => TRUE,
                'mahasiswa' => $mahasiswa
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'ID not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
	}

	public function index_delete()
    {
        $id = $this->delete('id');
        if($id == null){
            $this->response([
                'status' => FALSE,
                'message' => 'Provide an ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }else{
            if($this->mahasiswa->deleteMahasiswa($id) > 0){
                $this->response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'Deleted the resource'
                ], 201);
            }else{
                $this->response([
                    'status' => FALSE,
                    'message' => 'ID not found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post()
    {
        $data=[
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan'),
        ];

        if($this->mahasiswa->createMahasiswa($data) > 0){
            $this->response([
                'status' => TRUE,
                'message' => 'New mahasiswa has been created.'
            ], REST_Controller::HTTP_CREATED);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to create new data'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $data=[
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan'),
        ];

        if($this->mahasiswa->updateMahasiswa($data,$id) > 0){
            $this->response([
                'status' => TRUE,
                'message' => 'mahasiswa has been updated.'
            ], 200);
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Failed to update data'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
