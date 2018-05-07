<?php
//
//  ZZZClient.php
//  
//	Copyright 2017 ZZZ Ltd. - Bulgaria
//
//	Licensed under the Apache License, Version 2.0 (the "License");
//	you may not use this file except in compliance with the License.
//	You may obtain a copy of the License at
//
//	http://www.apache.org/licenses/LICENSE-2.0
//
//	Unless required by applicable law or agreed to in writing, software
//	distributed under the License is distributed on an "AS IS" BASIS,
//	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//	See the License for the specific language governing permissions and
//	limitations under the License.
//
	class Socket
	{
		var $m_host  = "";
		var $m_port  = 0;
		var $m_sock;
		var $buf = "";

		function Socket($host, $port)
		{
			$this->m_host = $host;
			$this->m_port = $port;
		}

		function Connect()
		{
			$this->m_sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			return socket_connect($this->m_sock, gethostbyname($this->m_host), $this->m_port);
		}

		function send($data)
		{
			if(socket_write($this->m_sock, $data) == false)
			{
				printf("** failed to send(%s) **\n");
				return;
			}
		}

		function read($length = 2048)
		{
			return socket_read($this->m_sock, $length);
		}

		function kill()
		{
			socket_close($this->m_sock);
		}

		function print($s)
		{
			$this->send($s);
		}

		function read()
		{
			$response = '';
			while($resp = socket_read($this->m_sock, 1024)) {
				if(!$resp)
					break;
				$response .= $resp;
			}
			return $response;
		}
	}

	$host = "localhost";
	$port = 3333;
	function ZZZProgram($program) {
		global $host;
		global $port;
		$result="";
		$Connection = new Socket($host, $port);
		if($Connection->Connect()){
			$Connection->print($program + '\0');
			$result = $Connection->read();

			$Connection->kill();
		}
		return $result;
	}
	echo ZZZProgram("#[cout;[Hello world from ZZZServer!]]");
?>
