<?php

namespace Drupal\attendance_custom\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

/**
 * Provides a 'ApproveAttendance' action.
 *
 * @Action(
 *  id = "approve_attendance",
 *  label = @Translation("Approve attendance"),
 *  type = "node",
 * )
 */
class ApproveAttendance extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($object = NULL) {
    $approver_role = \Drupal::currentUser()->getRoles();
    $approver_uid = \Drupal::currentUser()->id();
    $approver = User::load($approver_uid);
    $approver_team = $approver->get('field_team')->target_id;
    $requester_uid = $object->get('field_user')->target_id;
    $requester = User::load($requester_uid);
    $requester_team = $requester->get('field_team')->target_id;
    $requester_role = $requester->getRoles();
    if ($approver_role[1] == 'team_leader' AND $requester_role[1] == 'team_leader'){
      drupal_set_message('Sorry you are not authorized to approve TL attendance requests');
    }
    elseif ($approver_team != $requester_team){
      drupal_set_message('Sorry you are not authorized to approve attendance of other team');
    }
    else {
      $object->field_status->value = 'Approved';
      $object->field_approved_by->target_id = $approver_uid;
      $object->save();
    }

  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $access = $object->status->access('edit', $account, TRUE)
      ->andIf($object->access('update', $account, TRUE));

    return $return_as_object ? $access : $access->isAllowed();
  }

}
