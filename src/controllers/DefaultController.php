<?php

namespace wavedesign\crafthrcommencementimportutility\controllers;

use wavedesign\crafthrcommencementimportutility\Plugin;

use Craft;
use craft\web\Controller;
use Craft\web\Request;

use craft\base\Element;
use craft\elements\Asset;
use craft\errors\UploadFailedException;
use craft\fields\Assets as AssetsField;
use craft\helpers\App;
use craft\helpers\ArrayHelper;
use craft\helpers\Assets;
use craft\helpers\Db;
use craft\helpers\Image;
use craft\helpers\StringHelper;
use craft\helpers\UrlHelper;
use craft\i18n\Formatter;
use craft\image\Raster;
use craft\models\VolumeFolder;
use craft\web\UploadedFile;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\base\UserException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use ZipArchive;
/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *

 *
 * @author    WAVE Design
 * @package   HRCraftPlugin
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = ['index', 'do-something', 'simple','file'];

    // Public Methods
    // =========================================================================

    //Convert Excel file to JSON

    public function actionConvert(string $path) {
        //$path = Craft::$app->request->getBodyParam('filepath');
        
        $services = Plugin::getInstance()->CraftService;
        $result = $services->excelToArray($path);

        return $result;
    }

    //Sort the data from the JSON 
    
    public function actionSort() {
        $data = Craft::$app->request->getBodyParam('data');
        $title = Craft::$app->request->getBodyParam('title');
        $mode = Craft::$app->request->getBodyParam('datamode');
        $services = Plugin::getInstance()->CraftService;

        if ($mode == "honor-roll") {
            $htmloutput = $services->sortHonorRoll($data,$title,false);
            $htmloutput_readable =  htmlspecialchars($htmloutput);
            $press_release = $services->sortHonorRoll($data,$title,true);

            return json_encode([$htmloutput_readable,$htmloutput,$press_release]);
        } else {
            $htmloutput = $services->sortByDegree($data,false);
            $htmloutput_readable =  htmlspecialchars($htmloutput);
            $press_release = $services->sortByDegree($data,true);

            return json_encode([$htmloutput_readable,$htmloutput,$press_release]);
        }
    }

    //Upload an Excel file to the server

    public function actionUpload() {
        $user = Craft::$app->request->getBodyParam('userId');
        $file = Craft::$app->request->getBodyParam('selectedFile');
        $services = RegistrarImporterCraft::getInstance()->CraftService;
        $result = $services->uploadNewAsset($file,$folder);
    }
    
    
}

