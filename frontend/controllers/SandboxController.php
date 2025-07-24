<?php
namespace frontend\controllers;

use frontend\migration\DatabaseMigration;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SandboxController extends Controller
{
    /**
     * @var \yii\gii\Module
     */
    public $module;
    /**
     * @var \yii\gii\Generator
     */
    public $generator;

   public function actionDemo(){
      /*$d=new DatabaseMigration();
      $d->up();*/
       echo "<pre>";
       print_r($this->module);
       echo "</pre>";
       die("die here");
   }
    /**
     * Loads the generator with the specified ID.
     * @param string $id the ID of the generator to be loaded.
     * @return \yii\gii\Generator the loaded generator
     * @throws NotFoundHttpException
     */
    protected function loadGenerator($id)
    {
        if (isset($this->module->generators[$id])) {
            $this->generator = $this->module->generators[$id];
            $this->generator->loadStickyAttributes();
            $this->generator->load($_POST);

            return $this->generator;
        } else {
            throw new NotFoundHttpException("Code generator not found: $id");
        }
    }
}
