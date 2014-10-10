<?php

namespace JavaScript;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    //Application\Model\Data\Cookie,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch;
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $callback = function (MvcEvent $event){
            $view = $event->getApplication()->getServiceManager()->get('ViewRenderer');
            $config = $event->getApplication()->getConfig();
            $controller = $event->getTarget();
            
            $rm = $event->getRouteMatch();
            if (!($rm instanceof RouteMatch)) {
                $rm = new RouteMatch(array(
                    'module'        => 'Application',
                    '__NAMESPACE__' => 'Application\Controller',
                    '__CONTROLLER__'=> 'index',
                    'controller'    => 'Application\Controller\Index',
                    'action'        => 'index',
                ));
            }

            $params = $rm->getParams();
            $modulo     = isset($params['__NAMESPACE__']) ? $params['__NAMESPACE__']:"";
            $controller = isset($params['__CONTROLLER__']) ? $params['__CONTROLLER__']:"";
            
            if (isset($params['controller'])) {
                $paramsArray = explode("\\", $params['controller']);
                $modulo = $paramsArray[0];
                $controller = $paramsArray[2];
            }

            $action = isset($params['action']) ? $params['action'] : null;
            $app = $event->getParam('application');
            $sm = $app->getServiceManager();

            $paramsConfig = [
                'modulo' => strtolower($modulo),
                'controller' => strtolower($controller),
                'action' => strtolower($action),
                'baseHost' => $view->base_path("/"),
                'min' => '',
                'AppCore' => [],
                'AppSandbox' => [],
                'AppSchema' => [ 'modules'=>[], 'requires'=>[] ]
            ];

            $view->inlineScript()->appendScript(
                "window.Global=".json_encode($paramsConfig, JSON_FORCE_OBJECT)
            );
        };

        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, $callback , 100
        );

        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH_ERROR, $callback , 100
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            )
        );
    }

}