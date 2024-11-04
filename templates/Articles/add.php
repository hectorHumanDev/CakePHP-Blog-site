<h1>Add Article</h1>
<?php
echo $this->Form->create($article, ['type' => 'post']);
echo $this->Form->control('user_id', ['type' => 'hidden', 'value' => 1]);  // Corrected here
echo $this->Form->control('title');
echo $this->Form->control('body');
echo $this->Form->button(__('Save Article'));
echo $this->Form->control('tag_string', ['type' => 'text']);
echo $this->Form->end();

?>