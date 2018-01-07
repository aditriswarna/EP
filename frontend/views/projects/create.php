<?php

use yii\helpers\Html;
use frontend\models\ProjectCategory;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Create New Project';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>
<div class="projects-create ">
	<?php 

//		echo Html::dropDownList('listname', $select, $projectCategories);
	
	?>
    <?php echo $this->render('_form', [
        'model' => $model,
        'projectParticipationData' => $projectParticipationData,
        'projectCategories' => $projectCategories,
        'projectTypes' => $projectTypes,
        'userData'=>$userData,
    ]) ?>

</div>
