<?php namespace App\Console;

use Illuminate\Console\Command;

class SendEmailTest extends Command {

	protected $signature = "envia:emailtest";

	protected $description = "Send a test email";

	public function __construct(){
		parent::__construct();
	}

	public function handle(){
		var_dump("This is a email test");
	}

}
