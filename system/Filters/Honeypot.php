<?php namespace CodeIgniter\Filters;

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014-2019 British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    CodeIgniter
 * @author     CodeIgniter Dev Team
 * @copyright  2014-2019 British Columbia Institute of Technology (https://bcit.ca/)
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://codeigniter.com
 * @since      Version 3.0.0
 * @filesource
 */

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use CodeIgniter\Honeypot\Exceptions\HoneypotException;

class Honeypot implements FilterInterface
{

	/**
	 * Checks if Honeypot field is empty; if not
	 * then the requester is a bot
	 *
	 * @param \CodeIgniter\HTTP\RequestInterface $request
	 *
	 * @return mixed
	 */
	public function before(RequestInterface $request)
	{
		$honeypot = Services::honeypot(new \Config\Honeypot());
		if ($honeypot->hasContent($request))
		{
			throw HoneypotException::isBot();
		}
	}

	/**
	 * Attach a honypot to the current response.
	 *
	 * @param  \CodeIgniter\HTTP\RequestInterface  $request
	 * @param  \CodeIgniter\HTTP\ResponseInterface $response
	 * @return mixed
	 */
	public function after(RequestInterface $request, ResponseInterface $response)
	{
		$honeypot = Services::honeypot(new \Config\Honeypot());
		$honeypot->attachHoneypot($response);
	}

}
