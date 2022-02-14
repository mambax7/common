<?php

use Xmf\Debug;
use Xmf\Module\Helper;
use Xmf\Module\Helper\Session;
use Xmf\Request;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
include_once dirname(__DIR__) . '/include/common.php';

/**
 * Class CommonObject
 *
 * MySQL default table structure:
 * CREATE TABLE mod_common_default (
 *   id                      int(10) unsigned NOT NULL AUTO_INCREMENT,
 *   category_id             int(10) unsigned,
 *
 *   created_uid             mediumint(8) unsigned NOT NULL,
 *   modified_uid            mediumint(8) unsigned NOT NULL,
 *   created                 DATETIME,
 *   modified                DATETIME,
 *   PRIMARY KEY (id),
 * ) ENGINE=MyISAM;
 *
 * 
 *  * $breadcrumb = new common\breadcrumb(); */

abstract class CommonObject extends XoopsObject {

    /**
     * @var archivioHelper
     * @access public
     */
    var $archivioHelper;
    var $itemName;

    /**
     * constructor
     */
    public function __construct() {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->commonHelper = \Xmf\Module\Helper::getHelper('common');
        //
        parent::__construct();
        //
        $this->initVar('id', XOBJ_DTYPE_INT, null, false); // INT(10) UNSIGNED NOT NULL AUTO_INCREMENT
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false); // INT(10) UNSIGNED NULL DEFAULT 0
        $this->initVar('category_id', XOBJ_DTYPE_INT, 0, false); INT(10) UNSIGNED NOT NULL, is the parent category id, set to 0 (default) if a no category/root record
        //
        $this->initVar('created', XOBJ_DTYPE_OTHER, date(_DBTIMESTAMPSTRING), false); // DATETIME: 'YYYY-MM-DD HH:MM:SS'
        $this->initVar('created_uid', XOBJ_DTYPE_INT, 0, false); //  MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL
        //
        $this->initVar('modified', XOBJ_DTYPE_OTHER, date(_DBTIMESTAMPSTRING), false); // DATETIME: 'YYYY-MM-DD HH:MM:SS'
        $this->initVar('modified_uid', XOBJ_DTYPE_INT, 0, false); //  MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL
    }

    /**
     * assign a value to a variable (also null is allowed)
     *
     * @access public
     * @param string $key   name of the variable to assign
     * @param mixed  $value value to assign
     * @param bool   $not_gpc
     */
    public function setVar($key, $value, $not_gpc = false) {
        if (!empty($key) && isset($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] = & $value;
            $this->vars[$key]['not_gpc'] = $not_gpc;
            $this->vars[$key]['changed'] = true;
            $this->setDirty();
        }
        if (!empty($key) && is_null($value) && isset($this->vars[$key])) {
            $this->vars[$key]['value'] = null;
            $this->vars[$key]['not_gpc'] = $not_gpc;
            $this->vars[$key]['changed'] = false;
            $this->setDirty();
        }
    }

    /**
     * This method return values as array ready for html output
     *
     * @return  array
     */
    public function getValues($keys = null, $format = 's', $maxDepth = 1) {
        xoops_load('XoopsUserUtility');
        //
        $values = parent::getValues($keys, $format, $maxDepth);
        //        
        $dateTimeObj = DateTime::createFromFormat(_DBTIMESTAMPSTRING, $values['created']);
        $values['created_timestamp'] = ($dateTimeObj === false) ? '' : $dateTimeObj->getTimestamp();
        $values['created_date_data'] = \XoopsLocal::formatTimestamp($values['created_timestamp'], _DATESTRING);
        //
        $values['created_uid_uname'] = \XoopsUserUtility::getUnameFromId($values['created_uid']);
        //
        $dateTimeObj = DateTime::createFromFormat(_DBTIMESTAMPSTRING, $values['modified']);
        $values['modified_timestamp'] = ($dateTimeObj === false) ? '' : $dateTimeObj->getTimestamp();
        $values['modified_date_data'] = \XoopsLocal::formatTimestamp($values['modified_timestamp'], _DATESTRING);
        //
        $values['modified_uid_uname'] = \XoopsUserUtility::getUnameFromId($values['modified_uid']);
        //
        return $values;
    }

    /**
     * This method get form values ready for database insert
     *
     * @return  array
     */
    public function setValues($default = array(), $hash = 'default') {
        $this->setVar('weight', Request::getInt('weight', 0, $hash));
        $this->setVar('category_id', Request::getInt('category_id', 0, $hash));
        //
        return true;
    }

}

abstract class CommonObjectHandler extends XoopsPersistableObjectHandler {

    /**
     * @var commonHelper
     */
    var $commonHelper;

    /**
     * @param null|object   $db
     */
    public function __construct($db = null, $table = '', $className = '', $keyName = '', $identifierName = '') {
        parent::__construct($db, $table, $className, $keyName, $identifierName);
        $this->commonHelper = \Xmf\Module\Helper::getHelper('common');
    }

    public function create($isNew = true) {
        $object = parent::create($isNew);
        return $object;
    }

    /**
     * *#@+
     * Methods of write handler {@link XoopsObjectWrite}
     */

    /**
     * insert an object into the database
     *
     * @param  XoopsObject $object {@link XoopsObject} reference to object
     * @param  bool        $force  flag to force the query execution despite security settings
     * @return mixed       object ID
     */
    public function insert(XoopsObject $object, $force = true) {
        if ($object->isNew()) {
            // created_uid, created
            $object->setVar('created', date(_DBTIMESTAMPSTRING));
            $object->setVar('created_uid', !empty($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0);
            $object->setVar('modified_uid', 0);
        } else {
            // modified_uid, modified
            $object->setVar('modified', date(_DBTIMESTAMPSTRING));
            $object->setVar('modified_uid', !empty($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0);
        }
//        $handler = $this->loadHandler('write');
//        return $handler->insert($object, $force);
        $ret = parent::insert($object, $force);
        // handle null values
        $queryFunc = empty($force) ? 'query' : 'queryF';
        $vars = $object->getVars();
        foreach ($vars as $key => $value) {
            if (is_null($value['value']) && ($value['data_type'] != 0)) {
                $sql = "UPDATE `{$this->table}` SET `{$key}` = NULL WHERE `{$this->keyName}` = {$this->db->quote($object->getVar($this->keyName))}";
                if (!$result = $this->db->{$queryFunc}($sql)) {
                    //return false;
                }
            }
        }
        return $ret;
    }

    /**
     * get inserted id
     *
     * @param null
     * @return int reference to the object
     */
    public function getInsertId() {
        return $this->db->getInsertId();
    }

    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false) {
        return parent::deleteAll($criteria, $force, true); // delete ALWAYS as object
    }

    public function &getObjects(CriteriaElement $tempCriteria = null, $id_as_key = false, $as_object = true) {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        if (null !== $tempCriteria) {
            $criteria->add($tempCriteria);
            if (!empty($tempCriteria->getSort())) {
                $criteria->setSort($tempCriteria->getSort());
            }
            if (!empty($tempCriteria->getOrder())) {
                $criteria->setOrder($tempCriteria->getOrder());
            }
        }
        //
        return parent::getObjects($criteria, $id_as_key, $as_object);
    }

    public function &getAll(CriteriaElement $tempCriteria = null, $fields = null, $asObject = true, $id_as_key = true) {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        if (null !== $tempCriteria) {
            $criteria->add($tempCriteria);
            if (!empty($tempCriteria->getSort())) {
                $criteria->setSort($tempCriteria->getSort());
            }
            if (!empty($tempCriteria->getOrder())) {
                $criteria->setOrder($tempCriteria->getOrder());
            }
        }
        //
        return parent::getAll($criteria, $fields, $asObject, $id_as_key);
    }

    public function getList(CriteriaElement $tempCriteria = null, $limit = 0, $start = 0) {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        if (null !== $tempCriteria) {
            $criteria->add($tempCriteria);
            if (!empty($tempCriteria->getSort())) {
                $criteria->setSort($tempCriteria->getSort());
            }
            if (!empty($tempCriteria->getOrder())) {
                $criteria->setOrder($tempCriteria->getOrder());
            }
        }
        //
        return parent::getList($criteria, $limit, $start);
    }

    public function &getIds(CriteriaElement $tempCriteria = null) {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        if (null !== $tempCriteria) {
            $criteria->add($tempCriteria);
            if (!empty($tempCriteria->getSort())) {
                $criteria->setSort($tempCriteria->getSort());
            }
            if (!empty($tempCriteria->getOrder())) {
                $criteria->setOrder($tempCriteria->getOrder());
            }
        }
        //
        return parent::getIds($criteria);
    }

    public function &getValues(CriteriaElement $tempCriteria = null) {
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        if (null !== $tempCriteria) {
            $criteria->add($tempCriteria);
            if (!empty($tempCriteria->getSort())) {
                $criteria->setSort($tempCriteria->getSort());
            }
            if (!empty($tempCriteria->getOrder())) {
                $criteria->setOrder($tempCriteria->getOrder());
            }
        }
        //
        $objs = $this->getObjects($criteria, true, true); // id as key, as object
        $values = [];
        foreach ($objs as $id => $obj) {
            $value = $obj->getValues();
            $values[$id] = $value;
        }
        //
        return $values;
    }

    /**
     * clone an existing object but as s new object
     *
     * @param  XoopsObject $object {@link XoopsObject} reference to object
     * @return XoopsObject 
     */
    public function clone(XoopsObject $object) {
        $notClonableKeys = [$this->keyName];

        $class = get_class($object);
        $cloneObject = new $this->className();
        foreach ($object->vars as $key => $var) {
            if (in_array($key, $notClonableKeys))
                continue;
            $cloneObject->setVar($key, $var['value']);
        }
        // need this to notify the handler class that this is a newly created object
        $cloneObject->setNew();
        //
        return $cloneObject;
    }

}
