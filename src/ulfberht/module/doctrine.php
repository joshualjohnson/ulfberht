<?php
/**
 * @package ulfberht
 * @author Joshua L. Johnson <josh@ua1.us>
 * @link http://ua1.us
 * @copyright Copyright 2016, Joshua L. Johnson
 * @license MIT
 */

namespace ulfberht\module;

use Exception;
use ulfberht\module\config;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class doctrine {

    private $_config;

    private $_doctrineObjects;

    public function __construct(config $config) {
        $this->_doctrineObjects = [];
        $this->_config = $config->get('doctrine');
        if (!$this->_config) {
            throw new Exception('Could not find Doctrine Config.');
        }

        foreach ($this->_config as $id => $config) {
            if (!isset($config['type'])) {
                throw new Exception('Undefined parameter "type" in "' . $id . '" doctrine config.');
            }
            if (!isset($config['paths'])) {
                throw new Exception('Undefined parameter "paths" in "' . $id . '" doctrine config.');
            }
            if (!isset($config['database'])) {
                throw new Exception('Undefined parameter "database" in "' . $id . '" doctrine config.');
            }

            $development = (isset($config['develop']) && $config['develop']) ? true : false;
            switch ($config['type']) {
                case 'annotation':
                    $docConfig = Setup::createAnnotationMetadataConfiguration($config['paths'], $development);
                break;
                case 'xml':
                    $docConfig = Setup::createXMLMetadataConfiguration($config['paths'], $development);
                break;
                case 'yaml':
                    $docConfig = Setup::createYAMLMetadataConfiguration($config['paths'], $development);
                break;
            }
            $this->_doctrineObjects['config'] = $docConfig;
            $this->_doctrineObjects[$id] = EntityManager::create($config['database'], $docConfig);
        }
    }

    public function getDotrineConfig() {
        if (!isset($this->_doctrineObjects['config'])) {
            throw new Exception('Could not find doctrine config object');
        }
        return $this->_doctrineObjects['config'];
    }

    public function getEntityManager($id) {
        if (!isset($this->_doctrineObjects[$id])) {
            throw new Exception('Could not find entityManager "' . $id . '"');
        }
        return $this->_doctrineObjects[$id];
    }

}
