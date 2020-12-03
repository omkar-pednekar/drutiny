<?php

namespace Drutiny\Report\Twig;

use Drutiny\AssessmentInterface;
use Drutiny\AuditResponse\AuditResponse;
use Twig\Environment;


class Helper {
  /**
   * Registered as a Twig filter to be used as: "Title here"|heading.
   */
  public static function filterSectionHeading(Environment $env, $heading)
  {
    return $env
      ->createTemplate('<h2 class="section-title" id="section_{{ heading | u.snake }}">{{ heading }}</h2>')
      ->render(['heading' => $heading]);
  }

  /**
   * Registered as a Twig filter to be used as: chart.foo|chart.
   */
  public static function filterChart(array $chart)
  {
    $class = 'chart-unprocessed';
    if (isset($chart['html-class'])) {
        $class .= ' '.$chart['html-class'];
    }
    $element = '<div class="'.$class.'" ';
    foreach ($chart as $name => $key) {
      $value = is_array($key) ? implode(',', $key) : $key;
      $element .= 'data-chart-'.$name . '="'.$value.'" ' ;
    }
    return $element . '></div>';
  }

  public static function renderAuditReponse(Environment $twig, AuditResponse $response, AssessmentInterface $assessment)
  {
      $globals = $twig->getGlobals();
      $template = 'report/policy/'.$response->getType().'.'.$globals['ext'].'.twig';
      $globals['logger']->info("Rendering audit response for ".$response->getPolicy()->name.' with '.$template);
      $globals['logger']->info('Keys: ' . implode(', ', array_keys($response->getTokens())));
      return $twig->render($template, [
        'audit_response' => $response,
        'assessment' => $assessment,
      ]);
  }

  public static function keyed($variable) {
    return is_array($variable) && is_string(key($variable));
  }
}

 ?>
