<div class="users form">
    <?= $this->Flash->render() ?>
    <h3>Register</h3>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <br>
        <?= $this->Form->control('email', ['required' => true]) ?>
        <?= $this->Form->control('password', ['required' => true, 'type' => 'password']) ?>
    </fieldset>
    <?= $this->Form->submit(__('Register', ['action' => 'register'])); ?>
    <?= $this->Form->end() ?>
</div>