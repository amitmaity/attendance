<?php

namespace Drupal\attendance_custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;

/**
 * Class AttendanceForm.
 */
class AttendanceForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'attendance_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['attendance_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Attendance Date'),
      '#description' => $this->t('The date of offsite attendance'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uid = \Drupal::currentUser()->id();
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      if ($key == 'attendance_date'){
        $date[] = $value;
        $node = Node::create([
          'type' => 'attendance',
          'field_date' => $date,
          'field_user' => $uid,
        ]);
        $node->save();
        drupal_set_message('Successfully applied for attendance');
      }
    }

  }

}
