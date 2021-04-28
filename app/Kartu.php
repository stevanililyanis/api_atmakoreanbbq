<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kartu extends Model
{
    public $incrementing = false;
    protected $table = 'kartu';
    protected $primaryKey = 'id_kartu';
    protected $fillable = [
        'id_kartu','nama_kartu','jenis_kartu','nomor_kartu','is_deleted'
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
