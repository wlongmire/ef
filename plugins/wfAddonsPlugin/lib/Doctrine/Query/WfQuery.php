<?php

class Wf_Doctrine_Query extends Doctrine_Query
{
	/**
	 * This function will wrap the current dql where statement
	 * in parenthesis. This allows more complex dql statements
	 * It can be called multiple times during the creation of the dql
	 * where clause.
	 *
   * @see http://danielfamily.com/techblog/?p=37
	 * @return Wf_Doctrine_Query
	 */
	public function whereParenWrap() {
		$where = $this->_dqlParts['where'];
		if (count($where) > 0) {
			array_unshift($where, '(');
			array_push($where, ')');
			$this->_dqlParts['where'] = $where;
		}

		return $this;
	}

    /**
     * Get exists sql query for this Doctrine_Query instance.
     *
     * This method is used in Wf_Doctrine_Query::exists() for returning a boolean representing whether a record exist.
     *
     * @return string $q
     */
    public function getExistsSqlQuery()
    {
        // triggers dql parsing/processing
        $this->getSqlQuery(array(), false); // this is ugly

        // initialize temporary variables
        $where   = $this->_sqlParts['where'];
        $having  = $this->_sqlParts['having'];
        $groupby = $this->_sqlParts['groupby'];

        $rootAlias = $this->getRootAlias();
        $tableAlias = $this->getSqlTableAlias($rootAlias);

        // Build the query base
        $q = 'SELECT 1 FROM ';

        // Build the from clause
        $from = $this->_buildSqlFromPart(true);

        // Build the where clause
        $where = ( ! empty($where)) ? ' WHERE ' . implode(' ', $where) : '';

        // Build the group by clause
        $groupby = ( ! empty($groupby)) ? ' GROUP BY ' . implode(', ', $groupby) : '';

        // Build the having clause
        $having = ( ! empty($having)) ? ' HAVING ' . implode(' AND ', $having) : '';

        // Building the from clause and finishing query
        if (count($this->_queryComponents) == 1 && empty($having)) {
            $q .= $from . $where . $groupby . $having;
        } else {
            // Subselect fields will contain only the pk of root entity
            $ta = $this->_conn->quoteIdentifier($tableAlias);

            $map = $this->getRootDeclaration();
            $idColumnNames = $map['table']->getIdentifierColumnNames();

            $pkFields = $ta . '.' . implode(', ' . $ta . '.', $this->_conn->quoteMultipleIdentifier($idColumnNames));

            // We need to do some magic in select fields if the query contain anything in having clause
            $selectFields = $pkFields;

            if ( ! empty($having)) {
                // For each field defined in select clause
                foreach ($this->_sqlParts['select'] as $field) {
                    // We only include aggregate expressions to count query
                    // This is needed because HAVING clause will use field aliases
                    if (strpos($field, '(') !== false) {
                        $selectFields .= ', ' . $field;
                    }
                }
                // Add having fields that got stripped out of select
                preg_match_all('/`[a-z0-9_]+`\.`[a-z0-9_]+`/i', $having, $matches, PREG_PATTERN_ORDER);
                if (count($matches[0]) > 0) {
                    $selectFields .= ', ' . implode(', ', array_unique($matches[0]));
                }
            }

            // If we do not have a custom group by, apply the default one
            if (empty($groupby)) {
                $groupby = ' GROUP BY ' . $pkFields;
            }

            $q .= '(SELECT ' . $selectFields . ' FROM ' . $from . $where . $groupby . $having . ' LIMIT 1) '
                . $this->_conn->quoteIdentifier('dctrn_count_query');
        }

        return 'SELECT EXISTS(' . $q . ') AS does_exist';
    }
    
  /**
     * Fetches whether a record exists for a query
     *
     * This method executes the main query without all the
     * selected fields, ORDER BY part, and OFFSET part. The LIMIT is set to 1.
     *
     * Example:
     * Main query:
     *      SELECT u.*, p.phonenumber FROM User u
     *          LEFT JOIN u.Phonenumber p
     *          WHERE p.phonenumber = '123 123' LIMIT 10
     *
     * The resulting SQL query:
     *      SELECT EXISTS(SELECT 1 FROM User u
     *          LEFT JOIN u.Phonenumber p
     *          WHERE p.phonenumber = '123 123'
     *            LIMIT 1)
     *
     * @param array $params        an array of prepared statement parameters
     * @return boolean             true if a row exists, false otherwise
     */
    public function exists($params = array())
    {
        $q = $this->getExistsSqlQuery();
        $params = $this->getCountQueryParams($params);
        $params = $this->_conn->convertBooleans($params);

        if ($this->_resultCache) {
            $conn = $this->getConnection();
            $cacheDriver = $this->getResultCacheDriver();
            $hash = $this->getResultCacheHash($params) . '_limit';
            $cached = ($this->_expireResultCache) ? false : $cacheDriver->fetch($hash);

            if ($cached === false) {
                // cache miss
                $results = $this->getConnection()->fetchAll($q, $params);
                $cacheDriver->save($hash, serialize($results), $this->getResultCacheLifeSpan());
            } else {
                $results = unserialize($cached);
            }
        } else {
            $results = $this->getConnection()->fetchAll($q, $params);
        }

        return isset($results[0]) && isset($results[0]['does_exist']) ? (boolean)$results[0]['does_exist'] : false;
    }

  /**
   * @return string An attempt to return the bound SQL query
   */
  public function getBoundSqlQuery()
  {
    $sql = $this->getSqlQuery();
    $parameters = $this->getInternalParams();
    
    $keys = array();
    
    # build a regular expression for each parameter
    foreach ($parameters as $key => &$value)
    {
      if (is_string($key))
      {
        $keys[] = '/:'.$key.'/';
      }
      else
      {
        $keys[] = '/[?]/';
      }
      if (is_string($value))
      {
        $value = '"' . $value . '"';
      }
    }

    return preg_replace($keys, $parameters, $sql, 1, $count);
  }

  /**
   * Echo the bound SQL query
   * @return Wf_Doctrine_Query this
   */
  public function echoBoundSqlQuery()
  {
    echo $this->getBoundSqlQuery() . "\n";
    return $this;
  }

  /**
   * Returns rows where the date is in the same month as $date
   */
  public function addWhereInMonth(DateTime $date, $alias = null, $field = 'date')
  {
    if (!$alias)
    {
      $alias = $this->getRootAlias();
    }
    $format = 'Y-m-d';
    $firstDate = clone $date;
    $firstDate->modify('-' . ($date->format('j') - 1) . 'days');
    $lastDate = clone $firstDate;
    $lastDate->modify('+' . ($firstDate->format('t') - 1) . 'days');

    
    $this->addWhere(
      sprintf('%s.%s >= ? AND %s.%s <= ?',$alias,$field,$alias,$field),
      array($firstDate->format($format), $lastDate->format($format))
    );

    return $this;
  }

  public function removeQueryPart($part)
  {
    unset($this->_dqlParts[$part]);
    return $this;
  }
}