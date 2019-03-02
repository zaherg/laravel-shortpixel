<?php

namespace Zaherg\ShortPixel;

use ShortPixel\Source;
use ShortPixel\Commander;
use ShortPixel\ClientException;
use ShortPixel\PersistException;
use ShortPixel\ShortPixel as MainClass;

class ShortPixel extends MainClass
{
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
        return (new Source())->fromUrls($urls);
    }
}
