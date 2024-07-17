<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = [
        'comment_count'
    ];

     /**
     * The has Many Relationship
     *
     * @var array
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function getcommentCountAttribute()
    {
 
         $comment = Comment::groupBy('blog_id')->select('blog_id', DB::raw('count(blog_id) as total_comments'))->where('blog_id', $this->id)->first();   
        if($comment !=null)
            return $comment;
        else 
            return 0;
    }
}
