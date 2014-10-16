<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {   
        flog('indexAction');
        // https://github.com/zf-fr/zfr-mailchimp-module
        $mailChimpClient = $serviceManager->get('ZfrMailChimp\Client\MailChimpClient');
        
        $storeScript = $this->getServiceLocator()->get('storeScript');
        $storeScript->setStore('fooIndex',['item'=>2,'item2'=>array(1,2)]);
        $this->layout()->setTemplate("layout/layout");
        return new ViewModel();
    }
}
