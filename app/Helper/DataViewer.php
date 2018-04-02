<?php 
namespace admin360\Helper;
use Validator;
use admin360\Scopes\SearchPaginateAndOrder;

trait DataViewer{
	
	public static function bootDataViewer(){
		static::addGlobalScope(new SearchPaginateAndOrder);
	}
}
 ?>	
