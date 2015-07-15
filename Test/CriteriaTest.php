<?php

use Criteria\Test\GoogleCriteria;
use Criteria\Test\AnalyticsCriteriaFormatter;


require dirname(dirname(__FILE__)) .'/vendor/autoload.php';


class CriteriaTest extends PHPUnit_Framework_TestCase{


	public function testInit()
	{

		$ga = new GoogleCriteria;

		$ga->add("customDimensions", function( $c, $p ){

			$c->add( $p->make( "dimension1", "ga:dimension1", "ipAddress" ) );

		});


		$this->assertEquals( $ga["customDimensions"]["ipAddress"], "ga:dimension1" );
		
		$this->assertEquals( 
			 	$ga->get( "customDimensions" )
			 	   ->get( "ipAddress" )
			 	   ->getValue(),
			 	   "ga:dimension1"
			 	);

		return $ga;

	}

	/**
	* @depends testInit
	**/

	public function testFunctions( $ga )
	{

		$ga->by("date","month","year", "ipAddress");


		$this->assertEquals( $ga['dimensions']["date"], "ga:date" );


		$this->assertEquals( $ga['dimensions']["ipAddress"], "ga:dimension1" );


		$ga->between( date("Y-m-d"), date("Y-m-d") );


		$this->assertEquals( $ga["timeframe"]["start"], date("Y-m-d") );


		$this->assertEquals( $ga["timeframe"]["end"], date("Y-m-d") );


		$ga->orderBy("sessions desc", "year asc");

		$this->assertEquals( $ga["sort"]["sessions"], "-ga:sessions" );


		$ga->with("metric1","metric2","metric3");

		$ga->site("54321");

		return $ga;

	}

	/**
	* @depends testFunctions
	**/

	public function testFormatter( $ga )
	{

		$ga->setFormatter( new AnalyticsCriteriaFormatter );


	}




}
