<?php echo $this->Html->css('checkpayment'); ?>
<?php foreach ((array)$cardsInfoOwn as $key => $cardsInfoOwn) : ?>
    <div class="flex">
        <div class="cardInfo">
            <p><?= $cardsInfoOwn['name']; ?></p>
            <p>****-****-****-<?= $cardsInfoOwn['card_number']; ?> - 有効期限 <?= $cardsInfoOwn['deadline']; ?></p>
        </div>
        <div class="action">
            <p><?= $this->Html->link('編集', ['action' => 'addpayment', 'id' => $cardsInfoOwn['id']], ['class' => 'button back-gray']) ?></p>
            <p><?= $this->Html->link('削除', '#', ['class' => 'button back-gray delete-payment', 'id' => urlencode($cardsInfoOwn['id'])]); ?></p>
        </div>
    </div>
<?php endforeach; ?>
<div class="half-button flex">
    <?= $this->Html->link('マイページに戻る', ['action' => 'top'], ['class' => 'button back-gray']); ?>
    <?= $this->Html->link('新規登録', '#', ['class' => 'button back-orange add-payment', 'id' => 'add-payment']); ?>
</div>
<?= $this->Html->script('jquery.min') ?>
<?= $this->Html->script('checkpayment') ?>
