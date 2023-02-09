<?php

namespace Drupal\build_hooks\Event;

use Drupal\build_hooks\BuildHookDetails;
use Drupal\build_hooks\Entity\DeploymentInterface;
use Drupal\build_hooks\Entity\FrontendEnvironmentInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines a class for triggering a build.
 */
class BuildTrigger extends Event {

  /**
   * Build hook details.
   *
   * @var \Drupal\build_hooks\BuildHookDetails
   */
  protected $buildHookDetails;

  /**
   * Front-end environment.
   *
   * @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface
   */
  protected $frontendEnvironment;

  /**
   * TRUE if should build.
   *
   * @var bool
   */
  protected $build = TRUE;

  /**
   * Reason not to build.
   *
   * @var \Drupal\Core\StringTranslation\TranslatableMarkup
   */
  protected $reason;

  /**
   * Response.
   *
   * @var \Psr\Http\Message\ResponseInterface
   */
  protected $response;

  /**
   * Current deployment.
   *
   * @var \Drupal\build_hooks\Entity\DeploymentInterface
   */
  protected $deployment;

  /**
   * Constructs a new BuildTriggerEvent.
   *
   * @param \Drupal\build_hooks\BuildHookDetails $buildHookDetails
   *   Build hook details.
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $frontendEnvironment
   *   Front-end environment.
   * @param \Drupal\build_hooks\Entity\DeploymentInterface $deployment
   *   Deployment.
   */
  public function __construct(BuildHookDetails $buildHookDetails, FrontendEnvironmentInterface $frontendEnvironment, DeploymentInterface $deployment) {
    $this->buildHookDetails = $buildHookDetails;
    $this->frontendEnvironment = $frontendEnvironment;
    $this->deployment = $deployment;
  }

  /**
   * Sets if the build should occur.
   *
   * @return $this
   */
  public function setShouldBuild() : BuildTrigger {
    $this->build = TRUE;
    return $this;
  }

  /**
   * Sets should not build.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup $reason
   *   The reason not to build.
   *
   * @return $this
   */
  public function setShouldNotBuild(TranslatableMarkup $reason) : BuildTrigger {
    $this->build = FALSE;
    $this->reason = $reason;
    return $this;
  }

  /**
   * Check if the build should occur.
   *
   * @return bool
   *   TRUE if should build.
   */
  public function shouldBuild() : bool {
    return $this->build;
  }

  /**
   * Gets value of reason not to build.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Reason not to build.
   */
  public function getReason(): TranslatableMarkup {
    return $this->reason;
  }

  /**
   * Sets response.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   Response.
   */
  public function setResponse(ResponseInterface $response): BuildTrigger {
    $this->response = $response;
    return $this;
  }

  /**
   * Gets build hook details.
   *
   * @return \Drupal\build_hooks\BuildHookDetails
   *   Build hook details.
   */
  public function getBuildHookDetails(): BuildHookDetails {
    return $this->buildHookDetails;
  }

  /**
   * Gets front-end environment.
   *
   * @return \Drupal\build_hooks\Entity\FrontendEnvironmentInterface
   *   Front-end environment.
   */
  public function getFrontendEnvironment(): FrontendEnvironmentInterface {
    return $this->frontendEnvironment;
  }

  /**
   * Gets value of Response.
   *
   * @return \Psr\Http\Message\ResponseInterface|null
   *   Value of Response.
   */
  public function getResponse(): ?ResponseInterface {
    return $this->response;
  }

  /**
   * Gets current deployment.
   *
   * @return \Drupal\build_hooks\Entity\DeploymentInterface
   *   Current deployment.
   */
  public function getDeployment(): DeploymentInterface {
    return $this->deployment;
  }

}
