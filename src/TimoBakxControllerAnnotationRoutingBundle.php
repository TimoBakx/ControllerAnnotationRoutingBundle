<?php
declare(strict_types=1);

namespace TimoBakx\ControllerAnnotationRoutingBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TimoBakx\ControllerAnnotationRoutingBundle\DependencyInjection\ControllerAnnotationRoutingExtension;
use TimoBakx\ControllerAnnotationRoutingBundle\DependencyInjection\ControllerLoaderPass;

class TimoBakxControllerAnnotationRoutingBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ControllerLoaderPass());
    }

    /**
     * @return ExtensionInterface
     */
    public function getContainerExtension(): ExtensionInterface
    {
        return new ControllerAnnotationRoutingExtension();
    }


}