<?php

namespace Drupal\attendance_custom\Plugin\RulesAction;

use Drupal\rules\Core\RulesActionBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Entity;
use Drupal\user\Entity\User;

/**
 * Provides a 'Fetch entity by id' action.
 *
 * @RulesAction(
 *   id = "send_approval_link_slack",
 *   label = @Translation("Send Approval Link Slack"),
 *   category = @Translation("Custom"),
 *   context = {
 *     "entity_id" = @ContextDefinition("integer",
 *       label = @Translation("Node ID"),
 *       description = @Translation("The id of the entity that should be fetched."),
 *     )
 *   },
 *   provides = {
 *     "result" = @ContextDefinition("string",
 *       label = @Translation("Status Message")
 *     )
 *   }
 * )
 *
 * @todo: Add access callback information from Drupal 7.
 * @todo: port for rules_entity_action_type_options.
 */
class SendApprovalLinkSlack extends RulesActionBase {

  /**
   * Executes custom plugin.
   *
   * @param node $node
   */

  public function execute() {
    $node_id = $this->getContextValue('entity_id');
    //$node_id = $node_id->list[0]->values['value'];
    $node = Node::load($node_id);
    $requester_id = $node->get('field_user')->target_id;
    $requester = User::load($requester_id);
    $requester_name = $requester->get('field_name')->value;
    global $base_url;
    $message = "$requester_name has requested attendance. Click on this link to approve $base_url/attendance_custom/approve_attendance/$node_id";
    define('SLACK_WEBHOOK', 'https://hooks.slack.com/services/aaa/bbb/ccc');
    $msg = ['text' => $message];
    $c = curl_init(SLACK_WEBHOOK);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($c, CURLOPT_POST, TRUE);
    curl_setopt($c, CURLOPT_POSTFIELDS, ['payload' => json_encode($msg)]);
    $result = curl_exec($c);
    curl_close($c);

    $this->setProvidedValue('result', 'Success');
  }

}
