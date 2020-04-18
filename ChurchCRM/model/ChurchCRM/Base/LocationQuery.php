<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\Location as ChildLocation;
use ChurchCRM\LocationQuery as ChildLocationQuery;
use ChurchCRM\Map\LocationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'locations' table.
 *
 * This is a table for storing all physical locations (Church Offices, Events, etc)
 *
 * @method     ChildLocationQuery orderByLocationId($order = Criteria::ASC) Order by the location_id column
 * @method     ChildLocationQuery orderByLocationType($order = Criteria::ASC) Order by the location_typeID column
 * @method     ChildLocationQuery orderByLocationName($order = Criteria::ASC) Order by the location_name column
 * @method     ChildLocationQuery orderByLocationAddress($order = Criteria::ASC) Order by the location_address column
 * @method     ChildLocationQuery orderByLocationCity($order = Criteria::ASC) Order by the location_city column
 * @method     ChildLocationQuery orderByLocationState($order = Criteria::ASC) Order by the location_state column
 * @method     ChildLocationQuery orderByLocationZip($order = Criteria::ASC) Order by the location_zip column
 * @method     ChildLocationQuery orderByLocationCountry($order = Criteria::ASC) Order by the location_country column
 * @method     ChildLocationQuery orderByLocationPhone($order = Criteria::ASC) Order by the location_phone column
 * @method     ChildLocationQuery orderByLocationEmail($order = Criteria::ASC) Order by the location_email column
 * @method     ChildLocationQuery orderByLocationTimzezone($order = Criteria::ASC) Order by the location_timzezone column
 *
 * @method     ChildLocationQuery groupByLocationId() Group by the location_id column
 * @method     ChildLocationQuery groupByLocationType() Group by the location_typeID column
 * @method     ChildLocationQuery groupByLocationName() Group by the location_name column
 * @method     ChildLocationQuery groupByLocationAddress() Group by the location_address column
 * @method     ChildLocationQuery groupByLocationCity() Group by the location_city column
 * @method     ChildLocationQuery groupByLocationState() Group by the location_state column
 * @method     ChildLocationQuery groupByLocationZip() Group by the location_zip column
 * @method     ChildLocationQuery groupByLocationCountry() Group by the location_country column
 * @method     ChildLocationQuery groupByLocationPhone() Group by the location_phone column
 * @method     ChildLocationQuery groupByLocationEmail() Group by the location_email column
 * @method     ChildLocationQuery groupByLocationTimzezone() Group by the location_timzezone column
 *
 * @method     ChildLocationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLocationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLocationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLocationQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLocationQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLocationQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLocationQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildLocationQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildLocationQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildLocationQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildLocationQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildLocationQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildLocationQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     \ChurchCRM\EventQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLocation findOne(ConnectionInterface $con = null) Return the first ChildLocation matching the query
 * @method     ChildLocation findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLocation matching the query, or a new ChildLocation object populated from the query conditions when no match is found
 *
 * @method     ChildLocation findOneByLocationId(int $location_id) Return the first ChildLocation filtered by the location_id column
 * @method     ChildLocation findOneByLocationType(int $location_typeID) Return the first ChildLocation filtered by the location_typeID column
 * @method     ChildLocation findOneByLocationName(string $location_name) Return the first ChildLocation filtered by the location_name column
 * @method     ChildLocation findOneByLocationAddress(string $location_address) Return the first ChildLocation filtered by the location_address column
 * @method     ChildLocation findOneByLocationCity(string $location_city) Return the first ChildLocation filtered by the location_city column
 * @method     ChildLocation findOneByLocationState(string $location_state) Return the first ChildLocation filtered by the location_state column
 * @method     ChildLocation findOneByLocationZip(string $location_zip) Return the first ChildLocation filtered by the location_zip column
 * @method     ChildLocation findOneByLocationCountry(string $location_country) Return the first ChildLocation filtered by the location_country column
 * @method     ChildLocation findOneByLocationPhone(string $location_phone) Return the first ChildLocation filtered by the location_phone column
 * @method     ChildLocation findOneByLocationEmail(string $location_email) Return the first ChildLocation filtered by the location_email column
 * @method     ChildLocation findOneByLocationTimzezone(string $location_timzezone) Return the first ChildLocation filtered by the location_timzezone column *

 * @method     ChildLocation requirePk($key, ConnectionInterface $con = null) Return the ChildLocation by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOne(ConnectionInterface $con = null) Return the first ChildLocation matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocation requireOneByLocationId(int $location_id) Return the first ChildLocation filtered by the location_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationType(int $location_typeID) Return the first ChildLocation filtered by the location_typeID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationName(string $location_name) Return the first ChildLocation filtered by the location_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationAddress(string $location_address) Return the first ChildLocation filtered by the location_address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationCity(string $location_city) Return the first ChildLocation filtered by the location_city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationState(string $location_state) Return the first ChildLocation filtered by the location_state column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationZip(string $location_zip) Return the first ChildLocation filtered by the location_zip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationCountry(string $location_country) Return the first ChildLocation filtered by the location_country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationPhone(string $location_phone) Return the first ChildLocation filtered by the location_phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationEmail(string $location_email) Return the first ChildLocation filtered by the location_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocation requireOneByLocationTimzezone(string $location_timzezone) Return the first ChildLocation filtered by the location_timzezone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocation[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLocation objects based on current ModelCriteria
 * @method     ChildLocation[]|ObjectCollection findByLocationId(int $location_id) Return ChildLocation objects filtered by the location_id column
 * @method     ChildLocation[]|ObjectCollection findByLocationType(int $location_typeID) Return ChildLocation objects filtered by the location_typeID column
 * @method     ChildLocation[]|ObjectCollection findByLocationName(string $location_name) Return ChildLocation objects filtered by the location_name column
 * @method     ChildLocation[]|ObjectCollection findByLocationAddress(string $location_address) Return ChildLocation objects filtered by the location_address column
 * @method     ChildLocation[]|ObjectCollection findByLocationCity(string $location_city) Return ChildLocation objects filtered by the location_city column
 * @method     ChildLocation[]|ObjectCollection findByLocationState(string $location_state) Return ChildLocation objects filtered by the location_state column
 * @method     ChildLocation[]|ObjectCollection findByLocationZip(string $location_zip) Return ChildLocation objects filtered by the location_zip column
 * @method     ChildLocation[]|ObjectCollection findByLocationCountry(string $location_country) Return ChildLocation objects filtered by the location_country column
 * @method     ChildLocation[]|ObjectCollection findByLocationPhone(string $location_phone) Return ChildLocation objects filtered by the location_phone column
 * @method     ChildLocation[]|ObjectCollection findByLocationEmail(string $location_email) Return ChildLocation objects filtered by the location_email column
 * @method     ChildLocation[]|ObjectCollection findByLocationTimzezone(string $location_timzezone) Return ChildLocation objects filtered by the location_timzezone column
 * @method     ChildLocation[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LocationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ChurchCRM\Base\LocationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ChurchCRM\\Location', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLocationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLocationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLocationQuery) {
            return $criteria;
        }
        $query = new ChildLocationQuery();
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
     * @return ChildLocation|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocationTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LocationTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLocation A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT location_id, location_typeID, location_name, location_address, location_city, location_state, location_zip, location_country, location_phone, location_email, location_timzezone FROM locations WHERE location_id = :p0';
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
            /** @var ChildLocation $obj */
            $obj = new ChildLocation();
            $obj->hydrate($row);
            LocationTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLocation|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the location_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationId(1234); // WHERE location_id = 1234
     * $query->filterByLocationId(array(12, 34)); // WHERE location_id IN (12, 34)
     * $query->filterByLocationId(array('min' => 12)); // WHERE location_id > 12
     * </code>
     *
     * @param     mixed $locationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationId($locationId = null, $comparison = null)
    {
        if (is_array($locationId)) {
            $useMinMax = false;
            if (isset($locationId['min'])) {
                $this->addUsingAlias(LocationTableMap::COL_LOCATION_ID, $locationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($locationId['max'])) {
                $this->addUsingAlias(LocationTableMap::COL_LOCATION_ID, $locationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_ID, $locationId, $comparison);
    }

    /**
     * Filter the query on the location_typeID column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationType(1234); // WHERE location_typeID = 1234
     * $query->filterByLocationType(array(12, 34)); // WHERE location_typeID IN (12, 34)
     * $query->filterByLocationType(array('min' => 12)); // WHERE location_typeID > 12
     * </code>
     *
     * @param     mixed $locationType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationType($locationType = null, $comparison = null)
    {
        if (is_array($locationType)) {
            $useMinMax = false;
            if (isset($locationType['min'])) {
                $this->addUsingAlias(LocationTableMap::COL_LOCATION_TYPEID, $locationType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($locationType['max'])) {
                $this->addUsingAlias(LocationTableMap::COL_LOCATION_TYPEID, $locationType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_TYPEID, $locationType, $comparison);
    }

    /**
     * Filter the query on the location_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationName('fooValue');   // WHERE location_name = 'fooValue'
     * $query->filterByLocationName('%fooValue%', Criteria::LIKE); // WHERE location_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationName($locationName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_NAME, $locationName, $comparison);
    }

    /**
     * Filter the query on the location_address column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationAddress('fooValue');   // WHERE location_address = 'fooValue'
     * $query->filterByLocationAddress('%fooValue%', Criteria::LIKE); // WHERE location_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationAddress The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationAddress($locationAddress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationAddress)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_ADDRESS, $locationAddress, $comparison);
    }

    /**
     * Filter the query on the location_city column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationCity('fooValue');   // WHERE location_city = 'fooValue'
     * $query->filterByLocationCity('%fooValue%', Criteria::LIKE); // WHERE location_city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationCity The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationCity($locationCity = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationCity)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_CITY, $locationCity, $comparison);
    }

    /**
     * Filter the query on the location_state column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationState('fooValue');   // WHERE location_state = 'fooValue'
     * $query->filterByLocationState('%fooValue%', Criteria::LIKE); // WHERE location_state LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationState The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationState($locationState = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationState)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_STATE, $locationState, $comparison);
    }

    /**
     * Filter the query on the location_zip column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationZip('fooValue');   // WHERE location_zip = 'fooValue'
     * $query->filterByLocationZip('%fooValue%', Criteria::LIKE); // WHERE location_zip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationZip The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationZip($locationZip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationZip)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_ZIP, $locationZip, $comparison);
    }

    /**
     * Filter the query on the location_country column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationCountry('fooValue');   // WHERE location_country = 'fooValue'
     * $query->filterByLocationCountry('%fooValue%', Criteria::LIKE); // WHERE location_country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationCountry The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationCountry($locationCountry = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationCountry)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_COUNTRY, $locationCountry, $comparison);
    }

    /**
     * Filter the query on the location_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationPhone('fooValue');   // WHERE location_phone = 'fooValue'
     * $query->filterByLocationPhone('%fooValue%', Criteria::LIKE); // WHERE location_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationPhone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationPhone($locationPhone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationPhone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_PHONE, $locationPhone, $comparison);
    }

    /**
     * Filter the query on the location_email column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationEmail('fooValue');   // WHERE location_email = 'fooValue'
     * $query->filterByLocationEmail('%fooValue%', Criteria::LIKE); // WHERE location_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationEmail The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationEmail($locationEmail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationEmail)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_EMAIL, $locationEmail, $comparison);
    }

    /**
     * Filter the query on the location_timzezone column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationTimzezone('fooValue');   // WHERE location_timzezone = 'fooValue'
     * $query->filterByLocationTimzezone('%fooValue%', Criteria::LIKE); // WHERE location_timzezone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $locationTimzezone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function filterByLocationTimzezone($locationTimzezone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($locationTimzezone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocationTableMap::COL_LOCATION_TIMZEZONE, $locationTimzezone, $comparison);
    }

    /**
     * Filter the query by a related \ChurchCRM\Event object
     *
     * @param \ChurchCRM\Event|ObjectCollection $event the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocationQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \ChurchCRM\Event) {
            return $this
                ->addUsingAlias(LocationTableMap::COL_LOCATION_ID, $event->getLocationId(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            return $this
                ->useEventQuery()
                ->filterByPrimaryKeys($event->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function joinEvent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\ChurchCRM\EventQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLocation $location Object to remove from the list of results
     *
     * @return $this|ChildLocationQuery The current query, for fluid interface
     */
    public function prune($location = null)
    {
        if ($location) {
            $this->addUsingAlias(LocationTableMap::COL_LOCATION_ID, $location->getLocationId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the locations table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LocationTableMap::clearInstancePool();
            LocationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LocationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LocationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LocationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LocationQuery
