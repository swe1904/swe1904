<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ajay
 * Date: 28/1/15
 * Time: 3:08 PM
 * To change this template use File | Settings | File Templates.
 */

namespace backend\components;


use DateTime;
use Aws\S3\S3Client;
use backend\models\FileUpload;
use Aws\S3\Exception\S3Exception;
use WGenial\S3ObjectsStreamZip\S3ObjectsStreamZip;
use WGenial\S3ObjectsStreamZip\Exception\InvalidParamsException;
use Yii;

class Helper {
    /**
     * @param $date in format dd-mm-yyyy or mm/dd/yyyy or 'April 21, 2010'
     * @return string in format mm-dd-yyy
     */
    public static function firstDateOfMonth($date, $format = 'd-m-Y'){
        $firstDate = date('01-m-Y', strtotime($date));
        return date($format, strtotime($firstDate));
    }

    /**
     * @param $date in format dd-mm-yyyy or mm/dd/yyyy or 'April 21, 2010'
     * @return string in format mm-dd-yyy
     */
    public static function lastDateOfMonth($date, $format = 'd-m-Y'){
        $lastDate = date('t-m-Y', strtotime($date));
        return date($format, strtotime($lastDate));
    }

    public static function currentMonthWords(){
        $currentMonth = date('m');
        $dateObj   = DateTime::createFromFormat('!m', $currentMonth);
        return $dateObj->format('F');
    }

    public static function previousMonthWords(){
        $currentMonth = date('m')-1;
        $dateObj   = DateTime::createFromFormat('!m', $currentMonth);
        return $dateObj->format('F');
    }

    public static function previousMonthNumber(){
        return date('m', strtotime("-1 months"));
    }

    public static function monthWordFromNumber($monthNumber){
        $dateObj   = DateTime::createFromFormat('!m', $monthNumber);
        return $dateObj->format('F');
    }

    //source: https://stackoverflow.com/questions/4249432/export-to-csv-via-php#
    public static function downloadFileSetHeaders($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2018 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    //source: https://stackoverflow.com/questions/4249432/export-to-csv-via-php#
    public static function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    //initialises s3Client for use in other functions
    private static function getS3Client() {
        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => getenv('AWS_REGION'),
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY'),
                'secret' => getenv('AWS_SECRET_KEY')
            ]
        ]);
        return $s3Client;
    }

    // public static function getS3Object($s3FileKey) {
    //     $s3Client = self::getS3Client();
    //     try {
    //         $object = $s3Client->getObject(array(
    //             'Bucket' => $_ENV['AWS_S3_BUCKET'],
    //             'Key'    => $s3FileKey
    //         ));
    //         return $object;
    //     } catch (Aws\S3\Exception\S3Exception $e) {
    //         Yii::$app->session->setFlash('error', 'File could not be downloaded, please try again.');
    //         return null;
    //     }
    // }


    public static function getS3Object($s3FileKey) {
        // Check if the bucket environment variable is set correctly
        if (empty($_ENV['AWS_S3_BUCKET']) || $_ENV['AWS_S3_BUCKET'] === false) {
            Yii::$app->session->setFlash('error', 'AWS S3 Bucket is not set or invalid.');
            return null;
        }
    
        // Initialize the S3 client
        $s3Client = self::getS3Client();
    
        try {
            // Retrieve the object from S3
            $object = $s3Client->getObject(array(
                'Bucket' => $_ENV['AWS_S3_BUCKET'], // Ensure this is a valid string
                'Key'    => $s3FileKey
            ));
            return $object;
        } catch (\Aws\S3\Exception\S3Exception $e) {
            // Handle the error if the file cannot be downloaded
            Yii::$app->session->setFlash('error', 'File could not be downloaded, please try again.');
            return null;
        }
    }
    

    //gets S3 object with key and saves it on $savePath
    public static function downloadObjectFromS3($s3FileKey, $savePath) {
        $s3Client = self::getS3Client();
        try {
            if ($s3Client->doesObjectExist(getenv('AWS_S3_BUCKET'), $s3FileKey)) {
                $objects = $s3Client->getObject([
                    'Bucket' => $_ENV['AWS_S3_BUCKET'],
                    'Key' => $s3FileKey,
                    'SaveAs' => $savePath
                ]);
            }
        } catch (\Aws\S3\Exception\S3Exception $e) {
            Yii::$app->session->setFlash('error', 'File could not be downloaded, please try again.');
            return null;
        }
    }

    //uploads single object to S3
    public static function uploadToS3($bucket, $key, $filePath, $errorMessage) {
        $s3Client = self::getS3Client();
        try {
            if (file_exists($filePath)) {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key'    => $key,
                    'Body'   => fopen($filePath, 'r'),
                    'ACL' => 'public-read',
                ]);
                return $result->get('ObjectURL');
            }
        } catch (\Aws\S3\Exception\S3Exception $e) {
            Yii::$app->session->setFlash('error', $errorMessage);
            return false;
        }
    }

    //deletes single object from S3 and returns negation of file_exists
    //so if file is deleted, it returns true 
    public static function deleteFromS3($bucket, $key) {
        $s3Client = self::getS3Client();
        $result = $s3Client->deleteObject([
            'Bucket' => $bucket,
            'Key'    => $key
        ]);
        return !$s3Client->doesObjectExist($bucket, $key);
    }

    //deletes entire directory from s3 using prefix
    public static function deleteFolderFromS3($bucket, $prefix) {
        $s3Client = self::getS3Client();
        $s3Client->deleteMatchingObjects($bucket, $prefix);
        return !$s3Client->doesObjectExist($bucket, $prefix);
    }

    public static function sendEmailViaSes($fromEmail, $toEmail, $cc, $subject, $htmlBody, $textBody, $filePath, $fileName)
    {
        Yii::$app->set('mailer', Yii::$app->get('sesMailer'));

        $message = Yii::$app->mailer->compose();
        if($fromEmail != null)
            $message->setFrom($fromEmail);
        if($toEmail != null)
            $message->setTo($toEmail);
        if($cc != null)
            $message->setCc($cc);
        if($subject != null)
            $message->setSubject($subject);
        if($htmlBody != null)
            $message->setHtmlBody($htmlBody);
        if($textBody != null)
            $message->setTextBody($textBody);
        if($filePath != null && $fileName != null)    
            $message->attach($filePath, ['fileName'=>$fileName]);

        $message->send();
            
    }
}
