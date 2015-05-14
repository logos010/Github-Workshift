<?php

/**
 * Description of ContactsUtil
 *
 * @author HAO
 */
class ContactsUtil {

    public static function get($model) {
        echo CHtml::link($model->title, array('//site/view2', 'cate' => 'danh-ba', 'subCate' => $model->term->alias, 'id' => $model->id, 'alias' => $model->alias));
        echo '<div style="padding: 3px 0">' . $model->denominations . '</div>';
        echo "<div style='padding-left: 20px'>";
        foreach ($model->fields as $value) {
            echo $value->value . '<br/>';
        }
        echo "</div>";
        return '';
    }

}

?>
