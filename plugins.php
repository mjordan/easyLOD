<?php
$plugins = array(
  // Configuration for URIs using the 'foo' namespace.
  'foo' => array(
      'plugin' => 'foaf',
      'dataSourceConfig' => array(
        'input_file' => 'data_sources/foaf/people_foo.csv',
      ),
  ),
  // Configuration for URIs using the 'bar' namespace.
  'bar' => array(
      'plugin' => 'foaf',
      'dataSourceConfig' => array(
        'input_file' => 'data_sources/foaf/people.csv',
      ),
  ),
);
?>
