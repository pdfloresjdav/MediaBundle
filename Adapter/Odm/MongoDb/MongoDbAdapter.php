<?php

/*
 * This file is part of the VlabsMediaBundle package.
 *
 * (c) Valentin Ferriere <http://www.v-labs.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vlabs\MediaBundle\Adapter\Odm\MongoDb;

use Doctrine\Common\EventArgs;
use Doctrine\ODM\MongoDB\Proxy;
use Vlabs\MediaBundle\Adapter\AdapterInterface;

/**
 * @author Valentin Ferriere <valentin.ferriere@gmail.com>
 */
class MongoDbAdapter implements AdapterInterface
{
    /**
    * {@inheritDoc}
    */
    public function getObject(EventArgs $e)
    {
        return $e->getDocument();
    }

    /**
    * {@inheritDoc}
    */
    public function getLoadedMetaDatas(EventArgs $e)
    {
        return $e->getDocumentManager()->getMetadataFactory()->getLoadedMetadata();
    }

    /**
    * {@inheritDoc}
    */
    public function getClass($e)
    {
        return ($e instanceof Proxy) ? get_parent_class($e) : get_class($e);
    }
}
