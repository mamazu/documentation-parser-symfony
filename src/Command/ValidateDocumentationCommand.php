<?php

declare(strict_types=1);

namespace Mamazu\DocumentationValidator\Bundle\Command;

use Mamazu\DocumentationParser\Application;
use Mamazu\DocumentationParser\Output\Formatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ValidateDocumentationCommand extends Command
{
    protected static $defaultName = 'documentation:validate';

    /** @var Application */
    private $application;

    /** @var Formatter */
    private $formatter;

    public function __construct(
        Application $application,
        Formatter $formatter,
        $name = null
    ) {
        $this->application = $application;
        $this->formatter = $formatter;
        parent::__construct($name);
     }

    protected function configure()
    {
        $this->setDescription('Validate the documentation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $errors = $this->application->parse();

        $io = new SymfonyStyle($input, $output);

        foreach($errors as $error) {
            $io->error($this->formatter->format($error));
        }
    }
}
