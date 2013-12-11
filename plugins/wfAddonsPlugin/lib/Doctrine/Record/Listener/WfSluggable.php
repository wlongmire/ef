<?php
/**
 * Originally copied from: Doctrine_Template_Listener_Sluggable
 * Modified to always check for unique values, fix bug regarding strtolower, and support updates of slugs
 */
class Doctrine_Template_Listener_WfSluggable extends Doctrine_Record_Listener
{
    /**
     * Array of sluggable options
     *
     * @var string
     */
    protected $_options = array();

    /**
     * __construct
     *
     * @param string $array
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Set the slug value automatically when a record is inserted
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preInsert(Doctrine_Event $event)
    {
        $record = $event->getInvoker();
        $name = $record->getTable()->getFieldName($this->_options['name']);

        if ( ! $record->$name) {
            $record->$name = $this->buildSlugFromFields($record);
        }

        if ($this->_options['unique'] === true) {
          $record->$name = $this->getUniqueSlug($record, $record->$name);
        }
    }

    /**
     * Set the slug value automatically when a record is updated if the options are configured
     * to allow it
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preUpdate(Doctrine_Event $event)
    {
        $record = $event->getInvoker();
        $name = $record->getTable()->getFieldName($this->_options['name']);
        $initialSlug = $record->$name;

        if ( ! $record->$name || (
            $this->_options['update'] &&
            ! array_key_exists($name, $record->getModified())
        )) {
            $record->$name = $this->buildSlugFromFields($record);
        } else if ( ! empty($record->$name) &&
            $this->_options['update'] &&
            array_key_exists($name, $record->getModified()
        )) {
            $record->$name = $this->buildSlugFromSlugField($record);
        }

        if ($this->_options['unique'] === true && $initialSlug != $record->$name) { //if the name didn't change, we can assume it is still unique
          $record->$name = $this->getUniqueSlug($record, $record->$name);
        }
    }

    /**
     * Generate the slug for a given Doctrine_Record based on the configured options
     *
     * @param Doctrine_Record $record
     * @return string $slug
     */
    protected function buildSlugFromFields($record)
    {
        if (empty($this->_options['fields'])) {
            if (is_callable($this->_options['provider'])) {
            	$value = call_user_func($this->_options['provider'], $record);
            } else if (method_exists($record, 'getUniqueSlug')) {
                $value = $record->getUniqueSlug($record);
            } else {
                $value = (string) $record;
            }
        } else {
            $value = '';
            foreach ($this->_options['fields'] as $field) {
                $value .= $record->$field . ' ';
            }
        }

        return call_user_func_array($this->_options['builder'], array($value, $record));
    }

    /**
     * Generate the slug for a given Doctrine_Record slug field
     *
     * @param Doctrine_Record $record
     * @return string $slug
     */
    protected function buildSlugFromSlugField($record)
    {
        $name = $record->getTable()->getFieldName($this->_options['name']);
        $value = $record->$name;

        return call_user_func_array($this->_options['builder'], array($value, $record));
    }

    /**
     * Creates a unique slug for a given Doctrine_Record. This function enforces the uniqueness by
     * incrementing the values with a postfix if the slug is not unique
     *
     * @param Doctrine_Record $record
     * @param string $slugFromFields
     * @return string $slug
     */
    public function getUniqueSlug($record, $slugFromFields)
    {
        $name = $record->getTable()->getFieldName($this->_options['name']);
        $proposal =  call_user_func_array($this->_options['builder'], array($slugFromFields, $record));
        $slug = $proposal;

        $whereString = 'r.' . $name . ' LIKE ?';
        $whereParams = array($proposal.'%');

        if ($record->exists()) {
            $identifier = $record->identifier();
            $whereString .= ' AND r.' . implode(' != ? AND r.', $record->getTable()->getIdentifierColumnNames()) . ' != ?';
            $whereParams = array_merge($whereParams, array_values($identifier));
        }

        foreach ($this->_options['uniqueBy'] as $uniqueBy) {
            if (is_null($record->$uniqueBy)) {
                $whereString .= ' AND r.'.$uniqueBy.' IS NULL';
            } else {
                $whereString .= ' AND r.'.$uniqueBy.' = ?';
                $value = $record->$uniqueBy;
                if ($value instanceof Doctrine_Record) {
                    $value = current((array) $value->identifier());
                }
                $whereParams[] =  $value;
            }
        }

        // Disable indexby to ensure we get all records
        $originalIndexBy = $record->getTable()->getBoundQueryPart('indexBy');
        $record->getTable()->bindQueryPart('indexBy', null);

        $query = $record->getTable()
            ->createQuery('r')
            ->select('r.' . $name)
            ->where($whereString , $whereParams)
            ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);

        // We need to introspect SoftDelete to check if we are not disabling unique records too
        if ($record->getTable()->hasTemplate('Doctrine_Template_SoftDelete')) {
	        $softDelete = $record->getTable()->getTemplate('Doctrine_Template_SoftDelete');

	        // we have to consider both situations here
            if ($softDelete->getOption('type') == 'boolean') {
                $conn = $query->getConnection();

                $query->addWhere(
                    '(r.' . $softDelete->getOption('name') . ' = ' . $conn->convertBooleans(true) .
                    ' OR r.' . $softDelete->getOption('name') . ' = ' . $conn->convertBooleans(false) . ')'
                );
            } else {
                $query->addWhere('(r.' . $softDelete->getOption('name') . ' IS NOT NULL OR r.' . $softDelete->getOption('name') . ' IS NULL)');
            }
        }

        $similarSlugResult = $query->execute();
        $query->free();

        // Change indexby back
        $record->getTable()->bindQueryPart('indexBy', $originalIndexBy);

        $similarSlugs = array();
        foreach ($similarSlugResult as $key => $value) {
            $similarSlugs[$key] = strtolower($value[$name]);
        }

        $i = 1;
        while (in_array(strtolower($slug), $similarSlugs)) {
            $slug = call_user_func_array($this->_options['builder'], array($proposal.'-'.$i, $record));
            $i++;
        }

        // If slug is longer then the column length then we need to trim it
        // and try to generate a unique slug again
        $length = $record->getTable()->getFieldLength($this->_options['name']);
        if (strlen($slug) > $length) {
            $slug = substr($slug, 0, $length - (strlen($i) + 1));
            $slug = $this->getUniqueSlug($record, $slug);
        }

        return  $slug;
    }
}