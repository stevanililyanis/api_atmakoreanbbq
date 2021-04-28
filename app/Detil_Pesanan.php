<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Detil_Pesanan extends Model
{
    public $incrementing = false;
    protected $table = 'detil_pesanan';
    protected $primaryKey = 'id_detil_pesanan';
    protected $fillable = [
        'id_detil_pesanan','jumlah_pesanan', 'subtotal_harga', 'status_pesanan','id_menu','id_pesanan' ,'is_deleted'
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
