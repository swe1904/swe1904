<?php

namespace common\components\filesystem;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use trntv\filekit\filesystem\FilesystemBuilderInterface;
use yii\base\BaseObject;

/**
 * Class AwsS3v3FlysystemBuilder
 * @author Eugene Terentev <eugene@terentev.net>
 */
class AwsS3v3FlysystemBuilder extends BaseObject implements FilesystemBuilderInterface
{
    public $key;
    public $secret;
    public $region;
    public $version;
    public $endPoint;

    /**
     * @return mixed
     */
    public function build()
    {
        $client = new S3Client([
            'credentials' => [
                'key'    => $this->key,
                'secret' => $this->secret
            ],
            'region' => $this->region,
            'version' => 'latest',
        ]);

        $adapter = new AwsS3Adapter(
            $client, getenv('AWS_S3_BUCKET'),
            $prefix = '',
            $options = [
                'ACL' => 'public-read',
            ]
        );
        return new Filesystem($adapter);
    }
}