<?php namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Admin extends ResourceController
{
    use ResponseTrait;

    //Metedo Select Admin.
	public function index()
    {
        $model = new AdminModel();
        $data = $model->findAll();
        return $this->respond($data);
    }

    //Metedo Insert Admin.
    public function create()
    {
        $model = new AdminModel();
        $data = $this->request->getJSON();

        if($model->insert($data)){
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Dados salvos'
                ]
            ];
            return $this->respondCreated($response);
        }

        return $this->fail($model->errors());
    }

    //Metedo Update Admin.
    public function update($id = null)
    {
        $model = new AdminModel();
        $data = $this->request->getJSON();
        
        if($model->update($id, $data)){
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Dados atualizados'
                    ]
                ];
                return $this->respond($response);
        };

        return $this->fail($model->errors());
    }
    
    //Metedo Delete Admin.
    public function delete($id = null)
    {
        $model = new AdminModel();
        $data = $model->find($id);
        
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Dados removidos'
                ]
            ];
            return $this->respondDeleted($response);
        }
        
        return $this->failNotFound('Nenhum dado encontrado com id '.$id);        
    }
}
