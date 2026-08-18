<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Console;

use Romainsickenberg\ProgrammaticResume\Generator\GeneratorRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:generate-resume', description: 'Generate a resume.json from a PHP profile generator.')]
final class GenerateResumeCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument(
                'profile',
                InputArgument::OPTIONAL,
                \sprintf('Profile to generate (%s). Omit to be prompted.', implode(', ', array_keys(GeneratorRegistry::all()))),
            )
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Write JSON to this file instead of stdout.');
    }

    /**
     * @throws \JsonException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $profiles = GeneratorRegistry::all();

        $slug = $input->getArgument('profile');
        if (null === $slug) {
            $slug = $io->choice('Which resume profile do you want to generate?', array_keys($profiles));
        }

        try {
            $generator = GeneratorRegistry::resolve($slug);
        } catch (\InvalidArgumentException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $resume = $generator();

        if (!$resume->validate()) {
            $io->error(\sprintf('Resume validation failed for profile "%s".', $slug));

            return Command::FAILURE;
        }

        $json = json_encode($resume, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        $outputFile = $input->getOption('output');
        if (null !== $outputFile) {
            file_put_contents($outputFile, $json . PHP_EOL);
            $io->success(\sprintf('Wrote "%s" resume to %s', $slug, $outputFile));

            return Command::SUCCESS;
        }

        $output->writeln($json);

        return Command::SUCCESS;
    }
}
