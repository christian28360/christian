<?php

namespace CHRIST\Common\Kernel\Modules\Import\Wrappers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use CHRIST\Common\Kernel\Modules\Import\Interfaces\IImportWrapper;
use CHRIST\Common\Kernel\Modules\Import\Wrappers\AbstractWrapper;

/**
 * Description of FtpWrapper
 *
 * @author glr735
 */
class FtpWrapper extends AbstractWrapper implements IImportWrapper
{
    /**
     * Source file (remote server)
     * @var string
     */
    private $source;
	
    /**
     * Local path
     * @var string
     */
    private $target;
	
    /**
     * Root on remote server
     * @var string
     */
    private $remoteRoot = '';
	
    /**
     * FTP config
     * [ 'host' => '', 'port' => '', 'login' => '', 'password' => '' ]
     * @var array
     */
    private $ftpConfig = array();
	
    /**
     * Flux FTP
     * @var resource
     */
    private $ftpStream = null;
    
    private $parametersMap = array(
        'source'    => 'source', 
        'target'    => 'target', 
        'ftp'       => 'ftpConfig', 
    );
    
    
    

    /**
     * Constructor
     * @param \CHRIST\Common\Kernel\Modules\Import\Parameter $parameters
     * @throws \Exception 
     */
    public function __construct(\CHRIST\Common\Kernel\Modules\Import\Parameter $parameters)
    {
        $this->parameters   = $parameters;
        
        $this->source       = $this->parameters->getSettings('source');
	$this->target       = $this->parameters->getSettings('file');
	$this->remoteRoot   = $this->parameters->getSettings('remoteRoot');
	$this->ftpConfig    = $this->parameters->getSettings('ftp');
        $this->date         = $this->parameters->getSettings('dateImport');
        
        if (!isset($this->ftpConfig['host']) || empty($this->ftpConfig['host'])) {
            throw new \Exception(__CLASS__ . ' => FTP parameter "host" should not be empty', 1);
        }
        
        if (!isset($this->ftpConfig['port']) || empty($this->ftpConfig['port'])) {
            $this->ftpConfig['port'] = 21;
        }

        if (!isset($this->ftpConfig['login']) || empty($this->ftpConfig['login'])) {
            throw new \Exception(__CLASS__ . ' => FTP parameter "login" should not be empty', 1);
        }

        if (!isset($this->ftpConfig['password']) || empty($this->ftpConfig['password'])) {
            throw new \Exception(__CLASS__ . ' => FTP parameter "password" should not be empty', 1);
        }
            
        if (empty($this->source)) {
            throw new \Exception(__CLASS__ . ' => "source" should not be empty', 1);
        }

        if (empty($this->target)) {
            throw new \Exception(__CLASS__ . ' => "target" should not be empty', 1);
        }
                
		
        if (($this->ftpStream = @ftp_connect($this->ftpConfig['host'], $this->ftpConfig['port'])) === false) {
            throw new \Exception(__CLASS__ . ' => Unable to connect to remote server', 1);
        }

	if (!@ftp_login($this->ftpStream, $this->ftpConfig['login'], $this->ftpConfig['password'])) {
            throw new \Exception(__CLASS__ . ' => Authentication on the remote server failed', 1);
        }

        if (!@ftp_pasv($this->ftpStream, true)) {
            throw new \Exception(__CLASS__ . ' => Passive mode failed', 1);
        }
        
        if (@ftp_chdir($this->ftpStream, $this->remoteRoot) === false) {
            throw new \Exception(__CLASS__ . ' => Remote root "' . $this->remoteRoot . '" doesn\'t exist', 1);
        }
    }
        
        
    /**
     * Closes FTP connection
     */
    public function __destruct()
    {
        if ($this->ftpStream !== false) {
            ftp_close($this->ftpStream);
        }
    }

    /**
     * Create directory on remote server
     * @param  string $name
     */
    private function createDir($name = '')
    {
        if (@ftp_chdir($this->ftpStream, $name) === false) {

            if (@ftp_mkdir($this->ftpStream, $name) === false) {
                throw new \Exception(__CLASS__ . ' => Failed to create folder "' . $name . '" on remote server', 1);
            }

            @ftp_chdir($this->ftpStream, $name);
        }
    }

    /**
     * Downloads a file from the FTP server
     * @return boolean true if file is downloaded
     * @throws \Exception
     */
    public function getFile()
    {
        // Archives tree
        $this->createDir('archives/' . $this->date->format('Y'));
        $this->createDir($this->date->format('m'));

        $info = new \SplFileInfo($this->target);

        $oldmask = umask(0);
        @mkdir($info->getPath(), 0775, true);
        umask($oldmask);
            
        if (@ftp_nlist($this->ftpStream, "-la " . '/' . $this->remoteRoot . '/' . $this->source) === false) {
            throw new \Exception(__CLASS__ . ' => File "' . $this->source . '" doesn\'t exist on remote server', 1);
        }
        
        if (@ftp_get($this->ftpStream, $this->target, '/' . $this->remoteRoot . '/' . $this->source, FTP_BINARY)) {
            $oldmask = umask(0);
            @chmod($this->cible, 0775);
            umask($oldmask);
            
            $fileInfo = new \SplFileInfo($this->source);
            $newName = $fileInfo->getBasename('.' . $fileInfo->getExtension()) . ' ' . date('Y-m-d-H-i-s') . '.' . $fileInfo->getExtension();
            
            if (@ftp_rename($this->ftpStream, '/' . $this->remoteRoot . '/' . $this->source, $newName) === true) {
                return true;
            } else {
                throw new \Exception(__CLASS__ . ' => archiving ' . $this->source . ' on the remote server failed', 1);
            }
            
            return true;
        } else {
            
            throw new \Exception(__CLASS__ . ' => download of "' . $this->source . '" is failed', 1);
	}
    }

    /**
     * @inherit
     */
    public function import()
    {
        return $this->finalizeImport($this->getFile() ? 'OK' : 'Error file transfer');
    }
}