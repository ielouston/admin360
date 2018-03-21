<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ProductController extends Controller
{
 	public function mainTable(){
 		return view('products.main');
 	}   

 	public function getGallery(){
 		return view('products.gallery');
 	}
}
