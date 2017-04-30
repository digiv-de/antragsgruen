<?php

use app\components\HTMLTools;
use app\components\UrlHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var \app\models\forms\AntragsgruenInitDb $form
 * @var string $delInstallFileCmd
 * @var bool $installFileDeletable
 * @var bool $editable
 * @var string $makeEditabeCommand
 */


$controller  = $this->context;
$this->title = \yii::t('manager', 'title_install');

/** @var \app\controllers\admin\IndexController $controller */
$controller = $this->context;
$layout     = $controller->layoutParams;
$layout->loadFuelux();
$layout->robotsNoindex = true;
$layout->addCSS('css/manager.css');

echo '<h1>' . \yii::t('manager', 'title_install') . '</h1>';
echo Html::beginForm('', 'post', ['class' => 'antragsgruenInitForm form-horizontal fuelux']);

echo '<div class="content">';
echo $controller->showErrors();

if (!$editable) {
    echo '<div class="alert alert-danger" role="alert">';
    echo \Yii::t('manager', 'err_settings_ro');
    echo '<br><br><pre>';
    echo Html::encode($makeEditabeCommand);
    echo '</pre>';
    echo '</div>';
}


if ($form->isConfigured()) {
    try {
        $form->verifyDBConnection();

        echo '<div class="alert alert-success" role="alert">
        <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
        <span class="sr-only">Success:</span>';
        echo \yii::t('manager', 'config_finished');
        echo '</div>';

        if (!$form->tablesAreCreated()) {
            echo '<div class="alert alert-info" role="alert">
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span class="sr-only">Hint:</span>';

            echo \Yii::t('manager', 'config_create_tables');
            echo '</div>';
        }
    } catch (\Exception $e) {
        echo '<div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                ' . nl2br(Html::encode($e->getMessage())) . '
            </div>';
    }
} else {
    echo '<div class="alert alert-info" role="alert">
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span class="sr-only">Welcome:</span>
        ' . \yii::t('manager', 'welcome') . '
    </div>';
}

echo '</div>';

echo Html::endForm();


echo Html::beginForm('', 'post', [
    'class'                    => 'antragsgruenInitForm form-horizontal fuelux',
    'data-antragsgruen-widget' => 'manager/InitDb',
]);

?>
    <h2 class="green"><?= \Yii::t('manager', 'config_lang') ?></h2>
    <div class="content">
        <div class="form-group language">
            <label class="col-sm-4 control-label" for="language"><?= \Yii::t('manager', 'config_lang') ?>:</label>
            <div class="col-sm-8"><?php
                echo HTMLTools::fueluxSelectbox(
                    'language',
                    \app\components\MessageSource::getBaseLanguages(),
                    $form->language,
                    ['id' => 'language']
                );
                ?></div>
        </div>
    </div>

    <h2 class="green"><?= \Yii::t('manager', 'config_db') ?></h2>
    <div class="content">

        <div class="form-group sqlType">
            <label class="col-sm-4 control-label" for="sqlType"><?= \Yii::t('manager', 'config_db_type') ?>:</label>
            <div class="col-sm-8"><?php
                echo HTMLTools::fueluxSelectbox(
                    'sqlType',
                    [
                        'mysql' => 'MySQL / MariaDB',
                    ],
                    $form->sqlType,
                    ['id' => 'sqlType']
                );
                ?></div>
        </div>

        <div class="form-group sqlOption mysqlOption">
            <label class="col-sm-4 control-label" for="sqlHost"><?= \Yii::t('manager', 'config_db_host') ?>:</label>
            <div class="col-sm-8">
                <input type="text" name="sqlHost" placeholder="localhost"
                       value="<?= Html::encode($form->sqlHost) ?>" class="form-control" id="sqlHost">
            </div>
        </div>

        <div class="form-group sqlOption mysqlOption">
            <label class="col-sm-4 control-label" for="sqlUsername"><?= \Yii::t('manager', 'config_db_username') ?>
                :</label>
            <div class="col-sm-8">
                <input type="text" name="sqlUsername"
                       value="<?= Html::encode($form->sqlUsername) ?>" class="form-control" id="sqlUsername">
            </div>
        </div>

        <div class="form-group sqlOption mysqlOption">
            <label class="col-sm-4 control-label" for="sqlPassword"><?= \Yii::t('manager', 'config_db_password') ?>
                :</label>
            <div class="col-sm-8">
                <input type="password" name="sqlPassword" value="" class="form-control" id="sqlPassword"<?php
                if ($form->sqlPassword != '') {
                    echo ' placeholder="' . \Yii::t('manager', 'config_db_password_unch') . '"';
                }
                ?>>
                <label style="font-weight: normal; font-size: 0.9em;">
                    <input type="checkbox" name="sqlPasswordNone" value="" id="sqlPasswordNone">
                    <?= \Yii::t('manager', 'config_db_no_password') ?>
                </label>
            </div>
        </div>

        <div class="form-group sqlOption mysqlOption">
            <label class="col-sm-4 control-label" for="sqlDB"><?= \Yii::t('manager', 'config_db_dbname') ?>:</label>
            <div class="col-sm-8">
                <input type="text" name="sqlDB"
                       value="<?= Html::encode($form->sqlDB) ?>" class="form-control" id="sqlDB">
            </div>
        </div>

        <div class="testDB">
            <button type="button" name="testDB" class="btn btn-default testDBcaller"
                    data-url="<?= Html::encode(UrlHelper::createUrl('manager/antragsgrueninitdbtest')) ?>">
                <?= \Yii::t('manager', 'config_db_test') ?></button>
            <div class="testDBRpending hidden"><?= \Yii::t('manager', 'config_db_testing') ?>...</div>
            <div class="testDBerror hidden">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="result"></span>
            </div>
            <div class="testDBsuccess hidden">
                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                <?= \Yii::t('manager', 'config_db_test_succ') ?>
            </div>
        </div>


        <div class="createTables"><label>
                <?= Html::checkbox('sqlCreateTables', $form->sqlCreateTables, ['id' => 'sqlCreateTables']) ?>
                <?= \Yii::t('manager', 'config_db_create') ?>
            </label>
            <div class="alreadyCreatedHint"><?= \Yii::t('manager', 'config_db_create_hint') ?></div>
        </div>
    </div>

    <h2 class="green"><?= \Yii::t('manager', 'config_admin') ?></h2>
    <div class="content">

        <?php
        if ($form->hasAdminAccount()) {
            echo '<strong>' . \Yii::t('manager', 'config_admin_already') . '</strong><br>';
            echo \Yii::t('manager', 'config_admin_alreadyh');
        } else {
            echo '<div class="form-group">
    <label class="col-sm-4 control-label" for="adminUsername">' . \Yii::t('manager', 'config_admin_email') . ':</label>
    <div class="col-sm-8">
        <input type="email" required name="adminUsername"
        value="' . Html::encode($form->adminUsername) . '" class="form-control" id="adminUsername">
    </div>
</div>';

            echo '<div class="form-group">
    <label class="col-sm-4 control-label" for="adminPassword">' . \Yii::t('manager', 'config_admin_pwd') . ':</label>
    <div class="col-sm-8">
        <input type="password" required name="adminPassword" value="" class="form-control" id="adminPassword">
    </div>
</div>';
        }
        ?>
    </div>


    <div class="content saveholder">
        <button type="submit" name="saveDb" class="btn btn-primary"><?= \Yii::t('base', 'save') ?></button>
    </div>

<?php
echo Html::endForm();
