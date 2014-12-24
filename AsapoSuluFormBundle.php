<?php

namespace Asapo\Bundle\Sulu\FormBundle;

use Asapo\Bundle\Sulu\FormBundle\DependencyInjection\Pass\FormsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AsapoSuluFormBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
     $container->addCompilerPass(new FormsCompilerPass());
    }

}
