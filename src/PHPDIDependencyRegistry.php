<?php declare(strict_types=1);

namespace Qlimix\DependencyContainer;

use DI\Container;
use function array_replace_recursive;

final class PHPDIDependencyRegistry implements RegistryInterface
{
    private Container $phpdi;

    public function __construct(Container $phpdi)
    {
        $this->phpdi = $phpdi;
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, callable $service): void
    {
        $this->phpdi->set($id, function () use ($service) {
            return $service($this);
        });
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
        $this->phpdi->set($id, function () use ($maker) {
            return $maker($this);
        });
    }

    /**
     * @inheritDoc
     */
    public function make(string $id, ?string $setId = null)
    {
        $object = $this->phpdi->make($id);
        if ($setId !== null) {
            $this->setValue($setId, $object);
        }

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function merge(string $id, array $value): void
    {
        if (!$this->has($id)) {
            $this->setValue($id, $value);
        }

        $this->setValue($id, array_replace_recursive($this->get($id), $value));
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
    public function has($id): bool
    {
        return $this->phpdi->has($id);
    }
}
