<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bahan extends Model
{
    public $incrementing = false;
    protected $table = 'bahan';
    protected $primaryKey = 'id_bahan';
    protected $fillable = [
        'id_bahan','nama_bahan','harga_bahan','unit','remaining_stock','is_deleted'
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
