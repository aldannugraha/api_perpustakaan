<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Helper\CustomController;

class BooksController extends CustomController{


    public function index()
    {
        $data = Books::with(['kategori:id,nama'])->orderBy('created_at', 'DESC')->get();
        if (!$data) {
            return $this->jsonNotFoundResponse('not found!');
        }
        return $this->jsonSuccessResponse('success', $data);
    }

    public function store()
    {
        try {
            $body = $this->parseRequestBody();
            $data = [
                'kategori_id' => $body['kategori_id'],
                'judul' => $body['judul'],
                'penulis' => $body['penulis'],
                'penerbit' => $body['penerbit'],
                'tahun_terbit' => $body['tahun_terbit'],
            ];
            $add = Books::create($data);
            return $this->jsonCreatedResponse('success', $add);
        } catch (\Throwable $e) {
            return $this->jsonErrorResponse('internal server error ' . $e->getMessage());
        }
    }

    public function getByID($id)
    {
        try {
            $data = Books::with([])->where('id', '=', $id)->first();
            if (!$data) {
                return $this->jsonNotFoundResponse(' not found');
            }
            if ($this->request->method() === 'POST') {
                return $this->patch($data);
            }
            return $this->jsonSuccessResponse('success', $data);
        } catch (\Throwable $e) {
            return $this->jsonErrorResponse('internal server error ' . $e->getMessage());
        }
    }

    private function patch($data)
    {
        $body = $this->parseRequestBody();
        $data_request = [
            'kategori_id' => $body['kategori_id'],
            'judul' => $body['judul'],
            'penulis' => $body['penulis'],
            'penerbit' => $body['penerbit'],
            'tahun_terbit' => $body['tahun_terbit'],
        ];
        $data->update($data_request);
        return $this->jsonCreatedResponse('success');
    }

}