<?php

namespace ChurchCRM\Base;

use \Exception;
use \PDO;
use ChurchCRM\Event as ChildEvent;
use ChurchCRM\EventQuery as ChildEventQuery;
use ChurchCRM\Location as ChildLocation;
use ChurchCRM\LocationQuery as ChildLocationQuery;
use ChurchCRM\Map\EventTableMap;
use ChurchCRM\Map\LocationTableMap;
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
 * Base class that represents a row from the 'locations' table.
 *
 * This is a table for storing all physical locations (Church Offices, Events, etc)
 *
 * @package    propel.generator.ChurchCRM.Base
 */
abstract class Location implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\ChurchCRM\\Map\\LocationTableMap';


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
     * The value for the location_id field.
     *
     * @var        int
     */
    protected $location_id;

    /**
     * The value for the location_typeid field.
     *
     * @var        int
     */
    protected $location_typeid;

    /**
     * The value for the location_name field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_name;

    /**
     * The value for the location_address field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_address;

    /**
     * The value for the location_city field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_city;

    /**
     * The value for the location_state field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_state;

    /**
     * The value for the location_zip field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_zip;

    /**
     * The value for the location_country field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_country;

    /**
     * The value for the location_phone field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_phone;

    /**
     * The value for the location_email field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_email;

    /**
     * The value for the location_timzezone field.
     *
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $location_timzezone;

    /**
     * @var        ObjectCollection|ChildEvent[] Collection to store aggregation of ChildEvent objects.
     */
    protected $collEvents;
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
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->location_name = '';
        $this->location_address = '';
        $this->location_city = '';
        $this->location_state = '';
        $this->location_zip = '';
        $this->location_country = '';
        $this->location_phone = '';
        $this->location_email = '';
        $this->location_timzezone = '';
    }

    /**
     * Initializes internal state of ChurchCRM\Base\Location object.
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
     * Compares this with another <code>Location</code> instance.  If
     * <code>obj</code> is an instance of <code>Location</code>, delegates to
     * <code>equals(Location)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Location The current object, for fluid interface
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
     * Get the [location_id] column value.
     *
     * @return int
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * Get the [location_typeid] column value.
     *
     * @return int
     */
    public function getLocationType()
    {
        return $this->location_typeid;
    }

    /**
     * Get the [location_name] column value.
     *
     * @return string
     */
    public function getLocationName()
    {
        return $this->location_name;
    }

    /**
     * Get the [location_address] column value.
     *
     * @return string
     */
    public function getLocationAddress()
    {
        return $this->location_address;
    }

    /**
     * Get the [location_city] column value.
     *
     * @return string
     */
    public function getLocationCity()
    {
        return $this->location_city;
    }

    /**
     * Get the [location_state] column value.
     *
     * @return string
     */
    public function getLocationState()
    {
        return $this->location_state;
    }

    /**
     * Get the [location_zip] column value.
     *
     * @return string
     */
    public function getLocationZip()
    {
        return $this->location_zip;
    }

    /**
     * Get the [location_country] column value.
     *
     * @return string
     */
    public function getLocationCountry()
    {
        return $this->location_country;
    }

    /**
     * Get the [location_phone] column value.
     *
     * @return string
     */
    public function getLocationPhone()
    {
        return $this->location_phone;
    }

    /**
     * Get the [location_email] column value.
     *
     * @return string
     */
    public function getLocationEmail()
    {
        return $this->location_email;
    }

    /**
     * Get the [location_timzezone] column value.
     *
     * @return string
     */
    public function getLocationTimzezone()
    {
        return $this->location_timzezone;
    }

    /**
     * Set the value of [location_id] column.
     *
     * @param int $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->location_id !== $v) {
            $this->location_id = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_ID] = true;
        }

        return $this;
    } // setLocationId()

    /**
     * Set the value of [location_typeid] column.
     *
     * @param int $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationType($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->location_typeid !== $v) {
            $this->location_typeid = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_TYPEID] = true;
        }

        return $this;
    } // setLocationType()

    /**
     * Set the value of [location_name] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_name !== $v) {
            $this->location_name = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_NAME] = true;
        }

        return $this;
    } // setLocationName()

    /**
     * Set the value of [location_address] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_address !== $v) {
            $this->location_address = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_ADDRESS] = true;
        }

        return $this;
    } // setLocationAddress()

    /**
     * Set the value of [location_city] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_city !== $v) {
            $this->location_city = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_CITY] = true;
        }

        return $this;
    } // setLocationCity()

    /**
     * Set the value of [location_state] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationState($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_state !== $v) {
            $this->location_state = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_STATE] = true;
        }

        return $this;
    } // setLocationState()

    /**
     * Set the value of [location_zip] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationZip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_zip !== $v) {
            $this->location_zip = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_ZIP] = true;
        }

        return $this;
    } // setLocationZip()

    /**
     * Set the value of [location_country] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_country !== $v) {
            $this->location_country = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_COUNTRY] = true;
        }

        return $this;
    } // setLocationCountry()

    /**
     * Set the value of [location_phone] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_phone !== $v) {
            $this->location_phone = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_PHONE] = true;
        }

        return $this;
    } // setLocationPhone()

    /**
     * Set the value of [location_email] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_email !== $v) {
            $this->location_email = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_EMAIL] = true;
        }

        return $this;
    } // setLocationEmail()

    /**
     * Set the value of [location_timzezone] column.
     *
     * @param string $v new value
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function setLocationTimzezone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location_timzezone !== $v) {
            $this->location_timzezone = $v;
            $this->modifiedColumns[LocationTableMap::COL_LOCATION_TIMZEZONE] = true;
        }

        return $this;
    } // setLocationTimzezone()

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
            if ($this->location_name !== '') {
                return false;
            }

            if ($this->location_address !== '') {
                return false;
            }

            if ($this->location_city !== '') {
                return false;
            }

            if ($this->location_state !== '') {
                return false;
            }

            if ($this->location_zip !== '') {
                return false;
            }

            if ($this->location_country !== '') {
                return false;
            }

            if ($this->location_phone !== '') {
                return false;
            }

            if ($this->location_email !== '') {
                return false;
            }

            if ($this->location_timzezone !== '') {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LocationTableMap::translateFieldName('LocationId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LocationTableMap::translateFieldName('LocationType', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_typeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LocationTableMap::translateFieldName('LocationName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LocationTableMap::translateFieldName('LocationAddress', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LocationTableMap::translateFieldName('LocationCity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LocationTableMap::translateFieldName('LocationState', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_state = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LocationTableMap::translateFieldName('LocationZip', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_zip = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LocationTableMap::translateFieldName('LocationCountry', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LocationTableMap::translateFieldName('LocationPhone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : LocationTableMap::translateFieldName('LocationEmail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : LocationTableMap::translateFieldName('LocationTimzezone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location_timzezone = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = LocationTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ChurchCRM\\Location'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(LocationTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLocationQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collEvents = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Location::setDeleted()
     * @see Location::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLocationQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(LocationTableMap::DATABASE_NAME);
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
                LocationTableMap::addInstanceToPool($this);
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
                    \ChurchCRM\EventQuery::create()
                        ->filterByPrimaryKeys($this->eventsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventsScheduledForDeletion = null;
                }
            }

            if ($this->collEvents !== null) {
                foreach ($this->collEvents as $referrerFK) {
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'location_id';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_TYPEID)) {
            $modifiedColumns[':p' . $index++]  = 'location_typeID';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'location_name';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'location_address';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'location_city';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_STATE)) {
            $modifiedColumns[':p' . $index++]  = 'location_state';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_ZIP)) {
            $modifiedColumns[':p' . $index++]  = 'location_zip';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'location_country';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'location_phone';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'location_email';
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_TIMZEZONE)) {
            $modifiedColumns[':p' . $index++]  = 'location_timzezone';
        }

        $sql = sprintf(
            'INSERT INTO locations (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'location_id':
                        $stmt->bindValue($identifier, $this->location_id, PDO::PARAM_INT);
                        break;
                    case 'location_typeID':
                        $stmt->bindValue($identifier, $this->location_typeid, PDO::PARAM_INT);
                        break;
                    case 'location_name':
                        $stmt->bindValue($identifier, $this->location_name, PDO::PARAM_STR);
                        break;
                    case 'location_address':
                        $stmt->bindValue($identifier, $this->location_address, PDO::PARAM_STR);
                        break;
                    case 'location_city':
                        $stmt->bindValue($identifier, $this->location_city, PDO::PARAM_STR);
                        break;
                    case 'location_state':
                        $stmt->bindValue($identifier, $this->location_state, PDO::PARAM_STR);
                        break;
                    case 'location_zip':
                        $stmt->bindValue($identifier, $this->location_zip, PDO::PARAM_STR);
                        break;
                    case 'location_country':
                        $stmt->bindValue($identifier, $this->location_country, PDO::PARAM_STR);
                        break;
                    case 'location_phone':
                        $stmt->bindValue($identifier, $this->location_phone, PDO::PARAM_STR);
                        break;
                    case 'location_email':
                        $stmt->bindValue($identifier, $this->location_email, PDO::PARAM_STR);
                        break;
                    case 'location_timzezone':
                        $stmt->bindValue($identifier, $this->location_timzezone, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

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
        $pos = LocationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getLocationId();
                break;
            case 1:
                return $this->getLocationType();
                break;
            case 2:
                return $this->getLocationName();
                break;
            case 3:
                return $this->getLocationAddress();
                break;
            case 4:
                return $this->getLocationCity();
                break;
            case 5:
                return $this->getLocationState();
                break;
            case 6:
                return $this->getLocationZip();
                break;
            case 7:
                return $this->getLocationCountry();
                break;
            case 8:
                return $this->getLocationPhone();
                break;
            case 9:
                return $this->getLocationEmail();
                break;
            case 10:
                return $this->getLocationTimzezone();
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

        if (isset($alreadyDumpedObjects['Location'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Location'][$this->hashCode()] = true;
        $keys = LocationTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getLocationId(),
            $keys[1] => $this->getLocationType(),
            $keys[2] => $this->getLocationName(),
            $keys[3] => $this->getLocationAddress(),
            $keys[4] => $this->getLocationCity(),
            $keys[5] => $this->getLocationState(),
            $keys[6] => $this->getLocationZip(),
            $keys[7] => $this->getLocationCountry(),
            $keys[8] => $this->getLocationPhone(),
            $keys[9] => $this->getLocationEmail(),
            $keys[10] => $this->getLocationTimzezone(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collEvents) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'events';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events_events';
                        break;
                    default:
                        $key = 'Events';
                }

                $result[$key] = $this->collEvents->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\ChurchCRM\Location
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LocationTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\ChurchCRM\Location
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setLocationId($value);
                break;
            case 1:
                $this->setLocationType($value);
                break;
            case 2:
                $this->setLocationName($value);
                break;
            case 3:
                $this->setLocationAddress($value);
                break;
            case 4:
                $this->setLocationCity($value);
                break;
            case 5:
                $this->setLocationState($value);
                break;
            case 6:
                $this->setLocationZip($value);
                break;
            case 7:
                $this->setLocationCountry($value);
                break;
            case 8:
                $this->setLocationPhone($value);
                break;
            case 9:
                $this->setLocationEmail($value);
                break;
            case 10:
                $this->setLocationTimzezone($value);
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
        $keys = LocationTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setLocationId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setLocationType($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLocationName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setLocationAddress($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLocationCity($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLocationState($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setLocationZip($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setLocationCountry($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setLocationPhone($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setLocationEmail($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setLocationTimzezone($arr[$keys[10]]);
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
     * @return $this|\ChurchCRM\Location The current object, for fluid interface
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
        $criteria = new Criteria(LocationTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_ID)) {
            $criteria->add(LocationTableMap::COL_LOCATION_ID, $this->location_id);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_TYPEID)) {
            $criteria->add(LocationTableMap::COL_LOCATION_TYPEID, $this->location_typeid);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_NAME)) {
            $criteria->add(LocationTableMap::COL_LOCATION_NAME, $this->location_name);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_ADDRESS)) {
            $criteria->add(LocationTableMap::COL_LOCATION_ADDRESS, $this->location_address);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_CITY)) {
            $criteria->add(LocationTableMap::COL_LOCATION_CITY, $this->location_city);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_STATE)) {
            $criteria->add(LocationTableMap::COL_LOCATION_STATE, $this->location_state);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_ZIP)) {
            $criteria->add(LocationTableMap::COL_LOCATION_ZIP, $this->location_zip);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_COUNTRY)) {
            $criteria->add(LocationTableMap::COL_LOCATION_COUNTRY, $this->location_country);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_PHONE)) {
            $criteria->add(LocationTableMap::COL_LOCATION_PHONE, $this->location_phone);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_EMAIL)) {
            $criteria->add(LocationTableMap::COL_LOCATION_EMAIL, $this->location_email);
        }
        if ($this->isColumnModified(LocationTableMap::COL_LOCATION_TIMZEZONE)) {
            $criteria->add(LocationTableMap::COL_LOCATION_TIMZEZONE, $this->location_timzezone);
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
        $criteria = ChildLocationQuery::create();
        $criteria->add(LocationTableMap::COL_LOCATION_ID, $this->location_id);

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
        $validPk = null !== $this->getLocationId();

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
        return $this->getLocationId();
    }

    /**
     * Generic method to set the primary key (location_id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setLocationId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getLocationId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \ChurchCRM\Location (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLocationId($this->getLocationId());
        $copyObj->setLocationType($this->getLocationType());
        $copyObj->setLocationName($this->getLocationName());
        $copyObj->setLocationAddress($this->getLocationAddress());
        $copyObj->setLocationCity($this->getLocationCity());
        $copyObj->setLocationState($this->getLocationState());
        $copyObj->setLocationZip($this->getLocationZip());
        $copyObj->setLocationCountry($this->getLocationCountry());
        $copyObj->setLocationPhone($this->getLocationPhone());
        $copyObj->setLocationEmail($this->getLocationEmail());
        $copyObj->setLocationTimzezone($this->getLocationTimzezone());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getEvents() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEvent($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return \ChurchCRM\Location Clone of current object.
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
        if ('Event' == $relationName) {
            $this->initEvents();
            return;
        }
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
     * Reset is the collEvents collection loaded partially.
     */
    public function resetPartialEvents($v = true)
    {
        $this->collEventsPartial = $v;
    }

    /**
     * Initializes the collEvents collection.
     *
     * By default this just sets the collEvents collection to an empty array (like clearcollEvents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEvents($overrideExisting = true)
    {
        if (null !== $this->collEvents && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventTableMap::getTableMap()->getCollectionClassName();

        $this->collEvents = new $collectionClassName;
        $this->collEvents->setModel('\ChurchCRM\Event');
    }

    /**
     * Gets an array of ChildEvent objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLocation is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     * @throws PropelException
     */
    public function getEvents(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEvents) {
                // return empty collection
                $this->initEvents();
            } else {
                $collEvents = ChildEventQuery::create(null, $criteria)
                    ->filterByLocation($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventsPartial && count($collEvents)) {
                        $this->initEvents(false);

                        foreach ($collEvents as $obj) {
                            if (false == $this->collEvents->contains($obj)) {
                                $this->collEvents->append($obj);
                            }
                        }

                        $this->collEventsPartial = true;
                    }

                    return $collEvents;
                }

                if ($partial && $this->collEvents) {
                    foreach ($this->collEvents as $obj) {
                        if ($obj->isNew()) {
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
     * Sets a collection of ChildEvent objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $events A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function setEvents(Collection $events, ConnectionInterface $con = null)
    {
        /** @var ChildEvent[] $eventsToDelete */
        $eventsToDelete = $this->getEvents(new Criteria(), $con)->diff($events);


        $this->eventsScheduledForDeletion = $eventsToDelete;

        foreach ($eventsToDelete as $eventRemoved) {
            $eventRemoved->setLocation(null);
        }

        $this->collEvents = null;
        foreach ($events as $event) {
            $this->addEvent($event);
        }

        $this->collEvents = $events;
        $this->collEventsPartial = false;

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
    public function countEvents(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPartial && !$this->isNew();
        if (null === $this->collEvents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEvents) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEvents());
            }

            $query = ChildEventQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLocation($this)
                ->count($con);
        }

        return count($this->collEvents);
    }

    /**
     * Method called to associate a ChildEvent object to this object
     * through the ChildEvent foreign key attribute.
     *
     * @param  ChildEvent $l ChildEvent
     * @return $this|\ChurchCRM\Location The current object (for fluent API support)
     */
    public function addEvent(ChildEvent $l)
    {
        if ($this->collEvents === null) {
            $this->initEvents();
            $this->collEventsPartial = true;
        }

        if (!$this->collEvents->contains($l)) {
            $this->doAddEvent($l);

            if ($this->eventsScheduledForDeletion and $this->eventsScheduledForDeletion->contains($l)) {
                $this->eventsScheduledForDeletion->remove($this->eventsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEvent $event The ChildEvent object to add.
     */
    protected function doAddEvent(ChildEvent $event)
    {
        $this->collEvents[]= $event;
        $event->setLocation($this);
    }

    /**
     * @param  ChildEvent $event The ChildEvent object to remove.
     * @return $this|ChildLocation The current object (for fluent API support)
     */
    public function removeEvent(ChildEvent $event)
    {
        if ($this->getEvents()->contains($event)) {
            $pos = $this->collEvents->search($event);
            $this->collEvents->remove($pos);
            if (null === $this->eventsScheduledForDeletion) {
                $this->eventsScheduledForDeletion = clone $this->collEvents;
                $this->eventsScheduledForDeletion->clear();
            }
            $this->eventsScheduledForDeletion[]= clone $event;
            $event->setLocation(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Location is new, it will return
     * an empty collection; or if this Location has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Location.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventsJoinEventType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('EventType', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Location is new, it will return
     * an empty collection; or if this Location has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Location.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventsJoinPersonRelatedByType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('PersonRelatedByType', $joinBehavior);

        return $this->getEvents($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Location is new, it will return
     * an empty collection; or if this Location has previously
     * been saved, it will retrieve related Events from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Location.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEvent[] List of ChildEvent objects
     */
    public function getEventsJoinPersonRelatedBySecondaryContactPersonId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventQuery::create(null, $criteria);
        $query->joinWith('PersonRelatedBySecondaryContactPersonId', $joinBehavior);

        return $this->getEvents($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->location_id = null;
        $this->location_typeid = null;
        $this->location_name = null;
        $this->location_address = null;
        $this->location_city = null;
        $this->location_state = null;
        $this->location_zip = null;
        $this->location_country = null;
        $this->location_phone = null;
        $this->location_email = null;
        $this->location_timzezone = null;
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
            if ($this->collEvents) {
                foreach ($this->collEvents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collEvents = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LocationTableMap::DEFAULT_STRING_FORMAT);
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
