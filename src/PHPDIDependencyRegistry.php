<?php declare(strict_types=1);

namespace Qlimix\DependencyContainer\PHPDI;

use DI\Container;
use Qlimix\DependencyContainer\DependencyRegistryInterface;

final class PHPDIDependencyRegistry implements DependencyRegistryInterface
{
    /** @var Container */
    private $phpdi;

    /**
     * @param Container $phpdi
     */
    public function __construct(Container $phpdi)
    {
        $this->phpdi = $phpdi;
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, callable $service): void
    {
        $this->phpdi->set($id, $service);
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $id, $value): void
    {
        $this->phpdi->set($id, $value);
    }

    public function setMaker(string $id, callable $maker): void
    {
        $this->phpdi->set($id, $maker);
    }

    /**
     * @inheritDoc
     */
    public function make(string $id)
    {
        return $this->phpdi->make($id);
    }

    public function merge(string $id, array $value): void
    {
        if (!$this->has($id)) {
            $this->setValue($id, $value);
        }

        $this->setValue($id, array_merge_recursive($this->get($id), $value));
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        return $this->phpdi->get($id);
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return $this->phpdi->has($id);
    }
}
