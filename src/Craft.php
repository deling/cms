<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

use craft\behaviors\ContentBehavior;
use craft\behaviors\ElementQueryBehavior;
use craft\db\Query;
use craft\helpers\FileHelper;
use GuzzleHttp\Client;
use yii\base\ExitException;
use yii\db\Expression;
use yii\helpers\VarDumper;
use yii\web\Request;

/**
 * Craft is helper class serving common Craft and Yii framework functionality.
 * It encapsulates [[Yii]] and ultimately [[yii\BaseYii]], which provides the actual implementation.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class Craft extends Yii
{
    // Constants
    // =========================================================================

    // Edition constants
    const Solo = 0;
    const Pro = 1;

    /**
     * @deprecated in 3.0.0. Use [[Solo]] instead.
     */
    const Personal = 0;
    /**
     * @deprecated in 3.0.0. Use [[Pro]] instead.
     */
    const Client = 1;

    // Properties
    // =========================================================================

    /**
     * @var \craft\web\Application|\craft\console\Application The application instance.
     */
    public static $app;

    /**
     * @var array The default cookie configuration.
     */
    private static $_baseCookieConfig;

    // Public Methods
    // =========================================================================

    /**
     * Displays a variable.
     *
     * @param mixed $var The variable to be dumped.
     * @param int $depth The maximum depth that the dumper should go into the variable. Defaults to 10.
     * @param bool $highlight Whether the result should be syntax-highlighted. Defaults to true.
     */
    public static function dump($var, int $depth = 10, bool $highlight = true)
    {
        VarDumper::dump($var, $depth, $highlight);
    }

    /**
     * Displays a variable and ends the request. (“Dump and die”)
     *
     * @param mixed $var The variable to be dumped.
     * @param int $depth The maximum depth that the dumper should go into the variable. Defaults to 10.
     * @param bool $highlight Whether the result should be syntax-highlighted. Defaults to true.
     * @throws ExitException if the application is in testing mode
     */
    public static function dd($var, int $depth = 10, bool $highlight = true)
    {
        VarDumper::dump($var, $depth, $highlight);
        exit();
    }

    /**
     * Generates and returns a cookie config.
     *
     * @param array $config Any config options that should be included in the config.
     * @param Request|null $request The request object
     * @return array The cookie config array.
     */
    public static function cookieConfig(array $config = [], Request $request = null): array
    {
        if (self::$_baseCookieConfig === null) {
            $generalConfig = static::$app->getConfig()->getGeneral();

            $defaultCookieDomain = $generalConfig->defaultCookieDomain;
            $useSecureCookies = $generalConfig->useSecureCookies;

            if ($useSecureCookies === 'auto') {
                if ($request === null) {
                    $request = static::$app->getRequest();
                }

                $useSecureCookies = $request->getIsSecureConnection();
            }

            self::$_baseCookieConfig = [
                'domain' => $defaultCookieDomain,
                'secure' => $useSecureCookies,
                'httpOnly' => true
            ];
        }

        return array_merge(self::$_baseCookieConfig, $config);
    }

    // Service Getters
    // -------------------------------------------------------------------------

    /**
     * Returns the API service.
     *
     * @return \craft\services\Api The API service
     */
    public static function Api()
    {
        return self::$app->getApi();
    }

    /**
     * Returns the assets service.
     *
     * @return \craft\services\Assets The assets service
     */
    public static function Assets()
    {
        return self::$app->getAssets();
    }

    /**
     * Returns the asset indexing service.
     *
     * @return \craft\services\AssetIndexer The asset indexing service
     */
    public static function AssetIndexer()
    {
        return self::$app->getAssetIndexer();
    }

    /**
     * Returns the asset transforms service.
     *
     * @return \craft\services\AssetTransforms The asset transforms service
     */
    public static function AssetTransforms()
    {
        return self::$app->getAssetTransforms();
    }

    /**
     * Returns the categories service.
     *
     * @return \craft\services\Categories The categories service
     */
    public static function Categories()
    {
        return self::$app->getCategories();
    }

    /**
     * Returns the Composer service.
     *
     * @return \craft\services\Composer The Composer service
     */
    public static function Composer()
    {
        return self::$app->getComposer();
    }

    /**
     * Returns the config service.
     *
     * @return \craft\services\Config The config service
     */
    public static function Config()
    {
        return self::$app->getConfig();
    }

    /**
     * Returns the content service.
     *
     * @return \craft\services\Content The content service
     */
    public static function Content()
    {
        return self::$app->getContent();
    }

    /**
     * Returns the content migration manager.
     *
     * @return MigrationManager The content migration manager
     */
    public static function ContentMigrator(): MigrationManager
    {
        return self::$app->getContentMigrator();
    }

    /**
     * Returns the dashboard service.
     *
     * @return \craft\services\Dashboard The dashboard service
     */
    public static function Dashboard()
    {
        return self::$app->getDashboard();
    }

    /**
     * Returns the deprecator service.
     *
     * @return \craft\services\Deprecator The deprecator service
     */
    public static function Deprecator()
    {
        return self::$app->getDeprecator();
    }

    /**
     * Returns the element indexes service.
     *
     * @return \craft\services\ElementIndexes The element indexes service
     */
    public static function ElementIndexes()
    {
        return self::$app->getElementIndexes();
    }

    /**
     * Returns the elements service.
     *
     * @return \craft\services\Elements The elements service
     */
    public static function Elements()
    {
        return self::$app->getElements();
    }

    /**
     * Returns the system email messages service.
     *
     * @return \craft\services\SystemMessages The system email messages service
     */
    public static function SystemMessages()
    {
        return self::$app->getSystemMessages();
    }

    /**
     * Returns the entries service.
     *
     * @return \craft\services\Entries The entries service
     */
    public static function Entries()
    {
        return self::$app->getEntries();
    }

    /**
     * Returns the entry revisions service.
     *
     * @return \craft\services\EntryRevisions The entry revisions service
     */
    public static function EntryRevisions()
    {
        return self::$app->getEntryRevisions();
    }

    /**
     * Returns the feeds service.
     *
     * @return \craft\feeds\Feeds The feeds service
     */
    public static function Feeds()
    {
        return self::$app->getFeeds();
    }

    /**
     * Returns the fields service.
     *
     * @return \craft\services\Fields The fields service
     */
    public static function Fields()
    {
        return self::$app->getFields();
    }

    /**
     * Returns the globals service.
     *
     * @return \craft\services\Globals The globals service
     */
    public static function Globals()
    {
        return self::$app->getGlobals();
    }

    /**
     * Returns the images service.
     *
     * @return \craft\services\Images The images service
     */
    public static function Images()
    {
        return self::$app->getImages();
    }

    /**
     * Returns a Locale object for the target language.
     *
     * @return Locale The Locale object for the target language
     */
    public static function Locale(): Locale
    {
        return self::$app->getLocale();
    }

    /**
     * Returns the current mailer.
     *
     * @return \craft\mail\Mailer The mailer component
     */
    public static function Mailer()
    {
        return self::$app->getMailer();
    }

    /**
     * Returns the matrix service.
     *
     * @return \craft\services\Matrix The matrix service
     */
    public static function Matrix()
    {
        return self::$app->getMatrix();
    }

    /**
     * Returns the application’s migration manager.
     *
     * @return MigrationManager The application’s migration manager
     */
    public static function Migrator(): MigrationManager
    {
        return self::$app->getMigrator();
    }

    /**
     * Returns the application’s mutex service.
     *
     * @return Mutex The application’s mutex service
     */
    public static function Mutex(): Mutex
    {
        return self::$app->getMutex();
    }

    /**
     * Returns the path service.
     *
     * @return \craft\services\Path The path service
     */
    public static function Path()
    {
        return self::$app->getPath();
    }

    /**
     * Returns the plugins service.
     *
     * @return \craft\services\Plugins The plugins service
     */
    public static function Plugins()
    {
        return self::$app->getPlugins();
    }

    /**
     * Returns the plugin store service.
     *
     * @return \craft\services\PluginStore The plugin store service
     */
    public static function PluginStore()
    {
        return self::$app->getPluginStore();
    }

    /**
     * Returns the queue service.
     *
     * @return Queue|QueueInterface The queue service
     */
    public static function Queue()
    {
        return self::$app->getQueue();
    }

    /**
     * Returns the relations service.
     *
     * @return \craft\services\Relations The relations service
     */
    public static function Relations()
    {
        return self::$app->getRelations();
    }

    /**
     * Returns the routes service.
     *
     * @return \craft\services\Routes The routes service
     */
    public static function Routes()
    {
        return self::$app->getRoutes();
    }

    /**
     * Returns the search service.
     *
     * @return \craft\services\Search The search service
     */
    public static function Search()
    {
        return self::$app->getSearch();
    }

    /**
     * Returns the sections service.
     *
     * @return \craft\services\Sections The sections service
     */
    public static function Sections()
    {
        return self::$app->getSections();
    }

    /**
     * Returns the sites service.
     *
     * @return \craft\services\Sites The sites service
     */
    public static function Sites()
    {
        return self::$app->getSites();
    }

    /**
     * Returns the structures service.
     *
     * @return \craft\services\Structures The structures service
     */
    public static function Structures()
    {
        return self::$app->getStructures();
    }

    /**
     * Returns the system settings service.
     *
     * @return \craft\services\SystemSettings The system settings service
     */
    public static function SystemSettings()
    {
        return self::$app->getSystemSettings();
    }

    /**
     * Returns the tags service.
     *
     * @return \craft\services\Tags The tags service
     */
    public static function Tags()
    {
        return self::$app->getTags();
    }

    /**
     * Returns the template cache service.
     *
     * @return \craft\services\TemplateCaches The template caches service
     */
    public static function TemplateCaches()
    {
        return self::$app->getTemplateCaches();
    }

    /**
     * Returns the tokens service.
     *
     * @return \craft\services\Tokens The tokens service
     */
    public static function Tokens()
    {
        return self::$app->getTokens();
    }

    /**
     * Returns the updates service.
     *
     * @return \craft\services\Updates The updates service
     */
    public static function Updates()
    {
        return self::$app->getUpdates();
    }

    /**
     * Returns the user groups service.
     *
     * @return \craft\services\UserGroups The user groups service
     */
    public static function UserGroups()
    {
        return self::$app->getUserGroups();
    }

    /**
     * Returns the user permissions service.
     *
     * @return \craft\services\UserPermissions The user permissions service
     */
    public static function UserPermissions()
    {
        return self::$app->getUserPermissions();
    }

    /**
     * Returns the users service.
     *
     * @return \craft\services\Users The users service
     */
    public static function Users()
    {
        return self::$app->getUsers();
    }

    /**
     * Returns the utilities service.
     *
     * @return \craft\services\Utilities The utilities service
     */
    public static function Utilities()
    {
        return self::$app->getUtilities();
    }

    /**
     * Returns the volumes service.
     *
     * @return \craft\services\Volumes The volumes service
     */
    public static function Volumes()
    {
        return self::$app->getVolumes();
    }

    /**
     * Class autoloader.
     *
     * @param string $className
     */
    public static function autoload($className)
    {
        if ($className !== ContentBehavior::class && $className !== ElementQueryBehavior::class) {
            return;
        }

        $storedFieldVersion = static::$app->getInfo()->fieldVersion;
        $compiledClassesPath = static::$app->getPath()->getCompiledClassesPath();

        $contentBehaviorFile = $compiledClassesPath.DIRECTORY_SEPARATOR.'ContentBehavior.php';
        $elementQueryBehaviorFile = $compiledClassesPath.DIRECTORY_SEPARATOR.'ElementQueryBehavior.php';

        $isContentBehaviorFileValid = self::_loadFieldAttributesFile($contentBehaviorFile, $storedFieldVersion);
        $isElementQueryBehaviorFileValid = self::_loadFieldAttributesFile($elementQueryBehaviorFile, $storedFieldVersion);

        if ($isContentBehaviorFileValid && $isElementQueryBehaviorFileValid) {
            return;
        }

        if (self::$app->getIsInstalled()) {
            // Properties are case-sensitive, so get all the binary-unique field handles
            if (self::$app->getDb()->getIsMysql()) {
                $column = new Expression('binary [[handle]] as [[handle]]');
            } else {
                $column = 'handle';
            }

            $fieldHandles = (new Query())
                ->distinct(true)
                ->from(['{{%fields}}'])
                ->select([$column])
                ->column();
        } else {
            $fieldHandles = [];
        }

        if (!$isContentBehaviorFileValid) {
            $handles = [];
            $properties = [];

            foreach ($fieldHandles as $handle) {
                $handles[] = <<<EOD
        '{$handle}' => true,
EOD;

                $properties[] = <<<EOD
    /**
     * @var mixed Value for field with the handle “{$handle}”.
     */
    public \${$handle};
EOD;
            }

            self::_writeFieldAttributesFile(
                static::$app->getBasePath().DIRECTORY_SEPARATOR.'behaviors'.DIRECTORY_SEPARATOR.'ContentBehavior.php.template',
                ['{VERSION}', '/* HANDLES */', '/* PROPERTIES */'],
                [$storedFieldVersion, implode("\n", $handles), implode("\n\n", $properties)],
                $contentBehaviorFile
            );
        }

        if (!$isElementQueryBehaviorFileValid) {
            $methods = [];

            foreach ($fieldHandles as $handle) {
                $methods[] = <<<EOD
 * @method self {$handle}(mixed \$value) Sets the [[{$handle}]] property
EOD;
            }

            self::_writeFieldAttributesFile(
                static::$app->getBasePath().DIRECTORY_SEPARATOR.'behaviors'.DIRECTORY_SEPARATOR.'ElementQueryBehavior.php.template',
                ['{VERSION}', '{METHOD_DOCS}'],
                [$storedFieldVersion, implode("\n", $methods)],
                $elementQueryBehaviorFile
            );
        }
    }

    /**
     * Creates a Guzzle client configured with the given array merged with any default values in config/guzzle.php.
     *
     * @param array $config Guzzle client config settings
     * @return Client
     */
    public static function createGuzzleClient(array $config = []): Client
    {
        // Set the Craft header by default.
        $defaultConfig = [
            'headers' => [
                'User-Agent' => 'Craft/'.self::$app->getVersion().' '.\GuzzleHttp\default_user_agent()
            ],
        ];

        // Grab the config from config/guzzle.php that is used on every Guzzle request.
        $guzzleConfig = self::$app->getConfig()->getConfigFromFile('guzzle');

        // Merge default into guzzle config.
        $guzzleConfig = array_replace_recursive($guzzleConfig, $defaultConfig);

        // Maybe they want to set some config options specifically for this request.
        $guzzleConfig = array_replace_recursive($guzzleConfig, $config);

        return new Client($guzzleConfig);
    }

    /**
     * Loads a field attribute file, if it’s valid.
     *
     * @param string $path
     * @param string $storedFieldVersion
     * @return bool
     */
    private static function _loadFieldAttributesFile(string $path, string $storedFieldVersion): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        // Make sure it's up-to-date
        $f = fopen($path, 'rb');
        $line = fgets($f);
        fclose($f);

        if (strpos($line, "// v{$storedFieldVersion}") === false) {
            return false;
        }

        include $path;
        return true;
    }

    /**
     * Writes a field attributes file.
     *
     * @param string $templatePath
     * @param string[] $search
     * @param string[] $replace
     * @param string $destinationPath
     */
    private static function _writeFieldAttributesFile(string $templatePath, array $search, array $replace, string $destinationPath)
    {
        $fileContents = file_get_contents($templatePath);
        $fileContents = str_replace($search, $replace, $fileContents);
        FileHelper::writeToFile($destinationPath, $fileContents);

        // Invalidate opcache
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($destinationPath, true);
        }

        include $destinationPath;
    }
}

spl_autoload_register([Craft::class, 'autoload'], true, true);
