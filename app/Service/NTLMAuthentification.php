<?php

namespace CHRIST\Common\Service;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Singleton permettant de récupérer 
 * le code RH et les informations des
 * utilisateur courant
 */
class NTLMAuthentification
{
    /**
     * @var \Silex\Application
     * @access private
     */
    private $app = null;

    /**
     * @var BaseEntity
     * @access private
     */
    private $entity = null;
    
    /**
     * @var \CHRIST\Common\Entity\Login\User
     * @access private
     */
    private $secureEntity = null;

    /**
     * NTLM informations
     * @var array
     */
    private $ntlmInfos = array(
        'codeRH' => '', 
        'domain' => '', 
        'workstation' => '',
    );
    
    
    
    
    /**
     * Constructor
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app, $ntlmInfos = array())
    {        
        $this->app          = $app;
        
        try {
            $force = $this->app['dtc.config.manager']->getSettings('config', 'ntlmInfos');
        } catch (\Exception $ex) {
            $force = false;
        }
        
        if ($force !== false) {
            
            $this->ntlmInfos = $force;
            $this->app['session']->set('UserNtlm', null);
            
            $this->app['logger']->info('NTLM : Reset user');
        } elseif ($this->app['environment'] != 'test') {
            
            // If environment is test no ntlm
            $this->ntlmInfos    = $ntlmInfos;

            $this->app['logger']->info('NTLM : Get user informations', array('ntlmInfos' => $this->ntlmInfos));
        }
        
//        $this->initUser();
    }
    
    /**
     * Initialize current user in session
     */
    public function initUser()
    {
        if ($this->app['session']->get('UserNtlm') != NULL
                && !_empty($this->app['session']->get('UserNtlm')->getId())
                && !_empty($this->app['session']->get('UserNtlm')->getDomain())
            ) {
                
            $this->entity = $this->app['session']->get('UserNtlm');
            $this->app['logger']->info('NTLM : Get user "' . $this->entity->getId() . '" from session');
                        
        } elseif (!empty($this->ntlmInfos['codeRH'])) {
            $this->entity = $this->app['orm.ems']['intranet']->getRepository("CHRIST\Common\Entity\Samba")->getObjectBy(array('id' => $this->ntlmInfos['codeRH']));
            $this->app['session']->set('UserNtlm', $this->entity);
            
            $this->app['logger']->info('NTLM : Set user in session', array('ntlmInfos' => $this->ntlmInfos));
        }
    }
    
    /**
     * Refresh current user object
     */
    public function refresh()
    {
        $this->app['session']->set('UserNtlm', null);
        $this->app['logger']->info('NTLM : Reset user');
        
        $this->ntlmInfos = self::getNtlmInfos();
        
        $this->initUser();
    }

    /**
     * Get UserNtlm
     * 
     * @return BaseEntity
     */
    public function getEntity()
    {
        if (empty($this->entity) || _empty($this->entity->getId())){
        
            $this->entity = new \CHRIST\Common\Entity\Samba();
        }
        
        return $this->entity;
    }

    /**
     * Get SecureUserNtlm
     * @return \CHRIST\Common\Entity\Login\User
     */
    public function getSecureEntity()
    {
        if (empty($this->secureEntity) || !($this->secureEntity instanceof \CHRIST\Common\Entity\Login\User) || _empty($this->secureEntity->getLogin())){
            
            $this->secureEntity = $this->app['orm.ems']['login']->getRepository("CHRIST\Common\Entity\Login\User")->find($this->ntlmInfos['codeRH']);
            
            if (is_null($this->secureEntity)) {
                $this->secureEntity = new \CHRIST\Common\Entity\Login\User();
            }
        }
        
        return $this->secureEntity;
    }
    
    /**
     * Change current user
     * @param string $codeRH
     */
    public function changeUser($codeRH)
    {
        $this->entity = $this->app['orm.ems']['intranet']->getRepository("CHRIST\Common\Entity\Samba")->getObjectBy(array('id' => $codeRH));
        $this->app['session']->set('UserNtlm', $this->entity);
    }

    /**
     * Analyse les entêtes HTTP pour obtenir les informations de l'utilisateur
     * 
     * @return array
     * @throws \Exception
     */
    public static function getNtlmInfos()
    {
        $ntlmInfos = array(
            'codeRH' => '', 
            'domain' => '', 
            'workstation' => '',
        );
        
        $headers      = apache_request_headers();
        
        if (!isset($headers['Authorization'])) {
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: NTLM');
            exit();
        }
        
        $auth = $headers['Authorization'];
        
        
        if (substr($auth, 0, 5) == 'NTLM ') {
            
            $msg = base64_decode(substr($auth, 5));
            
            if (substr($msg, 0, 8) != "NTLMSSP\x00") {
                
                throw new \Exception('ERREUR NTLM : header inconnu', 401);
            }

            if ($msg[8] == "\x01") {
                
                $msg2 = "NTLMSSP\x00\x02" . "\x00\x00\x00\x00" . // target name len/alloc
                        "\x00\x00\x00\x00" . // target name offset
                        "\x01\x02\x81\x01" . // flags
                        "\x00\x00\x00\x00\x00\x00\x00\x00" . // challenge
                        "\x00\x00\x00\x00\x00\x00\x00\x00" . // context
                        "\x00\x00\x00\x00\x30\x00\x00\x00"; // target info len/alloc/offset
                
                header('HTTP/1.1 401 Unauthorized');
                header('WWW-Authenticate: NTLM ' . trim(base64_encode($msg2)));
                exit();
            } else if ($msg[8] == "\x03") {
                $ntlmInfos['codeRH'] = substr(self::getNtlmMessage($msg, 36), -6);
                $ntlmInfos['domain'] = self::getNtlmMessage($msg, 28);
                $ntlmInfos['workstation'] = self::getNtlmMessage($msg, 44);
            }
        }
        
        return $ntlmInfos;
    }

    /**
     * Retourne un message permettant de récupérer les informations 
     * des entêtes lors de l'authentification
     * 
     * @param string     $msg
     * @param int        $start
     * @param boolean    $unicode
     * 
     * @return string
     */
    public static function getNtlmMessage($msg, $start, $unicode = true) {
        $len = (ord($msg[$start + 1]) * 256) + ord($msg[$start]);
        $off = (ord($msg[$start + 5]) * 256) + ord($msg[$start + 4]);
                
        if ($unicode) {
            return $result = iconv('UTF-16LE', 'UTF-8', substr($msg, $off, $len));
        } else {
            return substr($msg, $off, $len);
        }
    }
}
