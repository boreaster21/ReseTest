<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fav;
use App\Models\ShopReview;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'area',
        'ganre',
        'detail',
        'URL',
    ];

    public function reviews(){
        return $this->hasMany(ShopReview::class, 'shop_id', 'id');
    }
    public function favs()
    {
        return $this->hasMany(Fav::class, 'shop_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * リプライにFavを付いているかの判定
     *
     * @return bool true:Likeがついてる false:Likeがついてない
     */
    public function is_faved_by_auth_user()
    {
        $id = Auth::id();
        $favers = array();
        foreach ($this->favs as $fav) {
            array_push($favers, $fav->user_id);
        }

        if (in_array($id, $favers)) {
            return true;
        } else {
            return false;
        }
    }

    public function scopeAreaSearch($query, $area)
    {
        if (!empty($area)) {
            $query->where('area', $area);
        }
    }

    public function scopeGanreSearch($query, $ganre)
    {
        if (!empty($ganre)) {
            $query->where('ganre', $ganre);
        }
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->orwhere('name', 'like', '%' . $keyword . '%')
            ->orwhere('detail', 'like', '%' . $keyword . '%');
        }
    }
}
