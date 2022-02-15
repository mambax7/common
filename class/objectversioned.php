<?php

use Xmf\Debug;
use Xmf\Module\Helper;
use Xmf\Module\Helper\Session;
use Xmf\Request;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
include_once dirname(__DIR__) . '/include/common.php';



abstract class CommonVersionedobject extends CommonObject
{

    var $itemName;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->commonHelper = \Xmf\Module\Helper::getHelper('common');
        //
        parent::__construct();
        //
        $this->initVar('prev_id', XOBJ_DTYPE_INT, null, false); // INT(10) UNSIGNED NULL DEFAULT NULL
        $this->initVar('next_id', XOBJ_DTYPE_INT, null, false); // INT(10) UNSIGNED NULL DEFAULT NULL
        $this->initVar('version', XOBJ_DTYPE_INT, 0, false); // INT(10) UNSIGNED NULL DEFAULT 0
        //
        $this->initVar('updated_uid', XOBJ_DTYPE_INT, 0, false); //  MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL
        $this->initVar('updated', XOBJ_DTYPE_OTHER, date(_DBTIMESTAMPSTRING), false); // DATETIME: 'YYYY-MM-DD HH:MM:SS'

    }

    /**
     * assign a value to a variable (also null is allowed)
     *
     * @access public
     * @param string $key   name of the variable to assign
     * @param mixed  $value value to assign
     * @param bool   $not_gpc
     */
    public function setVar($key, $value, $not_gpc = false)
    {
//TODO think about this method
        return parent::setVar($key, $value, $not_gpc);
    }

    /**
     * This method return values as array ready for html output
     *
     * @return  array
     */
    public function getValues($keys = null, $format = 's', $maxDepth = 1)
    {
        $values = parent::getValues($keys, $format, $maxDepth);
        $values['updated_uid_uname'] = \XoopsUserUtility::getUnameFromId($values['updated_uid']);
        $values['updated_date_data'] = \XoopsLocal::formatTimestamp($values['updated'], 'l');
        //
        return $values;
    }

    /**
     * This method get form values ready for database insert
     *
     * @return  array
     */
    public function setValues($default = [], $hash = 'default')
    {
//        $this->setVar('weight', Request::getInt('weight', 0, $hash));
//        $this->setVar('category_id', Request::getInt('category_id', 0, $hash));
        //
        return true;
    }
    
    public function getPrevObj() // get previous object version
    {
//TODO
    }
    public function getNextObj() // get next object version
    {
//TODO
    }
    public function getLatestObj() // get latest/most recent object version (next_id IS NULL)
    {
//TODO
    }
    public function getOldestObj() // get oldest/first object version (prev_id IS NULL)
    {
//TODO
    }
    

}

abstract class CommonVersionedobjectHandler extends XoopsPersistableObjectHandler
{

    /**
     * @var commonHelper
     */
    var $commonHelper;

    /**
     * @param null|object   $db
     */
    public function __construct($db = null, $table = '', $className = '', $keyName = '', $identifierName = '')
    {
        parent::__construct($db, $table, $className, $keyName, $identifierName);
        $this->commonHelper = \Xmf\Module\Helper::getHelper('common');
    }

    public function create($isNew = true)
    {
        $object = parent::create($isNew);
        return $object;
    }
    
    public function update($isNew = true)
    {
//        $object = parent::create($isNew);
//        return $object;
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
    public function insert(XoopsObject $object, $force = true)
    {
        if ($object->isNew()) {
            // created_uid, created
            $object->setVar('created_uid', !empty($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0);
            $object->setVar('modified_uid', 0);
            $object->setVar('created', date(_DBTIMESTAMPSTRING));
        } else {
            // modified_uid, modified
            $object->setVar('modified_uid', !empty($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0);
            $object->setVar('modified', date(_DBTIMESTAMPSTRING));
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
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }


    
    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
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
}
