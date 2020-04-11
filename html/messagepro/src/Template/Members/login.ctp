<div>
    <?= $this->Html->link(__('New Member'), ['action' => 'add']) ?>
</div>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Log in') ?></legend>
        <?php
        echo $this->Form->control('membername');
        echo $this->Form->control('memberpass', ['type' => 'password']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>