<?php

include dirname(__FILE__).'/../../../../../../test/bootstrap/unit.php';

$t = new lime_test();

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
$databaseManager = new sfDatabaseManager($configuration);

fsCronTaskTable::removeAllTasks();
$allTasks = fsCronTaskTable::getTasks()->toArray();
$t->is_deeply($allTasks, array(), 'Removing all tasks should make getTasks() return an empty array');


$task = fsCronTask::create('Test Task', 'echo "This is a test"');
$task->save();


$allTasks = fsCronTaskTable::getTasks()->toArray();
$t->is($allTasks, true, 'Saving a task should work');
$t->is(count($allTasks), 1, 'There should only be one task there');

$t->is(fsCronTaskTable::existsByName('Test Task'), true, 'Finding the added task by name works.');
$t->is(fsCronTaskTable::existsByName('Nonexistant Task'), false, 'Finding a nonexistant task should fail.');