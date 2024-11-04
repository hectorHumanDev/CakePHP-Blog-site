<?php
declare(strict_types=1);

namespace App\Model\Entity;
use Cake\Collection\Collection;
use Cake\ORM\Entity;

class Article extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'title' => true,
        'slug' => true,
        'body' > true,
        'published' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'tags' => true,
        'tag_string' => true
    ];

    public function _getTagString()
    {
        if (isset($this->fields['tag_string'])) {
            return $this->fields['tag_string'];
        }
        if (empty($this->tags)) {
            return '';
        }
        $tags = new Collection($this->tags);
        $str = $tags->reduce(function ($string, $tag) {
            return $string . $tag->title . ',';
        }, '');
        return trim($str, ', ');
    }
}