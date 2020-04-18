<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\Pledge as ChildPledge;
use ChurchCRM\PledgeQuery as ChildPledgeQuery;
use ChurchCRM\Map\PledgeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'pledge_plg' table.
 *
 * This contains all payment/pledge information
 *
 * @method     ChildPledgeQuery orderById($order = Criteria::ASC) Order by the plg_plgID column
 * @method     ChildPledgeQuery orderByFamId($order = Criteria::ASC) Order by the plg_FamID column
 * @method     ChildPledgeQuery orderByFyId($order = Criteria::ASC) Order by the plg_FYID column
 * @method     ChildPledgeQuery orderByDate($order = Criteria::ASC) Order by the plg_date column
 * @method     ChildPledgeQuery orderByAmount($order = Criteria::ASC) Order by the plg_amount column
 * @method     ChildPledgeQuery orderBySchedule($order = Criteria::ASC) Order by the plg_schedule column
 * @method     ChildPledgeQuery orderByMethod($order = Criteria::ASC) Order by the plg_method column
 * @method     ChildPledgeQuery orderByComment($order = Criteria::ASC) Order by the plg_comment column
 * @method     ChildPledgeQuery orderByDateLastEdited($order = Criteria::ASC) Order by the plg_DateLastEdited column
 * @method     ChildPledgeQuery orderByEditedBy($order = Criteria::ASC) Order by the plg_EditedBy column
 * @method     ChildPledgeQuery orderByPledgeOrPayment($order = Criteria::ASC) Order by the plg_PledgeOrPayment column
 * @method     ChildPledgeQuery orderByFundId($order = Criteria::ASC) Order by the plg_fundID column
 * @method     ChildPledgeQuery orderByDepId($order = Criteria::ASC) Order by the plg_depID column
 * @method     ChildPledgeQuery orderByCheckNo($order = Criteria::ASC) Order by the plg_CheckNo column
 * @method     ChildPledgeQuery orderByProblem($order = Criteria::ASC) Order by the plg_Problem column
 * @method     ChildPledgeQuery orderByScanString($order = Criteria::ASC) Order by the plg_scanString column
 * @method     ChildPledgeQuery orderByAutId($order = Criteria::ASC) Order by the plg_aut_ID column
 * @method     ChildPledgeQuery orderByAutCleared($order = Criteria::ASC) Order by the plg_aut_Cleared column
 * @method     ChildPledgeQuery orderByAutResultId($order = Criteria::ASC) Order by the plg_aut_ResultID column
 * @method     ChildPledgeQuery orderByNondeductible($order = Criteria::ASC) Order by the plg_NonDeductible column
 * @method     ChildPledgeQuery orderByGroupKey($order = Criteria::ASC) Order by the plg_GroupKey column
 *
 * @method     ChildPledgeQuery groupById() Group by the plg_plgID column
 * @method     ChildPledgeQuery groupByFamId() Group by the plg_FamID column
 * @method     ChildPledgeQuery groupByFyId() Group by the plg_FYID column
 * @method     ChildPledgeQuery groupByDate() Group by the plg_date column
 * @method     ChildPledgeQuery groupByAmount() Group by the plg_amount column
 * @method     ChildPledgeQuery groupBySchedule() Group by the plg_schedule column
 * @method     ChildPledgeQuery groupByMethod() Group by the plg_method column
 * @method     ChildPledgeQuery groupByComment() Group by the plg_comment column
 * @method     ChildPledgeQuery groupByDateLastEdited() Group by the plg_DateLastEdited column
 * @method     ChildPledgeQuery groupByEditedBy() Group by the plg_EditedBy column
 * @method     ChildPledgeQuery groupByPledgeOrPayment() Group by the plg_PledgeOrPayment column
 * @method     ChildPledgeQuery groupByFundId() Group by the plg_fundID column
 * @method     ChildPledgeQuery groupByDepId() Group by the plg_depID column
 * @method     ChildPledgeQuery groupByCheckNo() Group by the plg_CheckNo column
 * @method     ChildPledgeQuery groupByProblem() Group by the plg_Problem column
 * @method     ChildPledgeQuery groupByScanString() Group by the plg_scanString column
 * @method     ChildPledgeQuery groupByAutId() Group by the plg_aut_ID column
 * @method     ChildPledgeQuery groupByAutCleared() Group by the plg_aut_Cleared column
 * @method     ChildPledgeQuery groupByAutResultId() Group by the plg_aut_ResultID column
 * @method     ChildPledgeQuery groupByNondeductible() Group by the plg_NonDeductible column
 * @method     ChildPledgeQuery groupByGroupKey() Group by the plg_GroupKey column
 *
 * @method     ChildPledgeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPledgeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPledgeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPledgeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPledgeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPledgeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPledgeQuery leftJoinDeposit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Deposit relation
 * @method     ChildPledgeQuery rightJoinDeposit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Deposit relation
 * @method     ChildPledgeQuery innerJoinDeposit($relationAlias = null) Adds a INNER JOIN clause to the query using the Deposit relation
 *
 * @method     ChildPledgeQuery joinWithDeposit($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Deposit relation
 *
 * @method     ChildPledgeQuery leftJoinWithDeposit() Adds a LEFT JOIN clause and with to the query using the Deposit relation
 * @method     ChildPledgeQuery rightJoinWithDeposit() Adds a RIGHT JOIN clause and with to the query using the Deposit relation
 * @method     ChildPledgeQuery innerJoinWithDeposit() Adds a INNER JOIN clause and with to the query using the Deposit relation
 *
 * @method     ChildPledgeQuery leftJoinDonationFund($relationAlias = null) Adds a LEFT JOIN clause to the query using the DonationFund relation
 * @method     ChildPledgeQuery rightJoinDonationFund($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DonationFund relation
 * @method     ChildPledgeQuery innerJoinDonationFund($relationAlias = null) Adds a INNER JOIN clause to the query using the DonationFund relation
 *
 * @method     ChildPledgeQuery joinWithDonationFund($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DonationFund relation
 *
 * @method     ChildPledgeQuery leftJoinWithDonationFund() Adds a LEFT JOIN clause and with to the query using the DonationFund relation
 * @method     ChildPledgeQuery rightJoinWithDonationFund() Adds a RIGHT JOIN clause and with to the query using the DonationFund relation
 * @method     ChildPledgeQuery innerJoinWithDonationFund() Adds a INNER JOIN clause and with to the query using the DonationFund relation
 *
 * @method     ChildPledgeQuery leftJoinFamily($relationAlias = null) Adds a LEFT JOIN clause to the query using the Family relation
 * @method     ChildPledgeQuery rightJoinFamily($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Family relation
 * @method     ChildPledgeQuery innerJoinFamily($relationAlias = null) Adds a INNER JOIN clause to the query using the Family relation
 *
 * @method     ChildPledgeQuery joinWithFamily($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Family relation
 *
 * @method     ChildPledgeQuery leftJoinWithFamily() Adds a LEFT JOIN clause and with to the query using the Family relation
 * @method     ChildPledgeQuery rightJoinWithFamily() Adds a RIGHT JOIN clause and with to the query using the Family relation
 * @method     ChildPledgeQuery innerJoinWithFamily() Adds a INNER JOIN clause and with to the query using the Family relation
 *
 * @method     ChildPledgeQuery leftJoinPerson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Person relation
 * @method     ChildPledgeQuery rightJoinPerson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Person relation
 * @method     ChildPledgeQuery innerJoinPerson($relationAlias = null) Adds a INNER JOIN clause to the query using the Person relation
 *
 * @method     ChildPledgeQuery joinWithPerson($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Person relation
 *
 * @method     ChildPledgeQuery leftJoinWithPerson() Adds a LEFT JOIN clause and with to the query using the Person relation
 * @method     ChildPledgeQuery rightJoinWithPerson() Adds a RIGHT JOIN clause and with to the query using the Person relation
 * @method     ChildPledgeQuery innerJoinWithPerson() Adds a INNER JOIN clause and with to the query using the Person relation
 *
 * @method     \ChurchCRM\DepositQuery|\ChurchCRM\DonationFundQuery|\ChurchCRM\FamilyQuery|\ChurchCRM\PersonQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPledge findOne(ConnectionInterface $con = null) Return the first ChildPledge matching the query
 * @method     ChildPledge findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPledge matching the query, or a new ChildPledge object populated from the query conditions when no match is found
 *
 * @method     ChildPledge findOneById(int $plg_plgID) Return the first ChildPledge filtered by the plg_plgID column
 * @method     ChildPledge findOneByFamId(int $plg_FamID) Return the first ChildPledge filtered by the plg_FamID column
 * @method     ChildPledge findOneByFyId(int $plg_FYID) Return the first ChildPledge filtered by the plg_FYID column
 * @method     ChildPledge findOneByDate(string $plg_date) Return the first ChildPledge filtered by the plg_date column
 * @method     ChildPledge findOneByAmount(string $plg_amount) Return the first ChildPledge filtered by the plg_amount column
 * @method     ChildPledge findOneBySchedule(string $plg_schedule) Return the first ChildPledge filtered by the plg_schedule column
 * @method     ChildPledge findOneByMethod(string $plg_method) Return the first ChildPledge filtered by the plg_method column
 * @method     ChildPledge findOneByComment(string $plg_comment) Return the first ChildPledge filtered by the plg_comment column
 * @method     ChildPledge findOneByDateLastEdited(string $plg_DateLastEdited) Return the first ChildPledge filtered by the plg_DateLastEdited column
 * @method     ChildPledge findOneByEditedBy(int $plg_EditedBy) Return the first ChildPledge filtered by the plg_EditedBy column
 * @method     ChildPledge findOneByPledgeOrPayment(string $plg_PledgeOrPayment) Return the first ChildPledge filtered by the plg_PledgeOrPayment column
 * @method     ChildPledge findOneByFundId(int $plg_fundID) Return the first ChildPledge filtered by the plg_fundID column
 * @method     ChildPledge findOneByDepId(int $plg_depID) Return the first ChildPledge filtered by the plg_depID column
 * @method     ChildPledge findOneByCheckNo(string $plg_CheckNo) Return the first ChildPledge filtered by the plg_CheckNo column
 * @method     ChildPledge findOneByProblem(boolean $plg_Problem) Return the first ChildPledge filtered by the plg_Problem column
 * @method     ChildPledge findOneByScanString(string $plg_scanString) Return the first ChildPledge filtered by the plg_scanString column
 * @method     ChildPledge findOneByAutId(int $plg_aut_ID) Return the first ChildPledge filtered by the plg_aut_ID column
 * @method     ChildPledge findOneByAutCleared(boolean $plg_aut_Cleared) Return the first ChildPledge filtered by the plg_aut_Cleared column
 * @method     ChildPledge findOneByAutResultId(int $plg_aut_ResultID) Return the first ChildPledge filtered by the plg_aut_ResultID column
 * @method     ChildPledge findOneByNondeductible(string $plg_NonDeductible) Return the first ChildPledge filtered by the plg_NonDeductible column
 * @method     ChildPledge findOneByGroupKey(string $plg_GroupKey) Return the first ChildPledge filtered by the plg_GroupKey column *

 * @method     ChildPledge requirePk($key, ConnectionInterface $con = null) Return the ChildPledge by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOne(ConnectionInterface $con = null) Return the first ChildPledge matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPledge requireOneById(int $plg_plgID) Return the first ChildPledge filtered by the plg_plgID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByFamId(int $plg_FamID) Return the first ChildPledge filtered by the plg_FamID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByFyId(int $plg_FYID) Return the first ChildPledge filtered by the plg_FYID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByDate(string $plg_date) Return the first ChildPledge filtered by the plg_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByAmount(string $plg_amount) Return the first ChildPledge filtered by the plg_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneBySchedule(string $plg_schedule) Return the first ChildPledge filtered by the plg_schedule column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByMethod(string $plg_method) Return the first ChildPledge filtered by the plg_method column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByComment(string $plg_comment) Return the first ChildPledge filtered by the plg_comment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByDateLastEdited(string $plg_DateLastEdited) Return the first ChildPledge filtered by the plg_DateLastEdited column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByEditedBy(int $plg_EditedBy) Return the first ChildPledge filtered by the plg_EditedBy column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByPledgeOrPayment(string $plg_PledgeOrPayment) Return the first ChildPledge filtered by the plg_PledgeOrPayment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByFundId(int $plg_fundID) Return the first ChildPledge filtered by the plg_fundID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByDepId(int $plg_depID) Return the first ChildPledge filtered by the plg_depID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByCheckNo(string $plg_CheckNo) Return the first ChildPledge filtered by the plg_CheckNo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByProblem(boolean $plg_Problem) Return the first ChildPledge filtered by the plg_Problem column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByScanString(string $plg_scanString) Return the first ChildPledge filtered by the plg_scanString column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByAutId(int $plg_aut_ID) Return the first ChildPledge filtered by the plg_aut_ID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByAutCleared(boolean $plg_aut_Cleared) Return the first ChildPledge filtered by the plg_aut_Cleared column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByAutResultId(int $plg_aut_ResultID) Return the first ChildPledge filtered by the plg_aut_ResultID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByNondeductible(string $plg_NonDeductible) Return the first ChildPledge filtered by the plg_NonDeductible column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPledge requireOneByGroupKey(string $plg_GroupKey) Return the first ChildPledge filtered by the plg_GroupKey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPledge[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPledge objects based on current ModelCriteria
 * @method     ChildPledge[]|ObjectCollection findById(int $plg_plgID) Return ChildPledge objects filtered by the plg_plgID column
 * @method     ChildPledge[]|ObjectCollection findByFamId(int $plg_FamID) Return ChildPledge objects filtered by the plg_FamID column
 * @method     ChildPledge[]|ObjectCollection findByFyId(int $plg_FYID) Return ChildPledge objects filtered by the plg_FYID column
 * @method     ChildPledge[]|ObjectCollection findByDate(string $plg_date) Return ChildPledge objects filtered by the plg_date column
 * @method     ChildPledge[]|ObjectCollection findByAmount(string $plg_amount) Return ChildPledge objects filtered by the plg_amount column
 * @method     ChildPledge[]|ObjectCollection findBySchedule(string $plg_schedule) Return ChildPledge objects filtered by the plg_schedule column
 * @method     ChildPledge[]|ObjectCollection findByMethod(string $plg_method) Return ChildPledge objects filtered by the plg_method column
 * @method     ChildPledge[]|ObjectCollection findByComment(string $plg_comment) Return ChildPledge objects filtered by the plg_comment column
 * @method     ChildPledge[]|ObjectCollection findByDateLastEdited(string $plg_DateLastEdited) Return ChildPledge objects filtered by the plg_DateLastEdited column
 * @method     ChildPledge[]|ObjectCollection findByEditedBy(int $plg_EditedBy) Return ChildPledge objects filtered by the plg_EditedBy column
 * @method     ChildPledge[]|ObjectCollection findByPledgeOrPayment(string $plg_PledgeOrPayment) Return ChildPledge objects filtered by the plg_PledgeOrPayment column
 * @method     ChildPledge[]|ObjectCollection findByFundId(int $plg_fundID) Return ChildPledge objects filtered by the plg_fundID column
 * @method     ChildPledge[]|ObjectCollection findByDepId(int $plg_depID) Return ChildPledge objects filtered by the plg_depID column
 * @method     ChildPledge[]|ObjectCollection findByCheckNo(string $plg_CheckNo) Return ChildPledge objects filtered by the plg_CheckNo column
 * @method     ChildPledge[]|ObjectCollection findByProblem(boolean $plg_Problem) Return ChildPledge objects filtered by the plg_Problem column
 * @method     ChildPledge[]|ObjectCollection findByScanString(string $plg_scanString) Return ChildPledge objects filtered by the plg_scanString column
 * @method     ChildPledge[]|ObjectCollection findByAutId(int $plg_aut_ID) Return ChildPledge objects filtered by the plg_aut_ID column
 * @method     ChildPledge[]|ObjectCollection findByAutCleared(boolean $plg_aut_Cleared) Return ChildPledge objects filtered by the plg_aut_Cleared column
 * @method     ChildPledge[]|ObjectCollection findByAutResultId(int $plg_aut_ResultID) Return ChildPledge objects filtered by the plg_aut_ResultID column
 * @method     ChildPledge[]|ObjectCollection findByNondeductible(string $plg_NonDeductible) Return ChildPledge objects filtered by the plg_NonDeductible column
 * @method     ChildPledge[]|ObjectCollection findByGroupKey(string $plg_GroupKey) Return ChildPledge objects filtered by the plg_GroupKey column
 * @method     ChildPledge[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PledgeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ChurchCRM\Base\PledgeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ChurchCRM\\Pledge', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPledgeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPledgeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPledgeQuery) {
            return $criteria;
        }
        $query = new ChildPledgeQuery();
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
     * @return ChildPledge|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PledgeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PledgeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPledge A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT plg_plgID, plg_FamID, plg_FYID, plg_date, plg_amount, plg_schedule, plg_method, plg_comment, plg_DateLastEdited, plg_EditedBy, plg_PledgeOrPayment, plg_fundID, plg_depID, plg_CheckNo, plg_Problem, plg_scanString, plg_aut_ID, plg_aut_Cleared, plg_aut_ResultID, plg_NonDeductible, plg_GroupKey FROM pledge_plg WHERE plg_plgID = :p0';
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
            /** @var ChildPledge $obj */
            $obj = new ChildPledge();
            $obj->hydrate($row);
            PledgeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPledge|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_PLGID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_PLGID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the plg_plgID column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE plg_plgID = 1234
     * $query->filterById(array(12, 34)); // WHERE plg_plgID IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE plg_plgID > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_PLGID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_PLGID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_PLGID, $id, $comparison);
    }

    /**
     * Filter the query on the plg_FamID column
     *
     * Example usage:
     * <code>
     * $query->filterByFamId(1234); // WHERE plg_FamID = 1234
     * $query->filterByFamId(array(12, 34)); // WHERE plg_FamID IN (12, 34)
     * $query->filterByFamId(array('min' => 12)); // WHERE plg_FamID > 12
     * </code>
     *
     * @see       filterByFamily()
     *
     * @param     mixed $famId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByFamId($famId = null, $comparison = null)
    {
        if (is_array($famId)) {
            $useMinMax = false;
            if (isset($famId['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_FAMID, $famId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($famId['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_FAMID, $famId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_FAMID, $famId, $comparison);
    }

    /**
     * Filter the query on the plg_FYID column
     *
     * Example usage:
     * <code>
     * $query->filterByFyId(1234); // WHERE plg_FYID = 1234
     * $query->filterByFyId(array(12, 34)); // WHERE plg_FYID IN (12, 34)
     * $query->filterByFyId(array('min' => 12)); // WHERE plg_FYID > 12
     * </code>
     *
     * @param     mixed $fyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByFyId($fyId = null, $comparison = null)
    {
        if (is_array($fyId)) {
            $useMinMax = false;
            if (isset($fyId['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_FYID, $fyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fyId['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_FYID, $fyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_FYID, $fyId, $comparison);
    }

    /**
     * Filter the query on the plg_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE plg_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE plg_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE plg_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the plg_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE plg_amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE plg_amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE plg_amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the plg_schedule column
     *
     * Example usage:
     * <code>
     * $query->filterBySchedule('fooValue');   // WHERE plg_schedule = 'fooValue'
     * $query->filterBySchedule('%fooValue%', Criteria::LIKE); // WHERE plg_schedule LIKE '%fooValue%'
     * </code>
     *
     * @param     string $schedule The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterBySchedule($schedule = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($schedule)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_SCHEDULE, $schedule, $comparison);
    }

    /**
     * Filter the query on the plg_method column
     *
     * Example usage:
     * <code>
     * $query->filterByMethod('fooValue');   // WHERE plg_method = 'fooValue'
     * $query->filterByMethod('%fooValue%', Criteria::LIKE); // WHERE plg_method LIKE '%fooValue%'
     * </code>
     *
     * @param     string $method The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByMethod($method = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($method)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_METHOD, $method, $comparison);
    }

    /**
     * Filter the query on the plg_comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE plg_comment = 'fooValue'
     * $query->filterByComment('%fooValue%', Criteria::LIKE); // WHERE plg_comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query on the plg_DateLastEdited column
     *
     * Example usage:
     * <code>
     * $query->filterByDateLastEdited('2011-03-14'); // WHERE plg_DateLastEdited = '2011-03-14'
     * $query->filterByDateLastEdited('now'); // WHERE plg_DateLastEdited = '2011-03-14'
     * $query->filterByDateLastEdited(array('max' => 'yesterday')); // WHERE plg_DateLastEdited > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateLastEdited The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByDateLastEdited($dateLastEdited = null, $comparison = null)
    {
        if (is_array($dateLastEdited)) {
            $useMinMax = false;
            if (isset($dateLastEdited['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_DATELASTEDITED, $dateLastEdited['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateLastEdited['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_DATELASTEDITED, $dateLastEdited['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_DATELASTEDITED, $dateLastEdited, $comparison);
    }

    /**
     * Filter the query on the plg_EditedBy column
     *
     * Example usage:
     * <code>
     * $query->filterByEditedBy(1234); // WHERE plg_EditedBy = 1234
     * $query->filterByEditedBy(array(12, 34)); // WHERE plg_EditedBy IN (12, 34)
     * $query->filterByEditedBy(array('min' => 12)); // WHERE plg_EditedBy > 12
     * </code>
     *
     * @see       filterByPerson()
     *
     * @param     mixed $editedBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByEditedBy($editedBy = null, $comparison = null)
    {
        if (is_array($editedBy)) {
            $useMinMax = false;
            if (isset($editedBy['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_EDITEDBY, $editedBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($editedBy['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_EDITEDBY, $editedBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_EDITEDBY, $editedBy, $comparison);
    }

    /**
     * Filter the query on the plg_PledgeOrPayment column
     *
     * Example usage:
     * <code>
     * $query->filterByPledgeOrPayment('fooValue');   // WHERE plg_PledgeOrPayment = 'fooValue'
     * $query->filterByPledgeOrPayment('%fooValue%', Criteria::LIKE); // WHERE plg_PledgeOrPayment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pledgeOrPayment The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByPledgeOrPayment($pledgeOrPayment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pledgeOrPayment)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_PLEDGEORPAYMENT, $pledgeOrPayment, $comparison);
    }

    /**
     * Filter the query on the plg_fundID column
     *
     * Example usage:
     * <code>
     * $query->filterByFundId(1234); // WHERE plg_fundID = 1234
     * $query->filterByFundId(array(12, 34)); // WHERE plg_fundID IN (12, 34)
     * $query->filterByFundId(array('min' => 12)); // WHERE plg_fundID > 12
     * </code>
     *
     * @see       filterByDonationFund()
     *
     * @param     mixed $fundId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByFundId($fundId = null, $comparison = null)
    {
        if (is_array($fundId)) {
            $useMinMax = false;
            if (isset($fundId['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_FUNDID, $fundId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fundId['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_FUNDID, $fundId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_FUNDID, $fundId, $comparison);
    }

    /**
     * Filter the query on the plg_depID column
     *
     * Example usage:
     * <code>
     * $query->filterByDepId(1234); // WHERE plg_depID = 1234
     * $query->filterByDepId(array(12, 34)); // WHERE plg_depID IN (12, 34)
     * $query->filterByDepId(array('min' => 12)); // WHERE plg_depID > 12
     * </code>
     *
     * @see       filterByDeposit()
     *
     * @param     mixed $depId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByDepId($depId = null, $comparison = null)
    {
        if (is_array($depId)) {
            $useMinMax = false;
            if (isset($depId['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_DEPID, $depId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($depId['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_DEPID, $depId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_DEPID, $depId, $comparison);
    }

    /**
     * Filter the query on the plg_CheckNo column
     *
     * Example usage:
     * <code>
     * $query->filterByCheckNo(1234); // WHERE plg_CheckNo = 1234
     * $query->filterByCheckNo(array(12, 34)); // WHERE plg_CheckNo IN (12, 34)
     * $query->filterByCheckNo(array('min' => 12)); // WHERE plg_CheckNo > 12
     * </code>
     *
     * @param     mixed $checkNo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByCheckNo($checkNo = null, $comparison = null)
    {
        if (is_array($checkNo)) {
            $useMinMax = false;
            if (isset($checkNo['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_CHECKNO, $checkNo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($checkNo['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_CHECKNO, $checkNo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_CHECKNO, $checkNo, $comparison);
    }

    /**
     * Filter the query on the plg_Problem column
     *
     * Example usage:
     * <code>
     * $query->filterByProblem(true); // WHERE plg_Problem = true
     * $query->filterByProblem('yes'); // WHERE plg_Problem = true
     * </code>
     *
     * @param     boolean|string $problem The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByProblem($problem = null, $comparison = null)
    {
        if (is_string($problem)) {
            $problem = in_array(strtolower($problem), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_PROBLEM, $problem, $comparison);
    }

    /**
     * Filter the query on the plg_scanString column
     *
     * Example usage:
     * <code>
     * $query->filterByScanString('fooValue');   // WHERE plg_scanString = 'fooValue'
     * $query->filterByScanString('%fooValue%', Criteria::LIKE); // WHERE plg_scanString LIKE '%fooValue%'
     * </code>
     *
     * @param     string $scanString The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByScanString($scanString = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($scanString)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_SCANSTRING, $scanString, $comparison);
    }

    /**
     * Filter the query on the plg_aut_ID column
     *
     * Example usage:
     * <code>
     * $query->filterByAutId(1234); // WHERE plg_aut_ID = 1234
     * $query->filterByAutId(array(12, 34)); // WHERE plg_aut_ID IN (12, 34)
     * $query->filterByAutId(array('min' => 12)); // WHERE plg_aut_ID > 12
     * </code>
     *
     * @param     mixed $autId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByAutId($autId = null, $comparison = null)
    {
        if (is_array($autId)) {
            $useMinMax = false;
            if (isset($autId['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_AUT_ID, $autId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($autId['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_AUT_ID, $autId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_AUT_ID, $autId, $comparison);
    }

    /**
     * Filter the query on the plg_aut_Cleared column
     *
     * Example usage:
     * <code>
     * $query->filterByAutCleared(true); // WHERE plg_aut_Cleared = true
     * $query->filterByAutCleared('yes'); // WHERE plg_aut_Cleared = true
     * </code>
     *
     * @param     boolean|string $autCleared The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByAutCleared($autCleared = null, $comparison = null)
    {
        if (is_string($autCleared)) {
            $autCleared = in_array(strtolower($autCleared), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_AUT_CLEARED, $autCleared, $comparison);
    }

    /**
     * Filter the query on the plg_aut_ResultID column
     *
     * Example usage:
     * <code>
     * $query->filterByAutResultId(1234); // WHERE plg_aut_ResultID = 1234
     * $query->filterByAutResultId(array(12, 34)); // WHERE plg_aut_ResultID IN (12, 34)
     * $query->filterByAutResultId(array('min' => 12)); // WHERE plg_aut_ResultID > 12
     * </code>
     *
     * @param     mixed $autResultId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByAutResultId($autResultId = null, $comparison = null)
    {
        if (is_array($autResultId)) {
            $useMinMax = false;
            if (isset($autResultId['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_AUT_RESULTID, $autResultId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($autResultId['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_AUT_RESULTID, $autResultId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_AUT_RESULTID, $autResultId, $comparison);
    }

    /**
     * Filter the query on the plg_NonDeductible column
     *
     * Example usage:
     * <code>
     * $query->filterByNondeductible(1234); // WHERE plg_NonDeductible = 1234
     * $query->filterByNondeductible(array(12, 34)); // WHERE plg_NonDeductible IN (12, 34)
     * $query->filterByNondeductible(array('min' => 12)); // WHERE plg_NonDeductible > 12
     * </code>
     *
     * @param     mixed $nondeductible The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByNondeductible($nondeductible = null, $comparison = null)
    {
        if (is_array($nondeductible)) {
            $useMinMax = false;
            if (isset($nondeductible['min'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_NONDEDUCTIBLE, $nondeductible['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nondeductible['max'])) {
                $this->addUsingAlias(PledgeTableMap::COL_PLG_NONDEDUCTIBLE, $nondeductible['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_NONDEDUCTIBLE, $nondeductible, $comparison);
    }

    /**
     * Filter the query on the plg_GroupKey column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupKey('fooValue');   // WHERE plg_GroupKey = 'fooValue'
     * $query->filterByGroupKey('%fooValue%', Criteria::LIKE); // WHERE plg_GroupKey LIKE '%fooValue%'
     * </code>
     *
     * @param     string $groupKey The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByGroupKey($groupKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($groupKey)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PledgeTableMap::COL_PLG_GROUPKEY, $groupKey, $comparison);
    }

    /**
     * Filter the query by a related \ChurchCRM\Deposit object
     *
     * @param \ChurchCRM\Deposit|ObjectCollection $deposit The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByDeposit($deposit, $comparison = null)
    {
        if ($deposit instanceof \ChurchCRM\Deposit) {
            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_DEPID, $deposit->getId(), $comparison);
        } elseif ($deposit instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_DEPID, $deposit->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDeposit() only accepts arguments of type \ChurchCRM\Deposit or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Deposit relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function joinDeposit($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Deposit');

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
            $this->addJoinObject($join, 'Deposit');
        }

        return $this;
    }

    /**
     * Use the Deposit relation Deposit object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\DepositQuery A secondary query class using the current class as primary query
     */
    public function useDepositQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDeposit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Deposit', '\ChurchCRM\DepositQuery');
    }

    /**
     * Filter the query by a related \ChurchCRM\DonationFund object
     *
     * @param \ChurchCRM\DonationFund|ObjectCollection $donationFund The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByDonationFund($donationFund, $comparison = null)
    {
        if ($donationFund instanceof \ChurchCRM\DonationFund) {
            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_FUNDID, $donationFund->getId(), $comparison);
        } elseif ($donationFund instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_FUNDID, $donationFund->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDonationFund() only accepts arguments of type \ChurchCRM\DonationFund or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DonationFund relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function joinDonationFund($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DonationFund');

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
            $this->addJoinObject($join, 'DonationFund');
        }

        return $this;
    }

    /**
     * Use the DonationFund relation DonationFund object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\DonationFundQuery A secondary query class using the current class as primary query
     */
    public function useDonationFundQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDonationFund($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DonationFund', '\ChurchCRM\DonationFundQuery');
    }

    /**
     * Filter the query by a related \ChurchCRM\Family object
     *
     * @param \ChurchCRM\Family|ObjectCollection $family The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByFamily($family, $comparison = null)
    {
        if ($family instanceof \ChurchCRM\Family) {
            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_FAMID, $family->getId(), $comparison);
        } elseif ($family instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_FAMID, $family->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFamily() only accepts arguments of type \ChurchCRM\Family or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Family relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function joinFamily($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Family');

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
            $this->addJoinObject($join, 'Family');
        }

        return $this;
    }

    /**
     * Use the Family relation Family object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\FamilyQuery A secondary query class using the current class as primary query
     */
    public function useFamilyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFamily($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Family', '\ChurchCRM\FamilyQuery');
    }

    /**
     * Filter the query by a related \ChurchCRM\Person object
     *
     * @param \ChurchCRM\Person|ObjectCollection $person The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPledgeQuery The current query, for fluid interface
     */
    public function filterByPerson($person, $comparison = null)
    {
        if ($person instanceof \ChurchCRM\Person) {
            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_EDITEDBY, $person->getId(), $comparison);
        } elseif ($person instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PledgeTableMap::COL_PLG_EDITEDBY, $person->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPerson() only accepts arguments of type \ChurchCRM\Person or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Person relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function joinPerson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Person');

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
            $this->addJoinObject($join, 'Person');
        }

        return $this;
    }

    /**
     * Use the Person relation Person object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChurchCRM\PersonQuery A secondary query class using the current class as primary query
     */
    public function usePersonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPerson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Person', '\ChurchCRM\PersonQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPledge $pledge Object to remove from the list of results
     *
     * @return $this|ChildPledgeQuery The current query, for fluid interface
     */
    public function prune($pledge = null)
    {
        if ($pledge) {
            $this->addUsingAlias(PledgeTableMap::COL_PLG_PLGID, $pledge->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pledge_plg table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PledgeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PledgeTableMap::clearInstancePool();
            PledgeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PledgeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PledgeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PledgeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PledgeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PledgeQuery
