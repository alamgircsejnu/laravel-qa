<?php

namespace App;

use Dotenv\Parser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Markdown;
use Kaishiyoku\HtmlPurifier\HtmlPurifier;
use Mews\Purifier\Facades\Purifier;

class Question extends Model
{
    use VotableTrait;
    
    protected $fillable = ['title', 'body'];

    protected $appends = ['created_date', 'is_favorited', 'favorites_count', 'body_html'];

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class)->orderByDesc('votes_count');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }

    public function getUrlAttribute()
    {
        return route("questions.show",$this->slug);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute()
    {
        if ($this->answers_count > 0)
        {
            if ($this->best_answer_id)
            {
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;

        $this->save();
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); // 'question_id', 'user_id');
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getBodyHtmlAttribute()
    {
        $purifier = new HtmlPurifier();

        return $purifier->purify($this->bodyHtml());
    }

    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }

    public function excerpt($length)
    {
        return \Str::limit(strip_tags($this->bodyHtml()),$length);
    }

    private function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }

}
