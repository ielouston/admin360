<?php 
namespace Admin360\Helper;
use Validator;
use Admin360\Scopes\SearchPaginateAndOrder;

trait DataViewer{
	
	public static function bootDataViewer(){
		static::addGlobalScope(new SearchPaginateAndOrder);
	}
}
 ?>	
