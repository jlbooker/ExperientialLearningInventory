<?php
class Sysinventory_System {
    var $id;
    var $location_id;
    var $department_id;
    var $room_number;
    var $model;
    var $hdd;
    var $proc;
    var $ram;
    var $dual_mon;
    var $mac;
    var $printer;
    var $staff_member;
    var $username;
    var $telephone;
    var $docking_stand;
    var $deep_freeze;
    var $purchase_date;
    var $vlan;
    var $reformat;
    var $notes;

    function Sysinventory_System($sysid=0) {
        if ($sysid == 0) return;

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysid);
        $result = $db->loadObject($this);
    }

    function save() {
        $db = new PHPWS_DB('sysinventory_system');
        $result = $db->saveObject($this);

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        return $result;
    }

    function deleteSystem($sysId) {
        if (!isset($sysId)) {
            return 'No System ID set.';
        }

        $db = new PHPWS_DB('sysinventory_system');
        $db->addWhere('id',$sysId);
        $result = $db->delete();

        if(PEAR::isError($result)) {
            PHPWS_Error::log($result);
        }
        if($db->affectedRows() == 1) {
            return 'true';
        }else{
            return 'Database Error';
        }
    }
    function get_row_tags() {
       $rowTags = array();

       // edit and delete links
       $rowTags['EDIT'] = PHPWS_Text::moduleLink('Edit','sysinventory',array('action'=>'edit_system','id'=>$this->id,'redir'=>'1'));
       $rowTags['DELETE'] = '<a href="javascript:void(0);" class="delete" id=' . $this->id . '>Delete</a>'; 
       // get department and location names 
       $rowTags['DEPARTMENT'] = $this->getDepartment();
       $rowTags['LOCATION'] = $this->getLocation();

       return $rowTags;
    }

    public function report_row() {
        $row['Department']       = $this->getDepartment();
        $row['Location']         = $this->getLocation();
        $row['Room_Number']      = $this->room_number;
        $row['Model']            = $this->model;
        $row['HDD']              = $this->hdd;
        $row['Processor']        = $this->proc;
        $row['RAM']              = $this->ram;
        $row['Dual_Monitor']     = $this->dual_mon;
        $row['MAC']              = $this->mac;
        $row['Printer']          = $this->printer;
        $row['Staff_Member']     = $this->printer;
        $row['Username']         = $this->username;
        $row['Telephone']        = $this->telephone;
        $row['Docking_Stand']    = $this->docking_stand;
        $row['Deep_Freeze']      = $this->deep_freeze;
        $row['Purchase_Date']    = $this->purchase_date;
        $row['VLAN']             = $this->vlan;
        $row['Reformat']         = $this->reformat;
        $row['Notes']            = $this->notes;

        return $row;
    }

    function getDepartment() {
        $db = new PHPWS_DB('sysinventory_department');
        $db->addWhere('id',$this->department_id);
        $dept = $db->select('row');
        return $dept['description'];
    }

    function getLocation() {
        $db = new PHPWS_DB('sysinventory_location');
        $db->addWhere('id',$this->location_id);
        $loc = $db->select('row');
        return $loc['description'];
    }

    function addSystem($id) {
        PHPWS_Core::initModClass('sysinventory','Sysinventory_System.php');
        if(!isset($_REQUEST['dual_mon'])) $_REQUEST['dual_mon'] = 'no';
        if(!isset($_REQUEST['docking_stand'])) $_REQUEST['docking_stand'] = 'no';
        if(!isset($_REQUEST['deep_freeze'])) $_REQUEST['deep_freeze'] = 'no';
        if(!isset($_REQUEST['reformat'])) $_REQUEST['reformat'] = 'no';


        $sys = new Sysinventory_System;

        $sys->id                  = $id;
        $sys->department_id       = $_REQUEST['department_id'];
        $sys->location_id         = $_REQUEST['location_id'];
        $sys->room_number         = $_REQUEST['room_number'];
        $sys->model               = $_REQUEST['model'];
        $sys->hdd                 = $_REQUEST['hdd'];
        $sys->proc                = $_REQUEST['proc'];
        $sys->ram                 = $_REQUEST['ram'];
        $sys->dual_mon            = $_REQUEST['dual_mon'];
        $sys->mac                 = $_REQUEST['mac'];
        $sys->printer             = $_REQUEST['printer'];
        $sys->staff_member        = $_REQUEST['staff_member'];
        $sys->username            = $_REQUEST['username'];
        $sys->telephone           = $_REQUEST['telephone'];
        $sys->docking_stand       = $_REQUEST['docking_stand'];
        $sys->deep_freeze         = $_REQUEST['deep_freeze'];
        $sys->purchase_date       = $_REQUEST['purchase_date'];
        $sys->vlan                = $_REQUEST['vlan'];
        $sys->reformat            = $_REQUEST['reformat'];
        $sys->notes               = $_REQUEST['notes'];

        $result = $sys->save();
        if (PEAR::isError($result)) {
            PHPWS_Core::initModClass('sysinventory','Sysinventory_Menu.php');
            Sysinventory_Menu::showMenu($result);
        }
        PHPWS_Core::reroute('index.php?module=sysinventory&action=report&redir=1');
    }

}
?>