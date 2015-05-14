<!-- Create a new project -->
    <p id="btn-create" class="box">
        <a href="<?php echo Yii::app()->createUrl('/'.$module.'/'.$controller.'/create'); ?>">
            <span>Create <?php echo $controller; ?></span>
        </a>
    </p>