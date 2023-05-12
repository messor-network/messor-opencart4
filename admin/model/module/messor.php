<?php

namespace Opencart\Admin\Model\Extension\Messor\Module;

class Messor extends \Opencart\System\Engine\Model
{

  public function LoadSettings()
  {
    return $this->config->get('module_messor_status');
  }

  public function createEvents()
  {
    $this->load->model('setting/event');
    // todo change parameters function delete, add association array, and add description
    $this->model_setting_event->addEvent(
      array(
        "code" => "messor",
        "description" => "Add messor in left column",
        "trigger" => "admin/view/common/column_left/before",
        // todo change action, before extension/module/messor/addLeftColumn. Events now, controller | function
        "action" => "extension/messor/module/messor|addLeftColumn",
        "status" => 1,
        "sort_order" => 0
      )
    );
    // todo change parameters function delete, add association array, and add description
    $this->model_setting_event->addEvent(
      array(
        "code" => "messor",
        "description" => "Add call Messor before controller",
        "trigger" => "catalog/controller/*/before",
        // todo change action, before extension/module/messor/alertMessor. Events now, controller | function
        "action" => "extension/messor/module/messor|alertMessor",
        "status" => 1,
        "sort_order" => 0
      )
    );
    // todo change parameters function delete, add association array, and add description
    $this->model_setting_event->addEvent(
      array(
        "code" => "messor",
        "description" => "Add call Messor before controller if page not found",
        "trigger" => "catalog/controller/error/not_found/before",
        // todo change action, before extension/module/messor/detect. Events now, controller | function
        "action" => "extension/module/messor|detect",
        "status" => 1,
        "sort_order" => 0
      )
    );
  }

  public function deleteEvents()
  {
    $this->load->model('setting/event');
    $this->model_setting_event->deleteEventByCode('messor');
  }

  // todo opencart 4 function no used
  public function getInstallId()
  {
    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `filename` LIKE '%messor%'");
    return $query;
  }

  // todo opencart 4 function no used
  public function deleteExtensionPathOfInstall($extension_install_id)
  {
    $this->db->query("DELETE FROM `" . DB_PREFIX . "extension_path` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
  }
}
