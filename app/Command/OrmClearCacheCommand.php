<?php

namespace CHRIST\Common\Command;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Cache\ApcCache;

/**
 * Description of ClearCacheDoctrine
 *
 * @author glr735
 */
class OrmClearCacheCommand extends Command
{
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
        ->setName('dtc:clear-cache:orm')
        ->setDescription('Clear all metadata and query cache of the various cache drivers.')
        ->setDefinition(array(
            new InputOption(
                'flush', null, InputOption::VALUE_NONE,
                'If defined, cache entries will be flushed instead of deleted/invalidated.'
            )
        ));

        $this->setHelp(<<<EOT
The <info>%command.name%</info> command is meant to clear the metadata cache of associated Entity Manager.
It is possible to invalidate all cache entries at once - called delete -, or flushes the cache provider
instance completely.

The execution type differ on how you execute the command.
If you want to invalidate the entries (and not delete from cache instance), this command would do the work:

<info>%command.name%</info>

Alternatively, if you want to flush the cache provider using this command:

<info>%command.name% --flush</info>

Finally, be aware that if <info>--flush</info> option is passed, not all cache providers are able to flush entries,
because of a limitation of its execution nature.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = \CHRIST\Common\Kernel\SingleApp::getAppliation();
        $message = '';
        $output->writeln('--------------------------------------------------');
        
        foreach (array_keys($app['dbs.options']) as $connectionName) {
            
            
            $output->writeln('Treatement of : ' . $connectionName);
            
            $this->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet(array(
                'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app['dbs'][$connectionName]),
                'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['orm.ems'][$connectionName])
            )));
            
            
            $em = $this->getHelper('em')->getEntityManager();
            $metaCacheDriver = $em->getConfiguration()->getMetadataCacheImpl();
            $queryCacheDriver = $em->getConfiguration()->getQueryCacheImpl();
            
            if (!$metaCacheDriver) {
                throw new \InvalidArgumentException('No meta cache driver is configured on given EntityManager.');
            }
            
            if (!$queryCacheDriver) {
                throw new \InvalidArgumentException('No meta cache driver is configured on given EntityManager.');
            }

            if (($metaCacheDriver instanceof ApcCache) && ($queryCacheDriver instanceof ApcCache)) {
                throw new \LogicException("Cannot clear APC Cache from Console, its shared in the Webserver memory and not accessible from the CLI.");
            }

            $output->writeln('Clearing ALL Metadata cache entries');
            $output->writeln('Clearing ALL Query cache entries');
            
            $resultMeta  = $metaCacheDriver->deleteAll();
            $resultQuery = $queryCacheDriver->deleteAll();
            $message .= ($resultMeta && $resultQuery) ? 'Successfully deleted cache entries.' : 'No cache entries were deleted.';

            $resultMeta  = $metaCacheDriver->flushAll();
            $resultQuery = $queryCacheDriver->flushAll();
            $output->writeln('--------------------------------------------------');
        }
        
        $output->write($message . PHP_EOL);
    }
}
