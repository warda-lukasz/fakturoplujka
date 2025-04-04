#!/usr/bin/env php

<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


use Utils\TexManager;

$app = new SingleCommandApplication();

$app
    ->setName("Fakturoplujka - Invoice generator")
    ->setVersion('1.2.0')
    ->addArgument('startNumber', InputArgument::OPTIONAL, 'Start number for invoice generation', 1)
    ->addArgument('renderInactive', InputArgument::OPTIONAL, 'Render inactive invoices', false)
    ->setCode(function (InputInterface $input, OutputInterface $output): int {
        $start = microtime(true);

        $renderInactive = $input->getArgument('renderInactive');
        $startNumber = $input->getArgument('startNumber');

        $io = new SymfonyStyle($input, $output);
        $io->title('Fakturoplujka - Invoice generator');
        $io->writeln('Working... 🧐');

        $texManager = new TexManager($renderInactive, $startNumber);
        $texManager->parseTemplate();

        $io->success('Done... 😎  Check your output folder 👌');

        $stop = microtime(true);
        $io->info(
            sprintf(
                'Execution time:: %s ms',
                (int)(($stop - $start) * 1000)
            )
        );
        return 0;
    });

$app->run();
