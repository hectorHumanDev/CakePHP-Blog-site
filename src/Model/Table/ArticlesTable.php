<?php
declare(strict_types=1);

namespace App\Model\Table;
use Cake\ORM\Query\SelectQuery;
use Cake\Validation\Validator;
use Cake\ORM\Table;
use Cake\Utility\Text; // the Text class
use Cake\Event\EventInterface; // the EventInterface class




class ArticlesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->addBehavior("Timestamp");
        $this->belongsToMany('Tags', ['joinTable' => 'articles_tags', 'dependent' => true]); //creates association between article and multiple tags
    }

    public function beforeSave(EventInterface $event, $entity, $options)
    {
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }

        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    protected function _buildTags($tagString)
    {
        $newTags = array_map('trim', explode(',', $tagString));
        $newTags = array_filter($newTags);
        $newTags = array_unique($newTags);

        $out = [];
        $tags = $this->Tags->find()->where(['Tags.title IN' => $newTags])->all();

        foreach ($tags->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        foreach ($tags as $tag) {
            $out[] = $tag;
        }

        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }
        return $out;
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title')
            ->minLength('title', 10)
            ->maxLength('title', 255)

            ->notEmptyString('body')
            ->minLength('body', 1);

        return $validator;
    }

    public function findTagged(SelectQuery $query, array $tags = [])
    {
        $columns = [
            'Articles.id',
            'Articles.user_id',
            'Articles.title',
            'Articles.body',
            'Articles.published',
            'Articles.created',
            'Articles.slug'
        ];
        $query = $query->select($columns)->distinct($columns);

        if (empty($tags)) {
            $query->leftJoinWith('Tags')->where(['Tags.title IS' => null]);
        } else {
            $query->innerJoinWith('Tags')->where(['Tags.title IN' => $tags]);
        }
        return $query->groupBy(['Articles.id']);
    }

}