<?php

namespace Ciandt\Behat\EnvironmentExtension\Cli;

use Behat\Testwork\Cli\Controller;
use Ciandt\Behat\EnvironmentExtension\EnvironmentRepository;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EnvironmentController.
 *
 * @package Ciandt\Behat\EnvironmentExtension\Cli
 */
final class EnvironmentController implements Controller {

  /**
   * The environment repository.
   *
   * @var EnvironmentRepository
   */
  protected $environmentRepository;

  /**
   * EnvironmentController constructor.
   *
   * @param EnvironmentRepository $environment_repository
   */
  public function __construct(EnvironmentRepository $environment_repository) {
    $this->environmentRepository = $environment_repository;
  }

  /**
   * {@inheritdoc}
   */
  public function configure(SymfonyCommand $command) {
    $command->addOption(
      'environment',
      'e',
      InputArgument::OPTIONAL,
      'Set the default environment to run the features',
      'default'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    if (
      ($environment = $input->getOption('environment')) &&
      in_array($environment, $this->environmentRepository->getEnvironments())
    ) {
      $this->environmentRepository->setDefaultEnvironment($input->getOption('environment'));
    }
  }

}
