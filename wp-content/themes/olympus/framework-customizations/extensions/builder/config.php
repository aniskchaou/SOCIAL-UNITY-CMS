<?php if (!defined('FW')) die('Forbidden');

$cfg = array();

$cfg['grid.columns'] = array(
	'1_6' => array(
		'title'          => '1/6',
		'backend_class'  => 'fw-col-sm-2',
		'frontend_class' => 'col-xs-12 col-md-2 col-lg-2',
	),
	'1_5' => array(
		'title'          => '1/5',
		'backend_class'  => 'fw-col-sm-15',
		'frontend_class' => 'col-xs-12 col-sm-6 col-md-15 col-lg-15',
	),
	'1_4' => array(
		'title'          => '1/4',
		'backend_class'  => 'fw-col-sm-3',
		'frontend_class' => 'col-xs-12 col-sm-12 col-md-6 col-lg-3',
	),
	'1_3' => array(
		'title'          => '1/3',
		'backend_class'  => 'fw-col-sm-4',
		'frontend_class' => 'col-xs-12 col-sm-12 col-md-4 col-lg-4',
	),
	'1_2' => array(
		'title'          => '1/2',
		'backend_class'  => 'fw-col-sm-6',
		'frontend_class' => 'col-xs-12 col-sm-12 col-md-6 col-lg-6',
	),
	'2_3' => array(
		'title'          => '2/3',
		'backend_class'  => 'fw-col-sm-8',
		'frontend_class' => 'col-xs-12 col-sm-12 col-md-8 col-lg-8',
	),
	'3_4' => array(
		'title'          => '3/4',
		'backend_class'  => 'fw-col-sm-9',
		'frontend_class' => 'col-xs-12 col-md-12 col-lg-9',
	),
	'1_1' => array(
		'title'          => '1/1',
		'backend_class'  => 'fw-col-sm-12',
		'frontend_class' => 'col-lg-12',
	),
);
/**
 * @since 1.2.0
 */
$cfg['grid.row.class'] = 'row';
