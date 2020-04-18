<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\Calendar as ChildCalendar;
use ChurchCRM\CalendarQuery as ChildCalendarQuery;
use ChurchCRM\Map\CalendarTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'calendars' table.
 *
 *
 *
 * @method     ChildCalendarQuery orderById($order = Criteria::ASC) Order by the calendar_id column
 * @method     ChildCalendarQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCalendarQuery orderByAccessToken($order = Criteria::ASC) Order by the accesstoken column
 * @method     ChildCalendarQuery orderByBackgroundColor($order = Criteria::ASC) Order by the backgroundColor column
 * @method     ChildCalendarQuery orderByForegroundColor($order = Criteria::ASC) Order by the foregroundColor column
 *
 * @method     ChildCalendarQuery groupById() Group by the calendar_id column
 * @method     ChildCalendarQuery groupByName() Group by the name column
 * @method     ChildCalendarQuery groupByAccessToken() Group by the accesstoken column
 * @method     ChildCalendarQuery groupByBackgroundColor() Group by the backgroundColor column
 * @method     ChildCalendarQuery groupByForegroundColor() Group by the foregroundColor column
 *
 * @method     ChildCalendarQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCalendarQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCalendarQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCalendarQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCalendarQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCalendarQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCalendarQuery leftJoinCalendarEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the CalendarEvent relation
 * @method     ChildCalendarQuery rightJoinCalendarEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CalendarEvent relation
 * @method     ChildCalendarQuery innerJoinCalendarEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the CalendarEvent relation
 *
 * @method     ChildCalendarQuery joinWithCalendarEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CalendarEvent relation
 *
 * @method     ChildCalendarQuery leftJoinWithCalendarEvent() Adds a LEFT JOIN clause and with to the query using the CalendarEvent relation
 * @method     ChildCalendarQuery rightJoinWithCalendarEvent() Adds a RIGHT JOIN clause and with to the query using the CalendarEvent relation
 * @method     ChildCalendarQuery innerJoinWithCalendarEvent() Adds a INNER JOIN clause and with to the query using the CalendarEvent relation
 *
 * @method     \ChurchCRM\CalendarEventQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCalendar findOne(ConnectionInterface $con = null) Return the first ChildCalendar matching the query
 * @method     ChildCalendar findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCalendar matching the query, or a new ChildCalendar object populated from the query conditions when no match is found
 *
 * @method     ChildCalendar findOneById(int $calendar_id) Return the first ChildCalendar filtered by the calendar_id column
 * @method     ChildCalendar findOneByName(string $name) Return the first ChildCalendar filtered by the name column
 * @method     ChildCalendar findOneByAccessToken(string $accesstoken) Return the first ChildCalendar filtered by the accesstoken column
 * @method     ChildCalendar findOneByBackgroundColor(string $backgroundColor) Return the first ChildCalendar filtered by the backgroundColor column
 * @method     ChildCalendar findOneByForegroundColor(string $foregroundColor) Return the first ChildCalendar filtered by the foregroundColor column *

 * @method     ChildCalendar requirePk($key, ConnectionInterface $con = null) Return the ChildCalendar by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCalendar requireOne(ConnectionInterface $con = null) Return the first ChildCalendar matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCalendar requireOneById(int $calendar_id) Return the first ChildCalendar filtered by the calendar_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCalendar requireOneByName(string $name) Return the first ChildCalendar filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCalendar requireOneByAccessToken(string $accesstoken) Return the first ChildCalendar filtered by the accesstoken column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCalendar requireOneByBackgroundColor(string $backgroundColor) Return the first ChildCalendar filtered by the backgroundColor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCalendar requireOneByForegroundColor(string $foregroundColor) Return the first ChildCalendar filtered by the foregroundColor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCalendar[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCalendar objects based on current ModelCriteria
 * @method     ChildCalendar[]|ObjectCollection findById(int $calendar_id) Return ChildCalendar objects filtered by the calendar_id column
 * @method     ChildCalendar[]|ObjectCollection findByName(string $name) Return ChildCalendar objects filtered by the name column
 * @method     ChildCalendar[]|ObjectCollection findByAccessToken(string $accesstoken) Return ChildCalendar objects filtered by the accesstoken column
 * @method     ChildCalendar[]|ObjectCollection findByBackgroundColor(string $backgroundColor) Return ChildCalendar objects filtered by the backgroundColor column
 * @method     ChildCalendar[]|ObjectCollection findByForegroundColor(string $foregroundColor) Return ChildCalendar objects filtered by the foregroundColor column
 * @method     ChildCalendar[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CalendarQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ChurchCRM\Base\CalendarQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ChurchCRM\\Calendar', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCalendarQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCalendarQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCalendarQuery) {
            return $criteria;
        }
        $query = new ChildCalendarQuery();
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
     * @return ChildCalendar|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CalendarTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CalendarTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCalendar A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT calendar_id, name, accesstoken, backgroundColor, foregroundColor FROM calendars WHERE calendar_id = :p0';
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
            /** @var ChildCalendar $obj */
            $obj = new ChildCalendar();
            $obj->hydrate($row);
            CalendarTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCalendar|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CalendarTableMap::COL_CALENDAR_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CalendarTableMap::COL_CALENDAR_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the calendar_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE calendar_id = 1234
     * $query->filterById(array(12, 34)); // WHERE calendar_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE calendar_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CalendarTableMap::COL_CALENDAR_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CalendarTableMap::COL_CALENDAR_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CalendarTableMap::COL_CALENDAR_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CalendarTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the accesstoken column
     *
     * Example usage:
     * <code>
     * $query->filterByAccessToken('fooValue');   // WHERE accesstoken = 'fooValue'
     * $query->filterByAccessToken('%fooValue%', Criteria::LIKE); // WHERE accesstoken LIKE '%fooValue%'
     * </code>
     *
     * @param     string $accessToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByAccessToken($accessToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($accessToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CalendarTableMap::COL_ACCESSTOKEN, $accessToken, $comparison);
    }

    /**
     * Filter the query on the backgroundColor column
     *
     * Example usage:
     * <code>
     * $query->filterByBackgroundColor('fooValue');   // WHERE backgroundColor = 'fooValue'
     * $query->filterByBackgroundColor('%fooValue%', Criteria::LIKE); // WHERE backgroundColor LIKE '%fooValue%'
     * </code>
     *
     * @param     string $backgroundColor The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByBackgroundColor($backgroundColor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($backgroundColor)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CalendarTableMap::COL_BACKGROUNDCOLOR, $backgroundColor, $comparison);
    }

    /**
     * Filter the query on the foregroundColor column
     *
     * Example usage:
     * <code>
     * $query->filterByForegroundColor('fooValue');   // WHERE foregroundColor = 'fooValue'
     * $query->filterByForegroundColor('%fooValue%', Criteria::LIKE); // WHERE foregroundColor LIKE '%fooValue%'
     * </code>
     *
     * @param     string $foregroundColor The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByForegroundColor($foregroundColor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($foregroundColor)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CalendarTableMap::COL_FOREGROUNDCOLOR, $foregroundColor, $comparison);
    }

    /**
     * Filter the query by a related \ChurchCRM\CalendarEvent object
     *
     * @param \ChurchCRM\CalendarEvent|ObjectCollection $calendarEvent the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByCalendarEvent($calendarEvent, $comparison = null)
    {
        if ($calendarEvent instanceof \ChurchCRM\CalendarEvent) {
            return $this
                ->addUsingAlias(CalendarTableMap::COL_CALENDAR_ID, $calendarEvent->getCalendarId(), $comparison);
        } elseif ($calendarEvent instanceof ObjectCollection) {
            return $this
                ->useCalendarEventQuery()
                ->filterByPrimaryKeys($calendarEvent->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCalendarEvent() only accepts arguments of type \ChurchCRM\CalendarEvent or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CalendarEvent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function joinCalendarEvent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CalendarEvent');

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
            $this->addJoinObject($join, 'CalendarEvent');
        }

        return $this;
    }

    /**
     * Use the CalendarEvent relation CalendarEvent object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\CalendarEventQuery A secondary query class using the current class as primary query
     */
    public function useCalendarEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCalendarEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CalendarEvent', '\ChurchCRM\CalendarEventQuery');
    }

    /**
     * Filter the query by a related Event object
     * using the calendar_events table as cross reference
     *
     * @param Event $event the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCalendarQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCalendarEventQuery()
            ->filterByEvent($event, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCalendar $calendar Object to remove from the list of results
     *
     * @return $this|ChildCalendarQuery The current query, for fluid interface
     */
    public function prune($calendar = null)
    {
        if ($calendar) {
            $this->addUsingAlias(CalendarTableMap::COL_CALENDAR_ID, $calendar->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the calendars table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CalendarTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CalendarTableMap::clearInstancePool();
            CalendarTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CalendarTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CalendarTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CalendarTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CalendarTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CalendarQuery
