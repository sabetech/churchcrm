<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\RecordProperty as ChildRecordProperty;
use ChurchCRM\RecordPropertyQuery as ChildRecordPropertyQuery;
use ChurchCRM\Map\RecordPropertyTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'record2property_r2p' table.
 *
 * This table indicates which persons, families, or groups are assigned specific properties and what the values of those properties are.
 *
 * @method     ChildRecordPropertyQuery orderByPropertyId($order = Criteria::ASC) Order by the r2p_pro_ID column
 * @method     ChildRecordPropertyQuery orderByRecordId($order = Criteria::ASC) Order by the r2p_record_ID column
 * @method     ChildRecordPropertyQuery orderByPropertyValue($order = Criteria::ASC) Order by the r2p_Value column
 *
 * @method     ChildRecordPropertyQuery groupByPropertyId() Group by the r2p_pro_ID column
 * @method     ChildRecordPropertyQuery groupByRecordId() Group by the r2p_record_ID column
 * @method     ChildRecordPropertyQuery groupByPropertyValue() Group by the r2p_Value column
 *
 * @method     ChildRecordPropertyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRecordPropertyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRecordPropertyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRecordPropertyQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildRecordPropertyQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildRecordPropertyQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildRecordPropertyQuery leftJoinProperty($relationAlias = null) Adds a LEFT JOIN clause to the query using the Property relation
 * @method     ChildRecordPropertyQuery rightJoinProperty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Property relation
 * @method     ChildRecordPropertyQuery innerJoinProperty($relationAlias = null) Adds a INNER JOIN clause to the query using the Property relation
 *
 * @method     ChildRecordPropertyQuery joinWithProperty($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Property relation
 *
 * @method     ChildRecordPropertyQuery leftJoinWithProperty() Adds a LEFT JOIN clause and with to the query using the Property relation
 * @method     ChildRecordPropertyQuery rightJoinWithProperty() Adds a RIGHT JOIN clause and with to the query using the Property relation
 * @method     ChildRecordPropertyQuery innerJoinWithProperty() Adds a INNER JOIN clause and with to the query using the Property relation
 *
 * @method     \ChurchCRM\PropertyQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildRecordProperty findOne(ConnectionInterface $con = null) Return the first ChildRecordProperty matching the query
 * @method     ChildRecordProperty findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRecordProperty matching the query, or a new ChildRecordProperty object populated from the query conditions when no match is found
 *
 * @method     ChildRecordProperty findOneByPropertyId(int $r2p_pro_ID) Return the first ChildRecordProperty filtered by the r2p_pro_ID column
 * @method     ChildRecordProperty findOneByRecordId(int $r2p_record_ID) Return the first ChildRecordProperty filtered by the r2p_record_ID column
 * @method     ChildRecordProperty findOneByPropertyValue(string $r2p_Value) Return the first ChildRecordProperty filtered by the r2p_Value column *

 * @method     ChildRecordProperty requirePk($key, ConnectionInterface $con = null) Return the ChildRecordProperty by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecordProperty requireOne(ConnectionInterface $con = null) Return the first ChildRecordProperty matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRecordProperty requireOneByPropertyId(int $r2p_pro_ID) Return the first ChildRecordProperty filtered by the r2p_pro_ID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecordProperty requireOneByRecordId(int $r2p_record_ID) Return the first ChildRecordProperty filtered by the r2p_record_ID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecordProperty requireOneByPropertyValue(string $r2p_Value) Return the first ChildRecordProperty filtered by the r2p_Value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRecordProperty[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildRecordProperty objects based on current ModelCriteria
 * @method     ChildRecordProperty[]|ObjectCollection findByPropertyId(int $r2p_pro_ID) Return ChildRecordProperty objects filtered by the r2p_pro_ID column
 * @method     ChildRecordProperty[]|ObjectCollection findByRecordId(int $r2p_record_ID) Return ChildRecordProperty objects filtered by the r2p_record_ID column
 * @method     ChildRecordProperty[]|ObjectCollection findByPropertyValue(string $r2p_Value) Return ChildRecordProperty objects filtered by the r2p_Value column
 * @method     ChildRecordProperty[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RecordPropertyQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ChurchCRM\Base\RecordPropertyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ChurchCRM\\RecordProperty', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRecordPropertyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRecordPropertyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildRecordPropertyQuery) {
            return $criteria;
        }
        $query = new ChildRecordPropertyQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$r2p_pro_ID, $r2p_record_ID] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildRecordProperty|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RecordPropertyTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = RecordPropertyTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildRecordProperty A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT r2p_pro_ID, r2p_record_ID, r2p_Value FROM record2property_r2p WHERE r2p_pro_ID = :p0 AND r2p_record_ID = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildRecordProperty $obj */
            $obj = new ChildRecordProperty();
            $obj->hydrate($row);
            RecordPropertyTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildRecordProperty|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_PRO_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_RECORD_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(RecordPropertyTableMap::COL_R2P_PRO_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(RecordPropertyTableMap::COL_R2P_RECORD_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the r2p_pro_ID column
     *
     * Example usage:
     * <code>
     * $query->filterByPropertyId(1234); // WHERE r2p_pro_ID = 1234
     * $query->filterByPropertyId(array(12, 34)); // WHERE r2p_pro_ID IN (12, 34)
     * $query->filterByPropertyId(array('min' => 12)); // WHERE r2p_pro_ID > 12
     * </code>
     *
     * @see       filterByProperty()
     *
     * @param     mixed $propertyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function filterByPropertyId($propertyId = null, $comparison = null)
    {
        if (is_array($propertyId)) {
            $useMinMax = false;
            if (isset($propertyId['min'])) {
                $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_PRO_ID, $propertyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($propertyId['max'])) {
                $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_PRO_ID, $propertyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_PRO_ID, $propertyId, $comparison);
    }

    /**
     * Filter the query on the r2p_record_ID column
     *
     * Example usage:
     * <code>
     * $query->filterByRecordId(1234); // WHERE r2p_record_ID = 1234
     * $query->filterByRecordId(array(12, 34)); // WHERE r2p_record_ID IN (12, 34)
     * $query->filterByRecordId(array('min' => 12)); // WHERE r2p_record_ID > 12
     * </code>
     *
     * @param     mixed $recordId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function filterByRecordId($recordId = null, $comparison = null)
    {
        if (is_array($recordId)) {
            $useMinMax = false;
            if (isset($recordId['min'])) {
                $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_RECORD_ID, $recordId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($recordId['max'])) {
                $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_RECORD_ID, $recordId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_RECORD_ID, $recordId, $comparison);
    }

    /**
     * Filter the query on the r2p_Value column
     *
     * Example usage:
     * <code>
     * $query->filterByPropertyValue('fooValue');   // WHERE r2p_Value = 'fooValue'
     * $query->filterByPropertyValue('%fooValue%', Criteria::LIKE); // WHERE r2p_Value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $propertyValue The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function filterByPropertyValue($propertyValue = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($propertyValue)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordPropertyTableMap::COL_R2P_VALUE, $propertyValue, $comparison);
    }

    /**
     * Filter the query by a related \ChurchCRM\Property object
     *
     * @param \ChurchCRM\Property|ObjectCollection $property The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function filterByProperty($property, $comparison = null)
    {
        if ($property instanceof \ChurchCRM\Property) {
            return $this
                ->addUsingAlias(RecordPropertyTableMap::COL_R2P_PRO_ID, $property->getProId(), $comparison);
        } elseif ($property instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RecordPropertyTableMap::COL_R2P_PRO_ID, $property->toKeyValue('PrimaryKey', 'ProId'), $comparison);
        } else {
            throw new PropelException('filterByProperty() only accepts arguments of type \ChurchCRM\Property or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Property relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function joinProperty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Property');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Property');
        }

        return $this;
    }

    /**
     * Use the Property relation Property object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\PropertyQuery A secondary query class using the current class as primary query
     */
    public function usePropertyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProperty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Property', '\ChurchCRM\PropertyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRecordProperty $recordProperty Object to remove from the list of results
     *
     * @return $this|ChildRecordPropertyQuery The current query, for fluid interface
     */
    public function prune($recordProperty = null)
    {
        if ($recordProperty) {
            $this->addCond('pruneCond0', $this->getAliasedColName(RecordPropertyTableMap::COL_R2P_PRO_ID), $recordProperty->getPropertyId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(RecordPropertyTableMap::COL_R2P_RECORD_ID), $recordProperty->getRecordId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the record2property_r2p table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RecordPropertyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RecordPropertyTableMap::clearInstancePool();
            RecordPropertyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(RecordPropertyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RecordPropertyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            RecordPropertyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RecordPropertyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // RecordPropertyQuery
