<?php

namespace Zaherg\ShortPixel;

use ShortPixel\Source;
use ShortPixel\Commander;
use ShortPixel\ClientException;
use ShortPixel\PersistException;
use ShortPixel\ShortPixel as MainClass;

class ShortPixel
{
    const MAX_ALLOWED_FILES_PER_CALL = 10;
    const MAX_ALLOWED_FILES_PER_WEB_CALL = 30;
    const CLIENT_MAX_BODY_SIZE = 48; // in MBytes.
    const MAX_RETRIES = 6;

    const LOSSY_EXIF_TAG = 'SPXLY';
    const LOSSLESS_EXIF_TAG = 'SPXLL';

    const RESIZE_OUTER = 1;
    const RESIZE_INNER = 3;

    /**
     * @param $key - the ShortPixel API Key
     */
    public function setKey($key): void
    {
        MainClass::setKey($key);
    }

    /**
     * @param $options - set the ShortPxiel options. Options defaults are the following:
     *  "lossy" => 1, // 1 - lossy, 0 - lossless
     * "keep_exif" => 0, // 1 - EXIF is preserved, 0 - EXIF is removed
     * "resize_width" => null, // in pixels. null means no resize
     * "resize_height" => null,
     * "cmyk2rgb" => 1,
     * "notify_me" => null, // should contain full URL of of notification script (notify.php)
     * "wait" => 30,
     * //local options
     * "total_wait" => 30,
     * "base_url" => null, // base url of the images - used to generate the path for toFile by extracting from original URL and using the remaining path as relative path to base_path
     * "base_path" => "/tmp", // base path for the saved files
     */
    public function setOptions($options)
    {
        return MainClass::setOptions($options);
    }

    /**
     * @param $name - option name
     *
     * @return mixed
     */
    public function opt($name)
    {
        return MainClass::opt($name);
    }

    /**
     * @param $paths
     * @param $basePath - common base path used to determine the subfolders that will be created in the destination
     * @param null $pending
     * @param bool $refresh
     *
     * @throws ClientException
     *
     * @return Commander - the class that handles the optimization commands
     *
     * @internal param $path - the file path on the local drive
     */
    public function fromFiles($paths, $basePath = null, $pending = null, $refresh = false): Commander
    {
        return (new Source())->fromFiles($paths, $basePath, $pending, $refresh);
    }

    /**
     * @param $path
     * @param $basePath - common base path used to determine the subfolders that will be created in the destination
     * @param null $pending
     * @param bool $refresh
     *
     * @throws ClientException
     *
     * @return Commander - the class that handles the optimization commands
     *
     * @internal param $path - the file path on the local drive
     */
    public function fromFile($path, $basePath = null, $pending = null, $refresh = false): Commander
    {
        return $this->fromFiles($path, $basePath, $pending, $refresh);
    }

    /**
     * returns the optimization counters of the folder and subfolders.
     *
     * @param $path - the file path on the local drive
     * @param bool  $recurse      - boolean - go into subfolders or not
     * @param bool  $fileList     - return the list of files with optimization status (only current folder, not subfolders)
     * @param array $exclude      - array of folder names that you want to exclude from the optimization
     * @param bool  $persistPath  - the path where to look for the metadata, if different from the $path
     * @param int   $recurseDepth - how many subfolders deep to go. Defaults to PHP_INT_MAX
     * @param bool  $retrySkipped - if true, all skipped files will be reset to pending with retries = 0
     *
     * @throws PersistException
     *
     * @return object|void (object)array('status', 'total', 'succeeded', 'pending', 'same', 'failed') | mixed
     */
    public function folderInfo($path, $recurse = true, $fileList = false, $exclude = [], $persistPath = false, $recurseDepth = PHP_INT_MAX, $retrySkipped = false)
    {
        return (new Source())
            ->folderInfo($path, $recurse, $fileList, $exclude, $persistPath, $recurseDepth, $retrySkipped);
    }

    /**
     * processes a chunk of MAX_ALLOWED files from the folder, based on the persisted information about which images are processed and which not. This information is offered by the Persister object.
     *
     * @param $path - the folder path on the local drive
     * @param int   $maxFiles         - maximum number of files to select from the folder
     * @param array $exclude          - exclude files based on regex patterns
     * @param bool  $persistFolder    - the path where to store the metadata, if different from the $path (usually the target path)
     * @param int   $maxTotalFileSize - max summed up file size in MB
     * @param int   $recurseDepth     - how many subfolders deep to go. Defaults to PHP_INT_MAX
     *
     * @throws ClientException
     * @throws PersistException
     *
     * @return Commander - the class that handles the optimization commands
     */
    public function fromFolder($path, $maxFiles = 0, $exclude = [], $persistFolder = false, $maxTotalFileSize = ShortPixel::CLIENT_MAX_BODY_SIZE, $recurseDepth = PHP_INT_MAX)
    {
        return (new Source())
            ->fromFolder($path, $maxFiles, $exclude, $persistFolder, $maxTotalFileSize, $recurseDepth);
    }

    /**
     * processes a chunk of MAX_ALLOWED URLs from a folder that is accessible via web at the $webPath location,
     * based on the persisted information about which images are processed and which not. This information is offered by the Persister object.
     *
     * @param $path - the folder path on the local drive
     * @param $webPath - the web URL of the folder
     * @param array $exclude       - exclude files based on regex patterns
     * @param bool  $persistFolder - the path where to store the metadata, if different from the $path (usually the target path)
     * @param int   $recurseDepth  - how many subfolders deep to go. Defaults to PHP_INT_MAX
     *
     * @throws ClientException
     * @throws PersistException
     *
     * @return Commander - the class that handles the optimization commands
     */
    public function fromWebFolder($path, $webPath, $exclude = [], $persistFolder = false, $recurseDepth = PHP_INT_MAX)
    {
        return (new Source())
            ->fromWebFolder($path, $webPath, $exclude, $persistFolder, $recurseDepth);
    }

    /**
     * @param $urls - the array of urls to be optimized
     *
     * @throws ClientException
     *
     * @return Commander - the class that handles the optimization commands
     */
    public function fromUrls($urls)
    {
        $source = new Source();
        return $source->fromUrls($urls);
    }

    /**
     * @throws \ShortPixel\AccountException
     *
     * @return \ShortPixel\Client singleton
     */
    public static function getClient()
    {
        return MainClass::getClient();
    }
}
