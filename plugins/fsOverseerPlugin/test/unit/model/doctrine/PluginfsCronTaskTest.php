<?php

include dirname(__FILE__).'/../../../../../../test/bootstrap/unit.php';

$t = new lime_test(21);

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);
$databaseManager = new sfDatabaseManager($configuration);


$task = fsCronTask::create('Test Task', 'echo "This is a test"');
$task2 = fsCronTask::create('Test Task with Freq', 'echo "This is a second test"', '+1 second');

$t->isa_ok($task, 'fsCronTask', 'create() returns an object');
$t->isa_ok($task2, 'fsCronTask');

$now = new DateTime();
$later = new DateTime('+3 weeks');
$ago = new DateTime('-2 days');

$t->is($task->shouldRun($now), true, 'Task without set frequency runs');
$t->is($task2->shouldRun($now), true, 'Task with set frequency runs');

$t->info('Setting tasks as just ran');
$task->justRan($now);
$task2->justRan($now);

$t->is($task->shouldRun($now), false, 'Task without frequency should never run again.');
$t->is($task2->shouldRun($now), false, 'Task with frequency should not run until later.');
$t->is($task->shouldRun($later), false, 'Task without frequency should still not be running');
$t->is($task2->shouldRun($later), true, 'Task with frequency should now run.');

$task2->next_run = $ago->format('Y-m-d H:i:s');
$t->is($task2->shouldRun($now), true, 'Task should run if next run is in the past.');

$task2->enabled = false;
$t->is($task2->shouldRun($now), false, 'Disabled tasks should not run.');



$t->info('Validating frequencies (good)');
$goodFrequencies = array('+1 day', '+5 min', '+2 years');
foreach ($goodFrequencies as $good)
{
  $t->is($task->validateFrequency($good, false), true, $good);
}


$t->info('Validating frequencies (bad, return false)');
$badFrequencies = array('invalid', '-1 day', '+0 min', '+3 faketimes');
foreach ($badFrequencies as $bad)
{
  $t->is($task->validateFrequency($bad, false), false, $bad);
}

$t->info('Validating frequencies (bad, throw error)');
foreach ($badFrequencies as $bad)
{
  try
  {
    $task->validateFrequency($bad);
    $t->fail($bad . ' should have thrown an exception');
  }
  catch (Exception $e)
  {
    $t->pass($bad);
  }
}