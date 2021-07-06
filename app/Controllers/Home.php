<?php

namespace App\Controllers;

class Home extends BaseController
{

	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
	}

	public function index()
	{
		return view('welcome_message');
	}
}
