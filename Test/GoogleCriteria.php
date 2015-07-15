<?php namespace Criteria\Test;


use Criteria\CriteriaBuilder;
use RicAnthonyLee\Itemizer\ItemFactory;
use RicAnthonyLee\Itemizer\ItemCollectionFactory;


class GoogleCriteria extends CriteriaBuilder{


	protected function __setCallbacks()
	{

		parent::__setCallbacks();

		$this->setItemFactory( new ItemFactory )
			 ->setFactory( new ItemCollectionFactory );

	}


	public function site( $id )
	{

		$site = $this->item()->make( 'site', "ga:".$id );

		$this->addItem( $site );
		return $this;

	}


	/**
	* Set the dimensions that we're using to search
	**/


	public function by()
	{

		$dimensions = func_get_args();
		$custom     = $this->get( "customDimensions" );

		$this->add( "dimensions", function( $criteria, $param ) use ($dimensions, $custom){


			foreach( $dimensions as $d )
			{

				if( $custom->has( $d ) )
				{

					$criteria->set( $custom->get( $d ) );

				}
				else 
				{

					$criteria->set( $param->make( $d, "ga:".$d ) );

				}

			}


		});

		return $this;

	}


	public function with()
	{

		$metrics = func_get_args();

		$this->add( "metrics", function( $criteria, $param ) use( $metrics ){


			foreach( $metrics as $m )
			{

				$criteria->add( $param->make( $m, "ga:".$m ) );

			}

		});

		return $this;

	}

	public function between( $from, $until )
	{

		$this->add( "timeframe", function( $criteria, $param ) use ( $from, $until ) {


			$criteria->add( $param->make( "start-date", $from, "start" ) );

			$criteria->add( $param->make( "end-date", $until, "end" ) );


		});

		return $this;

	}

	public function orderBy()
	{

		$order = func_get_args();

		$this->add( "sort", function( $criteria, $param ) use ( $order ) {

			foreach( $order as $o )
			{

				$o   = explode( " ", trim( $o ) );

				$key = $o[0];
				$ord = strtolower( $o[1] ) === "desc" ? "-" : "" ; 

				$criteria->add( $param->make( $key, $ord."ga:".$key ) );

			}

		});	

		return $this;	


	}


}