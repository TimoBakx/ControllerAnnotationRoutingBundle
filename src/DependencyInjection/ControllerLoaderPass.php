<?php
declare(strict_types=1);

namespace TimoBakx\ControllerAnnotationRoutingBundle\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use TimoBakx\ControllerAnnotationRoutingBundle\ControllerInterface;
use TimoBakx\ControllerAnnotationRoutingBundle\Routing\ControllerAnnotationLoader;
use Zend\Code\Reflection\ClassReflection;

class ControllerLoaderPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ControllerAnnotationLoader::class)) {
            return;
        }

        $loaderDefinition = $container->findDefinition(ControllerAnnotationLoader::class);
        $controllerDefinitions = [];

        foreach ($container->getDefinitions() as $serviceId => $definition) {
            if ($definition->isAbstract()) {
                continue;
            }

            try {
                $reflection = new ClassReflection($definition->getClass());
            } catch (\ReflectionException $exception) {
                continue;
            }

            if ($reflection->isSubclassOf(Controller::class) || $reflection->isSubclassOf(ControllerInterface::class)) {
                $controllerDefinitions[] = new Reference($serviceId);
            }
        }

        $loaderDefinition->setArgument(0, $controllerDefinitions);
    }
}
