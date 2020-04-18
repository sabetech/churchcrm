<?php

namespace ChurchCRM\Map;

use ChurchCRM\FamilyCustomMaster;
use ChurchCRM\FamilyCustomMasterQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'family_custom_master' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class FamilyCustomMasterTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'ChurchCRM.Map.FamilyCustomMasterTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'family_custom_master';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\ChurchCRM\\FamilyCustomMaster';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'ChurchCRM.FamilyCustomMaster';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the fam_custom_Order field
     */
    const COL_FAM_CUSTOM_ORDER = 'family_custom_master.fam_custom_Order';

    /**
     * the column name for the fam_custom_Field field
     */
    const COL_FAM_CUSTOM_FIELD = 'family_custom_master.fam_custom_Field';

    /**
     * the column name for the fam_custom_Name field
     */
    const COL_FAM_CUSTOM_NAME = 'family_custom_master.fam_custom_Name';

    /**
     * the column name for the fam_custom_Special field
     */
    const COL_FAM_CUSTOM_SPECIAL = 'family_custom_master.fam_custom_Special';

    /**
     * the column name for the fam_custom_FieldSec field
     */
    const COL_FAM_CUSTOM_FIELDSEC = 'family_custom_master.fam_custom_FieldSec';

    /**
     * the column name for the type_ID field
     */
    const COL_TYPE_ID = 'family_custom_master.type_ID';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Order', 'Field', 'Name', 'CustomSpecial', 'FieldSecurity', 'TypeId', ),
        self::TYPE_CAMELNAME     => array('order', 'field', 'name', 'customSpecial', 'fieldSecurity', 'typeId', ),
        self::TYPE_COLNAME       => array(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_ORDER, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_NAME, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_SPECIAL, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELDSEC, FamilyCustomMasterTableMap::COL_TYPE_ID, ),
        self::TYPE_FIELDNAME     => array('fam_custom_Order', 'fam_custom_Field', 'fam_custom_Name', 'fam_custom_Special', 'fam_custom_FieldSec', 'type_ID', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Order' => 0, 'Field' => 1, 'Name' => 2, 'CustomSpecial' => 3, 'FieldSecurity' => 4, 'TypeId' => 5, ),
        self::TYPE_CAMELNAME     => array('order' => 0, 'field' => 1, 'name' => 2, 'customSpecial' => 3, 'fieldSecurity' => 4, 'typeId' => 5, ),
        self::TYPE_COLNAME       => array(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_ORDER => 0, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD => 1, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_NAME => 2, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_SPECIAL => 3, FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELDSEC => 4, FamilyCustomMasterTableMap::COL_TYPE_ID => 5, ),
        self::TYPE_FIELDNAME     => array('fam_custom_Order' => 0, 'fam_custom_Field' => 1, 'fam_custom_Name' => 2, 'fam_custom_Special' => 3, 'fam_custom_FieldSec' => 4, 'type_ID' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('family_custom_master');
        $this->setPhpName('FamilyCustomMaster');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ChurchCRM\\FamilyCustomMaster');
        $this->setPackage('ChurchCRM');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('fam_custom_Order', 'Order', 'SMALLINT', true, null, 0);
        $this->addPrimaryKey('fam_custom_Field', 'Field', 'VARCHAR', true, 5, '');
        $this->addColumn('fam_custom_Name', 'Name', 'VARCHAR', true, 40, '');
        $this->addColumn('fam_custom_Special', 'CustomSpecial', 'SMALLINT', false, 8, null);
        $this->addColumn('fam_custom_FieldSec', 'FieldSecurity', 'TINYINT', true, null, 1);
        $this->addColumn('type_ID', 'TypeId', 'TINYINT', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Field', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Field', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Field', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Field', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Field', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Field', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('Field', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? FamilyCustomMasterTableMap::CLASS_DEFAULT : FamilyCustomMasterTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (FamilyCustomMaster object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = FamilyCustomMasterTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FamilyCustomMasterTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FamilyCustomMasterTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FamilyCustomMasterTableMap::OM_CLASS;
            /** @var FamilyCustomMaster $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FamilyCustomMasterTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = FamilyCustomMasterTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FamilyCustomMasterTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var FamilyCustomMaster $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FamilyCustomMasterTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_ORDER);
            $criteria->addSelectColumn(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD);
            $criteria->addSelectColumn(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_NAME);
            $criteria->addSelectColumn(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_SPECIAL);
            $criteria->addSelectColumn(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELDSEC);
            $criteria->addSelectColumn(FamilyCustomMasterTableMap::COL_TYPE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.fam_custom_Order');
            $criteria->addSelectColumn($alias . '.fam_custom_Field');
            $criteria->addSelectColumn($alias . '.fam_custom_Name');
            $criteria->addSelectColumn($alias . '.fam_custom_Special');
            $criteria->addSelectColumn($alias . '.fam_custom_FieldSec');
            $criteria->addSelectColumn($alias . '.type_ID');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(FamilyCustomMasterTableMap::DATABASE_NAME)->getTable(FamilyCustomMasterTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FamilyCustomMasterTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(FamilyCustomMasterTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new FamilyCustomMasterTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a FamilyCustomMaster or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or FamilyCustomMaster object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FamilyCustomMasterTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ChurchCRM\FamilyCustomMaster) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FamilyCustomMasterTableMap::DATABASE_NAME);
            $criteria->add(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD, (array) $values, Criteria::IN);
        }

        $query = FamilyCustomMasterQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FamilyCustomMasterTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FamilyCustomMasterTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the family_custom_master table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return FamilyCustomMasterQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a FamilyCustomMaster or Criteria object.
     *
     * @param mixed               $criteria Criteria or FamilyCustomMaster object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FamilyCustomMasterTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from FamilyCustomMaster object
        }


        // Set the correct dbName
        $query = FamilyCustomMasterQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // FamilyCustomMasterTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
FamilyCustomMasterTableMap::buildTableMap();
