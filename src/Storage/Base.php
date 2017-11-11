<?php
namespace Lhlog\Storage;

use Lhlog\IBase\IStorage;

abstract class Base implements IStorage
{
    use \Lhlog\Traits\Base;

    public $queue      = [];

    public $useBuffer  = false;

    public $bufferSize = 10;

    public function __construct($config = array())
    {
        $this->init($config);
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function init(array $config)
    {
        $this->initProperty( $config );
    }
}