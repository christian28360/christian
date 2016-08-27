<?php

namespace CHRIST\Common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;
use Knp\Provider\ConsoleServiceProvider;
use CHRIST\Common\Kernel\ConfigLoader;
use CHRIST\Common\Kernel\ConfigManager;
use CHRIST\Common\Kernel\ModuleFinder;
use CHRIST\Common\Service\NTLMAuthentification;
use CHRIST\Common\Kernel\ManagerRegistry;

/**
 * Description of bootstrap
 *
 * @author glr735
 */
class Bootstrap {

    private $app;

    /**
     * Constructor 
     * 
     * @param string $environment ex: (LOCAL, PROD)
     * @throws \Exception
     */
    public function __construct($environment, $debug = false, $loader = null) {
        if (empty($environment)) {
            throw new \Exception('Vous devez spécifier un environment à utiliser (local ou prod) dans index[...].php');
        }

        if (!is_null($loader)) {
            // vu sur : http://stackoverflow.com/questions/14518949/symfony2-change-apc-to-xcache
            /* New */
            require __DIR__ . '/../vendor/symfony/class-loader/Symfony/Component/ClassLoader/XcacheClassLoader.php';
            $loader = new \Symfony\Component\ClassLoader\XcacheClassLoader('autoloader.', $loader);
            /* old 
              require __DIR__ . '/../vendor/symfony/class-loader/Symfony/Component/ClassLoader/ApcClassLoader.php';
              $apcLoader = new \Symfony\Component\ClassLoader\ApcClassLoader('autoloader.', $loader);
             */
            $apcLoader->register();
            $loader->unregister();
        }

        // NTLM authentification
        $ntlmInfos = NTLMAuthentification::getNtlmInfos();

        $this->app = $app = new Application();
        $this->app['environment'] = $environment;
        $this->app['debug'] = $debug;

        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $this->app['locale'] = 'fr';
        $this->app['session.default_locale'] = $this->app['locale'];

        $this->app['dtc.current.module'] = null;

        // display errors
        if ($this->app['debug'] === true) {

            error_reporting(E_ALL | E_STRICT);
            ini_set('display_errors', 1);
            ini_set('log_errors', 1);
        }


        $this->app['dtc.time.start'] = new \DateTime();
        $this->app['dtc.time.trace'] = $this->app->share(function () use ($app) {
            $interval = $app['dtc.time.start']->diff(new \DateTime());
            exit();
        });

        $this->app['dtc.has.apc'] = $this->app->share(function () use ($app) {
            return function_exists('apc_store') && ini_get('apc.enabled');
        });

        // User Ntlm 
        $this->app['dtc.user.ntlm'] = $this->app->share(function () use ($app, $ntlmInfos) {
            return new NTLMAuthentification($app, $ntlmInfos);
        });

        Kernel\SingleApp::init($this->app);

        // Register moduleFinder
        $this->app['dtc.modules'] = $this->app->share(function ($app) {
            return new ModuleFinder($app['environment']);
        });

        // Register configManager
        $this->app['dtc.config.manager'] = $this->app->share(function ($app) {
            return new ConfigManager();
        });

        // Load global and modules configuration
        $this->registerRoute();

        // Load configuration
        $loader = new ConfigLoader($this->app);
        $loader->load('config');

        // Register configuration
        $this->registerProvider();
        $this->clearCache();

        \Symfony\Component\HttpFoundation\Request::enableHttpMethodParameterOverride();

        $this->app->before(function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {

            // Find current module called
            $nameSpace = explode('\\', $request->attributes->get('_controller'));

            if (isset($nameSpace[1]) && isset($nameSpace[2]) && $nameSpace[1] == 'Modules') {

                $app['dtc.current.module'] = $nameSpace[2];

                $registerClass = '\\' . $nameSpace[0] . '\\' . $nameSpace[1] . '\\' . $app['dtc.current.module'] . '\\' . $app['dtc.current.module'] . 'Register';

                if (class_exists($registerClass)) {
                    $app->register(new $registerClass());
                }
            }
        });

        $this->app->error(function (\Exception $e, $code) use ($app) {

            // commented for testing purposes
            if ($app['debug']) {
                return;
            }

            if ($code == 403) {
                return new Response($app['twig']->render('app/Resources/views/errors/403.html.twig', array('message' => $e->getMessage())), 403);
            }

            if ($code == 404) {
                return new Response($app['twig']->render('app/Resources/views/errors/404.html.twig', array('message' => $e->getMessage())), 404);
            }

            return new Response($app['twig']->render('app/Resources/views/errors/500.html.twig', array('message' => $e->getMessage())), 500);
        });
    }

    public function run() {
        $this->app->run();
    }

    public function getApp() {
        return $this->app;
    }

    /**
     * Mount all route of application
     */
    private function registerRoute() {

        $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/Resources/config'));

        $collection = $loader->load('routes.yml');

        if (!empty($this->app['environment'])) {
            try {
                $c = $loader->load('routes_' . $this->app['environment'] . '.yml');
                $collection->addCollection($c);
            } catch (\InvalidArgumentException $ex) {
                
            }
        }

        if (!$this->app['dtc.has.apc'] || ($collection = unserialize(apc_fetch('PortailSI_routes'))) === false || $collection == null) {

            $loader = new YamlFileLoader(new FileLocator(__DIR__ . '/Resources/config'));

            $collection = $loader->load('routes.yml');

            if (!empty($this->app['environment'])) {
                try {
                    $c = $loader->load('routes_' . $this->app['environment'] . '.yml');

                    $collection->addCollection($c);
                } catch (\InvalidArgumentException $ex) {
                    
                }
            }

            if ($this->app['dtc.has.apc'])
                apc_store('PortailSI_routes', serialize($collection));
        }

        $this->app['routes']->addCollection($collection);
    }

    /**
     * Register global provider
     */
    public function registerProvider() {
        $app = $this->app;

        $this->app->register(new SessionServiceProvider());
        $this->app->register(new ValidatorServiceProvider());
        $this->app->register(new FormServiceProvider());
        $this->app->register(new UrlGeneratorServiceProvider());
        $this->app->register(new \Silex\Provider\ServiceControllerServiceProvider());
        $this->app->register(new TranslationServiceProvider());


        $this->app->register(
                new SwiftmailerServiceProvider(), $this->app['dtc.config.manager']->getSettings('config', 'swiftmailer_configuration')
        );

        $this->app->register(
                new MonologServiceProvider(), $this->app['dtc.config.manager']->getSettings('config', 'monolog')
        );
        // Activate log rotate
        $this->app['monolog'] = $this->app->share($this->app->extend('monolog', function($monolog, $app) {
                    $monolog->pushHandler(new \Monolog\Handler\RotatingFileHandler($app['monolog.logfile'], 7, \Monolog\Logger::DEBUG));
                    return $monolog;
                }));


        $this->registerTemplateEngine();
        $this->registerOrm();
        $this->registerSecurity();
        $this->registerTranslation();

        $this->app['managerRegistry'] = $this->app->share(function ($app) {
            $emsName = array_keys($app['dbs.options']);
            $managerRegistry = new ManagerRegistry(null, array(), array_combine($emsName, $emsName), null, null, '\Doctrine\ORM\Proxy\Proxy');
            $managerRegistry->setContainer($app);

            return $managerRegistry;
        });

        $this->app['form.extensions'] = $this->app->share($this->app->extend('form.extensions', function ($extensions) use ($app) {
                    $emsName = array_keys($app['dbs.options']);

                    $managerRegistry = new ManagerRegistry(null, array(), array_combine($emsName, $emsName), null, null, '\Doctrine\ORM\Proxy\Proxy');
                    $managerRegistry->setContainer($app);
                    $extensions[] = new \Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension($managerRegistry);
                    return $extensions;
                }));

        if (isset($this->app['validator']) && class_exists('Symfony\\Bridge\\Doctrine\\Validator\\Constraints\\UniqueEntityValidator')) {

            $this->app['doctrine.orm.validator.unique_validator'] = function ($app) {
                return new \Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator($app['managerRegistry']);
            };

            if (!isset($this->app['validator.validator_service_ids'])) {
                $this->app['validator.validator_service_ids'] = array();
            }

            $this->app['validator.validator_service_ids'] = array_merge(
                    $this->app['validator.validator_service_ids'], array('doctrine.orm.validator.unique' => 'doctrine.orm.validator.unique_validator')
            );
        }

        if ($this->app['debug'] === true) {
            $this->app->register(
                    new \Silex\Provider\WebProfilerServiceProvider(), $this->app['dtc.config.manager']->getSettings('config', 'profiler_configuration')
            );
            $this->app->register(new \Sorien\Provider\DoctrineProfilerServiceProvider());
        }

        $this->app->register(new ConsoleServiceProvider(), $this->app['dtc.config.manager']->getSettings('config', 'console_configuration')
        );
    }

    /**
     * Register template engine (TWIG)
     */
    private function registerTemplateEngine() {
        $this->app->register(
                new TwigServiceProvider(), $this->app['dtc.config.manager']->getSettings('config', 'twig_configuration')
        );

        $this->app['twig'] = $this->app->share($this->app->extend('twig', function($twig, $app) {

                    // Add global var in twig
                    foreach ($app['dtc.config.manager']->getSettings('config', 'twig_globalsVar') as $name => $value) {
                        $app['logger']->debug('TWIG addGlobal "' . $name . '" => "' . $value . '"');
                        $twig->addGlobal($name, $value);
                    }

                    // Permet de convertir un entier (1-12) nom de mois ex: "Juil."
                    $filter = new \Twig_SimpleFilter('monthString', function ($string, $type = 'B') {
                        return toUTF8(strftime('%' . $type, strtotime(date('Y') . '-' . $string . '-01')));
                    });

                    $twig->addFilter('monthString', $filter);

                    /*
                      // Permet d'afficher une date avec le nom du jour de la sem. ex: "mar 04/12/2015"
                      $Jour = array("Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam");
                      $filter = new \Twig_SimpleFilter('jourDate', function ($string, $type = 'B') {
                      return toUTF8(strftime('%' . $type, strtotime(date('Y') . '-' . $string . '-01')));
                      });

                      $twig->addFilter('jourDate', $filter);
                     */

                    /*
                     * Permet de convertir un objet DateTime en timestamp et de le formater en appliquant le pattern
                     * passé en paramètre. (http://php.net/manual/en/function.strftime.php)
                     */
                    $filter = new \Twig_SimpleFilter('strftime', function (\DateTime $date, $type = '%d/%m/%Y') {
                        return toUTF8(strftime($type, $date->format('U')));
                    });

                    $twig->addFilter('strftime', $filter);

                    if ($app['debug'] === true) {
                        $webProfilerPath = '../vendor/symfony/web-profiler-bundle/Symfony/Bundle/WebProfilerBundle/Resources/views';
                        $app['twig.loader.filesystem']->addPath($webProfilerPath, 'WebProfiler');
                    }

                    return $twig;
                }));
    }

    /**
     * Register ORM (Doctrine)
     */
    private function registerOrm() {
        $this->app->register(
                new DoctrineServiceProvider, $this->app['dtc.config.manager']->getSettings('config', 'doctrine_dbal_configuration')
        );

        foreach (array_keys($this->app['dbs.options']) as $connectionName) {
            $this->app['dbs'][$connectionName]->getConfiguration()->setSQLLogger(new \CHRIST\Common\Service\DebugStack($this->app['logger']));
            $this->app['dbs'][$connectionName]->getDatabasePlatform()->registerDoctrineTypeMapping('sysname', 'string');
        }

        $this->app->register(
                new DoctrineOrmServiceProvider, $this->app['dtc.config.manager']->getSettings('config', 'doctrine_orm_configuration')
        );

        $this->app['dtc.user.ntlm']->getEntity();

        $blameableListener = new \Gedmo\Blameable\BlameableListener();
        $blameableListener->setUserValue($this->app['dtc.user.ntlm']->getEntity()->getId());

        $this->app->register(new \CHRIST\Common\Kernel\Providers\DoctrineExtensionsService\DoctrineExtensionsServiceProvider(), array(
            'doctrine_orm.extensions' => array(
                new \Gedmo\Timestampable\TimestampableListener(),
                new \Gedmo\Sluggable\SluggableListener(),
                $blameableListener,
            )
        ));
    }

    /**
     * Register security context
     */
    private function registerSecurity() {
        $app = $this->app;

        $config = $this->app['dtc.config.manager']->getSettings('config', 'security_configuration');

        $className = $config['security.firewalls']['secured']['users'];
        $config['security.firewalls']['secured']['users'] = $this->app->share(function() use ($className) {
            return new $className();
        });

        $this->app->register(new Kernel\Providers\CustomSecurityServiceProvider(), $config);

        $this->app['security.session_strategy'] = $this->app->share(function ($app) {
            return new SessionAuthenticationStrategy('none');
        });

        $this->app['twig'] = $this->app->share($this->app->extend('twig', function($twig, $app) {

                    $function = new \Twig_SimpleFunction('is_granted', function($role) use ($app) {

                        return $app['security']->isGranted($role);
                    });

                    $twig->addFunction($function);

                    return $twig;
                }));
    }

    /**
     * Register translation context (load all file)
     */
    private function registerTranslation() {
        $this->app->register(new TranslationServiceProvider(), array(
            'locale_fallbacks' => array('fr'),
        ));

        $this->app['translator'] = $this->app->share($this->app->extend('translator', function($translator, $app) {
                    $translator->addLoader('yaml', new \Symfony\Component\Translation\Loader\YamlFileLoader());
                    $translator->addResource('yaml', __DIR__ . '/Resources/translation/messages.fr.yml', 'fr');

                    return $translator;
                }));
    }

    private function clearCache() {
        if ($this->app['environment'] == 'local' && $this->app['debug'] == true) {

            // Clear apc cache
            if (function_exists('apc_clear_cache') && version_compare(PHP_VERSION, '5.5.0', '>=') && apc_clear_cache()) {
                $this->app['logger']->debug('APC User Cache clear: success');
            } elseif (function_exists('apc_clear_cache') && version_compare(PHP_VERSION, '5.5.0', '<') && apc_clear_cache('user')) {
                $this->app['logger']->debug('APC User Cache clear: success');
            } else {
                $this->app['logger']->debug('APC User Cache clear: fail');
            }

            if (function_exists('opcache_reset') && opcache_reset()) {
                $this->app['logger']->debug('APC Opcode Cache clear: success');
            } elseif (function_exists('apc_clear_cache') && version_compare(PHP_VERSION, '5.5.0', '<') && apc_clear_cache('opcode')) {
                $this->app['logger']->debug('APC Opcode Cache clear: success');
            } else {
                $this->app['logger']->debug('APC Opcode Cache clear: failure');
            }
        }
    }

}
