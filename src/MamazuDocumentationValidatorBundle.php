<?php
declare(strict_types=1);

namespace Mamazu\DocumentationValidator\Bundle;

use Mamazu\DocumentationValidator\Bundle\DependencyInjection\Compiler\RegisterAggregationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MamazuDocumentationValidatorBundle extends Bundle {
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new RegisterAggregationPass('documentation_validator.parser.aggregator', 'documentation_validator.parser')
        );
        $container->addCompilerPass(
            new RegisterAggregationPass('documentation_validator.validator.aggregator', 'documentation_validator.validator')
        );
    }
}
