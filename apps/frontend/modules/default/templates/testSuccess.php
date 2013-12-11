<pre>
<?php
$venues = Doctrine_Query::create()
   ->from('Venue vn')
   ->innerJoin('vn.VenueTypes vt')
   ->where('vt.id = ?', 1)
   ->execute();

print_r($venues->toArray());
 ?>
</pre>