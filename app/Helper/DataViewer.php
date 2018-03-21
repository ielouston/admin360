<?php 
namespace App\Helper;
use Validator;
use App\Scopes\SearchPaginateAndOrder;

trait DataViewer{
	
	public static function bootDataViewer(){
		static::addGlobalScope(new SearchPaginateAndOrder);
	}
}
 ?>	
