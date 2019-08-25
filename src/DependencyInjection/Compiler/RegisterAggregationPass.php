<?php
declare(strict_types=1);

namespace Mamazu\DocumentationValidator\Bundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterAggregationPass implements CompilerPassInterface
{
    /** @var string */
    private $aggregatorId;

    /** @var string */
    private $tag;

    public function __construct(string $aggregatorServiceId, string $tag)
    {
        $this->aggregatorId = $aggregatorServiceId;
        $this->tag          = $tag;
    }

    /** {@inheritDoc} */
    public function process(ContainerBuilder $container): void
    {
        $parserAggregator = $container->findDefinition($this->aggregatorId);

        $services = $container->findTaggedServiceIds($this->tag);
        foreach ($services as $serviceId => $tagArguments) {
            $key = $tagArguments[0]['key'];
            $this->addMethodCall($parserAggregator, $key, $container->findDefinition($serviceId));
        }
    }

    public function addMethodCall(Definition $parserAggregator, string $key, Definition $serviceId): void
    {
        $parserAggregator->addMethodCall($this->getMethodName(), [$key, $serviceId]);
    }

    private function getMethodName(): string
    {
        switch ($this->tag) {
            case 'documentation_validator.parser':
                return 'addParser';
            case 'documentation_validator.validator':
                return 'addValidator';
        }

        throw new \InvalidArgumentException('Unkown tag');
    }
}
