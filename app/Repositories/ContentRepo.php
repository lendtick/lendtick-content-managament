<?php

namespace App\Repositories;

use App\Models\ContentModel as ContentDB;
use Illuminate\Database\QueryException;

class ContentRepo{
	
	public function all($columns = array('*')){
		try {
			if($columns == array('*')) return ContentDB::all();
			else return ContentDB::select($columns)->get();
		}catch(QueryException $e){
			throw new \Exception($e->getMessage(), 500);
		}
	}

	public function ByConditions($where , $value){
		try {
			return ContentDB::where($where, $value)->orderBy('id_content', 'desc')->get();
		}catch(QueryException $e){
			throw new \Exception($e->getMessage(), 500);
		}
	}

	public function create(array $data){
		try {
			return ContentDB::create($data);
		}catch(QueryException $e){
			throw new \Exception($e->getMessage(), 500);
		}
	}

	public function find($column, $value){
		try {
			return ContentDB::where($column, $value)->first();
		}catch(QueryException $e){
			throw new \Exception($e->getMessage(), 500);
		}
	}

	public function update($id, array $data){
		try { 
			return ContentDB::where('MsProductId',$id)->update($data);
		}catch(QueryException $e){
			throw new \Exception($e->getMessage(), 500);
		}	
	} 

	public function delete($id){
		try { 
			return ContentDB::where('MsProductId',$id)->delete();
		}catch(QueryException $e){
			throw new \Exception($e->getMessage(), 500);
		}
		
	}

	public function last(){
		try{
			return ContentDB::orderBy('Id', 'desc')->first();
		}catch(QueryException $e){
			throw new \Exception($e->getMessage(), 500);
		}
	}
}
