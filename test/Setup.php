<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

class Eve_Plugin_Htpasswd_Setup extends PHPUnit_Framework_TestCase
{
    public function testImport()
    {
        $callback = Eve\Plugin\Htpasswd\Setup::i()->import(array(
			'admin' => '123',
			'guest' => 'guest'
		));
		
		$this->assertTrue(is_callable($callback));
	}
}