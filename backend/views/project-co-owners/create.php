<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectCoOwners */

$this->title = 'Create Project Co Owners';
$this->params['breadcrumbs'][] = ['label' => 'Project Co Owners', 'url' => ['index?id='.$project->project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>

.ui-autocomplete {
    height: 200px;
    overflow-y: scroll;
	}
</style>
<div class="project-co-owners-create">

    <h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'project' => $project,
        'users' => $users,
    ]) ?>

</div>
