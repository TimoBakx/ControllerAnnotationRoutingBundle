<?php
declare(strict_types=1);

namespace TimoBakx\ControllerAnnotationRoutingBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\RouteCollection;

class ControllerAnnotationLoader implements LoaderInterface
{
    /**
     * @var LoaderResolverInterface
     */
    protected $resolver;

    /**
     * @var AnnotatedRouteControllerLoader
     */
    private $loader;

    /**
     * @var array
     */
    private $controllers;

    /**
     * @var bool
     */
    private $isLoaded = false;

    /**
     * ControllerAnnotationLoader constructor.
     * @param array $controllers
     * @param AnnotatedRouteControllerLoader $loader
     */
    public function __construct(array $controllers, AnnotatedRouteControllerLoader $loader)
    {
        $this->controllers = $controllers;
        $this->loader = $loader;
    }

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return RouteCollection
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null): RouteCollection
    {
        if ($this->isLoaded === true) {
            throw new \RuntimeException('Do not add the loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->controllers as $controller) {
            $routes->addCollection($this->loader->load(\get_class($controller)));
        }

        $this->isLoaded = true;

        return $routes;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null): bool
    {
        return $type === 'controllerannotation';
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver(): LoaderResolverInterface
    {
        return $this->resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver): void
    {
        $this->resolver = $resolver;
    }
}
