<?php
/* @var $this yii\web\View */

/* @var array $dataVerbs */


use frontend\assets\VerbsAsset;

$this->title = 'Verbs';
$this->params['breadcrumbs'][] = $this->title;

VerbsAsset::register($this);

?>

<table border="1" style="margin: 0 auto; width: 80%" class="verbs-table">
    <thead>
    <tr>
        <td><b>Infinitive</b></td>
        <td><b>Past Simple</b></td>
        <td><b>Past Participle</b></td>
        <td><b>Translation</b></td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($dataVerbs as $key => $dataVerb): ?>
        <tr data-number-tr="<?= $key ?>">
            <?php $notShow = mt_rand(0, 2); ?>
            <?php foreach ($dataVerb as $keyTd => $item): ?>

                <?php
                $input = "<input type='text' data-val='$item' class='border verb-input' style='border-width: 3px !important;'>";
                $icon = '<svg data-toggle="tooltip" title="' . $item . '" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill icon-info" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
</svg>';
                ?>
                <?= "<td>" .
                ($keyTd !== $notShow ? $item : ($input . $icon)) .
                "</td>"
                ?>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div style="margin-top: 20px;margin-bottom: 20px;">
    <button id="check" type="button" class="btn btn-info">Check</button>
</div>
