<?php

namespace Drupal\attendance_custom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

/**
 * Class AttendanceController.
 */
class AttendanceController extends ControllerBase {

  /**
   * Approve_attendance.
   *
   * @return string
   *   Return Hello string.
   */
  public function approve_attendance($nid) {
    $object = Node::load($nid);
    $approver_role = \Drupal::currentUser()->getRoles();
    $approver_uid = \Drupal::currentUser()->id();
    $requester_uid = $object->field_user->target_id;
    $requester = User::load($requester_uid);
    $requester_role = $requester->getRoles();
    if ($approver_role[1] == 'team_leader' AND $requester_role[1] == 'team_leader'){
      $msg = 'Sorry you are not authorized to approve TL attendance requests';
    }
    elseif ($approver_role[1] == 'employee'){
      $msg = 'Sorry you are not authorized to approve attendance requests';
    }
    else{
      $object->field_status->value = 'Approved';
      $object->field_approved_by->target_id = $approver_uid;
      $object->save();
      $msg = 'Successfully approved attendance record';
    }
    return [
      '#type' => 'markup',
      '#markup' => $this->t($msg),
    ];
  }

}
