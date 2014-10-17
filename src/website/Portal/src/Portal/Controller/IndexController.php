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
    public function indexAction(\ZfrMailChimp\Client\MailChimpClient $mc = null)
    {   
        flog('indexAction');

        // https://github.com/zf-fr/zfr-mailchimp-module
        // Cliente MailChip --------------------------------------------------------------
        $mc = $this->getServiceLocator()->get('ZfrMailChimp\Client\MailChimpClient');
        
        // Campania existente ------------------------------------------------------------
        //$idCampagnExist = '35b41b9a9e';
        //$campaig = $mc->getCampaigns(array('id'=>$idCampagnExist)); 
        //var_dump($campaig);
        
        // Creando campania --------------------------------------------------------------
        $apiKey = $this->getServiceLocator()->get('config')['zfr_mailchimp']['key'];
        $listIdExist = 'b529ef0a83';

        $opts['list_id'] = $listIdExist;
        $opts['subject'] = 'mi asunto de prueba';
        $opts['from_email'] = 'jrodev@yahoo.es'; 
        $opts['from_name'] = 'JRODEV SAC';
        $opts['tracking']=array('opens'=>true, 'html_clicks'=>true, 'text_clicks'=>true);
        // $opts['analytics'] = array('google'=>'goo_analytics_key');
        $opts['title'] = 'Titulo de prueba';

        $content = array(
            'html'=>'Mi contenido <b>html</b> *|UNSUB|* mensaje', 
            'text'=>'texto de prueba *|UNSUB|*'
        );
        /* 
        // Usando Template:
        $content = array(
            'html_main'       => 'some pretty html content',
            'html_sidecolumn' => 'this goes in a side column',
            'html_header'     => 'this gets placed in the header',
            'html_footer'     => 'the footer with an *|UNSUB|* message', 
            'text'            => 'text content text content *|UNSUB|*'
        );
        $opts['template_id'] = "1";
        */

        $idCamp = $mc->campaignCreate('regular', $opts, $content);

        if ($mc->errorCode){
                echo "\n\t no se puede crear campania!";
                echo "\n\t errorCode=".$mc->errorCode;
                echo "\n\t errorMessage=".$mc->errorMessage."\n";
        } else {
                echo "\n\t id nueva campania:".$idCamp."\n";
        }
        
        // Suscribiendo correos ------------------------------------------------------
        $batch = array();
        $batch['email'] = array('email' => 'user1@mail.com');
        $batch['email'] = array('email' => 'user2@mail.com');
        $batch['email'] = array('email' => 'jrodev@yahoo.es');
        /* ... more ...*/
        
        $mc->batchSubscribe($apiKey, $listIdExist, $batch);
        
        // Send Campania --------------------------------------------------------------
        $mc->sendCampaign($listIdExist, $idCamp);
        
        
        $storeScript = $this->getServiceLocator()->get('storeScript');
        $storeScript->setStore('fooIndex',['item'=>2,'item2'=>array(1,2)]);
        $this->layout()->setTemplate("layout/layout");
        return new ViewModel();
    }
}
