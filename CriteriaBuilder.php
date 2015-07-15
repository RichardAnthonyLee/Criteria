<?php namespace Criteria;


use RicAnthonyLee\Itemizer\ItemCollection;


class CriteriaBuilder extends ItemCollection{


	/**
	* add an item collection and it's items via callback
	* @return Criteria\CriteriaBuilder
	**/

	public function addParameter( $name, callable $callback )
	{

		$collection  = $this->has( $name ) ? $this->get( $collection ) : $this->factory()->make( $name );
		$itemFactory = $this->item();

		//allow user to add items to the collection using a callback

		call_user_func_array( $callback , [ $collection, $itemFactory ]  );

		$this->addItem( $collection );

		return $this;

	}


	/**
	* set the configuration callback/map for the __call magic method
	* @return void
	**/

	protected function __setCallbacks()
	{

		parent::__setCallbacks();
		$this->mergeCallbackMap([
			"add" => "addParameter"
		]);

	}



}