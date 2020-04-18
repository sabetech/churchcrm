<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\KioskAssignment as ChildKioskAssignment;
use ChurchCRM\KioskAssignmentQuery as ChildKioskAssignmentQuery;
use ChurchCRM\Map\KioskAssignmentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'kioskassginment_kasm' table.
 *
 *
 *
 * @method     ChildKioskAssignmentQuery orderById($order = Criteria::ASC) Order by the kasm_ID column
 * @method     ChildKioskAssignmentQuery orderByKioskId($order = Criteria::ASC) Order by the kasm_kdevId column
 * @method     ChildKioskAssignmentQuery orderByAssignmentType($order = Criteria::ASC) Order by the kasm_AssignmentType column
 * @method     ChildKioskAssignmentQuery orderByEventId($order = Criteria::ASC) Order by the kasm_EventId column
 *
 * @method     ChildKioskAssignmentQuery groupById() Group by the kasm_ID column
 * @method     ChildKioskAssignmentQuery groupByKioskId() Group by the kasm_kdevId column
 * @method     ChildKioskAssignmentQuery groupByAssignmentType() Group by the kasm_AssignmentType column
 * @method     ChildKioskAssignmentQuery groupByEventId() Group by the kasm_EventId column
 *
 * @method     ChildKioskAssignmentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildKioskAssignmentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildKioskAssignmentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildKioskAssignmentQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildKioskAssignmentQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildKioskAssignmentQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildKioskAssignmentQuery leftJoinKioskDevice($relationAlias = null) Adds a LEFT JOIN clause to the query using the KioskDevice relation
 * @method     ChildKioskAssignmentQuery rightJoinKioskDevice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the KioskDevice relation
 * @method     ChildKioskAssignmentQuery innerJoinKioskDevice($relationAlias = null) Adds a INNER JOIN clause to the query using the KioskDevice relation
 *
 * @method     ChildKioskAssignmentQuery joinWithKioskDevice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the KioskDevice relation
 *
 * @method     ChildKioskAssignmentQuery leftJoinWithKioskDevice() Adds a LEFT JOIN clause and with to the query using the KioskDevice relation
 * @method     ChildKioskAssignmentQuery rightJoinWithKioskDevice() Adds a RIGHT JOIN clause and with to the query using the KioskDevice relation
 * @method     ChildKioskAssignmentQuery innerJoinWithKioskDevice() Adds a INNER JOIN clause and with to the query using the KioskDevice relation
 *
 * @method     ChildKioskAssignmentQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildKioskAssignmentQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildKioskAssignmentQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildKioskAssignmentQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildKioskAssignmentQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildKioskAssignmentQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildKioskAssignmentQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     \ChurchCRM\KioskDeviceQuery|\ChurchCRM\EventQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildKioskAssignment findOne(ConnectionInterface $con = null) Return the first ChildKioskAssignment matching the query
 * @method     ChildKioskAssignment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildKioskAssignment matching the query, or a new ChildKioskAssignment object populated from the query conditions when no match is found
 *
 * @method     ChildKioskAssignment findOneById(int $kasm_ID) Return the first ChildKioskAssignment filtered by the kasm_ID column
 * @method     ChildKioskAssignment findOneByKioskId(int $kasm_kdevId) Return the first ChildKioskAssignment filtered by the kasm_kdevId column
 * @method     ChildKioskAssignment findOneByAssignmentType(int $kasm_AssignmentType) Return the first ChildKioskAssignment filtered by the kasm_AssignmentType column
 * @method     ChildKioskAssignment findOneByEventId(int $kasm_EventId) Return the first ChildKioskAssignment filtered by the kasm_EventId column *

 * @method     ChildKioskAssignment requirePk($key, ConnectionInterface $con = null) Return the ChildKioskAssignment by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildKioskAssignment requireOne(ConnectionInterface $con = null) Return the first ChildKioskAssignment matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildKioskAssignment requireOneById(int $kasm_ID) Return the first ChildKioskAssignment filtered by the kasm_ID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildKioskAssignment requireOneByKioskId(int $kasm_kdevId) Return the first ChildKioskAssignment filtered by the kasm_kdevId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildKioskAssignment requireOneByAssignmentType(int $kasm_AssignmentType) Return the first ChildKioskAssignment filtered by the kasm_AssignmentType column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildKioskAssignment requireOneByEventId(int $kasm_EventId) Return the first ChildKioskAssignment filtered by the kasm_EventId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildKioskAssignment[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildKioskAssignment objects based on current ModelCriteria
 * @method     ChildKioskAssignment[]|ObjectCollection findById(int $kasm_ID) Return ChildKioskAssignment objects filtered by the kasm_ID column
 * @method     ChildKioskAssignment[]|ObjectCollection findByKioskId(int $kasm_kdevId) Return ChildKioskAssignment objects filtered by the kasm_kdevId column
 * @method     ChildKioskAssignment[]|ObjectCollection findByAssignmentType(int $kasm_AssignmentType) Return ChildKioskAssignment objects filtered by the kasm_AssignmentType column
 * @method     ChildKioskAssignment[]|ObjectCollection findByEventId(int $kasm_EventId) Return ChildKioskAssignment objects filtered by the kasm_EventId column
 * @method     ChildKioskAssignment[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class KioskAssignmentQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ChurchCRM\Base\KioskAssignmentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ChurchCRM\\KioskAssignment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildKioskAssignmentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildKioskAssignmentQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildKioskAssignmentQuery) {
            return $criteria;
        }
        $query = new ChildKioskAssignmentQuery();
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
     * @return ChildKioskAssignment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(KioskAssignmentTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = KioskAssignmentTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildKioskAssignment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT kasm_ID, kasm_kdevId, kasm_AssignmentType, kasm_EventId FROM kioskassginment_kasm WHERE kasm_ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildKioskAssignment $obj */
            $obj = new ChildKioskAssignment();
            $obj->hydrate($row);
            KioskAssignmentTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildKioskAssignment|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the kasm_ID column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE kasm_ID = 1234
     * $query->filterById(array(12, 34)); // WHERE kasm_ID IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE kasm_ID > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ID, $id, $comparison);
    }

    /**
     * Filter the query on the kasm_kdevId column
     *
     * Example usage:
     * <code>
     * $query->filterByKioskId(1234); // WHERE kasm_kdevId = 1234
     * $query->filterByKioskId(array(12, 34)); // WHERE kasm_kdevId IN (12, 34)
     * $query->filterByKioskId(array('min' => 12)); // WHERE kasm_kdevId > 12
     * </code>
     *
     * @see       filterByKioskDevice()
     *
     * @param     mixed $kioskId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterByKioskId($kioskId = null, $comparison = null)
    {
        if (is_array($kioskId)) {
            $useMinMax = false;
            if (isset($kioskId['min'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_KDEVID, $kioskId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($kioskId['max'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_KDEVID, $kioskId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_KDEVID, $kioskId, $comparison);
    }

    /**
     * Filter the query on the kasm_AssignmentType column
     *
     * Example usage:
     * <code>
     * $query->filterByAssignmentType(1234); // WHERE kasm_AssignmentType = 1234
     * $query->filterByAssignmentType(array(12, 34)); // WHERE kasm_AssignmentType IN (12, 34)
     * $query->filterByAssignmentType(array('min' => 12)); // WHERE kasm_AssignmentType > 12
     * </code>
     *
     * @param     mixed $assignmentType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterByAssignmentType($assignmentType = null, $comparison = null)
    {
        if (is_array($assignmentType)) {
            $useMinMax = false;
            if (isset($assignmentType['min'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ASSIGNMENTTYPE, $assignmentType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($assignmentType['max'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ASSIGNMENTTYPE, $assignmentType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ASSIGNMENTTYPE, $assignmentType, $comparison);
    }

    /**
     * Filter the query on the kasm_EventId column
     *
     * Example usage:
     * <code>
     * $query->filterByEventId(1234); // WHERE kasm_EventId = 1234
     * $query->filterByEventId(array(12, 34)); // WHERE kasm_EventId IN (12, 34)
     * $query->filterByEventId(array('min' => 12)); // WHERE kasm_EventId > 12
     * </code>
     *
     * @see       filterByEvent()
     *
     * @param     mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, $comparison = null)
    {
        if (is_array($eventId)) {
            $useMinMax = false;
            if (isset($eventId['min'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_EVENTID, $eventId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventId['max'])) {
                $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_EVENTID, $eventId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_EVENTID, $eventId, $comparison);
    }

    /**
     * Filter the query by a related \ChurchCRM\KioskDevice object
     *
     * @param \ChurchCRM\KioskDevice|ObjectCollection $kioskDevice The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterByKioskDevice($kioskDevice, $comparison = null)
    {
        if ($kioskDevice instanceof \ChurchCRM\KioskDevice) {
            return $this
                ->addUsingAlias(KioskAssignmentTableMap::COL_KASM_KDEVID, $kioskDevice->getId(), $comparison);
        } elseif ($kioskDevice instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(KioskAssignmentTableMap::COL_KASM_KDEVID, $kioskDevice->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByKioskDevice() only accepts arguments of type \ChurchCRM\KioskDevice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the KioskDevice relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function joinKioskDevice($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('KioskDevice');

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
            $this->addJoinObject($join, 'KioskDevice');
        }

        return $this;
    }

    /**
     * Use the KioskDevice relation KioskDevice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\KioskDeviceQuery A secondary query class using the current class as primary query
     */
    public function useKioskDeviceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinKioskDevice($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'KioskDevice', '\ChurchCRM\KioskDeviceQuery');
    }

    /**
     * Filter the query by a related \ChurchCRM\Event object
     *
     * @param \ChurchCRM\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \ChurchCRM\Event) {
            return $this
                ->addUsingAlias(KioskAssignmentTableMap::COL_KASM_EVENTID, $event->getId(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(KioskAssignmentTableMap::COL_KASM_EVENTID, $event->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \ChurchCRM\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function joinEvent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Event');

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
            $this->addJoinObject($join, 'Event');
        }

        return $this;
    }

    /**
     * Use the Event relation Event object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\ChurchCRM\EventQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildKioskAssignment $kioskAssignment Object to remove from the list of results
     *
     * @return $this|ChildKioskAssignmentQuery The current query, for fluid interface
     */
    public function prune($kioskAssignment = null)
    {
        if ($kioskAssignment) {
            $this->addUsingAlias(KioskAssignmentTableMap::COL_KASM_ID, $kioskAssignment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the kioskassginment_kasm table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(KioskAssignmentTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            KioskAssignmentTableMap::clearInstancePool();
            KioskAssignmentTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(KioskAssignmentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(KioskAssignmentTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            KioskAssignmentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            KioskAssignmentTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // KioskAssignmentQuery
