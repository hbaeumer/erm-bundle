<?php declare(strict_types=1);

/**
 * This file is part of hbaeumer/erm-bundle
 *
 * Copyright (c) Heiner Baeumer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */


namespace Hbaeumer\ErmBundle\Command;

use Hbaeumer\ErmBundle\Grapher\PlantUmlGrapher;
use Hbaeumer\ErmBundle\Parser\PlantUmlEntityParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ERMDiagramCommand extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'doctrine:erm:output';

    /**
     * @var PlantUmlEntityParser
     */
    private $parser;

    /**
     * @var PlantUmlGrapher
     */
    private $grapher;

    public function __construct(PlantUmlEntityParser $parser, PlantUmlGrapher $grapher)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->grapher = $grapher;
    }

    protected function configure(): void
    {
        $this->addOption('svg', 's', InputOption::VALUE_OPTIONAL, 'svg path');
        $this->addOption('markup', 'm', InputOption::VALUE_NONE, 'markup output');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $markup = $this->parser->getMarkup();
        if ($input->getOption('markup')) {
            $output->writeln($markup);
        }
        if ($input->getOption('svg')) {
            file_put_contents($input->getOption('svg'), $this->grapher->getSVG($markup));
        }
    }
}
