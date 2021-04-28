<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class History extends Model
{
    public $incrementing = false;
    protected $table = 'history';
    protected $primaryKey = 'id_history';
    protected $fillable = [
        'id_history','id_bahan','tipe_stock','stock','harga_bahan','tanggal','is_deleted'
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
