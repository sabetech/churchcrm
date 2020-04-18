<?php

namespace ChurchCRM\Base;

use \DateTime;
use \Exception;
use \PDO;
use ChurchCRM\Event as ChildEvent;
use ChurchCRM\EventQuery as ChildEventQuery;
use ChurchCRM\EventType as ChildEventType;
use ChurchCRM\EventTypeQuery as ChildEventTypeQuery;
use ChurchCRM\Group as ChildGroup;
use ChurchCRM\GroupQuery as ChildGroupQuery;
use ChurchCRM\Map\EventTableMap;
use ChurchCRM\Map\EventTypeTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'event_types' table.
 *
 *
 *
 * @package    propel.generator.ChurchCRM.Base
 */
abstract class EventType implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\ChurchCRM\\Map\\EventTypeTableMap';


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
     * The value for the type_id field.
     *
     * @var        int
     */
    protected $type_id;

    /**
     * The value for the type_name field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $type_name;

    /**
     * The value for the type_defstarttime field.
     *
     * Note: this column has a database default value of: '00:00:00.000000'
     * @var        DateTime
     */
    protected $type_defstarttime;

    /**
     * The value for the type_defrecurtype field.
     *
     * Note: this column has a database default value of: 'none'
     * @var        string
     */
    protected $type_defrecurtype;

    /**
     * The value for the type_defrecurdow field.
     *
     * Note: this column has a database default value of: 'Sunday'
     * @var        string
     */
    protected $type_defrecurdow;

    /**
     * The value for the type_defrecurdom field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $type_defrecurdom;

    /**
     * The value for the type_defrecurdoy field.
     *
     * Note: this column has a database default value of: '2016-01-01'
     * @var        DateTime
     */
    protected $type_defrecurdoy;

    /**
     * The value for the type_active field.
     *
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $type_active;

    /**
     * The value for the type_grpid field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $type_grpid;

    /**
     * @var        ChildGroup
     */
    protected $aGroup;

    /**
     * @var        ObjectCollection|ChildEvent[] Collection to store aggregation of ChildEvent objects.
     */
    protected $collEventTypes;
    protected $collEventTypesPartial;

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
    protected $eventTypesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->type_name = '';
        $this->type_defstarttime = PropelDateTime::newInstance('00:00:00.000000', null, 'DateTime');
        $this->type_defrecurtype = 'none';
        $this->type_defrecurdow = 'Sunday';
        $this->type_defrecurdom = '0';
        $this->type_defrecurdoy = PropelDateTime::newInstance('2016-01-01', null, 'DateTime');
        $this->type_active = 1;
        $this->type_grpid = 0;
    }

    /**
     * Initializes internal state of ChurchCRM\Base\EventType object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>EventType</code> instance.  If
     * <code>obj</code> is an instance of <code>EventType</code>, delegates to
     * <code>equals(EventType)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|EventType The current object, for fluid interface
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
     * Get the [type_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->type_id;
    }

    /**
     * Get the [type_name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->type_name;
    }

    /**
     * Get the [optionally formatted] temporal [type_defstarttime] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDefStartTime($format = NULL)
    {
        if ($format === null) {
            return $this->type_defstarttime;
        } else {
            return $this->type_defstarttime instanceof \DateTimeInterface ? $this->type_defstarttime->format($format) : null;
        }
    }

    /**
     * Get the [type_defrecurtype] column value.
     *
     * @return string
     */
    public function getDefRecurType()
    {
        return $this->type_defrecurtype;
    }

    /**
     * Get the [type_defrecurdow] column value.
     *
     * @return string
     */
    public function getDefRecurDOW()
    {
        return $this->type_defrecurdow;
    }

    /**
     * Get the [type_defrecurdom] column value.
     *
     * @return string
     */
    public function getDefRecurDOM()
    {
        return $this->type_defrecurdom;
    }

    /**
     * Get the [optionally formatted] temporal [type_defrecurdoy] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDefRecurDOY($format = NULL)
    {
        if ($format === null) {
            return $this->type_defrecurdoy;
        } else {
            return $this->type_defrecurdoy instanceof \DateTimeInterface ? $this->type_defrecurdoy->format($format) : null;
        }
    }

    /**
     * Get the [type_active] column value.
     *
     * @return int
     */
    public function getActive()
    {
        return $this->type_active;
    }

    /**
     * Get the [type_grpid] column value.
     *
     * @return int
     */
    public function getGroupId()
    {
        return $this->type_grpid;
    }

    /**
     * Set the value of [type_id] column.
     *
     * @param int $v new value
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type_id !== $v) {
            $this->type_id = $v;
            $this->modifiedColumns[EventTypeTableMap::COL_TYPE_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [type_name] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type_name !== $v) {
            $this->type_name = $v;
            $this->modifiedColumns[EventTypeTableMap::COL_TYPE_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Sets the value of [type_defstarttime] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setDefStartTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->type_defstarttime !== null || $dt !== null) {
            if ( ($dt != $this->type_defstarttime) // normalized values don't match
                || ($dt->format('H:i:s.u') === '00:00:00.000000') // or the entered value matches the default
                 ) {
                $this->type_defstarttime = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTypeTableMap::COL_TYPE_DEFSTARTTIME] = true;
            }
        } // if either are not null

        return $this;
    } // setDefStartTime()

    /**
     * Set the value of [type_defrecurtype] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setDefRecurType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type_defrecurtype !== $v) {
            $this->type_defrecurtype = $v;
            $this->modifiedColumns[EventTypeTableMap::COL_TYPE_DEFRECURTYPE] = true;
        }

        return $this;
    } // setDefRecurType()

    /**
     * Set the value of [type_defrecurdow] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setDefRecurDOW($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type_defrecurdow !== $v) {
            $this->type_defrecurdow = $v;
            $this->modifiedColumns[EventTypeTableMap::COL_TYPE_DEFRECURDOW] = true;
        }

        return $this;
    } // setDefRecurDOW()

    /**
     * Set the value of [type_defrecurdom] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setDefRecurDOM($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type_defrecurdom !== $v) {
            $this->type_defrecurdom = $v;
            $this->modifiedColumns[EventTypeTableMap::COL_TYPE_DEFRECURDOM] = true;
        }

        return $this;
    } // setDefRecurDOM()

    /**
     * Sets the value of [type_defrecurdoy] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setDefRecurDOY($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->type_defrecurdoy !== null || $dt !== null) {
            if ( ($dt != $this->type_defrecurdoy) // normalized values don't match
                || ($dt->format('Y-m-d') === '2016-01-01') // or the entered value matches the default
                 ) {
                $this->type_defrecurdoy = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTypeTableMap::COL_TYPE_DEFRECURDOY] = true;
            }
        } // if either are not null

        return $this;
    } // setDefRecurDOY()

    /**
     * Set the value of [type_active] column.
     *
     * @param int $v new value
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type_active !== $v) {
            $this->type_active = $v;
            $this->modifiedColumns[EventTypeTableMap::COL_TYPE_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Set the value of [type_grpid] column.
     *
     * @param int $v new value
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function setGroupId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type_grpid !== $v) {
            $this->type_grpid = $v;
            $this->modifiedColumns[EventTypeTableMap::COL_TYPE_GRPID] = true;
        }

        if ($this->aGroup !== null && $this->aGroup->getId() !== $v) {
            $this->aGroup = null;
        }

        return $this;
    } // setGroupId()

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
            if ($this->type_name !== '') {
                return false;
            }

            if ($this->type_defstarttime && $this->type_defstarttime->format('H:i:s.u') !== '00:00:00.000000') {
                return false;
            }

            if ($this->type_defrecurtype !== 'none') {
                return false;
            }

            if ($this->type_defrecurdow !== 'Sunday') {
                return false;
            }

            if ($this->type_defrecurdom !== '0') {
                return false;
            }

            if ($this->type_defrecurdoy && $this->type_defrecurdoy->format('Y-m-d') !== '2016-01-01') {
                return false;
            }

            if ($this->type_active !== 1) {
                return false;
            }

            if ($this->type_grpid !== 0) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTypeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTypeTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTypeTableMap::translateFieldName('DefStartTime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_defstarttime = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTypeTableMap::translateFieldName('DefRecurType', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_defrecurtype = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventTypeTableMap::translateFieldName('DefRecurDOW', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_defrecurdow = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventTypeTableMap::translateFieldName('DefRecurDOM', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_defrecurdom = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventTypeTableMap::translateFieldName('DefRecurDOY', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->type_defrecurdoy = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EventTypeTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_active = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : EventTypeTableMap::translateFieldName('GroupId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_grpid = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = EventTypeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ChurchCRM\\EventType'), 0, $e);
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
        if ($this->aGroup !== null && $this->type_grpid !== $this->aGroup->getId()) {
            $this->aGroup = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(EventTypeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventTypeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aGroup = null;
            $this->collEventTypes = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see EventType::setDeleted()
     * @see EventType::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTypeTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventTypeQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTypeTableMap::DATABASE_NAME);
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
                EventTypeTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aGroup !== null) {
                if ($this->aGroup->isModified() || $this->aGroup->isNew()) {
                    $affectedRows += $this->aGroup->save($con);
                }
                $this->setGroup($this->aGroup);
            }

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

            if ($this->eventTypesScheduledForDeletion !== null) {
                if (!$this->eventTypesScheduledForDeletion->isEmpty()) {
                    \ChurchCRM\EventQuery::create()
                        ->filterByPrimaryKeys($this->eventTypesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventTypesScheduledForDeletion = null;
                }
            }

            if ($this->collEventTypes !== null) {
                foreach ($this->collEventTypes as $referrerFK) {
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

        $this->modifiedColumns[EventTypeTableMap::COL_TYPE_ID] = true;
        if (null !== $this->type_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTypeTableMap::COL_TYPE_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'type_id';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'type_name';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFSTARTTIME)) {
            $modifiedColumns[':p' . $index++]  = 'type_defstarttime';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURTYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type_defrecurtype';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURDOW)) {
            $modifiedColumns[':p' . $index++]  = 'type_defrecurDOW';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURDOM)) {
            $modifiedColumns[':p' . $index++]  = 'type_defrecurDOM';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURDOY)) {
            $modifiedColumns[':p' . $index++]  = 'type_defrecurDOY';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'type_active';
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_GRPID)) {
            $modifiedColumns[':p' . $index++]  = 'type_grpid';
        }

        $sql = sprintf(
            'INSERT INTO event_types (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'type_id':
                        $stmt->bindValue($identifier, $this->type_id, PDO::PARAM_INT);
                        break;
                    case 'type_name':
                        $stmt->bindValue($identifier, $this->type_name, PDO::PARAM_STR);
                        break;
                    case 'type_defstarttime':
                        $stmt->bindValue($identifier, $this->type_defstarttime ? $this->type_defstarttime->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'type_defrecurtype':
                        $stmt->bindValue($identifier, $this->type_defrecurtype, PDO::PARAM_STR);
                        break;
                    case 'type_defrecurDOW':
                        $stmt->bindValue($identifier, $this->type_defrecurdow, PDO::PARAM_STR);
                        break;
                    case 'type_defrecurDOM':
                        $stmt->bindValue($identifier, $this->type_defrecurdom, PDO::PARAM_STR);
                        break;
                    case 'type_defrecurDOY':
                        $stmt->bindValue($identifier, $this->type_defrecurdoy ? $this->type_defrecurdoy->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'type_active':
                        $stmt->bindValue($identifier, $this->type_active, PDO::PARAM_INT);
                        break;
                    case 'type_grpid':
                        $stmt->bindValue($identifier, $this->type_grpid, PDO::PARAM_INT);
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
        $pos = EventTypeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDefStartTime();
                break;
            case 3:
                return $this->getDefRecurType();
                break;
            case 4:
                return $this->getDefRecurDOW();
                break;
            case 5:
                return $this->getDefRecurDOM();
                break;
            case 6:
                return $this->getDefRecurDOY();
                break;
            case 7:
                return $this->getActive();
                break;
            case 8:
                return $this->getGroupId();
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

        if (isset($alreadyDumpedObjects['EventType'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EventType'][$this->hashCode()] = true;
        $keys = EventTypeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDefStartTime(),
            $keys[3] => $this->getDefRecurType(),
            $keys[4] => $this->getDefRecurDOW(),
            $keys[5] => $this->getDefRecurDOM(),
            $keys[6] => $this->getDefRecurDOY(),
            $keys[7] => $this->getActive(),
            $keys[8] => $this->getGroupId(),
        );
        if ($result[$keys[2]] instanceof \DateTimeInterface) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTimeInterface) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aGroup) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'group';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'group_grp';
                        break;
                    default:
                        $key = 'Group';
                }

                $result[$key] = $this->aGroup->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collEventTypes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'events';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events_events';
                        break;
                    default:
                        $key = 'EventTypes';
                }

                $result[$key] = $this->collEventTypes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\ChurchCRM\EventType
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTypeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\ChurchCRM\EventType
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
                $this->setDefStartTime($value);
                break;
            case 3:
                $this->setDefRecurType($value);
                break;
            case 4:
                $this->setDefRecurDOW($value);
                break;
            case 5:
                $this->setDefRecurDOM($value);
                break;
            case 6:
                $this->setDefRecurDOY($value);
                break;
            case 7:
                $this->setActive($value);
                break;
            case 8:
                $this->setGroupId($value);
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
        $keys = EventTypeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDefStartTime($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setDefRecurType($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDefRecurDOW($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDefRecurDOM($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setDefRecurDOY($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setActive($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setGroupId($arr[$keys[8]]);
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
     * @return $this|\ChurchCRM\EventType The current object, for fluid interface
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
        $criteria = new Criteria(EventTypeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_ID)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_ID, $this->type_id);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_NAME)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_NAME, $this->type_name);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFSTARTTIME)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_DEFSTARTTIME, $this->type_defstarttime);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURTYPE)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_DEFRECURTYPE, $this->type_defrecurtype);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURDOW)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_DEFRECURDOW, $this->type_defrecurdow);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURDOM)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_DEFRECURDOM, $this->type_defrecurdom);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_DEFRECURDOY)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_DEFRECURDOY, $this->type_defrecurdoy);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_ACTIVE)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_ACTIVE, $this->type_active);
        }
        if ($this->isColumnModified(EventTypeTableMap::COL_TYPE_GRPID)) {
            $criteria->add(EventTypeTableMap::COL_TYPE_GRPID, $this->type_grpid);
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
        $criteria = ChildEventTypeQuery::create();
        $criteria->add(EventTypeTableMap::COL_TYPE_ID, $this->type_id);

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
     * Generic method to set the primary key (type_id column).
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
     * @param      object $copyObj An object of \ChurchCRM\EventType (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDefStartTime($this->getDefStartTime());
        $copyObj->setDefRecurType($this->getDefRecurType());
        $copyObj->setDefRecurDOW($this->getDefRecurDOW());
        $copyObj->setDefRecurDOM($this->getDefRecurDOM());
        $copyObj->setDefRecurDOY($this->getDefRecurDOY());
        $copyObj->setActive($this->getActive());
        $copyObj->setGroupId($this->getGroupId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getEventTypes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventType($relObj->copy($deepCopy));
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
     * @return \ChurchCRM\EventType Clone of current object.
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
     * Declares an association between this object and a ChildGroup object.
     *
     * @param  ChildGroup $v
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     * @throws PropelException
     */
    public function setGroup(ChildGroup $v = null)
    {
        if ($v === null) {
            $this->setGroupId(0);
        } else {
            $this->setGroupId($v->getId());
        }

        $this->aGroup = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildGroup object, it will not be re-added.
        if ($v !== null) {
            $v->addEventType($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildGroup object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildGroup The associated ChildGroup object.
     * @throws PropelException
     */
    public function getGroup(ConnectionInterface $con = null)
    {
        if ($this->aGroup === null && ($this->type_grpid != 0)) {
            $this->aGroup = ChildGroupQuery::create()->findPk($this->type_grpid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aGroup->addEventTypes($this);
             */
        }

        return $this->aGroup;
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
        if ('EventType' == $relationName) {
            $this->initEventTypes();
            return;
        }
    }

    /**
     * Clears out the collEventTypes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventTypes()
     */
    public function clearEventTypes()
    {
        $this->collEventTypes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventTypes collection loaded partially.
     */
    public function resetPartialEventTypes($v = true)
    {
        $this->collEventTypesPartial = $v;
    }

    /**
     * Initializes the collEventTypes collection.
     *
     * By default this just sets the collEventTypes collection to an empty array (like clearcollEventTypes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventTypes($overrideExisting = true)
    {
        if (null !== $this->collEventTypes && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventTableMap::getTableMap()->getCollectionClassName();

        $this->collEventTypes = new $collectionClassName;
        $this->collEventTypes->setModel('\ChurchCRM\Event');
    }

    /**
     * Gets an array of ChildEvent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventType is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @throws PropelException
     */
    public function getEventTypes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventTypesPartial && !$this->isNew();
        if (null === $this->collEventTypes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventTypes) {
                // return empty collection
                $this->initEventTypes();
            } else {
                $collEventTypes = ChildEventQuery::create(null, $criteria)
                    ->filterByEventType($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventTypesPartial && count($collEventTypes)) {
                        $this->initEventTypes(false);

                        foreach ($collEventTypes as $obj) {
                            if (false == $this->collEventTypes->contains($obj)) {
                                $this->collEventTypes->append($obj);
                            }
                        }

                        $this->collEventTypesPartial = true;
                    }

                    return $collEventTypes;
                }

                if ($partial && $this->collEventTypes) {
                    foreach ($this->collEventTypes as $obj) {
                        if ($obj->isNew()) {
                            $collEventTypes[] = $obj;
                        }
                    }
                }

                $this->collEventTypes = $collEventTypes;
                $this->collEventTypesPartial = false;
            }
        }

        return $this->collEventTypes;
    }

    /**
     * Sets a collection of ChildEvent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventTypes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventType The current object (for fluent API support)
     */
    public function setEventTypes(Collection $eventTypes, ConnectionInterface $con = null)
    {
        /** @var ChildEvent[] $eventTypesToDelete */
        $eventTypesToDelete = $this->getEventTypes(new Criteria(), $con)->diff($eventTypes);


        $this->eventTypesScheduledForDeletion = $eventTypesToDelete;

        foreach ($eventTypesToDelete as $eventTypeRemoved) {
            $eventTypeRemoved->setEventType(null);
        }

        $this->collEventTypes = null;
        foreach ($eventTypes as $eventType) {
            $this->addEventType($eventType);
        }

        $this->collEventTypes = $eventTypes;
        $this->collEventTypesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Event objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Event objects.
     * @throws PropelException
     */
    public function countEventTypes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventTypesPartial && !$this->isNew();
        if (null === $this->collEventTypes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventTypes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventTypes());
            }

            $query = ChildEventQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventType($this)
                ->count($con);
        }

        return count($this->collEventTypes);
    }

    /**
     * Method called to associate a ChildEvent object to this object
     * through the ChildEvent foreign key attribute.
     *
     * @param  ChildEvent $l ChildEvent
     * @return $this|\ChurchCRM\EventType The current object (for fluent API support)
     */
    public function addEventType(ChildEvent $l)
    {
        if ($this->collEventTypes === null) {
            $this->initEventTypes();
            $this->collEventTypesPartial = true;
        }

        if (!$this->collEventTypes->contains($l)) {
            $this->doAddEventType($l);

            if ($this->eventTypesScheduledForDeletion and $this->eventTypesScheduledForDeletion->contains($l)) {
                $this->eventTypesScheduledForDeletion->remove($this->eventTypesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEvent $eventType The ChildEvent object to add.
     */
    protected function doAddEventType(ChildEvent $eventType)
    {
        $this->collEventTypes[]= $eventType;
        $eventType->setEventType($this);
    }

    /**
     * @param  ChildEvent $eventType The ChildEvent object to remove.
     * @return $this|ChildEventType The current object (for fluent API support)
     */
    public function removeEventType(ChildEvent $eventType)
    {
        if ($this->getEventTypes()->contains($eventType)) {
            $pos = $this->collEventTypes->search($eventType);
            $this->collEventTypes->remove($pos);
            if (null === $this->eventTypesScheduledForDeletion) {
                $this->eventTypesScheduledForDeletion = clone $this->collEventTypes;
                $this->eventTypesScheduledForDeletion->clear();
            }
            $this->eventTypesScheduledForDeletion[]= clone $eventType;
            $eventType->setEventType(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventType is new, it will return
     * an empty collection; or if this EventType has previously
     * been saved, it will retrieve related EventTypes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventTypesJoinPersonRelatedByType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('PersonRelatedByType', $joinBehavior);

        return $this->getEventTypes($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventType is new, it will return
     * an empty collection; or if this EventType has previously
     * been saved, it will retrieve related EventTypes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventTypesJoinPersonRelatedBySecondaryContactPersonId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('PersonRelatedBySecondaryContactPersonId', $joinBehavior);

        return $this->getEventTypes($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventType is new, it will return
     * an empty collection; or if this EventType has previously
     * been saved, it will retrieve related EventTypes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventType.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventTypesJoinLocation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('Location', $joinBehavior);

        return $this->getEventTypes($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aGroup) {
            $this->aGroup->removeEventType($this);
        }
        $this->type_id = null;
        $this->type_name = null;
        $this->type_defstarttime = null;
        $this->type_defrecurtype = null;
        $this->type_defrecurdow = null;
        $this->type_defrecurdom = null;
        $this->type_defrecurdoy = null;
        $this->type_active = null;
        $this->type_grpid = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collEventTypes) {
                foreach ($this->collEventTypes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collEventTypes = null;
        $this->aGroup = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTypeTableMap::DEFAULT_STRING_FORMAT);
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
