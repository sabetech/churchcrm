<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\FamilyCustomMaster as ChildFamilyCustomMaster;
use ChurchCRM\FamilyCustomMasterQuery as ChildFamilyCustomMasterQuery;
use ChurchCRM\Map\FamilyCustomMasterTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'family_custom_master' table.
 *
 *
 *
 * @method     ChildFamilyCustomMasterQuery orderByOrder($order = Criteria::ASC) Order by the fam_custom_Order column
 * @method     ChildFamilyCustomMasterQuery orderByField($order = Criteria::ASC) Order by the fam_custom_Field column
 * @method     ChildFamilyCustomMasterQuery orderByName($order = Criteria::ASC) Order by the fam_custom_Name column
 * @method     ChildFamilyCustomMasterQuery orderByCustomSpecial($order = Criteria::ASC) Order by the fam_custom_Special column
 * @method     ChildFamilyCustomMasterQuery orderByFieldSecurity($order = Criteria::ASC) Order by the fam_custom_FieldSec column
 * @method     ChildFamilyCustomMasterQuery orderByTypeId($order = Criteria::ASC) Order by the type_ID column
 *
 * @method     ChildFamilyCustomMasterQuery groupByOrder() Group by the fam_custom_Order column
 * @method     ChildFamilyCustomMasterQuery groupByField() Group by the fam_custom_Field column
 * @method     ChildFamilyCustomMasterQuery groupByName() Group by the fam_custom_Name column
 * @method     ChildFamilyCustomMasterQuery groupByCustomSpecial() Group by the fam_custom_Special column
 * @method     ChildFamilyCustomMasterQuery groupByFieldSecurity() Group by the fam_custom_FieldSec column
 * @method     ChildFamilyCustomMasterQuery groupByTypeId() Group by the type_ID column
 *
 * @method     ChildFamilyCustomMasterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFamilyCustomMasterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFamilyCustomMasterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFamilyCustomMasterQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildFamilyCustomMasterQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildFamilyCustomMasterQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildFamilyCustomMaster findOne(ConnectionInterface $con = null) Return the first ChildFamilyCustomMaster matching the query
 * @method     ChildFamilyCustomMaster findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFamilyCustomMaster matching the query, or a new ChildFamilyCustomMaster object populated from the query conditions when no match is found
 *
 * @method     ChildFamilyCustomMaster findOneByOrder(int $fam_custom_Order) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Order column
 * @method     ChildFamilyCustomMaster findOneByField(string $fam_custom_Field) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Field column
 * @method     ChildFamilyCustomMaster findOneByName(string $fam_custom_Name) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Name column
 * @method     ChildFamilyCustomMaster findOneByCustomSpecial(int $fam_custom_Special) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Special column
 * @method     ChildFamilyCustomMaster findOneByFieldSecurity(int $fam_custom_FieldSec) Return the first ChildFamilyCustomMaster filtered by the fam_custom_FieldSec column
 * @method     ChildFamilyCustomMaster findOneByTypeId(int $type_ID) Return the first ChildFamilyCustomMaster filtered by the type_ID column *

 * @method     ChildFamilyCustomMaster requirePk($key, ConnectionInterface $con = null) Return the ChildFamilyCustomMaster by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFamilyCustomMaster requireOne(ConnectionInterface $con = null) Return the first ChildFamilyCustomMaster matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFamilyCustomMaster requireOneByOrder(int $fam_custom_Order) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFamilyCustomMaster requireOneByField(string $fam_custom_Field) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Field column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFamilyCustomMaster requireOneByName(string $fam_custom_Name) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFamilyCustomMaster requireOneByCustomSpecial(int $fam_custom_Special) Return the first ChildFamilyCustomMaster filtered by the fam_custom_Special column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFamilyCustomMaster requireOneByFieldSecurity(int $fam_custom_FieldSec) Return the first ChildFamilyCustomMaster filtered by the fam_custom_FieldSec column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFamilyCustomMaster requireOneByTypeId(int $type_ID) Return the first ChildFamilyCustomMaster filtered by the type_ID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFamilyCustomMaster[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFamilyCustomMaster objects based on current ModelCriteria
 * @method     ChildFamilyCustomMaster[]|ObjectCollection findByOrder(int $fam_custom_Order) Return ChildFamilyCustomMaster objects filtered by the fam_custom_Order column
 * @method     ChildFamilyCustomMaster[]|ObjectCollection findByField(string $fam_custom_Field) Return ChildFamilyCustomMaster objects filtered by the fam_custom_Field column
 * @method     ChildFamilyCustomMaster[]|ObjectCollection findByName(string $fam_custom_Name) Return ChildFamilyCustomMaster objects filtered by the fam_custom_Name column
 * @method     ChildFamilyCustomMaster[]|ObjectCollection findByCustomSpecial(int $fam_custom_Special) Return ChildFamilyCustomMaster objects filtered by the fam_custom_Special column
 * @method     ChildFamilyCustomMaster[]|ObjectCollection findByFieldSecurity(int $fam_custom_FieldSec) Return ChildFamilyCustomMaster objects filtered by the fam_custom_FieldSec column
 * @method     ChildFamilyCustomMaster[]|ObjectCollection findByTypeId(int $type_ID) Return ChildFamilyCustomMaster objects filtered by the type_ID column
 * @method     ChildFamilyCustomMaster[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FamilyCustomMasterQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ChurchCRM\Base\FamilyCustomMasterQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ChurchCRM\\FamilyCustomMaster', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFamilyCustomMasterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFamilyCustomMasterQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFamilyCustomMasterQuery) {
            return $criteria;
        }
        $query = new ChildFamilyCustomMasterQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFamilyCustomMaster|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FamilyCustomMasterTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = FamilyCustomMasterTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFamilyCustomMaster A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT fam_custom_Order, fam_custom_Field, fam_custom_Name, fam_custom_Special, fam_custom_FieldSec, type_ID FROM family_custom_master WHERE fam_custom_Field = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFamilyCustomMaster $obj */
            $obj = new ChildFamilyCustomMaster();
            $obj->hydrate($row);
            FamilyCustomMasterTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildFamilyCustomMaster|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the fam_custom_Order column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE fam_custom_Order = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE fam_custom_Order IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE fam_custom_Order > 12
     * </code>
     *
     * @param     mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByOrder($order = null, $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_ORDER, $order, $comparison);
    }

    /**
     * Filter the query on the fam_custom_Field column
     *
     * Example usage:
     * <code>
     * $query->filterByField('fooValue');   // WHERE fam_custom_Field = 'fooValue'
     * $query->filterByField('%fooValue%', Criteria::LIKE); // WHERE fam_custom_Field LIKE '%fooValue%'
     * </code>
     *
     * @param     string $field The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByField($field = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($field)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD, $field, $comparison);
    }

    /**
     * Filter the query on the fam_custom_Name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE fam_custom_Name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE fam_custom_Name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the fam_custom_Special column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomSpecial(1234); // WHERE fam_custom_Special = 1234
     * $query->filterByCustomSpecial(array(12, 34)); // WHERE fam_custom_Special IN (12, 34)
     * $query->filterByCustomSpecial(array('min' => 12)); // WHERE fam_custom_Special > 12
     * </code>
     *
     * @param     mixed $customSpecial The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByCustomSpecial($customSpecial = null, $comparison = null)
    {
        if (is_array($customSpecial)) {
            $useMinMax = false;
            if (isset($customSpecial['min'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_SPECIAL, $customSpecial['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customSpecial['max'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_SPECIAL, $customSpecial['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_SPECIAL, $customSpecial, $comparison);
    }

    /**
     * Filter the query on the fam_custom_FieldSec column
     *
     * Example usage:
     * <code>
     * $query->filterByFieldSecurity(1234); // WHERE fam_custom_FieldSec = 1234
     * $query->filterByFieldSecurity(array(12, 34)); // WHERE fam_custom_FieldSec IN (12, 34)
     * $query->filterByFieldSecurity(array('min' => 12)); // WHERE fam_custom_FieldSec > 12
     * </code>
     *
     * @param     mixed $fieldSecurity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByFieldSecurity($fieldSecurity = null, $comparison = null)
    {
        if (is_array($fieldSecurity)) {
            $useMinMax = false;
            if (isset($fieldSecurity['min'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELDSEC, $fieldSecurity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fieldSecurity['max'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELDSEC, $fieldSecurity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELDSEC, $fieldSecurity, $comparison);
    }

    /**
     * Filter the query on the type_ID column
     *
     * Example usage:
     * <code>
     * $query->filterByTypeId(1234); // WHERE type_ID = 1234
     * $query->filterByTypeId(array(12, 34)); // WHERE type_ID IN (12, 34)
     * $query->filterByTypeId(array('min' => 12)); // WHERE type_ID > 12
     * </code>
     *
     * @param     mixed $typeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function filterByTypeId($typeId = null, $comparison = null)
    {
        if (is_array($typeId)) {
            $useMinMax = false;
            if (isset($typeId['min'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_TYPE_ID, $typeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($typeId['max'])) {
                $this->addUsingAlias(FamilyCustomMasterTableMap::COL_TYPE_ID, $typeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FamilyCustomMasterTableMap::COL_TYPE_ID, $typeId, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFamilyCustomMaster $familyCustomMaster Object to remove from the list of results
     *
     * @return $this|ChildFamilyCustomMasterQuery The current query, for fluid interface
     */
    public function prune($familyCustomMaster = null)
    {
        if ($familyCustomMaster) {
            $this->addUsingAlias(FamilyCustomMasterTableMap::COL_FAM_CUSTOM_FIELD, $familyCustomMaster->getField(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the family_custom_master table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FamilyCustomMasterTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FamilyCustomMasterTableMap::clearInstancePool();
            FamilyCustomMasterTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FamilyCustomMasterTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FamilyCustomMasterTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FamilyCustomMasterTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FamilyCustomMasterTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FamilyCustomMasterQuery
