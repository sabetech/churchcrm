<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\MenuLink as ChildMenuLink;
use ChurchCRM\MenuLinkQuery as ChildMenuLinkQuery;
use ChurchCRM\Map\MenuLinkTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_links' table.
 *
 *
 *
 * @method     ChildMenuLinkQuery orderById($order = Criteria::ASC) Order by the linkId column
 * @method     ChildMenuLinkQuery orderByName($order = Criteria::ASC) Order by the linkName column
 * @method     ChildMenuLinkQuery orderByUri($order = Criteria::ASC) Order by the linkUri column
 * @method     ChildMenuLinkQuery orderByOrder($order = Criteria::ASC) Order by the linkOrder column
 *
 * @method     ChildMenuLinkQuery groupById() Group by the linkId column
 * @method     ChildMenuLinkQuery groupByName() Group by the linkName column
 * @method     ChildMenuLinkQuery groupByUri() Group by the linkUri column
 * @method     ChildMenuLinkQuery groupByOrder() Group by the linkOrder column
 *
 * @method     ChildMenuLinkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuLinkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuLinkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuLinkQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuLinkQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuLinkQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuLink findOne(ConnectionInterface $con = null) Return the first ChildMenuLink matching the query
 * @method     ChildMenuLink findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuLink matching the query, or a new ChildMenuLink object populated from the query conditions when no match is found
 *
 * @method     ChildMenuLink findOneById(int $linkId) Return the first ChildMenuLink filtered by the linkId column
 * @method     ChildMenuLink findOneByName(string $linkName) Return the first ChildMenuLink filtered by the linkName column
 * @method     ChildMenuLink findOneByUri(string $linkUri) Return the first ChildMenuLink filtered by the linkUri column
 * @method     ChildMenuLink findOneByOrder(int $linkOrder) Return the first ChildMenuLink filtered by the linkOrder column *

 * @method     ChildMenuLink requirePk($key, ConnectionInterface $con = null) Return the ChildMenuLink by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuLink requireOne(ConnectionInterface $con = null) Return the first ChildMenuLink matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuLink requireOneById(int $linkId) Return the first ChildMenuLink filtered by the linkId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuLink requireOneByName(string $linkName) Return the first ChildMenuLink filtered by the linkName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuLink requireOneByUri(string $linkUri) Return the first ChildMenuLink filtered by the linkUri column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuLink requireOneByOrder(int $linkOrder) Return the first ChildMenuLink filtered by the linkOrder column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuLink[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuLink objects based on current ModelCriteria
 * @method     ChildMenuLink[]|ObjectCollection findById(int $linkId) Return ChildMenuLink objects filtered by the linkId column
 * @method     ChildMenuLink[]|ObjectCollection findByName(string $linkName) Return ChildMenuLink objects filtered by the linkName column
 * @method     ChildMenuLink[]|ObjectCollection findByUri(string $linkUri) Return ChildMenuLink objects filtered by the linkUri column
 * @method     ChildMenuLink[]|ObjectCollection findByOrder(int $linkOrder) Return ChildMenuLink objects filtered by the linkOrder column
 * @method     ChildMenuLink[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuLinkQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ChurchCRM\Base\MenuLinkQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ChurchCRM\\MenuLink', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuLinkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuLinkQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuLinkQuery) {
            return $criteria;
        }
        $query = new ChildMenuLinkQuery();
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
     * @return ChildMenuLink|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuLinkTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuLinkTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMenuLink A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT linkId, linkName, linkUri, linkOrder FROM menu_links WHERE linkId = :p0';
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
            /** @var ChildMenuLink $obj */
            $obj = new ChildMenuLink();
            $obj->hydrate($row);
            MenuLinkTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMenuLink|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuLinkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MenuLinkTableMap::COL_LINKID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuLinkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MenuLinkTableMap::COL_LINKID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the linkId column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE linkId = 1234
     * $query->filterById(array(12, 34)); // WHERE linkId IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE linkId > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuLinkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MenuLinkTableMap::COL_LINKID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MenuLinkTableMap::COL_LINKID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuLinkTableMap::COL_LINKID, $id, $comparison);
    }

    /**
     * Filter the query on the linkName column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE linkName = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE linkName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuLinkQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuLinkTableMap::COL_LINKNAME, $name, $comparison);
    }

    /**
     * Filter the query on the linkUri column
     *
     * Example usage:
     * <code>
     * $query->filterByUri('fooValue');   // WHERE linkUri = 'fooValue'
     * $query->filterByUri('%fooValue%', Criteria::LIKE); // WHERE linkUri LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uri The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuLinkQuery The current query, for fluid interface
     */
    public function filterByUri($uri = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uri)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuLinkTableMap::COL_LINKURI, $uri, $comparison);
    }

    /**
     * Filter the query on the linkOrder column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE linkOrder = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE linkOrder IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE linkOrder > 12
     * </code>
     *
     * @param     mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuLinkQuery The current query, for fluid interface
     */
    public function filterByOrder($order = null, $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(MenuLinkTableMap::COL_LINKORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(MenuLinkTableMap::COL_LINKORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuLinkTableMap::COL_LINKORDER, $order, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuLink $menuLink Object to remove from the list of results
     *
     * @return $this|ChildMenuLinkQuery The current query, for fluid interface
     */
    public function prune($menuLink = null)
    {
        if ($menuLink) {
            $this->addUsingAlias(MenuLinkTableMap::COL_LINKID, $menuLink->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_links table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuLinkTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuLinkTableMap::clearInstancePool();
            MenuLinkTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuLinkTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuLinkTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuLinkTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuLinkTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuLinkQuery
