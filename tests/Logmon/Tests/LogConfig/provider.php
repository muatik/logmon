<?php
	$fm = array(
			'data' => array(
				'raw' => array('unique' => 4, 'date'=> '2013-12-15 16:12:10', 'type' => 'warning', 'message' => 'This is a nice log entry.'),
				'mapped' => array('unique' => 4, 'date'=> '2013-12-15 16:12:10', 'type' => 'warning', 'message' => 'This is a nice log entry.')
			),
			'mapping' => (object) array(
				'unique' => (object) array('fieldName'=>'id', 'regex' => '(.*)'),
				'type' => (object) array('fieldName'=>'type', 'regex' => '(.*)'),
				'date' => (object) array('fieldName'=>'date', 'regex' => '(.*)'),
				'message' => (object) array('fieldName'=>'message', 'regex' => '(.*)')
			)
		);
		$fm = array(
			'data' => array(
				'raw' => array('line' => '4 2013-12-15 16:12:10 warning This is a nice log entry.'),
				'mapped' => array('unique' => '4', 'date'=> '2013-12-15 16:12:10', 'type' => 'warning', 'message' => 'This is a nice log entry.')
			),
			'mapping' => (object) array(
				'unique' => (object) array('fieldName'=>'line', 'regex' => '(^\d+)'),
				'date' => (object) array('fieldName'=>'line', 'regex' => '^\d+ (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})'),
				'type' => (object) array('fieldName'=>'line', 'regex' => '^\d+ \d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ([a-z]+)'),
				'message' => (object) array('fieldName'=>'line', 'regex' => '^\d+ \d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} [a-z]+ (.+)')
			)
		);
return $fm;
