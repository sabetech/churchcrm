<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\Calendar as ChildCalendar;
use ChurchCRM\CalendarEvent as ChildCalendarEvent;
use ChurchCRM\CalendarEventQuery as ChildCalendarEventQuery;
use ChurchCRM\CalendarQuery as ChildCalendarQuery;
use ChurchCRM\Event as ChildEvent;
use ChurchCRM\EventQuery as ChildEventQuery;
use ChurchCRM\Map\CalendarEventTableMap;
use ChurchCRM\Map\CalendarTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'calendars' table.
 *
 *
 *
 * @package    propel.generator.ChurchCRM.Base
 */
abstract class Calendar implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\ChurchCRM\\Map\\CalendarTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the calendar_id field.
     *
     * @var        int
     */
    protected $calendar_id;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the accesstoken field.
     *
     * @var        string
     */
    protected $accesstoken;

    /**
     * The value for the backgroundcolor field.
     *
     * @var        string
     */
    protected $backgroundcolor;

    /**
     * The value for the foregroundcolor field.
     *
     * @var        string
     */
    protected $foregroundcolor;

    /**
     * @var        ObjectCollection|ChildCalendarEvent[] Collection to store aggregation of ChildCalendarEvent objects.
     */
    protected $collCalendarEvents;
    protected $collCalendarEventsPartial;

    /**
     * @var        ObjectCollection|ChildEvent[] Cross Collection to store aggregation of ChildEvent objects.
     */
    protected $collEvents;

    /**
     * @var bool
     */
    protected $collEventsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEvent[]
     */
    protected $eventsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCalendarEvent[]
     */
    protected $calendarEventsScheduledForDeletion = null;

    /**
     * Initializes internal state of ChurchCRM\Base\Calendar object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Calendar</code> instance.  If
     * <code>obj</code> is an instance of <code>Calendar</code>, delegates to
     * <code>equals(Calendar)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Calendar The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [calendar_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->calendar_id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [accesstoken] column value.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accesstoken;
    }

    /**
     * Get the [backgroundcolor] column value.
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundcolor;
    }

    /**
     * Get the [foregroundcolor] column value.
     *
     * @return string
     */
    public function getForegroundColor()
    {
        return $this->foregroundcolor;
    }

    /**
     * Set the value of [calendar_id] column.
     *
     * @param int $v new value
     * @return $this|\ChurchCRM\Calendar The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->calendar_id !== $v) {
            $this->calendar_id = $v;
            $this->modifiedColumns[CalendarTableMap::COL_CALENDAR_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Calendar The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[CalendarTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [accesstoken] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Calendar The current object (for fluent API support)
     */
    public function setAccessToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->accesstoken !== $v) {
            $this->accesstoken = $v;
            $this->modifiedColumns[CalendarTableMap::COL_ACCESSTOKEN] = true;
        }

        return $this;
    } // setAccessToken()

    /**
     * Set the value of [backgroundcolor] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Calendar The current object (for fluent API support)
     */
    public function setBackgroundColor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->backgroundcolor !== $v) {
            $this->backgroundcolor = $v;
            $this->modifiedColumns[CalendarTableMap::COL_BACKGROUNDCOLOR] = true;
        }

        return $this;
    } // setBackgroundColor()

    /**
     * Set the value of [foregroundcolor] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Calendar The current object (for fluent API support)
     */
    public function setForegroundColor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->foregroundcolor !== $v) {
            $this->foregroundcolor = $v;
            $this->modifiedColumns[CalendarTableMap::COL_FOREGROUNDCOLOR] = true;
        }

        return $this;
    } // setForegroundColor()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CalendarTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->calendar_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CalendarTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CalendarTableMap::translateFieldName('AccessToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->accesstoken = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CalendarTableMap::translateFieldName('BackgroundColor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->backgroundcolor = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CalendarTableMap::translateFieldName('ForegroundColor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->foregroundcolor = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = CalendarTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ChurchCRM\\Calendar'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CalendarTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCalendarQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCalendarEvents = null;

            $this->collEvents = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Calendar::setDeleted()
     * @see Calendar::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CalendarTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCalendarQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CalendarTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CalendarTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->eventsScheduledForDeletion !== null) {
                if (!$this->eventsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->eventsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \ChurchCRM\CalendarEventQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->eventsScheduledForDeletion = null;
                }

            }

            if ($this->collEvents) {
                foreach ($this->collEvents as $event) {
                    if (!$event->isDeleted() && ($event->isNew() || $event->isModified())) {
                        $event->save($con);
                    }
                }
            }


            if ($this->calendarEventsScheduledForDeletion !== null) {
                if (!$this->calendarEventsScheduledForDeletion->isEmpty()) {
                    \ChurchCRM\CalendarEventQuery::create()
                        ->filterByPrimaryKeys($this->calendarEventsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->calendarEventsScheduledForDeletion = null;
                }
            }

            if ($this->collCalendarEvents !== null) {
                foreach ($this->collCalendarEvents as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[CalendarTableMap::COL_CALENDAR_ID] = true;
        if (null !== $this->calendar_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CalendarTableMap::COL_CALENDAR_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CalendarTableMap::COL_CALENDAR_ID)) {
            $modifiedColumns[':p' . $index++]  = 'calendar_id';
        }
        if ($this->isColumnModified(CalendarTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(CalendarTableMap::COL_ACCESSTOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'accesstoken';
        }
        if ($this->isColumnModified(CalendarTableMap::COL_BACKGROUNDCOLOR)) {
            $modifiedColumns[':p' . $index++]  = 'backgroundColor';
        }
        if ($this->isColumnModified(CalendarTableMap::COL_FOREGROUNDCOLOR)) {
            $modifiedColumns[':p' . $index++]  = 'foregroundColor';
        }

        $sql = sprintf(
            'INSERT INTO calendars (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'calendar_id':
                        $stmt->bindValue($identifier, $this->calendar_id, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'accesstoken':
                        $stmt->bindValue($identifier, $this->accesstoken, PDO::PARAM_STR);
                        break;
                    case 'backgroundColor':
                        $stmt->bindValue($identifier, $this->backgroundcolor, PDO::PARAM_STR);
                        break;
                    case 'foregroundColor':
                        $stmt->bindValue($identifier, $this->foregroundcolor, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CalendarTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getAccessToken();
                break;
            case 3:
                return $this->getBackgroundColor();
                break;
            case 4:
                return $this->getForegroundColor();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Calendar'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Calendar'][$this->hashCode()] = true;
        $keys = CalendarTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getAccessToken(),
            $keys[3] => $this->getBackgroundColor(),
            $keys[4] => $this->getForegroundColor(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCalendarEvents) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'calendarEvents';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'calendar_eventss';
                        break;
                    default:
                        $key = 'CalendarEvents';
                }

                $result[$key] = $this->collCalendarEvents->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\ChurchCRM\Calendar
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CalendarTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\ChurchCRM\Calendar
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setAccessToken($value);
                break;
            case 3:
                $this->setBackgroundColor($value);
                break;
            case 4:
                $this->setForegroundColor($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = CalendarTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAccessToken($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setBackgroundColor($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setForegroundColor($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\ChurchCRM\Calendar The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CalendarTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CalendarTableMap::COL_CALENDAR_ID)) {
            $criteria->add(CalendarTableMap::COL_CALENDAR_ID, $this->calendar_id);
        }
        if ($this->isColumnModified(CalendarTableMap::COL_NAME)) {
            $criteria->add(CalendarTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(CalendarTableMap::COL_ACCESSTOKEN)) {
            $criteria->add(CalendarTableMap::COL_ACCESSTOKEN, $this->accesstoken);
        }
        if ($this->isColumnModified(CalendarTableMap::COL_BACKGROUNDCOLOR)) {
            $criteria->add(CalendarTableMap::COL_BACKGROUNDCOLOR, $this->backgroundcolor);
        }
        if ($this->isColumnModified(CalendarTableMap::COL_FOREGROUNDCOLOR)) {
            $criteria->add(CalendarTableMap::COL_FOREGROUNDCOLOR, $this->foregroundcolor);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildCalendarQuery::create();
        $criteria->add(CalendarTableMap::COL_CALENDAR_ID, $this->calendar_id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (calendar_id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \ChurchCRM\Calendar (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setAccessToken($this->getAccessToken());
        $copyObj->setBackgroundColor($this->getBackgroundColor());
        $copyObj->setForegroundColor($this->getForegroundColor());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCalendarEvents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCalendarEvent($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \ChurchCRM\Calendar Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CalendarEvent' == $relationName) {
            $this->initCalendarEvents();
            return;
        }
    }

    /**
     * Clears out the collCalendarEvents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCalendarEvents()
     */
    public function clearCalendarEvents()
    {
        $this->collCalendarEvents = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCalendarEvents collection loaded partially.
     */
    public function resetPartialCalendarEvents($v = true)
    {
        $this->collCalendarEventsPartial = $v;
    }

    /**
     * Initializes the collCalendarEvents collection.
     *
     * By default this just sets the collCalendarEvents collection to an empty array (like clearcollCalendarEvents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCalendarEvents($overrideExisting = true)
    {
        if (null !== $this->collCalendarEvents && !$overrideExisting) {
            return;
        }

        $collectionClassName = CalendarEventTableMap::getTableMap()->getCollectionClassName();

        $this->collCalendarEvents = new $collectionClassName;
        $this->collCalendarEvents->setModel('\ChurchCRM\CalendarEvent');
    }

    /**
     * Gets an array of ChildCalendarEvent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCalendar is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCalendarEvent[] List of ChildCalendarEvent objects
     * @throws PropelException
     */
    public function getCalendarEvents(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCalendarEventsPartial && !$this->isNew();
        if (null === $this->collCalendarEvents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCalendarEvents) {
                // return empty collection
                $this->initCalendarEvents();
            } else {
                $collCalendarEvents = ChildCalendarEventQuery::create(null, $criteria)
                    ->filterByCalendar($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCalendarEventsPartial && count($collCalendarEvents)) {
                        $this->initCalendarEvents(false);

                        foreach ($collCalendarEvents as $obj) {
                            if (false == $this->collCalendarEvents->contains($obj)) {
                                $this->collCalendarEvents->append($obj);
                            }
                        }

                        $this->collCalendarEventsPartial = true;
                    }

                    return $collCalendarEvents;
                }

                if ($partial && $this->collCalendarEvents) {
                    foreach ($this->collCalendarEvents as $obj) {
                        if ($obj->isNew()) {
                            $collCalendarEvents[] = $obj;
                        }
                    }
                }

                $this->collCalendarEvents = $collCalendarEvents;
                $this->collCalendarEventsPartial = false;
            }
        }

        return $this->collCalendarEvents;
    }

    /**
     * Sets a collection of ChildCalendarEvent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $calendarEvents A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCalendar The current object (for fluent API support)
     */
    public function setCalendarEvents(Collection $calendarEvents, ConnectionInterface $con = null)
    {
        /** @var ChildCalendarEvent[] $calendarEventsToDelete */
        $calendarEventsToDelete = $this->getCalendarEvents(new Criteria(), $con)->diff($calendarEvents);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->calendarEventsScheduledForDeletion = clone $calendarEventsToDelete;

        foreach ($calendarEventsToDelete as $calendarEventRemoved) {
            $calendarEventRemoved->setCalendar(null);
        }

        $this->collCalendarEvents = null;
        foreach ($calendarEvents as $calendarEvent) {
            $this->addCalendarEvent($calendarEvent);
        }

        $this->collCalendarEvents = $calendarEvents;
        $this->collCalendarEventsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CalendarEvent objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CalendarEvent objects.
     * @throws PropelException
     */
    public function countCalendarEvents(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCalendarEventsPartial && !$this->isNew();
        if (null === $this->collCalendarEvents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCalendarEvents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCalendarEvents());
            }

            $query = ChildCalendarEventQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCalendar($this)
                ->count($con);
        }

        return count($this->collCalendarEvents);
    }

    /**
     * Method called to associate a ChildCalendarEvent object to this object
     * through the ChildCalendarEvent foreign key attribute.
     *
     * @param  ChildCalendarEvent $l ChildCalendarEvent
     * @return $this|\ChurchCRM\Calendar The current object (for fluent API support)
     */
    public function addCalendarEvent(ChildCalendarEvent $l)
    {
        if ($this->collCalendarEvents === null) {
            $this->initCalendarEvents();
            $this->collCalendarEventsPartial = true;
        }

        if (!$this->collCalendarEvents->contains($l)) {
            $this->doAddCalendarEvent($l);

            if ($this->calendarEventsScheduledForDeletion and $this->calendarEventsScheduledForDeletion->contains($l)) {
                $this->calendarEventsScheduledForDeletion->remove($this->calendarEventsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCalendarEvent $calendarEvent The ChildCalendarEvent object to add.
     */
    protected function doAddCalendarEvent(ChildCalendarEvent $calendarEvent)
    {
        $this->collCalendarEvents[]= $calendarEvent;
        $calendarEvent->setCalendar($this);
    }

    /**
     * @param  ChildCalendarEvent $calendarEvent The ChildCalendarEvent object to remove.
     * @return $this|ChildCalendar The current object (for fluent API support)
     */
    public function removeCalendarEvent(ChildCalendarEvent $calendarEvent)
    {
        if ($this->getCalendarEvents()->contains($calendarEvent)) {
            $pos = $this->collCalendarEvents->search($calendarEvent);
            $this->collCalendarEvents->remove($pos);
            if (null === $this->calendarEventsScheduledForDeletion) {
                $this->calendarEventsScheduledForDeletion = clone $this->collCalendarEvents;
                $this->calendarEventsScheduledForDeletion->clear();
            }
            $this->calendarEventsScheduledForDeletion[]= clone $calendarEvent;
            $calendarEvent->setCalendar(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Calendar is new, it will return
     * an empty collection; or if this Calendar has previously
     * been saved, it will retrieve related CalendarEvents from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Calendar.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCalendarEvent[] List of ChildCalendarEvent objects
     */
    public function getCalendarEventsJoinEvent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCalendarEventQuery::create(null, $criteria);
        $query->joinWith('Event', $joinBehavior);

        return $this->getCalendarEvents($query, $con);
    }

    /**
     * Clears out the collEvents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEvents()
     */
    public function clearEvents()
    {
        $this->collEvents = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collEvents crossRef collection.
     *
     * By default this just sets the collEvents collection to an empty collection (like clearEvents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEvents()
    {
        $collectionClassName = CalendarEventTableMap::getTableMap()->getCollectionClassName();

        $this->collEvents = new $collectionClassName;
        $this->collEventsPartial = true;
        $this->collEvents->setModel('\ChurchCRM\Event');
    }

    /**
     * Checks if the collEvents collection is loaded.
     *
     * @return bool
     */
    public function isEventsLoaded()
    {
        return null !== $this->collEvents;
    }

    /**
     * Gets a collection of ChildEvent objects related by a many-to-many relationship
     * to the current object by way of the calendar_events cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCalendar is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEvents(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collEvents) {
                    $this->initEvents();
                }
            } else {

                $query = ChildEventQuery::create(null, $criteria)
                    ->filterByCalendar($this);
                $collEvents = $query->find($con);
                if (null !== $criteria) {
                    return $collEvents;
                }

                if ($partial && $this->collEvents) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collEvents as $obj) {
                        if (!$collEvents->contains($obj)) {
                            $collEvents[] = $obj;
                        }
                    }
                }

                $this->collEvents = $collEvents;
                $this->collEventsPartial = false;
            }
        }

        return $this->collEvents;
    }

    /**
     * Sets a collection of Event objects related by a many-to-many relationship
     * to the current object by way of the calendar_events cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $events A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildCalendar The current object (for fluent API support)
     */
    public function setEvents(Collection $events, ConnectionInterface $con = null)
    {
        $this->clearEvents();
        $currentEvents = $this->getEvents();

        $eventsScheduledForDeletion = $currentEvents->diff($events);

        foreach ($eventsScheduledForDeletion as $toDelete) {
            $this->removeEvent($toDelete);
        }

        foreach ($events as $event) {
            if (!$currentEvents->contains($event)) {
                $this->doAddEvent($event);
            }
        }

        $this->collEventsPartial = false;
        $this->collEvents = $events;

        return $this;
    }

    /**
     * Gets the number of Event objects related by a many-to-many relationship
     * to the current object by way of the calendar_events cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Event objects
     */
    public function countEvents(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEvents) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getEvents());
                }

                $query = ChildEventQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCalendar($this)
                    ->count($con);
            }
        } else {
            return count($this->collEvents);
        }
    }

    /**
     * Associate a ChildEvent to this object
     * through the calendar_events cross reference table.
     *
     * @param ChildEvent $event
     * @return ChildCalendar The current object (for fluent API support)
     */
    public function addEvent(ChildEvent $event)
    {
        if ($this->collEvents === null) {
            $this->initEvents();
        }

        if (!$this->getEvents()->contains($event)) {
            // only add it if the **same** object is not already associated
            $this->collEvents->push($event);
            $this->doAddEvent($event);
        }

        return $this;
    }

    /**
     *
     * @param ChildEvent $event
     */
    protected function doAddEvent(ChildEvent $event)
    {
        $calendarEvent = new ChildCalendarEvent();

        $calendarEvent->setEvent($event);

        $calendarEvent->setCalendar($this);

        $this->addCalendarEvent($calendarEvent);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$event->isCalendarsLoaded()) {
            $event->initCalendars();
            $event->getCalendars()->push($this);
        } elseif (!$event->getCalendars()->contains($this)) {
            $event->getCalendars()->push($this);
        }

    }

    /**
     * Remove event of this object
     * through the calendar_events cross reference table.
     *
     * @param ChildEvent $event
     * @return ChildCalendar The current object (for fluent API support)
     */
    public function removeEvent(ChildEvent $event)
    {
        if ($this->getEvents()->contains($event)) {
            $calendarEvent = new ChildCalendarEvent();
            $calendarEvent->setEvent($event);
            if ($event->isCalendarsLoaded()) {
                //remove the back reference if available
                $event->getCalendars()->removeObject($this);
            }

            $calendarEvent->setCalendar($this);
            $this->removeCalendarEvent(clone $calendarEvent);
            $calendarEvent->clear();

            $this->collEvents->remove($this->collEvents->search($event));

            if (null === $this->eventsScheduledForDeletion) {
                $this->eventsScheduledForDeletion = clone $this->collEvents;
                $this->eventsScheduledForDeletion->clear();
            }

            $this->eventsScheduledForDeletion->push($event);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->calendar_id = null;
        $this->name = null;
        $this->accesstoken = null;
        $this->backgroundcolor = null;
        $this->foregroundcolor = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collCalendarEvents) {
                foreach ($this->collCalendarEvents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEvents) {
                foreach ($this->collEvents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCalendarEvents = null;
        $this->collEvents = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CalendarTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
