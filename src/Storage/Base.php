<?php
namespace Lhlog\Storage;

use Lhlog\IBase\IStorage;

abstract class Base extends \SplPriorityQueue implements IStorage
{
    use \Lhlog\Traits\Base;

    protected $priorityQueue;

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

    public function compare($priority1, $priority2)
    {
        if ($priority1 === $priority2) return 0;
        return $priority1 < $priority2 ? -1 : 1;
    }
}