<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentModel extends Model {

    protected $table = 'content.content_management';
    protected $primaryKey = 'id_content';

    protected $fillable = [
        'id_content',
        'id_content_type',
        'title',
        'description',
        'status',
        'created_at',
        'created_by',
        'image_path'
    ];
    
    public $timestamps = false;
}
